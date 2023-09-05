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
        Schema::table('devis', function (Blueprint $table) {
            $table->unsignedBigInteger('transporteur_id')->nullable()->after('offre_id');
            $table->foreign('transporteur_id')->references('id')->on('transporteurs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devis', function (Blueprint $table) {
            $table->dropForeign(['transporteur_id']);
            $table->dropColumn('transporteur_id');
        });
    }
};
