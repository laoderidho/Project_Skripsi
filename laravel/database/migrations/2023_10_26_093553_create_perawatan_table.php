<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('perawatan', function (Blueprint $table) {
            $table->id('id_perawatan');
            $table->unsignedBigInteger('id_perawat');
            $table->unsignedBigInteger('id_data_diagnostik');
            $table->time('waktu_pencatatan');
            $table->timestamps();
        });

        Schema::table('perawatan', function (Blueprint $table) {
            $table->foreign('id_perawat')->references('id_perawat')->on('perawat');
            $table->foreign('id_data_diagnostik')->references('id_data_diagnostik')->on('data_diagnostik');
        });
    }

    public function down()
    {
        Schema::dropIfExists('perawatan');
    }
};
