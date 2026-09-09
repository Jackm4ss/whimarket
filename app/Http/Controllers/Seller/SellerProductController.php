<?php

namespace App\Http\Controllers\Seller;

use App\Enums\ProductCondition;
use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SellerProductController extends Controller
{
    private function getVerifiedSeller(): ?Seller
    {
        $seller = Auth::user()?->seller;
        if (! $seller || ! $seller->isVerified()) {
            return null;
        }

        return $seller;
    }

    public function index(Request $request): View|RedirectResponse
    {
        $seller = $this->getVerifiedSeller();
        if (! $seller) {
            return redirect()->route('seller.register')
                ->with('info', 'Silakan aktifkan toko Anda dengan kode akses resmi terlebih dahulu.');
        }

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

    public function create(): View|RedirectResponse
    {
        $seller = $this->getVerifiedSeller();
        if (! $seller) {
            return redirect()->route('seller.register')
                ->with('info', 'Silakan aktifkan toko Anda dengan kode akses resmi terlebih dahulu.');
        }

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
        $seller = $this->getVerifiedSeller();
        if (! $seller) {
            return redirect()->route('seller.register')
                ->with('info', 'Silakan aktifkan toko Anda dengan kode akses resmi terlebih dahulu.');
        }
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

    public function edit(int $id): View|RedirectResponse
    {
        $seller = $this->getVerifiedSeller();
        if (! $seller) {
            return redirect()->route('seller.register')
                ->with('info', 'Silakan aktifkan toko Anda dengan kode akses resmi terlebih dahulu.');
        }
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
        $seller = $this->getVerifiedSeller();
        if (! $seller) {
            return redirect()->route('seller.register')
                ->with('info', 'Silakan aktifkan toko Anda dengan kode akses resmi terlebih dahulu.');
        }
        $product = $seller->products()->with(['images', 'variants'])->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:5000',
            'price' => 'required|numeric|min:1000',
            'condition' => 'required|string',
            'status' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'deleted_images' => 'nullable|array',
            'deleted_images.*' => 'integer|exists:product_images,id',
            'variants' => 'required|array|min:1',
            'variants.*.id' => 'nullable|integer',
            'variants.*.name' => 'required|string|max:100',
            'variants.*.price' => 'required|numeric|min:1000',
            'variants.*.stock' => 'required|integer|min:0',
        ], [
            'variants.required' => 'Setidaknya harus ada minimal 1 varian produk.',
            'variants.min' => 'Setidaknya harus ada minimal 1 varian produk.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.max' => 'Ukuran setiap foto maksimal 5MB.',
        ]);

        // 1. Update basic attributes
        $product->update([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'condition' => $validated['condition'],
            'status' => $validated['status'],
        ]);

        // 2. Process Deleted Images
        if (! empty($validated['deleted_images'])) {
            foreach ($validated['deleted_images'] as $imgId) {
                $img = ProductImage::where('product_id', $product->id)->find($imgId);
                if ($img) {
                    if (str_starts_with($img->image_path, '/storage/')) {
                        Storage::disk('public')->delete(str_replace('/storage/', '', $img->image_path));
                    }
                    $img->delete();
                }
            }
        }

        // 3. Process Newly Uploaded Images
        if ($request->hasFile('images')) {
            $existingCount = $product->images()->count();
            foreach ($request->file('images') as $idx => $file) {
                $filename = 'prod_'.$product->id.'_'.($existingCount + $idx).'_'.Str::uuid().'.'.$file->getClientOriginalExtension();
                $path = '/storage/'.$file->storeAs('products', $filename, 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'sort_order' => $existingCount + $idx,
                    'is_primary' => ($existingCount === 0 && $idx === 0),
                ]);
            }
        }

        // 4. Ensure At Least One Image is Marked as Primary
        if ($product->images()->where('is_primary', true)->doesntExist()) {
            $firstImg = $product->images()->first();
            if ($firstImg) {
                $firstImg->update(['is_primary' => true]);
            }
        }

        // 5. Process Variants & Stock Updates
        $submittedVariantIds = [];
        foreach ($validated['variants'] as $vData) {
            if (! empty($vData['id'])) {
                $existingVariant = ProductVariant::where('product_id', $product->id)->find($vData['id']);
                if ($existingVariant) {
                    $existingVariant->update([
                        'name' => $vData['name'],
                        'price' => $vData['price'],
                        'stock' => $vData['stock'],
                    ]);
                    $submittedVariantIds[] = $existingVariant->id;

                    continue;
                }
            }

            // Create new variant
            $newVariant = ProductVariant::create([
                'product_id' => $product->id,
                'name' => $vData['name'],
                'sku' => 'WHI-'.strtoupper(Str::random(6)),
                'price' => $vData['price'],
                'stock' => $vData['stock'],
            ]);
            $submittedVariantIds[] = $newVariant->id;
        }

        // Remove variants that were deleted by the seller
        ProductVariant::where('product_id', $product->id)
            ->whereNotIn('id', $submittedVariantIds)
            ->delete();

        return redirect()->route('seller.products.index')
            ->with('success', "Produk '{$product->name}' berhasil diperbarui beserta foto, varian, dan stoknya!");
    }

    public function destroy(int $id): RedirectResponse
    {
        $seller = $this->getVerifiedSeller();
        if (! $seller) {
            return redirect()->route('seller.register')
                ->with('info', 'Silakan aktifkan toko Anda dengan kode akses resmi terlebih dahulu.');
        }
        $product = $seller->products()->findOrFail($id);
        $name = $product->name;
        $product->delete();

        return redirect()->route('seller.products.index')
            ->with('success', "Produk '{$name}' berhasil dihapus dari toko.");
    }
}
