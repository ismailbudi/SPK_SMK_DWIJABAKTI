<?php

namespace Tests\Feature;

use App\Models\Kriteria;
use App\Models\Ranking;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class WorkflowTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_dan_alur_perhitungan(): void
    {
        $user = User::where('username', 'admin')->first();
        if (! $user) {
            $this->markTestSkipped('Seeder belum dijalankan.');
        }

        $this->post('/login', [
            'username' => 'admin',
            'password' => 'password',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);

        $this->post('/roc')->assertRedirect('/roc');
        $this->assertSame(0, Kriteria::whereNull('bobot_roc')->count());

        $this->post('/moora')->assertRedirect('/moora');
        $this->assertGreaterThan(0, Ranking::count());

        $this->post('/ranking/refresh')->assertRedirect('/ranking');
    }

    public function test_validasi_prioritas_kriteria_harus_berurutan(): void
    {
        $user = User::where('username', 'admin')->first();
        if (! $user) {
            $this->markTestSkipped('Seeder belum dijalankan.');
        }

        $this->actingAs($user)
            ->post('/kriteria', [
                'kode' => 'C9',
                'nama' => 'Kriteria Uji',
                'jenis' => 'benefit',
                'prioritas' => 9,
            ])
            ->assertSessionHasErrors('prioritas');

        $this->assertDatabaseMissing('kriterias', ['kode' => 'C9']);
    }
}
