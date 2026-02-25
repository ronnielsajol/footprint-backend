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
        Schema::create('pol_deployments', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->string('exact_venue');
            $table->string('lgu')->nullable();
            $table->string('barangay')->nullable();
            $table->string('region')->nullable();
            $table->string('district')->nullable();
            $table->string('province')->nullable();
            $table->integer('deployment_month'); // 1-12
            $table->integer('deployment_year'); // YYYY
            $table->date('turnover_date')->nullable();
            $table->string('pol_officer')->nullable();
            $table->string('category')->nullable();
            $table->enum('asc_type', ['virtual', 'actual'])->nullable();
            $table->string('llc')->nullable();
            $table->string('psc')->nullable();
            $table->string('proponent')->nullable();
            $table->string('sector_recipient')->nullable();
            $table->integer('count')->nullable();
            $table->string('unit')->nullable();
            $table->text('donation_summary')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->enum('source', ['TESDA', 'DSWD-AICS', 'DOLE-DILP', 'DOLE-TUPAD', 'DOH-MAIFIP', 'Private'])->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('created_by');
            $table->index('deployment_year');
            $table->index('deployment_month');
            $table->index(['deployment_year', 'deployment_month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pol_deployments');
    }
};
