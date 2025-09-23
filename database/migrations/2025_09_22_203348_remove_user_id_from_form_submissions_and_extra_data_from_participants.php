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
        // Remove user_id from form_submissions table
        Schema::table('form_submissions', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['user_id']);
            
            // Drop index that includes user_id
            $table->dropIndex(['form_id', 'user_id']);
            
            // Drop the column
            $table->dropColumn('user_id');
        });

        // Remove extra_data from participants table
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn('extra_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back user_id to form_submissions table
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('participant_id');
            
            // Recreate foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Recreate index
            $table->index(['form_id', 'user_id']);
        });

        // Add back extra_data to participants table
        Schema::table('participants', function (Blueprint $table) {
            $table->json('extra_data')->nullable()->after('birth_date');
        });
    }
};