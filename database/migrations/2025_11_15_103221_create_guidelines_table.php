<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guidelines', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->bigInteger('price')->default(0); // Menyimpan harga angka murni
            $table->string('file_pdf')->nullable();  // Path file PDF
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guidelines');
    }
};
