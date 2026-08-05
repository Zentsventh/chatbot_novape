<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('contact_id')->constrained('contacts')->cascadeOnDelete();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('channel', ['whatsapp', 'messenger', 'instagram']);
            $table->enum('status', ['open', 'bot_active', 'human_active', 'waiting', 'resolved', 'closed'])->default('bot_active');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->string('subject', 255)->nullable();
            $table->boolean('is_bot_paused')->default(false);
            $table->timestamp('bot_paused_at')->nullable();
            $table->foreignId('bot_paused_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('auto_assigned')->default(false);
            $table->unsignedBigInteger('assignment_rule_id')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->string('last_message_preview', 255)->nullable();
            $table->integer('message_count')->unsigned()->default(0);
            $table->integer('unread_count')->unsigned()->default(0);
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'channel']);
            $table->index(['tenant_id', 'last_message_at']);
            $table->index(['tenant_id', 'priority', 'status']);
            $table->index(['tenant_id', 'assigned_user_id', 'unread_count']);
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();
            $table->foreignId('contact_id')->constrained('contacts')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('channel', ['whatsapp', 'messenger', 'instagram']);
            $table->enum('direction', ['inbound', 'outbound']);
            $table->enum('message_type', ['text', 'image', 'audio', 'video', 'document', 'sticker', 'location', 'contact', 'template', 'interactive', 'reaction', 'unsupported'])->default('text');
            $table->text('content')->nullable();
            $table->string('media_url', 500)->nullable();
            $table->string('media_mime_type', 100)->nullable();
            $table->integer('media_file_size')->unsigned()->nullable();
            $table->boolean('is_ai_generated')->default(false);
            $table->enum('ai_engine_used', ['local', 'gemini'])->nullable();
            $table->integer('ai_tokens_used')->unsigned()->nullable()->default(0);
            $table->integer('ai_response_time_ms')->unsigned()->nullable();
            $table->boolean('is_internal_note')->default(false);
            $table->enum('status', ['queued', 'sent', 'delivered', 'read', 'failed', 'deleted'])->default('queued');
            $table->string('error_message', 500)->nullable();
            $table->string('external_message_id', 255)->nullable()->index();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'created_at']);
            $table->index(['conversation_id', 'created_at']);
            $table->index(['contact_id', 'created_at']);
            $table->index(['tenant_id', 'channel', 'created_at']);
            $table->index(['tenant_id', 'is_ai_generated', 'created_at']);
            $table->index(['tenant_id', 'direction', 'created_at']);
            $table->index(['conversation_id', 'is_internal_note']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
    }
};
