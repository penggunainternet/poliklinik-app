<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarPoli extends Model

{
    protected $table = 'daftar_poli';

    protected $fillable = ['id_pasien', 'id_jadwal', 'keluhan', 'no_antrian'];
    public function dokters()
    {
        return $this->hasMany(User::class, 'id_poli');
    }
}
