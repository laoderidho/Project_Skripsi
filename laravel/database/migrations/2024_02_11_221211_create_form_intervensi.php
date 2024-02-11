<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormIntervensiTable extends Migration
{
    public function up()
    {
        Schema::create('form_intervensi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pemeriksaan');
            $table->foreign('id_pemeriksaan')->references('id')->on('pemeriksaan')->onDelete('cascade');
            $table->string('kode_intervensi');
            $table->string('nama_intervensi');
            $table->text('observasi');
            $table->text('terapeutik');
            $table->text('edukasi');
            $table->text('kolaborasi');
            $table->string('catatan_intervensi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('form_intervensi');
    }
}
