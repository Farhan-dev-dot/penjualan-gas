<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_admin_login_with_customer_credentials_does_not_redirect_to_homepage(): void
    {
        $user = User::factory()->create([
            'email' => 'customer@example.com',
            'password' => bcrypt('user-password'),
        ]);

        $response = $this->from('/petugas/login')
            ->post('/petugas/login', [
                'email' => $user->email,
                'password' => 'user-password',
            ]);

        $response->assertRedirect('/petugas/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest('admin');
        $this->assertGuest('web');
    }

    public function test_customer_logged_in_is_redirected_to_homepage_when_accessing_admin_route(): void
    {
        $user = User::factory()->create([
            'email' => 'customer@example.com',
            'password' => bcrypt('user-password'),
        ]);

        Route::middleware(['web', 'admin'])->get('/test-admin-only', function () {
            return 'ok';
        });

        $response = $this->actingAs($user, 'web')->get('/test-admin-only');

        $response->assertRedirect('/');
        $response->assertSessionHasErrors('email');
    }

    public function test_admin_can_update_customer_status(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('admin-password'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $customer = User::factory()->create([
            'email' => 'customer2@example.com',
            'password' => bcrypt('user-password'),
            'role' => 'user',
            'status' => 'aktif',
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->patchJson('/admin/pelanggan/' . $customer->id . '/status', [
            'status' => 'nonaktif',
        ]);

        $response->assertOk();
        $response->assertJsonPath('status', 'nonaktif');
        $this->assertDatabaseHas('users', ['id' => $customer->id, 'status' => 'nonaktif']);
    }
}
