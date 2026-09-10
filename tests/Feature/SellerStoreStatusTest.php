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

class SellerStoreStatusTest extends TestCase
{
    use RefreshDatabase;

    private function createSellerWithProducts(SellerStatus $status = SellerStatus::VERIFIED): array
    {
        $sellerUser = User::factory()->create(['role' => UserRole::SELLER]);
        $seller = Seller::create([
            'user_id' => $sellerUser->id,
            'store_name' => 'Toko Fashion Rachel',
            'username' => 'toko-fashion-rachel',
            'bio' => 'Fashion pre-loved berkualitas.',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Rachel Demo',
            'status' => $status,
            'verified_at' => $status === SellerStatus::VERIFIED ? now() : null,
            'rejection_reason' => $status === SellerStatus::SUSPENDED ? 'Evaluasi kepatuhan toko oleh admin' : null,
        ]);

        $category = Category::firstOrCreate(['slug' => 'fashion'], ['name' => 'Fashion', 'is_active' => true]);

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Blazer Vintage Rachel Edition',
            'slug' => 'blazer-vintage-rachel-edition',
            'description' => 'Blazer preloved kondisi sangat baik.',
            'price' => 450000,
            'condition' => ProductCondition::LIKE_NEW,
            'status' => ProductStatus::ACTIVE,
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'All Size',
            'sku' => 'WHI-BLZ-ALL',
            'price' => 450000,
            'stock' => 5,
        ]);

        return [$sellerUser, $seller, $product, $variant];
    }

    public function test_inactive_seller_sees_alert_on_dashboard_and_is_not_redirected_to_register(): void
    {
        [$sellerUser, $seller] = $this->createSellerWithProducts(SellerStatus::SUSPENDED);

        $response = $this->actingAs($sellerUser)->get(route('seller.dashboard'));

        $response->assertOk();
        $response->assertSee('Toko Anda Sedang Dinonaktifkan oleh Administrator');
        $response->assertSee('Evaluasi kepatuhan toko oleh admin');
        $response->assertSee('Toko Dinonaktifkan Sementara');
    }

    public function test_inactive_seller_cannot_create_or_store_new_product(): void
    {
        [$sellerUser, $seller] = $this->createSellerWithProducts(SellerStatus::SUSPENDED);
        $category = Category::first();

        // Visiting create page should redirect to products index with error
        $createResponse = $this->actingAs($sellerUser)->get(route('seller.products.create'));
        $createResponse->assertRedirect(route('seller.products.index'));
        $createResponse->assertSessionHas('error', 'Toko Anda sedang dinonaktifkan oleh administrator. Anda belum dapat menambah produk baru.');

        // Attempting to post new product
        $storeResponse = $this->actingAs($sellerUser)->post(route('seller.products.store'), [
            'name' => 'Produk Ilegal Saat Nonaktif',
            'category_id' => $category->id,
            'description' => 'Mencoba membuat produk saat toko nonaktif.',
            'price' => 200000,
            'condition' => ProductCondition::GOOD->value,
            'variants' => [
                ['name' => 'Default', 'price' => 200000, 'stock' => 10],
            ],
        ]);

        $storeResponse->assertRedirect(route('seller.products.index'));
        $storeResponse->assertSessionHas('error', 'Toko Anda sedang dinonaktifkan oleh administrator. Anda belum dapat menambah produk baru.');

        $this->assertDatabaseMissing('products', [
            'name' => 'Produk Ilegal Saat Nonaktif',
        ]);
    }

    public function test_buyer_sees_inactive_store_notice_on_seller_profile(): void
    {
        [, $seller, $product] = $this->createSellerWithProducts(SellerStatus::SUSPENDED);

        $response = $this->get(route('seller.profile', '@'.$seller->username));

        $response->assertOk();
        $response->assertSee('Toko Ini Sedang Dinonaktifkan Sementara');
        $response->assertSee('Toko Sedang Nonaktif');
    }

    public function test_buyer_sees_inactive_store_notice_on_product_detail_and_cannot_buy(): void
    {
        [, , $product] = $this->createSellerWithProducts(SellerStatus::SUSPENDED);

        $response = $this->get(route('product.detail', $product->slug));

        $response->assertOk();
        $response->assertSee('Toko Penjual Sedang Dinonaktifkan');
        $response->assertSee('Toko Penjual Dinonaktifkan (Tidak Dapat Dibeli)');
    }

    public function test_cannot_add_product_of_inactive_seller_to_cart(): void
    {
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        [, , , $variant] = $this->createSellerWithProducts(SellerStatus::SUSPENDED);

        $response = $this->actingAs($buyer)->postJson(route('cart.add'), [
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'success' => false,
            'message' => 'Maaf, toko penjual produk ini sedang dinonaktifkan sehingga produk tidak dapat dibeli.',
        ]);

        $this->assertDatabaseMissing('cart_items', [
            'product_variant_id' => $variant->id,
        ]);
    }

    public function test_cannot_checkout_product_of_inactive_seller(): void
    {
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        $address = Address::create([
            'user_id' => $buyer->id,
            'recipient_name' => 'Buyer Checkout Test',
            'phone' => '08123456789',
            'full_address' => 'Jl. Kebon Jeruk No. 5',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Barat',
            'district' => 'Kebon Jeruk',
            'postal_code' => '11530',
            'is_default' => true,
        ]);

        [, $seller, $product, $variant] = $this->createSellerWithProducts(SellerStatus::SUSPENDED);

        $cart = Cart::create(['user_id' => $buyer->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
            'is_selected' => true,
        ]);

        // Visiting checkout.index must redirect back to cart
        $indexResponse = $this->actingAs($buyer)->get(route('checkout.index'));
        $indexResponse->assertRedirect(route('cart.index'));
        $indexResponse->assertSessionHas('error', "Toko penjual '{$seller->store_name}' sedang dinonaktifkan sehingga produk tidak dapat dibeli.");

        // Attempting to post checkout.process directly must fail
        $processResponse = $this->actingAs($buyer)->post(route('checkout.process'), [
            'address_id' => $address->id,
        ]);

        $processResponse->assertRedirect(route('cart.index'));
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_inactive_seller_products_hidden_from_shop_catalog(): void
    {
        [, , $product] = $this->createSellerWithProducts(SellerStatus::SUSPENDED);

        $response = $this->get(route('shop'));
        $response->assertOk();
        $response->assertDontSee($product->name);
    }
}
