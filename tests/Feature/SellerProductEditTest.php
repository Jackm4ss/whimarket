<?php

namespace Tests\Feature;

use App\Enums\ProductCondition;
use App\Enums\ProductStatus;
use App\Enums\SellerStatus;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SellerProductEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_can_view_product_edit_page(): void
    {
        $user = User::factory()->create(['role' => UserRole::SELLER]);
        $seller = Seller::create([
            'user_id' => $user->id,
            'store_name' => 'Toko Uji Coba',
            'username' => 'toko-uji-coba',
            'bank_name' => 'BCA',
            'bank_account_number' => '123',
            'bank_account_name' => 'Uji',
            'status' => SellerStatus::VERIFIED,
            'verified_at' => now(),
        ]);

        $category = Category::firstOrCreate(['slug' => 'fashion'], ['name' => 'Fashion', 'is_active' => true]);

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Sepatu Sneakers Vintage',
            'slug' => 'sepatu-sneakers-vintage',
            'description' => 'Sepatu langka kondisi baik.',
            'price' => 750000,
            'condition' => ProductCondition::VERY_GOOD,
            'status' => ProductStatus::ACTIVE,
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Size 42',
            'sku' => 'WHI-SNEAK-42',
            'price' => 750000,
            'stock' => 5,
        ]);

        $response = $this->actingAs($user)->get(route('seller.products.edit', $product->id));

        $response->assertOk();
        $response->assertSee('Sepatu Sneakers Vintage');
        $response->assertSee('Size 42');
        $response->assertSee('Foto &amp; Galeri Produk', false);
        $response->assertSee('Varian &amp; Stok Produk', false);
    }

    public function test_seller_can_update_photos_variants_and_stock(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => UserRole::SELLER]);
        $seller = Seller::create([
            'user_id' => $user->id,
            'store_name' => 'Toko Uji Coba',
            'username' => 'toko-uji-coba',
            'bank_name' => 'BCA',
            'bank_account_number' => '123',
            'bank_account_name' => 'Uji',
            'status' => SellerStatus::VERIFIED,
            'verified_at' => now(),
        ]);

        $category = Category::firstOrCreate(['slug' => 'fashion'], ['name' => 'Fashion', 'is_active' => true]);

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Sepatu Sneakers Vintage',
            'slug' => 'sepatu-sneakers-vintage',
            'description' => 'Sepatu langka kondisi baik.',
            'price' => 750000,
            'condition' => ProductCondition::VERY_GOOD,
            'status' => ProductStatus::ACTIVE,
        ]);

        // Old photo
        $oldPhoto = ProductImage::create([
            'product_id' => $product->id,
            'image_path' => '/storage/products/old_photo.png',
            'sort_order' => 0,
            'is_primary' => true,
        ]);
        Storage::disk('public')->put('products/old_photo.png', 'old-content');

        // Existing variant 1
        $var1 = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Size 41',
            'sku' => 'WHI-SNEAK-41',
            'price' => 750000,
            'stock' => 2,
        ]);

        // Existing variant 2 (to be deleted)
        $var2 = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Size 42 (Hapus)',
            'price' => 750000,
            'stock' => 1,
        ]);

        $newPhoto = UploadedFile::fake()->create('new_photo.png', 100, 'image/png');

        $response = $this->actingAs($user)->put(route('seller.products.update', $product->id), [
            'name' => 'Sepatu Sneakers Vintage Reborn',
            'category_id' => $category->id,
            'description' => 'Deskripsi baru setelah diupdate.',
            'price' => 800000,
            'condition' => ProductCondition::LIKE_NEW->value,
            'status' => ProductStatus::ACTIVE->value,
            'deleted_images' => [$oldPhoto->id],
            'images' => [$newPhoto],
            'variants' => [
                [
                    'id' => $var1->id,
                    'name' => 'Size 41 Updated',
                    'price' => 800000,
                    'stock' => 10,
                ],
                [
                    'id' => null, // Newly added variant
                    'name' => 'Size 43 Baru',
                    'price' => 820000,
                    'stock' => 4,
                ],
            ],
        ]);

        $response->assertRedirect(route('seller.products.index'));
        $response->assertSessionHas('success');

        $product->refresh();

        // 1. Check updated basic info
        $this->assertEquals('Sepatu Sneakers Vintage Reborn', $product->name);
        $this->assertEquals(800000, (float) $product->price);

        // 2. Check old photo removed from DB & disk
        $this->assertDatabaseMissing('product_images', ['id' => $oldPhoto->id]);
        Storage::disk('public')->assertMissing('products/old_photo.png');

        // 3. Check new photo exists in DB & disk
        $this->assertCount(1, $product->images);
        $newImageRecord = $product->images->first();
        $this->assertTrue($newImageRecord->is_primary);
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $newImageRecord->image_path));

        // 4. Check variants: var1 updated, var2 deleted, var3 added
        $this->assertCount(2, $product->variants);
        $this->assertDatabaseMissing('product_variants', ['id' => $var2->id]);

        $updatedVar1 = ProductVariant::find($var1->id);
        $this->assertEquals('Size 41 Updated', $updatedVar1->name);
        $this->assertEquals(10, $updatedVar1->stock);

        $newVar3 = ProductVariant::where('product_id', $product->id)->where('name', 'Size 43 Baru')->first();
        $this->assertNotNull($newVar3);
        $this->assertEquals(4, $newVar3->stock);
        $this->assertEquals(820000, (float) $newVar3->price);

        // 5. Total stock
        $this->assertEquals(14, $product->total_stock);
    }
}
