<?php

namespace Tests\Feature;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Seller;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductBrowseTest extends TestCase
{
    use DatabaseMigrations;

    public function test_landing_page_renders_successfully(): void
    {
        $category = Category::factory()->create(['name' => 'Fashion', 'slug' => 'fashion']);
        $seller = Seller::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'seller_id' => $seller->id,
            'name' => 'Jaket Denim Vintage',
        ]);
        ProductImage::factory()->create(['product_id' => $product->id, 'is_primary' => true]);
        ProductVariant::factory()->create(['product_id' => $product->id]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Jaket Denim Vintage');
    }

    public function test_shop_page_filters_by_category_and_keyword(): void
    {
        $catFashion = Category::factory()->create(['name' => 'Fashion', 'slug' => 'fashion']);
        $catElectronics = Category::factory()->create(['name' => 'Elektronik', 'slug' => 'elektronik']);

        $seller = Seller::factory()->create();

        $prod1 = Product::factory()->create([
            'category_id' => $catFashion->id,
            'seller_id' => $seller->id,
            'name' => 'Hoodie Purple Edition',
            'price' => 300000,
            'status' => ProductStatus::ACTIVE,
        ]);
        ProductVariant::factory()->create(['product_id' => $prod1->id]);

        $prod2 = Product::factory()->create([
            'category_id' => $catElectronics->id,
            'seller_id' => $seller->id,
            'name' => 'Headphone Wireless',
            'price' => 750000,
            'status' => ProductStatus::ACTIVE,
        ]);
        ProductVariant::factory()->create(['product_id' => $prod2->id]);

        // Filter category=fashion
        $responseCat = $this->get(route('shop', ['kategori' => 'fashion']));
        $responseCat->assertStatus(200);
        $responseCat->assertSee('Hoodie Purple Edition');
        $responseCat->assertDontSee('Headphone Wireless');

        // Search query q=Headphone
        $responseSearch = $this->get(route('shop', ['q' => 'Headphone']));
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('Headphone Wireless');
        $responseSearch->assertDontSee('Hoodie Purple Edition');
    }

    public function test_product_detail_page_loads(): void
    {
        $category = Category::factory()->create();
        $seller = Seller::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'seller_id' => $seller->id,
            'name' => 'Totebag Spesial',
            'slug' => 'totebag-spesial',
        ]);
        ProductImage::factory()->create(['product_id' => $product->id]);
        ProductVariant::factory()->create(['product_id' => $product->id, 'name' => 'Hitam', 'stock' => 5]);

        $response = $this->get(route('product.detail', 'totebag-spesial'));
        $response->assertStatus(200);
        $response->assertSee('Totebag Spesial');
        $response->assertSee($seller->store_name);
    }
}
