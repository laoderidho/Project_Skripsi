<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jenis_penyebab', function (Blueprint $table) {
            $table->id('id_jenis_penyebab');
            $table->string('nama_jenis_penyebab');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jenis_penyebab');
    }
};
