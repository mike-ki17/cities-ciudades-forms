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
        // First, drop foreign key constraints that reference cities table
        Schema::table('forms', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
        });

        Schema::table('participants', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
        });

        // Rename the cities table to events
        Schema::rename('cities', 'events');

        // Modify the events table structure to match the new requirements
        Schema::table('events', function (Blueprint $table) {
            // Remove old columns
            $table->dropColumn(['timezone', 'extra_data', 'deleted_at']);
            
            // Add new columns
            $table->string('city')->after('name');
            $table->integer('year')->after('city');
            
            // Remove the unique constraint on name since we now have city and year
            $table->dropUnique('cities_name_unique');
            
            // Add new unique constraint on name + city + year combination
            $table->unique(['name', 'city', 'year']);
        });

        // Recreate foreign key constraints pointing to events table
        Schema::table('forms', function (Blueprint $table) {
            $table->foreign('city_id')->references('id')->on('events')->onDelete('cascade');
        });

        Schema::table('participants', function (Blueprint $table) {
            $table->foreign('city_id')->references('id')->on('events')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraints
        Schema::table('forms', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
        });

        Schema::table('participants', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
        });

        // Revert the events table structure
        Schema::table('events', function (Blueprint $table) {
            // Remove new columns
            $table->dropColumn(['city', 'year']);
            
            // Add back old columns
            $table->string('timezone')->nullable()->after('name');
            $table->json('extra_data')->nullable()->after('timezone');
            $table->softDeletes();
            
            // Remove the unique constraint on name + city + year
            $table->dropUnique('events_name_city_year_unique');
            
            // Add back unique constraint on name
            $table->unique('name');
        });

        // Rename the events table back to cities
        Schema::rename('events', 'cities');

        // Recreate foreign key constraints pointing to cities table
        Schema::table('forms', function (Blueprint $table) {
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });

        Schema::table('participants', function (Blueprint $table) {
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
        });
    }
};