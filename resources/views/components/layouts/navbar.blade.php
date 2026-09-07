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

            <!-- Center-Right: Search Bar -->
            <div class="flex-1 max-w-[320px] md:max-w-[380px] lg:max-w-[440px] xl:max-w-[480px] relative hidden sm:block">
                <div class="relative w-full">
                    <input
                        type="text"
                        placeholder="Cari produk, kategori, atau apapun..."
                        class="w-full h-11 sm:h-[44px] pl-10 pr-4 rounded-xl bg-[#F9FAFB] border border-gray-200 text-xs sm:text-[14px] text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] transition-all"
                    />
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                </div>
            </div>

            <!-- Right: Wishlist, Cart, Profile Menu / Auth -->
            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                <!-- Wishlist Icon -->
                <a href="#wishlist" class="relative p-2 text-gray-700 hover:text-[#4F26A6] transition-colors rounded-xl hover:bg-gray-50" title="Favorit">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span class="absolute top-0.5 right-0.5 w-4 h-4 sm:w-[18px] sm:h-[18px] rounded-full bg-[#4F26A6] text-white text-[9.5px] sm:text-[10px] font-extrabold flex items-center justify-center shadow-xs">
                        {{ $wishlistCount }}
                    </span>
                </a>

                <!-- Cart Icon -->
                <a href="#keranjang" class="relative p-2 text-gray-700 hover:text-[#4F26A6] transition-colors rounded-xl hover:bg-gray-50" title="Keranjang">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="9" cy="21" r="1" />
                        <circle cx="20" cy="21" r="1" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6" />
                    </svg>
                    <span class="absolute top-0.5 right-0.5 w-4 h-4 sm:w-[18px] sm:h-[18px] rounded-full bg-[#4F26A6] text-white text-[9.5px] sm:text-[10px] font-extrabold flex items-center justify-center shadow-xs">
                        {{ $cartCount }}
                    </span>
                </a>

                <div class="hidden lg:block h-6 w-[1px] bg-gray-200 mx-1"></div>

                <!-- Profile Menu Dropdown / Guest buttons -->
                @if($user)
                    <div class="relative" @click.outside="isProfileOpen = false">
                        <button
                            type="button"
                            @click="isProfileOpen = !isProfileOpen"
                            class="hidden lg:flex items-center gap-2.5 pl-1 cursor-pointer group focus:outline-none"
                        >
                            <img
                                src="{{ $user['avatar'] }}"
                                alt="{{ $user['name'] }}"
                                class="w-9 h-9 rounded-full object-cover ring-2 ring-purple-100 group-hover:ring-[#4F26A6]/30 transition-all shrink-0"
                            />
                            <span class="text-sm font-semibold text-gray-800 group-hover:text-[#4F26A6] transition-colors">
                                {{ $user['name'] }}
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

                        <!-- Dropdown Content -->
                        <div
                            x-show="isProfileOpen"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute right-0 top-full mt-2.5 w-64 bg-white rounded-2xl shadow-[0_16px_40px_rgba(0,0,0,0.12)] border border-gray-100 p-3 z-50 text-left space-y-1"
                            style="display: none;"
                        >
                            <div class="px-3.5 py-2.5 border-b border-gray-100 mb-1.5">
                                <p class="text-[11px] text-gray-400 font-medium">Masuk sebagai</p>
                                <p class="text-sm font-extrabold text-gray-900 truncate mt-0.5">{{ $user['name'] }}</p>
                            </div>

                            <a href="#profil" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-[13.5px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Profil Saya</span>
                            </a>
                            <a href="#pesanan" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-[13.5px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <span>Pesanan Saya</span>
                            </a>
                            <a href="#wishlist" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-[13.5px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <span>Wishlist</span>
                            </a>
                            <div class="h-[1px] bg-gray-100 my-1.5"></div>
                            <a href="#keluar" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-[13.5px] font-semibold text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Keluar</span>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="hidden lg:flex items-center gap-2.5">
                        <a href="#masuk" class="px-5 h-[44px] flex items-center justify-center rounded-xl text-[15px] font-semibold text-[#4F26A6] border-[1.5px] border-[#4F26A6] hover:bg-[#4F26A6]/5 transition-all">
                            Masuk
                        </a>
                        <a href="#daftar" class="px-6 h-[44px] flex items-center justify-center rounded-xl text-[15px] font-semibold text-white bg-[#4F26A6] hover:bg-[#3E1D85] shadow-xs transition-all">
                            Daftar
                        </a>
                    </div>
                @endif

                <!-- Mobile Hamburger Button -->
                <button
                    type="button"
                    @click="isMobileMenuOpen = true"
                    class="lg:hidden p-2 rounded-xl text-gray-700 hover:bg-gray-100 hover:text-[#4F26A6] transition-colors focus:outline-none ml-1"
                    aria-label="Toggle Menu"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
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

            <!-- Mobile Search Bar -->
            <div class="mt-4 relative sm:hidden">
                <input
                    type="text"
                    placeholder="Cari produk, kategori..."
                    class="w-full h-10 pl-9 pr-3 rounded-xl bg-[#F9FAFB] border border-gray-200 text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:border-[#4F26A6]"
                />
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
            </div>

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
            <a href="#daftar" class="w-full py-3 rounded-xl text-center font-bold text-white bg-[#4F26A6] hover:bg-[#3E1D85] shadow-md shadow-[#4F26A6]/20 transition-all text-sm">
                Daftar Sekarang
            </a>
            <a href="#masuk" class="w-full py-3 rounded-xl text-center font-bold text-[#4F26A6] border-2 border-[#4F26A6] hover:bg-[#4F26A6]/5 transition-all text-sm">
                Masuk ke Akun
            </a>
        </div>
    </aside>
</div>
