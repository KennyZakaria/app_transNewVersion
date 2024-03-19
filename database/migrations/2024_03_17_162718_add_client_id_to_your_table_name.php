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
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('client_id');
                $table->foreign('client_id')->references('id')->on('client')->onDelete('cascade');
                $table->unsignedBigInteger('transporteur_id');
                $table->foreign('transporteur_id')->references('id')->on('Transporteur')->onDelete('cascade');
                $table->unsignedInteger('numStars');
                $table->text('comment');
                $table->timestamps();
            });

    
    }

    /**
     * Reverse the migrations.
     */
    public function down()
        {
            Schema::dropIfExists('reviews');
        }
};
