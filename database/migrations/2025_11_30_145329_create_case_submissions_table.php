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
        Schema::create('case_submissions', function (Blueprint $table) {
            $table->id();

            // Relasi ke Event, Group, Case, dan User
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('group_id')->constrained()->onDelete('cascade');

            // Pastikan nama tabel referensi sesuai dengan migration 'cases' sebelumnya (biasanya 'cases')
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade');

            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User yang melakukan submit

            // Data Submisi
            $table->text('submission_text')->nullable(); // Link jawaban (misal GDrive/Canva)
            $table->string('file_path')->nullable(); // File upload (opsional)

            // Data Reward & Ranking (Diisi saat submit berhasil)
            $table->integer('rank')->nullable(); // Urutan ke berapa (1, 2, 3...)
            $table->bigInteger('reward_amount')->default(0); // Nominal uang yang didapat

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_submissions');
    }
};
