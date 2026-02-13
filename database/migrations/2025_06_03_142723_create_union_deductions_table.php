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
        if (!Schema::hasTable('union_deductions')) {
            Schema::create('union_deductions', function (Blueprint $table) {
                $table->id();
                $table->tinyInteger('deduction_id');
                $table->tinyInteger('union_id');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('union_deductions');
    }
};
