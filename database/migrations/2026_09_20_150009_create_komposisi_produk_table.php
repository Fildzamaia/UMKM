<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komposisi_produk', function (Blueprint $table) {
            $table->string('id_varian');
            $table->string('id_bahan');

            $table->decimal('jumlah_per_unit', 12, 3);

            $table->primary([
                'id_varian',
                'id_bahan'
            ]);

            $table->foreign('id_varian')
                ->references('id_varian')
                ->on('varian_produk')
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
        Schema::dropIfExists('komposisi_produk');
    }
};