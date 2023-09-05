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
        Schema::create('transporteurs', function (Blueprint $table) {
            $table->unsignedBigInteger('id', false)->primary();
            $table->enum('status', ['Transport company', 'Auto entrepreneur', 'Private carrier']);
            $table->string('CinRectoURU');
            $table->string('CinVersoURU');
            $table->string('VehicleURUS'); 
            // Add foreign key constraints
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('ville_id')->constrained('villes');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transporteurs');
    }
};
