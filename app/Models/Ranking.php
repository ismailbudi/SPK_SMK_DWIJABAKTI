<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ranking extends Model
{
    use HasFactory;

    protected $table = 'rankings';
    protected $primaryKey = 'id_ranking';

    protected $fillable = [
        'periode_id',
        'id_siswa',
        'nilai_akhir',
        'peringkat',
        'keterangan',
    ];

    protected $casts = [
        'nilai_akhir' => 'decimal:6',
        'peringkat' => 'integer',
    ];

    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
}
