<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_penjualan',
        'id_akun',
        'tanggal_penjualan',
        'kanal_penjualan',
        'metode_pembayaran',
        'status_pembayaran',
        'referensi_pembayaran',
        'nominal_bayar',
        'status_penjualan',
        'alamat_pengiriman',
        'poin_didapat',
        'poin_digunakan',
        'diskon_poin',
    ];

    protected $casts = [
        'tanggal_penjualan' => 'datetime',
        'nominal_bayar' => 'decimal:2',
        'poin_didapat' => 'integer',
        'poin_digunakan' => 'integer',
        'diskon_poin' => 'decimal:2',
    ];

    public function akun(): BelongsTo
    {
        return $this->belongsTo(
            Akun::class,
            'id_akun',
            'id_akun'
        );
    }

    public function detailPenjualan(): HasMany
    {
        return $this->hasMany(
            DetailPenjualan::class,
            'id_penjualan',
            'id_penjualan'
        );
    }

    public function penjualanMember(): HasOne
    {
        return $this->hasOne(
            PenjualanMember::class,
            'id_penjualan',
            'id_penjualan'
        );
    }
}
