<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jenis_gejala', function (Blueprint $table) {
            $table->id('id_jenis_gejala');
            $table->string('nama_jenis_gejala');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jenis_gejala');
    }
};
