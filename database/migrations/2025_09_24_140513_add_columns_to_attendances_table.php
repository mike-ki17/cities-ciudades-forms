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
        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('participant_id')->after('id');
            $table->unsignedBigInteger('cycle_id')->after('participant_id');
            $table->boolean('attended')->default(false)->after('cycle_id');
            $table->timestamp('attended_at')->nullable()->after('attended');
            
            // Foreign key constraints
            $table->foreign('participant_id')->references('id')->on('participants')->onDelete('cascade');
            $table->foreign('cycle_id')->references('id')->on('cycles')->onDelete('cascade');
            
            // Indexes for better performance
            $table->index(['participant_id', 'cycle_id']);
            $table->index(['cycle_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['participant_id']);
            $table->dropForeign(['cycle_id']);
            $table->dropIndex(['participant_id', 'cycle_id']);
            $table->dropIndex(['cycle_id']);
            $table->dropColumn(['participant_id', 'cycle_id', 'attended', 'attended_at']);
        });
    }
};
