<?php

namespace Tests\Feature;

use App\Enums\ProductCondition;
use App\Enums\ProductStatus;
use App\Enums\SellerStatus;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\SellerFollower;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SellerFollowersAndWishlistsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'buyer', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'seller', 'guard_name' => 'web']);
    }

    private function createSeller(): array
    {
        $user = User::factory()->create(['role' => UserRole::SELLER]);
        $user->assignRole('seller');

        $seller = Seller::create([
            'user_id' => $user->id,
            'store_name' => 'Merch & Preloved Store',
            'username' => 'merchstore',
            'bio' => 'Seller Bio',
            'bank_name' => 'BCA',
            'bank_account_number' => '1122334455',
            'bank_account_name' => 'Store Owner',
            'status' => SellerStatus::VERIFIED,
            'verified_at' => now(),
        ]);

        return [$user, $seller];
    }

    private function createProduct(Seller $seller, string $name, float $price = 150000): Product
    {
        $category = Category::firstOrCreate(['slug' => 'merchandise'], ['name' => 'Merchandise', 'is_active' => true]);

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::random(5),
            'description' => 'Test Product Description',
            'condition' => ProductCondition::BRAND_NEW,
            'status' => ProductStatus::ACTIVE,
            'price' => $price,
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Default Variant',
            'sku' => 'SKU-'.Str::random(6),
            'price' => $price,
            'stock' => 10,
        ]);

        return $product;
    }

    public function test_guest_cannot_access_seller_followers_or_wishlists(): void
    {
        $responseFollowers = $this->get(route('seller.followers.index'));
        $responseFollowers->assertRedirect(route('login'));

        $responseWishlists = $this->get(route('seller.wishlists.index'));
        $responseWishlists->assertRedirect(route('login'));
    }

    public function test_non_seller_is_redirected_to_seller_register(): void
    {
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        $buyer->assignRole('buyer');

        $responseFollowers = $this->actingAs($buyer)->get(route('seller.followers.index'));
        $responseFollowers->assertRedirect(route('seller.register'));

        $responseWishlists = $this->actingAs($buyer)->get(route('seller.wishlists.index'));
        $responseWishlists->assertRedirect(route('seller.register'));
    }

    public function test_admin_is_redirected_away_from_seller_followers_and_wishlists(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $admin->assignRole('admin');

        $responseFollowers = $this->actingAs($admin)->get(route('seller.followers.index'));
        $responseFollowers->assertRedirect('/admin/sellers');

        $responseWishlists = $this->actingAs($admin)->get(route('seller.wishlists.index'));
        $responseWishlists->assertRedirect('/admin/products');
    }

    public function test_seller_can_view_followers_list_with_customer_order_badge(): void
    {
        [$sellerUser, $seller] = $this->createSeller();

        $buyer1 = User::factory()->create(['name' => 'Budi Setiawan', 'role' => UserRole::BUYER]);
        $buyer2 = User::factory()->create(['name' => 'Siti Aisyah', 'role' => UserRole::BUYER]);

        // Both follow the store
        SellerFollower::create(['user_id' => $buyer1->id, 'seller_id' => $seller->id]);
        SellerFollower::create(['user_id' => $buyer2->id, 'seller_id' => $seller->id]);

        // Buyer 1 made an order in this store
        Order::create([
            'order_number' => 'WHI-TEST-001',
            'buyer_id' => $buyer1->id,
            'seller_id' => $seller->id,
            'total_amount' => 150000,
            'shipping_cost' => 15000,
            'admin_fee' => 2000,
            'grand_total' => 167000,
            'status' => 'completed',
            'address_snapshot' => ['city' => 'Jakarta'],
        ]);

        $response = $this->actingAs($sellerUser)->get(route('seller.followers.index'));

        $response->assertStatus(200);
        $response->assertSee('Budi Setiawan');
        $response->assertSee('Siti Aisyah');
        $response->assertSee('Pernah Belanja (1 Pesanan)');
        $response->assertSee('Pengikut Baru');
    }

    public function test_seller_can_search_followers_by_name_or_email(): void
    {
        [$sellerUser, $seller] = $this->createSeller();

        $buyer1 = User::factory()->create(['name' => 'Andi Wijaya', 'email' => 'andi@example.com']);
        $buyer2 = User::factory()->create(['name' => 'Dewi Lestari', 'email' => 'dewi@example.com']);

        SellerFollower::create(['user_id' => $buyer1->id, 'seller_id' => $seller->id]);
        SellerFollower::create(['user_id' => $buyer2->id, 'seller_id' => $seller->id]);

        $responseSearch = $this->actingAs($sellerUser)->get(route('seller.followers.index', ['q' => 'Andi']));

        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('Andi Wijaya');
        $responseSearch->assertDontSee('Dewi Lestari');
    }

    public function test_seller_can_view_wishlist_activities_stream(): void
    {
        [$sellerUser, $seller] = $this->createSeller();
        $product = $this->createProduct($seller, 'T-Shirt Creator Edition', 180000);

        $buyer = User::factory()->create(['name' => 'Farhan Pratama', 'role' => UserRole::BUYER]);

        // Buyer wishlists the product
        Wishlist::create(['user_id' => $buyer->id, 'product_id' => $product->id]);

        $response = $this->actingAs($sellerUser)->get(route('seller.wishlists.index', ['tab' => 'activity']));

        $response->assertStatus(200);
        $response->assertSee('Farhan Pratama');
        $response->assertSee('T-Shirt Creator Edition');
        $response->assertSee('Rp 180.000');
        $response->assertSee('10 pcs');
    }

    public function test_seller_can_view_products_ranked_by_wishlist_count(): void
    {
        [$sellerUser, $seller] = $this->createSeller();
        $prodPopular = $this->createProduct($seller, 'Hoodie Viral 2026', 350000);
        $prodNormal = $this->createProduct($seller, 'Topi Snapback Kasual', 95000);

        $buyer1 = User::factory()->create(['name' => 'Buyer Satu']);
        $buyer2 = User::factory()->create(['name' => 'Buyer Dua']);

        // 2 wishlists for Hoodie
        Wishlist::create(['user_id' => $buyer1->id, 'product_id' => $prodPopular->id]);
        Wishlist::create(['user_id' => $buyer2->id, 'product_id' => $prodPopular->id]);

        // 1 wishlist for Topi
        Wishlist::create(['user_id' => $buyer1->id, 'product_id' => $prodNormal->id]);

        $response = $this->actingAs($sellerUser)->get(route('seller.wishlists.index', ['tab' => 'products']));

        $response->assertStatus(200);
        $response->assertSee('Hoodie Viral 2026');
        $response->assertSee('2 Peminat');
        $response->assertSee('Topi Snapback Kasual');
        $response->assertSee('1 Peminat');
    }

    public function test_seller_can_filter_wishlists_by_specific_product(): void
    {
        [$sellerUser, $seller] = $this->createSeller();
        $prod1 = $this->createProduct($seller, 'Sepatu Sneakers Preloved');
        $prod2 = $this->createProduct($seller, 'Jaket Kulit Vintage');

        $buyer1 = User::factory()->create(['name' => 'Pembeli Sepatu']);
        $buyer2 = User::factory()->create(['name' => 'Pembeli Jaket']);

        Wishlist::create(['user_id' => $buyer1->id, 'product_id' => $prod1->id]);
        Wishlist::create(['user_id' => $buyer2->id, 'product_id' => $prod2->id]);

        $responseFilter = $this->actingAs($sellerUser)->get(route('seller.wishlists.index', [
            'tab' => 'activity',
            'product_id' => $prod1->id,
        ]));

        $responseFilter->assertStatus(200);
        $responseFilter->assertSee('Pembeli Sepatu');
        $responseFilter->assertSee('Sepatu Sneakers Preloved');
        $responseFilter->assertDontSee('Pembeli Jaket');
    }

    public function test_seller_dashboard_and_navbar_render_links_to_followers_and_wishlists(): void
    {
        [$sellerUser, $seller] = $this->createSeller();

        $responseDashboard = $this->actingAs($sellerUser)->get(route('seller.dashboard'));
        $responseDashboard->assertStatus(200);
        $responseDashboard->assertSee(route('seller.followers.index'));
        $responseDashboard->assertSee(route('seller.wishlists.index'));
        $responseDashboard->assertSee('Pengikut Toko');
        $responseDashboard->assertSee('Peminat Wishlist');
    }
}
