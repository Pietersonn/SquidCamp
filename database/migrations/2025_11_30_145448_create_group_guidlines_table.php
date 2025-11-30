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
        Schema::create('group_guidelines', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('guideline_id')->constrained()->onDelete('cascade');

            // Data Transaksi
            $table->bigInteger('price_paid'); // Harga saat guideline dibeli (untuk history)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_guidelines');
    }
};
