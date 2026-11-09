<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriterias';
    protected $primaryKey = 'id_kriteria';

    protected $fillable = [
        'kode',
        'nama',
        'jenis',
        'prioritas',
        'bobot_roc',
    ];

    protected $casts = [
        'prioritas' => 'integer',
        'bobot_roc' => 'decimal:6',
    ];

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class, 'id_kriteria');
    }

    public function perhitungans()
    {
        return $this->hasMany(Perhitungan::class, 'id_kriteria');
    }

    public static function getOrderedByPrioritas()
    {
        return self::orderBy('prioritas')->get();
    }

    public static function getActiveCount()
    {
        return self::count();
    }
}
