<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('knowledge_bases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g. "Catálogo.pdf" or "https://miweb.com"
            $table->enum('type', ['pdf', 'url', 'text'])->default('pdf');
            $table->longText('content')->nullable(); // Extracted text
            $table->json('metadata')->nullable(); // Size, processed info
            $table->string('status')->default('processed'); // processing, processed, error
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_bases');
    }
};
