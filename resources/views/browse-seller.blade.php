@php
    $sellersData = $sellersList ?? [];
@endphp
<x-layouts.app :title="'Dukung Kreator Favoritmu - WhiMarket'" activeTab="seller">
    <main class="w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-6 sm:py-8" x-data="browseSeller">
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
            <!-- Left Sidebar Filter (Desktop >=1024px) -->
            <div class="hidden lg:block">
                <aside class="w-[240px] xl:w-[255px] shrink-0 space-y-6 sm:space-y-7 bg-white rounded-2xl p-5 sm:p-6 border border-gray-100 shadow-[0_2px_12px_rgba(0,0,0,0.03)] self-start">
                    <!-- 1. Kategori Section -->
                    <div>
                        <button
                            type="button"
                            @click="categoryOpen = !categoryOpen"
                            class="w-full flex items-center justify-between text-[14px] sm:text-[14.5px] font-bold text-gray-900 mb-3 sm:mb-3.5"
                        >
                            <span>Kategori Seller</span>
                            <svg
                                class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                :class="categoryOpen ? '' : 'rotate-180'"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
                            </svg>
                        </button>

                        <div x-show="categoryOpen" class="space-y-1.5 sm:space-y-2 pt-1">
                            <!-- All Categories -->
                            <button
                                type="button"
                                @click="selectedCategory = 'all'"
                                class="w-full flex items-center gap-3 px-3.5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-[13px] font-medium transition-all"
                                :class="selectedCategory === 'all' ? 'bg-[#EDE4FF] text-[#4F26A6] font-bold shadow-2xs' : 'text-gray-600 hover:text-[#4F26A6] hover:bg-gray-50'"
                            >
                                <span :class="selectedCategory === 'all' ? 'text-[#4F26A6]' : 'text-gray-400'">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/>
                                    </svg>
                                </span>
                                <span>Semua Seller</span>
                            </button>

                            <!-- Artis -->
                            <button
                                type="button"
                                @click="selectedCategory = 'artis'"
                                class="w-full flex items-center gap-3 px-3.5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-[13px] font-medium transition-all"
                                :class="selectedCategory === 'artis' ? 'bg-[#EDE4FF] text-[#4F26A6] font-bold shadow-2xs' : 'text-gray-600 hover:text-[#4F26A6] hover:bg-gray-50'"
                            >
                                <span :class="selectedCategory === 'artis' ? 'text-[#4F26A6]' : 'text-gray-400'">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </span>
                                <span>Artis</span>
                            </button>

                            <!-- Selebgram -->
                            <button
                                type="button"
                                @click="selectedCategory = 'selebgram'"
                                class="w-full flex items-center gap-3 px-3.5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-[13px] font-medium transition-all"
                                :class="selectedCategory === 'selebgram' ? 'bg-[#EDE4FF] text-[#4F26A6] font-bold shadow-2xs' : 'text-gray-600 hover:text-[#4F26A6] hover:bg-gray-50'"
                            >
                                <span :class="selectedCategory === 'selebgram' ? 'text-[#4F26A6]' : 'text-gray-400'">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" />
                                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
                                    </svg>
                                </span>
                                <span>Selebgram</span>
                            </button>

                            <!-- Streamer -->
                            <button
                                type="button"
                                @click="selectedCategory = 'streamer'"
                                class="w-full flex items-center gap-3 px-3.5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-[13px] font-medium transition-all"
                                :class="selectedCategory === 'streamer' ? 'bg-[#EDE4FF] text-[#4F26A6] font-bold shadow-2xs' : 'text-gray-600 hover:text-[#4F26A6] hover:bg-gray-50'"
                            >
                                <span :class="selectedCategory === 'streamer' ? 'text-[#4F26A6]' : 'text-gray-400'">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="2" y="7" width="20" height="15" rx="2" ry="2" />
                                        <polyline points="17 2 12 7 7 2" />
                                    </svg>
                                </span>
                                <span>Streamer</span>
                            </button>

                            <!-- Content Creator -->
                            <button
                                type="button"
                                @click="selectedCategory = 'creator'"
                                class="w-full flex items-center gap-3 px-3.5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-[13px] font-medium transition-all"
                                :class="selectedCategory === 'creator' ? 'bg-[#EDE4FF] text-[#4F26A6] font-bold shadow-2xs' : 'text-gray-600 hover:text-[#4F26A6] hover:bg-gray-50'"
                            >
                                <span :class="selectedCategory === 'creator' ? 'text-[#4F26A6]' : 'text-gray-400'">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <span>Content Creator</span>
                            </button>
                        </div>
                    </div>

                    <div class="h-[1px] bg-gray-100 w-full"></div>

                    <!-- Terapkan & Reset Filter Buttons -->
                    <div class="pt-3 space-y-2">
                        <button
                            type="button"
                            @click="appliedCategory = selectedCategory"
                            class="w-full py-2.5 rounded-xl font-bold text-xs sm:text-[13.5px] text-center flex items-center justify-center gap-2 bg-[#4F26A6] hover:bg-[#3E1D85] text-white transition-all cursor-pointer shadow-xs"
                        >
                            <span>Terapkan Filter</span>
                        </button>
                        <button
                            type="button"
                            @click="selectedCategory = 'all'; appliedCategory = 'all'"
                            class="btn-reset-filter w-full py-2.5 rounded-xl font-bold text-xs sm:text-[13.5px] text-center flex items-center justify-center gap-2 cursor-pointer shadow-2xs"
                        >
                            <svg class="w-3.5 h-3.5 text-current transition-colors" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span class="text-current transition-colors">Reset Filter</span>
                        </button>
                    </div>
                </aside>
            </div>

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

                    <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto justify-between sm:justify-end">
                        <!-- Mobile & Tablet Filter Trigger Button -->
                        <button
                            type="button"
                            @click="filterDrawerOpen = true"
                            class="lg:hidden inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-white border border-gray-200 text-xs sm:text-sm font-semibold text-gray-700 shadow-xs hover:border-[#4F26A6] active:bg-gray-50 transition-all shrink-0 cursor-pointer"
                        >
                            <svg class="w-4 h-4 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span>Filter</span>
                        </button>

                        <span class="hidden sm:inline text-xs text-gray-400 font-medium whitespace-nowrap"><span x-text="filteredSellers.length"></span> seller ditemukan</span>
                        <!-- Compact Fit-Content Dropdown Aligned with Trigger Button -->
                        <x-sort-dropdown :options="[
                            'terbaru' => 'Urutan: Terbaru',
                            'terpopuler' => 'Terpopuler',
                            'rating' => 'Rating Tertinggi',
                            'produk' => 'Produk Terbanyak',
                        ]" />
                    </div>
                </div>

                <!-- Seller Card (Mockup Parity: 1 Seller Dummy @rachel_venya) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6">
                    <template x-for="seller in filteredSellers" :key="seller.id">
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
                                    <div class="flex items-center gap-1.5 text-xs text-gray-500 mb-4 min-h-[20px]">
                                        <template x-if="seller.rating > 0">
                                            <div class="flex items-center gap-1.5">
                                                <div class="flex items-center gap-0.5 text-[#F59E0B]">
                                                    @for($i = 0; $i < 5; $i++)
                                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                                    @endfor
                                                </div>
                                                <span class="font-bold text-gray-800" x-text="seller.rating.toFixed(1)"></span>
                                                <span class="text-gray-400 font-normal" x-text="'(' + seller.reviewCount + ' ulasan)'"></span>
                                            </div>
                                        </template>
                                        <template x-if="!seller.rating || seller.rating == 0">
                                            <div class="flex items-center gap-1.5 text-gray-400 font-medium">
                                                <svg class="w-3.5 h-3.5 text-gray-300 fill-none stroke-current" stroke-width="2" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                                <span class="font-semibold text-gray-600">Belum ada ulasan</span>
                                                <span>(0 ulasan)</span>
                                            </div>
                                        </template>
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
                                            <span class="text-[18px] sm:text-xl font-extrabold text-[#111827] block leading-tight" x-text="seller.followerCount == '0' || seller.followerCount == 0 ? '0' : seller.followerCount"></span>
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
                    <template x-if="filteredSellers.length === 0">
                        <div class="col-span-full py-12 text-center text-gray-400">
                            <p class="text-sm font-semibold">Tidak ada seller yang cocok dengan filter yang dipilih.</p>
                            <button
                                type="button"
                                @click="selectedCategory = 'all'"
                                class="mt-3 text-xs font-bold text-[#481EBC] hover:underline"
                            >
                                Reset Filter
                            </button>
                        </div>
                    </template>
                </div>
            </section>
        </div>

        <!-- Mobile & Tablet Filter Drawer: Full-width Bottom Sheet Drawer (<1024px) -->
        <div
            id="seller-filter-drawer-overlay"
            x-show="filterDrawerOpen"
            x-cloak
            x-transition:enter="transition-opacity duration-300 ease-out"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-200 ease-in"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="filterDrawerOpen = false"
            class="fixed inset-0 bg-black/40 backdrop-blur-xs z-50 lg:hidden flex items-end justify-center p-0"
        >
            <div
                @click.stop
                x-show="filterDrawerOpen"
                x-cloak
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="translate-y-full"
                x-transition:enter-end="translate-y-0"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="translate-y-0"
                x-transition:leave-end="translate-y-full"
                class="bg-white w-full max-w-full max-h-[85vh] sm:max-h-[82vh] rounded-t-[28px] sm:rounded-t-[32px] shadow-[0_-10px_40px_rgba(0,0,0,0.18)] flex flex-col justify-between overflow-hidden"
            >
                <!-- Drag Handle Indicator -->
                <div class="pt-3 pb-1 flex justify-center shrink-0">
                    <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
                </div>

                <!-- Drawer Header -->
                <div class="px-5 sm:px-8 pb-3.5 pt-1 border-b border-gray-100 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <h2 class="text-base sm:text-lg font-bold text-gray-900">Filter Seller</h2>
                    </div>
                    <button
                        type="button"
                        @click="filterDrawerOpen = false"
                        class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition-colors focus:outline-none cursor-pointer"
                        aria-label="Tutup Filter"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Scrollable Filter Content -->
                <div class="flex-1 overflow-y-auto px-5 sm:px-8 py-4 space-y-6 [scrollbar-width:none]">
                    <!-- 1. Kategori Section -->
                    <div>
                        <button
                            type="button"
                            @click="categoryOpen = !categoryOpen"
                            class="w-full flex items-center justify-between text-[14px] sm:text-[14.5px] font-bold text-gray-900 mb-3"
                        >
                            <span>Kategori Seller</span>
                            <svg
                                class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                :class="categoryOpen ? '' : 'rotate-180'"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
                            </svg>
                        </button>

                        <div x-show="categoryOpen" class="space-y-1.5 sm:space-y-2 pt-1">
                            <template x-for="cat in [
                                { id: 'all', name: 'Semua Seller', icon: '<svg class=\'w-3.5 h-3.5\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' viewBox=\'0 0 24 24\'><rect x=\'3\' y=\'3\' width=\'7\' height=\'7\' rx=\'1.5\'/><rect x=\'14\' y=\'3\' width=\'7\' height=\'7\' rx=\'1.5\'/><rect x=\'14\' y=\'14\' width=\'7\' height=\'7\' rx=\'1.5\'/><rect x=\'3\' y=\'14\' width=\'7\' height=\'7\' rx=\'1.5\'/></svg>' },
                                { id: 'artis', name: 'Artis', icon: '<svg class=\'w-3.5 h-3.5\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z\' /></svg>' },
                                { id: 'selebgram', name: 'Selebgram', icon: '<svg class=\'w-3.5 h-3.5\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' viewBox=\'0 0 24 24\'><rect x=\'2\' y=\'2\' width=\'20\' height=\'20\' rx=\'5\' ry=\'5\' /><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z\' /><line x1=\'17.5\' y1=\'6.5\' x2=\'17.51\' y2=\'6.5\' /></svg>' },
                                { id: 'streamer', name: 'Streamer', icon: '<svg class=\'w-3.5 h-3.5\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' viewBox=\'0 0 24 24\'><rect x=\'2\' y=\'7\' width=\'20\' height=\'15\' rx=\'2\' ry=\'2\' /><polyline points=\'17 2 12 7 7 2\' /></svg>' },
                                { id: 'creator', name: 'Content Creator', icon: '<svg class=\'w-3.5 h-3.5\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z\' /></svg>' }
                            ]" :key="cat.id">
                                <button
                                    type="button"
                                    @click="selectedCategory = cat.id"
                                    class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-[13px] font-medium transition-all"
                                    :class="selectedCategory === cat.id ? 'bg-[#EDE4FF] text-[#4F26A6] font-bold shadow-2xs' : 'text-gray-600 hover:text-[#4F26A6] hover:bg-gray-50'"
                                >
                                    <span :class="selectedCategory === cat.id ? 'text-[#4F26A6]' : 'text-gray-400'" x-html="cat.icon"></span>
                                    <span x-text="cat.name"></span>
                                </button>
                            </template>
                        </div>
                    </div>


                <!-- Drawer Action Footer (Pinned Bottom) -->
                <div class="p-5 sm:p-7 pt-3 border-t border-gray-100 flex flex-col gap-2.5 shrink-0 bg-white">
                    <button
                        type="button"
                        @click="appliedCategory = selectedCategory; filterDrawerOpen = false;"
                        class="w-full py-3 rounded-xl text-center font-bold text-white bg-[#4F26A6] hover:bg-[#3E1D85] shadow-md shadow-[#4F26A6]/20 transition-all text-sm cursor-pointer"
                    >
                        Terapkan Filter
                    </button>
                    <button
                        type="button"
                        @click="selectedCategory = 'all'; appliedCategory = 'all'; filterDrawerOpen = false;"
                        class="btn-reset-filter w-full py-2.5 rounded-xl text-center font-bold text-xs sm:text-[13px] cursor-pointer shadow-2xs"
                    >
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
    function registerBrowseSeller() {
        Alpine.data('browseSeller', () => ({
            selectedCategory: 'all',
            appliedCategory: 'all',
            sortBy: 'terbaru',
            sortDropdownOpen: false,
            categoryOpen: false,
            filterDrawerOpen: false,
            sellers: @js($sellersData),
            get filteredSellers() {
                let list = this.sellers.filter(s => {
                    if (this.appliedCategory !== 'all' && s.category !== this.appliedCategory) return false;
                    return true;
                });
                if (this.sortBy === 'rating') list.sort((a, b) => b.rating - a.rating);
                if (this.sortBy === 'produk') list.sort((a, b) => b.itemCount - a.itemCount);
                return list;
            }
        }));
    }
    if (window.Alpine) {
        registerBrowseSeller();
    } else {
        document.addEventListener('alpine:init', registerBrowseSeller);
    }
    </script>
    @endpush
</x-layouts.app>
