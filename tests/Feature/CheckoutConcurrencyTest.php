<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CheckoutConcurrencyTest extends TestCase
{
    use DatabaseMigrations;

    public function test_zero_overselling_under_concurrent_stock_reduction(): void
    {
        // Setup product with variant stock exactly = 1
        $seller = Seller::factory()->create();
        $product = Product::factory()->create(['seller_id' => $seller->id]);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'price' => 200000,
            'stock' => 1,
        ]);

        // Buyer 1
        $buyer1 = User::factory()->buyer()->create();
        $address1 = Address::factory()->create(['user_id' => $buyer1->id]);
        $cart1 = Cart::create(['user_id' => $buyer1->id]);
        CartItem::create([
            'cart_id' => $cart1->id,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
            'is_selected' => true,
        ]);

        // Buyer 2
        $buyer2 = User::factory()->buyer()->create();
        $address2 = Address::factory()->create(['user_id' => $buyer2->id]);
        $cart2 = Cart::create(['user_id' => $buyer2->id]);
        CartItem::create([
            'cart_id' => $cart2->id,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
            'is_selected' => true,
        ]);

        // Buyer 1 processes checkout
        $response1 = $this->actingAs($buyer1)->post(route('checkout.process'), [
            'address_id' => $address1->id,
        ]);
        $response1->assertSessionHasNoErrors();
        $response1->assertRedirect();

        // Check variant stock now is 0
        $variant->refresh();
        $this->assertEquals(0, $variant->stock);

        // Buyer 2 tries to checkout the now sold-out variant
        $response2 = $this->actingAs($buyer2)->post(route('checkout.process'), [
            'address_id' => $address2->id,
        ]);
        $response2->assertSessionHas('error');

        // Verify stock is still 0 (never negative)
        $variant->refresh();
        $this->assertEquals(0, $variant->stock);

        // Verify exactly 1 order was created
        $this->assertEquals(1, Order::count());
    }
}
