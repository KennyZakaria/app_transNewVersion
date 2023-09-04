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
        Schema::create('accept_actions', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date')->nullable();
            $table->decimal('prix', 10, 2)->nullable();
            $table->unsignedBigInteger('devi_id');
            $table->foreign('devi_id')->references('id')->on('devis')->onDelete('cascade');
            $table->text('observations')->nullable();  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accept_actions');
    }
};
