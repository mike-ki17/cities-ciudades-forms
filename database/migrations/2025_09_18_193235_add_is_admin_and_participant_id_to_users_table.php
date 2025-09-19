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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('email_verified_at');
            $table->unsignedBigInteger('participant_id')->nullable()->after('is_admin');
            
            $table->index('is_admin');
            $table->index('participant_id');
            
            // Foreign key constraint will be added after the participant table is created
            // $table->foreign('participant_id')->references('id')->on('participant')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['participant_id']);
            $table->dropIndex(['participant_id']);
            $table->dropIndex(['is_admin']);
            $table->dropColumn(['is_admin', 'participant_id']);
        });
    }
};