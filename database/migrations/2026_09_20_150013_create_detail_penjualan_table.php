<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->string('id_detail_penjualan')->primary();

            $table->string('id_penjualan');
            $table->string('id_varian');

            $table->unsignedInteger('jumlah');

            $table->decimal(
                'harga_satuan',
                14,
                2
            );

            $table->foreign('id_penjualan')
                ->references('id_penjualan')
                ->on('penjualan')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_varian')
                ->references('id_varian')
                ->on('varian_produk')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
    }
};