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
        Schema::create('user', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nama');
            $table->date('tanggal_lahir');
            $table->boolean('jenis_kelamin');
            $table->string('alamat');
            $table->string('no_telepon');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('no_karyawan')->unique();
            $table->string('role');
            $table->boolean('status');
            $table->string('photo')->nullable;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
