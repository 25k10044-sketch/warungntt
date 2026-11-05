<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Mockery;
use Tests\TestCase;

class LoginFirstTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_sent_to_login_before_seeing_the_homepage(): void
    {
        $this->get('/')
            ->assertRedirect(route('login'));
    }

    public function test_signed_in_customer_can_open_the_homepage_after_login(): void
    {
        $customer = User::factory()->create(['role' => 'pelanggan']);

        $this->actingAs($customer)
            ->get('/')
            ->assertOk();
    }

    public function test_signed_in_admin_is_sent_to_the_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/')
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_google_login_shows_a_helpful_message_when_credentials_are_missing(): void
    {
        config([
            'services.google.client_id' => null,
            'services.google.client_secret' => null,
            'services.google.redirect' => null,
        ]);

        $this->get(route('auth.google'))
            ->assertRedirect(route('login'))
            ->assertSessionHas('error', 'Login Google belum dikonfigurasi. Isi GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, dan GOOGLE_REDIRECT_URI di file .env.');
    }

    public function test_google_callback_redirects_to_login_if_oauth_session_state_is_missing(): void
    {
        $provider = Mockery::mock();
        $provider->shouldReceive('user')->once()->andThrow(new InvalidStateException);
        Socialite::shouldReceive('driver')->once()->with('google')->andReturn($provider);

        $this->get(route('auth.google.callback', ['code' => 'fake-code', 'state' => 'fake-state']))
            ->assertRedirect(route('login'))
            ->assertSessionHas('error', 'Sesi login Google sudah kedaluwarsa atau cookie sesi tidak diterima. Mulai login dari halaman ini lagi, gunakan localhost secara konsisten, dan jangan buka ulang halaman callback.');
    }
}
