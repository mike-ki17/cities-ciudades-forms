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
        Schema::create('parent_form_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_option_parent_id');
            $table->unsignedBigInteger('form_option_child_id');
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('form_option_parent_id')->references('id')->on('form_options')->onDelete('cascade');
            $table->foreign('form_option_child_id')->references('id')->on('form_options')->onDelete('cascade');
            
            // Indexes for better performance
            $table->index(['form_option_parent_id']);
            $table->index(['form_option_child_id']);
            
            // Unique constraint to prevent duplicate parent-child relationships
            $table->unique(['form_option_parent_id', 'form_option_child_id'], 'parent_child_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_form_options');
    }
};
