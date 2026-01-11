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
        Schema::create('annual_salary_increments', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->string('increment_month');
            $table->year('increment_year');
            $table->date('month_year');
            $table->integer('salary_structure');
            $table->integer('grade_level');
            $table->integer('old_grade_step');
            $table->integer('new_grade_step');
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_salary_increments');
    }
};
