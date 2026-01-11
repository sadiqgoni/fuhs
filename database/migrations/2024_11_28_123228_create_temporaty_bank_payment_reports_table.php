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
        Schema::create('temporaty_bank_payment_reports', function (Blueprint $table) {
            $table->id();
            $table->string('account_number');
            $table->string('amount');
            $table->string('bank');
            $table->string('branch')->nullable();
            $table->string('sort_code')->nullable();
            $table->string('remark');
            $table->string('staff_number');
            $table->string('staff_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporaty_bank_payment_reports');
    }
};
