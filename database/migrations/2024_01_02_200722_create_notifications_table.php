<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Making user_id nullable
            $table->enum('notificationType', [
                'demandeAcceptee',
                'demandeRejetee',
                'nouveauDevis',
                'compteApprouve',
                'compteRejete',
                'devisAccepteParClient',
                'devisRejeteParClient',
            ]);
            $table->timestamp('dateCreation')->default(now());
            $table->boolean('statusRead')->default(false);
            $table->unsignedBigInteger('deviDemandeCompteId')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
