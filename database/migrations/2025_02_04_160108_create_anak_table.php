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
        Schema::create('anak', function (Blueprint $table) {
            $table->string('id_anak', 10)->primary(); 
            $table->string('nama_anak');
            $table->date('tanggal_lahir');
            $table->string('id_wali')->nullable();
            $table->text('catatan')->nullable(); // Optional text field
            $table->integer('umur')->nullable();    
            $table->string('jenis_kelamin');
            $table->timestamps();


            $table->foreign('id_wali')->references('id_wali')->on('orangtua_wali')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anak');
    }
};
