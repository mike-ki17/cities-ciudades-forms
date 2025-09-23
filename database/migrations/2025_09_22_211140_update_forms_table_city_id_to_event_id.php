<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            // Drop the existing foreign key constraint (it references cities table)
            $table->dropForeign(['event_id']);
            
            // Add foreign key constraint for event_id (now references events table)
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['event_id']);
            
            // Recreate the old foreign key constraint (references cities table)
            $table->foreign('event_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }
};
