<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventChallengesTable extends Migration
{
    public function up()
    {
        Schema::create('event_challenges', function (Blueprint $table) {
            $table->id();

            // Relasi ke event
            $table->foreignId('event_id')
                ->constrained('events')
                ->onDelete('cascade');

            // Relasi ke challenge
            $table->foreignId('challenge_id')
                ->constrained('challenges')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_challenges');
    }
}
