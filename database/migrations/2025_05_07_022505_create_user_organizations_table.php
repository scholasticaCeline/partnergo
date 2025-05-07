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
        Schema::create('user_organizations', function (Blueprint $table) {
            $table->string('UserOrganizationID', 5)->primary();
            $table->string('UserID', 5);
            $table->string('OrganizationID', 5);
            $table->integer('IsAdmin');

            $table->foreign('UserID')->references('UserID')->on('users');
            $table->foreign('OrganizationID')->references('OrganizationID')->on('organizations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_organizations');
    }
};
