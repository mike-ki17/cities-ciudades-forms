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
            // First, add the new event_id column (nullable initially)
            $table->unsignedBigInteger('event_id')->nullable()->after('id');
        });

        // Copy data from event_id to event_id (since event_id was referencing events table)
        DB::statement('UPDATE forms SET event_id = event_id WHERE event_id IS NOT NULL');

        Schema::table('forms', function (Blueprint $table) {
            // Make event_id not nullable
            $table->unsignedBigInteger('event_id')->nullable(false)->change();
            
            // Drop the existing foreign key constraint
            $table->dropForeign(['event_id']);
            
            // Drop the event_id column
            $table->dropColumn('event_id');
            
            // Add foreign key constraint for event_id
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            
            // Add index for better performance
            $table->index(['event_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            // Drop the new foreign key constraint
            $table->dropForeign(['event_id']);
            
            // Drop the event_id column
            $table->dropColumn('event_id');
            
            // Add back the event_id column
            $table->unsignedBigInteger('event_id')->nullable();
            
            // Recreate the old foreign key constraint
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            
            // Add back the old index
            $table->index(['event_id', 'is_active']);
        });
    }
};
