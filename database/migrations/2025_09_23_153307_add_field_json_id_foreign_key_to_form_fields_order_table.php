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
        Schema::table('form_fields_order', function (Blueprint $table) {
            // Add foreign key constraint for field_json_id
            $table->foreign('field_json_id')->references('id')->on('fields_json')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_fields_order', function (Blueprint $table) {
            $table->dropForeign(['field_json_id']);
        });
    }
};
