<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 public function up()
    {
        Schema::create('kriteria_luaran', function (Blueprint $table) {
            $table->id('id_kriteria_luaran');
            $table->unsignedBigInteger('id_luaran');
            $table->string('nama_kriteria_luaran', 255);
            $table->timestamps();
        });

        // Schema::table('kriteria_luaran', function (Blueprint $table) {
        //     $table->foreign('id_luaran')->references('id_luaran')->on('luaran');
        // });
    }

    public function down()
    {
        Schema::dropIfExists('kriteria_luaran');
    }
};
