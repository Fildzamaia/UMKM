<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mutasi_stok', function (Blueprint $table) {
            $table->string('id_mutasi')->primary();

            $table->string('id_varian');
            $table->string('id_akun');

            $table->dateTime('tanggal_mutasi');

            $table->enum('jenis_mutasi', [
                'MASUK',
                'KELUAR',
                'PENYESUAIAN'
            ]);

            $table->integer('jumlah');
            $table->unsignedInteger('stok_sebelum');
            $table->unsignedInteger('stok_sesudah');

            // Referensi bebas ke transaksi asal (id_penjualan, id_pembelian, dst.)
            $table->string('sumber')->nullable();

            // Log mutasi tidak pernah diedit, jadi tidak ada updated_at.
            $table->timestamp('created_at')->useCurrent();

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

            $table->index([
                'id_varian',
                'tanggal_mutasi'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi_stok');
    }
};
