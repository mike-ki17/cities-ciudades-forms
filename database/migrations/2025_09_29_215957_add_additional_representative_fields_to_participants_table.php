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
            $table->string('representative_address')->nullable()->after('representative_authorization');
            $table->string('representative_phone')->nullable()->after('representative_address');
            $table->string('representative_email')->nullable()->after('representative_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn(['representative_address', 'representative_phone', 'representative_email']);
        });
    }
};
