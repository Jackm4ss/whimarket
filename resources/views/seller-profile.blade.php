@php
    $sellerName = isset($seller) ? ($seller->store_name ?? 'Seller WhiMarket') : 'Rachel Vennya';
    $sellerHandle = isset($seller) ? ($seller->username ?? 'rachel_venya') : 'rachel_venya';
    $sellerAvatar = isset($seller) ? ($seller->avatar_url ?? ($seller->user?->avatar ?? '/assets/avatar-rachel-exact.png')) : '/assets/avatar-rachel-exact.png';
    $sellerBanner = isset($seller) ? ($seller->banner_url ?? '/assets/seller-banner-rachel.png') : '/assets/seller-banner-rachel.png';
    $sellerRole = isset($seller) ? 'Verified Creator' : 'Selebgram';
    $sellerBio = isset($seller) && !empty($seller->bio) ? $seller->bio : '“Let good things find a new home ♡”';
    $sellerItemsCount = isset($products) ? $products->count() : 112;
    $sellerRating = isset($stats['rating']) && $stats['rating'] !== null ? $stats['rating'] : null;
    $sellerReviewCount = isset($stats['review_count']) ? $stats['review_count'] : 0;
    $sellerFollowerCount = isset($stats['follower_count']) ? $stats['follower_count'] : 0;
    $sellerJoinedDate = isset($stats['joined_date']) ? $stats['joined_date'] : (isset($seller->created_at) ? $seller->created_at->translatedFormat('M Y') : 'Mar 2024');
    $isOwnStore = auth()->check() && isset($seller) && (int) auth()->id() === (int) $seller->user_id;

    $userWishlistIds = auth()->check() ? auth()->user()->wishlists()->pluck('product_id')->toArray() : [];
    $displayProducts = isset($products) && $products->isNotEmpty() ? $products->map(function ($p) use ($sellerName, $sellerAvatar, $userWishlistIds) {
        return [
            'id' => $p->slug,
            'model_id' => $p->id,
            'title' => $p->name,
            'sellerName' => $sellerName,
            'sellerAvatar' => $sellerAvatar,
            'verified' => true,
            'is_liked' => in_array($p->id, $userWishlistIds),
            'condition' => $p->condition?->label() ?? 'Seperti Baru',
            'priceText' => 'Rp ' . number_format((float) $p->price, 0, ',', '.'),
            'priceNumber' => (float) $p->price,
            'likes' => $p->wishlists_count ?? 10,
            'image' => $p->primary_image_url,
            'category' => $p->category?->slug ?? 'fashion',
            'href' => route('product.detail', $p->slug),
        ];
    })->values()->all() : [
        [
            'id' => 'rv_1',
            'title' => 'Nike Dunk Low Purple Exclusive',
            'sellerName' => $sellerName,
            'sellerAvatar' => $sellerAvatar,
            'verified' => true,
            'condition' => 'Seperti Baru',
            'priceText' => 'Rp 1.200.000',
            'priceNumber' => 1200000,
            'likes' => 128,
            'image' => '/assets/products/prod-dunk.png',
            'category' => 'fashion',
            'href' => '/produk/prod-dunk-purple',
        ],
        [
            'id' => 'rv_2',
            'title' => 'Tas Michael Kors Original Brown',
            'sellerName' => $sellerName,
            'sellerAvatar' => $sellerAvatar,
            'verified' => true,
            'condition' => 'Sangat Baik',
            'priceText' => 'Rp 2.450.000',
            'priceNumber' => 2450000,
            'likes' => 215,
            'image' => '/assets/banner-chanel-bag.png',
            'category' => 'tas',
            'href' => '/produk/tas-michael-kors-8',
        ],
        [
            'id' => 'rv_3',
            'title' => 'Varsity Jacket Whimarket Exclusive',
            'sellerName' => $sellerName,
            'sellerAvatar' => $sellerAvatar,
            'verified' => true,
            'condition' => 'Seperti Baru',
            'priceText' => 'Rp 650.000',
            'priceNumber' => 650000,
            'likes' => 94,
            'image' => '/assets/products/prod-hoodie.png',
            'category' => 'fashion',
            'href' => '/produk/hoodie-streamer-edition-10',
        ],
        [
            'id' => 'rv_4',
            'title' => 'Jaket Denim Vintage Washed',
            'sellerName' => $sellerName,
            'sellerAvatar' => $sellerAvatar,
            'verified' => true,
            'condition' => 'Baik',
            'priceText' => 'Rp 450.000',
            'priceNumber' => 450000,
            'likes' => 142,
            'image' => '/assets/products/prod-denim.png',
            'category' => 'fashion',
            'href' => '/produk/jaket-denim-vintage-1',
        ],
    ];
@endphp
<x-layouts.app
    :title="$sellerName . ' - Toko Resmi WhiMarket'"
    activeTab="belanja"
    :wishlistCount="0"
    :cartCount="0"
    :user="null"
