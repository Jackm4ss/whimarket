<?php

namespace Tests\Feature;

use App\Enums\Gender;
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
            'phone' => '+6281299999999',
        ]);
    }

    public function test_user_can_update_gender_in_profile_settings(): void
    {
        $user = User::factory()->create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@whimarket.com',
            'gender' => Gender::PRIA,
        ]);

        $response = $this->actingAs($user)->put(route('profile.settings.update'), [
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@whimarket.com',
            'gender' => 'wanita',
        ]);

        $response->assertRedirect('/akun/pengaturan?tab=biodata');
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals(Gender::WANITA, $user->gender);
        $this->assertEquals('Wanita', $user->gender->label());

        // View profile settings and assert gender is displayed
        $viewResponse = $this->actingAs($user)->get(route('profile.settings'));
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('Wanita');
        $viewResponse->assertSee('Jenis Kelamin');
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

    public function test_user_phone_is_normalized_to_e164_with_plus_62_from_different_inputs(): void
    {
        $user = User::factory()->create();

        // 1. Raw digits without prefix: 81234567890
        $this->actingAs($user)->put(route('profile.settings.update'), [
            'name' => 'User Test',
            'email' => $user->email,
            'phone' => '81234567890',
        ]);
        $this->assertEquals('+6281234567890', $user->fresh()->phone);

        // 2. Already formatted +62: +628987654321
        $this->actingAs($user)->put(route('profile.settings.update'), [
            'name' => 'User Test',
            'email' => $user->email,
            'phone' => '+628987654321',
        ]);
        $this->assertEquals('+628987654321', $user->fresh()->phone);

        // 3. With 62 without plus: 62811223344
        $this->actingAs($user)->put(route('profile.settings.update'), [
            'name' => 'User Test',
            'email' => $user->email,
            'phone' => '62811223344',
        ]);
        $this->assertEquals('+62811223344', $user->fresh()->phone);
    }

    public function test_user_can_clear_phone_number_to_null(): void
    {
        $user = User::factory()->create(['phone' => '+6281234567890']);

        $this->actingAs($user)->put(route('profile.settings.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '',
        ]);

        $this->assertNull($user->fresh()->phone);
    }

    public function test_biodata_page_renders_plus_62_prefix_and_initial_phone(): void
    {
        $user1 = User::factory()->create(['phone' => '081234567890']);
        $response1 = $this->actingAs($user1)->get(route('profile.settings', ['tab' => 'biodata']));
        $response1->assertStatus(200);
        $response1->assertSee('+62');
        $response1->assertSee("phoneDisplay: '81234567890'", false);

        $user2 = User::factory()->create(['phone' => '+628999888777']);
        $response2 = $this->actingAs($user2)->get(route('profile.settings', ['tab' => 'biodata']));
        $response2->assertStatus(200);
        $response2->assertSee('+62');
        $response2->assertSee("phoneDisplay: '8999888777'", false);
    }
}
