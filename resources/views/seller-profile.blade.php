<x-layouts.app
    title="Rachel Vennya - Toko Resmi WhiMarket"
    activeTab="belanja"
    :wishlistCount="0"
    :cartCount="0"
    :user="null"
>
    <main
        class="flex-1 w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-3 pb-20"
        x-data="{
            activeTab: 'produk',
            isFollowing: false,
            isShareCopied: false,
            isBioExpanded: false,
            selectedCategory: 'all',
            sortBy: 'terbaru',
            productSortDropdownOpen: false,
            searchQuery: '',
            reviewFilter: 'all',
            reviewSort: 'terbaru',
            reviewSortDropdownOpen: false,
            allProducts: [
                {
                    id: 'rv_1',
                    sellerName: 'Rachel Vennya',
                    sellerAvatar: '/assets/avatar-rachel.png',
                    verified: true,
                    condition: 'Seperti Baru',
                    priceText: 'Rp 1.200.000',
                    priceNumber: 1200000,
                    likes: 128,
                    image: '/assets/products/prod-dunk.png',
                    category: 'fashion'
                },
                {
                    id: 'rv_2',
                    title: 'Tas Michael Kors Original Brown',
                    sellerName: 'Rachel Vennya',
                    sellerAvatar: '/assets/avatar-rachel.png',
                    verified: true,
                    condition: 'Sangat Baik',
                    priceText: 'Rp 2.450.000',
                    priceNumber: 2450000,
                    likes: 215,
                    image: '/assets/banner-chanel-bag.png',
                    category: 'tas'
                },
                {
                    id: 'rv_3',
                    title: 'Varsity Jacket Whimarket Exclusive',
                    sellerName: 'Rachel Vennya',
                    sellerAvatar: '/assets/avatar-rachel.png',
                    verified: true,
                    condition: 'Seperti Baru',
                    priceText: 'Rp 650.000',
                    priceNumber: 650000,
                    likes: 94,
                    image: '/assets/products/prod-hoodie.png',
                    category: 'fashion'
                },
                {
                    id: 'rv_4',
                    title: 'Jaket Denim Vintage Washed',
                    sellerName: 'Rachel Vennya',
                    sellerAvatar: '/assets/avatar-rachel.png',
                    verified: true,
                    condition: 'Baik',
                    priceText: 'Rp 450.000',
                    priceNumber: 450000,
                    likes: 142,
                    image: '/assets/products/prod-denim.png',
                    category: 'fashion'
                },
                {
                    id: 'rv_5',
                    title: 'Parfum Original Rare Luxury',
                    sellerName: 'Rachel Vennya',
                    sellerAvatar: '/assets/avatar-rachel.png',
                    verified: true,
                    condition: 'Sangat Baik',
                    priceText: 'Rp 850.000',
                    priceNumber: 850000,
                    likes: 76,
                    image: '/assets/products/prod-parfum.png',
                    category: 'kecantikan'
                },
                {
                    id: 'rv_6',
                    title: 'Totebag Limited Edition White',
                    sellerName: 'Rachel Vennya',
                    sellerAvatar: '/assets/avatar-rachel.png',
                    verified: true,
                    condition: 'Seperti Baru',
                    priceText: 'Rp 195.000',
                    priceNumber: 195000,
                    likes: 310,
                    image: '/assets/products/prod-totebag.png',
                    category: 'tas'
                }
            ],

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
                    setTimeout(() => { this.isShareCopied = false; }, 2000);
                }
            }
        }"
    >
        <!-- 1. Breadcrumbs: Beranda > Seller > Rachel Vennya -->
        <nav class="flex items-center gap-2 text-xs sm:text-[13px] text-gray-500 font-medium mb-3">
            <a href="/" class="hover:text-[#4F26A6] transition-colors cursor-pointer">Beranda</a>
            <span class="text-gray-300 font-normal">&gt;</span>
            <a href="/belanja" class="hover:text-[#4F26A6] transition-colors cursor-pointer">Seller</a>
            <span class="text-gray-300 font-normal">&gt;</span>
            <span class="text-gray-900 font-bold">Rachel Vennya</span>
        </nav>

        <!-- 2. Hero Banner (reduced height: aspect 1568/380 with max height constraint) -->
        <div class="relative w-full h-[180px] sm:h-[240px] md:h-[280px] lg:h-[300px] xl:h-[320px] rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xs">
            <img
                src="/assets/seller-banner-rachel.png"
                alt="Rachel Vennya Banner"
                class="w-full h-full object-cover object-center"
            />
            <button
                type="button"
                @click="copyShare()"
                class="absolute top-4 right-4 bg-white/95 hover:bg-white backdrop-blur-md px-3.5 py-1.5 rounded-full shadow-xs hover:shadow-sm border border-white/80 flex items-center gap-1.5 text-[12px] font-bold text-gray-800 hover:text-[#4F26A6] transition-all cursor-pointer group"
            >
                <svg class="w-3.5 h-3.5 text-gray-700 group-hover:text-[#4F26A6] transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
                <span x-text="isShareCopied ? 'Tersalin!' : 'Bagikan Toko'"></span>
            </button>
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
                            src="/assets/avatar-rachel-exact.png"
                            alt="Rachel Vennya"
                            class="w-36 h-36 lg:w-40 lg:h-40 rounded-full object-cover ring-4 sm:ring-[5px] ring-white shadow-lg bg-white"
                        />
                    </div>

                    <!-- Seller Info (strictly NEXT to avatar, matching desktop mockup 1:1) -->
                    <div class="flex flex-col pt-2.5">
                        <!-- Name + Verified Rosette -->
                        <div class="flex items-center gap-2 mb-1">
                            <h1 class="text-[25px] xl:text-[28px] font-black text-[#111827] tracking-tight leading-tight">
                                Rachel Vennya
                            </h1>
                            <x-verified-badge size="md" class="w-5.5 h-5.5 shrink-0" />
                        </div>

                        <!-- Subtitle / Role -->
                        <p class="text-[14px] text-gray-500 font-medium mb-1">
                            Selebgram
                        </p>

                        <!-- Bio quote -->
                        <p class="text-[14.5px] text-gray-700 font-normal mb-3">
                            &ldquo;Let good things find a new home ♡&rdquo;
                        </p>

                        <!-- Desktop Stats Row: All in one line next to avatar -->
                        <div class="flex items-center gap-3.5 text-[13.5px] text-gray-600 font-medium">
                            <!-- Rating -->
                            <div class="inline-flex items-center gap-1.5 shrink-0">
                                <svg class="w-[18px] h-[18px] text-amber-400 fill-current shrink-0" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="font-extrabold text-gray-900 text-[15px]">4.9</span>
                                <span class="text-gray-400 font-normal whitespace-nowrap text-[13px]">(1.2rb ulasan)</span>
                            </div>

                            <span class="text-gray-200 font-light">|</span>

                            <!-- Barang Count -->
                            <div class="inline-flex items-center gap-1.5 shrink-0">
                                <svg class="w-[18px] h-[18px] text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <span class="font-semibold text-gray-800 whitespace-nowrap text-[13.5px]">112 Barang</span>
                            </div>

                            <span class="text-gray-200 font-light">|</span>

                            <!-- Pengikut -->
                            <div class="inline-flex items-center gap-1.5 shrink-0">
                                <svg class="w-[18px] h-[18px] text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="font-semibold text-gray-800 whitespace-nowrap text-[13.5px]">12.4rb Pengikut</span>
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
                                <span class="text-gray-500 font-normal whitespace-nowrap text-[13px]">Bergabung sejak Mar 2024</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Desktop Action Buttons -->
                <div class="flex items-center gap-3 pt-6 pr-1 shrink-0">
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

                    <button
                        type="button"
                        class="w-11 h-11 sm:w-11.5 sm:h-11.5 rounded-xl border border-gray-200/90 bg-white hover:bg-gray-50 flex items-center justify-center text-gray-700 transition-colors shadow-2xs cursor-pointer shrink-0"
                        title="Menu Lainnya"
                    >
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Only (< 768px): Responsive layout matching user mobile preference -->
            <div class="flex flex-col md:hidden w-full">
                <!-- Top row: Avatar + Name on left, 3-dots button on right -->
                <div class="flex items-start justify-between w-full gap-2 sm:gap-4">
                    <div class="flex items-end gap-3 sm:gap-6 min-w-0">
                        <div class="-mt-12 sm:-mt-16 shrink-0 z-20">
                            <img
                                src="/assets/avatar-rachel-exact.png"
                                alt="Rachel Vennya"
                                class="w-24 h-24 sm:w-28 sm:h-28 md:w-32 md:h-32 rounded-full object-cover ring-4 sm:ring-[5px] ring-white shadow-lg bg-white"
                            />
                        </div>
                        <div class="flex flex-col pt-5 sm:pt-7 md:pt-8 pb-1 min-w-0">
                            <div class="flex items-center gap-1.5 sm:gap-2">
                                <h1 class="text-[19px] sm:text-[25px] font-black text-[#111827] tracking-tight leading-tight whitespace-nowrap">
                                    Rachel Vennya
                                </h1>
                                <x-verified-badge size="md" class="w-4.5 h-4.5 sm:w-5 sm:h-5 shrink-0" />
                            </div>
                            <p class="text-[13px] sm:text-[14px] text-gray-500 font-medium mt-0.5">
                                Selebgram
                            </p>
                        </div>
                    </div>
                    <!-- 3-dots button aligned with name -->
                    <button
                        type="button"
                        class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl border border-gray-200/90 bg-white hover:bg-gray-50 items-center justify-center text-gray-700 transition-colors shadow-2xs cursor-pointer shrink-0 mt-5 sm:mt-7 md:mt-8 flex"
                        title="Menu Lainnya"
                    >
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                        </svg>
                    </button>
                </div>

                <!-- Below photo: Bio Quote -->
                <p class="text-[14px] sm:text-[15px] text-gray-700 font-normal mt-3 mb-2.5">
                    &ldquo;Let good things find a new home ♡&rdquo;
                </p>

                <!-- Tablet Stats Row (sm to lg: >= 640px and < 1024px) -->
                <div class="hidden sm:flex flex-wrap items-center gap-x-3.5 gap-y-2 text-[13px] sm:text-[14px] text-gray-600 font-medium mb-3">
                    <div class="inline-flex items-center gap-1.5 shrink-0">
                        <svg class="w-[18px] h-[18px] text-amber-400 fill-current shrink-0" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="font-extrabold text-gray-900 text-[14.5px] sm:text-[15px]">4.9</span>
                        <span class="text-gray-400 font-normal whitespace-nowrap text-[12.5px] sm:text-[13px]">(1.2rb ulasan)</span>
                    </div>

                    <span class="text-gray-200 font-light">|</span>

                    <div class="inline-flex items-center gap-1.5 shrink-0">
                        <svg class="w-[17px] h-[17px] text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="font-semibold text-gray-800 whitespace-nowrap text-[13px] sm:text-[14px]">112 Barang</span>
                    </div>

                    <span class="text-gray-200 font-light">|</span>

                    <div class="inline-flex items-center gap-1.5 shrink-0">
                        <svg class="w-[17px] h-[17px] text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="font-semibold text-gray-800 whitespace-nowrap text-[13px] sm:text-[14px]">12.4rb Pengikut</span>
                    </div>

                    <span class="text-gray-200 font-light">|</span>

                    <div class="inline-flex items-center gap-1.5 shrink-0">
                        <svg class="w-[17px] h-[17px] text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        <span class="text-gray-500 font-normal whitespace-nowrap text-[12.5px] sm:text-[13px]">Bergabung sejak Mar 2024</span>
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
                        <span class="text-[19px] font-black text-[#111827] leading-none">4.9</span>
                        <span class="text-[12px] text-gray-400 font-normal mt-1.5 whitespace-nowrap">(1.2rb ulasan)</span>
                    </div>

                    <div class="h-8 w-[1px] bg-gray-200/80 shrink-0"></div>

                    <!-- Barang -->
                    <div class="flex flex-col items-center text-center flex-1">
                        <svg class="w-5 h-5 text-[#4F26A6] stroke-current fill-none mb-1" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="text-[19px] font-black text-[#111827] leading-none">112</span>
                        <span class="text-[12px] text-gray-400 font-normal mt-1.5 whitespace-nowrap">Barang</span>
                    </div>

                    <div class="h-8 w-[1px] bg-gray-200/80 shrink-0"></div>

                    <!-- Pengikut -->
                    <div class="flex flex-col items-center text-center flex-1">
                        <svg class="w-5 h-5 text-[#4F26A6] stroke-current fill-none mb-1" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-[19px] font-black text-[#111827] leading-none">12.4rb</span>
                        <span class="text-[12px] text-gray-400 font-normal mt-1.5 whitespace-nowrap">Pengikut</span>
                    </div>

                    <div class="h-8 w-[1px] bg-gray-200/80 shrink-0"></div>
                </div>

                <!-- + Ikuti Toko Button -->
                <div class="w-full pt-1">
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
                        Tentang Rachel Vennya
                    </h3>

                    <div class="text-[13px] sm:text-[13.5px] text-gray-600 leading-relaxed space-y-2">
                        <p>
                            Di sini aku jual barang pre-loved pribadi yang masih bagus dan layak pakai. Semoga bisa menemukan pemilik baru yang lebih cinta lagi ♡
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
                                    @click.prevent.stop="isLiked = !isLiked; likesCount += (isLiked ? 1 : -1)"
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

                                <img :src="product.image" :alt="product.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
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
                                    <h3 class="text-[13.5px] sm:text-[14.5px] font-medium text-gray-700 mt-2 mb-3 line-clamp-1" x-text="product.title"></h3>
                                </div>
                                <div class="flex items-center justify-between pt-1">
                                    <span class="text-sm sm:text-[15.5px] font-bold text-[#4F26A6]" x-text="product.priceText"></span>
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
                    </template>
                </div>
            </div>
        </div>

        <!-- 6. Tab Content: Ulasan (Matching exact 1:1 mockup) -->
        <div x-show="activeTab === 'ulasan'" class="mt-8 flex flex-col lg:flex-row items-start gap-8" style="display: none;">
            <!-- Left: Rating Keseluruhan Sidebar -->
            <div class="w-full lg:w-[310px] xl:w-[330px] shrink-0 space-y-4">
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
                    <h3 class="text-base sm:text-[17px] font-extrabold text-gray-900 tracking-tight mb-4">
                        Rating Keseluruhan
                    </h3>
                    <div class="flex items-baseline gap-3 mb-1">
                        <span class="text-5xl font-black text-gray-900 tracking-tight">4.9</span>
                        <div class="flex items-center gap-1 text-amber-400">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 font-medium mb-6">dari 1.278 ulasan</p>

                    <!-- Breakdown Bars with enlarged numbers -->
                    <div class="space-y-2.5 mb-6">
                        @foreach([
                            ['star' => 5, 'count' => '1.086', 'pct' => 85],
                            ['star' => 4, 'count' => '142', 'pct' => 15],
                            ['star' => 3, 'count' => '38', 'pct' => 4],
                            ['star' => 2, 'count' => '8', 'pct' => 1.5],
                            ['star' => 1, 'count' => '4', 'pct' => 1]
                        ] as $row)
                            <div class="flex items-center gap-3 text-gray-700 font-medium">
                                <span class="w-3.5 text-sm font-extrabold text-gray-900">{{ $row['star'] }}</span>
                                <svg class="w-4 h-4 text-amber-400 fill-current shrink-0" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <div class="flex-1 h-2.5 bg-purple-50 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#5022CE] rounded-full" style="width: {{ $row['pct'] }}%"></div>
                                </div>
                                <span class="w-11 text-right text-gray-500 text-[12.5px] font-bold">{{ $row['count'] }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Verified Note Box -->
                    <div class="bg-[#F6F4F9] rounded-2xl p-4 flex items-center gap-3.5">
                        <div class="w-9 h-9 rounded-2xl bg-[#E8E2F4] flex items-center justify-center text-[#5022CE] shrink-0">
                            <svg class="w-5 h-5 fill-none stroke-current" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div class="text-[12.5px] text-gray-900 font-semibold leading-snug">
                            <p>Ulasan asli dari pembeli</p>
                            <p>terverifikasi di Whimarket.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Reviews List -->
            <div class="flex-1 w-full min-w-0 bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
                <!-- Header row: Semua Ulasan title and Urutkan dropdown -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 items-end">
                    <div class="w-full sm:w-auto">
                        <h2 class="text-[20px] sm:text-[22px] font-extrabold text-[#111827] tracking-tight">
                            Semua Ulasan
                        </h2>
                        <p class="text-xs sm:text-[13px] text-gray-500 font-normal mt-0.5">
                            Lihat pengalaman pembeli lain berbelanja di toko Rachel Vennya.
                        </p>
                    </div>

                    <!-- Urutkan Dropdown (Right-aligned on mobile and desktop) -->
                    <div class="relative shrink-0 w-fit" @click.outside="reviewSortDropdownOpen = false">
                        <button
                            type="button"
                            @click="reviewSortDropdownOpen = !reviewSortDropdownOpen"
                            class="inline-flex items-center justify-between gap-2.5 bg-white border border-gray-200 text-xs sm:text-[13px] font-semibold text-gray-800 rounded-xl px-3.5 sm:px-4 py-2 sm:py-2.5 focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] shadow-xs hover:border-gray-300 transition-all cursor-pointer whitespace-nowrap"
                        >
                            <span x-text="reviewSort === 'terbaru' ? 'Urutan: Terbaru' : (reviewSort === 'tertinggi' ? 'Rating Tertinggi' : 'Rating Terendah')"></span>
                            <svg
                                class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200 shrink-0"
                                :class="reviewSortDropdownOpen ? 'rotate-180 text-[#4F26A6]' : ''"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Custom Floating Sort Options Panel -->
                        <div
                            x-show="reviewSortDropdownOpen"
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
                                @click="reviewSort = 'terbaru'; reviewSortDropdownOpen = false"
                                class="w-full text-left px-3.5 py-2 rounded-xl flex items-center hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors cursor-pointer"
                                :class="reviewSort === 'terbaru' ? 'bg-[#F3EEFF] text-[#4F26A6] font-bold' : ''"
                            >
                                <span>Urutan: Terbaru</span>
                            </button>
                            <button
                                type="button"
                                @click="reviewSort = 'tertinggi'; reviewSortDropdownOpen = false"
                                class="w-full text-left px-3.5 py-2 rounded-xl flex items-center hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors cursor-pointer"
                                :class="reviewSort === 'tertinggi' ? 'bg-[#F3EEFF] text-[#4F26A6] font-bold' : ''"
                            >
                                <span>Rating Tertinggi</span>
                            </button>
                            <button
                                type="button"
                                @click="reviewSort = 'terendah'; reviewSortDropdownOpen = false"
                                class="w-full text-left px-3.5 py-2 rounded-xl flex items-center hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors cursor-pointer"
                                :class="reviewSort === 'terendah' ? 'bg-[#F3EEFF] text-[#4F26A6] font-bold' : ''"
                            >
                                <span>Rating Terendah</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Star Filter Pills Row -->
                <div class="flex items-center gap-2 sm:gap-2.5 overflow-x-auto pb-4 mb-6 border-b border-gray-100 [scrollbar-width:none]">
                    <button
                        type="button"
                        @click="reviewFilter = 'all'"
                        class="px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-xl text-xs sm:text-[12.5px] font-bold whitespace-nowrap transition-all cursor-pointer flex items-center gap-1.5 shrink-0"
                        :class="reviewFilter === 'all' ? 'border-2 border-[#5022CE] text-[#5022CE] bg-purple-50/50' : 'border border-gray-200/80 text-gray-700 bg-white hover:bg-gray-50'"
                    >
                        <span>Semua (1.278)</span>
                    </button>
                    <template x-for="p in [
                        { id: '5', label: '5', count: '1.086' },
                        { id: '4', label: '4', count: '142' },
                        { id: '3', label: '3', count: '38' },
                        { id: '2', label: '2', count: '8' },
                        { id: '1', label: '1', count: '4' }
                    ]" :key="p.id">
                        <button
                            type="button"
                            @click="reviewFilter = p.id"
                            class="px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-xl text-xs sm:text-[12.5px] font-bold whitespace-nowrap transition-all cursor-pointer flex items-center gap-1.5 shrink-0"
                            :class="reviewFilter === p.id ? 'border-2 border-[#5022CE] text-[#5022CE] bg-purple-50/50' : 'border border-gray-200/80 text-gray-700 bg-white hover:bg-gray-50'"
                        >
                            <span x-text="p.label"></span>
                            <svg class="w-3.5 h-3.5 text-amber-400 fill-current shrink-0" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span class="text-gray-400 font-normal" x-text="'(' + p.count + ')'"></span>
                        </button>
                    </template>
                </div>

                <!-- Review Items -->
                <div class="divide-y divide-gray-100">
                    <!-- Review Item 1: Anya Geraldine -->
                    <div class="pb-7 sm:pb-8 pt-0">
                        <div class="flex flex-col md:flex-row md:items-stretch justify-between gap-6">
                            <div class="flex-1 min-w-0 md:pr-6 md:border-r md:border-gray-200/80">
                                <div class="flex items-center gap-3 mb-2">
                                    <img src="/assets/avatar-anya.png" alt="Anya Geraldine" class="w-10 h-10 rounded-full object-cover ring-1 ring-gray-100 shrink-0" />
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-[14.5px] font-bold text-gray-900">Anya Geraldine</h4>
                                            <span class="text-xs text-gray-400 font-normal">3 hari lalu</span>
                                        </div>
                                        <div class="flex items-center gap-1 text-amber-400 mt-1">
                                            @for($i = 0; $i < 5; $i++)
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs sm:text-[13.5px] text-gray-700 leading-relaxed mb-3">
                                    Barangnya masih super bagus, sesuai deskripsi! Packing rapi banget dan pengiriman cepat. Makasih ka Rachel ♡
                                </p>
                                <div class="flex items-center gap-2.5">
                                    <img src="/assets/review-chanel-1.png" alt="Review 1" class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover border border-gray-100 hover:scale-105 transition-transform cursor-pointer shadow-2xs" />
                                    <img src="/assets/review-chanel-2.png" alt="Review 2" class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover border border-gray-100 hover:scale-105 transition-transform cursor-pointer shadow-2xs" />
                                    <img src="/assets/review-chanel-3.png" alt="Review 3" class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover border border-gray-100 hover:scale-105 transition-transform cursor-pointer shadow-2xs" />
                                </div>
                            </div>
                            <div class="flex items-start gap-3.5 sm:gap-4 shrink-0 pt-4 md:pt-0 w-full md:w-[280px]">
                                <div class="w-[84px] h-[84px] sm:w-[90px] sm:h-[90px] rounded-2xl bg-[#ECE8F1] shrink-0 overflow-hidden">
                                    <img src="/assets/products/prod-bag.png" alt="Tas Charles & Keith" class="w-full h-full object-cover" />
                                </div>
                                <div class="h-[84px] sm:h-[90px] flex flex-col justify-between min-w-0 py-0.5">
                                    <div>
                                        <span class="text-[12.5px] sm:text-[13px] font-bold text-gray-900 leading-tight truncate block">Tas Charles &amp; Keith Black</span>
                                        <span class="text-[13.5px] sm:text-[14px] font-extrabold text-[#5022CE] mt-1 leading-tight block">Rp 850.000</span>
                                    </div>
                                    <a href="#produk" class="text-[12px] sm:text-[13px] text-[#5022CE] hover:text-[#3E1D85] font-bold inline-flex items-center gap-1.5 transition-colors group leading-tight">
                                        <span>Lihat Produk</span>
                                        <span class="group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Item 2: Fuji An -->
                    <div class="py-7 sm:py-8">
                        <div class="flex flex-col md:flex-row md:items-stretch justify-between gap-6">
                            <div class="flex-1 min-w-0 md:pr-6 md:border-r md:border-gray-200/80">
                                <div class="flex items-center gap-3 mb-2">
                                    <img src="/assets/avatars/avatar-fuji.png" alt="Fuji An" class="w-10 h-10 rounded-full object-cover ring-1 ring-gray-100 shrink-0" />
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-[14.5px] font-bold text-gray-900">Fuji An</h4>
                                            <span class="text-xs text-gray-400 font-normal">1 minggu lalu</span>
                                        </div>
                                        <div class="flex items-center gap-1 text-amber-400 mt-1">
                                            @for($i = 0; $i < 5; $i++)
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs sm:text-[13.5px] text-gray-700 leading-relaxed mb-3">
                                    Hoodie-nya masih like new! Bahannya tebal dan nyaman banget dipakai. Suka banget, makasih! ♡
                                </p>
                                <div class="flex items-center gap-2.5">
                                    <img src="/assets/review-hoodie-1.png" alt="Review 1" class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover border border-gray-100 hover:scale-105 transition-transform cursor-pointer shadow-2xs" />
                                    <img src="/assets/review-hoodie-2.png" alt="Review 2" class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover border border-gray-100 hover:scale-105 transition-transform cursor-pointer shadow-2xs" />
                                    <img src="/assets/review-hoodie-3.png" alt="Review 3" class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover border border-gray-100 hover:scale-105 transition-transform cursor-pointer shadow-2xs" />
                                </div>
                            </div>
                            <div class="flex items-start gap-3.5 sm:gap-4 shrink-0 pt-4 md:pt-0 w-full md:w-[280px]">
                                <div class="w-[84px] h-[84px] sm:w-[90px] sm:h-[90px] rounded-2xl bg-[#ECE8F1] shrink-0 overflow-hidden">
                                    <img src="/assets/products/prod-hoodie.png" alt="Hoodie Plan Do" class="w-full h-full object-cover" />
                                </div>
                                <div class="h-[84px] sm:h-[90px] flex flex-col justify-between min-w-0 py-0.5">
                                    <div>
                                        <span class="text-[12.5px] sm:text-[13px] font-bold text-gray-900 leading-tight truncate block">Hoodie Plan Do</span>
                                        <span class="text-[13.5px] sm:text-[14px] font-extrabold text-[#5022CE] mt-1 leading-tight block">Rp 500.000</span>
                                    </div>
                                    <a href="#produk" class="text-[12px] sm:text-[13px] text-[#5022CE] hover:text-[#3E1D85] font-bold inline-flex items-center gap-1.5 transition-colors group leading-tight">
                                        <span>Lihat Produk</span>
                                        <span class="group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Item 3: Raisy Febian -->
                    <div class="pt-7 sm:pt-8 pb-4 sm:pb-5">
                        <div class="flex flex-col md:flex-row md:items-stretch justify-between gap-6">
                            <div class="flex-1 min-w-0 md:pr-6 md:border-r md:border-gray-200/80">
                                <div class="flex items-center gap-3 mb-2">
                                    <img src="/assets/avatars/avatar-raisy.png" alt="Raisy Febian" class="w-10 h-10 rounded-full object-cover ring-1 ring-gray-100 shrink-0" />
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-[14.5px] font-bold text-gray-900">Raisy Febian</h4>
                                            <span class="text-xs text-gray-400 font-normal">2 minggu lalu</span>
                                        </div>
                                        <div class="flex items-center gap-1 text-amber-400 mt-1">
                                            @for($i = 0; $i < 5; $i++)
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs sm:text-[13.5px] text-gray-700 leading-relaxed mb-3">
                                    Barangnya super aman dipacking pake bubble wrap tebal. Sesuai deskripsi banget, seller ramah dan gercep.
                                </p>
                                <div class="flex items-center gap-2.5">
                                    <img src="/assets/review-pokemon-1.png" alt="Review 1" class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover border border-gray-100 hover:scale-105 transition-transform cursor-pointer shadow-2xs" />
                                    <img src="/assets/review-pokemon-2.png" alt="Review 2" class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover border border-gray-100 hover:scale-105 transition-transform cursor-pointer shadow-2xs" />
                                    <img src="/assets/review-pokemon-3.png" alt="Review 3" class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover border border-gray-100 hover:scale-105 transition-transform cursor-pointer shadow-2xs" />
                                </div>
                            </div>
                            <div class="flex items-start gap-3.5 sm:gap-4 shrink-0 pt-4 md:pt-0 pb-2 md:pb-0 w-full md:w-[280px]">
                                <div class="w-[84px] h-[84px] sm:w-[90px] sm:h-[90px] rounded-2xl bg-[#ECE8F1] shrink-0 overflow-hidden">
                                    <img src="/assets/products/prod-pokemon.png" alt="Kartu Pokemon Rare" class="w-full h-full object-cover" />
                                </div>
                                <div class="h-[84px] sm:h-[90px] flex flex-col justify-between min-w-0 py-0.5">
                                    <div>
                                        <span class="text-[12.5px] sm:text-[13px] font-bold text-gray-900 leading-tight truncate block">Kartu Pokemon Rare</span>
                                        <span class="text-[13.5px] sm:text-[14px] font-extrabold text-[#5022CE] mt-1 leading-tight block">Rp 1.500.000</span>
                                    </div>
                                    <a href="#produk" class="text-[12px] sm:text-[13px] text-[#5022CE] hover:text-[#3E1D85] font-bold inline-flex items-center gap-1.5 transition-colors group leading-tight">
                                        <span>Lihat Produk</span>
                                        <span class="group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>
