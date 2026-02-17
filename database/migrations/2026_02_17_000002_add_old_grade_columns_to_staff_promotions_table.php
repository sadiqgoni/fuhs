<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff_promotions', function (Blueprint $table) {
            if (!Schema::hasColumn('staff_promotions', 'old_grade_level')) {
                $table->integer('old_grade_level')->nullable()->after('step');
            }
            if (!Schema::hasColumn('staff_promotions', 'old_step')) {
                $table->integer('old_step')->nullable()->after('old_grade_level');
            }
            if (!Schema::hasColumn('staff_promotions', 'old_salary_structure')) {
                $table->string('old_salary_structure', 50)->nullable()->after('old_step');
            }
        });
    }

    public function down(): void
    {
        Schema::table('staff_promotions', function (Blueprint $table) {
            $table->dropColumn(['old_grade_level', 'old_step', 'old_salary_structure']);
        });
    }
};
