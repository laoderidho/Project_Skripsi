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
        Schema::create('detail_penyebab', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_diagnosa');
            $table->unsignedBigInteger('id_jenis_penyebab');
            $table->string('nama_penyebab', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_diagnosa')->references('id')->on('diagnosa');
            $table->foreign('id_jenis_penyebab')->references('id')->on('jenis_penyebab');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penyebab');
    }
};
