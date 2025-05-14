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
        Schema::create('messages', function (Blueprint $table) {
            $table->uuid('MessageID')->primary();
            $table->uuid('SenderID');
            $table->uuid('ReceiverID');
            $table->string('Content', 1000);
            $table->date('CreatedAt');
            $table->date('ReadAt')->nullable();

            $table->foreign('SenderID')->references('UserID')->on('users');
            $table->foreign('ReceiverID')->references('UserID')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
