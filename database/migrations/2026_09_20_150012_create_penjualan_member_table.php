<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan_member', function (Blueprint $table) {
            $table->string('id_penjualan')->primary();
            $table->string('id_akun_customer');

            $table->foreign('id_penjualan')
                ->references('id_penjualan')
                ->on('penjualan')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_akun_customer')
                ->references('id_akun')
                ->on('akun')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan_member');
    }
};