<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile_settings(): void
    {
        $response = $this->get(route('profile.settings'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_profile_settings(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.settings'));

        $response->assertStatus(200);
        $response->assertSee('Pengaturan Akun');
        $response->assertSee('Biodata Diri');
        $response->assertSee('Daftar Alamat');
    }

    public function test_user_can_update_profile_info(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@whimarket.com',
            'phone' => '0811111111',
        ]);

        $response = $this->actingAs($user)->put(route('profile.settings.update'), [
            'name' => 'Updated Budi Name',
            'email' => 'updated.budi@whimarket.com',
            'phone' => '081299999999',
        ]);

        $response->assertRedirect('/akun/pengaturan?tab=biodata');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Budi Name',
            'email' => 'updated.budi@whimarket.com',
            'phone' => '081299999999',
        ]);
    }

    public function test_user_can_add_delivery_address(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('addresses.store'), [
            'recipient_name' => 'Budi Home',
            'phone' => '081234567890',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Kebayoran Baru',
            'postal_code' => '12190',
            'full_address' => 'Jl. Senopati No. 45 RT 02 RW 03',
            'is_default' => '1',
        ]);

        $response->assertRedirect('/akun/pengaturan?tab=alamat');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'recipient_name' => 'Budi Home',
            'city' => 'Jakarta Selatan',
            'is_default' => true,
        ]);
    }

    public function test_first_address_is_automatically_default(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('addresses.store'), [
            'recipient_name' => 'First Address',
            'phone' => '081234567890',
            'province' => 'Jawa Barat',
            'city' => 'Bandung',
            'full_address' => 'Jl. Dago No. 10',
            'is_default' => '0',
        ]);

        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'recipient_name' => 'First Address',
            'is_default' => true,
        ]);
    }

    public function test_user_can_update_address(): void
    {
        $user = User::factory()->create();
        $address = Address::factory()->create([
            'user_id' => $user->id,
            'recipient_name' => 'Old Recipient',
        ]);

        $response = $this->actingAs($user)->put(route('addresses.update', $address->id), [
            'recipient_name' => 'New Recipient',
            'phone' => '0899999999',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Pusat',
            'full_address' => 'Jl. Sudirman Kav 20',
        ]);

        $response->assertRedirect('/akun/pengaturan?tab=alamat');
        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'recipient_name' => 'New Recipient',
            'city' => 'Jakarta Pusat',
        ]);
    }

    public function test_user_can_set_default_address(): void
    {
        $user = User::factory()->create();
        $addr1 = Address::factory()->create(['user_id' => $user->id, 'is_default' => true]);
        $addr2 = Address::factory()->create(['user_id' => $user->id, 'is_default' => false]);

        $response = $this->actingAs($user)->post(route('addresses.default', $addr2->id));

        $response->assertRedirect('/akun/pengaturan?tab=alamat');
        $this->assertFalse($addr1->fresh()->is_default);
        $this->assertTrue($addr2->fresh()->is_default);
    }

    public function test_user_can_delete_address(): void
    {
        $user = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('addresses.destroy', $address->id));

        $response->assertRedirect('/akun/pengaturan?tab=alamat');
        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }
}
