<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('waktu_shift', function (Blueprint $table) {
            $table->id('id_waktu_shift');
            $table->date('hari');
            $table->date('tanggal');
            $table->integer('shift');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waktu_shift');
    }
};
