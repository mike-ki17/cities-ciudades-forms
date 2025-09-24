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
        Schema::table('form_categories', function (Blueprint $table) {
            $table->string('code')->unique()->after('id');
            $table->string('name')->after('code');
            $table->text('description')->nullable()->after('name');
            $table->boolean('is_active')->default(true)->after('description');
            
            // Indexes for better performance
            $table->index(['code']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_categories', function (Blueprint $table) {
            $table->dropIndex(['code']);
            $table->dropIndex(['is_active']);
            $table->dropColumn(['code', 'name', 'description', 'is_active']);
        });
    }
};
