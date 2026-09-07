<x-layouts.app :title="'Dukung Kreator Favoritmu - WhiMarket'" activeTab="seller">
    <main class="w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-6 sm:py-8" x-data="{
        selectedCategory: 'all',
        selectedRating: null,
        sortBy: 'terbaru',
        categoryOpen: true,
        ratingOpen: true,
        filterDrawerOpen: false,
        seller: {
            id: 'sel_rachel',
            name: 'Rachel Vennya',
            handle: '@rachel_venya',
            role: 'Selebgram',
            category: 'selebgram',
            verified: true,
            avatar: '/assets/avatar-rachel-exact.png',
            cardBg: '/assets/seller-card-cover-rachel.png',
            rating: 4.9,
            reviewCount: '1.2rb',
            itemCount: 112,
            followerCount: '12.4rb',
            profileUrl: '/seller/@rachel_venya'
        },
        matchesFilter() {
            if (this.selectedCategory !== 'all' && this.seller.category !== this.selectedCategory) {
                return false;
            }
            if (this.selectedRating && Math.floor(this.seller.rating) < this.selectedRating) {
                return false;
            }
            return true;
        }
    }">
        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs sm:text-[13px] text-gray-500 font-medium mb-5">
            <a href="/" class="hover:text-[#4F26A6] transition-colors">Beranda</a>
            <span class="text-gray-300 font-normal">&gt;</span>
            <span class="text-gray-900 font-bold">Seller</span>
        </nav>

        <!-- Hero Purple Banner (Pixel-Perfect from Mockup) -->
        <div class="relative w-full rounded-2xl sm:rounded-3xl overflow-hidden bg-[#4B16CF] shadow-lg shadow-[#4B16CF]/15 mb-8 sm:mb-10 flex flex-col md:flex-row items-center justify-between min-h-[175px] sm:min-h-[185px] lg:h-[205px] xl:h-[215px]">
            <!-- Vector Curves Background from Shop Banner -->
            <div class="absolute right-0 top-0 bottom-0 h-full w-full pointer-events-none z-0 flex justify-end overflow-hidden">
                <img
                    src="/assets/banner-vector-bg.png"
                    alt=""
                    decoding="async"
                    class="h-full w-auto object-cover object-right opacity-90"
                />
            </div>

            <!-- Left Banner Copy -->
            <div class="relative z-10 w-full md:w-[50%] lg:w-[48%] p-6 sm:p-8 lg:pl-12 xl:pl-16 py-6 sm:py-7 flex flex-col justify-center text-left">
                <h1 class="text-2xl sm:text-3xl lg:text-[36px] xl:text-[38px] font-black tracking-tight leading-[1.15] text-white">
                    <span>Dukung Kreator</span><br />
                    <span class="text-[#F5BA47]">Favoritmu</span>
                </h1>
                <p class="text-white/85 text-xs sm:text-[13.5px] lg:text-[14px] mt-2 sm:mt-2.5 leading-relaxed max-w-[460px] font-normal">
                    Temukan barang pre-loved langsung dari artis, selebgram, streamer, dan figur publik favoritmu.
                </p>
            </div>

            <!-- Right Banner Illustration with Cutout Asset -->
            <div class="relative z-10 w-full md:w-[50%] lg:w-[52%] h-[180px] sm:h-[195px] lg:h-full flex items-end justify-center md:justify-end overflow-visible pr-0 sm:pr-4 lg:pr-6">
                <img
                    src="/assets/banner.png"
                    alt="Dukung Kreator Favoritmu"
                    decoding="async"
                    class="h-full w-auto max-h-[220px] object-contain object-bottom select-none pointer-events-none"
                />
            </div>
        </div>

        <!-- Main Content Area: Sidebar Filters & Seller Cards Grid -->
        <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 items-start">
            <!-- Left Sidebar Filter (Desktop) -->
            <aside class="hidden lg:block w-64 xl:w-72 shrink-0 space-y-5">
                <!-- Filter Section 1: Kategori Seller -->
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-[0_2px_12px_rgba(0,0,0,0.02)]">
                    <button
                        type="button"
                        @click="categoryOpen = !categoryOpen"
                        class="w-full flex items-center justify-between text-left text-[15px] font-extrabold text-[#111827] focus:outline-none"
                    >
                        <span>Kategori Seller</span>
                        <svg
                            class="w-4 h-4 text-gray-400 transform transition-transform"
                            :class="categoryOpen ? 'rotate-180' : ''"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="categoryOpen" x-transition class="mt-4 space-y-1">
                        <!-- All Categories -->
                        <button
                            type="button"
                            @click="selectedCategory = 'all'"
                            :class="selectedCategory === 'all' ? 'bg-[#F3EEFF] text-[#481EBC] font-bold' : 'text-gray-700 hover:bg-gray-50 font-medium'"
                            class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-[13.5px] transition-colors text-left cursor-pointer"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Semua Seller</span>
                        </button>

                        <!-- Artis -->
                        <button
                            type="button"
                            @click="selectedCategory = 'artis'"
                            :class="selectedCategory === 'artis' ? 'bg-[#F3EEFF] text-[#481EBC] font-bold' : 'text-gray-700 hover:bg-gray-50 font-medium'"
                            class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-[13.5px] transition-colors text-left cursor-pointer"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Artis</span>
                        </button>

                        <!-- Selebgram -->
                        <button
                            type="button"
                            @click="selectedCategory = 'selebgram'"
                            :class="selectedCategory === 'selebgram' ? 'bg-[#F3EEFF] text-[#481EBC] font-bold' : 'text-gray-700 hover:bg-gray-50 font-medium'"
                            class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-[13.5px] transition-colors text-left cursor-pointer"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" />
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
                            </svg>
                            <span>Selebgram</span>
                        </button>

                        <!-- Streamer -->
                        <button
                            type="button"
                            @click="selectedCategory = 'streamer'"
                            :class="selectedCategory === 'streamer' ? 'bg-[#F3EEFF] text-[#481EBC] font-bold' : 'text-gray-700 hover:bg-gray-50 font-medium'"
                            class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-[13.5px] transition-colors text-left cursor-pointer"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="2" y="7" width="20" height="15" rx="2" ry="2" />
                                <polyline points="17 2 12 7 7 2" />
                            </svg>
                            <span>Streamer</span>
                        </button>

                        <!-- Content Creator -->
                        <button
                            type="button"
                            @click="selectedCategory = 'creator'"
                            :class="selectedCategory === 'creator' ? 'bg-[#F3EEFF] text-[#481EBC] font-bold' : 'text-gray-700 hover:bg-gray-50 font-medium'"
                            class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-[13.5px] transition-colors text-left cursor-pointer"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span>Content Creator</span>
                        </button>
                    </div>
                </div>

                <!-- Filter Section 2: Rating Seller -->
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-[0_2px_12px_rgba(0,0,0,0.02)]">
                    <button
                        type="button"
                        @click="ratingOpen = !ratingOpen"
                        class="w-full flex items-center justify-between text-left text-[15px] font-extrabold text-[#111827] focus:outline-none"
                    >
                        <span>Rating Seller</span>
                        <svg
                            class="w-4 h-4 text-gray-400 transform transition-transform"
                            :class="ratingOpen ? 'rotate-180' : ''"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="ratingOpen" x-transition class="mt-4 space-y-2.5">
                        <!-- Rating 5 -->
                        <label class="flex items-center justify-between text-xs sm:text-[13px] text-gray-700 cursor-pointer group">
                            <div class="flex items-center gap-2.5">
                                <input
                                    type="checkbox"
                                    :checked="selectedRating === 5"
                                    @change="selectedRating = selectedRating === 5 ? null : 5"
                                    class="w-4 h-4 rounded-md border-gray-300 text-[#481EBC] focus:ring-[#481EBC]"
                                />
                                <span class="font-bold text-gray-800">5</span>
                                <div class="flex items-center gap-0.5 text-[#F59E0B]">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    @endfor
                                </div>
                            </div>
                            <span class="text-xs text-gray-400 font-medium">(42)</span>
                        </label>

                        <!-- Rating 4 -->
                        <label class="flex items-center justify-between text-xs sm:text-[13px] text-gray-700 cursor-pointer group">
                            <div class="flex items-center gap-2.5">
                                <input
                                    type="checkbox"
                                    :checked="selectedRating === 4"
                                    @change="selectedRating = selectedRating === 4 ? null : 4"
                                    class="w-4 h-4 rounded-md border-gray-300 text-[#481EBC] focus:ring-[#481EBC]"
                                />
                                <span class="font-bold text-gray-800">4</span>
                                <div class="flex items-center gap-0.5 text-[#F59E0B]">
                                    @for($i = 0; $i < 4; $i++)
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    @endfor
                                    <svg class="w-3.5 h-3.5 text-gray-200 fill-current" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                </div>
                            </div>
                            <span class="text-xs text-gray-400 font-medium">(58)</span>
                        </label>

                        <!-- Rating 3 -->
                        <label class="flex items-center justify-between text-xs sm:text-[13px] text-gray-700 cursor-pointer group">
                            <div class="flex items-center gap-2.5">
                                <input
                                    type="checkbox"
                                    :checked="selectedRating === 3"
                                    @change="selectedRating = selectedRating === 3 ? null : 3"
                                    class="w-4 h-4 rounded-md border-gray-300 text-[#481EBC] focus:ring-[#481EBC]"
                                />
                                <span class="font-bold text-gray-800">3</span>
                                <div class="flex items-center gap-0.5 text-[#F59E0B]">
                                    @for($i = 0; $i < 3; $i++)
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    @endfor
                                    @for($i = 0; $i < 2; $i++)
                                        <svg class="w-3.5 h-3.5 text-gray-200 fill-current" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    @endfor
                                </div>
                            </div>
                            <span class="text-xs text-gray-400 font-medium">(18)</span>
                        </label>

                        <!-- Rating 2 -->
                        <label class="flex items-center justify-between text-xs sm:text-[13px] text-gray-700 cursor-pointer group">
                            <div class="flex items-center gap-2.5">
                                <input
                                    type="checkbox"
                                    :checked="selectedRating === 2"
                                    @change="selectedRating = selectedRating === 2 ? null : 2"
                                    class="w-4 h-4 rounded-md border-gray-300 text-[#481EBC] focus:ring-[#481EBC]"
                                />
                                <span class="font-bold text-gray-800">2</span>
                                <div class="flex items-center gap-0.5 text-[#F59E0B]">
                                    @for($i = 0; $i < 2; $i++)
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    @endfor
                                    @for($i = 0; $i < 3; $i++)
                                        <svg class="w-3.5 h-3.5 text-gray-200 fill-current" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    @endfor
                                </div>
                            </div>
                            <span class="text-xs text-gray-400 font-medium">(6)</span>
                        </label>

                        <!-- Rating 1 -->
                        <label class="flex items-center justify-between text-xs sm:text-[13px] text-gray-700 cursor-pointer group">
                            <div class="flex items-center gap-2.5">
                                <input
                                    type="checkbox"
                                    :checked="selectedRating === 1"
                                    @change="selectedRating = selectedRating === 1 ? null : 1"
                                    class="w-4 h-4 rounded-md border-gray-300 text-[#481EBC] focus:ring-[#481EBC]"
                                />
                                <span class="font-bold text-gray-800">1</span>
                                <div class="flex items-center gap-0.5 text-[#F59E0B]">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    @for($i = 0; $i < 4; $i++)
                                        <svg class="w-3.5 h-3.5 text-gray-200 fill-current" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    @endfor
                                </div>
                            </div>
                            <span class="text-xs text-gray-400 font-medium">(4)</span>
                        </label>
                    </div>
                </div>
            </aside>

            <!-- Right Content: Header Row & Seller Card Grid -->
            <section class="flex-1 w-full">
                <!-- Header Title & Sort Selector -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl sm:text-2xl font-black text-[#111827] tracking-tight">
                                Semua Seller
                            </h2>
                            <span class="text-xs text-gray-400 font-medium sm:hidden">1 seller ditemukan</span>
                        </div>
                        <p class="text-xs sm:text-[13.5px] text-gray-500 mt-1 font-normal">
                            Temukan dan dukung kreator favoritmu di Whimarket.
                        </p>
                    </div>

                    <div class="flex items-center justify-between sm:justify-end gap-3 w-full sm:w-auto">
                        <span class="hidden sm:inline text-xs text-gray-400 font-medium whitespace-nowrap">128 seller ditemukan</span>
                        <div class="relative inline-block w-full sm:w-auto">
                            <select
                                x-model="sortBy"
                                class="w-full sm:w-auto appearance-none bg-white border border-gray-200 text-xs sm:text-[13px] font-semibold text-gray-700 rounded-xl px-4 py-2.5 pr-8 focus:outline-none focus:ring-2 focus:ring-[#481EBC]/20 focus:border-[#481EBC] shadow-2xs cursor-pointer"
                            >
                                <option value="terbaru">Urutkan: Terbaru</option>
                                <option value="terpopuler">Urutkan: Terpopuler</option>
                                <option value="rating">Urutkan: Rating Tertinggi</option>
                                <option value="produk">Urutkan: Produk Terbanyak</option>
                            </select>
                            <svg class="w-3.5 h-3.5 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Seller Card (Mockup Parity: 1 Seller Dummy @rachel_venya) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6">
                    <template x-if="matchesFilter()">
                        <div class="bg-white rounded-2xl sm:rounded-3xl border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_32px_rgba(72,30,188,0.08)] transition-all flex flex-col justify-between group overflow-hidden">
                            <!-- Top Banner Section -->
                            <div class="relative w-full h-[142px] sm:h-[148px] bg-[#E8DEFD] overflow-hidden">
                                <img
                                    :src="seller.cardBg"
                                    :alt="seller.name"
                                    decoding="async"
                                    class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                                />
                            </div>

                            <!-- Circular Avatar Overlapping Banner and White Card Body -->
                            <div class="relative -mt-9 ml-6 z-20 w-[72px] h-[72px] sm:w-[76px] sm:h-[76px] rounded-full p-0.5 bg-white ring-4 ring-white shadow-md overflow-hidden shrink-0">
                                <img
                                    :src="seller.avatar"
                                    :alt="seller.name"
                                    decoding="async"
                                    class="w-full h-full object-cover rounded-full"
                                />
                            </div>

                            <!-- Card Body Information -->
                            <div class="px-6 pt-3 pb-6 flex-1 flex flex-col justify-between">
                                <div>
                                    <!-- Name & Verified Badge -->
                                    <div class="flex items-center gap-1.5 mb-0.5">
                                        <h3 class="text-base sm:text-[18px] font-extrabold text-[#111827] tracking-tight hover:text-[#481EBC] transition-colors">
                                            <a :href="seller.profileUrl" x-text="seller.name"></a>
                                        </h3>
                                        <template x-if="seller.verified">
                                            <x-verified-badge size="sm" class="w-4 h-4 shrink-0" />
                                        </template>
                                    </div>

                                    <!-- Role -->
                                    <p class="text-xs text-gray-400 font-medium mb-3.5" x-text="seller.role"></p>

                                    <!-- Rating Stars & Review Count -->
                                    <div class="flex items-center gap-1.5 text-xs text-gray-500 mb-4">
                                        <div class="flex items-center gap-0.5 text-[#F59E0B]">
                                            @for($i = 0; $i < 5; $i++)
                                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                            @endfor
                                        </div>
                                        <span class="font-bold text-gray-800" x-text="seller.rating.toFixed(1)"></span>
                                        <span class="text-gray-400 font-normal" x-text="'(' + seller.reviewCount + ' ulasan)'"></span>
                                    </div>

                                    <!-- Horizontal Divider Separator Line -->
                                    <div class="w-full border-t border-gray-200 my-4"></div>

                                    <!-- Stats Columns: Barang & Pengikut with Vertical Separator -->
                                    <div class="grid grid-cols-2 divide-x divide-gray-200 text-center mb-6">
                                        <div class="pr-2">
                                            <span class="text-[18px] sm:text-xl font-extrabold text-[#111827] block leading-tight" x-text="seller.itemCount"></span>
                                            <span class="text-[12.5px] text-gray-400 font-medium block mt-1">Barang</span>
                                        </div>
                                        <div class="pl-2">
                                            <span class="text-[18px] sm:text-xl font-extrabold text-[#111827] block leading-tight" x-text="seller.followerCount"></span>
                                            <span class="text-[12.5px] text-gray-400 font-medium block mt-1">Pengikut</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Action Button: Lihat Toko -->
                                <a
                                    :href="seller.profileUrl"
                                    class="w-full py-2.5 rounded-xl border border-purple-200 text-[#481EBC] hover:bg-[#481EBC] hover:text-white font-bold text-xs sm:text-[13.5px] transition-all text-center block cursor-pointer"
                                >
                                    Lihat Toko
                                </a>
                            </div>
                        </div>
                    </template>

                    <!-- Empty state if filter doesn't match -->
                    <template x-if="!matchesFilter()">
                        <div class="col-span-full py-12 text-center text-gray-400">
                            <p class="text-sm font-semibold">Tidak ada seller yang cocok dengan filter yang dipilih.</p>
                            <button
                                type="button"
                                @click="selectedCategory = 'all'; selectedRating = null;"
                                class="mt-3 text-xs font-bold text-[#481EBC] hover:underline"
                            >
                                Reset Filter
                            </button>
                        </div>
                    </template>
                </div>
            </section>
        </div>
    </main>
</x-layouts.app>
