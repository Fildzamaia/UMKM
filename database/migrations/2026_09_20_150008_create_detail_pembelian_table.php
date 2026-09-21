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
            $table->string('id_bahan');

            $table->decimal('jumlah', 12, 2);
            $table->decimal('harga_satuan', 14, 2);

            $table->foreign('id_pembelian')
                ->references('id_pembelian')
                ->on('pembelian_bahan')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_bahan')
                ->references('id_bahan')
                ->on('bahan_baku')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pembelian');
    }
};