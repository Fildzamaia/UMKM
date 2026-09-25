<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutasiStok extends Model
{
    protected $table = 'mutasi_stok';
    protected $primaryKey = 'id_mutasi';

    public $incrementing = false;
    protected $keyType = 'string';

    // Tabel hanya punya created_at (diisi manual), tanpa updated_at.
    public $timestamps = false;

    protected $fillable = [
        'id_mutasi',
        'id_varian',
        'id_akun',
        'tanggal_mutasi',
        'jenis_mutasi',
        'jumlah',
        'stok_sebelum',
        'stok_sesudah',
        'sumber',
        'created_at',
    ];

    protected $casts = [
        'tanggal_mutasi' => 'datetime',
        'jumlah' => 'integer',
        'stok_sebelum' => 'integer',
        'stok_sesudah' => 'integer',
        'created_at' => 'datetime',
    ];

    public function varian(): BelongsTo
    {
        return $this->belongsTo(
            VarianProduk::class,
            'id_varian',
            'id_varian'
        );
    }

    public function akun(): BelongsTo
    {
        return $this->belongsTo(
            Akun::class,
            'id_akun',
            'id_akun'
        );
    }
}
