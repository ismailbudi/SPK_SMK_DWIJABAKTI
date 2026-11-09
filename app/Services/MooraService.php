<?php

namespace App\Services;

class MooraService
{
    /**
     * Hitung seluruh tahap MOORA.
     *
     * @param array $alternatives [['id_siswa' => int, 'nilai' => [id_kriteria => float]], ...]
     * @param array $criteria     [['id_kriteria' => int, 'prioritas' => int, 'jenis' => 'benefit'|'cost', 'bobot_roc' => float], ...]
     * @return array
     */
    public function calculate(array $alternatives, array $criteria): array
    {
        $criteria = collect($criteria)
            ->sortBy('prioritas')
            ->values()
            ->all();

        $matrix = [];
        foreach ($alternatives as $alternatif) {
            $matrix[$alternatif['id_siswa']] = $alternatif['nilai'];
        }

        $divisors = [];
        foreach ($criteria as $kriteria) {
            $id = $kriteria['id_kriteria'];
            $sumSquares = 0.0;
            foreach ($matrix as $nilai) {
                $x = (float) ($nilai[$id] ?? 0);
                $sumSquares += $x * $x;
            }
            $divisors[$id] = $sumSquares > 0 ? sqrt($sumSquares) : 0.0;
        }

        $normalized = [];
        $weighted = [];
        $yi = [];

        foreach ($matrix as $idSiswa => $nilai) {
            $normalized[$idSiswa] = [];
            $weighted[$idSiswa] = [];
            $total = 0.0;

            foreach ($criteria as $kriteria) {
                $id = $kriteria['id_kriteria'];
                $divisor = $divisors[$id];
                $x = (float) ($nilai[$id] ?? 0);
                $normal = $divisor > 0 ? $x / $divisor : 0.0;
                $terbobot = $normal * (float) $kriteria['bobot_roc'];

                $normalized[$idSiswa][$id] = round($normal, 6);
                $weighted[$idSiswa][$id] = round($terbobot, 6);

                if (($kriteria['jenis'] ?? 'benefit') === 'cost') {
                    $total -= $terbobot;
                } else {
                    $total += $terbobot;
                }
            }

            $yi[$idSiswa] = $total;
        }

        $ranking = $this->rank($alternatives, $criteria, $matrix, $yi);

        return [
            'criteria' => $criteria,
            'alternatives' => $alternatives,
            'divisors' => $divisors,
            'matrix' => $matrix,
            'normalized' => $normalized,
            'weighted' => $weighted,
            'yi' => $yi,
            'ranking' => $ranking,
        ];
    }

    /**
     * Urutkan Yi menurun; jika seri, bandingkan nilai kriteria prioritas 1, 2, dst.
     */
    protected function rank(array $alternatives, array $criteria, array $matrix, array $yi): array
    {
        $ids = array_keys($yi);

        usort($ids, function ($a, $b) use ($criteria, $matrix, $yi) {
            if (abs($yi[$a] - $yi[$b]) > 0.0000001) {
                return $yi[$a] > $yi[$b] ? -1 : 1;
            }

            foreach ($criteria as $kriteria) {
                $id = $kriteria['id_kriteria'];
                $va = (float) ($matrix[$a][$id] ?? 0);
                $vb = (float) ($matrix[$b][$id] ?? 0);
                if ($va !== $vb) {
                    return $va < $vb ? 1 : -1;
                }
            }
            return 0;
        });

        $result = [];
        $peringkat = 1;
        foreach ($ids as $id) {
            $result[] = [
                'id_siswa' => $id,
                'nilai_akhir' => round($yi[$id], 6),
                'peringkat' => $peringkat++,
            ];
        }

        return $result;
    }
}
