<?php

namespace Tests\Feature;

use App\Models\Dispute;
use App\Models\Order;
use App\Models\Seller;
use App\Models\User;
use App\States\Dispute\OpenDispute;
use App\States\Dispute\ResolvedRefund;
use App\States\Dispute\SellerResponded;
use App\States\Order\Cancelled;
use App\States\Order\Delivered;
use App\States\Order\Disputed;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DisputeResolutionTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'buyer', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'seller', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    }

    public function test_dispute_filing_seller_response_and_admin_refund(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $admin->assignRole('admin');

        $sellerUser = User::factory()->seller()->create();
        $sellerUser->assignRole('seller');
        $seller = Seller::factory()->create(['user_id' => $sellerUser->id]);

        $buyer = User::factory()->buyer()->create();
        $buyer->assignRole('buyer');

        // Delivered order awaiting 48h inspection
        $order = Order::factory()->create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'status' => Delivered::class,
            'inspection_deadline_at' => now()->addHours(30),
        ]);

        // 1. Buyer files dispute
        $evidence = UploadedFile::fake()->create('cacat.jpg', 100, 'image/jpeg');
        $responseDispute = $this->actingAs($buyer)->post(route('dispute.store', $order->order_number), [
            'reason' => 'Barang Rusak / Cacat Fisik',
            'description' => 'Bagian resleting jaket rusak saat unboxing.',
            'evidence' => [$evidence],
        ]);
        $responseDispute->assertRedirect();

        $order->refresh();
        $this->assertTrue($order->status->equals(Disputed::class));

        $dispute = Dispute::where('order_id', $order->id)->first();
        $this->assertNotNull($dispute);
        $this->assertTrue($dispute->status->equals(OpenDispute::class));

        // 2. Seller responds to dispute
        $counterEvidence = UploadedFile::fake()->create('packing_sebelum_kirim.jpg', 100, 'image/jpeg');
        $responseSeller = $this->actingAs($sellerUser)->post("/seller/disputes/{$dispute->id}/respond", [
            'seller_response' => 'Kondisi resleting sudah dicek lancar saat packing.',
            'seller_evidence' => [$counterEvidence],
        ]);
        $responseSeller->assertRedirect();

        $dispute->refresh();
        $this->assertTrue($dispute->status->equals(SellerResponded::class));

        // 3. Admin reviews and approves refund to buyer
        $dispute->update([
            'status' => ResolvedRefund::class,
            'resolution_notes' => 'Kerusakan fisik valid berdasarkan bukti unboxing.',
            'resolved_by' => $admin->id,
            'resolved_at' => now(),
        ]);
        $order->status->transitionTo(Cancelled::class);

        $order->refresh();
        $dispute->refresh();
        $this->assertTrue($dispute->status->equals(ResolvedRefund::class));
        $this->assertTrue($order->status->equals(Cancelled::class));
    }
}
