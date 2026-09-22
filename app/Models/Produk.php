<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_produk',
        'id_kategori',
        'nama_produk',
        'harga_jual',
        'deskripsi',
        'gambar_produk',
        'status_produk'
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(
            KategoriProduk::class,
            'id_kategori',
            'id_kategori'
        );
    }

    public function varian(): HasMany
    {
        return $this->hasMany(
            VarianProduk::class,
            'id_produk', 
            'id_produk'
        );
    }

    public function promo(): BelongsToMany
    {
        return $this->belongsToMany(
            Promo::class,
            'promo_produk',
            'id_produk',
            'id_promo'
        );
    }
}
