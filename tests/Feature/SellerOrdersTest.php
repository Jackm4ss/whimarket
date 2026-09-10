<?php

namespace Tests\Feature;

use App\Enums\SellerStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;
use App\States\Order\Paid;
use App\States\Order\Shipped;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerOrdersTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_seller_orders_page(): void
    {
        $response = $this->get(route('seller.orders.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_non_seller_redirected_to_seller_register(): void
    {
        $user = User::factory()->create(['role' => UserRole::BUYER]);

        $response = $this->actingAs($user)->get(route('seller.orders.index'));

        $response->assertRedirect(route('seller.register'));
    }

    public function test_admin_redirected_to_admin_orders(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);

        $response = $this->actingAs($admin)->get(route('seller.orders.index'));

        $response->assertRedirect('/admin/orders');
    }

    public function test_seller_can_view_orders_page(): void
    {
        $sellerUser = User::factory()->create(['role' => UserRole::SELLER]);
        $seller = Seller::create([
            'user_id' => $sellerUser->id,
            'store_name' => 'Toko Cellos Official',
            'username' => 'cellos_store',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Cellos Official',
            'status' => SellerStatus::VERIFIED,
        ]);

        $buyer = User::factory()->create(['role' => UserRole::BUYER, 'name' => 'Budi Pembeli']);

        $product = Product::factory()->create(['seller_id' => $seller->id]);
        $variant = ProductVariant::factory()->create(['product_id' => $product->id]);

        $order = Order::create([
            'order_number' => 'WHI-2026-0001',
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'total_amount' => 150000,
            'shipping_cost' => 15000,
            'admin_fee' => 1000,
            'grand_total' => 166000,
            'status' => Paid::class,
            'address_snapshot' => [
                'recipient_name' => 'Budi Pembeli',
                'phone' => '081234567890',
                'full_address' => 'Jl. Merdeka No. 45',
                'district' => 'Gambir',
                'city' => 'Jakarta Pusat',
                'province' => 'DKI Jakarta',
                'postal_code' => '10110',
            ],
        ]);

        $order->items()->create([
            'product_variant_id' => $variant->id,
            'product_name_snapshot' => 'Kaos Cellos Signature',
            'variant_name_snapshot' => 'Hitam / L',
            'price_snapshot' => 150000,
            'quantity' => 1,
            'subtotal' => 150000,
        ]);

        $response = $this->actingAs($sellerUser)->get(route('seller.orders.index'));

        $response->assertOk();
        $response->assertViewIs('seller.orders.index');
        $response->assertSee('Kelola Pesanan Masuk');
        $response->assertSee('#WHI-2026-0001');
        $response->assertSee('Budi Pembeli');
        $response->assertSee('Kaos Cellos Signature');
    }

    public function test_seller_can_filter_orders_by_status(): void
    {
        $sellerUser = User::factory()->create(['role' => UserRole::SELLER]);
        $seller = Seller::create([
            'user_id' => $sellerUser->id,
            'store_name' => 'Toko Filter Test',
            'username' => 'filter_store',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Filter Store',
            'status' => SellerStatus::VERIFIED,
        ]);

        $buyer = User::factory()->create(['role' => UserRole::BUYER]);

        // Paid / Processing order (Perlu Dikirim)
        $orderProcessing = Order::create([
            'order_number' => 'WHI-ORDER-PROC',
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'total_amount' => 100000,
            'shipping_cost' => 10000,
            'admin_fee' => 1000,
            'grand_total' => 111000,
            'status' => Paid::class,
            'address_snapshot' => ['recipient_name' => 'Buyer One', 'full_address' => 'Addr 1'],
        ]);

        // Shipped order
        $orderShipped = Order::create([
            'order_number' => 'WHI-ORDER-SHIP',
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'total_amount' => 200000,
            'shipping_cost' => 10000,
            'admin_fee' => 1000,
            'grand_total' => 211000,
            'status' => Shipped::class,
            'address_snapshot' => ['recipient_name' => 'Buyer Two', 'full_address' => 'Addr 2'],
        ]);

        // Filter: processing
        $responseProcessing = $this->actingAs($sellerUser)->get(route('seller.orders.index', ['status' => 'processing']));
        $responseProcessing->assertOk();
        $responseProcessing->assertSee('WHI-ORDER-PROC');
        $responseProcessing->assertDontSee('WHI-ORDER-SHIP');

        // Filter: shipped
        $responseShipped = $this->actingAs($sellerUser)->get(route('seller.orders.index', ['status' => 'shipped']));
        $responseShipped->assertOk();
        $responseShipped->assertSee('WHI-ORDER-SHIP');
        $responseShipped->assertDontSee('WHI-ORDER-PROC');

        // Filter: all
        $responseAll = $this->actingAs($sellerUser)->get(route('seller.orders.index', ['status' => 'all']));
        $responseAll->assertOk();
        $responseAll->assertSee('WHI-ORDER-PROC');
        $responseAll->assertSee('WHI-ORDER-SHIP');
    }

    public function test_seller_cannot_see_other_seller_orders(): void
    {
        $sellerUser1 = User::factory()->create(['role' => UserRole::SELLER]);
        $seller1 = Seller::create([
            'user_id' => $sellerUser1->id,
            'store_name' => 'Seller One Store',
            'username' => 'seller_one',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567891',
            'bank_account_name' => 'Seller One',
            'status' => SellerStatus::VERIFIED,
        ]);

        $sellerUser2 = User::factory()->create(['role' => UserRole::SELLER]);
        $seller2 = Seller::create([
            'user_id' => $sellerUser2->id,
            'store_name' => 'Seller Two Store',
            'username' => 'seller_two',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567892',
            'bank_account_name' => 'Seller Two',
            'status' => SellerStatus::VERIFIED,
        ]);

        $buyer = User::factory()->create(['role' => UserRole::BUYER]);

        $order1 = Order::create([
            'order_number' => 'WHI-SELLER1-ORDER',
            'buyer_id' => $buyer->id,
            'seller_id' => $seller1->id,
            'total_amount' => 100000,
            'shipping_cost' => 10000,
            'admin_fee' => 1000,
            'grand_total' => 111000,
            'status' => Paid::class,
            'address_snapshot' => ['recipient_name' => 'Buyer', 'full_address' => 'Addr'],
        ]);

        $order2 = Order::create([
            'order_number' => 'WHI-SELLER2-ORDER',
            'buyer_id' => $buyer->id,
            'seller_id' => $seller2->id,
            'total_amount' => 200000,
            'shipping_cost' => 10000,
            'admin_fee' => 1000,
            'grand_total' => 211000,
            'status' => Paid::class,
            'address_snapshot' => ['recipient_name' => 'Buyer', 'full_address' => 'Addr'],
        ]);

        $response = $this->actingAs($sellerUser1)->get(route('seller.orders.index'));

        $response->assertOk();
        $response->assertSee('WHI-SELLER1-ORDER');
        $response->assertDontSee('WHI-SELLER2-ORDER');
    }
}
