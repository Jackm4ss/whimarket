<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'buyer', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'seller', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    }

    public function test_google_redirect_returns_redirect(): void
    {
        $response = $this->get(route('auth.google.redirect'));
        $response->assertRedirect();
        $this->assertStringContainsString('accounts.google.com', $response->getTargetUrl());
    }

    public function test_google_callback_creates_buyer_and_logs_in(): void
    {
        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getId')->andReturn('google_123456');
        $socialiteUser->shouldReceive('getName')->andReturn('Google User');
        $socialiteUser->shouldReceive('getEmail')->andReturn('google.user@example.com');
        $socialiteUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar.jpg');

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $user = User::where('email', 'google.user@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals(UserRole::BUYER, $user->role);
        $this->assertTrue($user->hasRole('buyer'));
        $this->assertEquals('google_123456', $user->google_id);
    }

    public function test_logout_clears_session(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_onboarding_address_saves_default_address(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('onboarding.address'), [
            'recipient_name' => 'Budi Baru',
            'phone' => '08123456789',
            'full_address' => 'Jl. Kebon Jeruk No. 10',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Barat',
            'district' => 'Kebon Jeruk',
            'postal_code' => '11530',
        ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'recipient_name' => 'Budi Baru',
            'is_default' => true,
        ]);
    }
}
