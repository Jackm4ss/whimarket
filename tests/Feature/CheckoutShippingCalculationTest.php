<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\PlatformSetting;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CheckoutShippingCalculationTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'buyer', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'seller', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    }

    public function test_checkout_index_does_not_charge_default_shipping_when_buyer_has_no_address(): void
    {
        PlatformSetting::updateOrCreate(
            ['key' => 'admin_fee'],
            [
                'name' => 'Biaya Layanan Pembeli',
                'value' => '2000',
                'is_active' => true,
            ]
        );

        $sellerUser = User::factory()->seller()->create();
        $seller = Seller::factory()->create(['user_id' => $sellerUser->id]);

        $buyer = User::factory()->buyer()->create();

        $product = Product::factory()->create(['seller_id' => $seller->id]);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 5,
            'price' => 100000,
        ]);

        $cart = Cart::create(['user_id' => $buyer->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
            'is_selected' => true,
        ]);

        $response = $this->actingAs($buyer)->get(route('checkout.index'));
        $response->assertOk();
        $response->assertViewHas('shippingCost', 0);
        $response->assertViewHas('defaultAddress', null);
        $response->assertViewHas('grandTotal', 102000.0); // 100000 subtotal + 0 shipping + 2000 adminFee
        $response->assertSee('Menunggu Alamat');
        $response->assertSee('Belum Ada Alamat Pengiriman');
    }

    public function test_checkout_index_charges_proper_shipping_when_buyer_has_address(): void
    {
        PlatformSetting::updateOrCreate(
            ['key' => 'admin_fee'],
            [
                'name' => 'Biaya Layanan Pembeli',
                'value' => '2000',
                'is_active' => true,
            ]
        );

        $sellerUser = User::factory()->seller()->create();
        $seller = Seller::factory()->create(['user_id' => $sellerUser->id]);

        $buyer = User::factory()->buyer()->create();
        $address = Address::factory()->create([
            'user_id' => $buyer->id,
            'province' => 'DKI Jakarta',
            'is_default' => true,
        ]);

        $product = Product::factory()->create(['seller_id' => $seller->id]);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 5,
            'price' => 100000,
        ]);

        $cart = Cart::create(['user_id' => $buyer->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
            'is_selected' => true,
        ]);

        $response = $this->actingAs($buyer)->get(route('checkout.index'));
        $response->assertOk();
        $response->assertViewHas('shippingCost', 15000);
        $response->assertViewHas('defaultAddress', fn ($a) => $a->id === $address->id);
        $response->assertViewHas('grandTotal', 117000.0); // 100000 subtotal + 15000 shipping + 2000 adminFee
        $response->assertSee('Pengiriman Standar / Reguler');
        $response->assertSee('Pilihan Resmi');
    }

    public function test_checkout_process_requires_address(): void
    {
        $sellerUser = User::factory()->seller()->create();
        $seller = Seller::factory()->create(['user_id' => $sellerUser->id]);

        $buyer = User::factory()->buyer()->create();

        $product = Product::factory()->create(['seller_id' => $seller->id]);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 5,
            'price' => 100000,
        ]);

        $cart = Cart::create(['user_id' => $buyer->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
            'is_selected' => true,
        ]);

        $response = $this->actingAs($buyer)->post(route('checkout.process'), [
            'address_id' => '',
        ]);

        $response->assertSessionHasErrors(['address_id']);
    }
}
