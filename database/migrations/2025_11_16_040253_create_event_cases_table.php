<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_cases', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel events
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');

            // Relasi ke tabel master cases (menggunakan case_id)
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_cases');
    }
};
