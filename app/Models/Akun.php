<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Akun extends Authenticatable
{
    protected $table = 'akun';

    protected $primaryKey = 'id_akun';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_akun',
        'nama',
        'username',
        'password_hash',
        'email',
        'no_telp',
        'alamat',
        'tipe_akun',
        'status_akun',
    ];

    protected $hidden = [
        'password_hash',
    ];

    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }
}