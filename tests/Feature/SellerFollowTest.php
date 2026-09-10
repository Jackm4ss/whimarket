<?php

namespace Tests\Feature;

use App\Enums\ProductCondition;
use App\Enums\ProductStatus;
use App\Enums\SellerStatus;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\SellerFollower;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerFollowTest extends TestCase
{
    use RefreshDatabase;

    private function createSellerAndProduct(): array
    {
        $sellerUser = User::factory()->create(['role' => UserRole::SELLER]);
        $seller = Seller::create([
            'user_id' => $sellerUser->id,
            'store_name' => 'Celloszx Official Store',
            'username' => 'celloszx',
            'bio' => 'Official creator store.',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Cellos Demo',
            'status' => SellerStatus::VERIFIED,
            'verified_at' => now(),
        ]);

        $category = Category::firstOrCreate(['slug' => 'fashion'], ['name' => 'Fashion', 'is_active' => true]);

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Jersey Creator Cellos Edition',
            'slug' => 'jersey-creator-cellos-edition',
            'description' => 'Jersey original.',
            'price' => 250000,
            'condition' => ProductCondition::LIKE_NEW,
            'status' => ProductStatus::ACTIVE,
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'L',
            'sku' => 'CLS-JRS-L',
            'price' => 250000,
            'stock' => 10,
        ]);

        return [$sellerUser, $seller, $product, $variant];
    }

    public function test_guest_cannot_follow_seller(): void
    {
        [, $seller] = $this->createSellerAndProduct();

        $response = $this->postJson(route('seller.toggle-follow', $seller->id));

        $response->assertStatus(401);
        $response->assertJson([
            'error' => 'Unauthenticated',
        ]);
        $this->assertDatabaseMissing('seller_followers', [
            'seller_id' => $seller->id,
        ]);
    }

    public function test_buyer_can_follow_and_unfollow_seller(): void
    {
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        [, $seller] = $this->createSellerAndProduct();

        // 1. Follow
        $followResponse = $this->actingAs($buyer)->postJson(route('seller.toggle-follow', $seller->id));

        $followResponse->assertOk();
        $followResponse->assertJson([
            'success' => true,
            'is_following' => true,
            'followers_count' => 1,
            'user_followed_count' => 1,
        ]);
        $this->assertDatabaseHas('seller_followers', [
            'user_id' => $buyer->id,
            'seller_id' => $seller->id,
        ]);

        // 2. Unfollow
        $unfollowResponse = $this->actingAs($buyer)->postJson(route('seller.toggle-follow', $seller->id));

        $unfollowResponse->assertOk();
        $unfollowResponse->assertJson([
            'success' => true,
            'is_following' => false,
            'followers_count' => 0,
            'user_followed_count' => 0,
        ]);
        $this->assertDatabaseMissing('seller_followers', [
            'user_id' => $buyer->id,
            'seller_id' => $seller->id,
        ]);
    }

    public function test_seller_cannot_follow_own_store(): void
    {
        [$sellerUser, $seller] = $this->createSellerAndProduct();

        $response = $this->actingAs($sellerUser)->postJson(route('seller.toggle-follow', $seller->id));

        $response->assertStatus(422);
        $response->assertJson([
            'error' => 'SelfFollowForbidden',
            'message' => 'Anda tidak dapat mengikuti toko Anda sendiri.',
        ]);
        $this->assertDatabaseMissing('seller_followers', [
            'user_id' => $sellerUser->id,
            'seller_id' => $seller->id,
        ]);
    }

    public function test_followed_sellers_page_renders_for_guest_and_authenticated_users(): void
    {
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        [, $seller] = $this->createSellerAndProduct();

        // Guest sees login CTA
        $guestResponse = $this->get(route('followed-sellers.index'));
        $guestResponse->assertOk();
        $guestResponse->assertSee('Masuk untuk Melihat Toko yang Diikuti');

        // Authenticated with no follows sees empty state
        $authEmptyResponse = $this->actingAs($buyer)->get(route('followed-sellers.index'));
        $authEmptyResponse->assertOk();
        $authEmptyResponse->assertSee('Belum Ada Toko yang Diikuti');

        // Authenticated with follow sees the store
        SellerFollower::create([
            'user_id' => $buyer->id,
            'seller_id' => $seller->id,
        ]);

        $authPopulatedResponse = $this->actingAs($buyer)->get(route('followed-sellers.index'));
        $authPopulatedResponse->assertOk();
        $authPopulatedResponse->assertSee('Celloszx Official Store');
        $authPopulatedResponse->assertSee('Kunjungi Toko');
    }

    public function test_seller_profile_and_product_detail_pass_correct_follow_state(): void
    {
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        [, $seller, $product] = $this->createSellerAndProduct();

        // Unfollowed state
        $profileResponse = $this->actingAs($buyer)->get(route('seller.profile', '@'.$seller->username));
        $profileResponse->assertOk();
        $profileResponse->assertViewHas('isFollowing', false);

        $productResponse = $this->actingAs($buyer)->get(route('product.detail', $product->slug));
        $productResponse->assertOk();
        $productResponse->assertViewHas('isFollowingSeller', false);

        // Follow seller
        SellerFollower::create([
            'user_id' => $buyer->id,
            'seller_id' => $seller->id,
        ]);

        // Followed state
        $profileFollowedResponse = $this->actingAs($buyer)->get(route('seller.profile', '@'.$seller->username));
        $profileFollowedResponse->assertOk();
        $profileFollowedResponse->assertViewHas('isFollowing', true);

        $productFollowedResponse = $this->actingAs($buyer)->get(route('product.detail', $product->slug));
        $productFollowedResponse->assertOk();
        $productFollowedResponse->assertViewHas('isFollowingSeller', true);
    }

    public function test_navbar_displays_followed_sellers_menu_link(): void
    {
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        [, $seller] = $this->createSellerAndProduct();

        SellerFollower::create([
            'user_id' => $buyer->id,
            'seller_id' => $seller->id,
        ]);

        $response = $this->actingAs($buyer)->get(route('home'));
        $response->assertOk();
        $response->assertSee(route('followed-sellers.index'));
        $response->assertSee('Toko yang Diikuti');
    }
}
