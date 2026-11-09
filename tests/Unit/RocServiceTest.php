<?php

namespace Tests\Unit;

use App\Services\RocService;
use PHPUnit\Framework\TestCase;

class RocServiceTest extends TestCase
{
    public function test_bobot_roc_untuk_lima_kriteria(): void
    {
        $weights = (new RocService())->weights(5);

        $this->assertEqualsWithDelta(0.456667, $weights[1], 0.000001);
        $this->assertEqualsWithDelta(0.256667, $weights[2], 0.000001);
        $this->assertEqualsWithDelta(0.156667, $weights[3], 0.000001);
        $this->assertEqualsWithDelta(0.090000, $weights[4], 0.000001);
        $this->assertEqualsWithDelta(0.040000, $weights[5], 0.000001);
        $this->assertEqualsWithDelta(1.0, array_sum($weights), 0.000001);
    }

    public function test_bobot_roc_untuk_enam_kriteria(): void
    {
        $weights = (new RocService())->weights(6);

        $this->assertEqualsWithDelta(0.408333, $weights[1], 0.000001);
        $this->assertEqualsWithDelta(0.241667, $weights[2], 0.000001);
        $this->assertEqualsWithDelta(0.158333, $weights[3], 0.000001);
        $this->assertEqualsWithDelta(0.102778, $weights[4], 0.000001);
        $this->assertEqualsWithDelta(0.061111, $weights[5], 0.000001);
        $this->assertEqualsWithDelta(0.027778, $weights[6], 0.000001);
        $this->assertEqualsWithDelta(1.0, array_sum($weights), 0.000001);
    }

    public function test_formula_mengikuti_prioritas(): void
    {
        $formulas = (new RocService())->formulas(3);

        $this->assertSame('1/1 + 1/2 + 1/3', $formulas[1]);
        $this->assertSame('1/2 + 1/3', $formulas[2]);
        $this->assertSame('1/3', $formulas[3]);
    }
}
