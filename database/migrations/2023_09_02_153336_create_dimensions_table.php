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
        Schema::create('dimensions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('article_id');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->decimal('dimensionX', 10, 2)->nullable(); 
            $table->decimal('dimensionY', 10, 2)->nullable(); 
            $table->decimal('dimensionZ', 10, 2)->nullable(); 
            $table->enum('uniteDimension', ['mètre', 'centimètre'])->nullable();
            $table->decimal('poid', 10, 2)->nullable();
            $table->enum('unitePoids', ['Kilogramme', 'Quintal', 'Tonne'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dimensions');
    }
};
