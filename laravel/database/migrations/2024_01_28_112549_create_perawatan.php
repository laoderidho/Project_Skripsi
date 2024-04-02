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
            $table->unsignedBigInteger('id_pasien');
            $table->unsignedBigInteger('bed');
            $table->date('tanggal_masuk');
            $table->time('waktu_pencatatan');
            $table->date('tanggal_keluar')->nullable();
            $table->time('waktu_keluar')->nullable();
            $table->string('status_pasien', 10);
            $table->timestamps();
        });

        if (Schema::hasTable('pasien')) {
            // Define foreign key constraint
            Schema::table('perawatan', function (Blueprint $table) {
                $table->foreign('id_pasien')->references('id')->on('pasien');
            });
        }

        // Check if the 'beds' table exists
        if (Schema::hasTable('beds')) {
            // Define foreign key constraint
            Schema::table('perawatan', function (Blueprint $table) {
                $table->foreign('bed')->references('id')->on('beds');
            });
        }
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::dropIfExists('perawatan');
    }
};
