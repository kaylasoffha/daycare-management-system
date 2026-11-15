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
        Schema::create('orangtua_wali', function (Blueprint $table) {
            $table->string('id_wali', 5)->primary(); 
            $table->string('nama_wali'); 
            $table->string('no_telepon')->nullable(); 
            $table->string('email')->nullable(); 
            $table->string('alamat')->nullable(); 
            $table->string('hubungan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orangtua_wali', function (Blueprint $table) {
            $table->dropColumn('nama_wali');
        });
    }
};
