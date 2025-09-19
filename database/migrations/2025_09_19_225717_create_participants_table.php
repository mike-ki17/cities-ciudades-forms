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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('document_type', ['DNI', 'CE', 'PASSPORT', 'OTRO'])->default('OTRO');
            $table->string('document_number');
            $table->date('birth_date')->nullable();
            $table->json('extra_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
            $table->index(['city_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
