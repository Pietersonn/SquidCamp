<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');

            // 1. PENGIRIM (Sumber Dana)
            // Bisa: 'group', 'investor' (user), 'system'
            $table->string('from_type')->nullable();
            $table->unsignedBigInteger('from_id')->nullable();

            // 2. PENERIMA (Tujuan Dana)
            // Bisa: 'group', 'system'
            $table->string('to_type')->nullable();
            $table->unsignedBigInteger('to_id')->nullable();

            // 3. JUMLAH & TIPE
            $table->bigInteger('amount');
            // Enum saran: 'INVESTMENT', 'TRANSFER', 'BUY_GUIDELINE', 'CHALLENGE_REWARD'
            $table->string('type');

            // 4. REFERENSI (BARU & PENTING!)
            // Ini untuk mencatat: Transaksi ini terkait benda apa?
            // Contoh: Jika BUY_GUIDELINE, maka reference_type='App\Models\Guideline', reference_id=5
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->text('note')->nullable(); // Catatan manual (opsional)
            $table->timestamps();

            // Indexing untuk performa query history
            $table->index(['event_id', 'from_type', 'from_id']);
            $table->index(['event_id', 'to_type', 'to_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
