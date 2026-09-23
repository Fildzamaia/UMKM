<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->string('id_pembelian')
                ->nullable()
                ->unique()
                ->after('id_akun');

            $table->foreign('id_pembelian')
                ->references('id_pembelian')
                ->on('pembelian_produk')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        DB::statement("
            ALTER TABLE pengeluaran
            MODIFY jenis_pengeluaran ENUM(
                'PEMBELIAN_PRODUK',
                'LISTRIK',
                'INTERNET',
                'SEWA',
                'ONGKIR',
                'PERAWATAN',
                'LAINNYA'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::table('pengeluaran')
            ->where('jenis_pengeluaran', 'PEMBELIAN_PRODUK')
            ->delete();

        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->dropForeign(['id_pembelian']);
            $table->dropUnique(['id_pembelian']);
            $table->dropColumn('id_pembelian');
        });

        DB::statement("
            ALTER TABLE pengeluaran
            MODIFY jenis_pengeluaran ENUM(
                'LISTRIK',
                'INTERNET',
                'SEWA',
                'ONGKIR',
                'PERAWATAN',
                'LAINNYA'
            ) NOT NULL
        ");
    }
};