<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('step_allowance_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('salary_structure_id');
            $table->unsignedInteger('grade_level');
            $table->unsignedInteger('step');
            $table->unsignedInteger('allowance_id');
            $table->decimal('value', 15, 2);
            $table->timestamps();

            $table->index(['salary_structure_id', 'grade_level', 'step', 'allowance_id'], 'step_allowance_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('step_allowance_templates');
    }
};

