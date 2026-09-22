<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VarianProduk extends Model
{
    protected $table = 'varian_produk';
    protected $primaryKey = 'id_varian';

    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'id_varian',
        'id_produk',
        'ukuran',
        'warna',
        'stok', 
        'stok_mininum',
    ];

    protected $casts = [
        'stok' => 'integer',
        'stok_minimum' => 'integer',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(
            Produk::class,
            'id_produk',
            'id_produk'
        );
    }
}
