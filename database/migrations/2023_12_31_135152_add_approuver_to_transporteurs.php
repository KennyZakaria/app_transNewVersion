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
        Schema::table('transporteurs', function (Blueprint $table) {
            $table->boolean('approuver')->default(true);
        });
    }

    public function down()
    {
        Schema::table('transporteurs', function (Blueprint $table) {
            $table->dropColumn('approuver');
        });
    }
};
