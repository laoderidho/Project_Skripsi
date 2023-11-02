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
<<<<<<<< HEAD:laravel/database/migrations/2023_11_02_104435_create_perawat_table.php
        Schema::create('perawat', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
========
        Schema::create('intervensi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_intervensi', 255);
            $table->timestamps();

>>>>>>>> 73ff3bc34597ed1a4a22d609c381f25eb60395d3:laravel/database/migrations/2023_10_31_164508_create_intervensi_table.php
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perawat');
    }
};
