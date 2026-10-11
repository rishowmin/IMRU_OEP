<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminRememberMeTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_screen_shows_remember_me_checkbox(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertOk();
        $response->assertSee('name="remember"', false);
    }

    public function test_admin_login_with_remember_me_sets_recaller_cookie(): void
    {
        $admin = $this->createAdmin();

        $response = $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'password',
            'remember' => '1',
        ]);

        $this->assertAuthenticatedAs($admin, 'admin');
        $response->assertCookie(Auth::guard('admin')->getRecallerName());
        $this->assertNotEmpty($admin->fresh()->remember_token);
    }

    public function test_admin_login_without_remember_me_does_not_set_recaller_cookie(): void
    {
        $admin = $this->createAdmin();

        $response = $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($admin, 'admin');
        $response->assertCookieMissing(Auth::guard('admin')->getRecallerName());
        $this->assertNull($admin->fresh()->remember_token);
    }

    private function createAdmin(): Admin
    {
        return Admin::create([
            'first_name' => 'Test',
            'last_name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => Hash::make('password'),
        ]);
    }
}
