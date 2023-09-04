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
        if (!DB::table('roles')->exists()) { 
            DB::table('roles')->insert([
                ['name' => 'ROLE_TRANSPORTEUR'],
                ['name' => 'ROLE_CLIENT'], 
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
