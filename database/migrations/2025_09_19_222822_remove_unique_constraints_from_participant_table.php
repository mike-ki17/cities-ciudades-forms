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
        // Only proceed if participant table exists
        if (Schema::hasTable('participant')) {
            Schema::table('participant', function (Blueprint $table) {
                // Remove unique constraints if they exist
                try {
                    $table->dropUnique('unique_participant_email');
                } catch (\Exception $e) {
                    // Constraint doesn't exist, ignore
                }
                
                try {
                    $table->dropUnique('unique_participant_document');
                } catch (\Exception $e) {
                    // Constraint doesn't exist, ignore
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participant', function (Blueprint $table) {
            // Restore unique constraints
            $table->unique('email', 'unique_participant_email');
            $table->unique(['document_type', 'document_number'], 'unique_participant_document');
        });
    }
};
