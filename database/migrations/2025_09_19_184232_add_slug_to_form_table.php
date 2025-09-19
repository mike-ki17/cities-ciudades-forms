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
        // Generate slugs for existing forms
        $forms = \App\Models\Form::all();
        foreach ($forms as $form) {
            if (empty($form->slug)) {
                $form->slug = \Illuminate\Support\Str::slug($form->name . '-' . $form->id);
                $form->save();
            }
        }

        // Add the unique constraint
        Schema::table('form', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form', function (Blueprint $table) {
            $table->dropUnique(['slug']);
        });
    }
};
