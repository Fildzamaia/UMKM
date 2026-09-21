<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produksi', function (Blueprint $table) {
            $table->string('id_produksi')->primary();

            $table->string('id_varian');
            $table->string('id_akun');

            $table->unsignedInteger('jumlah_produksi');

            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('estimasi_selesai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();

            $table->enum('status_produksi', [
                'DRAFT',
                'MENUNGGU_BAHAN',
                'DIPROSES',
                'SELESAI',
                'BATAL'
            ])->default('DRAFT');

            $table->timestamps();

            $table->foreign('id_varian')
                ->references('id_varian')
                ->on('varian_produk')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_akun')
                ->references('id_akun')
                ->on('akun')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produksi');
    }
};