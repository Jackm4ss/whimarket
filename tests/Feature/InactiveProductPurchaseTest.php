<?php

namespace Tests\Feature;

use App\Enums\ProductCondition;
use App\Enums\ProductStatus;
use App\Enums\SellerStatus;
use App\Enums\UserRole;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InactiveProductPurchaseTest extends TestCase
{
    use RefreshDatabase;

    private function createProductWithSeller(ProductStatus $status = ProductStatus::INACTIVE): array
    {
        $sellerUser = User::factory()->create(['role' => UserRole::SELLER]);
        $seller = Seller::create([
            'user_id' => $sellerUser->id,
            'store_name' => 'Toko Creator Test',
            'username' => 'toko-creator-test',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Creator Test',
            'status' => SellerStatus::VERIFIED,
            'verified_at' => now(),
        ]);

        $category = Category::firstOrCreate(['slug' => 'fashion'], ['name' => 'Fashion', 'is_active' => true]);

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Hoodie Creator Edisi Terbatas',
            'slug' => 'hoodie-creator-edisi-terbatas',
            'description' => 'Hoodie creator vintage.',
            'price' => 350000,
            'condition' => ProductCondition::LIKE_NEW,
            'status' => $status,
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Hitam - L',
            'sku' => 'WHI-HD-BLK-L',
            'price' => 350000,
            'stock' => 10,
        ]);

        return [$sellerUser, $seller, $product, $variant];
    }

    public function test_cannot_add_inactive_product_to_cart(): void
    {
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        [, , $product, $variant] = $this->createProductWithSeller(ProductStatus::INACTIVE);

        $response = $this->actingAs($buyer)->postJson(route('cart.add'), [
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'success' => false,
            'message' => 'Maaf, produk ini sedang tidak aktif dan tidak dapat dibeli.',
        ]);

        $this->assertDatabaseMissing('cart_items', [
            'product_variant_id' => $variant->id,
        ]);
    }

    public function test_cannot_checkout_inactive_product(): void
    {
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        $address = Address::create([
            'user_id' => $buyer->id,
            'recipient_name' => 'Buyer Test',
            'phone' => '08123456789',
            'full_address' => 'Jl. Test No. 1',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Kebayoran Baru',
            'postal_code' => '12110',
            'is_default' => true,
        ]);

        [, , $product, $variant] = $this->createProductWithSeller(ProductStatus::INACTIVE);

        $cart = Cart::create(['user_id' => $buyer->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
            'is_selected' => true,
        ]);

        // Attempt to visit checkout index
        $indexResponse = $this->actingAs($buyer)->get(route('checkout.index'));
        $indexResponse->assertRedirect(route('cart.index'));
        $indexResponse->assertSessionHas('error', "Produk '{$product->name}' sedang tidak aktif dan tidak dapat dibeli.");

        // Attempt to process checkout directly
        $processResponse = $this->actingAs($buyer)->post(route('checkout.process'), [
            'address_id' => $address->id,
        ]);

        $processResponse->assertRedirect(route('cart.index'));
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_inactive_product_detail_shows_notice_and_disables_purchase(): void
    {
        [, , $product] = $this->createProductWithSeller(ProductStatus::INACTIVE);

        $response = $this->get(route('product.detail', $product->slug));
        $response->assertOk();
        $response->assertSee('Produk Sedang Dinonaktifkan');
        $response->assertSee('Produk Sedang Dinonaktifkan (Tidak Dapat Dibeli)');
    }

    public function test_inactive_product_hidden_from_public_seller_profile(): void
    {
        [, $seller, $product] = $this->createProductWithSeller(ProductStatus::INACTIVE);

        $response = $this->get(route('seller.profile', '@'.$seller->username));
        $response->assertOk();
        $response->assertDontSee($product->name);
    }
}
