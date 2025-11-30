<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('challenge_submissions', function (Blueprint $table) {
            $table->id();
            // Relasi ke Event, Group, dan Challenge
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->foreignId('challenge_id')->constrained('challenges')->onDelete('cascade');

            // User yang melakukan submit (bisa null saat baru diambil Captain)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            // Jawaban
            $table->text('submission_text')->nullable(); // Link Gdrive/Canva
            $table->string('file_path')->nullable(); // File upload langsung

            // Status: 'active' (lagi dikerjakan), 'pending' (menunggu review), 'approved', 'rejected'
            $table->enum('status', ['active', 'pending', 'approved', 'rejected'])->default('active');

            // Feedback Mentor
            $table->text('mentor_feedback')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('challenge_submissions');
    }
};
