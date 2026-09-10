<?php

namespace Database\Seeders;

use App\Enums\PaymentStatus;
use App\Enums\ProductCondition;
use App\Enums\ProductStatus;
use App\Enums\SellerStatus;
use App\Enums\UserRole;
use App\Models\Address;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\SellerAccessCode;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Wishlist;
use App\States\Order\Delivered;
use App\States\Order\Paid;
use App\States\Order\Processing;
use App\Support\MarketData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Spatie Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $sellerRole = Role::firstOrCreate(['name' => 'seller', 'guard_name' => 'web']);
        $buyerRole = Role::firstOrCreate(['name' => 'buyer', 'guard_name' => 'web']);

        $this->call(ShippingZoneSeeder::class);
        $this->call(PlatformSettingSeeder::class);

        // 2. Demo Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@whimarket.com'],
            [
                'name' => 'Admin WhiMarket',
                'password' => Hash::make('password'),
                'role' => UserRole::ADMIN,
                'phone' => '081234567890',
                'avatar' => '/assets/logo-whimarket.png',
            ]
        );
        $admin->syncRoles([$adminRole]);

        // 3. Demo Buyer User
        $buyer = User::firstOrCreate(
            ['email' => 'buyer@whimarket.com'],
            [
                'name' => 'Budi Pratama',
                'password' => Hash::make('password'),
                'role' => UserRole::BUYER,
                'phone' => '081987654321',
                'avatar' => '/assets/avatars/avatar-raisy.png',
            ]
        );
        $buyer->syncRoles([$buyerRole]);

        $buyer2 = User::firstOrCreate(
            ['email' => 'siti.nurhaliza@gmail.com'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('password'),
                'role' => UserRole::BUYER,
                'phone' => '081234567890',
                'avatar' => '/assets/avatars/avatar-raisy.png',
            ]
        );
        $buyer2->syncRoles([$buyerRole]);
        // Buyer default address
        Address::firstOrCreate(
            ['user_id' => $buyer->id, 'is_default' => true],
            [
                'recipient_name' => 'Budi Pratama',
                'phone' => '081987654321',
                'full_address' => 'Jl. Senopati No. 45, Senayan',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'district' => 'Kebayoran Baru',
                'postal_code' => '12190',
                'is_default' => true,
            ]
        );

        // 4. Seed Categories
        $categoriesData = MarketData::categories();
        $seededCategories = [];
        foreach ($categoriesData as $cat) {
            $category = Category::firstOrCreate(
                ['slug' => $cat['id']],
                [
                    'name' => $cat['name'],
                    'image' => $cat['image'],
                    'bg_color' => $cat['bgColor'],
                    'is_active' => true,
                ]
            );
            $seededCategories[$cat['id']] = $category;
        }

        // 5. Seed Sellers & Seller Users
        $sellersData = MarketData::sellers();
        $seededSellers = [];
        foreach ($sellersData as $s) {
            $username = str_replace('@', '', $s['handle']);
            $sellerEmail = $username.'@whimarket.com';

            $sellerUser = User::firstOrCreate(
                ['email' => $sellerEmail],
                [
                    'name' => $s['name'],
                    'password' => Hash::make('password'),
                    'role' => UserRole::SELLER,
                    'phone' => '08'.fake()->numerify('##########'),
                    'avatar' => $s['avatar'],
                ]
            );
            $sellerUser->syncRoles([$sellerRole]);

            $seller = Seller::firstOrCreate(
                ['user_id' => $sellerUser->id],
                [
                    'store_name' => $s['name'],
                    'username' => $username,
                    'bio' => $s['role'].' resmi di WhiMarket. Menjual koleksi pre-loved & merchandise eksklusif terverifikasi.',
                    'bank_name' => 'BCA',
                    'bank_account_number' => '8273'.fake()->numerify('######'),
                    'bank_account_name' => $s['name'],
                    'status' => SellerStatus::VERIFIED,
                    'verified_at' => now(),
                ]
            );

            $seededSellers[$username] = $seller;
            $seededSellers[$s['name']] = $seller;
        }

        // 6. Seed Featured Product: Hoodie Dream Plan Do (Celloszx)
        $detail = MarketData::productDetail();
        $cellosUser = User::firstOrCreate(
            ['email' => 'celloszx@whimarket.com'],
            [
                'name' => 'Celloszx',
                'password' => Hash::make('password'),
                'role' => UserRole::SELLER,
                'phone' => '081398765432',
                'avatar' => '/assets/avatars/avatar-cellos.png',
            ]
        );
        $cellosUser->syncRoles([$sellerRole]);

        $cellosSeller = Seller::firstOrCreate(
            ['user_id' => $cellosUser->id],
            [
                'store_name' => 'Celloszx Official',
                'username' => 'celloszx',
                'bio' => 'Content Creator. Official Merch & Pre-loved items.',
                'bank_name' => 'BCA',
                'bank_account_number' => '5412899012',
                'bank_account_name' => 'Cellos Zx',
                'status' => SellerStatus::VERIFIED,
                'verified_at' => now(),
            ]
        );
        $seededSellers['Celloszx'] = $cellosSeller;
        $seededSellers['celloszx'] = $cellosSeller;

        $hoodieProduct = Product::firstOrCreate(
            ['slug' => $detail['id']],
            [
                'seller_id' => $cellosSeller->id,
                'category_id' => $seededCategories['fashion']->id,
                'name' => $detail['title'],
                'description' => $detail['description'],
                'price' => $detail['price'],
                'condition' => ProductCondition::BRAND_NEW,
                'status' => ProductStatus::ACTIVE,
            ]
        );

        // Hoodie Images
        foreach ($detail['gallery'] as $idx => $img) {
            ProductImage::firstOrCreate(
                ['product_id' => $hoodieProduct->id, 'image_path' => $img['main']],
                [
                    'sort_order' => $idx,
                    'is_primary' => $idx === 0,
                ]
            );
        }

        // Hoodie Variants
        $sizes = ['S', 'M', 'L', 'XL'];
        $colors = ['Purple', 'Black', 'White'];
        foreach ($colors as $color) {
            foreach ($sizes as $size) {
                ProductVariant::firstOrCreate(
                    [
                        'product_id' => $hoodieProduct->id,
                        'name' => "$color - $size",
                    ],
                    [
                        'sku' => 'WHI-HDP-'.strtoupper(substr($color, 0, 1))."-$size",
                        'price' => $detail['price'],
                        'stock' => 15,
                    ]
                );
            }
        }

        // 7. Seed Shop & Landing Products
        $shopProducts = MarketData::shopProducts();
        foreach ($shopProducts as $idx => $sp) {
            // Find or associate seller
            $seller = $seededSellers[$sp['sellerName']] ?? $cellosSeller;
            $catSlug = $sp['category'] ?? 'fashion';
            $category = $seededCategories[$catSlug] ?? $seededCategories['fashion'];

            $cond = match (strtolower($sp['condition'] ?? '')) {
                'brand new', 'new' => ProductCondition::BRAND_NEW,
                'good', 'baik', 'sangat baik' => ProductCondition::GENTLY_USED,
                default => ProductCondition::LIKE_NEW,
            };

            $productSlug = Str::slug($sp['title']).'-'.($idx + 1);
            $product = Product::firstOrCreate(
                ['slug' => $productSlug],
                [
                    'seller_id' => $seller->id,
                    'category_id' => $category->id,
                    'name' => $sp['title'],
                    'description' => "Barang pre-loved original personal milik {$sp['sellerName']}. Kondisi sangat terawat, original, dan siap dikirim.",
                    'price' => $sp['priceNumber'] ?? 250000,
                    'condition' => $cond,
                    'status' => ProductStatus::ACTIVE,
                ]
            );

            // Primary Image
            ProductImage::firstOrCreate(
                ['product_id' => $product->id, 'image_path' => $sp['image']],
                [
                    'sort_order' => 0,
                    'is_primary' => true,
                ]
            );

            // Default Variants
            ProductVariant::firstOrCreate(
                ['product_id' => $product->id, 'name' => 'All Size'],
                [
                    'sku' => 'WHI-'.strtoupper(Str::random(6)),
                    'price' => $sp['priceNumber'] ?? 250000,
                    'stock' => 5,
                ]
            );
        }

        // 8. Seed Wishlist for Buyer
        Wishlist::firstOrCreate([
            'user_id' => $buyer->id,
            'product_id' => $hoodieProduct->id,
        ]);

        // 9. Seed Access Codes
        SellerAccessCode::firstOrCreate(
            ['code' => 'WHI-VIP-CREATOR'],
            [
                'email' => 'calon.seller@gmail.com',
                'is_used' => false,
                'created_by' => $admin->id,
            ]
        );
        SellerAccessCode::firstOrCreate(
            ['code' => 'WHI-VIP-TEST123'],
            [
                'email' => null,
                'is_used' => false,
                'created_by' => $admin->id,
            ]
        );

        // 10. Seed Demo Orders for Full Lifecycle Testing
        $firstVariant = $hoodieProduct->variants()->first();

        // Order 1: Delivered & Awaiting 48h Inspection
        $orderDelivered = Order::firstOrCreate(
            ['order_number' => 'WHI-20260908-DEMO01'],
            [
                'buyer_id' => $buyer->id,
                'seller_id' => $cellosSeller->id,
                'address_snapshot' => [
                    'recipient_name' => 'Budi Pratama',
                    'phone' => '081987654321',
                    'full_address' => 'Jl. Senopati No. 45, Senayan',
                    'city' => 'Jakarta Selatan',
                    'province' => 'DKI Jakarta',
                    'postal_code' => '12190',
                ],
                'total_amount' => 500000,
                'shipping_cost' => 15000,
                'grand_total' => 515000,
                'status' => Delivered::class,
                'inspection_deadline_at' => now()->addHours(36),
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $orderDelivered->id, 'product_variant_id' => $firstVariant->id],
            [
                'product_name_snapshot' => $hoodieProduct->name,
                'variant_name_snapshot' => $firstVariant->name,
                'price_snapshot' => 500000,
                'quantity' => 1,
                'subtotal' => 500000,
            ]
        );

        Payment::firstOrCreate(
            ['order_id' => $orderDelivered->id],
            [
                'bank_destination' => 'BCA',
                'sender_bank_name' => 'BCA',
                'sender_account_name' => 'Budi Pratama',
                'proof_path' => 'payments/demo_proof.jpg',
                'amount' => 515000,
                'status' => PaymentStatus::VERIFIED,
                'verified_by' => $admin->id,
                'verified_at' => now()->subDays(2),
            ]
        );

        Shipment::firstOrCreate(
            ['order_id' => $orderDelivered->id],
            [
                'courier_name' => 'J&T Express',
                'tracking_number' => 'JT8829103948',
                'pre_shipment_photo_path' => 'shipments/demo_packing.jpg',
                'receipt_photo_path' => 'shipments/demo_receipt.jpg',
                'shipped_at' => now()->subDays(1),
                'delivered_at' => now()->subHours(12),
            ]
        );

        // Order 2: Paid / Processing
        $orderPaid = Order::firstOrCreate(
            ['order_number' => 'WHI-20260908-DEMO02'],
            [
                'buyer_id' => $buyer2->id,
                'seller_id' => $cellosSeller->id,
                'address_snapshot' => [
                    'recipient_name' => 'Siti Nurhaliza',
                    'phone' => '081234567890',
                    'full_address' => 'Jl. Dago Asri No. 12, Coblong',
                    'city' => 'Bandung',
                    'province' => 'Jawa Barat',
                    'postal_code' => '40135',
                ],
                'total_amount' => 500000,
                'shipping_cost' => 15000,
                'grand_total' => 515000,
                'status' => Processing::class,
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $orderPaid->id, 'product_variant_id' => $firstVariant->id],
            [
                'product_name_snapshot' => $hoodieProduct->name,
                'variant_name_snapshot' => $firstVariant->name,
                'price_snapshot' => 500000,
                'quantity' => 1,
                'subtotal' => 500000,
            ]
        );

        Payment::firstOrCreate(
            ['order_id' => $orderPaid->id],
            [
                'bank_destination' => 'BCA',
                'sender_bank_name' => 'Mandiri',
                'sender_account_name' => 'Budi Pratama',
                'proof_path' => 'payments/demo_proof_2.jpg',
                'amount' => 515000,
                'status' => PaymentStatus::VERIFIED,
                'verified_by' => $admin->id,
                'verified_at' => now()->subHours(4),
            ]
        );
    }
}
