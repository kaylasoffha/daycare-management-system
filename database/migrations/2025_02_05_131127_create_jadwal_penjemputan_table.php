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
        Schema::create('jadwal_penjemputan', function (Blueprint $table) {
            $table->string('id_penitipan')->primary(); // Menjadikan id_penitipan sebagai Primary Key
            $table->string('id_anak', 20);
            $table->date('tanggal');
            $table->time('jam_keluar');
            $table->time('penjemput');
            $table->string('status');
            $table->timestamps();

            // Definisi Foreign Key
            $table->foreign('id_anak')->references('id_anak')->on('anak')->onDelete('cascade');
            $table->foreign('id_penitipan')->references('id_penitipan')->on('jadwal_penitipan')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_penjemputan');
    }
};
