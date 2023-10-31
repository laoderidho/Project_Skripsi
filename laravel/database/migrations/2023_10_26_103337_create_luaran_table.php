<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('luaran', function (Blueprint $table) {
            $table->id('id_luaran');
            $table->string('kode_luaran', 255);
            $table->string('nama_luaran', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('luaran');
    }
};
