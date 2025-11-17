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
        Schema::create('squid_cases', function (Blueprint $table) {
            $table->id();

            // Terikat ke event mana soal ini
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');

            $table->string('title');
            $table->text('description');

            // Hadiah (sesuai V6: bisa ada, bisa tidak)
            $table->bigInteger('reward_dollar')->nullable();

            $table->timestamps();

            // Kunci V6: Asumsi 1 case per event
            $table->unique('event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('squid_cases');
    }
};
