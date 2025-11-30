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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('banner_image_path')->nullable();

            // Hanya tanggal event
            $table->date('event_date')->nullable();

            // 3 Fase Timer (Challenge, Case, Show)
            $table->timestamp('challenge_start_time')->nullable();
            $table->timestamp('challenge_end_time')->nullable();

            $table->timestamp('case_start_time')->nullable();
            $table->timestamp('case_end_time')->nullable();

            $table->timestamp('show_start_time')->nullable();
            $table->timestamp('show_end_time')->nullable();

            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
