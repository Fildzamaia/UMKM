<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualan';
    protected $primaryKey = 'id_detail_penjualan';

    public $incrementing = false;
    protected $keyType = 'string';

    // Tabel detail_penjualan tidak punya created_at/updated_at.
    public $timestamps = false;

    protected $fillable = [
        'id_detail_penjualan',
        'id_penjualan',
        'id_varian',
        'jumlah',
        'harga_satuan',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
    ];

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(
            Penjualan::class,
            'id_penjualan',
            'id_penjualan'
        );
    }

    public function varian(): BelongsTo
    {
        return $this->belongsTo(
            VarianProduk::class,
            'id_varian',
            'id_varian'
        );
    }
}
