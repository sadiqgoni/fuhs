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
        if (!Schema::hasTable('local_govts')) {
            Schema::create('local_govts', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->unsignedBigInteger('state_id');
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
                $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_govts');
    }
};
