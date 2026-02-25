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
    Schema::create('w_asc_deployment_officers', function (Blueprint $table) {
      $table->id();
      $table->foreignId('w_asc_deployment_id')->constrained('w_asc_deployments')->cascadeOnDelete();
      $table->string('officer_name');
      $table->timestamps();

      $table->index('w_asc_deployment_id');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('w_asc_deployment_officers');
  }
};
