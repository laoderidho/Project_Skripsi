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
        Schema::create('pemeriksaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_perawat');
            $table->unsignedBigInteger('id_perawatan');
            $table->unsignedBigInteger('nama_luaran', 255);
            $table->string('catatan_intervensi', 255)->nullable();
            $table->string('catatan_evaluasi', 255)->nullable();
            $table->string('catatan_luaran', 255)->nullable();
            $table->string('catatan_implementasi', 255)->nullable();
            $table->timestamp('jam_pemberian_diagnosa')->nullable();
            $table->timestamp('jam_pemberian_intervensi')->nullable();
            $table->timestamp('jam_pemberian_implementasi')->nullable();
            $table->timestamp('jam_penilaian_luaran')->nullable();
            $table->timestamp('jam_pemberian_evaluasi')->nullable();
            $table->timestamps();
            $table->integer('shift');

            $table->foreign('id_perawatan')->references('id')->on('perawatan');
            $table->foreign('id_perawat')->references('id')->on('perawat');
            $table->foreign('nama_luaran')->references('id')->on('luaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan');
    }
};
