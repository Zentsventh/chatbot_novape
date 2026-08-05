<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internal_chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name', 100)->nullable();
            $table->enum('type', ['direct', 'group'])->default('direct');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('internal_chat_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_chat_id')->constrained('internal_chats')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('role', ['member', 'admin'])->default('member');
            $table->timestamp('last_read_at')->nullable();
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();

            $table->unique(['internal_chat_id', 'user_id']);
        });

        Schema::create('internal_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('internal_chat_id')->constrained('internal_chats')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('content')->nullable();
            $table->enum('message_type', ['text', 'image', 'file', 'audio', 'call_started', 'call_ended'])->default('text');
            $table->string('media_url', 500)->nullable();
            $table->integer('call_duration_seconds')->unsigned()->nullable();
            $table->timestamps();

            $table->index(['internal_chat_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internal_messages');
        Schema::dropIfExists('internal_chat_participants');
        Schema::dropIfExists('internal_chats');
    }
};
