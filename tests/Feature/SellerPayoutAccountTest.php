<?php

namespace Tests\Feature;

use App\Enums\SellerStatus;
use App\Enums\UserRole;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerPayoutAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_seller_payout_account_page(): void
    {
        $response = $this->get(route('seller.payout-account.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_non_seller_cannot_access_seller_payout_account_page(): void
    {
        $user = User::factory()->create(['role' => UserRole::BUYER]);

        $response = $this->actingAs($user)->get(route('seller.payout-account.index'));

        $response->assertRedirect(route('seller.register'));
    }

    public function test_verified_seller_can_view_payout_account_page(): void
    {
        $user = User::factory()->create(['role' => UserRole::SELLER]);
        $seller = Seller::create([
            'user_id' => $user->id,
            'store_name' => 'Toko Fashion Rachel',
            'username' => 'toko-fashion-rachel',
            'bio' => 'Preloved pakaian original',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Rachel Vennya',
            'status' => SellerStatus::VERIFIED,
            'verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('seller.payout-account.index'));

        $response->assertOk();
        $response->assertViewIs('seller.payout-account.index');
        $response->assertSee('Toko Fashion Rachel');
        $response->assertSee('BCA');
        $response->assertSee('1234567890');
        $response->assertSee('Rachel Vennya');
    }

    public function test_seller_can_update_payout_bank_account(): void
    {
        $user = User::factory()->create(['role' => UserRole::SELLER]);
        $seller = Seller::create([
            'user_id' => $user->id,
            'store_name' => 'Cellos Official Store',
            'username' => 'cellos-official-store',
            'bio' => 'Merchandise eksklusif Cellos',
            'bank_name' => 'BCA',
            'bank_account_number' => '1111222233',
            'bank_account_name' => 'Cellos Zx',
            'status' => SellerStatus::VERIFIED,
            'verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->put(route('seller.payout-account.update'), [
            'bank_name' => 'Mandiri',
            'bank_account_number' => '1370012345678',
            'bank_account_name' => 'Yoshua Marcellos',
            'confirm_agreement' => '1',
        ]);

        $response->assertRedirect(route('seller.payout-account.index'));
        $response->assertSessionHas('success');

        $seller->refresh();
        $this->assertEquals('Mandiri', $seller->bank_name);
        $this->assertEquals('1370012345678', $seller->bank_account_number);
        $this->assertEquals('Yoshua Marcellos', $seller->bank_account_name);
    }

    public function test_update_payout_bank_account_validates_required_fields_and_agreement(): void
    {
        $user = User::factory()->create(['role' => UserRole::SELLER]);
        $seller = Seller::create([
            'user_id' => $user->id,
            'store_name' => 'Cellos Official Store',
            'username' => 'cellos-official-store',
            'bio' => 'Merchandise eksklusif Cellos',
            'bank_name' => 'BCA',
            'bank_account_number' => '1111222233',
            'bank_account_name' => 'Cellos Zx',
            'status' => SellerStatus::VERIFIED,
            'verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->put(route('seller.payout-account.update'), [
            'bank_name' => '',
            'bank_account_number' => 'abc', // invalid non-numeric
            'bank_account_name' => '',
            // confirm_agreement missing
        ]);

        $response->assertSessionHasErrors(['bank_name', 'bank_account_number', 'bank_account_name', 'confirm_agreement']);
    }
}
