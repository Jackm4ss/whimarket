<?php

namespace Tests\Feature;

use App\Enums\PaymentStatus;
use App\Enums\PayoutStatus;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\EscrowBalance;
use App\Models\Order;
use App\Models\Payout;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;
use App\States\Order\Completed;
use App\States\Order\Delivered;
use App\States\Order\Paid;
use App\States\Order\PaymentVerification;
use App\States\Order\PendingPayment;
use App\States\Order\Shipped;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OrderLifecycleTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'buyer', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'seller', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    }

    public function test_full_order_lifecycle_from_checkout_to_payout(): void
    {
        Storage::fake('public');

        // 1. Setup Admin, Seller, Buyer
        $admin = User::factory()->admin()->create();
        $admin->assignRole('admin');

        $sellerUser = User::factory()->seller()->create();
        $sellerUser->assignRole('seller');
        $seller = Seller::factory()->create(['user_id' => $sellerUser->id]);

        $buyer = User::factory()->buyer()->create();
        $buyer->assignRole('buyer');
        $address = Address::factory()->create(['user_id' => $buyer->id]);

        $product = Product::factory()->create(['seller_id' => $seller->id]);
        $variant = ProductVariant::factory()->create(['product_id' => $product->id, 'stock' => 5, 'price' => 300000]);

        // Cart item
        $cart = Cart::create(['user_id' => $buyer->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
            'is_selected' => true,
        ]);

        // 2. Checkout
        $responseCheckout = $this->actingAs($buyer)->post(route('checkout.process'), [
            'address_id' => $address->id,
        ]);
        $responseCheckout->assertRedirect();

        $order = Order::where('buyer_id', $buyer->id)->first();
        $this->assertNotNull($order);
        $this->assertTrue($order->status->equals(PendingPayment::class));
        $this->assertEquals(4, $variant->fresh()->stock);

        // 3. Buyer uploads payment proof
        $file = UploadedFile::fake()->create('bukti.jpg', 100, 'image/jpeg');
        $responseProof = $this->actingAs($buyer)->post(route('payment.upload', $order->order_number), [
            'sender_bank_name' => 'BCA',
            'sender_account_name' => 'Budi Pembeli',
            'proof' => $file,
        ]);
        $responseProof->assertRedirect();

        $order->refresh();
        $this->assertTrue($order->status->equals(PaymentVerification::class));
        $this->assertEquals(PaymentStatus::PENDING_REVIEW, $order->payment->status);

        // 4. Admin approves payment
        $order->payment->update([
            'status' => PaymentStatus::VERIFIED,
            'verified_by' => $admin->id,
            'verified_at' => now(),
        ]);
        $order->status->transitionTo(Paid::class);

        $order->refresh();
        $this->assertTrue($order->status->equals(Paid::class));

        // 5. Seller ships package & inputs tracking
        $preShipmentPhoto = UploadedFile::fake()->create('packing.jpg', 100, 'image/jpeg');
        $receiptPhoto = UploadedFile::fake()->create('resi.jpg', 100, 'image/jpeg');

        $responseShip = $this->actingAs($sellerUser)->post("/seller/orders/{$order->id}/fulfill", [
            'courier_name' => 'J&T Express',
            'tracking_number' => 'JT1234567890',
            'pre_shipment_photo' => $preShipmentPhoto,
            'receipt_photo' => $receiptPhoto,
        ]);
        $responseShip->assertRedirect();

        $order->refresh();
        $this->assertTrue($order->status->equals(Shipped::class));
        $this->assertNotNull($order->shipment);
        $this->assertEquals('JT1234567890', $order->shipment->tracking_number);

        // 6. Buyer confirms delivery (Jalur A - Fast Path)
        $responseDelivered = $this->actingAs($buyer)->post(route('orders.confirm_delivered', $order->order_number));
        $responseDelivered->assertRedirect();

        $order->refresh();
        $this->assertTrue($order->status->equals(Delivered::class));
        $this->assertNotNull($order->inspection_deadline_at);

        // 7. Buyer completes order
        $responseComplete = $this->actingAs($buyer)->post(route('orders.complete', $order->order_number));
        $responseComplete->assertRedirect();

        $order->refresh();
        $this->assertTrue($order->status->equals(Completed::class));

        // 8. Verify Escrow Released & Payout Created
        $escrow = EscrowBalance::where('order_id', $order->id)->first();
        $this->assertNotNull($escrow);
        $this->assertTrue($escrow->is_released);

        $payout = Payout::where('order_id', $order->id)->first();
        $this->assertNotNull($payout);
        $this->assertEquals(PayoutStatus::PENDING, $payout->status);
        $this->assertEquals($order->total_amount, $payout->amount);
    }

    public function test_buyer_can_checkout_and_upload_payment_proof_when_variant_stock_is_one(): void
    {
        Storage::fake('public');

        $sellerUser = User::factory()->seller()->create();
        $sellerUser->assignRole('seller');
        $seller = Seller::factory()->create(['user_id' => $sellerUser->id]);

        $buyer = User::factory()->buyer()->create();
        $buyer->assignRole('buyer');
        $address = Address::factory()->create(['user_id' => $buyer->id]);

        $product = Product::factory()->create(['seller_id' => $seller->id]);
        $variant = ProductVariant::factory()->create(['product_id' => $product->id, 'stock' => 1, 'price' => 250000]);

        $cart = Cart::create(['user_id' => $buyer->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
            'is_selected' => true,
        ]);

        // Checkout 1-stock item
        $responseCheckout = $this->actingAs($buyer)->post(route('checkout.process'), [
            'address_id' => $address->id,
        ]);
        $responseCheckout->assertRedirect();

        $order = Order::where('buyer_id', $buyer->id)->first();
        $this->assertNotNull($order);
        $this->assertTrue($order->status->equals(PendingPayment::class));
        $this->assertEquals(0, $variant->fresh()->stock);

        // Visit payment page: must be accessible without out-of-stock warning
        $responsePayment = $this->actingAs($buyer)->get(route('payment.show', $order->order_number));
        $responsePayment->assertOk();
        $responsePayment->assertDontSee('Stok Barang Ini Telah Habis');

        // Upload proof: must succeed despite remaining catalog stock being 0
        $file = UploadedFile::fake()->create('bukti_transfer.png', 120, 'image/png');
        $responseProof = $this->actingAs($buyer)->post(route('payment.upload', $order->order_number), [
            'sender_bank_name' => 'BCA',
            'sender_account_name' => 'Budi Pembeli',
            'proof' => $file,
        ]);
        $responseProof->assertRedirect();

        $order->refresh();
        $this->assertTrue($order->status->equals(PaymentVerification::class));
        $this->assertEquals(PaymentStatus::PENDING_REVIEW, $order->payment->status);
    }
}
