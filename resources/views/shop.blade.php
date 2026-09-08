@php
    $defaultCatalog = \App\Support\MarketData::shopProducts();
    $displayProducts = !empty($productsList) ? $productsList : $defaultCatalog;
@endphp
<x-layouts.app
    title="WhiMarket - Marketplace Pre-loved & Merchandise"
    activeTab="belanja"
    :wishlistCount="0"
    :cartCount="0"
    :user="null"
>
    <!-- Top Purple Hero Banner -->
    <x-shop.shop-banner />

    <!-- Catalog Container with Alpine filter state -->
    <div
        class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-2 pb-16"
        x-data="{
            allProducts: @js($displayProducts),
            searchQuery: '{{ request('q') ?? '' }}',
            selectedCategory: '{{ $initialCategory ?? 'all' }}',
            priceRange: [0, 50000000],
            selectedConditions: ['all'],
            sortBy: '{{ request('sort', 'terbaru') }}',
            sortDropdownOpen: false,
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
                    if (this.searchQuery) {
                        const sq = this.searchQuery.toLowerCase();
                        const matchTitle = (item.title || '').toLowerCase().includes(sq);
                        const matchSeller = (item.sellerName || '').toLowerCase().includes(sq);
                        if (!matchTitle && !matchSeller) return false;
                    }
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
                this.searchQuery = '';
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
                        class="btn-reset-filter w-full py-2.5 rounded-xl font-bold text-xs sm:text-[13.5px] text-center flex items-center justify-center gap-2 cursor-pointer shadow-2xs"
                    >
                        <svg class="w-3.5 h-3.5 text-current transition-colors" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="text-current transition-colors">Reset Filter</span>
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
                    <span class="text-gray-700 font-semibold" x-text="selectedCategory === 'all' ? 'Semua Produk' : (selectedCategory.charAt(0).toUpperCase() + selectedCategory.slice(1))"></span>
                </nav>

                <!-- Title, Subtitle, and Top Controls -->
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-5 sm:gap-6 pb-6 sm:pb-8 pt-2 sm:pt-3">
                    <div class="space-y-2 sm:space-y-2.5 max-w-xl">
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-[#111827] tracking-tight leading-snug" x-text="selectedCategory === 'all' ? 'Semua Produk' : (selectedCategory === 'tas' ? 'Tas & Aksesoris' : (selectedCategory === 'hobi' ? 'Hobi & Koleksi' : (selectedCategory.charAt(0).toUpperCase() + selectedCategory.slice(1))))">
                        </h2>
                        <p class="text-[13px] sm:text-[14px] text-gray-600 font-normal leading-relaxed">
                            Temukan berbagai barang pre-loved dari artis, selebgram, dan streamer favoritmu.
                        </p>
                        <template x-if="searchQuery">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-xl bg-[#F3EEFF] text-[#4F26A6] text-xs font-bold">
                                <span>Hasil pencarian: "<span x-text="searchQuery"></span>"</span>
                                <a href="{{ route('shop') }}" class="text-gray-400 hover:text-gray-700 cursor-pointer ml-1" title="Hapus filter pencarian">✕</a>
                            </div>
                        </template>
                    </div>
                    <div class="flex flex-col items-end gap-2 shrink-0">
                        <span class="text-[11.5px] text-gray-400 font-medium" x-text="filteredProducts.length + ' barang ditemukan'">
                        </span>

                        <!-- Controls: Filter Button (Mobile & Tablet), Sorting Dropdown & View Mode Buttons -->
                        <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto justify-between sm:justify-end">
                            <!-- Mobile Filter Trigger Button (Left) -->
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

                            <!-- Right Controls Cluster (Sort + View Mode) -->
                            <div class="flex items-center gap-2 sm:gap-2.5 shrink-0">
                            <x-sort-dropdown :options="[
                                'terbaru' => 'Urutan: Terbaru',
                                'harga-rendah' => 'Harga Terendah',
                                'harga-tinggi' => 'Harga Tertinggi',
                                'terpopuler' => 'Terpopuler',
                            ]" />
                            </div>

                            <!-- Grid vs List View Mode (Hidden on mobile & tablet, visible on desktop lg) -->
                            <div class="hidden lg:flex items-center bg-white border border-gray-200 rounded-xl p-1 shadow-xs gap-1">
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
