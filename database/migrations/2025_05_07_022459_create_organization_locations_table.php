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
        Schema::create('organization_locations', function (Blueprint $table) {
            $table->string('OrganizationLocationID', 5)->primary();
            $table->string('OrganizationID', 5);
            $table->string('LocationID', 5);

            $table->foreign('OrganizationID')->references('OrganizationID')->on('organizations');
            $table->foreign('LocationID')->references('LocationID')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_locations');
    }
};
