<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pembelian', function (Blueprint $table) {
            $table->string('id_detail_pembelian')->primary();

            $table->string('id_pembelian');
            $table->string('id_varian');

            $table->unsignedInteger('jumlah');
            $table->decimal('harga_satuan', 14, 2);

            $table->foreign('id_pembelian')
                ->references('id_pembelian')
                ->on('pembelian_produk')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_varian')
                ->references('id_varian')
                ->on('varian_produk')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->unique([
                'id_pembelian',
                'id_varian'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pembelian');
    }
};