<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_root_mengarahkan_pengguna_ke_dashboard(): void
    {
        $this->get('/')->assertRedirect(route('dashboard'));
    }
}
