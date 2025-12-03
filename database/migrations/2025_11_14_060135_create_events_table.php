<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('banner_image_path')->nullable();
            $table->string('instansi')->nullable(); // Tambahan kolom instansi jika belum ada

            $table->date('event_date')->nullable();

            // Timer Phases
            $table->timestamp('challenge_start_time')->nullable();
            $table->timestamp('challenge_end_time')->nullable();
            $table->timestamp('case_start_time')->nullable();
            $table->timestamp('case_end_time')->nullable();
            $table->timestamp('show_start_time')->nullable();
            $table->timestamp('show_end_time')->nullable();

            // Status
            $table->boolean('is_active')->default(false);
            $table->boolean('is_finished')->default(false);

            // --- SQUID BANK CENTRAL (SALDO ADMIN) ---
            $table->bigInteger('central_bank_balance')->default(0); // Cadangan Bank Admin
            $table->bigInteger('central_cash_balance')->default(0); // Uang Fisik Admin

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
