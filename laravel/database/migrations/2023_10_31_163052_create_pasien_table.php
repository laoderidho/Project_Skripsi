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
        Schema::create('pasien', function (Blueprint $table) {
            $table->id();
<<<<<<<< HEAD:laravel/database/migrations/2023_11_02_094927_create_pasien_table.php
            $table->string('nama_lengkap');
            $table->date('tanggal_lahir');
            $table->boolean('jenis_kelamin');
            $table->string('no_telepon');
========
            $table->string('nama');
            $table->date('tanggal_lahir');
            $table->boolean('jenis_kelamin');
            $table->string('no_telp');
>>>>>>>> 73ff3bc34597ed1a4a22d609c381f25eb60395d3:laravel/database/migrations/2023_10_31_163052_create_pasien_table.php
            $table->string('alamat');
            $table->boolean('status_pernikahan');
            $table->string('nik');
            $table->string('alergi')->nullable();
            $table->string('nama_asuransi')->nullable();
            $table->string('no_asuransi')->nullable();
            $table->string('no_medical_record');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};
