@props([
    'activeTab' => 'beranda',
    'wishlistCount' => 0,
    'cartCount' => 0,
    'user' => null
])

<div class="sticky top-0 z-50 w-full"
    x-data="{
        isScrolled: false,
        isProfileOpen: false,
        isMobileMenuOpen: false,
        isBelanjaOpen: false,
        isKategoriOpen: false,
        init() {
            window.addEventListener('scroll', () => {
                this.isScrolled = window.scrollY > 15;
            }, { passive: true });
        }
    }"
>
    <!-- ==================== MAIN HEADER ==================== -->
    <header
        id="main-header"
        :class="isScrolled ? '!bg-white shadow-[0_4px_20px_-2px_rgba(0,0,0,0.06)] !border-gray-100' : 'bg-white/95 sm:bg-white/90 lg:bg-transparent backdrop-blur-md lg:backdrop-blur-none'"
        class="w-full transition-colors duration-200 border-b border-gray-100 lg:border-transparent"
    >
        <div id="header-container" class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-4 sm:py-5 flex items-center justify-between gap-4 lg:gap-6">
            <!-- Left Area: Brand Logo & Navigation Links -->
            <div class="flex items-center gap-6 xl:gap-8">
                <a href="/" class="flex items-center group shrink-0">
                    <img
                        src="/assets/logo-whimarket.png"
                        alt="WhiMarket"
                        width="140"
                        height="42"
                        decoding="async"
                        class="h-8 sm:h-10 lg:h-[42px] w-auto object-contain mix-blend-multiply"
                    />
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden lg:flex items-center gap-9 xl:gap-10 text-[15px] xl:text-[15.5px] font-semibold text-gray-700">
                    <!-- Beranda -->
                    <div class="relative py-1 flex flex-col items-center {{ $activeTab === 'beranda' ? 'text-[#4F26A6] font-bold' : 'text-gray-700 font-semibold hover:text-[#4F26A6] transition-colors' }}">
                        <a href="/" class="focus:outline-none cursor-pointer">
                            Beranda
                        </a>
                        @if($activeTab === 'beranda')
                            <span class="absolute -bottom-2 left-0 right-0 h-[3px] bg-[#4F26A6] rounded-full"></span>
                        @endif
                    </div>

                    <!-- Dropdown: Belanja -->
                    <div class="relative group py-1 flex flex-col items-center {{ $activeTab === 'belanja' ? 'text-[#4F26A6] font-bold' : 'text-gray-700 font-semibold' }}">
                        <a href="/belanja" class="flex items-center gap-1.5 transition-colors focus:outline-none py-1 cursor-pointer {{ $activeTab === 'belanja' ? 'text-[#4F26A6]' : 'hover:text-[#4F26A6]' }}">
                            <span>Belanja</span>
                            <svg class="w-3 h-3 text-gray-400 group-hover:text-[#4F26A6] transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </a>
                        @if($activeTab === 'belanja')
                            <span class="absolute -bottom-2 left-0 right-0 h-[3px] bg-[#4F26A6] rounded-full"></span>
                        @endif

                        <!-- Belanja Dropdown Panel -->
                        <div class="absolute left-0 top-full pt-2 w-56 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 z-50 transform translate-y-1 group-hover:translate-y-0 text-left">
                            <div class="bg-white rounded-2xl shadow-[0_12px_36px_rgba(0,0,0,0.12)] border border-gray-100 p-2 space-y-0.5 font-normal">
                                <a href="/belanja" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                                    <svg class="w-4 h-4 text-gray-500 group-hover/item:text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/>
                                    </svg>
                                    <span>Semua Produk</span>
                                </a>
                                <a href="/#barang-terbaru" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                                    <svg class="w-4 h-4 text-gray-500 group-hover/item:text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                    </svg>
                                    <span>Produk Terbaru</span>
                                </a>
                                <a href="/#seller-populer" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                                    <svg class="w-4 h-4 text-gray-500 group-hover/item:text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                    </svg>
                                    <span>Produk Populer</span>
                                </a>
                                <a href="/#promo-spesial" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                                    <svg class="w-4 h-4 text-gray-500 group-hover/item:text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <span>Promo Spesial</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown: Kategori -->
                    <div class="relative group py-1">
                        <button class="flex items-center gap-1.5 text-gray-700 font-semibold hover:text-[#4F26A6] transition-colors focus:outline-none py-1">
                            <span>Kategori</span>
                            <svg class="w-3 h-3 text-gray-400 group-hover:text-[#4F26A6] transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="absolute left-0 top-full pt-2 w-60 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 z-50 transform translate-y-1 group-hover:translate-y-0">
                            <div class="bg-white rounded-2xl shadow-[0_12px_36px_rgba(0,0,0,0.12)] border border-gray-100 p-2 space-y-0.5">
                                <a href="/belanja?kategori=fashion" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                                    <svg class="w-4 h-4 text-gray-500 group-hover/item:text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 3l-4 2-4-2L3 6v4l3 1v10h12V11l3-1V6l-5-3z"/>
                                    </svg>
                                    <span>Fashion</span>
                                </a>
                                <a href="/belanja?kategori=tas" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                                    <svg class="w-4 h-4 text-gray-500 group-hover/item:text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <span>Tas &amp; Aksesoris</span>
                                </a>
                                <a href="/belanja?kategori=hobi" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                                    <svg class="w-4 h-4 text-gray-500 group-hover/item:text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                    </svg>
                                    <span>Hobi &amp; Koleksi</span>
                                </a>
                                <a href="/belanja?kategori=merchandise" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                                    <svg class="w-4 h-4 text-gray-500 group-hover/item:text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>
                                    </svg>
                                    <span>Merchandise</span>
                                </a>
                                <a href="/belanja?kategori=elektronik" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                                    <svg class="w-4 h-4 text-gray-500 group-hover/item:text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                                    </svg>
                                    <span>Elektronik</span>
                                </a>
                                <a href="/belanja?kategori=kecantikan" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                                    <svg class="w-4 h-4 text-gray-500 group-hover/item:text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                    </svg>
                                    <span>Kecantikan</span>
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <a href="/belanja" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                                    <svg class="w-4 h-4 text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/>
                                    </svg>
                                    <span>Lihat Semua Kategori</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Center-Right: Search Bar with Live Suggestions & Form Submission -->
            <div
                class="flex-1 max-w-[320px] md:max-w-[380px] lg:max-w-[440px] xl:max-w-[480px] relative hidden sm:block"
                x-data="{
                    query: '{{ request('q') ?? '' }}',
                    isOpen: false,
                    loading: false,
                    products: [],
                    sellers: [],
                    clearSearch() {
                        this.query = '';
                        this.isOpen = false;
                        const url = new URL(window.location.href);
                        if (url.searchParams.has('q')) {
                            url.searchParams.delete('q');
                            window.location.href = url.pathname + (url.searchParams.toString() ? '?' + url.searchParams.toString() : '');
                        } else {
                            if (this.$refs.desktopSearchInput) this.$refs.desktopSearchInput.focus();
                        }
                    },
                    async search(val) {
                        const q = (val !== undefined ? val : this.query).trim();
                        this.query = q;
                        if (q.length < 2) {
                            this.products = [];
                            this.sellers = [];
                            this.isOpen = false;
                            return;
                        }
                        this.loading = true;
                        try {
                            const res = await fetch('/api/search-suggest?q=' + encodeURIComponent(q));
                            const data = await res.json();
                            this.products = Array.isArray(data.products) ? data.products : [];
                            this.sellers = Array.isArray(data.sellers) ? data.sellers : [];
                            this.isOpen = (this.products.length > 0 || this.sellers.length > 0);
                        } catch (e) {
                            console.error(e);
                        } finally {
                            this.loading = false;
                        }
                    }
                }"
                @click.outside="isOpen = false"
                @keydown.escape="isOpen = false"
            >
                <form action="{{ route('shop') }}" method="GET" class="relative w-full">
                    <input
                        type="text"
                        name="q"
                        x-ref="desktopSearchInput"
                        x-model="query"
                        @input.debounce.300ms="search($event.target.value)"
                        @focus="if (query.trim().length >= 2) search(query)"
                        placeholder="Cari produk, kategori, atau apapun..."
                        autocomplete="off"
                        class="w-full h-11 sm:h-[44px] pl-10 pr-10 rounded-xl bg-[#F9FAFB] border border-gray-200 text-xs sm:text-[14px] text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] transition-all"
                    />
                    <button type="submit" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#4F26A6] transition-colors cursor-pointer" title="Cari">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                    </button>
                    <button
                        type="button"
                        x-show="query.length > 0"
                        x-cloak
                        @click="clearSearch()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full bg-white hover:bg-gray-100 active:bg-gray-200 text-gray-800 hover:text-black border border-gray-300 flex items-center justify-center transition-all cursor-pointer shadow-xs"
                        title="Hapus pencarian"
                    >
                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </form>

                <!-- Floating Live Search Dropdown Panel -->
                <div
                    x-show="isOpen"
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-1"
                    class="absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.18)] border border-gray-100 p-3 z-[100] text-left space-y-3"
                >
                    <!-- Products Matches -->
                    <template x-if="products.length > 0">
                        <div>
                            <span class="text-[10.5px] font-extrabold text-gray-400 tracking-wider uppercase px-2 block mb-1.5">
                                Produk Terkait
                            </span>
                            <div class="space-y-1">
                                <template x-for="prod in products" :key="prod.id">
                                    <a
                                        :href="prod.url"
                                        class="flex items-center gap-3 p-2 rounded-xl hover:bg-[#F3EEFF] transition-colors group/item"
                                    >
                                        <img
                                            :src="prod.image"
                                            :alt="prod.title"
                                            class="w-10 h-10 rounded-lg object-cover bg-gray-100 shrink-0 border border-gray-100"
                                        />
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs sm:text-[13px] font-bold text-gray-900 group-hover/item:text-[#4F26A6] truncate" x-text="prod.title"></p>
                                            <div class="flex items-center gap-1.5 text-[11px] text-gray-500">
                                                <span class="text-[#4F26A6] font-extrabold" x-text="prod.price_formatted"></span>
                                                <span>•</span>
                                                <span class="truncate" x-text="prod.seller_name"></span>
                                            </div>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Sellers Matches -->
                    <template x-if="sellers.length > 0">
                        <div class="pt-2 border-t border-gray-100">
                            <span class="text-[10.5px] font-extrabold text-gray-400 tracking-wider uppercase px-2 block mb-1.5">
                                Toko &amp; Kreator
                            </span>
                            <div class="space-y-1">
                                <template x-for="sel in sellers" :key="sel.id">
                                    <a
                                        :href="sel.url"
                                        class="flex items-center gap-2.5 p-2 rounded-xl hover:bg-[#F3EEFF] transition-colors group/item"
                                    >
                                        <img
                                            :src="sel.avatar"
                                            :alt="sel.name"
                                            class="w-8 h-8 rounded-full object-cover shrink-0 ring-1 ring-purple-100"
                                        />
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-gray-900 group-hover/item:text-[#4F26A6] truncate" x-text="sel.name"></p>
                                            <p class="text-[10.5px] text-gray-400" x-text="'@' + sel.username"></p>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- View All Results Footer -->
                    <div class="pt-2 border-t border-gray-100">
                        <a
                            :href="'/belanja?q=' + encodeURIComponent(query)"
                            class="flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs font-bold text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors"
                        >
                            <span>Lihat semua hasil untuk "<span x-text="query"></span>"</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right: Wishlist, Cart, Profile Menu / Auth -->
            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                @php
                    $currentUser = auth()->user() ?? (is_array($user) ? (object)$user : $user);
                @endphp

                <!-- Wishlist Icon (Desktop always; Mobile/Tablet only when GUEST) -->
                <a
                    href="{{ route('wishlist.index') }}"
                    class="{{ $currentUser ? 'hidden lg:flex' : 'flex' }} relative p-2 text-gray-700 hover:text-[#4F26A6] transition-colors rounded-xl hover:bg-gray-50"
                    title="Wishlist"
                >
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span class="absolute top-0.5 right-0.5 w-4 h-4 sm:w-[18px] sm:h-[18px] rounded-full bg-[#4F26A6] text-white text-[9.5px] sm:text-[10px] font-extrabold flex items-center justify-center shadow-xs">
                        {{ auth()->check() ? auth()->user()->wishlists()->count() : ($wishlistCount ?? 0) }}
                    </span>
                </a>

                <!-- Cart Icon (Desktop always; Mobile/Tablet only when GUEST) -->
                <a
                    href="{{ route('cart.index') }}"
                    class="{{ $currentUser ? 'hidden lg:flex' : 'flex' }} relative p-2 text-gray-700 hover:text-[#4F26A6] transition-colors rounded-xl hover:bg-gray-50"
                    title="Keranjang Belanja"
                >
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="9" cy="21" r="1" />
                        <circle cx="20" cy="21" r="1" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6" />
                    </svg>
                    <span class="absolute top-0.5 right-0.5 w-4 h-4 sm:w-[18px] sm:h-[18px] rounded-full bg-[#4F26A6] text-white text-[9.5px] sm:text-[10px] font-extrabold flex items-center justify-center shadow-xs">
                        {{ auth()->check() && auth()->user()->cart ? auth()->user()->cart->items()->count() : ($cartCount ?? 0) }}
                    </span>
                </a>

                <div class="hidden lg:block h-6 w-[1px] bg-gray-200 mx-1"></div>

                <!-- Profile Menu Dropdown / Guest buttons -->
                @if($currentUser)
                    @php
                        $currentUserName = is_object($currentUser) ? $currentUser->name : ($currentUser['name'] ?? 'User');
                        $currentUserAvatar = is_object($currentUser) ? ($currentUser->avatar ?? null) : ($currentUser['avatar'] ?? null);
                        $userInitial = strtoupper(substr($currentUserName, 0, 1));
                    @endphp
                    <div class="relative" @click.outside="isProfileOpen = false">
                        <button
                            type="button"
                            @click="isProfileOpen = !isProfileOpen"
                            class="flex items-center gap-1.5 sm:gap-2 pl-1 cursor-pointer group focus:outline-none py-1 px-1.5 sm:px-2 rounded-2xl hover:bg-gray-50 active:bg-gray-100 transition-all border border-transparent hover:border-gray-200/60"
                        >
                            @if(!empty($currentUserAvatar))
                                <img
                                    src="{{ $currentUserAvatar }}"
                                    alt="{{ $currentUserName }}"
                                    class="w-8 h-8 sm:w-9 sm:h-9 rounded-full object-cover ring-2 ring-purple-100 group-hover:ring-[#4F26A6]/30 transition-all shrink-0"
                                />
                            @else
                                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-[#F3EEFF] text-[#4F26A6] font-extrabold text-xs sm:text-sm flex items-center justify-center ring-2 ring-purple-100 group-hover:ring-[#4F26A6]/40 transition-all shrink-0">
                                    {{ $userInitial }}
                                </div>
                            @endif
                            <span class="text-xs sm:text-sm font-semibold text-gray-800 group-hover:text-[#4F26A6] transition-colors max-w-[85px] sm:max-w-[130px] truncate">
                                {{ $currentUserName }}
                            </span>
                            <svg
                                class="w-3.5 h-3.5 text-gray-400 group-hover:text-[#4F26A6] transition-transform duration-200"
                                :class="isProfileOpen ? 'rotate-180 text-[#4F26A6]' : ''"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown Content (Sorted & Styled like major e-commerce platforms) -->
                        <div
                            x-show="isProfileOpen"
                            x-cloak
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute right-0 top-full mt-2 w-64 sm:w-72 bg-white rounded-2xl sm:rounded-3xl shadow-[0_16px_40px_rgba(0,0,0,0.12)] border border-gray-100 p-2 sm:p-2.5 z-50 text-left space-y-0.5"
                        >
                            <!-- User Card Header -->
                            <a href="{{ route('profile.settings') }}" class="px-3 py-2.5 bg-[#FAF9FC] hover:bg-[#F3EEFF]/50 rounded-xl sm:rounded-2xl border border-gray-100/90 mb-1.5 flex items-center gap-3 transition-colors group/header block">
                                @if(!empty($currentUserAvatar))
                                    <img
                                        src="{{ $currentUserAvatar }}"
                                        alt="{{ $currentUserName }}"
                                        class="w-9 h-9 rounded-full object-cover ring-2 ring-purple-100 shrink-0"
                                    />
                                @else
                                    <div class="w-9 h-9 rounded-full bg-[#F3EEFF] text-[#4F26A6] font-black text-sm flex items-center justify-center ring-2 ring-purple-100 shrink-0">
                                        {{ $userInitial }}
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs sm:text-[13px] font-extrabold text-gray-950 group-hover/header:text-[#4F26A6] transition-colors truncate">{{ $currentUserName }}</p>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-[#F3EEFF] text-[#4F26A6]">
                                            {{ auth()->check() ? ucfirst(auth()->user()->role?->value ?? 'Buyer') : 'Buyer' }}
                                        </span>
                                        <span class="text-[10px] text-gray-400 font-medium group-hover/header:text-[#4F26A6]">Lihat Profil &rarr;</span>
                                    </div>
                                </div>
                            </a>

                            @php
                                $cartCountVal = auth()->check() && auth()->user()->cart ? auth()->user()->cart->items()->count() : ($cartCount ?? 0);
                                $wishCountVal = auth()->check() ? auth()->user()->wishlists()->count() : ($wishlistCount ?? 0);
                                $followedCountVal = auth()->check() ? auth()->user()->followedSellers()->count() : 0;
                            @endphp

                            @if(auth()->check() && auth()->user()->isSeller())
                                <!-- Seller Order: Toko Saya (1st), Akun & Pengaturan (2nd), Aktivitas Belanja (3rd) -->
                                <!-- Section 1: Toko Saya -->
                                <div class="pt-0.5 pb-0.5">
                                    <span class="px-3 pt-1 pb-0.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-400 block">Toko Saya</span>
                                </div>
                                <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group">
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#4F26A6] transition-colors shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/>
                                    </svg>
                                    <span>Dashboard Toko</span>
                                </a>
                                <a href="{{ route('seller.settings') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group">
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#4F26A6] transition-colors shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Pengaturan Toko &amp; Banner</span>
                                </a>

                                <!-- Section 2: Akun & Pengaturan -->
                                <div class="border-t border-gray-100 my-1 pt-1">
                                    <span class="px-3 pt-1 pb-0.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-400 block">Akun &amp; Pengaturan</span>
                                </div>
                                <a href="{{ route('profile.settings') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group">
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#4F26A6] transition-colors shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>Pengaturan Profil &amp; Alamat</span>
                                </a>
                                <a href="{{ route('password.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group">
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#4F26A6] transition-colors shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 11V7a5 5 0 0110 0v4"/>
                                    </svg>
                                    <span>Ganti Password</span>
                                </a>

                                <!-- Section 3: Aktivitas Belanja -->
                                <div class="border-t border-gray-100 my-1 pt-1">
                                    <span class="px-3 pt-1 pb-0.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-400 block">Aktivitas Belanja</span>
                                </div>
                                <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group">
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#4F26A6] transition-colors shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <span>Pesanan Saya</span>
                                </a>
                                <a href="{{ route('wishlist.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-[#4F26A6] transition-colors shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                        <span>Wishlist</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full {{ $wishCountVal > 0 ? 'bg-[#4F26A6] text-white' : 'bg-gray-100 text-gray-400' }} text-[10.5px] font-extrabold">
                                        {{ $wishCountVal }}
                                    </span>
                                </a>
                                <a href="{{ route('followed-sellers.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-[#4F26A6] transition-colors shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span>Toko yang Diikuti</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full {{ $followedCountVal > 0 ? 'bg-[#4F26A6] text-white' : 'bg-gray-100 text-gray-400' }} text-[10.5px] font-extrabold">
                                        {{ $followedCountVal }}
                                    </span>
                                </a>
                                <a href="{{ route('cart.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-[#4F26A6] transition-colors shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
                                        </svg>
                                        <span>Keranjang Belanja</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full {{ $cartCountVal > 0 ? 'bg-[#4F26A6] text-white' : 'bg-gray-100 text-gray-400' }} text-[10.5px] font-extrabold">
                                        {{ $cartCountVal }}
                                    </span>
                                </a>
                            @else
                                <!-- Buyer Order: Aktivitas Belanja (1st), Akun & Pengaturan (2nd) -->
                                <!-- Section 1: Aktivitas Belanja -->
                                <div class="pt-0.5 pb-0.5">
                                    <span class="px-3 pt-1 pb-0.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-400 block">Aktivitas Belanja</span>
                                </div>
                                <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group">
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#4F26A6] transition-colors shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <span>Pesanan Saya</span>
                                </a>
                                <a href="{{ route('wishlist.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-[#4F26A6] transition-colors shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                        <span>Wishlist</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full {{ $wishCountVal > 0 ? 'bg-[#4F26A6] text-white' : 'bg-gray-100 text-gray-400' }} text-[10.5px] font-extrabold">
                                        {{ $wishCountVal }}
                                    </span>
                                </a>
                                <a href="{{ route('followed-sellers.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-[#4F26A6] transition-colors shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span>Toko yang Diikuti</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full {{ $followedCountVal > 0 ? 'bg-[#4F26A6] text-white' : 'bg-gray-100 text-gray-400' }} text-[10.5px] font-extrabold">
                                        {{ $followedCountVal }}
                                    </span>
                                </a>
                                <a href="{{ route('cart.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-[#4F26A6] transition-colors shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
                                        </svg>
                                        <span>Keranjang Belanja</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full {{ $cartCountVal > 0 ? 'bg-[#4F26A6] text-white' : 'bg-gray-100 text-gray-400' }} text-[10.5px] font-extrabold">
                                        {{ $cartCountVal }}
                                    </span>
                                </a>

                                <!-- Section 2: Akun & Pengaturan -->
                                <div class="border-t border-gray-100 my-1 pt-1">
                                    <span class="px-3 pt-1 pb-0.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-400 block">Akun &amp; Pengaturan</span>
                                </div>
                                <a href="{{ route('profile.settings') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group">
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#4F26A6] transition-colors shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>Pengaturan Profil &amp; Alamat</span>
                                </a>
                                <a href="{{ route('password.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group">
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#4F26A6] transition-colors shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 11V7a5 5 0 0110 0v4"/>
                                    </svg>
                                    <span>Ganti Password</span>
                                </a>

                                <!-- Section: Aktivasi Toko Seller (Khusus Buyer) -->
                                <div class="border-t border-gray-100 my-1.5 pt-1.5">
                                    <a
                                        href="{{ route('seller.register') }}"
                                        class="flex items-center justify-between p-2.5 rounded-xl sm:rounded-2xl bg-gradient-to-r from-[#F3EEFF] to-[#FAF9FC] hover:from-[#EAE1FF] hover:to-[#F3EEFF] border border-[#4F26A6]/20 transition-all group/seller shadow-2xs"
                                        title="Aktivasi Toko Seller WhiMarket"
                                    >
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <div class="w-8 h-8 rounded-xl bg-[#4F26A6] text-white flex items-center justify-center shrink-0 shadow-2xs group-hover/seller:scale-105 transition-transform">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs sm:text-[13px] font-extrabold text-gray-900 group-hover/seller:text-[#4F26A6] transition-colors leading-tight truncate">
                                                    Aktivasi Toko Seller
                                                </p>
                                                <p class="text-[10px] sm:text-[10.5px] text-gray-500 truncate">
                                                    Buka toko &amp; mulai jualan
                                                </p>
                                            </div>
                                        </div>
                                        <svg class="w-4 h-4 text-[#4F26A6] shrink-0 group-hover/seller:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            @endif

                            <!-- Section 4: Administrator (khusus admin) -->
                            @if(auth()->check() && auth()->user()->isAdmin())
                                <div class="border-t border-gray-100 my-1 pt-1"></div>
                                <a href="/admin" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-amber-800 hover:bg-amber-50 transition-colors group">
                                    <svg class="w-4 h-4 text-amber-700 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    <span>Admin Panel</span>
                                </a>
                            @endif

                            <!-- Section 5: Logout -->
                            <div class="border-t border-gray-100 my-1 pt-1"></div>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-red-600 hover:bg-red-50 transition-colors cursor-pointer text-left group">
                                    <svg class="w-4 h-4 text-red-500 group-hover:text-red-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Guest: Desktop Masuk & Daftar -->
                    <div class="hidden lg:flex items-center gap-2.5">
                        <a href="{{ route('login') }}" class="px-5 h-[44px] flex items-center justify-center rounded-xl text-[15px] font-semibold text-[#4F26A6] border-[1.5px] border-[#4F26A6] hover:bg-[#4F26A6]/5 transition-all">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-6 h-[44px] flex items-center justify-center rounded-xl text-[15px] font-semibold text-white bg-[#4F26A6] hover:bg-[#3E1D85] shadow-xs transition-all">
                            Daftar
                        </a>
                    </div>
                @endif

                <!-- Mobile Hamburger Button (Only shown for GUEST) -->
                @if(!$currentUser)
                    <button
                        type="button"
                        @click="isMobileMenuOpen = true"
                        class="lg:hidden p-2 rounded-xl text-gray-700 hover:bg-gray-100 hover:text-[#4F26A6] transition-colors focus:outline-none ml-1 shrink-0"
                        aria-label="Toggle Menu"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    </header>

    <!-- ==================== MOBILE DRAWER & SIDEBAR (SLIDE OVER FROM RIGHT 1:1) ==================== -->
    <div
        id="sidebar-overlay"
        x-show="isMobileMenuOpen"
        x-transition:enter="transition-opacity duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="isMobileMenuOpen = false"
        class="fixed inset-0 bg-black/40 backdrop-blur-xs z-50 lg:hidden"
        style="display: none;"
    ></div>

    <aside
        id="mobile-sidebar"
        x-show="isMobileMenuOpen"
        x-transition:enter="transition-transform duration-300 ease-in-out"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform duration-300 ease-in-out"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed top-0 right-0 bottom-0 w-[300px] sm:w-[340px] bg-white z-50 shadow-2xl flex flex-col justify-between p-6 lg:hidden"
        style="display: none;"
    >
        <div class="flex flex-col">
            <!-- Sidebar Top Header -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <img src="/assets/logo-whimarket.png" alt="WhiMarket" class="h-8 w-auto object-contain mix-blend-multiply"/>
                <button
                    type="button"
                    @click="isMobileMenuOpen = false"
                    class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center transition-colors focus:outline-none"
                    aria-label="Close Menu"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            @if(($activeTab ?? '') !== 'belanja')
                <!-- Mobile Search Bar (Only shown on non-belanja pages) -->
                <form
                    action="{{ route('shop') }}"
                    method="GET"
                    class="mt-4 relative sm:hidden"
                    x-data="{
                        mQuery: '{{ request('q') ?? '' }}',
                        clearMobileSearch() {
                            this.mQuery = '';
                            const url = new URL(window.location.href);
                            if (url.searchParams.has('q')) {
                                url.searchParams.delete('q');
                                window.location.href = url.pathname + (url.searchParams.toString() ? '?' + url.searchParams.toString() : '');
                            } else {
                                if (this.$refs.mobileSearchInput) this.$refs.mobileSearchInput.focus();
                            }
                        }
                    }"
                >
                    <input
                        type="text"
                        name="q"
                        x-ref="mobileSearchInput"
                        x-model="mQuery"
                        placeholder="Cari produk, kategori, atau toko..."
                        class="w-full h-10 pl-9 pr-9 rounded-xl bg-[#F9FAFB] border border-gray-200 text-xs text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] transition-all"
                    />
                    <button type="submit" class="text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 hover:text-[#4F26A6] transition-colors cursor-pointer" title="Cari">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                    </button>
                    <button
                        type="button"
                        x-show="mQuery.length > 0"
                        x-cloak
                        @click="clearMobileSearch()"
                        class="absolute right-2.5 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full bg-white hover:bg-gray-100 active:bg-gray-200 text-gray-800 hover:text-black border border-gray-300 flex items-center justify-center transition-all cursor-pointer shadow-xs"
                        title="Hapus pencarian"
                    >
                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </form>
            @endif

            <!-- Navigation Links -->
            <nav class="flex flex-col mt-3 divide-y divide-gray-100 text-[15px] sm:text-[16px] font-bold text-gray-800">
                <a href="/" class="py-3.5 text-[#4F26A6] flex items-center justify-between">
                    <span>Beranda</span>
                    <span class="w-2 h-2 rounded-full bg-[#4F26A6]"></span>
                </a>

                <!-- Belanja Accordion -->
                <div class="py-3 flex flex-col">
                    <div
                        class="flex items-center justify-between text-gray-800 cursor-pointer"
                        @click="isBelanjaOpen = !isBelanjaOpen"
                    >
                        <span>Belanja</span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="isBelanjaOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                    <div x-show="isBelanjaOpen" class="flex flex-col pl-2 pt-2 space-y-1 text-xs font-semibold text-gray-700">
                        <a href="/belanja" class="flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors">
                            <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></svg>
                            <span>Semua Produk</span>
                        </a>
                        <a href="/#barang-terbaru" class="flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors">
                            <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            <span>Produk Terbaru</span>
                        </a>
                        <a href="/#seller-populer" class="flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors">
                            <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                            <span>Produk Populer</span>
                        </a>
                        <a href="/#promo-spesial" class="flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors">
                            <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            <span>Promo Spesial</span>
                        </a>
                    </div>
                </div>

                <!-- Kategori Accordion -->
                <div class="py-3 flex flex-col">
                    <div
                        class="flex items-center justify-between text-gray-800 cursor-pointer"
                        @click="isKategoriOpen = !isKategoriOpen"
                    >
                        <span>Kategori</span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="isKategoriOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                    <div x-show="isKategoriOpen" class="flex flex-col pl-2 pt-2 space-y-1 text-xs font-semibold text-gray-700">
                        <a href="/belanja?kategori=fashion" class="flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors">
                            <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 3l-4 2-4-2L3 6v4l3 1v10h12V11l3-1V6l-5-3z"/></svg>
                            <span>Fashion</span>
                        </a>
                        <a href="/belanja?kategori=tas" class="flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors">
                            <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span>Tas &amp; Aksesoris</span>
                        </a>
                        <a href="/belanja?kategori=hobi" class="flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors">
                            <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <span>Hobi &amp; Koleksi</span>
                        </a>
                        <a href="/belanja?kategori=merchandise" class="flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors">
                            <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                            <span>Merchandise</span>
                        </a>
                        <a href="/belanja?kategori=elektronik" class="flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors">
                            <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                            <span>Elektronik</span>
                        </a>
                        <a href="/belanja?kategori=kecantikan" class="flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors">
                            <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                            <span>Kecantikan</span>
                        </a>
                        <a href="/belanja" class="flex items-center gap-2.5 py-2 px-2.5 rounded-lg text-[#4F26A6] font-bold hover:bg-[#F3EEFF] transition-colors pt-2 border-t border-gray-100">
                            <svg class="w-4 h-4 text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/></svg>
                            <span>Lihat Semua Kategori</span>
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <div class="pt-4 border-t border-gray-200 flex flex-col gap-2.5">
            @if(auth()->check())
                <div class="px-2 py-1.5 bg-[#F3EEFF] rounded-xl text-xs font-bold text-[#4F26A6]">
                    Login: {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role?->value ?? 'Buyer') }})
                </div>
                @if(auth()->user()->isSeller())
                    <a href="{{ route('seller.dashboard') }}" class="w-full py-2.5 rounded-xl text-center font-bold text-white bg-[#4F26A6] hover:bg-[#3E1D85] transition-all text-xs">
                        Dashboard Seller
                    </a>
                    <a href="{{ route('seller.settings') }}" class="w-full py-2.5 rounded-xl text-center font-bold text-[#4F26A6] bg-[#F3EEFF] hover:bg-[#EADDFE] transition-all text-xs">
                        Pengaturan Toko &amp; Banner
                    </a>
                @else
                    <a href="{{ route('seller.register') }}" class="w-full py-2.5 rounded-xl text-center font-bold text-white bg-[#4F26A6] hover:bg-[#3E1D85] shadow-sm transition-all text-xs flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>Aktivasi Toko Seller WhiMarket</span>
                    </a>
                @endif
                @if(auth()->user()->isAdmin())
                    <a href="/admin" class="w-full py-2.5 rounded-xl text-center font-bold text-white bg-amber-600 hover:bg-amber-700 transition-all text-xs">
                        Admin Panel
                    </a>
                @endif
                <a href="{{ route('orders.index') }}" class="w-full py-2.5 rounded-xl text-center font-bold text-gray-700 border border-gray-200 hover:bg-gray-50 transition-all text-xs">
                    Pesanan Saya
                </a>
                <a href="{{ route('followed-sellers.index') }}" class="w-full py-2.5 rounded-xl text-center font-bold text-gray-700 border border-gray-200 hover:bg-gray-50 transition-all text-xs">
                    Toko yang Diikuti
                </a>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full py-2.5 rounded-xl text-center font-bold text-red-600 bg-red-50 hover:bg-red-100 transition-all text-xs cursor-pointer">
                        Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('register') }}" class="w-full py-3 rounded-xl text-center font-bold text-white bg-[#4F26A6] hover:bg-[#3E1D85] shadow-md shadow-[#4F26A6]/20 transition-all text-sm">
                    Daftar Sekarang
                </a>
                <a href="{{ route('login') }}" class="w-full py-3 rounded-xl text-center font-bold text-[#4F26A6] border-2 border-[#4F26A6] hover:bg-[#4F26A6]/5 transition-all text-sm">
                    Masuk ke Akun
                </a>
            @endif
        </div>
    </aside>
</div>
