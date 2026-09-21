<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->string('id_pengeluaran')->primary();

            $table->string('id_akun');

            $table->dateTime('tanggal_pengeluaran');

            $table->enum('jenis_pengeluaran', [
                'LISTRIK',
                'INTERNET',
                'SEWA',
                'ONGKIR',
                'PERAWATAN',
                'LAINNYA'
            ]);

            $table->decimal(
                'nominal',
                14,
                2
            );

            $table->text('keterangan')->nullable();

            $table->timestamps();

            $table->foreign('id_akun')
                ->references('id_akun')
                ->on('akun')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};