<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahan_baku', function (Blueprint $table) {
            $table->string('id_bahan')->primary();

            $table->string('nama_bahan');
            $table->string('warna', 50);
            $table->string('satuan', 20);

            $table->decimal('stok', 12, 2)->default(0);
            $table->decimal('stok_minimum', 12, 2)->default(0);

            $table->timestamps();

            $table->unique([
                'nama_bahan',
                'warna'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahan_baku');
    }
};