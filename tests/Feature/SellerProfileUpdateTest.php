<?php

namespace Tests\Feature;

use App\Enums\ProductStatus;
use App\Enums\SellerStatus;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SellerProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_seller_settings(): void
    {
        $response = $this->get(route('seller.settings'));

        $response->assertRedirect(route('login'));
    }

    public function test_verified_seller_can_view_settings_page(): void
    {
        $user = User::factory()->create(['role' => UserRole::SELLER]);
        $seller = Seller::create([
            'user_id' => $user->id,
            'store_name' => 'Toko Keren',
            'username' => 'toko-keren',
            'bio' => 'Bio toko keren',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Toko Keren',
            'status' => SellerStatus::VERIFIED,
            'verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('seller.settings'));

        $response->assertOk();
        $response->assertViewIs('seller.settings');
        $response->assertSee('Toko Keren');
    }

    public function test_seller_can_update_profile_avatar_and_banner(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => UserRole::SELLER]);
        $seller = Seller::create([
            'user_id' => $user->id,
            'store_name' => 'Original Store',
            'username' => 'original-store',
            'bio' => 'Original Bio',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Owner Name',
            'status' => SellerStatus::VERIFIED,
            'verified_at' => now(),
        ]);

        $avatarFile = UploadedFile::fake()->create('my_avatar.png', 100, 'image/png');
        $bannerFile = UploadedFile::fake()->create('my_banner.png', 200, 'image/png');

        $response = $this->actingAs($user)->put(route('seller.settings.update'), [
            'store_name' => 'Updated Store Name',
            'bio' => 'Updated Bio text here',
            'avatar' => $avatarFile,
            'banner' => $bannerFile,
            'bank_name' => 'Mandiri',
            'bank_account_number' => '987654321',
            'bank_account_name' => 'Updated Owner',
        ]);

        $response->assertRedirect(route('seller.settings'));
        $response->assertSessionHas('success');

        $user->refresh();
        $seller->refresh();

        $this->assertEquals('Updated Store Name', $seller->store_name);
        $this->assertEquals('Updated Bio text here', $seller->bio);
        $this->assertEquals('Mandiri', $seller->bank_name);
        $this->assertNotNull($user->avatar);
        $this->assertNotNull($seller->banner_image);

        $avatarDiskPath = str_replace('/storage/', '', $user->avatar);
        $bannerDiskPath = str_replace('/storage/', '', $seller->banner_image);

        Storage::disk('public')->assertExists($avatarDiskPath);
        Storage::disk('public')->assertExists($bannerDiskPath);
    }

    public function test_seller_can_remove_avatar_and_banner(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => UserRole::SELLER,
            'avatar' => '/storage/avatars/old_avatar.png',
        ]);
        Storage::disk('public')->put('avatars/old_avatar.png', 'fake-avatar-content');

        $seller = Seller::create([
            'user_id' => $user->id,
            'store_name' => 'My Store',
            'username' => 'my-store',
            'banner_image' => '/storage/banners/old_banner.png',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Owner Name',
            'status' => SellerStatus::VERIFIED,
            'verified_at' => now(),
        ]);
        Storage::disk('public')->put('banners/old_banner.png', 'fake-banner-content');

        $response = $this->actingAs($user)->put(route('seller.settings.update'), [
            'store_name' => 'My Store',
            'remove_avatar' => 1,
            'remove_banner' => 1,
        ]);

        $response->assertRedirect(route('seller.settings'));

        $user->refresh();
        $seller->refresh();

        $this->assertNull($user->avatar);
        $this->assertNull($seller->banner_image);

        Storage::disk('public')->assertMissing('avatars/old_avatar.png');
        Storage::disk('public')->assertMissing('banners/old_banner.png');
    }

    public function test_new_seller_profile_shows_clean_stats_and_empty_reviews(): void
    {
        $user = User::factory()->create(['role' => UserRole::SELLER]);
        $seller = Seller::create([
            'user_id' => $user->id,
            'store_name' => 'Studio Baru Bintang',
            'username' => 'studio-baru-bintang',
            'bio' => 'Toko baru berdiri',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Bintang',
            'status' => SellerStatus::VERIFIED,
            'verified_at' => now(),
        ]);

        $response = $this->get(route('seller.profile', '@'.$seller->username));

        $response->assertOk();
        $response->assertSee('Studio Baru Bintang');
        $response->assertSee('Belum ada ulasan');
        $response->assertSee('0 Pengikut');
        $response->assertDontSee('12.4rb Pengikut');
        $response->assertDontSee('Anya Geraldine');
        $response->assertSee('Belum Ada Ulasan');
    }

    public function test_seller_cannot_follow_own_store_and_sees_manage_store_button(): void
    {
        $user = User::factory()->create(['role' => UserRole::SELLER]);
        $seller = Seller::create([
            'user_id' => $user->id,
            'store_name' => 'Toko Milik Saya',
            'username' => 'toko-milik-saya',
            'bio' => 'Ini toko saya',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Saya',
            'status' => SellerStatus::VERIFIED,
            'verified_at' => now(),
        ]);

        // When owner views their own store:
        $ownerResponse = $this->actingAs($user)->get(route('seller.profile', '@'.$seller->username));
        $ownerResponse->assertOk();
        $ownerResponse->assertSee('Kelola Toko');
        $ownerResponse->assertDontSee('Ikuti Toko');

        // When another user views the store:
        $otherUser = User::factory()->create(['role' => UserRole::BUYER]);
        $otherResponse = $this->actingAs($otherUser)->get(route('seller.profile', '@'.$seller->username));
        $otherResponse->assertOk();
        $otherResponse->assertSee('Ikuti Toko');
    }

    public function test_seller_cannot_buy_their_own_product(): void
    {
        $sellerUser = User::factory()->create(['role' => UserRole::SELLER]);
        $seller = Seller::create([
            'user_id' => $sellerUser->id,
            'store_name' => 'Toko Barang Sendiri',
            'username' => 'toko-barang-sendiri',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Seller',
            'status' => SellerStatus::VERIFIED,
            'verified_at' => now(),
        ]);

        $category = Category::firstOrCreate(['slug' => 'fashion'], ['name' => 'Fashion', 'is_active' => true]);

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Jaket Kulit Sendiri',
            'slug' => 'jaket-kulit-sendiri',
            'description' => 'Jaket bagus',
            'price' => 500000,
            'status' => ProductStatus::ACTIVE,
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Size L',
            'price' => 500000,
            'stock' => 10,
        ]);

        // 1. Check Product Detail page for owner
        $responseDetail = $this->actingAs($sellerUser)->get(route('product.detail', $product->slug));
        $responseDetail->assertOk();
        $responseDetail->assertSee('Produk Toko Anda Sendiri');
        $responseDetail->assertSee('Kelola / Edit Produk di Seller Portal');
        $responseDetail->assertDontSee('Tambah ke Keranjang');

        // 2. Attempt to add to cart via API / form submit
        $responseCart = $this->actingAs($sellerUser)->postJson(route('cart.add'), [
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $responseCart->assertStatus(422);
        $responseCart->assertJson([
            'success' => false,
            'message' => 'Anda tidak dapat membeli produk dari toko Anda sendiri.',
        ]);
    }

    public function test_new_verified_seller_appears_in_directory_and_landing(): void
    {
        $sellerUser = User::factory()->create(['role' => UserRole::SELLER]);
        $seller = Seller::create([
            'user_id' => $sellerUser->id,
            'store_name' => 'Toko Muncul Di Direktori',
            'username' => 'toko-muncul-di-direktori',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Seller',
            'status' => SellerStatus::VERIFIED,
            'verified_at' => now(),
        ]);

        // Check directory /seller
        $dirResponse = $this->get(route('seller.directory'));
        $dirResponse->assertOk();
        $dirResponse->assertSee('Toko Muncul Di Direktori');

        // Check home /
        $homeResponse = $this->get('/');
        $homeResponse->assertOk();
        $homeResponse->assertSee('Toko Muncul Di Direktori');
    }
}
