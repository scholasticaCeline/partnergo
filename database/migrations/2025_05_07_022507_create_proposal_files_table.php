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
        Schema::create('proposal_files', function (Blueprint $table) {
            $table->string('ProposalFileID', 5)->primary();
            $table->string('UploadedBy', 5);
            $table->string('ProposalID', 5);
            $table->string('Filename', 255);
            $table->string('Filepath', 255);
            $table->date('CreatedAt');

            $table->foreign('UploadedBy')->references('UserID')->on('users');
            $table->foreign('ProposalID')->references('ProposalID')->on('proposals');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_files');
    }
};
