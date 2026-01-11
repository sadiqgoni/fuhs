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
        Schema::create('salary_deduction_templates', function (Blueprint $table) {
            $table->id();
            $table->integer('salary_structure_id');
            $table->integer('grade_level_from');
            $table->integer('grade_level_to');
            $table->integer('deduction_id');
            $table->integer('deduction_type');
            $table->double('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_deduction_templates');
    }
};
