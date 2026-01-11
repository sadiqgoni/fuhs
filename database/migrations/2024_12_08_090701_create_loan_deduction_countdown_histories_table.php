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
        Schema::create('loan_deduction_countdown_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->integer('payment_month');
            $table->integer('payment_year');
            $table->integer('amount_paid');
            $table->integer('countdown_value');
            $table->integer('outstanding_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_deduction_countdown_histories');
    }
};
