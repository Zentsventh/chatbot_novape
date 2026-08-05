<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name', 150)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('messenger_id', 100)->nullable();
            $table->string('instagram_id', 100)->nullable();
            $table->string('email')->nullable();
            $table->string('company', 150)->nullable();
            $table->string('profile_picture_url', 500)->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_blocked')->default(false);
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('first_interaction_at')->nullable();
            $table->timestamp('last_interaction_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'phone_number']);
            $table->index(['tenant_id', 'messenger_id']);
            $table->index(['tenant_id', 'instagram_id']);
            $table->index(['tenant_id', 'last_interaction_at']);
            $table->index(['tenant_id', 'assigned_user_id']);
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name', 50);
            $table->string('slug', 50);
            $table->string('color', 7)->default('#6B7280');
            $table->timestamps();

            $table->unique(['tenant_id', 'slug']);
        });

        Schema::create('taggables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->string('taggable_type', 100);
            $table->unsignedBigInteger('taggable_id');
            $table->timestamps();

            $table->index(['taggable_type', 'taggable_id']);
            $table->unique(['tag_id', 'taggable_type', 'taggable_id']);
        });

        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('contact_id')->constrained('contacts')->cascadeOnDelete();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->decimal('value', 12, 2)->unsigned()->nullable();
            $table->string('currency', 3)->default('USD');
            $table->enum('stage', ['lead', 'prospect', 'proposal', 'negotiation', 'won', 'lost'])->default('lead');
            $table->tinyInteger('probability')->unsigned()->nullable();
            $table->date('expected_close_date')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->string('lost_reason', 500)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'stage']);
        });

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('contact_id')->constrained('contacts')->cascadeOnDelete();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->timestamp('scheduled_at');
            $table->smallInteger('duration_minutes')->unsigned()->default(30);
            $table->enum('status', ['scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->string('location', 300)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancel_reason', 500)->nullable();
            $table->enum('source', ['manual', 'chatbot', 'api'])->default('manual');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'scheduled_at']);
            $table->index(['assigned_user_id', 'scheduled_at']);
            $table->index(['tenant_id', 'status', 'scheduled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('deals');
        Schema::dropIfExists('taggables');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('contacts');
    }
};
