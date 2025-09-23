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
        Schema::table('participants', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['event_id']);
            
            // Drop the index first
            $table->dropIndex(['event_id']);
            
            // Drop the event_id column
            $table->dropColumn('event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            // Add back the event_id column
            $table->unsignedBigInteger('event_id')->nullable();
            
            // Recreate foreign key constraint
            $table->foreign('event_id')->references('id')->on('events')->onDelete('set null');
            
            // Add index
            $table->index(['event_id']);
        });
    }
};
