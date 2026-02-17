<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('annual_salary_increments', function (Blueprint $table) {
            if (!Schema::hasColumn('annual_salary_increments', 'current_salary')) {
                $table->decimal('current_salary', 12, 2)->nullable()->after('status');
            }
            if (!Schema::hasColumn('annual_salary_increments', 'new_salary')) {
                $table->decimal('new_salary', 12, 2)->nullable()->after('current_salary');
            }
        });
    }

    public function down(): void
    {
        Schema::table('annual_salary_increments', function (Blueprint $table) {
            if (Schema::hasColumn('annual_salary_increments', 'current_salary')) {
                $table->dropColumn('current_salary');
            }
            if (Schema::hasColumn('annual_salary_increments', 'new_salary')) {
                $table->dropColumn('new_salary');
            }
        });
    }
};
