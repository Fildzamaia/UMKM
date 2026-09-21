<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo', function (Blueprint $table) {
            $table->string('id_promo')->primary();

            $table->string('nama_promo');

            $table->decimal(
                'persen_diskon',
                5,
                2
            );

            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo');
    }
};