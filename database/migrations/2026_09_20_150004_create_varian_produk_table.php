<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('varian_produk', function (Blueprint $table) {
            $table->string('id_varian')->primary();
            $table->string('id_produk');

            $table->string('ukuran', 10);
            $table->string('warna', 50);

            $table->unsignedInteger('stok')->default(0);
            $table->unsignedInteger('stok_minimum')->default(0);

            $table->timestamps();

            $table->foreign('id_produk')
                ->references('id_produk')
                ->on('produk')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->unique([
                'id_produk',
                'ukuran',
                'warna'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('varian_produk');
    }
};