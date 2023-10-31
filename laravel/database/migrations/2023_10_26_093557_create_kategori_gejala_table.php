<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kategori_gejala', function (Blueprint $table) {
            $table->id('id_kategori_gejala');
            $table->string('nama_kategori_gejala');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori_gejala');
    }
};
