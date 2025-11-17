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
        Schema::create('squid_challenges', function (Blueprint $table) {
            $table->id();
            // Terikat ke event mana soal ini
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');

            $table->string('title');
            $table->text('description');

            // Hadiah (sesuai V6: 700k, 500k, 300k)
            $table->bigInteger('reward_dollar');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('squid_challenges');
    }
};
