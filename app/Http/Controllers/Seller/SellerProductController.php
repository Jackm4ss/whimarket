<?php

namespace App\Http\Controllers\Seller;

use App\Enums\ProductCondition;
use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SellerProductController extends Controller
{
    public function index(Request $request): View
    {
        $seller = Auth::user()->seller;
        $search = $request->query('q');

        $query = $seller->products()->with(['category', 'variants', 'primaryImage']);

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        return view('seller.products.index', [
            'seller' => $seller,
            'products' => $products,
            'title' => 'Kelola Produk Toko | WhiMarket',
            'activeTab' => 'seller-products',
        ]);
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)->get();

        return view('seller.products.create', [
            'categories' => $categories,
            'conditions' => ProductCondition::cases(),
            'title' => 'Tambah Produk Baru | WhiMarket',
            'activeTab' => 'seller-products',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $seller = Auth::user()->seller;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:5000',
            'price' => 'required|numeric|min:1000',
            'condition' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'variants' => 'nullable|array',
            'variants.*.name' => 'required|string|max:100',
            'variants.*.price' => 'required|numeric|min:1000',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'condition' => $validated['condition'],
            'status' => ProductStatus::ACTIVE,
        ]);

        // Upload images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $idx => $file) {
                $filename = 'prod_'.$product->id.'_'.$idx.'_'.Str::uuid().'.'.$file->getClientOriginalExtension();
                $path = '/storage/'.$file->storeAs('products', $filename, 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'sort_order' => $idx,
                    'is_primary' => $idx === 0,
                ]);
            }
        } else {
            // Default placeholder image
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => '/assets/placeholder-product.png',
                'sort_order' => 0,
                'is_primary' => true,
            ]);
        }

        // Create variants
        if (! empty($validated['variants'])) {
            foreach ($validated['variants'] as $v) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $v['name'],
                    'sku' => 'WHI-'.strtoupper(Str::random(6)),
                    'price' => $v['price'],
                    'stock' => $v['stock'],
                ]);
            }
        } else {
            // Default single variant
            ProductVariant::create([
                'product_id' => $product->id,
                'name' => 'All Size',
                'sku' => 'WHI-'.strtoupper(Str::random(6)),
                'price' => $validated['price'],
                'stock' => 1,
            ]);
        }

        return redirect()->route('seller.products.index')
            ->with('success', "Produk '{$product->name}' berhasil ditambahkan ke katalog toko!");
    }

    public function edit(int $id): View
    {
        $seller = Auth::user()->seller;
        $product = $seller->products()->with(['variants', 'images'])->findOrFail($id);
        $categories = Category::where('is_active', true)->get();

        return view('seller.products.edit', [
            'product' => $product,
            'categories' => $categories,
            'conditions' => ProductCondition::cases(),
            'title' => 'Edit Produk: '.$product->name.' | WhiMarket',
            'activeTab' => 'seller-products',
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $seller = Auth::user()->seller;
        $product = $seller->products()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:5000',
            'price' => 'required|numeric|min:1000',
            'condition' => 'required|string',
            'status' => 'required|string',
        ]);

        $product->update($validated);

        return redirect()->route('seller.products.index')
            ->with('success', "Produk '{$product->name}' berhasil diperbarui!");
    }

    public function destroy(int $id): RedirectResponse
    {
        $seller = Auth::user()->seller;
        $product = $seller->products()->findOrFail($id);
        $name = $product->name;
        $product->delete();

        return redirect()->route('seller.products.index')
            ->with('success', "Produk '{$name}' berhasil dihapus dari toko.");
    }
}
