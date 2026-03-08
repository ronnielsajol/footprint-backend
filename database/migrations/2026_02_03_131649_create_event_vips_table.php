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
    Schema::create('event_vips', function (Blueprint $table) {
      $table->id();
      $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
      $table->foreignId('vip_id')->constrained('vips')->onDelete('cascade');
      $table->text('remarks')->nullable();
      $table->timestamps();

      // Prevent duplicate entries
      $table->unique(['event_id', 'vip_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('event_vips');
  }
};
