<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasFactory;

    protected $table = 'periodes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'tahun_ajaran',
        'semester',
        'jumlah_berprestasi',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'jumlah_berprestasi' => 'integer',
    ];

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class, 'periode_id');
    }

    public function perhitungans()
    {
        return $this->hasMany(Perhitungan::class, 'periode_id');
    }

    public function rankings()
    {
        return $this->hasMany(Ranking::class, 'periode_id');
    }

    public static function getActive()
    {
        return self::where('aktif', true)->first();
    }
}
