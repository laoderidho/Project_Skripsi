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
        Schema::create('data_diagnostik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pasien')->constrained('pasien','id');
            $table->foreignId('id_perawat')->constrained('perawat','id');
            $table->string('keluhan_tambahan', 255)->nullable();
            $table->string('suhu', 30)->nullable();
            $table->string('tekanan_darah', 255)->nullable();
            $table->integer('sistolik')->nullable();
            $table->integer('diastolik')->nullable();
            $table->string('nadi', 255)->nullable();
            $table->string('laju_respirasi', 255)->nullable();
            $table->string('kesadaran', 255)->nullable();
            $table->string('eye', 255)->nullable();
            $table->string('motor', 255)->nullable();
            $table->string('verbal', 255)->nullable();
            $table->string('pemeriksaan_fisik', 1000)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_diagnostik');
    }
};
