<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversation_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('content');
            $table->json('mentioned_users')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();

            $table->index(['conversation_id', 'created_at']);
        });

        Schema::create('assignment_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->tinyInteger('priority')->unsigned()->default(0);
            $table->json('conditions');
            $table->enum('target_type', ['specific_agent', 'round_robin', 'least_busy', 'random'])->default('round_robin');
            $table->foreignId('target_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('fallback_action', ['queue', 'assign_admin', 'leave_unassigned'])->default('queue');
            $table->boolean('is_active')->default(true);
            $table->integer('times_triggered')->unsigned()->default(0);
            $table->timestamps();

            $table->index(['tenant_id', 'is_active', 'priority']);
        });

        Schema::create('conversation_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();
            $table->foreignId('assigned_from_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_to_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('assignment_type', ['auto', 'manual', 'transfer', 'escalation'])->default('manual');
            $table->foreignId('assignment_rule_id')->nullable()->constrained('assignment_rules')->nullOnDelete();
            $table->string('reason', 500)->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['conversation_id', 'created_at']);
            $table->index(['tenant_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversation_assignments');
        Schema::dropIfExists('assignment_rules');
        Schema::dropIfExists('conversation_notes');
    }
};
