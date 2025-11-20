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
    Schema::create('guidelines', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->text('description')->nullable();
      $table->bigInteger('price')->default(0);

      // File PDF
      $table->string('pdf_path')->nullable(); // contoh: guidelines/guideline1.pdf

      $table->timestamps();
    });
  }



  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('guidelines');
  }
};
