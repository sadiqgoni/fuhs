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
        Schema::create('staff_promotions', function (Blueprint $table) {
            $table->id();
            $table->string('payroll_number');
            $table->string('salary_structure');
            $table->integer('level');
            $table->integer('step');
            $table->integer('staff_number');
            $table->integer('staff_name');
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_promotions');
    }
};
