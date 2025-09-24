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
        Schema::table('fields_json', function (Blueprint $table) {
            $table->json('dynamic_options')->nullable()->after('visible');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fields_json', function (Blueprint $table) {
            $table->dropColumn('dynamic_options');
        });
    }
};
