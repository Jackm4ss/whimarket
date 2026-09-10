<x-layouts.app :title="$title" activeTab="followed_sellers">
    <main
        class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-6 sm:pt-8 pb-24 sm:pb-32 lg:pb-36"
        x-data="followedStoresManager"
    >
        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs sm:text-[13px] text-gray-500 font-medium mb-6">
            <a href="/" class="hover:text-[#4F26A6] transition-colors">Beranda</a>
            <span class="text-gray-300 font-normal">&gt;</span>
            <span class="text-gray-900 font-bold">Toko yang Diikuti</span>
        </nav>

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 sm:mb-10 pb-6 sm:pb-8 border-b border-gray-100">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                        Toko yang Diikuti
                    </h1>
                    @auth
                        <template x-if="stores.length > 0">
                            <span
                                class="text-xs font-bold text-[#4F26A6] bg-[#F3EEFF] px-3 py-1 rounded-full border border-[#4F26A6]/10"
                                x-text="stores.length + ' Toko'"
                            >
                                {{ $followedSellers->count() }} Toko
                            </span>
                        </template>
                    @endauth
                </div>
                <p class="text-sm sm:text-[15px] text-gray-500 mt-1.5">
                    @guest
                        Masuk ke akunmu untuk melihat dan mengelola toko kreator yang kamu ikuti.
                    @else
                        Daftar kreator dan seller resmi yang kamu ikuti untuk memantau koleksi pre-loved &amp; produk terbaru.
                    @endguest
                </p>
            </div>
            @auth
                <a
                    href="{{ route('seller.directory') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border-2 border-[#4F26A6] text-[#4F26A6] hover:bg-[#4F26A6]/5 font-bold text-xs sm:text-sm transition-all shrink-0 cursor-pointer shadow-2xs"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <span>Jelajahi Seller Lain</span>
                </a>
            @endauth
        </div>

        @guest
            <!-- Guest State: Prompt to Login -->
            <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-8 sm:p-14 text-center max-w-xl mx-auto my-8">
                <div class="w-24 h-24 sm:w-28 sm:h-28 mx-auto mb-6 rounded-full bg-[#F3EEFF] flex items-center justify-center">
                    <svg class="w-12 h-12 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight mb-2">
                    Masuk untuk Melihat Toko yang Diikuti
                </h3>
                <p class="text-sm text-gray-500 max-w-md mx-auto mb-8 leading-relaxed">
                    Ikuti kreator favoritmu dan dapatkan akses cepat ke koleksi pre-loved terbaru mereka. Masuk atau daftar akun WhiMarket sekarang.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a
                        href="{{ route('login') }}"
                        class="w-full sm:w-auto px-6 py-3 rounded-xl bg-[#4F26A6] text-white font-bold text-sm hover:bg-[#3E1D85] transition-all shadow-md shadow-[#4F26A6]/20 text-center cursor-pointer"
                    >
                        Masuk ke Akun
                    </a>
                    <a
                        href="{{ route('register') }}"
                        class="w-full sm:w-auto px-6 py-3 rounded-xl border-2 border-gray-200 text-gray-700 font-bold text-sm hover:bg-gray-50 transition-all text-center cursor-pointer"
                    >
                        Daftar Akun Baru
                    </a>
                </div>
            </div>
        @else
            <!-- Authenticated: Empty State -->
            <template x-if="stores.length === 0">
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-8 sm:p-14 text-center max-w-xl mx-auto my-8">
                    <div class="w-24 h-24 sm:w-28 sm:h-28 mx-auto mb-6 rounded-full bg-[#F3EEFF] flex items-center justify-center">
                        <svg class="w-12 h-12 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight mb-2">
                        Belum Ada Toko yang Diikuti
                    </h3>
                    <p class="text-sm text-gray-500 max-w-md mx-auto mb-8 leading-relaxed">
                        Kamu belum mengikuti toko seller atau kreator manapun. Jelajahi katalog seller terverifikasi kami untuk menemukan barang favoritmu!
                    </p>
                    <a
                        href="{{ route('seller.directory') }}"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-[#4F26A6] text-white font-bold text-sm hover:bg-[#3E1D85] transition-all shadow-md shadow-[#4F26A6]/20 cursor-pointer"
                    >
                        <span>Eksplor Daftar Seller</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
            </template>

            <!-- Authenticated: Store Cards Grid -->
            <template x-if="stores.length > 0">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 sm:gap-8">
                    <template x-for="seller in stores" :key="seller.id">
                        <div class="bg-white rounded-2xl sm:rounded-3xl border border-gray-100/90 shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_30px_rgba(79,38,166,0.08)] transition-all flex flex-col justify-between overflow-hidden group">
                            <div>
                                <!-- Banner Header -->
                                <div class="relative w-full h-[120px] sm:h-[130px] bg-[#E8DEFD] overflow-hidden">
                                    <img
                                        :src="seller.banner_url"
                                        :alt="seller.store_name"
                                        class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                                    />
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                                    <template x-if="!seller.is_active">
                                        <div class="absolute top-3 right-3">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-100 text-amber-900 border border-amber-300 shadow-2xs">
                                                Toko Nonaktif
                                            </span>
                                        </div>
                                    </template>
                                </div>

                                <!-- Avatar & Main Store Info -->
                                <div class="px-5 sm:px-6 pt-0 pb-4">
                                    <div class="flex items-end justify-between -mt-9 mb-3">
                                        <div class="relative w-18 h-18 sm:w-20 sm:h-20 rounded-full p-0.5 bg-white ring-4 ring-white shadow-md overflow-hidden shrink-0">
                                            <img
                                                :src="seller.avatar_url"
                                                :alt="seller.store_name"
                                                class="w-full h-full object-cover rounded-full"
                                            />
                                        </div>
                                        <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-purple-50 text-[#4F26A6] border border-purple-100">
                                            Verified Creator
                                        </span>
                                    </div>

                                    <!-- Store Title & Username -->
                                    <div class="flex items-center gap-1.5 mb-1">
                                        <a
                                            :href="'/seller/@' + seller.username"
                                            class="text-[17px] sm:text-[18px] font-black text-gray-900 hover:text-[#4F26A6] transition-colors truncate tracking-tight"
                                            x-text="seller.store_name"
                                        ></a>
                                        <template x-if="seller.is_active">
                                            <x-verified-badge size="sm" class="w-4 h-4 text-[#4F26A6] shrink-0" />
                                        </template>
                                    </div>
                                    <p class="text-xs text-gray-400 font-medium mb-3" x-text="'@' + seller.username"></p>

                                    <!-- Bio snippet -->
                                    <template x-if="seller.bio">
                                        <p class="text-xs text-gray-600 line-clamp-2 italic mb-3.5 leading-relaxed" x-text="'&ldquo;' + seller.bio + '&rdquo;'"></p>
                                    </template>

                                    <!-- Key Metrics Row -->
                                    <div class="flex items-center justify-between py-2.5 px-3.5 bg-[#FAF9FC] rounded-xl border border-gray-100/90 text-xs mb-4">
                                        <div class="flex flex-col items-center flex-1">
                                            <span class="font-extrabold text-gray-900 text-[13.5px]" x-text="seller.items_count"></span>
                                            <span class="text-[10.5px] text-gray-400 font-medium">Barang</span>
                                        </div>
                                        <div class="h-6 w-[1px] bg-gray-200"></div>
                                        <div class="flex flex-col items-center flex-1">
                                            <span class="font-extrabold text-gray-900 text-[13.5px]" x-text="seller.followers_count"></span>
                                            <span class="text-[10.5px] text-gray-400 font-medium">Pengikut</span>
                                        </div>
                                        <div class="h-6 w-[1px] bg-gray-200"></div>
                                        <div class="flex flex-col items-center flex-1">
                                            <span class="font-extrabold text-gray-900 text-[13.5px]" x-text="seller.rating"></span>
                                            <span class="text-[10.5px] text-gray-400 font-medium">Rating</span>
                                        </div>
                                    </div>

                                    <!-- Products Preview Thumbnails -->
                                    <template x-if="seller.products && seller.products.length > 0">
                                        <div class="mb-2">
                                            <div class="flex items-center justify-between text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">
                                                <span>Produk Terbaru</span>
                                                <span class="text-[#4F26A6] font-semibold" x-text="seller.products.length + ' item'"></span>
                                            </div>
                                            <div class="grid grid-cols-4 gap-2">
                                                <template x-for="prod in seller.products" :key="prod.id">
                                                    <a
                                                        :href="'/produk/' + prod.slug"
                                                        class="group/item relative aspect-square rounded-xl overflow-hidden bg-gray-100 border border-gray-100 hover:border-[#4F26A6]/40 transition-all block shadow-2xs"
                                                        :title="prod.name"
                                                    >
                                                        <img
                                                            :src="prod.image_url"
                                                            :alt="prod.name"
                                                            class="w-full h-full object-cover group-hover/item:scale-110 transition-transform duration-300"
                                                        />
                                                    </a>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Footer Action Buttons -->
                            <div class="p-4 sm:p-5 pt-0 border-t border-gray-100 flex items-center gap-2.5 mt-2">
                                <a
                                    :href="'/seller/@' + seller.username"
                                    class="flex-1 py-2.5 px-4 rounded-xl bg-[#4F26A6] text-white hover:bg-[#3E1D85] font-bold text-xs sm:text-[13px] flex items-center justify-center gap-1.5 transition-all shadow-xs text-center cursor-pointer"
                                >
                                    <span>Kunjungi Toko</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                                <button
                                    type="button"
                                    @click="unfollowStore(seller)"
                                    :disabled="seller.isUnfollowing"
                                    class="py-2.5 px-3.5 rounded-xl border border-gray-200 text-gray-600 hover:text-red-600 hover:bg-red-50 hover:border-red-200 font-bold text-xs sm:text-[13px] flex items-center justify-center gap-1 transition-all cursor-pointer shrink-0 disabled:opacity-50"
                                    title="Batal ikuti toko ini"
                                >
                                    <template x-if="!seller.isUnfollowing">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span class="hidden sm:inline">Batal Ikuti</span>
                                        </div>
                                    </template>
                                    <template x-if="seller.isUnfollowing">
                                        <svg class="w-3.5 h-3.5 animate-spin text-gray-500" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </template>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        @endguest
    </main>

    @push('scripts')
    <script>
    function registerFollowedStores() {
        Alpine.data('followedStoresManager', () => ({
            stores: @js($followedSellers->map(function ($s) {
                $isDemo = in_array(strtolower($s->username), ['rachelvennya', 'celloszx', 'raisa6690', 'fuji_an', 'windahbasudara', 'bramastavrl']);
                return [
                    'id' => $s->id,
                    'store_name' => $s->store_name,
                    'username' => $s->username,
                    'bio' => $s->bio,
                    'banner_url' => $s->banner_url,
                    'avatar_url' => $s->avatar_url,
                    'is_active' => $s->status === \App\Enums\SellerStatus::VERIFIED,
                    'items_count' => $s->products_count ?? $s->products()->count(),
                    'followers_count' => $s->followers_count_formatted,
                    'rating' => $isDemo ? '4.9' : '5.0',
                    'isUnfollowing' => false,
                    'products' => $s->products->map(fn ($p) => [
                        'id' => $p->id,
                        'name' => $p->name,
                        'slug' => $p->slug,
                        'price' => 'Rp ' . number_format((float) $p->price, 0, ',', '.'),
                        'image_url' => $p->primary_image_url,
                    ])->values()->all(),
                ];
            })->values()->all()),

            async unfollowStore(seller) {
                if (!confirm(`Apakah Anda yakin ingin berhenti mengikuti toko "${seller.store_name}"?`)) {
                    return;
                }
                seller.isUnfollowing = true;
                try {
                    const res = await fetch(`/seller/toggle-follow/${seller.id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    });
                    const data = await res.json();
                    if (data.success && !data.is_following) {
                        this.stores = this.stores.filter(s => s.id !== seller.id);
                        if (data.user_followed_count !== undefined) {
                            document.querySelectorAll('a[href*="/toko-diikuti"] span').forEach(el => el.textContent = data.user_followed_count);
                        }
                    } else if (data.message) {
                        alert(data.message);
                    }
                } catch (e) {
                    alert('Terjadi kesalahan koneksi.');
                } finally {
                    seller.isUnfollowing = false;
                }
            }
        }));
    }
    if (window.Alpine) {
        registerFollowedStores();
    } else {
        document.addEventListener('alpine:init', registerFollowedStores);
    }
    </script>
    @endpush
</x-layouts.app>
