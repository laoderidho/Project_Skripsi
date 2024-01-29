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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->date('tanggal_lahir');
            $table->boolean('jenis_kelamin');
            $table->text('alamat');
            $table->string('no_telepon');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role');
            $table->string('photo')->nullable();
            $table->boolean('status')->nullable();
            $table->integer('shift')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
