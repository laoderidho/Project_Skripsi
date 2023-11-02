<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kriteria_luaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_luaran');
            $table->string('nama_kriteria_luaran', 255);
            $table->timestamps();

            $table->foreign('id_luaran')->references('id')->on('luaran');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriteria_luaran');
    }
};
