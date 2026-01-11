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
        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string( 'name')->nullable();
            $table->integer( 'total_jobs')->nullable();
            $table->integer( 'pending_jobs')->nullable();
            $table->integer( 'failed_jobs')->nullable();
            $table->longText( 'failed_job_ids')->nullable();
            $table->mediumText( 'options')->nullable();
            $table->timestamp( 'created_at')->nullable();
            $table->timestamp( 'cancelled_at')->nullable();
            $table->timestamp( 'finished_at')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_batches');
    }
};
