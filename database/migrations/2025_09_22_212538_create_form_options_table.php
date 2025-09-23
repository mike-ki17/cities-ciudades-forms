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
        Schema::create('form_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('value');
            $table->string('label');
            $table->integer('order')->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('category_id')->references('id')->on('form_categories')->onDelete('cascade');
            
            // Indexes for better performance
            $table->index(['category_id']);
            $table->index(['category_id', 'is_active']);
            $table->index(['category_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_options');
    }
};
