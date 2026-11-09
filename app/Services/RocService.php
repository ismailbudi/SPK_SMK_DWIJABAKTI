<?php

namespace App\Services;

class RocService
{
    /**
     * Hitung bobot ROC untuk n kriteria.
     * W_k = (1/n) * sum(i=k..n) 1/i
     *
     * @return array<int, float> keyed by prioritas (1-based)
     */
    public function weights(int $n): array
    {
        if ($n < 1) {
            return [];
        }

        $weights = [];
        for ($k = 1; $k <= $n; $k++) {
            $sum = 0.0;
            for ($i = $k; $i <= $n; $i++) {
                $sum += 1 / $i;
            }
            $weights[$k] = round($sum / $n, 6);
        }

        return $weights;
    }

    /**
     * Pecahan rumus ROC untuk ditampilkan, mis. "1/1 + 1/2 + 1/3".
     *
     * @return array<int, string> keyed by prioritas
     */
    public function formulas(int $n): array
    {
        $formulas = [];
        for ($k = 1; $k <= $n; $k++) {
            $terms = [];
            for ($i = $k; $i <= $n; $i++) {
                $terms[] = '1/' . $i;
            }
            $formulas[$k] = implode(' + ', $terms);
        }

        return $formulas;
    }
}
