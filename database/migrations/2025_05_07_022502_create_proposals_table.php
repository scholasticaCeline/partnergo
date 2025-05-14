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
        Schema::create('proposals', function (Blueprint $table) {
            $table->uuid('ProposalID')->primary();
            $table->uuid('UserID');
            $table->uuid('OrganizationID');
            $table->string('ProposalTitle', 255);
            $table->string('ProposalStatus', 10);
            $table->date('StartDate');
            $table->date('EndDate');
        
            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('OrganizationID')->references('OrganizationID')->on('organizations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
