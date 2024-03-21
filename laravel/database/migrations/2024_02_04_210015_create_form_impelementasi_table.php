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
        Schema::create('form_implementasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pemeriksaan');
            $table->unsignedBigInteger('nama_implementasi');
            $table->boolean('tindakan_implementasi')->nullable();
            $table->time('jam_ceklis')->nullable();
            $table->timestamps();

            $table->foreign('id_pemeriksaan')->references('id')->on('pemeriksaan');
            $table->foreign('nama_implementasi')->references('id')->on('tindakan_intervensi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_impelementasi');
    }
};
