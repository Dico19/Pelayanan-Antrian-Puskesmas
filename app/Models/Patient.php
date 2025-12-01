<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'alamat',
        'jenis_kelamin',
        'no_hp',
        'no_ktp',
        'tgl_lahir',
        'pekerjaan',
    ];

    public function antrians()
    {
        return $this->hasMany(Antrian::class);
    }
}
