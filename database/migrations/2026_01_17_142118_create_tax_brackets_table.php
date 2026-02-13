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
        Schema::create('tax_brackets', function (Blueprint $table) {
            $table->id();
            $table->string('version_name'); // e.g., "PAYE 2026 Structure"
            $table->date('effective_date');
            $table->boolean('is_active')->default(false); // Only one can be active
            $table->json('tax_brackets'); // Store bracket ranges and rates
            $table->json('reliefs')->nullable(); // Store tax relief configurations
            $table->text('description')->nullable(); // Optional description
            $table->timestamps();

            // Note: Uniqueness handled in model boot method
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_brackets');
    }
};
