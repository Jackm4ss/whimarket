<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_change_password_page(): void
    {
        $response = $this->get(route('password.edit'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_change_password_page(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);

        $response = $this->actingAs($user)->get(route('password.edit'));

        $response->assertStatus(200);
        $response->assertSee('Ganti Password');
        $response->assertSee('Password Saat Ini');
        $response->assertSee('Password Baru');
    }

    public function test_current_password_must_match(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);

        $response = $this->actingAs($user)->put(route('password.update'), [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors(['current_password']);
        $this->assertTrue(Hash::check('oldpassword123', $user->fresh()->password));
    }

    public function test_password_confirmation_must_match(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);

        $response = $this->actingAs($user)->put(route('password.update'), [
            'current_password' => 'oldpassword123',
            'password' => 'newpassword123',
            'password_confirmation' => 'mismatchedpassword',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertTrue(Hash::check('oldpassword123', $user->fresh()->password));
    }

    public function test_password_must_be_at_least_eight_characters(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);

        $response = $this->actingAs($user)->put(route('password.update'), [
            'current_password' => 'oldpassword123',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertTrue(Hash::check('oldpassword123', $user->fresh()->password));
    }

    public function test_user_can_successfully_update_password_and_login_with_new_password(): void
    {
        $user = User::factory()->create([
            'email' => 'buyer_test@whimarket.com',
            'password' => Hash::make('oldpassword123'),
        ]);

        $response = $this->actingAs($user)->put(route('password.update'), [
            'current_password' => 'oldpassword123',
            'password' => 'NewBrandPassword2026!',
            'password_confirmation' => 'NewBrandPassword2026!',
        ]);

        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('NewBrandPassword2026!', $user->fresh()->password));

        // Verify user can log in with new password
        auth()->logout();
        $loginResponse = $this->post(route('login.submit'), [
            'email' => 'buyer_test@whimarket.com',
            'password' => 'NewBrandPassword2026!',
        ]);

        $this->assertAuthenticatedAs($user);
    }
}
