<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('diagnosa', function (Blueprint $table) {
            $table->id('id_diagnosa');
            $table->string('nama_diagnosa', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('diagnosa');
    }
};