>
    <main
        class="flex-1 w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-3 pb-20"
        x-data="sellerProfile"
    >
        <!-- 1. Breadcrumbs: Beranda > Seller > Rachel Vennya -->
        <nav class="flex items-center gap-2 text-xs sm:text-[13px] text-gray-500 font-medium mb-3">
            <a href="/" class="hover:text-[#4F26A6] transition-colors cursor-pointer">Beranda</a>
            <span class="text-gray-300 font-normal">&gt;</span>
            <a href="/belanja" class="hover:text-[#4F26A6] transition-colors cursor-pointer">Seller</a>
            <span class="text-gray-300 font-normal">&gt;</span>
            <span class="text-gray-900 font-bold">{{ $sellerName }}</span>
        </nav>

        <!-- 2. Hero Banner (reduced height: aspect 1568/380 with max height constraint) -->
        <div class="relative w-full h-[180px] sm:h-[240px] md:h-[280px] lg:h-[300px] xl:h-[320px] rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xs">
            <img
                src="{{ $sellerBanner }}"
                alt="{{ $sellerName }} Banner"
                class="w-full h-full object-cover object-center"
            />
            <div class="absolute top-4 right-4 flex items-center gap-2">
                @if(Auth::check() && isset($seller) && Auth::id() === $seller->user_id)
                    <a
                        href="{{ route('seller.settings') }}"
                        class="bg-white/95 hover:bg-white backdrop-blur-md px-3.5 py-1.5 rounded-full shadow-xs hover:shadow-sm border border-white/80 flex items-center gap-1.5 text-[12px] font-bold text-gray-800 hover:text-[#4F26A6] transition-all cursor-pointer group"
                    >
                        <svg class="w-3.5 h-3.5 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Ubah Banner & Profil</span>
                    </a>
                @endif
                <button
                    type="button"
                    @click="shareModalOpen = true"
                    class="bg-white/95 hover:bg-white backdrop-blur-md px-3.5 py-1.5 rounded-full shadow-xs hover:shadow-sm border border-white/80 flex items-center gap-1.5 text-[12px] font-bold text-gray-800 hover:text-[#4F26A6] transition-all cursor-pointer group"
                >
                    <svg class="w-3.5 h-3.5 text-gray-700 group-hover:text-[#4F26A6] transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                    <span>Bagikan Toko</span>
                </button>
            </div>
        </div>

        <!-- 3. Profile Row -->
        <div class="relative pt-0 mb-6 px-1 sm:px-2">
            <!-- Desktop & Tablet (md and above, >=768px): 100% exact to mockup (Avatar on left, Info next to avatar, Buttons on right) -->
            <div class="hidden md:flex items-start justify-between gap-6">
                <!-- Left: Avatar + Full Info side-by-side -->
                <div class="flex items-start gap-6 pl-4">
                    <!-- Circular Avatar -->
                    <div class="-mt-16 xl:-mt-18 shrink-0 z-20">
                        <img
                            src="{{ $sellerAvatar }}"
                            alt="{{ $sellerName }}"
                            class="w-36 h-36 lg:w-40 lg:h-40 rounded-full object-cover ring-4 sm:ring-[5px] ring-white shadow-lg bg-white"
                        />
                    </div>

                    <!-- Seller Info (strictly NEXT to avatar, matching desktop mockup 1:1) -->
                    <div class="flex flex-col pt-2.5">
                        <!-- Name + Verified Rosette -->
                        <div class="flex items-center gap-2 mb-1">
                            <h1 class="text-[25px] xl:text-[28px] font-black text-[#111827] tracking-tight leading-tight">
                                {{ $sellerName }}
                            </h1>
                            <x-verified-badge size="md" class="w-5.5 h-5.5 shrink-0" />
                        </div>

                        <!-- Subtitle / Role -->
                        <p class="text-[14px] text-gray-500 font-medium mb-1">
                            {{ $sellerRole }}
                        </p>

                        <!-- Bio quote -->
                        <p class="text-[14.5px] text-gray-700 font-normal mb-3">
                            &ldquo;{{ $sellerBio }}&rdquo;
                        </p>

                        <!-- Desktop Stats Row: All in one line next to avatar -->
                        <div class="flex items-center gap-3.5 text-[13.5px] text-gray-600 font-medium">
                            <!-- Rating -->
                            <div class="inline-flex items-center gap-1.5 shrink-0">
                                <svg class="w-[18px] h-[18px] text-amber-400 fill-current shrink-0" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                @if($sellerRating !== null)
                                    <span class="font-extrabold text-gray-900 text-[15px]">{{ $sellerRating }}</span>
                                    <span class="text-gray-400 font-normal whitespace-nowrap text-[13px]">({{ $sellerReviewCount }} ulasan)</span>
                                @else
                                    <span class="font-bold text-gray-700 text-[14px]">Belum ada ulasan</span>
                                    <span class="text-gray-400 font-normal whitespace-nowrap text-[13px]">(0 ulasan)</span>
                                @endif
                            </div>

                            <span class="text-gray-200 font-light">|</span>

                            <!-- Barang Count -->
                            <div class="inline-flex items-center gap-1.5 shrink-0">
                                <svg class="w-[18px] h-[18px] text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <span class="font-semibold text-gray-800 whitespace-nowrap text-[13.5px]">{{ $sellerItemsCount }} Barang</span>
                            </div>

                            <span class="text-gray-200 font-light">|</span>

                            <!-- Pengikut -->
                            <div class="inline-flex items-center gap-1.5 shrink-0">
                                <svg class="w-[18px] h-[18px] text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="font-semibold text-gray-800 whitespace-nowrap text-[13.5px]">
                                    {{ is_numeric($sellerFollowerCount) && $sellerFollowerCount == 0 ? '0' : $sellerFollowerCount }} Pengikut
                                </span>
                            </div>

                            <span class="text-gray-200 font-light">|</span>

                            <!-- Bergabung -->
                            <div class="inline-flex items-center gap-1.5 shrink-0 text-gray-500">
                                <svg class="w-[18px] h-[18px] text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                                <span class="text-gray-500 font-normal whitespace-nowrap text-[13px]">Bergabung sejak {{ $sellerJoinedDate }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Desktop Action Buttons -->
                <div class="flex items-center gap-3 pt-6 pr-1 shrink-0">
                    @if($isOwnStore)
                        <a
                            href="{{ route('seller.dashboard') }}"
                            class="px-6 h-11 sm:h-11.5 rounded-xl text-[14px] font-bold flex items-center justify-center gap-2 bg-[#4F26A6] text-white hover:bg-[#3E1D85] shadow-xs cursor-pointer transition-all"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span>Kelola Toko</span>
                        </a>
                    @else
                        <button
                            type="button"
                            @click="isFollowing = !isFollowing"
                            class="px-7 h-11 sm:h-11.5 rounded-xl text-[14px] font-bold flex items-center justify-center gap-2 transition-all cursor-pointer shadow-xs"
                            :class="isFollowing ? 'bg-gray-100 text-gray-800 hover:bg-gray-200' : 'bg-[#4F26A6] text-white hover:bg-[#3E1D85] shadow-[0_4px_16px_rgba(79,38,166,0.22)]'"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path x-show="isFollowing" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                <path x-show="!isFollowing" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span x-text="isFollowing ? 'Mengikuti' : 'Ikuti Toko'"></span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Mobile Only (< 768px): Responsive layout matching user mobile preference -->
            <div class="flex flex-col md:hidden w-full">
                <!-- Top row: Avatar + Name on left, 3-dots button on right -->
                <div class="flex items-start justify-between w-full gap-2 sm:gap-4">
                    <div class="flex items-end gap-3 sm:gap-6 min-w-0">
                        <div class="-mt-12 sm:-mt-16 shrink-0 z-20">
                            <img
                                src="{{ $sellerAvatar }}"
                                alt="{{ $sellerName }}"
                                class="w-24 h-24 sm:w-28 sm:h-28 md:w-32 md:h-32 rounded-full object-cover ring-4 sm:ring-[5px] ring-white shadow-lg bg-white"
                            />
                        </div>
                        <div class="flex flex-col pt-5 sm:pt-7 md:pt-8 pb-1 min-w-0">
                            <div class="flex items-center gap-1.5 sm:gap-2">
                                <h1 class="text-[19px] sm:text-[25px] font-black text-[#111827] tracking-tight leading-tight whitespace-nowrap">
                                    {{ $sellerName }}
                                </h1>
                                <x-verified-badge size="md" class="w-4.5 h-4.5 sm:w-5 sm:h-5 shrink-0" />
                            </div>
                            <p class="text-[13px] sm:text-[14px] text-gray-500 font-medium mt-0.5">
                                {{ $sellerRole }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Below photo: Bio Quote -->
                <p class="text-[14px] sm:text-[15px] text-gray-700 font-normal mt-3 mb-2.5">
                    &ldquo;{{ $sellerBio }}&rdquo;
                </p>

                <!-- Tablet Stats Row (sm to lg: >= 640px and < 1024px) -->
                <div class="hidden sm:flex flex-wrap items-center gap-x-3.5 gap-y-2 text-[13px] sm:text-[14px] text-gray-600 font-medium mb-3">
                    <div class="inline-flex items-center gap-1.5 shrink-0">
                        <svg class="w-[18px] h-[18px] text-amber-400 fill-current shrink-0" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        @if($sellerRating !== null)
                            <span class="font-extrabold text-gray-900 text-[14.5px] sm:text-[15px]">{{ $sellerRating }}</span>
                            <span class="text-gray-400 font-normal whitespace-nowrap text-[12.5px] sm:text-[13px]">({{ $sellerReviewCount }} ulasan)</span>
                        @else
                            <span class="font-bold text-gray-700 text-[13px] sm:text-[13.5px]">Belum ada ulasan</span>
                            <span class="text-gray-400 font-normal whitespace-nowrap text-[12px]">(0 ulasan)</span>
                        @endif
                    </div>

                    <span class="text-gray-200 font-light">|</span>

                    <div class="inline-flex items-center gap-1.5 shrink-0">
                        <svg class="w-[17px] h-[17px] text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="font-semibold text-gray-800 whitespace-nowrap text-[13px] sm:text-[14px]">{{ $sellerItemsCount }} Barang</span>
                    </div>

                    <span class="text-gray-200 font-light">|</span>

                    <div class="inline-flex items-center gap-1.5 shrink-0">
                        <svg class="w-[17px] h-[17px] text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="font-semibold text-gray-800 whitespace-nowrap text-[13px] sm:text-[14px]">
                            {{ is_numeric($sellerFollowerCount) && $sellerFollowerCount == 0 ? '0' : $sellerFollowerCount }} Pengikut
                        </span>
                    </div>

                    <span class="text-gray-200 font-light">|</span>

                    <div class="inline-flex items-center gap-1.5 shrink-0">
                        <svg class="w-[17px] h-[17px] text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        <span class="text-gray-500 font-normal whitespace-nowrap text-[12.5px] sm:text-[13px]">Bergabung sejak {{ $sellerJoinedDate }}</span>
                    </div>
                </div>

                <!-- Mobile-Only Stats Row strictly for < 640px: 4 identical height separators -->
                <div class="flex sm:hidden items-center justify-between w-full pt-3 pb-3 mb-1 px-1">
                    <div class="h-8 w-[1px] bg-gray-200/80 shrink-0"></div>

                    <!-- Rating -->
                    <div class="flex flex-col items-center text-center flex-1">
                        <svg class="w-5 h-5 text-amber-400 fill-current mb-1" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        @if($sellerRating !== null)
                            <span class="text-[19px] font-black text-[#111827] leading-none">{{ $sellerRating }}</span>
                            <span class="text-[12px] text-gray-400 font-normal mt-1.5 whitespace-nowrap">({{ $sellerReviewCount }} ulasan)</span>
                        @else
                            <span class="text-[13px] font-bold text-gray-800 leading-tight">Belum ada ulasan</span>
                            <span class="text-[11px] text-gray-400 font-normal mt-0.5 whitespace-nowrap">(0 ulasan)</span>
                        @endif
                    </div>

                    <div class="h-8 w-[1px] bg-gray-200/80 shrink-0"></div>

                    <!-- Barang -->
                    <div class="flex flex-col items-center text-center flex-1">
                        <svg class="w-5 h-5 text-[#4F26A6] stroke-current fill-none mb-1" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="text-[19px] font-black text-[#111827] leading-none">{{ $sellerItemsCount }}</span>
                        <span class="text-[12px] text-gray-400 font-normal mt-1.5 whitespace-nowrap">Barang</span>
                    </div>

                    <div class="h-8 w-[1px] bg-gray-200/80 shrink-0"></div>

                    <!-- Pengikut -->
                    <div class="flex flex-col items-center text-center flex-1">
                        <svg class="w-5 h-5 text-[#4F26A6] stroke-current fill-none mb-1" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-[19px] font-black text-[#111827] leading-none">
                            {{ is_numeric($sellerFollowerCount) && $sellerFollowerCount == 0 ? '0' : $sellerFollowerCount }}
                        </span>
                        <span class="text-[12px] text-gray-400 font-normal mt-1.5 whitespace-nowrap">Pengikut</span>
                    </div>

                    <div class="h-8 w-[1px] bg-gray-200/80 shrink-0"></div>
                </div>

                <!-- Action Button for Store Owner or Visitors -->
                <div class="w-full pt-1">
                    @if($isOwnStore)
                        <a
                            href="{{ route('seller.dashboard') }}"
                            class="w-full sm:w-auto px-6 h-11 sm:h-11.5 rounded-xl text-xs sm:text-[14px] font-bold flex items-center justify-center gap-2 bg-[#4F26A6] text-white hover:bg-[#3E1D85] shadow-xs cursor-pointer transition-all text-center"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span>Kelola Toko Saya</span>
                        </a>
                    @else
                        <button
                            type="button"
                            @click="isFollowing = !isFollowing"
                            class="w-full sm:w-auto px-7 h-11 sm:h-11.5 rounded-xl text-xs sm:text-[14px] font-bold flex items-center justify-center gap-2 transition-all cursor-pointer shadow-xs"
                            :class="isFollowing ? 'bg-gray-100 text-gray-800 hover:bg-gray-200' : 'bg-[#4F26A6] text-white hover:bg-[#3E1D85] shadow-[0_4px_16px_rgba(79,38,166,0.22)]'"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path x-show="isFollowing" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                <path x-show="!isFollowing" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span x-text="isFollowing ? 'Mengikuti' : 'Ikuti Toko'"></span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- 4. Tabs Navigation: on mobile/tablet justified evenly across width; on desktop left-aligned with pl-4 -->
            <div class="flex items-center justify-around sm:justify-around lg:justify-start gap-2 sm:gap-6 lg:gap-14 text-[15px] sm:text-[16px] md:text-[17px] font-bold mt-8 px-2 sm:px-4 lg:px-0 lg:pl-4 w-full border-b border-gray-200/80">
                <div class="relative flex flex-col items-center flex-1 lg:flex-initial">
                    <button
                        type="button"
                        @click="activeTab = 'produk'"
                        class="pb-3 transition-colors cursor-pointer px-3 text-center w-full lg:w-auto"
                        :class="activeTab === 'produk' ? 'text-[#4F26A6]' : 'text-gray-500 hover:text-gray-900'"
                    >
                        Produk
                    </button>
                    <div x-show="activeTab === 'produk'" class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-[90px] sm:w-[105px] lg:w-[115px] h-[3.5px] bg-[#4F26A6] rounded-full"></div>
                </div>

                <div class="relative flex flex-col items-center flex-1 lg:flex-initial">
                    <button
                        type="button"
                        @click="activeTab = 'ulasan'"
                        class="pb-3 transition-colors cursor-pointer px-3 text-center w-full lg:w-auto"
                        :class="activeTab === 'ulasan' ? 'text-[#4F26A6]' : 'text-gray-500 hover:text-gray-900'"
                    >
                        Ulasan
                    </button>
                    <div x-show="activeTab === 'ulasan'" class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-[90px] sm:w-[105px] lg:w-[115px] h-[3.5px] bg-[#4F26A6] rounded-full" style="display: none;"></div>
                </div>
            </div>
        </div>

        <!-- 5. Tab Content: Produk -->
        <div x-show="activeTab === 'produk'" class="mt-8 flex flex-col lg:flex-row items-start gap-6 xl:gap-8">
            <!-- Left Column: Tentang Rachel Vennya Card matching exact mockup -->
            <div class="w-full lg:w-[320px] xl:w-[340px] shrink-0">
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
                    <h3 class="text-[17px] font-bold text-[#111827] mb-3 tracking-tight">
                        Tentang {{ $sellerName }}
                    </h3>

                    <div class="text-[13px] sm:text-[13.5px] text-gray-600 leading-relaxed space-y-2">
                        <p>
                            {{ $sellerBio }}
                        </p>
                        <p x-show="isBioExpanded" class="text-gray-500 pt-1 text-xs sm:text-[12.5px] leading-relaxed border-t border-gray-100 mt-2">
                            Semua koleksi dijamin original 100%, dirawat dengan baik dari lemari pribadi, dan dikemas secara higienis sebelum dikirimkan ke kamu.
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="isBioExpanded = !isBioExpanded"
                        class="mt-3.5 inline-flex items-center gap-1.5 text-xs sm:text-[13px] font-bold text-[#4F26A6] hover:text-[#3E1D85] transition-colors cursor-pointer"
                    >
                        <span x-text="isBioExpanded ? 'Tutup Selengkapnya' : 'Baca Selengkapnya'"></span>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="isBioExpanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div class="h-[1px] bg-gray-100 my-5"></div>

                    <!-- 3 Value Proposition Rows with comfortable breathing room -->
                    <div class="space-y-2">
                        <!-- 1. Akun Terverifikasi -->
                        <div class="flex items-center gap-4 py-2">
                            <div class="w-9 h-9 flex items-center justify-center text-[#5022CE] shrink-0">
                                <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 2L4 5.5v5.8c0 5.25 3.41 10.15 8 11.35 4.59-1.2 8-6.1 8-11.35V5.5L12 2zm-1.2 14.2l-3.5-3.5 1.41-1.41 2.09 2.08 5.09-5.09 1.41 1.41-6.5 6.51z" />
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <h4 class="text-[14px] font-bold text-gray-900 leading-snug">Akun Terverifikasi</h4>
                                <p class="text-[12.5px] text-gray-500 mt-1 leading-snug">Sudah diverifikasi oleh Whimarket</p>
                            </div>
                        </div>

                        <div class="h-[1px] bg-gray-100/90 my-1.5"></div>

                        <!-- 2. Respon Cepat -->
                        <div class="flex items-center gap-4 py-2">
                            <div class="w-9 h-9 flex items-center justify-center text-[#5022CE] shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <h4 class="text-[14px] font-bold text-gray-900 leading-snug">Respon Cepat</h4>
                                <p class="text-[12.5px] text-gray-500 mt-1 leading-snug">Rata-rata membalas dalam 15 menit</p>
                            </div>
                        </div>

                        <div class="h-[1px] bg-gray-100/90 my-1.5"></div>

                        <!-- 3. Pengiriman Aman -->
                        <div class="flex items-center gap-4 py-2">
                            <div class="w-9 h-9 flex items-center justify-center text-[#5022CE] shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <h4 class="text-[14px] font-bold text-gray-900 leading-snug">Pengiriman Aman</h4>
                                <p class="text-[12.5px] text-gray-500 mt-1 leading-snug">Dikemas dengan bubble wrap tebal</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Catalog (Category Pills, Sorting, Product Grid) -->
            <div class="flex-1 w-full min-w-0">
                <div class="flex flex-col 2xl:flex-row 2xl:items-center justify-between gap-4 mb-6">
                    <!-- Category Pills -->
                    <div class="flex items-center gap-2 sm:gap-2.5 overflow-x-auto pb-1.5 sm:pb-0 [scrollbar-width:none]">
                        <template x-for="cat in [
                            { id: 'all', label: 'Semua' },
                            { id: 'fashion', label: 'Fashion' },
                            { id: 'tas', label: 'Tas & Aksesoris' },
                            { id: 'kecantikan', label: 'Kecantikan' },
                            { id: 'merch', label: 'Merchandise' }
                        ]" :key="cat.id">
                            <button
                                type="button"
                                @click="selectedCategory = cat.id"
                                class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl sm:rounded-2xl text-[13px] sm:text-[14px] font-bold whitespace-nowrap transition-all cursor-pointer"
                                :class="selectedCategory === cat.id ? 'bg-[#4F26A6] text-white shadow-xs' : 'bg-white text-gray-700 hover:bg-gray-100 hover:text-gray-900 border border-gray-200/90'"
                                x-text="cat.label"
                            ></button>
                        </template>
                    </div>

                    <!-- Product Count & Sort (Right aligned) -->
                    <div class="flex items-center justify-between sm:justify-end shrink-0 gap-4">
                        <span class="text-xs sm:text-[13px] text-gray-500 font-medium whitespace-nowrap">
                            Menampilkan <strong class="text-gray-900" x-text="filteredProducts.length"></strong> produk
                        </span>

                        <div class="relative shrink-0" @click.outside="productSortDropdownOpen = false">
                            <button
                                type="button"
                                @click="productSortDropdownOpen = !productSortDropdownOpen"
                                class="w-full inline-flex items-center justify-between gap-2.5 bg-white border border-gray-200 text-xs sm:text-[13px] font-semibold text-gray-800 rounded-xl px-3.5 sm:px-4 py-2 sm:py-2.5 focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] shadow-xs hover:border-gray-300 transition-all cursor-pointer whitespace-nowrap"
                            >
                                <span x-text="sortBy === 'terbaru' ? 'Urutan: Terbaru' : (sortBy === 'harga-terendah' ? 'Harga Terendah' : 'Harga Tertinggi')"></span>
                                <svg
                                    class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200 shrink-0"
                                    :class="productSortDropdownOpen ? 'rotate-180 text-[#4F26A6]' : ''"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Custom Floating Sort Options Panel -->
                            <div
                                x-show="productSortDropdownOpen"
                                x-cloak
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-1"
                                class="absolute right-0 left-0 top-full mt-1.5 min-w-full bg-white border border-gray-100 rounded-2xl shadow-[0_12px_36px_rgba(0,0,0,0.12)] p-1.5 z-40 space-y-0.5 font-medium text-xs sm:text-[13px] text-gray-700"
                                style="display: none;"
                            >
                                <button
                                    type="button"
                                    @click="sortBy = 'terbaru'; productSortDropdownOpen = false"
                                    class="w-full text-left px-3.5 py-2 rounded-xl flex items-center hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors cursor-pointer"
                                    :class="sortBy === 'terbaru' ? 'bg-[#F3EEFF] text-[#4F26A6] font-bold' : ''"
                                >
                                    <span>Urutan: Terbaru</span>
                                </button>
                                <button
                                    type="button"
                                    @click="sortBy = 'harga-terendah'; productSortDropdownOpen = false"
                                    class="w-full text-left px-3.5 py-2 rounded-xl flex items-center hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors cursor-pointer"
                                    :class="sortBy === 'harga-terendah' ? 'bg-[#F3EEFF] text-[#4F26A6] font-bold' : ''"
                                >
                                    <span>Harga Terendah</span>
                                </button>
                                <button
                                    type="button"
                                    @click="sortBy = 'harga-tertinggi'; productSortDropdownOpen = false"
                                    class="w-full text-left px-3.5 py-2 rounded-xl flex items-center hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors cursor-pointer"
                                    :class="sortBy === 'harga-tertinggi' ? 'bg-[#F3EEFF] text-[#4F26A6] font-bold' : ''"
                                >
                                    <span>Harga Tertinggi</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Product Cards Grid: 2 cols mobile, 3 cols tablet, 4 cols desktop xl -->
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 lg:gap-5">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div
                            x-data="{ isLiked: false, likesCount: product.likes || 0 }"
                            class="bg-white rounded-2xl border border-gray-100/90 shadow-[0_4px_16px_rgba(0,0,0,0.04)] hover:shadow-[0_10px_26px_rgba(0,0,0,0.08)] hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col group"
                        >
                            <div class="w-full aspect-[4/5] bg-gray-100 overflow-hidden relative flex items-center justify-center">
                                <!-- Wishlist Heart Button -->
                                <button
                                    type="button"
                                    @click.prevent.stop="toggleWishlist(product)"
                                    class="absolute top-2.5 right-2.5 z-10 w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white/95 backdrop-blur-xs shadow-md flex items-center justify-center transition-transform hover:scale-105 cursor-pointer"
                                    :class="product.is_liked ? 'text-[#4F26A6]' : 'text-gray-700 hover:text-[#4F26A6]'"
                                    title="Simpan ke Wishlist"
                                >
                                    <svg
                                        class="w-4 h-4 sm:w-4.5 sm:h-4.5 transition-colors"
                                        viewBox="0 0 24 24"
                                        :fill="product.is_liked ? 'currentColor' : 'none'"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                    </svg>
                                </button>

                                <a :href="product.href" class="w-full h-full block">
                                    <img :src="product.image" :alt="product.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                </a>
                                <template x-if="product.condition">
                                    <div class="absolute bottom-2.5 left-2.5 z-20 pointer-events-none">
                                        <span class="px-2.5 sm:px-3 py-1 rounded-lg sm:rounded-xl bg-white text-gray-900 text-[11px] sm:text-[12px] font-bold shadow-md border border-black/5" x-text="product.condition"></span>
                                    </div>
                                </template>
                            </div>
                            <div class="p-3.5 sm:p-4 flex flex-col flex-1 justify-between">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <img :src="product.sellerAvatar" :alt="product.sellerName" class="w-5 h-5 sm:w-6 sm:h-6 rounded-full object-cover shrink-0 ring-1 ring-gray-100" />
                                        <span class="text-xs sm:text-[13px] font-bold text-gray-900 truncate" x-text="product.sellerName"></span>
                                        <x-verified-badge size="sm" class="w-3.5 h-3.5 shrink-0" />
                                    </div>
                                    <h3 class="text-[13.5px] sm:text-[14.5px] font-medium text-gray-700 mt-2 mb-3 line-clamp-1">
                                        <a :href="product.href" class="hover:text-[#4F26A6] transition-colors block">
                                            <span x-text="product.title"></span>
                                        </a>
                                    </h3>
                                </div>
                                <div class="flex items-center justify-between pt-1">
                                    <span class="text-sm sm:text-[15.5px] font-bold text-[#4F26A6]" x-text="product.priceText"></span>
                                    <div class="inline-flex items-center gap-1 text-xs text-gray-400 font-medium">
                                        <svg
                                            class="w-3.5 h-3.5 transition-colors"
                                            :class="product.is_liked ? 'text-[#4F26A6] fill-[#4F26A6]' : 'text-gray-400 fill-none'"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        >
                                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                        </svg>
                                        <span x-text="product.likes"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Empty Products State -->
                <template x-if="filteredProducts.length === 0">
                    <div class="py-16 text-center bg-white rounded-3xl border border-gray-100 p-8 shadow-xs mt-4">
                        <div class="w-16 h-16 rounded-3xl bg-[#F3EEFF] text-[#4F26A6] flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/>
                            </svg>
                        </div>
                        <h4 class="text-base font-bold text-gray-900 mb-1">Belum Ada Produk</h4>
                        <p class="text-xs text-gray-500">Toko ini belum menambahkan produk pada kategori ini.</p>
                    </div>
                </template>
            </div>
        </div>

        <!-- 6. Tab Content: Ulasan -->
        <div x-show="activeTab === 'ulasan'" x-cloak class="mt-8">
            <!-- Empty Reviews State -->
            <template x-if="reviewsList.length === 0">
                <div class="py-16 text-center bg-white rounded-3xl border border-gray-100 p-8 shadow-xs max-w-xl mx-auto flex flex-col items-center justify-center">
                    <div class="w-16 h-16 rounded-3xl bg-[#F3EEFF] text-[#4F26A6] flex items-center justify-center mb-4 shadow-2xs">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-extrabold text-gray-900 mb-1.5">Belum Ada Ulasan</h3>
                    <p class="text-xs sm:text-sm text-gray-500 max-w-md mx-auto leading-relaxed">
                        Toko ini belum memiliki ulasan dari pembeli. Jadilah yang pertama membeli barang dan memberikan ulasan untuk toko ini!
                    </p>
                </div>
            </template>
            <!-- Reviews List when reviews exist -->
            <template x-if="reviewsList.length > 0">
                <div class="flex flex-col lg:flex-row items-start gap-8">
                    <!-- Left: Rating Keseluruhan Sidebar -->
                    <div class="w-full lg:w-[310px] xl:w-[330px] shrink-0 space-y-4">
                        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
                            <h3 class="text-base sm:text-[17px] font-extrabold text-gray-900 tracking-tight mb-4">
                                Rating Keseluruhan
                            </h3>
                            <div class="flex items-baseline gap-3 mb-1">
                                <span class="text-5xl font-black text-gray-900 tracking-tight" x-text="reviewsList.length > 0 ? (reviewsList.reduce((acc, r) => acc + r.rating, 0) / reviewsList.length).toFixed(1) : '0'"></span>
                                <div class="flex items-center gap-1 text-amber-400">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 font-medium mb-6" x-text="'dari ' + reviewsList.length + ' ulasan'"></p>

                            <!-- Breakdown Bars -->
                            <div class="space-y-2.5 mb-6">
                                @foreach([5, 4, 3, 2, 1] as $star)
                                    <div class="flex items-center gap-3 text-gray-700 font-medium">
                                        <span class="w-3.5 text-sm font-extrabold text-gray-900">{{ $star }}</span>
                                        <svg class="w-4 h-4 text-amber-400 fill-current shrink-0" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <div class="flex-1 h-2.5 bg-purple-50 rounded-full overflow-hidden">
                                            <div class="h-full bg-[#5022CE] rounded-full" :style="'width: ' + (reviewsList.length > 0 ? (reviewsList.filter(r => r.rating === {{ $star }}).length / reviewsList.length * 100) : 0) + '%'"></div>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-500 w-8 text-right" x-text="reviewsList.filter(r => r.rating === {{ $star }}).length"></span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Filter Header & Review Cards -->
                    <div class="flex-1 w-full space-y-4">
                        <!-- Star Filter Pills & Sort Dropdown -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-gray-100">
                            <!-- Star Filter Pills -->
                            <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0 [scrollbar-width:none]">
                                <button
                                    type="button"
                                    @click="reviewFilter = 'all'"
                                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all cursor-pointer flex items-center gap-1.5 shrink-0"
                                    :class="reviewFilter === 'all' ? 'border-2 border-[#5022CE] text-[#5022CE] bg-purple-50/50' : 'border border-gray-200/80 text-gray-700 bg-white hover:bg-gray-50'"
                                >
                                    <span>Semua (<span x-text="reviewsList.length"></span>)</span>
                                </button>
                                <template x-for="star in [5, 4, 3, 2, 1]" :key="star">
                                    <button
                                        type="button"
                                        @click="reviewFilter = String(star)"
                                        class="px-3 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all cursor-pointer flex items-center gap-1.5 shrink-0"
                                        :class="reviewFilter === String(star) ? 'border-2 border-[#5022CE] text-[#5022CE] bg-purple-50/50' : 'border border-gray-200/80 text-gray-700 bg-white hover:bg-gray-50'"
                                    >
                                        <span x-text="star"></span>
                                        <svg class="w-3.5 h-3.5 text-amber-400 fill-current shrink-0" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <span class="text-gray-400 font-normal text-[11px]" x-text="'(' + reviewsList.filter(r => r.rating === star).length + ')'"></span>
                                    </button>
                                </template>
                            </div>

                            <!-- Review Sort Dropdown -->
                            <div class="relative shrink-0" @click.outside="reviewSortDropdownOpen = false">
                                <button
                                    type="button"
                                    @click="reviewSortDropdownOpen = !reviewSortDropdownOpen"
                                    class="h-9 px-3.5 rounded-xl bg-white border border-gray-200/90 text-xs font-semibold text-gray-800 flex items-center justify-between gap-2 shadow-2xs hover:border-[#4F26A6] transition-colors cursor-pointer"
                                >
                                    <span x-text="reviewSort === 'terbaru' ? 'Urutan: Terbaru' : (reviewSort === 'tertinggi' ? 'Rating Tertinggi' : 'Rating Terendah')"></span>
                                    <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200 shrink-0" :class="reviewSortDropdownOpen ? 'rotate-180 text-[#4F26A6]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div
                                    x-show="reviewSortDropdownOpen"
                                    x-cloak
                                    x-transition
                                    class="absolute right-0 top-full mt-1.5 w-44 bg-white border border-gray-100 rounded-2xl shadow-xl p-1.5 z-40 space-y-0.5 font-medium text-xs text-gray-700"
                                >
                                    <button type="button" @click="reviewSort = 'terbaru'; reviewSortDropdownOpen = false" class="w-full text-left px-3 py-2 rounded-xl flex items-center hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors cursor-pointer" :class="reviewSort === 'terbaru' ? 'bg-[#F3EEFF] text-[#4F26A6] font-bold' : ''">Urutan: Terbaru</button>
                                    <button type="button" @click="reviewSort = 'tertinggi'; reviewSortDropdownOpen = false" class="w-full text-left px-3 py-2 rounded-xl flex items-center hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors cursor-pointer" :class="reviewSort === 'tertinggi' ? 'bg-[#F3EEFF] text-[#4F26A6] font-bold' : ''">Rating Tertinggi</button>
                                    <button type="button" @click="reviewSort = 'terendah'; reviewSortDropdownOpen = false" class="w-full text-left px-3 py-2 rounded-xl flex items-center hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors cursor-pointer" :class="reviewSort === 'terendah' ? 'bg-[#F3EEFF] text-[#4F26A6] font-bold' : ''">Rating Terendah</button>
                                </div>
                            </div>
                        </div>
                        <!-- Review Cards List Container -->
                        <div class="space-y-4">
                            <template x-for="rev in filteredReviews" :key="rev.id">
                                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.03)] space-y-4">
                                    <!-- Author info & Rating -->
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex items-center gap-3">
                                            <img :src="rev.avatar" :alt="rev.author" class="w-11 h-11 rounded-full object-cover ring-2 ring-purple-100 shrink-0" />
                                            <div>
                                                <div class="flex items-center gap-1.5">
                                                    <h4 class="text-sm sm:text-base font-extrabold text-gray-900" x-text="rev.author"></h4>
                                                    <template x-if="rev.verified">
                                                        <span class="px-1.5 py-0.5 rounded-md bg-purple-50 text-[#4F26A6] text-[10px] font-bold">Terverifikasi</span>
                                                    </template>
                                                </div>
                                                <div class="flex items-center gap-1 text-amber-400 mt-0.5">
                                                    <template x-for="s in 5">
                                                        <svg class="w-3.5 h-3.5" :class="s <= rev.rating ? 'fill-current' : 'text-gray-200 fill-current'" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    </template>
                                                    <span class="text-[11.5px] text-gray-400 font-normal ml-1.5" x-text="rev.date"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Review Comment -->
                                    <p class="text-xs sm:text-sm text-gray-700 leading-relaxed font-normal" x-text="rev.comment"></p>

                                    <!-- Review Photos with Lightbox Trigger -->
                                    <template x-if="rev.images && rev.images.length > 0">
                                        <div class="flex items-center gap-2.5 flex-wrap pt-1">
                                            <template x-for="(img, idx) in rev.images" :key="idx">
                                                <button
                                                    type="button"
                                                    @click="openReviewMedia(rev, idx)"
                                                    class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden border border-gray-100 hover:opacity-90 hover:scale-102 transition-all cursor-pointer bg-gray-50 shrink-0"
                                                >
                                                    <img :src="img" :alt="'Foto ulasan ' + rev.author" class="w-full h-full object-cover" />
                                                </button>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- Product Attached Card -->
                                    <template x-if="rev.product">
                                        <div class="p-3 rounded-2xl bg-[#FAF9FC] border border-gray-100 flex items-center justify-between gap-3">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <img :src="rev.product.image" :alt="rev.product.title" class="w-11 h-11 rounded-xl object-cover border border-gray-200/80 shrink-0 bg-white" />
                                                <div class="min-w-0">
                                                    <h5 class="text-xs font-bold text-gray-900 truncate" x-text="rev.product.title"></h5>
                                                    <p class="text-xs font-extrabold text-[#4F26A6] mt-0.5" x-text="rev.product.priceText"></p>
                                                </div>
                                            </div>
                                            <a :href="rev.product.url" class="text-xs font-bold text-[#4F26A6] hover:underline shrink-0 flex items-center gap-1">
                                                <span>Lihat</span>
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                            </a>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <!-- Share Store Modal Popup -->
        <div
            x-show="shareModalOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs overflow-y-auto"
        >
            <div
                @click.outside="shareModalOpen = false"
                class="bg-white rounded-3xl overflow-hidden max-w-md w-full shadow-[0_25px_60px_rgba(79,38,166,0.2)] border border-gray-100 relative my-8"
            >
                <!-- Modal Banner Header with Generated Illustration -->
                <div class="relative h-32 sm:h-36 w-full overflow-hidden bg-gradient-to-br from-[#4F26A6] to-[#7C3AED] flex items-center justify-center">
                    <img
                        src="/assets/modals/share-store-header.png"
                        alt="Bagikan Toko"
                        class="w-full h-full object-cover mix-blend-luminosity opacity-40 absolute inset-0"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                    
                    <button
                        type="button"
                        @click="shareModalOpen = false"
                        class="absolute top-3.5 right-3.5 z-20 w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-md text-white flex items-center justify-center transition-all cursor-pointer"
                        title="Tutup"
                    >
                        ✕
                    </button>

                    <div class="relative z-10 text-center px-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-white text-[11px] font-extrabold mb-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                            Bagikan Toko Resmi
                        </span>
                        <h3 class="text-lg sm:text-xl font-black text-white tracking-tight">Koleksi Pre-loved Kreator</h3>
                    </div>
                </div>

                <div class="p-6 sm:p-7 space-y-5">
                    <!-- Store Preview Card -->
                    <div class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-[#FAF9FC] border border-gray-100/90 shadow-2xs">
                        <img src="{{ $sellerAvatar }}" alt="{{ $sellerName }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-purple-100 shrink-0" />
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5">
                                <h4 class="text-sm font-extrabold text-gray-950 truncate">{{ $sellerName }}</h4>
                                <x-verified-badge size="sm" class="w-3.5 h-3.5 shrink-0" />
                            </div>
                            <p class="text-xs text-gray-500 font-medium truncate mt-0.5">Toko Resmi Terverifikasi di WhiMarket</p>
                        </div>
                    </div>

                    <!-- Share to Social Media Grid -->
                    <div>
                        <span class="text-xs font-bold text-gray-700 mb-2.5 block uppercase tracking-wider">Bagikan via:</span>
                        <div class="grid grid-cols-4 gap-2.5 text-center">
                            <!-- WhatsApp -->
                            <a
                                :href="'https://wa.me/?text=' + encodeURIComponent('Lihat toko resmi {{ addslashes($sellerName) }} di WhiMarket! ' + window.location.href)"
                                target="_blank"
                                rel="noopener"
                                class="flex flex-col items-center gap-1.5 p-2.5 rounded-2xl hover:bg-emerald-50 text-gray-700 hover:text-emerald-600 transition-all border border-gray-100 hover:border-emerald-200 group/soc"
                            >
                                <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-xs group-hover/soc:scale-105 transition-transform">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.145.158 0 .433.058.663.347.23.289.88 2.14.953 2.285.072.145.115.318.014.521z"/></svg>
                                </div>
                                <span class="text-[11px] font-bold">WhatsApp</span>
                            </a>
                            <!-- Telegram -->
                            <a
                                :href="'https://t.me/share/url?url=' + encodeURIComponent(window.location.href) + '&text=' + encodeURIComponent('Lihat toko resmi {{ addslashes($sellerName) }} di WhiMarket!')"
                                target="_blank"
                                rel="noopener"
                                class="flex flex-col items-center gap-1.5 p-2.5 rounded-2xl hover:bg-sky-50 text-gray-700 hover:text-sky-600 transition-all border border-gray-100 hover:border-sky-200 group/soc"
                            >
                                <div class="w-10 h-10 rounded-xl bg-sky-500 text-white flex items-center justify-center shadow-xs group-hover/soc:scale-105 transition-transform">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.832.942z"/></svg>
                                </div>
                                <span class="text-[11px] font-bold">Telegram</span>
                            </a>
                            <!-- X Twitter -->
                            <a
                                :href="'https://twitter.com/intent/tweet?text=' + encodeURIComponent('Belanja barang pre-loved resmi {{ addslashes($sellerName) }} di WhiMarket! ' + window.location.href)"
                                target="_blank"
                                rel="noopener"
                                class="flex flex-col items-center gap-1.5 p-2.5 rounded-2xl hover:bg-gray-100 text-gray-700 hover:text-black transition-all border border-gray-100 hover:border-gray-300 group/soc"
                            >
                                <div class="w-10 h-10 rounded-xl bg-gray-950 text-white flex items-center justify-center shadow-xs group-hover/soc:scale-105 transition-transform">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </div>
                                <span class="text-[11px] font-bold">X (Twitter)</span>
                            </a>
                            <!-- Facebook -->
                            <a
                                :href="'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(window.location.href)"
                                target="_blank"
                                rel="noopener"
                                class="flex flex-col items-center gap-1.5 p-2.5 rounded-2xl hover:bg-blue-50 text-gray-700 hover:text-blue-600 transition-all border border-gray-100 hover:border-blue-200 group/soc"
                            >
                                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-xs group-hover/soc:scale-105 transition-transform">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M9 8H6v4h3v12h5V12h3.642L18 8h-4V6.333C14 5.374 14.5 5 15.667 5H18V0h-3.808C10.596 0 9 1.583 9 4.615V8z"/></svg>
                                </div>
                                <span class="text-[11px] font-bold">Facebook</span>
                            </a>
                        </div>
                    </div>

                    <!-- Copy Link Input Box -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Atau Salin Tautan Toko:</label>
                        <div class="flex items-center gap-2 p-1.5 pl-3.5 rounded-2xl bg-[#F9FAFB] border border-gray-200 focus-within:border-[#4F26A6] focus-within:ring-2 focus-within:ring-[#4F26A6]/20 transition-all">
                            <span class="text-xs text-gray-600 font-medium truncate flex-1" x-text="window.location.href"></span>
                            <button
                                type="button"
                                @click="copyShare()"
                                class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all shrink-0 cursor-pointer shadow-xs"
                                :class="isShareCopied ? 'bg-emerald-600 text-white' : 'bg-[#4F26A6] hover:bg-[#3E1D85] text-white'"
                            >
                                <span x-text="isShareCopied ? 'Tersalin! ✓' : 'Salin Tautan'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Media Popup Lightbox Modal -->
        <div
            x-show="reviewModalOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-md"
        >
            <div
                @click.outside="reviewModalOpen = false"
                class="bg-white rounded-3xl overflow-hidden max-w-2xl w-full shadow-2xl border border-gray-100 flex flex-col relative"
            >
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-[#FAF9FC]">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-purple-50 text-[#4F26A6] flex items-center justify-center font-bold text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-extrabold text-gray-900" x-text="'Foto Ulasan • ' + activeModalAuthor"></h4>
                            <p class="text-[11.5px] text-gray-400 font-medium" x-text="'Foto ' + (activeModalIndex + 1) + ' dari ' + activeModalImages.length"></p>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="reviewModalOpen = false"
                        class="w-8 h-8 rounded-full bg-white hover:bg-gray-100 border border-gray-200 text-gray-700 flex items-center justify-center transition-all hover:scale-105 active:scale-95 cursor-pointer shadow-2xs"
                    >
                        ✕
                    </button>
                </div>

                <!-- Modal Media View Area -->
                <div class="relative bg-black/95 flex items-center justify-center min-h-[320px] sm:min-h-[420px] max-h-[550px] overflow-hidden select-none">
                    <img :src="activeModalImg" alt="Ulasan Foto" class="max-w-full max-h-[500px] object-contain" />

                    <!-- Prev Button -->
                    <button
                        type="button"
                        x-show="activeModalIndex > 0"
                        @click="prevReviewMedia()"
                        class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/90 hover:bg-white text-gray-800 hover:text-[#4F26A6] flex items-center justify-center transition-all cursor-pointer shadow-xl backdrop-blur-md hover:scale-110 active:scale-95"
                    >
                        &larr;
                    </button>

                    <!-- Next Button -->
                    <button
                        type="button"
                        x-show="activeModalIndex < activeModalImages.length - 1"
                        @click="nextReviewMedia()"
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/90 hover:bg-white text-gray-800 hover:text-[#4F26A6] flex items-center justify-center transition-all cursor-pointer shadow-xl backdrop-blur-md hover:scale-110 active:scale-95"
                    >
                        &rarr;
                    </button>
                </div>

                <!-- Modal Caption Footer -->
                <div class="p-5 sm:p-6 bg-white border-t border-gray-100">
                    <p class="text-xs sm:text-sm text-gray-700 italic leading-relaxed" x-text="'&ldquo;' + activeModalComment + '&rdquo;'"></p>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
    function registerSellerProfile() {
        Alpine.data('sellerProfile', () => ({
            activeTab: 'produk',
            isFollowing: false,
            isShareCopied: false,
            isBioExpanded: false,
            shareModalOpen: false,
            reviewModalOpen: false,
            activeModalImg: '',
            activeModalAuthor: '',
            activeModalComment: '',
            activeModalImages: [],
            activeModalIndex: 0,
            selectedCategory: 'all',
            sortBy: 'terbaru',
            productSortDropdownOpen: false,
            searchQuery: '',
            reviewFilter: 'all',
            reviewSort: 'terbaru',
            reviewSortDropdownOpen: false,
            allProducts: @js($displayProducts),

            get filteredProducts() {
                let list = this.allProducts.filter(item => {
                    if (this.selectedCategory !== 'all' && item.category !== this.selectedCategory) return false;
                    if (this.searchQuery && !item.title.toLowerCase().includes(this.searchQuery.toLowerCase())) return false;
                    return true;
                });
                if (this.sortBy === 'harga-terendah') list.sort((a, b) => a.priceNumber - b.priceNumber);
                if (this.sortBy === 'harga-tertinggi') list.sort((a, b) => b.priceNumber - a.priceNumber);
                return list;
            },
            copyShare() {
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(window.location.href);
                    this.isShareCopied = true;
                    setTimeout(() => { this.isShareCopied = false; }, 2500);
                }
            },

            async toggleWishlist(product) {
                @if(!auth()->check())
                    window.location.href = '{{ route('login') }}';
                    return;
                @endif
                try {
                    const res = await fetch('/wishlist/toggle/' + (product.model_id || product.id), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    const data = await res.json();
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else if (data.success) {
                        product.is_liked = data.is_liked;
                        product.likes = data.likes_count;
                        if (data.user_wishlists_count !== undefined) {
                            document.querySelectorAll('a[href*="/wishlist"] span').forEach(el => el.textContent = data.user_wishlists_count);
                        }
                    }
                } catch (e) {
                    product.is_liked = !product.is_liked;
                }
            },

            reviewsList: @js($reviewsList ?? []),

            get filteredReviews() {
                let list = this.reviewsList.filter(r => {
                    if (this.reviewFilter === 'all') return true;
                    return String(r.rating) === String(this.reviewFilter);
                });
                if (this.reviewSort === 'tertinggi') list.sort((a, b) => b.rating - a.rating);
                if (this.reviewSort === 'terendah') list.sort((a, b) => a.rating - b.rating);
                return list;
            },

            openReviewMedia(review, imgIndex) {
                this.activeModalImages = review.images;
                this.activeModalIndex = imgIndex;
                this.activeModalImg = review.images[imgIndex];
                this.activeModalAuthor = review.author;
                this.activeModalComment = review.comment;
                this.reviewModalOpen = true;
            },

            nextReviewMedia() {
                if (this.activeModalIndex < this.activeModalImages.length - 1) {
                    this.activeModalIndex++;
                    this.activeModalImg = this.activeModalImages[this.activeModalIndex];
                }
            },

            prevReviewMedia() {
                if (this.activeModalIndex > 0) {
                    this.activeModalIndex--;
                    this.activeModalImg = this.activeModalImages[this.activeModalIndex];
                }
            }
        }));
    }
    if (window.Alpine) {
        registerSellerProfile();
    } else {
        document.addEventListener('alpine:init', registerSellerProfile);
    }
    </script>
    @endpush
</x-layouts.app>
