<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AppPagesTest extends TestCase
{
    public function test_halaman_utama_dapat_diakses_setelah_login(): void
    {
        $user = User::first();

        if (! $user) {
            $this->markTestSkipped('Data pengguna belum tersedia.');
        }

        $this->actingAs($user);

        foreach ([
            '/dashboard',
            '/siswa',
            '/kriteria',
            '/nilai',
            '/roc',
            '/moora',
            '/ranking',
            '/laporan',
            '/laporan/csv',
            '/laporan/pdf',
            '/laporan/cetak',
        ] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_tamu_diarahkan_ke_halaman_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }
}
