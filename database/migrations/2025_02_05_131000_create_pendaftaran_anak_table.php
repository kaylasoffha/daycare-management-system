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
        Schema::create('pendaftaran_anak', function (Blueprint $table) {
            $table->string('id_pendaftaran', 30)->primary();
            $table->string('id_anak', 10);
            $table->string('id_wali', 5);
            $table->date('tgl_pendaftaran');
            $table->timestamps();
            $table->foreign('id_anak')->references('id_anak')->on('anak')->onDelete('cascade');
            $table->foreign('id_wali')->references('id_wali')->on('orangtua_wali')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran_anak', function (Blueprint $table) {
            $table->dropColumn('id_pendaftaran');
        });
    }
};
