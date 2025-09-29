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
            $table->string('parent_name')->nullable()->after('birth_date');
            $table->string('parent_document_type')->nullable()->after('parent_name');
            $table->string('parent_document_number')->nullable()->after('parent_document_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn(['parent_name', 'parent_document_type', 'parent_document_number']);
        });
    }
};
