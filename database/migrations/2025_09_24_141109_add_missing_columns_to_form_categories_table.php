<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing records to have unique codes
        $categories = DB::table('form_categories')->get();
        foreach ($categories as $index => $category) {
            DB::table('form_categories')
                ->where('id', $category->id)
                ->update([
                    'code' => 'category_' . ($index + 1),
                    'name' => 'Categoría ' . ($index + 1),
                    'description' => 'Descripción de la categoría ' . ($index + 1),
                    'is_active' => true
                ]);
        }
        
        // Add constraints and indexes
        Schema::table('form_categories', function (Blueprint $table) {
            // Add unique constraint if it doesn't exist
            if (!Schema::hasIndex('form_categories', 'form_categories_code_unique')) {
                $table->unique('code', 'form_categories_code_unique');
            }
            
            // Add indexes if they don't exist
            if (!Schema::hasIndex('form_categories', 'form_categories_code_index')) {
                $table->index('code', 'form_categories_code_index');
            }
            if (!Schema::hasIndex('form_categories', 'form_categories_is_active_index')) {
                $table->index('is_active', 'form_categories_is_active_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_categories', function (Blueprint $table) {
            // Drop indexes if they exist
            if (Schema::hasIndex('form_categories', 'form_categories_code_index')) {
                $table->dropIndex('form_categories_code_index');
            }
            if (Schema::hasIndex('form_categories', 'form_categories_is_active_index')) {
                $table->dropIndex('form_categories_is_active_index');
            }
            if (Schema::hasIndex('form_categories', 'form_categories_code_unique')) {
                $table->dropUnique('form_categories_code_unique');
            }
        });
    }
};
