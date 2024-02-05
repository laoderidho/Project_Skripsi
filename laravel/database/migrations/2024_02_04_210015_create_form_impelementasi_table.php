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
        Schema::create('form_impelementasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pemeriksaan');
            $table->string('nama_implementasi', 255)->nullable();
            $table->boolean('tindakan_implementasi')->nullable();
            $table->time('jam_ceklis')->nullable();
            $table->timestamps();

            $table->foreign('id_pemeriksaan')->references('id')->on('pemeriksaan');
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
