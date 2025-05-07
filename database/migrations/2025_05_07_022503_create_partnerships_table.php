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
        Schema::create('partnerships', function (Blueprint $table) {
            $table->string('PartnershipID', 5)->primary();
            $table->string('OrganizationSenderID', 5);
            $table->string('OrganizationTargetID', 5);
            $table->string('PartnershipTypeID', 5);
            $table->string('ProposalID', 5);
            $table->string('Status', 255);
            $table->date('CreatedAt');
            $table->date('StartDate')->nullable();
            $table->date('EndDate')->nullable();

            $table->foreign('OrganizationSenderID')->references('OrganizationID')->on('organizations');
            $table->foreign('OrganizationTargetID')->references('OrganizationID')->on('organizations');
            $table->foreign('PartnershipTypeID')->references('PartnershipTypeID')->on('partnership_types');
            $table->foreign('ProposalID')->references('ProposalID')->on('proposals');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partnerships');
    }
};
