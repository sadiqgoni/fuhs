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
        Schema::create('salary_updates', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->double('basic_salary');
            $table->double('A1')->nullable()->default(0.00);
            $table->double('A2')->nullable()->default(0.00);
            $table->double('A3')->nullable()->default(0.00);
            $table->double('A4')->nullable()->default(0.00);
            $table->double('A5')->nullable()->default(0.00);
            $table->double('A6')->nullable()->default(0.00);
            $table->double('A7')->nullable()->default(0.00);
            $table->double('A8')->nullable()->default(0.00);
            $table->double('A9')->nullable()->default(0.00);
            $table->double('A10')->nullable()->default(0.00);
            $table->double('A11')->nullable()->default(0.00);
            $table->double('A12')->nullable()->default(0.00);
            $table->double('A13')->nullable()->default(0.00);
            $table->double('A14')->nullable()->default(0.00);
            $table->double('D1')->nullable()->default(0.00);
            $table->double('D2')->nullable()->default(0.00);
            $table->double('D3')->nullable()->default(0.00);
            $table->double('D4')->nullable()->default(0.00);
            $table->double('D5')->nullable()->default(0.00);
            $table->double('D6')->nullable()->default(0.00);
            $table->double('D7')->nullable()->default(0.00);
            $table->double('D8')->nullable()->default(0.00);
            $table->double('D9')->nullable()->default(0.00);
            $table->double('D10')->nullable()->default(0.00);
            $table->double('D11')->nullable()->default(0.00);
            $table->double('D12')->nullable()->default(0.00);
            $table->double('D13')->nullable()->default(0.00);
            $table->double('D14')->nullable()->default(0.00);
            $table->double('D15')->nullable()->default(0.00);
            $table->double('D16')->nullable()->default(0.00);
            $table->double('D17')->nullable()->default(0.00);
            $table->double('D18')->nullable()->default(0.00);
            $table->double('D19')->nullable()->default(0.00);
            $table->double('D20')->nullable()->default(0.00);
            $table->double('D21')->nullable()->default(0.00);
            $table->double('D22')->nullable()->default(0.00);
            $table->double('D23')->nullable()->default(0.00);
            $table->double('D24')->nullable()->default(0.00);
            $table->double('D25')->nullable()->default(0.00);
            $table->double('D26')->nullable()->default(0.00);
            $table->double('D27')->nullable()->default(0.00);
            $table->double('D28')->nullable()->default(0.00);
            $table->double('D29')->nullable()->default(0.00);
            $table->double('D30')->nullable()->default(0.00);
            $table->double('D31')->nullable()->default(0.00);
            $table->double('D32')->nullable()->default(0.00);
            $table->double('D33')->nullable()->default(0.00);
            $table->double('D34')->nullable()->default(0.00);
            $table->double('D35')->nullable()->default(0.00);
            $table->double('D36')->nullable()->default(0.00);
            $table->double('D37')->nullable()->default(0.00);
            $table->double('D38')->nullable()->default(0.00);
            $table->double('D39')->nullable()->default(0.00);
            $table->double('D40')->nullable()->default(0.00);
            $table->double('D41')->nullable()->default(0.00);
            $table->double('D42')->nullable()->default(0.00);
            $table->double('D43')->nullable()->default(0.00);
            $table->double('D44')->nullable()->default(0.00);
            $table->double('D45')->nullable()->default(0.00);
            $table->double('D46')->nullable()->default(0.00);
            $table->double('D47')->nullable()->default(0.00);
            $table->double('D48')->nullable()->default(0.00);
            $table->double('D49')->nullable()->default(0.00);
            $table->double('D50')->nullable()->default(0.00);
            $table->double('salary_areas')->nullable()->default(0.00);
            $table->double('gross_pay')->nullable()->default(0.00);
            $table->double('total_deduction')->nullable()->default(0.00);
            $table->double('net_pay')->nullable()->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_updates');
    }
};
