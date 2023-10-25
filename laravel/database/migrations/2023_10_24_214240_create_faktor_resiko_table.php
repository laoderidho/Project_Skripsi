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
        Schema::create('faktor_resiko', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_diagnosa');
            $table->string('nama');

            // foreign key
            $table->foreign('id_diagnosa')->references('id')->on('diagnosa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faktor_resiko');
    }
};
