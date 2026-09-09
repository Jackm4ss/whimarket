<?php

namespace Tests\Feature;

use App\Enums\ProductCondition;
use App\Enums\ProductStatus;
use App\Enums\SellerStatus;
use App\Enums\UserRole;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistAndCartTest extends TestCase
{
    use RefreshDatabase;

    private function createProductWithSeller(): array
    {
        $sellerUser = User::factory()->create(['role' => UserRole::SELLER]);
        $seller = Seller::create([
            'user_id' => $sellerUser->id,
            'store_name' => 'Toko Demo',
            'username' => 'toko-demo',
            'bank_name' => 'BCA',
            'bank_account_number' => '123',
            'bank_account_name' => 'Demo',
            'status' => SellerStatus::VERIFIED,
            'verified_at' => now(),
        ]);

        $category = Category::firstOrCreate(['slug' => 'fashion'], ['name' => 'Fashion', 'is_active' => true]);

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Kaos Merchandise WhiMarket',
            'slug' => 'kaos-merchandise-whimarket',
            'description' => 'Kaos bahan katun premium.',
            'price' => 150000,
            'condition' => ProductCondition::LIKE_NEW,
            'status' => ProductStatus::ACTIVE,
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Size M',
            'sku' => 'WHI-TSHIRT-M',
            'price' => 150000,
            'stock' => 10,
        ]);

        return [$sellerUser, $seller, $product, $variant];
    }

    public function test_guest_cannot_toggle_wishlist_and_gets_redirect(): void
    {
        [, , $product] = $this->createProductWithSeller();

        $response = $this->postJson(route('wishlist.toggle', $product->id));

        $response->assertStatus(401);
        $response->assertJson([
            'error' => 'Unauthenticated',
            'redirect' => route('login'),
        ]);
    }

    public function test_authenticated_user_can_toggle_wishlist_by_id_and_slug(): void
    {
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        [, , $product] = $this->createProductWithSeller();

        // 1. Toggle ON by ID
        $responseOn = $this->actingAs($buyer)->postJson(route('wishlist.toggle', $product->id));
        $responseOn->assertOk();
        $responseOn->assertJson([
            'success' => true,
            'is_liked' => true,
            'likes_count' => 1,
            'user_wishlists_count' => 1,
        ]);
        $this->assertDatabaseHas('wishlists', [
            'user_id' => $buyer->id,
            'product_id' => $product->id,
        ]);

        // 2. Toggle OFF by slug
        $responseOff = $this->actingAs($buyer)->postJson(route('wishlist.toggle', $product->slug));
        $responseOff->assertOk();
        $responseOff->assertJson([
            'success' => true,
            'is_liked' => false,
            'likes_count' => 0,
            'user_wishlists_count' => 0,
        ]);
        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $buyer->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_wishlist_page_displays_added_products(): void
    {
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        [, , $product] = $this->createProductWithSeller();

        Wishlist::create([
            'user_id' => $buyer->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($buyer)->get(route('wishlist.index'));

        $response->assertOk();
        $response->assertSee('Kaos Merchandise WhiMarket');
    }

    public function test_guest_cannot_add_to_cart(): void
    {
        [, , , $variant] = $this->createProductWithSeller();

        $response = $this->postJson(route('cart.add'), [
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'error' => 'Unauthenticated',
            'redirect' => route('login'),
        ]);
    }

    public function test_authenticated_user_can_add_to_cart_and_manage_items(): void
    {
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        [, , $product, $variant] = $this->createProductWithSeller();

        // 1. Add to cart
        $responseAdd = $this->actingAs($buyer)->postJson(route('cart.add'), [
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);
        $responseAdd->assertOk();
        $responseAdd->assertJson([
            'success' => true,
            'cart_count' => 1,
        ]);

        // 2. View Cart page
        $responseCartPage = $this->actingAs($buyer)->get(route('cart.index'));
        $responseCartPage->assertOk();
        $responseCartPage->assertSee('Kaos Merchandise WhiMarket');

        $cart = Cart::where('user_id', $buyer->id)->first();
        $cartItem = $cart->items()->first();
        $this->assertEquals(2, $cartItem->quantity);

        // 3. Update quantity
        $responseUpdate = $this->actingAs($buyer)->patchJson(route('cart.update', $cartItem->id), [
            'quantity' => 3,
        ]);
        $responseUpdate->assertOk();
        $responseUpdate->assertJson([
            'success' => true,
            'quantity' => 3,
            'item_subtotal' => 450000,
        ]);

        // 4. Remove item
        $responseRemove = $this->actingAs($buyer)->deleteJson(route('cart.remove', $cartItem->id));
        $responseRemove->assertOk();
        $responseRemove->assertJson([
            'success' => true,
            'cart_count' => 0,
        ]);
        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }
}
