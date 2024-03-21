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
        Schema::create('tindakan_implementasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tindakan_intervensi');
            $table->string('kalimat_implementasi');
            $table->timestamps();

            $table->foreign('id_tindakan_intervensi')->references('id')->on('tindakan_intervensi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindakan_implementasi');
    }
};
