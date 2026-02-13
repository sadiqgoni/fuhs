<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('annual_salary_increments', function (Blueprint $table) {
            $table->integer('arrears_months')->default(0)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('annual_salary_increments', function (Blueprint $table) {
            $table->dropColumn('arrears_months');
        });
    }
};
