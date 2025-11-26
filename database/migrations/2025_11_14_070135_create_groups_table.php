  <?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  return new class extends Migration
  {
      public function up(): void
      {
          Schema::create('groups', function (Blueprint $table) {
              $table->id();
              $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
              $table->string('name');
              // Ini adalah "Penugasan Mentor"
              $table->foreignId('mentor_id')->nullable()->constrained('users')->onDelete('set null');
              $table->foreignId('captain_id')->nullable()->constrained('users')->onDelete('set null');
              $table->foreignId('cocaptain_id')->nullable()->constrained('users')->onDelete('set null');
              $table->bigInteger('squid_dollar')->default(0);
              $table->timestamps();
          });
      }

      public function down(): void
      {
          Schema::dropIfExists('groups');
      }
  };
