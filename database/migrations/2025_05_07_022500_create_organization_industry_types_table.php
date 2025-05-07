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
        Schema::create('organization_industry_types', function (Blueprint $table) {
            $table->string('OrganizationIndustryID', 5)->primary();
            $table->string('OrganizationID', 5);
            $table->string('IndustryTypeID', 5);

            $table->foreign('OrganizationID')->references('OrganizationID')->on('organizations');
            $table->foreign('IndustryTypeID')->references('IndustryTypeID')->on('industry_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_industry_types');
    }
};
