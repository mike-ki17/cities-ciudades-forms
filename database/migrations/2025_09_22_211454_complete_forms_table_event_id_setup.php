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
        Schema::table('forms', function (Blueprint $table) {
            // Add foreign key constraint for event_id
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            
            // Add new index for better performance
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
            
            // Drop the new index
            $table->dropIndex(['event_id', 'is_active']);
            
            // Add back the old index
            $table->index(['is_active']);
        });
    }
};
