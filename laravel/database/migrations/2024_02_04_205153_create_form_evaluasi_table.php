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
        Schema::create('form_evaluasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pemeriksaan');
            $table->string('nama_luaran', 5000)->nullable();
            $table->string('hasil_luaran', 1)->nullable();
            $table->timestamps();

            $table->foreign('id_pemeriksaan')->references('id')->on('pemeriksaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_evaluasi');
    }
};
