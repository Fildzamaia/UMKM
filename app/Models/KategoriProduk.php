<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriProduk extends Model
{
    protected $table = 'kategori_produk';
    protected $primaryKey = 'id_katogori'; 
    
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_kategori',
        'nama_kategori',
    ];

    public function produk(): HasMany
    {
        return $this->hasMany(
            Produk::class,
            'id_kategori',
            'id_kategori'
        );
    }
}
