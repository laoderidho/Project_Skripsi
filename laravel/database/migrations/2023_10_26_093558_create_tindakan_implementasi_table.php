<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tindakan_implementasi', function (Blueprint $table) {
            $table->id('id_tindakan_implementasi');
            $table->unsignedBigInteger('id_tindakan_intervensi');
            $table->integer('kalimat_implementasi');
            $table->timestamps();
        });

        // Schema::table('tindakan_implementasi', function (Blueprint $table) {
        //     $table->foreign('id_tindakan_intervensi')->references('id_tindakan_intervensi')->on('tindakan_intervensi');
        // });
    }

    public function down()
    {
        Schema::dropIfExists('tindakan_implementasi');
    }
};
