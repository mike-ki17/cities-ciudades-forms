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
            // Eliminar campos de padres si existen
            $table->dropColumn(['parent_name', 'parent_document_type', 'parent_document_number']);
            
            // Agregar campos del representante legal
            $table->string('representative_name')->nullable()->after('birth_date');
            $table->string('representative_document_type')->nullable()->after('representative_name');
            $table->string('representative_document_number')->nullable()->after('representative_document_type');
            $table->boolean('representative_authorization')->default(false)->after('representative_document_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            // Eliminar campos del representante legal
            $table->dropColumn(['representative_name', 'representative_document_type', 'representative_document_number', 'representative_authorization']);
            
            // Restaurar campos de padres
            $table->string('parent_name')->nullable()->after('birth_date');
            $table->string('parent_document_type')->nullable()->after('parent_name');
            $table->string('parent_document_number')->nullable()->after('parent_document_type');
        });
    }
};
