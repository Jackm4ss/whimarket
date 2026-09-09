<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\SellerAccessCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerAccessCodeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_unlimited_seller_access_code_can_be_used_multiple_times(): void
    {
        $code = SellerAccessCode::create([
            'code' => 'WHI-VIP-UNLIMITED',
            'max_uses' => null,
            'used_count' => 0,
            'is_locked' => false,
            'is_one_time' => false,
        ]);

        // First user registers
        $user1 = User::factory()->create(['role' => UserRole::BUYER]);
        $response1 = $this->actingAs($user1)->post(route('seller.register.submit'), [
            'code' => 'WHI-VIP-UNLIMITED',
            'store_name' => 'Store One',
            'bio' => 'Bio One',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Owner One',
        ]);
        $response1->assertRedirect(route('seller.dashboard'));
        $this->assertEquals(1, $code->fresh()->used_count);
        $this->assertFalse($code->fresh()->is_locked);

        // Second user registers with same code
        $user2 = User::factory()->create(['role' => UserRole::BUYER]);
        $response2 = $this->actingAs($user2)->post(route('seller.register.submit'), [
            'code' => 'WHI-VIP-UNLIMITED',
            'store_name' => 'Store Two',
            'bio' => 'Bio Two',
            'bank_name' => 'BNI',
            'bank_account_number' => '0987654321',
            'bank_account_name' => 'Owner Two',
        ]);
        $response2->assertRedirect(route('seller.dashboard'));
        $this->assertEquals(2, $code->fresh()->used_count);
        $this->assertFalse($code->fresh()->is_locked);
    }

    public function test_limited_seller_access_code_enforces_quota_and_can_be_reset(): void
    {
        $code = SellerAccessCode::create([
            'code' => 'WHI-VIP-LIMITED2',
            'max_uses' => 2,
            'used_count' => 0,
            'is_locked' => false,
            'is_one_time' => false,
        ]);

        // User 1 registers
        $user1 = User::factory()->create(['role' => UserRole::BUYER]);
        $this->actingAs($user1)->post(route('seller.register.submit'), [
            'code' => 'WHI-VIP-LIMITED2',
            'store_name' => 'Store Limited 1',
            'bank_name' => 'BCA',
            'bank_account_number' => '1111111111',
            'bank_account_name' => 'Owner 1',
        ])->assertRedirect(route('seller.dashboard'));

        $this->assertEquals(1, $code->fresh()->used_count);

        // User 2 registers (fills quota)
        $user2 = User::factory()->create(['role' => UserRole::BUYER]);
        $this->actingAs($user2)->post(route('seller.register.submit'), [
            'code' => 'WHI-VIP-LIMITED2',
            'store_name' => 'Store Limited 2',
            'bank_name' => 'Mandiri',
            'bank_account_number' => '2222222222',
            'bank_account_name' => 'Owner 2',
        ])->assertRedirect(route('seller.dashboard'));

        $this->assertEquals(2, $code->fresh()->used_count);
        $this->assertTrue($code->fresh()->is_locked);

        // User 3 attempts to register (should be blocked by quota)
        $user3 = User::factory()->create(['role' => UserRole::BUYER]);
        $response3 = $this->actingAs($user3)->post(route('seller.register.submit'), [
            'code' => 'WHI-VIP-LIMITED2',
            'store_name' => 'Store Limited 3',
            'bank_name' => 'BRI',
            'bank_account_number' => '3333333333',
            'bank_account_name' => 'Owner 3',
        ]);
        $response3->assertSessionHas('error');
        $this->assertEquals(2, $code->fresh()->used_count);

        // Admin resets quota
        $code->fresh()->resetQuota();
        $this->assertEquals(0, $code->fresh()->used_count);
        $this->assertFalse($code->fresh()->is_locked);

        // User 3 tries again after reset -> succeeds
        $response4 = $this->actingAs($user3)->post(route('seller.register.submit'), [
            'code' => 'WHI-VIP-LIMITED2',
            'store_name' => 'Store Limited 3',
            'bank_name' => 'BRI',
            'bank_account_number' => '3333333333',
            'bank_account_name' => 'Owner 3',
        ]);
        $response4->assertRedirect(route('seller.dashboard'));
        $this->assertEquals(1, $code->fresh()->used_count);
    }

    public function test_locked_access_code_cannot_be_used(): void
    {
        $code = SellerAccessCode::create([
            'code' => 'WHI-VIP-LOCKED',
            'max_uses' => 10,
            'used_count' => 0,
            'is_locked' => true,
            'is_one_time' => false,
        ]);

        $user = User::factory()->create(['role' => UserRole::BUYER]);
        $response = $this->actingAs($user)->post(route('seller.register.submit'), [
            'code' => 'WHI-VIP-LOCKED',
            'store_name' => 'Store Locked',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Owner Locked',
        ]);
        $response->assertSessionHas('error');
    }

    public function test_one_time_access_code_automatically_locks_after_single_use(): void
    {
        $code = SellerAccessCode::create([
            'code' => 'WHI-VIP-ONETIME',
            'max_uses' => 1,
            'used_count' => 0,
            'is_locked' => false,
            'is_one_time' => true,
        ]);

        $user1 = User::factory()->create(['role' => UserRole::BUYER]);
        $this->actingAs($user1)->post(route('seller.register.submit'), [
            'code' => 'WHI-VIP-ONETIME',
            'store_name' => 'Store OneTime',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Owner OneTime',
        ])->assertRedirect(route('seller.dashboard'));

        $this->assertEquals(1, $code->fresh()->used_count);
        $this->assertTrue($code->fresh()->is_locked);
        $this->assertTrue($code->fresh()->is_used);

        // Second user cannot use it
        $user2 = User::factory()->create(['role' => UserRole::BUYER]);
        $response2 = $this->actingAs($user2)->post(route('seller.register.submit'), [
            'code' => 'WHI-VIP-ONETIME',
            'store_name' => 'Store OneTime 2',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Owner OneTime 2',
        ]);
        $response2->assertSessionHas('error');
    }
}
