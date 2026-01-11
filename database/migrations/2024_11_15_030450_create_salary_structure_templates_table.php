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
        Schema::create('salary_structure_templates', function (Blueprint $table) {
            $table->id();
            $table->integer('salary_structure_id');
            $table->integer('no_of_grade_steps');
            $table->double('step1')->nullable()->default(0.00);
            $table->double('step2')->nullable()->default(0.00);
            $table->double('step3')->nullable()->default(0.00);
            $table->double('step4')->nullable()->default(0.00);
            $table->double('step5')->nullable()->default(0.00);
            $table->double('step6')->nullable()->default(0.00);
            $table->double('step7')->nullable()->default(0.00);
            $table->double('step8')->nullable()->default(0.00);
            $table->double('step9')->nullable()->default(0.00);
            $table->double('step10')->nullable()->default(0.00);
            $table->double('step11')->nullable()->default(0.00);
            $table->double('step12')->nullable()->default(0.00);
            $table->double('step13')->nullable()->default(0.00);
            $table->double('step14')->nullable()->default(0.00);
            $table->double('step15')->nullable()->default(0.00);
            $table->double('step16')->nullable()->default(0.00);
            $table->double('step17')->nullable()->default(0.00);
            $table->double('step18')->nullable()->default(0.00);
            $table->double('step19')->nullable()->default(0.00);
            $table->double('step20')->nullable()->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_structure_templates');
    }
};
