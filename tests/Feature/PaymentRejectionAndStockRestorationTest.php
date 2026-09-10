<?php

namespace Tests\Feature;

use App\Enums\PaymentStatus;
use App\Enums\SellerStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;
use App\States\Order\Cancelled;
use App\States\Order\PaymentVerification;
use App\States\Order\PendingPayment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentRejectionAndStockRestorationTest extends TestCase
{
    use RefreshDatabase;

    private function createOrderFixture(int $initialStock = 5, int $quantity = 2): array
    {
        $sellerUser = User::factory()->create(['role' => UserRole::SELLER]);
        $seller = Seller::create([
            'user_id' => $sellerUser->id,
            'store_name' => 'Toko Cellos Official',
            'username' => 'cellos_official',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Cellos Official',
            'status' => SellerStatus::VERIFIED,
        ]);

        $buyer = User::factory()->create(['role' => UserRole::BUYER, 'name' => 'Budi Pembeli']);

        $product = Product::factory()->create([
            'seller_id' => $seller->id,
            'name' => 'Jaket Hoodie WhiMarket',
        ]);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'name' => 'Hitam / XL',
            'stock' => $initialStock,
            'price' => 100000,
        ]);

        $order = Order::create([
            'order_number' => 'WHI-2026-REJECT01',
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'total_amount' => 200000,
            'shipping_cost' => 15000,
            'admin_fee' => 1000,
            'grand_total' => 216000,
            'status' => PaymentVerification::class,
            'address_snapshot' => [
                'recipient_name' => 'Budi Pembeli',
                'phone' => '081234567890',
                'full_address' => 'Jl. Sudirman No. 12',
                'district' => 'Kebayoran Baru',
                'city' => 'Jakarta Selatan',
                'province' => 'DKI Jakarta',
                'postal_code' => '12190',
            ],
        ]);

        $order->items()->create([
            'product_variant_id' => $variant->id,
            'product_name_snapshot' => 'Jaket Hoodie WhiMarket',
            'variant_name_snapshot' => 'Hitam / XL',
            'price_snapshot' => 100000,
            'quantity' => $quantity,
            'subtotal' => 200000,
        ]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'bank_destination' => 'BCA',
            'sender_bank_name' => 'Mandiri',
            'sender_account_name' => 'Budi Pembeli',
            'proof_path' => 'payments/dummy_proof.jpg',
            'amount' => 216000,
            'status' => PaymentStatus::PENDING_REVIEW,
        ]);

        return compact('sellerUser', 'seller', 'buyer', 'product', 'variant', 'order', 'payment');
    }

    public function test_reject_payment_cancels_order_and_restores_stock(): void
    {
        $fixture = $this->createOrderFixture(initialStock: 3, quantity: 2);
        $order = $fixture['order'];
        $variant = $fixture['variant'];
        $payment = $fixture['payment'];

        $this->assertEquals(3, $variant->fresh()->stock);

        // Admin rejects payment proof with cancel & restore
        $payment->update([
            'status' => PaymentStatus::REJECTED,
            'rejection_reason' => 'Mutasi rekening BCA tidak ditemukan dengan nominal Rp 216.000.',
        ]);

        if ($order->status->canTransitionTo(Cancelled::class)) {
            $order->status->transitionTo(Cancelled::class);
        }
        $restored = $order->restoreStock();

        $this->assertTrue($restored);
        $this->assertTrue($order->fresh()->status->equals(Cancelled::class));
        $this->assertTrue($order->fresh()->is_stock_restored);
        // Stock should now be restored from 3 to 5 (3 + 2)
        $this->assertEquals(5, $variant->fresh()->stock);

        // Idempotency: second call does not restore again
        $secondRestore = $order->restoreStock();
        $this->assertFalse($secondRestore);
        $this->assertEquals(5, $variant->fresh()->stock);
    }

    public function test_reject_payment_with_request_reupload_keeps_stock_reserved(): void
    {
        $fixture = $this->createOrderFixture(initialStock: 1, quantity: 1);
        $order = $fixture['order'];
        $variant = $fixture['variant'];
        $payment = $fixture['payment'];

        // Admin rejects but gives chance to reupload
        $payment->update([
            'status' => PaymentStatus::REJECTED,
            'rejection_reason' => 'Foto bukti buram dan nomor referensi terpotong.',
        ]);

        if ($order->status->canTransitionTo(PendingPayment::class)) {
            $order->status->transitionTo(PendingPayment::class);
        }

        $this->assertTrue($order->fresh()->status->equals(PendingPayment::class));
        $this->assertFalse($order->fresh()->is_stock_restored);
        // Stock remains held at 1
        $this->assertEquals(1, $variant->fresh()->stock);
    }

    public function test_buyer_sees_rejection_reason_and_reorder_on_orders_page(): void
    {
        $fixture = $this->createOrderFixture(initialStock: 5, quantity: 2);
        $order = $fixture['order'];
        $payment = $fixture['payment'];
        $buyer = $fixture['buyer'];

        $payment->update([
            'status' => PaymentStatus::REJECTED,
            'rejection_reason' => 'Nominal transfer tidak sesuai dengan tagihan.',
        ]);
        $order->status->transitionTo(Cancelled::class);
        $order->restoreStock();

        // 1. Check order listing
        $responseIndex = $this->actingAs($buyer)->get(route('orders.index'));
        $responseIndex->assertOk();
        $responseIndex->assertSee('Pembayaran Ditolak');
        $responseIndex->assertSee('Nominal transfer tidak sesuai dengan tagihan.');
        $responseIndex->assertSee('Beli Lagi');

        // 2. Check order detail
        $responseShow = $this->actingAs($buyer)->get(route('orders.show', $order->order_number));
        $responseShow->assertOk();
        $responseShow->assertSee('Bukti Transfer Pembayaran Ditolak oleh Admin');
        $responseShow->assertSee('Nominal transfer tidak sesuai dengan tagihan.');
        $responseShow->assertSee('Pesan Ulang / Beli Lagi');
    }

    public function test_buyer_sees_rejected_badge_even_if_order_status_is_payment_verification(): void
    {
        $fixture = $this->createOrderFixture(initialStock: 5, quantity: 2);
        $order = $fixture['order'];
        $payment = $fixture['payment'];
        $buyer = $fixture['buyer'];

        $payment->update([
            'status' => PaymentStatus::REJECTED,
            'rejection_reason' => 'Mutasi palsu',
        ]);
        // Order remains in PaymentVerification
        $this->assertEquals('payment_verification', $order->fresh()->status::$name);

        $responseIndex = $this->actingAs($buyer)->get(route('orders.index'));
        $responseIndex->assertOk();
        $responseIndex->assertSee('Pembayaran Ditolak');
        $responseIndex->assertDontSee('Sedang Di Verifikasi Pembayaran Oleh Admin');
        $responseIndex->assertSee('Mutasi palsu');
    }

    public function test_buyer_can_cancel_pending_order_and_stock_is_restored(): void
    {
        $fixture = $this->createOrderFixture(initialStock: 2, quantity: 2);
        $order = $fixture['order'];
        $variant = $fixture['variant'];
        $buyer = $fixture['buyer'];

        $responseCancel = $this->actingAs($buyer)->post(route('orders.cancel', $order->order_number));
        $responseCancel->assertRedirect(route('orders.show', $order->order_number));
        $responseCancel->assertSessionHas('success');

        $this->assertTrue($order->fresh()->status->equals(Cancelled::class));
        $this->assertTrue($order->fresh()->is_stock_restored);
        // Stock restored from 2 to 4
        $this->assertEquals(4, $variant->fresh()->stock);
    }

    public function test_buyer_can_reorder_cancelled_order_items(): void
    {
        $fixture = $this->createOrderFixture(initialStock: 5, quantity: 2);
        $order = $fixture['order'];
        $buyer = $fixture['buyer'];

        $order->status->transitionTo(Cancelled::class);
        $order->restoreStock();

        $responseReorder = $this->actingAs($buyer)->post(route('orders.reorder', $order->order_number));
        $responseReorder->assertRedirect(route('cart.index'));
        $responseReorder->assertSessionHas('success');

        // Assert item was added to buyer's cart
        $this->assertDatabaseHas('cart_items', [
            'product_variant_id' => $fixture['variant']->id,
            'quantity' => 2,
            'is_selected' => true,
        ]);
    }
}
