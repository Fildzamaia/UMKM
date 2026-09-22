<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelian_produk', function (Blueprint $table) {
            $table->string('id_pembelian')->primary();

            $table->string('id_supplier');
            $table->string('id_akun');

            $table->dateTime('tanggal_pembelian');

            $table->enum('status_pembelian', [
                'DIPESAN',
                'DITERIMA',
                'BATAL'
            ])->default('DIPESAN');

            $table->timestamps();

            $table->foreign('id_supplier')
                ->references('id_supplier')
                ->on('supplier')
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
        Schema::dropIfExists('pembelian_produk');
    }
};