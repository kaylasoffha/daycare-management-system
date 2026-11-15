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
        Schema::create('jadwal_penitipan', function (Blueprint $table) {
            $table->string('id_penitipan', 10)->primary();
            $table->string('id_pengasuh', 10);
            $table->string('id_pendaftaran', 30);
            $table->date('tanggal');
            $table->time('jam_masuk'); // Kolom jam masuk
            $table->time('jam_keluar'); // Kolom jam keluar
            $table->timestamps();

            $table->foreign('id_pengasuh')->references('id_pengasuh')->on('pengasuh')->onDelete('cascade');
            $table->foreign('id_pendaftaran')->references('id_pendaftaran')->on('pendaftaran_anak')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_penitipan');
    }
};
