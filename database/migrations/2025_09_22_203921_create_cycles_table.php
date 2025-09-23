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
        Schema::create('cycles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('events_id');
            $table->string('name');
            $table->integer('days');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('events_id')->references('id')->on('events')->onDelete('cascade');
            
            // Index for better performance
            $table->index(['events_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cycles');
    }
};
