<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('implementasi', function (Blueprint $table) {
            $table->id('id_implementasi');
            $table->string('id_tindakan_intervensi', 255);
            $table->unsignedBigInteger('id_tindakan_implementasi');
            $table->string('status_implementasi', 255);
            $table->timestamps();
        });

        // Schema::table('implementasi', function (Blueprint $table) {
        //     $table->foreign('id_tindakan_implementasi')->references('id_tindakan_implementasi')->on('tindakan_implementasi');
        //     $table->foreign('id_tindakan_intervensi')->references('id_tindakan_intervensi')->on('tindakan_intervensi');
        // });
    }

    public function down()
    {
        Schema::dropIfExists('implementasi');
    }
};
