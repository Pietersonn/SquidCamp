<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('name');

            // Penugasan Mentor & Captain
            $table->foreignId('mentor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('captain_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('cocaptain_id')->nullable()->constrained('users')->onDelete('set null');

            // KEUANGAN
            $table->bigInteger('squid_dollar')->default(0); // Uang Cash (Dompet)
            $table->bigInteger('bank_balance')->default(0); // Uang di Bank (Tabungan) - BARU

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
