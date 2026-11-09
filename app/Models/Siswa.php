<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswas';
    protected $primaryKey = 'id_siswa';

    protected $fillable = [
        'nis',
        'nama',
        'kelas',
        'jenis_kelamin',
    ];

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class, 'id_siswa');
    }

    public function perhitungans()
    {
        return $this->hasMany(Perhitungan::class, 'id_siswa');
    }

    public function ranking()
    {
        return $this->hasOne(Ranking::class, 'id_siswa');
    }

    public function getPenilaianByPeriode($periodeId)
    {
        return $this->penilaians()->where('periode_id', $periodeId)->get();
    }
}
