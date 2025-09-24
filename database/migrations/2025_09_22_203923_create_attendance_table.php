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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('participant_id');
            $table->unsignedBigInteger('cycle_id');
            $table->boolean('attended')->default(false);
            $table->timestamp('attended_at')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('participant_id')->references('id')->on('participants')->onDelete('cascade');
            $table->foreign('cycle_id')->references('id')->on('cycles')->onDelete('cascade');
            
            // Indexes for better performance
            $table->index(['participant_id', 'cycle_id']);
            $table->index(['cycle_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
