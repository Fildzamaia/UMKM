<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akun', function (Blueprint $table) {
            $table->string('id_akun')->primary();
            $table->string('nama');
            $table->string('username')->unique();
            $table->string('password_hash');
            $table->string('email')->nullable()->unique();
            $table->string('no_telp');
            $table->text('alamat')->nullable();

            $table->enum('tipe_akun', [
                'CUSTOMER',
                'KASIR',
                'ADMIN'
            ]);

            $table->enum('status_akun', [
                'AKTIF',
                'NONAKTIF'
            ])->default('AKTIF');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akun');
    }
};