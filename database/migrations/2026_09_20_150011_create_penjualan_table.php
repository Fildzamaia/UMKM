<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->string('id_penjualan')->primary();

            $table->string('id_akun');

            $table->dateTime('tanggal_penjualan');

            $table->enum('kanal_penjualan', [
                'ONLINE',
                'OFFLINE'
            ]);

            $table->enum('metode_pembayaran', [
                'QR',
                'TUNAI'
            ]);

            $table->enum('status_pembayaran', [
                'MENUNGGU',
                'BERHASIL',
                'GAGAL'
            ])->default('MENUNGGU');

            $table->string('referensi_pembayaran')->nullable();

            $table->decimal(
                'nominal_bayar',
                14,
                2
            )->default(0);

            $table->enum('status_penjualan', [
                'BARU',
                'DIPROSES',
                'DIKIRIM',
                'SELESAI',
                'BATAL'
            ])->default('BARU');

            $table->text('alamat_pengiriman')->nullable();

            $table->unsignedInteger('poin_didapat')->default(0);
            $table->unsignedInteger('poin_digunakan')->default(0);

            $table->decimal(
                'diskon_poin',
                14,
                2
            )->default(0);

            $table->timestamps();

            $table->foreign('id_akun')
                ->references('id_akun')
                ->on('akun')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};