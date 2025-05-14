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
            $table->uuid('PartnershipID')->primary();
            $table->uuid('OrganizationSenderID');
            $table->uuid('OrganizationTargetID');
            $table->uuid('PartnershipTypeID');
            $table->uuid('ProposalID');
            $table->string('Status', 255);
            $table->date('CreatedAt');
            $table->date('StartDate')->nullable();
            $table->date('EndDate')->nullable();
        
            $table->foreign('OrganizationSenderID')->references('OrganizationID')->on('organizations')->onDelete('cascade');
            $table->foreign('OrganizationTargetID')->references('OrganizationID')->on('organizations')->onDelete('cascade');
            $table->foreign('PartnershipTypeID')->references('PartnershipTypeID')->on('partnership_types')->onDelete('cascade');
            $table->foreign('ProposalID')->references('ProposalID')->on('proposals')->onDelete('cascade');
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
