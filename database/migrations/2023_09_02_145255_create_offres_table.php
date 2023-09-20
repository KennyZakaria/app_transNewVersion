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
        Schema::create('offres', function (Blueprint $table) {
            $table->id();
            $table->dateTime('dateDebut')->nullable();
            $table->dateTime('dateFin')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->unsignedBigInteger('placeDepart');
            $table->foreign('placeDepart')->references('id')->on('places');
            $table->unsignedBigInteger('placeArrivee');
            $table->foreign('placeArrivee')->references('id')->on('places');
            $table->unsignedBigInteger('categorie');
            $table->foreign('categorie')->references('id')->on('categories');
            $table->enum('status', ['EnAttenteDeValidation', 'Valide', 'Rejete','Termine'])->default('EnAttenteDeValidation');
            $table->text('description')->nullable();  
            $table->decimal('prix', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offres');
    }
};
