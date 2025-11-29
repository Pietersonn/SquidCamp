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
            $table->foreignId('event_id')->constrained()->onDelete('cascade');

            // Rename to standard Laravel convention: {name}_type and {name}_id
            $table->string('from_type');
            $table->unsignedBigInteger('from_id');

            $table->string('to_type');
            $table->unsignedBigInteger('to_id');

            $table->bigInteger('amount');
            $table->string('reason'); // e.g., 'GROUP_TRANSFER', 'CHALLENGE_REWARD'
            $table->string('description')->nullable(); // Tambahan untuk catatan

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
