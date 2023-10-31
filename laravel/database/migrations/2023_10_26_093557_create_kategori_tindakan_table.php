<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kategori_tindakan', function (Blueprint $table) {
            $table->id('id_kategori_tindakan');
            $table->string('nama_kategori_tindakan', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori_tindakan');
    }
};
