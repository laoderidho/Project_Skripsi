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
        Schema::create('tindakan_intervensi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kategori_tindakan');
            $table->unsignedBigInteger('id_intervensi');
            $table->string('nama_tindakan_intervensi', 255);
            $table->timestamps();
            $table->foreign('id_kategori_tindakan')->references('id')->on('kategori_tindakan');
            $table->foreign('id_intervensi')->references('id')->on('intervensi');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindakan_intervensi');
    }
};
