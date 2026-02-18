<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix employee_profiles table
        Schema::table('employee_profiles', function (Blueprint $table) {
            $table->string('account_number')->change();
            $table->string('bank_code')->nullable()->change();
            // In case bvn or tax_id were added as integers manually or in missing migrations, 
            // we ensure they are strings if they exist.
            if (Schema::hasColumn('employee_profiles', 'bvn')) {
                $table->string('bvn')->nullable()->change();
            }
            if (Schema::hasColumn('employee_profiles', 'tax_id')) {
                $table->string('tax_id')->nullable()->change();
            }
        });

        // Fix staff_promotions table
        if (Schema::hasTable('staff_promotions')) {
            Schema::table('staff_promotions', function (Blueprint $table) {
                if (Schema::hasColumn('staff_promotions', 'staff_number')) {
                    $table->string('staff_number')->nullable()->change();
                }
                if (Schema::hasColumn('staff_promotions', 'staff_name')) {
                    $table->string('staff_name')->nullable()->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_profiles', function (Blueprint $table) {
            // Note: Reverting back to integer might fail if strings contain non-numeric data
            $table->integer('account_number')->change();
            $table->integer('bank_code')->change();
        });

        if (Schema::hasTable('staff_promotions')) {
            Schema::table('staff_promotions', function (Blueprint $table) {
                if (Schema::hasColumn('staff_promotions', 'staff_number')) {
                    $table->integer('staff_number')->change();
                }
                if (Schema::hasColumn('staff_promotions', 'staff_name')) {
                    $table->integer('staff_name')->change();
                }
            });
        }
    }
};
