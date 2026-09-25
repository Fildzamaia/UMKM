<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenjualanMember extends Model
{
    protected $table = 'penjualan_member';

    // id_penjualan adalah PK sekaligus FK ke penjualan (relasi 1:1).
    protected $primaryKey = 'id_penjualan';

    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id_penjualan',
        'id_akun_customer',
    ];

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(
            Penjualan::class,
            'id_penjualan',
            'id_penjualan'
        );
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(
            Akun::class,
            'id_akun_customer',
            'id_akun'
        );
    }
}
