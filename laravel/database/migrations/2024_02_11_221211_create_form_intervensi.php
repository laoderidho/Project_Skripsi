<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up():void
    {
        Schema::create('form_intervensi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pemeriksaan');
            $table->foreign('id_pemeriksaan')->references('id')->on('pemeriksaan')->onDelete('cascade');
            $table->string('nama_intervensi');
            $table->string('tindakan_intervensi');
            $table->string('catatan_intervensi')->nullable();
            $table->timestamps();
        });
    }

    public function down() :void
    {
        Schema::dropIfExists('form_intervensi');
    }
};
