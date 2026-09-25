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

        $this->setJenisPengeluaran([
            'PEMBELIAN_PRODUK',
            'LISTRIK',
            'INTERNET',
            'SEWA',
            'ONGKIR',
            'PERAWATAN',
            'LAINNYA',
        ]);
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

        $this->setJenisPengeluaran([
            'LISTRIK',
            'INTERNET',
            'SEWA',
            'ONGKIR',
            'PERAWATAN',
            'LAINNYA',
        ]);
    }

    /**
     * MySQL/MariaDB tetap memakai ALTER ... MODIFY seperti sebelumnya.
     * Driver lain (SQLite untuk test) tidak mengenal MODIFY, jadi kolom
     * diubah lewat Schema builder.
     */
    private function setJenisPengeluaran(array $values): void
    {
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            $list = implode(',', array_map(
                fn (string $value) => "'".$value."'",
                $values
            ));

            DB::statement(
                'ALTER TABLE pengeluaran MODIFY jenis_pengeluaran ENUM('.$list.') NOT NULL'
            );

            return;
        }

        Schema::table('pengeluaran', function (Blueprint $table) use ($values) {
            $table->enum('jenis_pengeluaran', $values)->change();
        });
    }
};