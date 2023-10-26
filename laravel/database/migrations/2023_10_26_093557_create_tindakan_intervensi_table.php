<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tindakan_intervensi', function (Blueprint $table) {
            $table->id('id_tindakan_intervensi');
            $table->unsignedBigInteger('id_kategori_tindakan');
            $table->string('id_intervensi', 255);
            $table->string('nama_tindakan_intervensi', 255);
            $table->timestamps();
        });

        // Schema::table('tindakan_intervensi', function (Blueprint $table) {
        //     $table->foreign('id_kategori_tindakan')->references('id_kategori_tindakan')->on('kategori_tindakan');
        //     $table->foreign('id_intervensi')->references('id_intervensi')->on('intervensi');
        // });
    }

    public function down()
    {
        Schema::dropIfExists('tindakan_intervensi');
    }
};
