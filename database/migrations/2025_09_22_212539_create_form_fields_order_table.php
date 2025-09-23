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
        Schema::create('form_fields_order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id');
            $table->unsignedBigInteger('form_category_id');
            $table->unsignedBigInteger('field_json_id')->nullable(); // Note: fields_json table not yet created
            $table->integer('order')->default(0);
            $table->json('extra_data')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
            $table->foreign('form_category_id')->references('id')->on('form_categories')->onDelete('cascade');
            // Note: field_json_id foreign key will be added when fields_json table is created
            
            // Indexes for better performance
            $table->index(['form_id']);
            $table->index(['form_category_id']);
            $table->index(['form_id', 'order']);
            $table->index(['form_category_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields_order');
    }
};
