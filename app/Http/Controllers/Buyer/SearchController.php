<?php

namespace App\Http\Controllers\Buyer;

use App\Enums\ProductStatus;
use App\Enums\SellerStatus;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    public function suggest(Request $request): JsonResponse
    {
        $rawQuery = trim((string) $request->query('q', ''));
        $query = substr($rawQuery, 0, 50);

        if (mb_strlen($query) < 2) {
            return response()->json([
                'products' => [],
                'sellers' => [],
                'query' => $query,
            ]);
        }

        $cacheKey = 'search_suggest_'.md5(mb_strtolower($query));

        $data = Cache::remember($cacheKey, 60, function () use ($query) {
            $escaped = str_replace(['%', '_'], ['\%', '\_'], $query);

            $products = Product::query()
                ->select(['id', 'seller_id', 'category_id', 'name', 'slug', 'price', 'condition'])
                ->with([
                    'primaryImage:id,product_id,image_path',
                    'seller:id,store_name,username,status,user_id',
                    'seller.user:id,avatar',
                ])
                ->where('status', ProductStatus::ACTIVE)
                ->where(function ($q) use ($escaped) {
                    $q->where('name', 'like', "%{$escaped}%")
                        ->orWhereHas('seller', fn ($sq) => $sq->where('store_name', 'like', "%{$escaped}%"));
                })
                ->take(5)
                ->get()
                ->map(fn ($p) => [
                    'id' => $p->id,
                    'title' => $p->name,
                    'slug' => $p->slug,
                    'url' => route('product.detail', $p->slug),
                    'price_formatted' => 'Rp '.number_format((float) $p->price, 0, ',', '.'),
                    'image' => $p->primary_image_url,
                    'seller_name' => $p->seller->store_name ?? 'WhiMarket Seller',
                    'condition' => $p->condition?->label() ?? 'Like New',
                ]);

            $sellers = Seller::query()
                ->select(['id', 'user_id', 'store_name', 'username', 'status'])
                ->with(['user:id,avatar'])
                ->where('status', SellerStatus::VERIFIED)
                ->where(function ($q) use ($escaped) {
                    $q->where('store_name', 'like', "%{$escaped}%")
                        ->orWhere('username', 'like', "%{$escaped}%");
                })
                ->take(3)
                ->get()
                ->map(fn ($s) => [
                    'id' => $s->id,
                    'name' => $s->store_name,
                    'username' => $s->username,
                    'url' => route('seller.profile', '@'.$s->username),
                    'avatar' => $s->user->avatar ?? '/assets/avatars/avatar-raisy.png',
                ]);

            return [
                'products' => $products->values()->all(),
                'sellers' => $sellers->values()->all(),
            ];
        });

        return response()->json([
            'products' => $data['products'],
            'sellers' => $data['sellers'],
            'query' => $query,
        ]);
    }
}
