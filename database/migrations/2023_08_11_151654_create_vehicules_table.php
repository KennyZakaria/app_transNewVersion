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
        Schema::create('vehicules', function (Blueprint $table) {
            $table->id();
            $table->text('Description')->nullable();  
            $table->string('Marque');
            $table->string('Model');  
            $table->unsignedBigInteger('transporteur_id');
            $table->unsignedBigInteger('vehicle_types_id');
            $table->timestamps();
            $table->foreign('transporteur_id')->references('user_id')->on('transporteurs');
            $table->foreign('vehicle_types_id')->references('id')->on('vehicle_types');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicules');
    }
};
