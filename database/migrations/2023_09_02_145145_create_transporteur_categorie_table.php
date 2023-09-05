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
        Schema::create('transporteur_categorie', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('transporteur_id')->unsigned();//region
            $table->unsignedBiginteger('categorie_id')->unsigned();//stores
            $table->foreign('transporteur_id')->references('user_id')->on('transporteurs')->onDelete('cascade');
            $table->foreign('categorie_id')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transporteur_categorie');
    }
};
