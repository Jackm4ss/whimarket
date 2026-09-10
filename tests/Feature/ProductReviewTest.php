<?php

namespace Tests\Feature;

use App\Enums\ProductCondition;
use App\Enums\ProductStatus;
use App\Enums\SellerStatus;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;
use App\States\Order\Completed;
use App\States\Order\Shipped;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProductReviewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'buyer', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'seller', 'guard_name' => 'web']);
    }

    private function createOrderFixture(string $orderState = Completed::class): array
    {
        $buyer = User::factory()->buyer()->create();

        $sellerUser = User::factory()->seller()->create();
        $seller = Seller::create([
            'user_id' => $sellerUser->id,
            'store_name' => 'Toko Kreator Official',
            'username' => 'tokokreator',
            'bio' => 'Official creator store.',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Toko Kreator',
            'status' => SellerStatus::VERIFIED,
            'verified_at' => now(),
        ]);

        $category = Category::firstOrCreate(['slug' => 'fashion'], ['name' => 'Fashion', 'is_active' => true]);

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Kaos Oversized WhiMarket',
            'slug' => 'kaos-oversized-whimarket',
            'description' => 'Kaos berkualitas tinggi.',
            'price' => 150000,
            'condition' => ProductCondition::LIKE_NEW,
            'status' => ProductStatus::ACTIVE,
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Size XL',
            'sku' => 'WHI-TSH-XL',
            'price' => 150000,
            'stock' => 20,
        ]);

        $order = Order::create([
            'order_number' => 'WHI-ORD-'.uniqid(),
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'address_snapshot' => [
                'recipient_name' => 'Budi Santoso',
                'phone' => '081234567890',
                'address' => 'Jl. Mawar No. 10',
                'province_name' => 'DKI Jakarta',
                'city_name' => 'Jakarta Selatan',
                'postal_code' => '12345',
            ],
            'total_amount' => 150000,
            'shipping_cost' => 10000,
            'grand_total' => 160000,
            'status' => $orderState,
            'completed_at' => $orderState === Completed::class ? now() : null,
        ]);

        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_variant_id' => $variant->id,
            'product_name_snapshot' => $product->name,
            'variant_name_snapshot' => $variant->name,
            'price_snapshot' => $variant->price,
            'quantity' => 1,
            'subtotal' => 150000,
        ]);

        return [$buyer, $seller, $product, $variant, $order, $orderItem];
    }

    public function test_guest_cannot_access_review_page_or_submit_review(): void
    {
        [$buyer, $seller, $product, $variant, $order, $orderItem] = $this->createOrderFixture();

        $this->get(route('reviews.create', $order->order_number))
            ->assertRedirect(route('login'));

        $this->post(route('reviews.store', $order->order_number), [
            'order_item_id' => $orderItem->id,
            'rating' => 5,
            'comment' => 'Bagus!',
        ])->assertRedirect(route('login'));
    }

    public function test_buyer_cannot_review_order_that_is_not_completed(): void
    {
        [$buyer, , , , $order, $orderItem] = $this->createOrderFixture(Shipped::class);

        $response = $this->actingAs($buyer)
            ->get(route('reviews.create', $order->order_number));

        $response->assertRedirect(route('orders.show', $order->order_number));
        $response->assertSessionHas('error');

        $storeResponse = $this->actingAs($buyer)
            ->post(route('reviews.store', $order->order_number), [
                'order_item_id' => $orderItem->id,
                'rating' => 5,
                'comment' => 'Bagus!',
            ]);

        $storeResponse->assertRedirect(route('orders.show', $order->order_number));
        $this->assertDatabaseCount('product_reviews', 0);
    }

    public function test_user_cannot_review_another_buyers_order(): void
    {
        [, , , , $order, $orderItem] = $this->createOrderFixture(Completed::class);
        $otherBuyer = User::factory()->buyer()->create();

        $this->actingAs($otherBuyer)
            ->get(route('reviews.create', $order->order_number))
            ->assertNotFound();

        $this->actingAs($otherBuyer)
            ->post(route('reviews.store', $order->order_number), [
                'order_item_id' => $orderItem->id,
                'rating' => 5,
                'comment' => 'Mencoba review order orang lain',
            ])->assertNotFound();

        $this->assertDatabaseCount('product_reviews', 0);
    }

    public function test_buyer_can_submit_review_for_completed_order(): void
    {
        [$buyer, , $product, , $order, $orderItem] = $this->createOrderFixture(Completed::class);

        $response = $this->actingAs($buyer)
            ->post(route('reviews.store', $order->order_number), [
                'order_item_id' => $orderItem->id,
                'rating' => 5,
                'comment' => 'Kualitas kaos luar biasa, bahan tebal dan adem!',
            ]);

        $response->assertRedirect(route('orders.show', $order->order_number));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('product_reviews', [
            'order_id' => $order->id,
            'order_item_id' => $orderItem->id,
            'product_id' => $product->id,
            'user_id' => $buyer->id,
            'rating' => 5,
            'comment' => 'Kualitas kaos luar biasa, bahan tebal dan adem!',
        ]);

        // Order detail view should display reviewed status
        $showResponse = $this->actingAs($buyer)->get(route('orders.show', $order->order_number));
        $showResponse->assertOk();
        $showResponse->assertSee('Sudah Diulas');
        $showResponse->assertSee('5/5');
    }

    public function test_buyer_cannot_submit_duplicate_review_for_same_order_item(): void
    {
        [$buyer, , $product, , $order, $orderItem] = $this->createOrderFixture(Completed::class);

        ProductReview::create([
            'order_id' => $order->id,
            'order_item_id' => $orderItem->id,
            'product_id' => $product->id,
            'user_id' => $buyer->id,
            'rating' => 5,
            'comment' => 'Ulasan pertama',
        ]);

        $response = $this->actingAs($buyer)
            ->post(route('reviews.store', $order->order_number), [
                'order_item_id' => $orderItem->id,
                'rating' => 4,
                'comment' => 'Ulasan kedua yang tidak boleh masuk',
            ]);

        $response->assertSessionHas('info');
        $this->assertDatabaseCount('product_reviews', 1);
    }

    public function test_review_validation_requires_valid_rating_between_1_and_5(): void
    {
        [$buyer, , , , $order, $orderItem] = $this->createOrderFixture(Completed::class);

        $this->actingAs($buyer)
            ->post(route('reviews.store', $order->order_number), [
                'order_item_id' => $orderItem->id,
                'rating' => 6, // Invalid > 5
                'comment' => 'Rating kebesaran',
            ])->assertSessionHasErrors('rating');

        $this->actingAs($buyer)
            ->post(route('reviews.store', $order->order_number), [
                'order_item_id' => $orderItem->id,
                'rating' => 0, // Invalid < 1
                'comment' => 'Rating nol',
            ])->assertSessionHasErrors('rating');

        $this->assertDatabaseCount('product_reviews', 0);
    }

    public function test_product_detail_page_displays_real_review_and_calculates_average_rating(): void
    {
        [$buyer, , $product, $variant, $order1, $orderItem1] = $this->createOrderFixture(Completed::class);

        // First review: 5 stars
        ProductReview::create([
            'order_id' => $order1->id,
            'order_item_id' => $orderItem1->id,
            'product_id' => $product->id,
            'user_id' => $buyer->id,
            'rating' => 5,
            'comment' => 'Mantap pol bahan super adem!',
        ]);

        // Second buyer and order for the same product: 3 stars
        $buyer2 = User::factory()->buyer()->create();
        $order2 = Order::create([
            'order_number' => 'WHI-ORD-'.uniqid(),
            'buyer_id' => $buyer2->id,
            'seller_id' => $order1->seller_id,
            'address_snapshot' => $order1->address_snapshot,
            'total_amount' => 150000,
            'shipping_cost' => 10000,
            'grand_total' => 160000,
            'status' => Completed::class,
            'completed_at' => now(),
        ]);
        $orderItem2 = OrderItem::create([
            'order_id' => $order2->id,
            'product_variant_id' => $variant->id,
            'product_name_snapshot' => $product->name,
            'variant_name_snapshot' => $variant->name,
            'price_snapshot' => $variant->price,
            'quantity' => 1,
            'subtotal' => 150000,
        ]);

        ProductReview::create([
            'order_id' => $order2->id,
            'order_item_id' => $orderItem2->id,
            'product_id' => $product->id,
            'user_id' => $buyer2->id,
            'rating' => 3,
            'comment' => 'Ukuran agak sedikit sempit tapi masih oke.',
        ]);

        // Average: (5 + 3) / 2 = 4.0
        $response = $this->get(route('product.detail', $product->slug));
        $response->assertOk();
        $response->assertSee('4');
        $response->assertSee('2 ulasan');
        $response->assertSee('Mantap pol bahan super adem!');
        $response->assertSee('Ukuran agak sedikit sempit tapi masih oke.');
    }

    public function test_buyer_can_upload_review_photos(): void
    {
        Storage::fake('public');
        [$buyer, , $product, , $order, $orderItem] = $this->createOrderFixture(Completed::class);
        $file1 = UploadedFile::fake()->create('unboxing1.jpg', 100, 'image/jpeg');
        $file2 = UploadedFile::fake()->create('unboxing2.png', 100, 'image/png');
        $response = $this->actingAs($buyer)
            ->post(route('reviews.store', $order->order_number), [
                'order_item_id' => $orderItem->id,
                'rating' => 5,
                'comment' => 'Ada foto unboxingnya juga!',
                'photos' => [$file1, $file2],
            ]);

        $response->assertRedirect(route('orders.show', $order->order_number));

        $review = ProductReview::first();
        $this->assertNotNull($review);
        $this->assertCount(2, $review->photos);
        $this->assertStringStartsWith('/storage/reviews/', $review->photos[0]);
    }

    public function test_buyer_can_upload_review_video(): void
    {
        Storage::fake('public');
        [$buyer, , $product, , $order, $orderItem] = $this->createOrderFixture(Completed::class);

        $video = UploadedFile::fake()->create('unboxing.mp4', 5000, 'video/mp4');

        $response = $this->actingAs($buyer)
            ->post(route('reviews.store', $order->order_number), [
                'order_item_id' => $orderItem->id,
                'rating' => 5,
                'comment' => 'Video unboxing kaos keren!',
                'video' => $video,
            ]);

        $response->assertRedirect(route('orders.show', $order->order_number));

        $review = ProductReview::first();
        $this->assertNotNull($review);
        $this->assertNotNull($review->video);
        $this->assertStringStartsWith('/storage/reviews/videos/', $review->video);
    }

    public function test_review_video_validation_rejects_invalid_video_format(): void
    {
        Storage::fake('public');
        [$buyer, , , , $order, $orderItem] = $this->createOrderFixture(Completed::class);

        $invalidFile = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');

        $response = $this->actingAs($buyer)
            ->post(route('reviews.store', $order->order_number), [
                'order_item_id' => $orderItem->id,
                'rating' => 5,
                'comment' => 'Format salah',
                'video' => $invalidFile,
            ]);

        $response->assertSessionHasErrors('video');
        $this->assertDatabaseCount('product_reviews', 0);
    }

    public function test_buyer_can_upload_both_photos_and_video_simultaneously(): void
    {
        Storage::fake('public');
        [$buyer, , $product, , $order, $orderItem] = $this->createOrderFixture(Completed::class);

        $file1 = UploadedFile::fake()->create('photo1.jpg', 100, 'image/jpeg');
        $file2 = UploadedFile::fake()->create('photo2.png', 100, 'image/png');
        $video = UploadedFile::fake()->create('video.mp4', 3000, 'video/mp4');

        $response = $this->actingAs($buyer)
            ->post(route('reviews.store', $order->order_number), [
                'order_item_id' => $orderItem->id,
                'rating' => 5,
                'comment' => 'Lengkap foto dan video!',
                'photos' => [$file1, $file2],
                'video' => $video,
            ]);

        $response->assertRedirect(route('orders.show', $order->order_number));

        $review = ProductReview::first();
        $this->assertNotNull($review);
        $this->assertCount(2, $review->photos);
        $this->assertNotNull($review->video);
    }

    public function test_buyer_can_upload_large_video_payload_without_post_too_large_exception(): void
    {
        Storage::fake('public');
        [$buyer, , , , $order, $orderItem] = $this->createOrderFixture(Completed::class);

        // 15MB video file (exceeds default 8MB post_max_size and 2MB upload_max_filesize)
        $largeVideo = UploadedFile::fake()->create('unboxing_15mb.mp4', 15000, 'video/mp4');

        $response = $this->actingAs($buyer)
            ->post(route('reviews.store', $order->order_number), [
                'order_item_id' => $orderItem->id,
                'rating' => 5,
                'comment' => 'Video resolusi tinggi 15MB unboxing mantap!',
                'video' => $largeVideo,
            ]);

        $response->assertRedirect(route('orders.show', $order->order_number));
        $review = ProductReview::first();
        $this->assertNotNull($review);
        $this->assertNotNull($review->video);
        $this->assertStringStartsWith('/storage/reviews/videos/', $review->video);
    }

    public function test_post_too_large_exception_is_handled_gracefully(): void
    {
        [$buyer, , , , $order] = $this->createOrderFixture(Completed::class);

        // Force middleware or route to throw PostTooLargeException
        Route::post('/test-post-too-large', function () {
            throw new PostTooLargeException;
        });

        $response = $this->actingAs($buyer)
            ->from(route('reviews.create', $order->order_number))
            ->post('/test-post-too-large', []);

        $response->assertRedirect(route('reviews.create', $order->order_number));
        $response->assertSessionHas('error', 'Ukuran berkas yang diunggah terlalu besar (maksimal 50MB untuk video). Silakan pilih berkas yang lebih kecil.');
    }
}
