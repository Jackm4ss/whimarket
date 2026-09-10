<?php

namespace Tests\Feature;

use App\Enums\ProductCondition;
use App\Enums\ProductStatus;
use App\Enums\SellerStatus;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminRoleStorefrontRestrictionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'buyer', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'seller', 'guard_name' => 'web']);
    }

    private function createAdminUser(): User
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'name' => 'Admin WhiMarket',
            'email' => 'admin.test@whimarket.com',
        ]);
        $admin->assignRole('admin');

        return $admin;
    }

    private function createSellerAndProduct(): array
    {
        $sellerUser = User::factory()->create(['role' => UserRole::SELLER]);
        $sellerUser->assignRole('seller');

        $seller = Seller::create([
            'user_id' => $sellerUser->id,
            'store_name' => 'Official Creator Store',
            'username' => 'officialcreator',
            'bio' => 'Creator store bio',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Creator Demo',
            'status' => SellerStatus::VERIFIED,
            'verified_at' => now(),
        ]);

        $category = Category::firstOrCreate(['slug' => 'fashion'], ['name' => 'Fashion', 'is_active' => true]);

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Creator Special Hoodie',
            'slug' => 'creator-special-hoodie',
            'description' => 'Special limited hoodie edition',
            'condition' => ProductCondition::LIKE_NEW,
            'status' => ProductStatus::ACTIVE,
            'price' => 250000,
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Size L',
            'sku' => 'HOODIE-L-001',
            'price' => 250000,
            'stock' => 5,
        ]);

        return [$seller, $product, $variant];
    }

    public function test_admin_profile_dropdown_only_shows_admin_panel_and_logout(): void
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Admin Panel');
        $response->assertSee('Keluar');
        $response->assertSee('Administrator');

        // Verify that standard buyer/seller menu items are NOT rendered in the profile dropdown
        $response->assertDontSee('Pesanan Saya');
        $response->assertDontSee('Aktivasi Toko Seller');
        $response->assertDontSee('Dashboard Toko');
        $response->assertDontSee('Pengaturan Profil & Alamat');
    }

    public function test_admin_cannot_access_cart_page(): void
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get(route('cart.index'));

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error', 'Akun Administrator tidak dapat menggunakan keranjang belanja.');
    }

    public function test_admin_cannot_add_product_to_cart(): void
    {
        $admin = $this->createAdminUser();
        [, , $variant] = $this->createSellerAndProduct();

        // AJAX request
        $responseJson = $this->actingAs($admin)->postJson(route('cart.add'), [
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $responseJson->assertStatus(403);
        $responseJson->assertJson([
            'success' => false,
            'message' => 'Akun Administrator tidak dapat melakukan transaksi pembelian.',
        ]);

        // Standard request
        $responseStandard = $this->actingAs($admin)->post(route('cart.add'), [
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $responseStandard->assertRedirect();
        $responseStandard->assertSessionHas('error', 'Akun Administrator tidak dapat melakukan transaksi pembelian.');
    }

    public function test_admin_cannot_access_or_process_checkout(): void
    {
        $admin = $this->createAdminUser();

        $responseGet = $this->actingAs($admin)->get(route('checkout.index'));
        $responseGet->assertRedirect(route('home'));
        $responseGet->assertSessionHas('error', 'Akun Administrator tidak dapat melakukan transaksi checkout.');

        $responsePost = $this->actingAs($admin)->post(route('checkout.process'), [
            'address_id' => 1,
        ]);
        $responsePost->assertRedirect(route('home'));
        $responsePost->assertSessionHas('error', 'Akun Administrator tidak dapat melakukan transaksi checkout.');
    }

    public function test_admin_cannot_follow_seller(): void
    {
        $admin = $this->createAdminUser();
        [$seller] = $this->createSellerAndProduct();

        // AJAX toggle follow
        $response = $this->actingAs($admin)->postJson(route('seller.toggle-follow', $seller->id));

        $response->assertStatus(403);
        $response->assertJson([
            'error' => 'AdminFollowForbidden',
            'message' => 'Akun Administrator tidak dapat mengikuti toko.',
        ]);

        // Access /toko-diikuti page
        $responsePage = $this->actingAs($admin)->get(route('followed-sellers.index'));
        $responsePage->assertRedirect(route('home'));
        $responsePage->assertSessionHas('error', 'Halaman Toko yang Diikuti khusus untuk pembeli.');
    }

    public function test_admin_cannot_register_as_seller_or_access_seller_portal(): void
    {
        $admin = $this->createAdminUser();

        // Seller register page
        $responseRegister = $this->actingAs($admin)->get(route('seller.register'));
        $responseRegister->assertRedirect('/admin');
        $responseRegister->assertSessionHas('error', 'Akun Administrator tidak dapat mendaftar sebagai seller.');

        // Seller register submit
        $responseSubmit = $this->actingAs($admin)->post(route('seller.register.submit'), [
            'code' => 'SOME-CODE',
            'store_name' => 'Store Name',
            'bank_name' => 'BCA',
            'bank_account_number' => '123',
            'bank_account_name' => 'Admin',
        ]);
        $responseSubmit->assertRedirect('/admin');
        $responseSubmit->assertSessionHas('error', 'Akun Administrator tidak dapat mendaftar sebagai seller.');

        // Seller dashboard
        $responseDashboard = $this->actingAs($admin)->get(route('seller.dashboard'));
        $responseDashboard->assertRedirect('/admin');

        // Seller products
        $responseProducts = $this->actingAs($admin)->get(route('seller.products.index'));
        $responseProducts->assertRedirect('/admin/products');
    }

    public function test_admin_can_use_wishlist_functionality(): void
    {
        $admin = $this->createAdminUser();
        [, $product] = $this->createSellerAndProduct();

        // Wishlist toggle
        $responseToggle = $this->actingAs($admin)->postJson(route('wishlist.toggle', $product->id));
        $responseToggle->assertStatus(200);
        $responseToggle->assertJson([
            'success' => true,
            'is_liked' => true,
        ]);

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $admin->id,
            'product_id' => $product->id,
        ]);

        // Wishlist index page
        $responseIndex = $this->actingAs($admin)->get(route('wishlist.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee($product->name);
    }

    public function test_product_detail_renders_admin_preview_banner_instead_of_purchase_buttons(): void
    {
        $admin = $this->createAdminUser();
        [, $product] = $this->createSellerAndProduct();

        $response = $this->actingAs($admin)->get(route('product.detail', $product->slug));

        $response->assertStatus(200);
        $response->assertSee('Akun Administrator (Mode Pratinjau)');
        $response->assertSee('Kelola Produk di Admin Panel');
        $response->assertDontSee('Beli Sekarang');
    }

    public function test_seller_profile_renders_manage_seller_button_for_admin(): void
    {
        $admin = $this->createAdminUser();
        [$seller] = $this->createSellerAndProduct();

        $response = $this->actingAs($admin)->get(route('seller.profile', '@'.$seller->username));

        $response->assertStatus(200);
        $response->assertSee('Kelola Seller di Admin');
    }
}
