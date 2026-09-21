<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_produk', function (Blueprint $table) {
            $table->string('id_promo');
            $table->string('id_produk');

            $table->primary([
                'id_promo',
                'id_produk'
            ]);

            $table->foreign('id_promo')
                ->references('id_promo')
                ->on('promo')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_produk')
                ->references('id_produk')
                ->on('produk')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_produk');
    }
};