<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rawat_inap', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pasien')->unsigned();
            // timestamp data type
            $table->timestamp('tanggal_masuk');
            $table->timestamp('tanggal_keluar')->nullable();
            $table->string('triase');
            $table->string('status');
            $table->timestamps();

            $table->foreign('id_pasien')->references('id')->on('pasien');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rawat_inap');
    }
};
