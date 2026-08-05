<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sequences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->enum('channel', ['whatsapp', 'messenger', 'instagram', 'all'])->default('all');
            $table->enum('trigger_type', ['manual', 'new_contact', 'tag_added', 'inactivity', 'appointment_reminder'])->default('manual');
            $table->json('trigger_config')->nullable();
            $table->boolean('is_active')->default(false);
            $table->integer('total_enrolled')->unsigned()->default(0);
            $table->integer('total_completed')->unsigned()->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['tenant_id', 'is_active']);
        });

        Schema::create('sequence_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sequence_id')->constrained('sequences')->cascadeOnDelete();
            $table->tinyInteger('step_order')->unsigned();
            $table->integer('delay_minutes')->unsigned()->default(0);
            $table->text('message_content');
            $table->enum('message_type', ['text', 'image', 'template', 'interactive'])->default('text');
            $table->string('media_url', 500)->nullable();
            $table->json('condition')->nullable();
            $table->timestamps();

            $table->index(['sequence_id', 'step_order']);
        });

        Schema::create('sequence_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('sequence_id')->constrained('sequences')->cascadeOnDelete();
            $table->foreignId('contact_id')->constrained('contacts')->cascadeOnDelete();
            $table->tinyInteger('current_step')->unsigned()->default(1);
            $table->enum('status', ['active', 'paused', 'completed', 'cancelled', 'failed'])->default('active');
            $table->timestamp('next_step_at')->nullable();
            $table->timestamp('enrolled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancel_reason', 255)->nullable();
            $table->timestamps();

            $table->index(['sequence_id', 'status']);
            $table->index(['status', 'next_step_at']);
            $table->unique(['sequence_id', 'contact_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sequence_enrollments');
        Schema::dropIfExists('sequence_steps');
        Schema::dropIfExists('sequences');
    }
};
