<?php

namespace Tests\Feature;

use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test register page can be rendered.
     */
    public function test_register_page_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Baru');
    }

    /**
     * Test registration creates a user and logs them in.
     */
    public function test_user_can_register(): void
    {
        $response = $this->post('/register', [
            'nama_pengguna' => 'Budi Staf Baru',
            'username'      => 'budistaf',
            'password'      => 'password123',
            'password_confirmation' => 'password123',
            'role'          => 'staf',
        ]);

        $response->assertRedirect('/dashboard');
        
        $this->assertDatabaseHas('penggunas', [
            'username' => 'budistaf',
            'nama_pengguna' => 'Budi Staf Baru',
            'role' => 'staf'
        ]);

        $this->assertEquals('budistaf', session('pengguna.username'));
    }

    /**
     * Test login works with correct credentials.
     */
    public function test_user_can_login(): void
    {
        $pengguna = Pengguna::create([
            'nama_pengguna' => 'Staf Sukses',
            'username' => 'stafsukses',
            'password' => \Illuminate\Support\Facades\Hash::make('staf123'),
            'role' => 'staf',
        ]);

        $response = $this->post('/login', [
            'username' => 'stafsukses',
            'password' => 'staf123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertEquals('stafsukses', session('pengguna.username'));
    }
}
