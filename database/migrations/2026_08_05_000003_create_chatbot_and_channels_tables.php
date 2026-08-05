<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_knowledges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->unique()->constrained('tenants')->cascadeOnDelete();
            $table->string('bot_name', 100)->default('Asistente Virtual');
            $table->text('system_prompt')->nullable();
            $table->json('business_hours')->nullable();
            $table->json('catalog')->nullable();
            $table->json('faqs')->nullable();
            $table->text('custom_instructions')->nullable();
            $table->text('welcome_message')->nullable();
            $table->text('out_of_hours_message')->nullable();
            $table->text('bot_paused_message')->nullable();
            $table->text('quota_exceeded_message')->nullable();
            $table->boolean('is_bot_active')->default(true);
            $table->tinyInteger('max_context_messages')->unsigned()->default(10);
            $table->decimal('ai_temperature', 2, 1)->default(0.7);
            $table->timestamps();
        });

        Schema::create('tenant_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->enum('channel', ['whatsapp', 'messenger', 'instagram']);
            $table->string('channel_name', 100)->nullable();
            $table->string('phone_number_id', 50)->nullable()->index();
            $table->string('whatsapp_business_id', 50)->nullable();
            $table->string('page_id', 50)->nullable()->index();
            $table->string('instagram_account_id', 50)->nullable()->index();
            $table->text('access_token')->nullable();
            $table->string('webhook_verify_token', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('last_webhook_at')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'channel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_channels');
        Schema::dropIfExists('chatbot_knowledges');
    }
};
