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

            $table->foreignUuid('UserID')
                ->constrained(table: 'users', column: 'UserID') 
                ->cascadeOnDelete();

            $table->foreignUuid('OrganizationID')
                ->constrained(table: 'organizations', column: 'OrganizationID')
                ->cascadeOnDelete();

            $table->foreignUuid('ProposingOrganizationID')
                ->nullable()
                ->constrained(table: 'organizations', column: 'OrganizationID')
                ->nullOnDelete();

            $table->string('ProposalTitle');
            $table->text('Description'); 
            $table->string('ProposalStatus')->default('submitted');
            $table->date('StartDate')->nullable();
            $table->date('EndDate')->nullable();
            $table->timestamps();
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
