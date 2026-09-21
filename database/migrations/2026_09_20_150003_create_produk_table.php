<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->string('id_produk')->primary();
            $table->string('id_kategori');

            $table->string('nama_produk');
            $table->decimal('harga_jual', 14, 2);
            $table->text('deskripsi')->nullable();
            $table->string('gambar_produk')->nullable();

            $table->enum('status_produk', [
                'AKTIF',
                'NONAKTIF'
            ])->default('AKTIF');

            $table->timestamps();

            $table->foreign('id_kategori')
                ->references('id_kategori')
                ->on('kategori_produk')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};