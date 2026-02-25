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
    Schema::create('w_asc_deployments', function (Blueprint $table) {
      $table->id();
      $table->string('exact_venue');
      $table->string('barangay')->nullable();
      $table->string('city_municipality')->nullable();
      $table->string('region')->nullable();
      $table->string('district')->nullable();
      $table->string('province')->nullable();
      $table->integer('deployment_month'); // 1-12
      $table->integer('deployment_year'); // YYYY
      $table->date('exact_date');
      $table->string('event_tagging')->nullable();
      $table->boolean('has_socials')->default(false);
      $table->boolean('has_sortie')->default(false);
      $table->boolean('asc_attended')->default(false);
      $table->boolean('llc_attended')->default(false);
      $table->boolean('psc_attended')->default(false);
      $table->json('pol_activities')->nullable(); // Array of activity strings
      $table->enum('sector', ['PTK', 'Kababaihan', 'MSMEs', 'Youth', 'BHW'])->nullable();
      $table->text('remarks')->nullable();
      $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
      $table->timestamps();
      $table->softDeletes();

      // Indexes
      $table->index('created_by');
      $table->index('deployment_year');
      $table->index('deployment_month');
      $table->index('exact_date');
      $table->index(['deployment_year', 'deployment_month']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('w_asc_deployments');
  }
};
