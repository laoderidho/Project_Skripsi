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
            $table->string('nama');
            $table->date('tanggal_lahir');
            $table->boolean('jenis_kelamin');
            $table->text('alamat');
            $table->string('no_telepon');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('no_karyawan');
            $table->string('role');
<<<<<<< HEAD:laravel/database/migrations/2023_09_23_090239_create_user_table.php
=======
            $table->string('photo');
>>>>>>> 73ff3bc34597ed1a4a22d609c381f25eb60395d3:laravel/database/migrations/2023_10_31_162849_create_users_table.php
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
