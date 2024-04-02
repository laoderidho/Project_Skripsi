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
        Schema::create('hasil_evaluasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pemeriksaan')->constrained('pemeriksaan');
            $table->string('subjektif')->nullable();
            $table->string('objektif')->nullable();
            $table->string('perencanaan');
            $table->string('pencapaian');
            $table->string('catatan_lainnya')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_evaluasi');
    }
};
