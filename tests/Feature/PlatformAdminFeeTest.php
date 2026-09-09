<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\PlatformSetting;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;
use App\Services\ShippingRateService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PlatformAdminFeeTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'buyer', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'seller', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    }

    public function test_default_admin_fee_setting_can_be_retrieved(): void
    {
        PlatformSetting::set('admin_fee', 2000, 'Biaya Layanan Pembeli');

        $this->assertEquals(2000.0, PlatformSetting::getAdminFee());
        $this->assertTrue(PlatformSetting::isAdminFeeActive());
    }

    public function test_checkout_applies_dynamic_admin_fee_to_order_and_payment(): void
    {
        PlatformSetting::updateOrCreate(
            ['key' => 'admin_fee'],
            [
                'name' => 'Biaya Layanan Pembeli',
                'value' => '2500',
                'is_active' => true,
            ]
        );

        $sellerUser = User::factory()->seller()->create();
        $seller = Seller::factory()->create(['user_id' => $sellerUser->id]);

        $buyer = User::factory()->buyer()->create();
        $address = Address::factory()->create(['user_id' => $buyer->id, 'province' => 'DKI Jakarta']);

        $product = Product::factory()->create(['seller_id' => $seller->id]);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 10,
            'price' => 100000,
        ]);

        $cart = Cart::create(['user_id' => $buyer->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
            'is_selected' => true,
        ]);

        // Process checkout
        $response = $this->actingAs($buyer)->post(route('checkout.process'), [
            'address_id' => $address->id,
        ]);

        $response->assertRedirect();

        $order = Order::where('buyer_id', $buyer->id)->first();
        $this->assertNotNull($order);

        $expectedSubtotal = 200000.00;
        $expectedShipping = (float) ShippingRateService::calculate($address->province)['cost'];
        $expectedAdminFee = 2500.00;
        $expectedGrandTotal = $expectedSubtotal + $expectedShipping + $expectedAdminFee;

        $this->assertEquals($expectedSubtotal, (float) $order->total_amount);
        $this->assertEquals($expectedShipping, (float) $order->shipping_cost);
        $this->assertEquals($expectedAdminFee, (float) $order->admin_fee);
        $this->assertEquals($expectedGrandTotal, (float) $order->grand_total);

        // Payment amount must equal grand_total
        $this->assertNotNull($order->payment);
        $this->assertEquals($expectedGrandTotal, (float) $order->payment->amount);
    }

    public function test_disabled_admin_fee_charges_zero_to_buyer(): void
    {
        PlatformSetting::updateOrCreate(
            ['key' => 'admin_fee'],
            [
                'name' => 'Biaya Layanan Pembeli',
                'value' => '2500',
                'is_active' => false,
            ]
        );

        $this->assertEquals(0.0, PlatformSetting::getAdminFee());

        $sellerUser = User::factory()->seller()->create();
        $seller = Seller::factory()->create(['user_id' => $sellerUser->id]);

        $buyer = User::factory()->buyer()->create();
        $address = Address::factory()->create(['user_id' => $buyer->id, 'province' => 'DKI Jakarta']);

        $product = Product::factory()->create(['seller_id' => $seller->id]);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 5,
            'price' => 50000,
        ]);

        $cart = Cart::create(['user_id' => $buyer->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
            'is_selected' => true,
        ]);

        $this->actingAs($buyer)->post(route('checkout.process'), [
            'address_id' => $address->id,
        ]);

        $order = Order::where('buyer_id', $buyer->id)->first();
        $this->assertNotNull($order);

        $expectedShipping = (float) ShippingRateService::calculate($address->province)['cost'];
        $this->assertEquals(0.0, (float) $order->admin_fee);
        $this->assertEquals(50000 + $expectedShipping, (float) $order->grand_total);
    }

    public function test_checkout_index_displays_configured_admin_fee(): void
    {
        PlatformSetting::updateOrCreate(
            ['key' => 'admin_fee'],
            [
                'name' => 'Biaya Layanan Pembeli',
                'value' => '3000',
                'is_active' => true,
            ]
        );

        $sellerUser = User::factory()->seller()->create();
        $seller = Seller::factory()->create(['user_id' => $sellerUser->id]);

        $buyer = User::factory()->buyer()->create();
        Address::factory()->create(['user_id' => $buyer->id, 'is_default' => true]);

        $product = Product::factory()->create(['seller_id' => $seller->id]);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 5,
            'price' => 75000,
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
        $response->assertViewHas('adminFee', 3000.0);
        $response->assertSee('Biaya Layanan');
        $response->assertSee('Fee Admin');
    }
}
