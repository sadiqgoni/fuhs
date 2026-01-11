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
        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->id();
            $table->integer('employment_id')->nullable();
            $table->string('full_name');
            $table->integer('department');
            $table->integer('staff_category');
            $table->integer('employment_type');
            $table->string('staff_number');
            $table->string('payroll_number');
            $table->integer('status')->nullable();
            $table->integer('salary_structure');
            $table->date('date_of_first_appointment')->nullable();
            $table->date('date_of_last_appointment')->nullable();
            $table->string('post_held')->nullable();
            $table->integer('grade_level');
            $table->string('step');
            $table->string('rank');
            $table->integer('unit');
            $table->string('phone_number');
            $table->string('whatsapp_number')->nullable();
            $table->string('email')->nullable();

            $table->string('bank_name');
            $table->integer('account_number');
            $table->integer('bank_code');

            $table->string('pfa_name');
            $table->string('pension_pin');
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('religion')->nullable();
            $table->string('tribe')->nullable();
            $table->string('marital_status')->nullable();
            $table->integer('nationality')->nullable();
            $table->integer('state_of_origin')->nullable();
            $table->integer('local_government')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('name_of_next_of_kin')->nullable();
            $table->string('next_of_kin_phone_number')->nullable();
            $table->string('relationship')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_profiles');
    }
};
