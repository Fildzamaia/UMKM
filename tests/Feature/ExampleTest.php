<?php

namespace Tests\Feature;

use Database\Seeders\PrototypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    // Halaman "/" membaca tabel produk, jadi database test perlu dimigrasi.
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->seed(PrototypeSeeder::class);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Basic T-Shirt');
    }
}
