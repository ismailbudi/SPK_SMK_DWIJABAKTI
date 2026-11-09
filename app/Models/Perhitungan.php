<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perhitungan extends Model
{
    use HasFactory;

    protected $table = 'perhitungans';
    protected $primaryKey = 'id_perhitungan';

    protected $fillable = [
        'periode_id',
        'id_siswa',
        'id_kriteria',
        'nilai_normalisasi',
        'nilai_terbobot',
    ];

    protected $casts = [
        'nilai_normalisasi' => 'decimal:6',
        'nilai_terbobot' => 'decimal:6',
    ];

    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria');
    }
}
