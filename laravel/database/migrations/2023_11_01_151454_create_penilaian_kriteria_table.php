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
        Schema::create('penilaian_kriteria', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kriteria_luaran');
            $table->string('nilai', 255);
            $table->timestamps();

            $table->foreign('id_kriteria_luaran')->references('id')->on('kriteria_luaran');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_kriteria');
    }
};
