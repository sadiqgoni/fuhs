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
        Schema::create('loan_deduction_countdowns', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->integer('deduction_id');
            $table->double('total_amount');
            $table->double('installment_amount');
            $table->string('start_month');
            $table->year('start_year');
            $table->date('month_year');
            $table->integer('no_of_installment');
            $table->integer('remaining_installment');
            $table->date('current_salary_month');
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_deduction_countdowns');
    }
};
