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
        // This migration is no longer needed as the foreign key constraint
        // is already handled by the previous migration
        // The index already exists from the original table creation
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is no longer needed
    }
};
