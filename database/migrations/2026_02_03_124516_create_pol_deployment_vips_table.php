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
    Schema::create('pol_deployment_vips', function (Blueprint $table) {
      $table->id();
      $table->foreignId('pol_deployment_id')->constrained('pol_deployments')->cascadeOnDelete();
      $table->foreignId('vip_id')->constrained('vips')->cascadeOnDelete();
      $table->text('remarks')->nullable();
      $table->timestamps();

      $table->unique(['pol_deployment_id', 'vip_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('pol_deployment_vips');
  }
};
