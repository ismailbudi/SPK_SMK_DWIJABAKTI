<?php

namespace Tests\Unit;

use App\Services\MooraService;
use PHPUnit\Framework\TestCase;

class MooraServiceTest extends TestCase
{
    public function test_perhitungan_moora_dua_alternatif(): void
    {
        $criteria = [
            ['id_kriteria' => 1, 'prioritas' => 1, 'jenis' => 'benefit', 'bobot_roc' => 0.6],
            ['id_kriteria' => 2, 'prioritas' => 2, 'jenis' => 'benefit', 'bobot_roc' => 0.4],
        ];

        $alternatives = [
            ['id_siswa' => 1, 'nilai' => [1 => 3, 2 => 4]],
            ['id_siswa' => 2, 'nilai' => [1 => 4, 2 => 3]],
        ];

        $result = (new MooraService())->calculate($alternatives, $criteria);

        $this->assertEqualsWithDelta(5.0, $result['divisors'][1], 0.000001);
        $this->assertEqualsWithDelta(5.0, $result['divisors'][2], 0.000001);
        $this->assertEqualsWithDelta(0.68, $result['yi'][1], 0.000001);
        $this->assertEqualsWithDelta(0.72, $result['yi'][2], 0.000001);
        $this->assertSame(2, $result['ranking'][0]['id_siswa']);
        $this->assertSame(1, $result['ranking'][0]['peringkat']);
        $this->assertSame(1, $result['ranking'][1]['id_siswa']);
        $this->assertSame(2, $result['ranking'][1]['peringkat']);
    }

    public function test_kriteria_cost_mengurangi_nilai(): void
    {
        $criteria = [
            ['id_kriteria' => 1, 'prioritas' => 1, 'jenis' => 'benefit', 'bobot_roc' => 0.5],
            ['id_kriteria' => 2, 'prioritas' => 2, 'jenis' => 'cost', 'bobot_roc' => 0.5],
        ];

        $alternatives = [
            ['id_siswa' => 1, 'nilai' => [1 => 10, 2 => 2]],
            ['id_siswa' => 2, 'nilai' => [1 => 10, 2 => 4]],
        ];

        $result = (new MooraService())->calculate($alternatives, $criteria);

        $this->assertSame(1, $result['ranking'][0]['id_siswa']);
    }

    public function test_tie_break_memakai_prioritas_satu(): void
    {
        $criteria = [
            ['id_kriteria' => 1, 'prioritas' => 1, 'jenis' => 'benefit', 'bobot_roc' => 0.5],
            ['id_kriteria' => 2, 'prioritas' => 2, 'jenis' => 'benefit', 'bobot_roc' => 0.5],
        ];

        $alternatives = [
            ['id_siswa' => 1, 'nilai' => [1 => 8, 2 => 6]],
            ['id_siswa' => 2, 'nilai' => [1 => 6, 2 => 8]],
        ];

        $result = (new MooraService())->calculate($alternatives, $criteria);

        $this->assertSame(1, $result['ranking'][0]['id_siswa']);
    }
}
