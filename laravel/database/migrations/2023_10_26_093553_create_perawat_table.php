<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::create('perawat', function (Blueprint $table) {
            $table->id('id_perawat');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_waktu_shift');
            $table->boolean('status');
            $table->timestamps();
        });

        Schema::table('perawat', function(Blueprint $table) {
            $table->foreign('id_user')->references('id_user')->on('user');
            $table->foreign('id_waktu_shift')->references('id_waktu_shift')->on('waktu_shift');
        });
    }

    public function down()
    {
        Schema::dropIfExists('perawat');
    }
};
