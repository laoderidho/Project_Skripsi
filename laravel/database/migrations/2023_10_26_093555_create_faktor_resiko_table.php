<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('faktor_resiko', function (Blueprint $table) {
            $table->id('id_faktor_resiko');
            $table->unsignedBigInteger('id_diagnosa');
            $table->string('nama', 255);
            $table->timestamps();
        });

        Schema::table('faktor_resiko', function (Blueprint $table) {
            $table->foreign('id_diagnosa')->references('id_diagnosa')->on('diagnosa');
        });
    }

    public function down()
    {
        Schema::dropIfExists('faktor_resiko');
    }
};
