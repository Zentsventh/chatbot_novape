<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->unsigned()->default(0.00);
            $table->string('currency', 3)->default('USD');
            $table->enum('billing_cycle', ['monthly', 'yearly'])->default('monthly');
            $table->smallInteger('max_agents')->unsigned()->default(1);
            $table->tinyInteger('max_channels')->unsigned()->default(1);
            $table->integer('message_limit_per_month')->unsigned()->default(1000);
            $table->enum('ai_engine_allowed', ['local', 'gemini', 'both'])->default('local');
            $table->boolean('has_crm')->default(false);
            $table->boolean('has_appointments')->default(false);
            $table->boolean('has_sequences')->default(false);
            $table->boolean('has_internal_chat')->default(true);
            $table->boolean('has_auto_assignment')->default(false);
            $table->json('features')->nullable();
            $table->tinyInteger('sort_order')->unsigned()->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_plan_id')->constrained('subscription_plans')->restrictOnDelete();
            $table->string('company_name', 150);
            $table->string('slug', 150)->unique();
            $table->string('legal_name', 200)->nullable();
            $table->string('tax_id', 30)->nullable();
            $table->string('contact_email', 255);
            $table->string('phone', 20)->nullable();
            $table->string('country', 3)->nullable();
            $table->string('timezone', 50)->default('America/Lima');
            $table->string('logo_url', 500)->nullable();
            $table->enum('status', ['trial', 'active', 'suspended', 'cancelled'])->default('trial');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('subscription_starts_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->integer('current_month_message_count')->unsigned()->default(0);
            $table->timestamp('message_count_reset_at')->nullable();
            $table->string('api_token', 100)->unique()->nullable();
            $table->json('settings')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('subscription_plans');
    }
};
