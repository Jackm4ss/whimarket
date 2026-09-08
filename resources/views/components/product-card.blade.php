@props(['product', 'className' => ''])

@php
    $isModel = is_object($product);
    $title = $isModel ? $product->name : ($product['title'] ?? '');
    $image = $isModel ? $product->primary_image_url : ($product['image'] ?? '/assets/placeholder-product.png');
    $sellerName = $isModel ? ($product->seller?->store_name ?? 'WhiMarket Creator') : ($product['sellerName'] ?? 'WhiMarket Creator');
    $sellerAvatar = $isModel ? ($product->seller?->user?->avatar ?? '/assets/avatars/avatar-raisy.png') : ($product['sellerAvatar'] ?? '/assets/avatars/avatar-raisy.png');
    $verified = $isModel ? ($product->seller?->isVerified() ?? true) : ($product['verified'] ?? true);
    $condition = $isModel ? ($product->condition?->label() ?? 'Like New') : ($product['condition'] ?? 'Like New');
    $priceText = $isModel ? ('Rp ' . number_format((float)$product->price, 0, ',', '.')) : ($product['priceText'] ?? 'Rp 0');
    $likesCount = $isModel ? ($product->wishlists_count ?? ($product->relationLoaded('wishlists') ? $product->wishlists->count() : 0)) : ($product['likes'] ?? 0);
    $productId = $isModel ? $product->id : ($product['id'] ?? null);
    $href = $isModel ? route('product.detail', $product->slug) : ($product['href'] ?? '#');
    $initialLiked = auth()->check() && $isModel && $product->relationLoaded('wishlists')
        ? $product->wishlists->where('user_id', auth()->id())->isNotEmpty()
        : false;
@endphp

<div
    x-data="{
        isLiked: {{ $initialLiked ? 'true' : 'false' }},
        likesCount: {{ $likesCount }},
        async toggleWishlist() {
            @if(!auth()->check())
                window.location.href = '{{ route('auth.google.redirect') }}';
                return;
            @else
                @if($productId)
                    try {
                        const res = await fetch('/wishlist/toggle/{{ $productId }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });
                        const data = await res.json();
                        this.isLiked = data.is_liked;
                        this.likesCount = data.likes_count;
                    } catch (e) {
                        this.isLiked = !this.isLiked;
                    }
                @else
                    this.isLiked = !this.isLiked;
                    this.likesCount += (this.isLiked ? 1 : -1);
                @endif
            @endif
        }
    }"
    class="bg-white rounded-2xl border border-gray-100/90 shadow-[0_4px_16px_rgba(0,0,0,0.04)] hover:shadow-[0_10px_26px_rgba(0,0,0,0.08)] hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col group {{ $className }}"
>
    <!-- Product Image Stage -->
    <div class="w-full aspect-[4/5] bg-gray-100 overflow-hidden relative flex items-center justify-center">
        <!-- Wishlist Heart Button -->
        <button
            type="button"
            @click.prevent.stop="toggleWishlist()"
            class="absolute top-2.5 right-2.5 z-10 w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white/95 backdrop-blur-xs shadow-md flex items-center justify-center transition-transform hover:scale-105 cursor-pointer"
            :class="isLiked ? 'text-[#4F26A6]' : 'text-gray-700 hover:text-[#4F26A6]'"
            title="Simpan ke Wishlist"
        >
            <svg
                class="w-4 h-4 sm:w-4.5 sm:h-4.5 transition-colors"
                viewBox="0 0 24 24"
                :fill="isLiked ? 'currentColor' : 'none'"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
            </svg>
        </button>

        <a href="{{ $href }}" class="w-full h-full block">
            <img
                src="{{ $image }}"
                alt="{{ $title }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 z-0"
                decoding="async"
            />
        </a>

        <!-- Condition Tag Badge -->
        @if(!empty($condition))
            <div class="absolute bottom-2.5 left-2.5 z-20 pointer-events-none">
                <span class="px-2.5 sm:px-3 py-1 rounded-lg sm:rounded-xl bg-white text-gray-900 text-[11px] sm:text-[12px] font-bold shadow-md border border-black/5">
                    {{ $condition }}
                </span>
            </div>
        @endif
    </div>

    <!-- Card Info Body -->
    <div class="p-3.5 sm:p-4 flex flex-col flex-1 justify-between">
        <div>
            <!-- Seller Name & Verified Rosette -->
            <div class="flex items-center gap-2">
                <img
                    src="{{ $sellerAvatar }}"
                    alt="{{ $sellerName }}"
                    class="w-5 h-5 sm:w-6 sm:h-6 rounded-full object-cover shrink-0 ring-1 ring-gray-100"
                    decoding="async"
                />
                <span class="text-xs sm:text-[13px] font-bold text-gray-900 truncate">
                    {{ $sellerName }}
                </span>
                @if(!empty($verified))
                    <x-verified-badge size="sm" class="w-3.5 h-3.5 shrink-0" />
                @endif
            </div>

            <!-- Product Title -->
            <h3 class="text-[13.5px] sm:text-[14.5px] font-medium text-gray-700 mt-2 mb-3 line-clamp-1">
                <a href="{{ $href }}" class="hover:text-[#4F26A6] transition-colors">
                    {{ $title }}
                </a>
            </h3>
        </div>

        <!-- Price & Likes Count -->
        <div class="flex items-center justify-between pt-1">
            <span class="text-sm sm:text-[15.5px] font-bold text-[#4F26A6]">
                {{ $priceText }}
            </span>
            <div class="inline-flex items-center gap-1 text-xs text-gray-400 font-medium">
                <svg
                    class="w-3.5 h-3.5 transition-colors"
                    :class="isLiked ? 'text-[#4F26A6] fill-[#4F26A6]' : 'text-gray-400 fill-none'"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                </svg>
                <span x-text="likesCount"></span>
            </div>
        </div>
    </div>
</div>
