<x-layouts.app
    title="WhiMarket - Marketplace Pre-loved & Merchandise"
    activeTab="belanja"
    :wishlistCount="2"
    :cartCount="3"
    :user="['name' => 'Halo, Dimas', 'avatar' => '/assets/avatar-anya.png']"
>
    <!-- Top Purple Hero Banner -->
    <x-shop.shop-banner />

    <!-- Catalog Container with Alpine filter state -->
    <div
        class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-2 pb-16"
        x-data="{
            allProducts: [
                {
                    id: 'prod_new_1',
                    title: 'Nike Dunk Low Purple (Used)',
                    sellerName: 'Rachel Vennya',
                    sellerAvatar: '/assets/avatar-rachel.png',
                    verified: true,
                    priceText: 'Rp 1.200.000',
                    priceNumber: 1200000,
                    likes: 128,
                    image: '/assets/products/prod-dunk.png',
                    condition: 'Seperti Baru',
                    category: 'fashion'
                },
                {
                    id: 'prod_new_2',
                    title: 'Tas Charles & Keith Black',
                    sellerName: 'Anya Geraldine',
                    sellerAvatar: '/assets/avatar-anya.png',
                    verified: true,
                    priceText: 'Rp 850.000',
                    priceNumber: 850000,
                    likes: 96,
                    image: '/assets/products/prod-bag.png',
                    condition: 'Sangat Baik',
                    category: 'tas'
                },
                {
                    id: 'prod_new_3',
                    title: 'Jaket Denim Vintage',
                    sellerName: 'Verrell Bramasta',
                    sellerAvatar: '/assets/avatars/avatar-verrell.png',
                    verified: true,
                    priceText: 'Rp 750.000',
                    priceNumber: 750000,
                    likes: 72,
                    image: '/assets/products/prod-denim.png',
                    condition: 'Seperti Baru',
                    category: 'fashion'
                },
                {
                    id: 'prod_new_4',
                    title: 'Hoodie Plan Do',
                    sellerName: 'Celloszx',
                    sellerAvatar: '/assets/avatars/avatar-cellos.png',
                    verified: true,
                    priceText: 'Rp 500.000',
                    priceNumber: 500000,
                    likes: 64,
                    image: '/assets/products/prod-hoodie.png',
                    condition: 'Baik',
                    category: 'fashion'
                },
                {
                    id: 'prod_new_5',
                    title: 'Instax Mini 11 (Used)',
                    sellerName: 'Fuji An',
                    sellerAvatar: '/assets/avatars/avatar-fuji.png',
                    verified: true,
                    priceText: 'Rp 1.000.000',
                    priceNumber: 1000000,
                    likes: 112,
                    image: '/assets/products/prod-instax.png',
                    condition: 'Sangat Baik',
                    category: 'elektronik'
                },
                {
                    id: 'prod_new_6',
                    title: 'Parfum Bleu de Chanel',
                    sellerName: 'Windah Basudara',
                    sellerAvatar: '/assets/avatars/avatar-windah.png',
                    verified: true,
                    priceText: 'Rp 1.600.000',
                    priceNumber: 1600000,
                    likes: 89,
                    image: '/assets/products/prod-chanel.png',
                    condition: 'Seperti Baru',
                    category: 'kecantikan'
                },
                {
                    id: 'prod_new_7',
                    title: 'Totebag Limited Edition',
                    sellerName: 'MiawAug',
                    sellerAvatar: '/assets/avatars/avatar-miawaug.png',
                    verified: true,
                    priceText: 'Rp 200.000',
                    priceNumber: 200000,
                    likes: 54,
                    image: '/assets/products/prod-totebag.png',
                    condition: 'Baik',
                    category: 'tas'
                },
                {
                    id: 'prod_new_8',
                    title: 'Kartu Pokemon Rare',
                    sellerName: 'Raisy Febian',
                    sellerAvatar: '/assets/avatars/avatar-raisy.png',
                    verified: true,
                    priceText: 'Rp 1.500.000',
                    priceNumber: 1500000,
                    likes: 76,
                    image: '/assets/products/prod-pokemon.png',
                    condition: 'Seperti Baru',
                    category: 'hobi'
                },
                {
                    id: 'prod_new_9',
                    title: 'Hoodie Damn Plan Do',
                    sellerName: 'Celloszx',
                    sellerAvatar: '/assets/avatars/avatar-cellos.png',
                    verified: true,
                    priceText: 'Rp 500.000',
                    priceNumber: 500000,
                    likes: 41,
                    image: '/assets/products/prod-hoodie-black.png',
                    condition: 'Baik',
                    category: 'fashion'
                },
                {
                    id: 'prod_new_10',
                    title: 'Varsity Jacket Whimarket',
                    sellerName: 'Rachel Vennya',
                    sellerAvatar: '/assets/avatar-rachel.png',
                    verified: true,
                    priceText: 'Rp 650.000',
                    priceNumber: 650000,
                    likes: 68,
                    image: '/assets/products/prod-varsity.png',
                    condition: 'Seperti Baru',
                    category: 'fashion'
                },
                {
                    id: 'prod_new_11',
                    title: 'Headphone Sony WH-1000XM5',
                    sellerName: 'Jerome Polin',
                    sellerAvatar: '/assets/avatar-jerome.png',
                    verified: true,
                    priceText: 'Rp 3.200.000',
                    priceNumber: 3200000,
                    likes: 102,
                    image: '/assets/products/prod-headphone.png',
                    condition: 'Sangat Baik',
                    category: 'elektronik'
                },
                {
                    id: 'prod_new_12',
                    title: 'Air Jordan 1 (Used)',
                    sellerName: 'Anya Geraldine',
                    sellerAvatar: '/assets/avatar-anya.png',
                    verified: true,
                    priceText: 'Rp 2.500.000',
                    priceNumber: 2500000,
                    likes: 95,
                    image: '/assets/products/prod-jordan.png',
                    condition: 'Sangat Baik',
                    category: 'fashion'
                }
            ],
            selectedCategory: 'all',
            priceRange: [0, 50000000],
            selectedConditions: ['all'],
            selectedLocation: '',
            sortBy: 'terbaru',
            viewMode: 'grid',
            isMobileFilterOpen: false,
            currentPage: 1,

            openSections: {
                kategori: true,
                harga: true,
                kondisi: true,
                lokasi: true,
            },

            isLocationDropdownOpen: false,
            locationSearchQuery: '',
            locationsList: [
                { id: '', name: 'Semua Lokasi' },
                { id: 'jabodetabek', name: 'Jabodetabek' },
                { id: 'jakarta-selatan', name: 'Jakarta Selatan' },
                { id: 'jakarta-barat', name: 'Jakarta Barat' },
                { id: 'jakarta-pusat', name: 'Jakarta Pusat' },
                { id: 'jakarta-utara', name: 'Jakarta Utara' },
                { id: 'jakarta-timur', name: 'Jakarta Timur' },
                { id: 'bandung', name: 'Bandung' },
                { id: 'surabaya', name: 'Surabaya' },
                { id: 'yogyakarta', name: 'Yogyakarta' },
                { id: 'semarang', name: 'Semarang' },
                { id: 'medan', name: 'Medan' },
                { id: 'bali', name: 'Bali & Denpasar' },
                { id: 'makassar', name: 'Makassar' },
            ],

            get filteredLocations() {
                if (!this.locationSearchQuery) return this.locationsList;
                return this.locationsList.filter(l => l.name.toLowerCase().includes(this.locationSearchQuery.toLowerCase()));
            },

            get selectedLocationName() {
                const loc = this.locationsList.find(l => l.id === this.selectedLocation);
                return loc ? loc.name : 'Pilih Lokasi';
            },

            toggleSection(sec) {
                this.openSections[sec] = !this.openSections[sec];
            },

            toggleCondition(val) {
                if (val === 'all') {
                    this.selectedConditions = ['all'];
                    return;
                }
                let curr = this.selectedConditions.filter(x => x !== 'all');
                if (curr.includes(val)) {
                    curr = curr.filter(x => x !== val);
                } else {
                    curr.push(val);
                }
                this.selectedConditions = curr.length === 0 ? ['all'] : curr;
            },

            get filteredProducts() {
                let list = this.allProducts.filter(item => {
                    if (this.selectedCategory !== 'all' && item.category !== this.selectedCategory) return false;
                    if (item.priceNumber < this.priceRange[0] || item.priceNumber > this.priceRange[1]) return false;
                    if (!this.selectedConditions.includes('all')) {
                        const mapCond = {
                            'seperti-baru': 'Seperti Baru',
                            'sangat-baik': 'Sangat Baik',
                            'baik': 'Baik',
                            'cukup': 'Cukup'
                        };
                        const matches = this.selectedConditions.some(c => mapCond[c] === item.condition || c === item.condition);
                        if (!matches) return false;
                    }
                    return true;
                });

                if (this.sortBy === 'harga-rendah') {
                    list.sort((a, b) => a.priceNumber - b.priceNumber);
                } else if (this.sortBy === 'harga-tinggi') {
                    list.sort((a, b) => b.priceNumber - a.priceNumber);
                } else if (this.sortBy === 'terpopuler') {
                    list.sort((a, b) => b.likes - a.likes);
                }
                return list;
            },

            resetFilters() {
                this.selectedCategory = 'all';
                this.priceRange = [0, 50000000];
                this.selectedConditions = ['all'];
                this.selectedLocation = '';
                this.sortBy = 'terbaru';
            },

            formatRupiah(num) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
            }
        }"
    >
        <div class="flex flex-col lg:flex-row items-start gap-6 xl:gap-8">
            <!-- Left Sidebar Filter (Desktop >=1024px) -->
            <div class="hidden lg:block">
                <aside class="w-[240px] xl:w-[255px] shrink-0 space-y-6 sm:space-y-7 bg-white rounded-2xl p-5 sm:p-6 border border-gray-100 shadow-[0_2px_12px_rgba(0,0,0,0.03)] self-start">
                <!-- 1. Kategori Section -->
                <div>
                    <button
                        type="button"
                        @click="toggleSection('kategori')"
                        class="w-full flex items-center justify-between text-[14px] sm:text-[14.5px] font-bold text-gray-900 mb-3 sm:mb-3.5"
                    >
                        <span>Kategori</span>
                        <svg
                            class="w-4 h-4 text-gray-400 transition-transform duration-200"
                            :class="openSections.kategori ? '' : 'rotate-180'"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
                        </svg>
                    </button>

                    <div x-show="openSections.kategori">
                        <x-shop.category-list />
                    </div>
                </div>

                <div class="h-[1px] bg-gray-100 w-full"></div>

                <!-- 2. Harga Section with interactive dual-thumb range slider -->
                <div>
                    <button
                        type="button"
                        @click="toggleSection('harga')"
                        class="w-full flex items-center justify-between text-[14px] sm:text-[14.5px] font-bold text-gray-900 mb-3 sm:mb-3.5"
                    >
                        <span>Harga</span>
                        <svg
                            class="w-4 h-4 text-gray-400 transition-transform duration-200"
                            :class="openSections.harga ? '' : 'rotate-180'"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
                        </svg>
                    </button>

                    <div x-show="openSections.harga" class="pt-1.5 space-y-3">
                        <div class="relative w-full flex items-center h-6">
                            <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                <div
                                    class="h-full bg-[#4F26A6] rounded-full"
                                    :style="`margin-left: ${(priceRange[0] / 50000000) * 100}%; width: ${((priceRange[1] - priceRange[0]) / 50000000) * 100}%;`"
                                ></div>
                            </div>
                            <input
                                type="range"
                                min="0"
                                max="50000000"
                                step="500000"
                                x-model.number="priceRange[0]"
                                class="absolute inset-0 w-full appearance-none bg-transparent pointer-events-auto cursor-pointer accent-[#4F26A6] opacity-0 z-20"
                            />
                            <input
                                type="range"
                                min="0"
                                max="50000000"
                                step="500000"
                                x-model.number="priceRange[1]"
                                class="absolute inset-0 w-full appearance-none bg-transparent pointer-events-auto cursor-pointer accent-[#4F26A6] opacity-0 z-30"
                            />
                            <div
                                class="absolute w-4 h-4 bg-[#4F26A6] rounded-full ring-2 ring-white shadow-md pointer-events-none -translate-x-1/2 z-10"
                                :style="`left: ${(priceRange[0] / 50000000) * 100}%;`"
                            ></div>
                            <div
                                class="absolute w-4 h-4 bg-[#4F26A6] rounded-full ring-2 ring-white shadow-md pointer-events-none -translate-x-1/2 z-10"
                                :style="`left: ${(priceRange[1] / 50000000) * 100}%;`"
                            ></div>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-500 font-semibold pt-0.5">
                            <span x-text="formatRupiah(priceRange[0])"></span>
                            <span x-text="formatRupiah(priceRange[1])"></span>
                        </div>
                    </div>
                </div>

                <div class="h-[1px] bg-gray-100 w-full"></div>

                <!-- 3. Kondisi Section (Checkboxes with custom tick) -->
                <div>
                    <button
                        type="button"
                        @click="toggleSection('kondisi')"
                        class="w-full flex items-center justify-between text-[14px] sm:text-[14.5px] font-bold text-gray-900 mb-3 sm:mb-3.5"
                    >
                        <span>Kondisi</span>
                        <svg
                            class="w-4 h-4 text-gray-400 transition-transform duration-200"
                            :class="openSections.kondisi ? '' : 'rotate-180'"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
                        </svg>
                    </button>

                    <div x-show="openSections.kondisi" class="space-y-3 sm:space-y-3.5 pt-1">
                        <template x-for="cond in [
                            { id: 'all', label: 'Semua Kondisi' },
                            { id: 'seperti-baru', label: 'Seperti Baru' },
                            { id: 'sangat-baik', label: 'Sangat Baik' },
                            { id: 'baik', label: 'Baik' },
                            { id: 'cukup', label: 'Cukup' }
                        ]" :key="cond.id">
                            <label class="flex items-center gap-3 cursor-pointer select-none group text-xs sm:text-[13px] text-gray-600 hover:text-gray-900 py-0.5">
                                <input
                                    type="checkbox"
                                    :checked="cond.id === 'all' ? selectedConditions.includes('all') : selectedConditions.includes(cond.id)"
                                    @change="toggleCondition(cond.id)"
                                    class="sr-only"
                                />
                                <div
                                    class="w-4 h-4 sm:w-4.5 sm:h-4.5 rounded-[4px] border flex items-center justify-center transition-colors shrink-0"
                                    :class="(cond.id === 'all' ? selectedConditions.includes('all') : selectedConditions.includes(cond.id)) ? 'bg-[#4F26A6] border-[#4F26A6]' : 'border-gray-300 bg-white group-hover:border-gray-400'"
                                >
                                    <svg
                                        x-show="cond.id === 'all' ? selectedConditions.includes('all') : selectedConditions.includes(cond.id)"
                                        class="w-3 h-3 text-white"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="3"
                                        viewBox="0 0 24 24"
                                    >
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </div>
                                <span
                                    :class="(cond.id === 'all' ? selectedConditions.includes('all') : selectedConditions.includes(cond.id)) ? 'text-gray-900 font-semibold' : 'text-gray-700 font-medium'"
                                    x-text="cond.label"
                                ></span>
                            </label>
                        </template>
                    </div>
                </div>

                <div class="h-[1px] bg-gray-100 w-full"></div>

                <!-- 4. Lokasi Section (Searchable dropdown) -->
                <div>
                    <button
                        type="button"
                        @click="toggleSection('lokasi')"
                        class="w-full flex items-center justify-between text-[14px] sm:text-[14.5px] font-bold text-gray-900 mb-3 sm:mb-3.5"
                    >
                        <span>Lokasi</span>
                        <svg
                            class="w-4 h-4 text-gray-400 transition-transform duration-200"
                            :class="openSections.lokasi ? '' : 'rotate-180'"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
                        </svg>
                    </button>

                    <div x-show="openSections.lokasi" class="relative pt-1" @click.outside="isLocationDropdownOpen = false">
                        <button
                            type="button"
                            @click="isLocationDropdownOpen = !isLocationDropdownOpen"
                            class="w-full flex items-center justify-between bg-[#FAFAFC] border border-gray-200 rounded-xl px-4 py-3 text-xs sm:text-[13px] text-gray-700 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] cursor-pointer transition-all"
                        >
                            <span :class="selectedLocation ? 'text-gray-900 font-semibold truncate' : 'text-gray-500 truncate'" x-text="selectedLocationName"></span>
                            <svg
                                class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0"
                                :class="isLocationDropdownOpen ? 'rotate-180 text-[#4F26A6]' : ''"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Searchable Dropdown Floating Panel -->
                        <div
                            x-show="isLocationDropdownOpen"
                            class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-100 rounded-2xl shadow-[0_12px_36px_rgba(0,0,0,0.12)] p-2.5 z-50"
                            style="display: none;"
                        >
                            <div class="relative mb-2">
                                <input
                                    type="text"
                                    placeholder="Cari kota atau daerah..."
                                    x-model="locationSearchQuery"
                                    class="w-full h-8 pl-7 pr-2 rounded-lg bg-gray-50 border border-gray-200 text-xs text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#4F26A6] focus:border-[#4F26A6]"
                                />
                                <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8" /><line x1="21" y1="21" x2="16.65" y2="16.65" />
                                </svg>
                            </div>

                            <div class="max-h-48 overflow-y-auto space-y-0.5 divide-y divide-gray-50">
                                <template x-for="loc in filteredLocations" :key="loc.id || 'all'">
                                    <button
                                        type="button"
                                        @click="selectedLocation = loc.id; isLocationDropdownOpen = false; locationSearchQuery = ''"
                                        class="w-full text-left px-2.5 py-1.5 rounded-lg text-xs transition-colors flex items-center justify-between"
                                        :class="selectedLocation === loc.id ? 'bg-[#F4EEFF] text-[#4F26A6] font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-[#4F26A6]'"
                                    >
                                        <span x-text="loc.name"></span>
                                        <svg x-show="selectedLocation === loc.id" class="w-3.5 h-3.5 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-[1px] bg-gray-100 w-full"></div>

                <!-- Reset Filter Button -->
                <div class="pt-2">
                    <button
                        type="button"
                        @click="resetFilters()"
                        class="w-full py-2.5 rounded-xl border border-purple-200/90 text-[#4F26A6] font-bold text-xs sm:text-[13.5px] hover:bg-[#4F26A6] hover:text-white transition-all text-center flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5 text-[#4F26A6] group-hover:text-white transition-colors" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="text-[#4F26A6] group-hover:text-white transition-colors">Reset Filter</span>
                    </button>
                    </aside>
                </div>

                <!-- Mobile & Tablet Filter Drawer (Slide-Over 1:1) -->
                <x-shop.filter-drawer />
            <!-- Right Catalog Area -->
            <div class="flex-1 w-full min-w-0">
                <!-- Breadcrumbs -->
                <nav class="flex items-center gap-2 text-xs text-gray-400 mb-3 sm:mb-3.5 font-medium select-none">
                    <a href="/" class="hover:text-[#4F26A6] transition-colors">Beranda</a>
                    <span>›</span>
                    <a href="/belanja" class="hover:text-[#4F26A6] transition-colors">Kategori</a>
                    <span>›</span>
                    <span class="text-gray-700 font-semibold">Semua Produk</span>
                </nav>

                <!-- Title, Subtitle, and Top Controls -->
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-5 sm:gap-6 pb-6 sm:pb-8 pt-2 sm:pt-3">
                    <div class="space-y-2 sm:space-y-2.5 max-w-xl">
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-[#111827] tracking-tight leading-snug">
                            Semua Produk
                        </h2>
                        <p class="text-[13px] sm:text-[14px] text-gray-600 font-normal leading-relaxed">
                            Temukan berbagai barang pre-loved dari artis, selebgram, dan streamer favoritmu.
                        </p>
                    </div>

                    <div class="flex flex-col items-end gap-2 shrink-0">
                        <span class="text-[11.5px] text-gray-400 font-medium">
                            1.248 barang ditemukan
                        </span>

                        <!-- Controls: Filter Button (Mobile & Tablet), Sorting Dropdown & View Mode Buttons -->
                        <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto justify-between sm:justify-end">
                            <!-- Mobile Filter Trigger Button -->
                            <button
                                type="button"
                                @click="isMobileFilterOpen = true"
                                class="lg:hidden inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-white border border-gray-200 text-xs sm:text-sm font-semibold text-gray-700 shadow-xs hover:border-[#4F26A6] active:bg-gray-50 transition-all shrink-0 cursor-pointer"
                            >
                                <svg class="w-4 h-4 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                <span>Filter</span>
                            </button>

                            <div class="flex items-center gap-2 sm:gap-2.5">
                                <select
                                    x-model="sortBy"
                                    class="appearance-none bg-white border border-gray-200 rounded-xl pl-3.5 pr-8 sm:pl-4 sm:pr-10 py-2 sm:py-2.5 text-xs sm:text-[13px] font-semibold text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] cursor-pointer shadow-xs hover:border-gray-300 transition-all min-w-[140px] sm:min-w-[170px]"
                                >
                                    <option value="terbaru">Urutan: Terbaru</option>
                                    <option value="harga-rendah">Harga Terendah</option>
                                    <option value="harga-tinggi">Harga Tertinggi</option>
                                    <option value="terpopuler">Terpopuler</option>
                                </select>
                                <svg class="w-3.5 h-3.5 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            <!-- Grid vs List View Mode -->
                            <div class="flex items-center bg-white border border-gray-200 rounded-xl p-1 shadow-xs gap-1">
                                <button
                                    type="button"
                                    @click="viewMode = 'grid'"
                                    class="p-1.5 sm:p-2 rounded-lg transition-colors cursor-pointer"
                                    :class="viewMode === 'grid' ? 'bg-[#4F26A6] text-white shadow-xs' : 'text-gray-400 hover:text-gray-700 hover:bg-gray-50'"
                                    title="Tampilan Grid"
                                >
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="7" height="7" rx="1" fill="currentColor" />
                                        <rect x="14" y="3" width="7" height="7" rx="1" fill="currentColor" />
                                        <rect x="14" y="14" width="7" height="7" rx="1" fill="currentColor" />
                                        <rect x="3" y="14" width="7" height="7" rx="1" fill="currentColor" />
                                    </svg>
                                </button>
                                <button
                                    type="button"
                                    @click="viewMode = 'list'"
                                    class="p-1.5 sm:p-2 rounded-lg transition-colors cursor-pointer"
                                    :class="viewMode === 'list' ? 'bg-[#4F26A6] text-white shadow-xs' : 'text-gray-400 hover:text-gray-700 hover:bg-gray-50'"
                                    title="Tampilan List"
                                >
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                        <line x1="8" y1="6" x2="21" y2="6" />
                                        <line x1="8" y1="12" x2="21" y2="12" />
                                        <line x1="8" y1="18" x2="21" y2="18" />
                                        <circle cx="4" cy="6" r="1.5" fill="currentColor" />
                                        <circle cx="4" cy="12" r="1.5" fill="currentColor" />
                                        <circle cx="4" cy="18" r="1.5" fill="currentColor" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4 Columns Grid: 12 Product Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-3.5 lg:gap-4">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div
                            x-data="{ isLiked: false, likesCount: product.likes || 0 }"
                            class="bg-white rounded-2xl border border-gray-100/90 shadow-[0_4px_16px_rgba(0,0,0,0.04)] hover:shadow-[0_10px_26px_rgba(0,0,0,0.08)] hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col group"
                        >
                            <div class="w-full aspect-[4/5] bg-gray-100 overflow-hidden relative flex items-center justify-center">
                                <button
                                    type="button"
                                    @click.prevent.stop="isLiked = !isLiked; likesCount += (isLiked ? 1 : -1)"
                                    class="absolute top-2.5 right-2.5 z-10 w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white/95 backdrop-blur-xs shadow-md flex items-center justify-center transition-transform hover:scale-105 cursor-pointer"
                                    :class="isLiked ? 'text-[#4F26A6]' : 'text-gray-700 hover:text-[#4F26A6]'"
                                    title="Simpan ke Wishlist"
                                >
                                    <svg
                                        class="w-4 h-4 transition-colors"
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
                                <img
                                    :src="product.image"
                                    :alt="product.title"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 z-0"
                                />

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
                                        <template x-if="product.verified">
                                            <x-verified-badge size="sm" class="w-3.5 h-3.5 shrink-0" />
                                        </template>
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
                <!-- Pagination matching 125 total pages -->
                <div class="flex items-center justify-center gap-1.5 sm:gap-2 mt-10 sm:mt-12 select-none">
                    <button
                        type="button"
                        class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-xl border border-gray-200 text-gray-400 hover:bg-gray-50 transition-colors cursor-not-allowed"
                        disabled
                    >
                        &lt;
                    </button>
                    <button
                        type="button"
                        class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-xl font-bold text-xs sm:text-sm bg-[#5022CE] text-white shadow-xs"
                    >
                        1
                    </button>
                    <button
                        type="button"
                        class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-xl font-semibold text-xs sm:text-sm border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors"
                    >
                        2
                    </button>
                    <button
                        type="button"
                        class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-xl font-semibold text-xs sm:text-sm border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors"
                    >
                        3
                    </button>
                    <button
                        type="button"
                        class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-xl font-semibold text-xs sm:text-sm border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors"
                    >
                        4
                    </button>
                    <button
                        type="button"
                        class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-xl font-semibold text-xs sm:text-sm border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors"
                    >
                        5
                    </button>
                    <span class="px-1 text-gray-400 font-bold text-xs">...</span>
                    <button
                        type="button"
                        class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-xl font-semibold text-xs sm:text-sm border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors"
                    >
                        125
                    </button>
                    <button
                        type="button"
                        class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors"
                    >
                        &gt;
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
