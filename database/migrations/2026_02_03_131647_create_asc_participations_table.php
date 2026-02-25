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
        Schema::create('asc_participations', function (Blueprint $table) {
            $table->id();
            $table->string('deployment_type'); // 'pol_deployment' or 'w_asc_deployment'
            $table->unsignedBigInteger('deployment_id');
            $table->text('participation_details');
            $table->integer('personnel_count')->nullable();
            $table->text('resources_deployed')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['deployment_type', 'deployment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asc_participations');
    }
};
