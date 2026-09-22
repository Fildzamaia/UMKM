<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Promo extends Model
{
    protected $table = 'promo';
    protected $primaryKey = 'id_promo';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_promo',
        'nama_promo',
        'persen_diskon',
        'tanggal_mulai',
        'tanggal_selesai'
    ];

    protected $casts = [
        'persen_diskon' => 'decimal:2',
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function produk(): BelongsToMany
    {
        return $this->belongsToMany(
            Produk::class,
            'promo_produk', 
            'id_promo',
            'id_produk'
        );
    }
}
