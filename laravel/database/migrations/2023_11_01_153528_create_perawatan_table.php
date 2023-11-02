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
        Schema::create('perawatan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_perawat');
            $table->unsignedBigInteger('id_data_diagnostik');
            $table->string('bed', 5);
            $table->time('waktu_pencatatan');
            $table->timestamps();

            $table->foreign('id_perawat')->references('id')->on('perawat');
            $table->foreign('id_data_diagnostik')->references('id')->on('data_diagnostik');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perawatan');
    }
};
