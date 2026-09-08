<!-- Mobile & Tablet Filter Drawer: Full-width Bottom Sheet Drawer (<1024px) -->
<div
    id="filter-drawer-overlay"
    x-show="isMobileFilterOpen"
    x-cloak
    x-transition:enter="transition-opacity duration-300 ease-out"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity duration-200 ease-in"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="isMobileFilterOpen = false"
    class="fixed inset-0 bg-black/40 backdrop-blur-xs z-50 lg:hidden flex items-end justify-center p-0"
    style="display: none;"
>
    <div
        @click.stop
        x-show="isMobileFilterOpen"
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
                <h2 class="text-base sm:text-lg font-bold text-gray-900">Filter</h2>
            </div>
            <button
                type="button"
                @click="isMobileFilterOpen = false"
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
                    @click="toggleSection('kategori')"
                    class="w-full flex items-center justify-between text-[14px] sm:text-[14.5px] font-bold text-gray-900 mb-3"
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

            <!-- 2. Harga Section -->
            <div>
                <button
                    type="button"
                    @click="toggleSection('harga')"
                    class="w-full flex items-center justify-between text-[14px] sm:text-[14.5px] font-bold text-gray-900 mb-3"
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
                                :style="`margin-left: ${(priceRange[0] / maxSliderPrice) * 100}%; width: ${Math.max(0, (priceRange[1] - priceRange[0]) / maxSliderPrice) * 100}%;`"
                            ></div>
                        </div>
                        <input
                            type="range"
                            min="0"
                            :max="maxSliderPrice"
                            :step="priceStep"
                            :value="priceRange[0]"
                            @input.debounce.50ms="updateMinPrice($event.target.value)"
                            class="absolute inset-0 w-full appearance-none bg-transparent pointer-events-auto cursor-pointer accent-[#4F26A6] opacity-0 z-20"
                        />
                        <input
                            type="range"
                            min="0"
                            :max="maxSliderPrice"
                            :step="priceStep"
                            :value="priceRange[1]"
                            @input.debounce.50ms="updateMaxPrice($event.target.value)"
                            class="absolute inset-0 w-full appearance-none bg-transparent pointer-events-auto cursor-pointer accent-[#4F26A6] opacity-0 z-30"
                        />
                        <div
                            class="absolute w-4 h-4 bg-[#4F26A6] rounded-full ring-2 ring-white shadow-md pointer-events-none -translate-x-1/2 z-10"
                            :style="`left: ${(priceRange[0] / maxSliderPrice) * 100}%;`"
                        ></div>
                        <div
                            class="absolute w-4 h-4 bg-[#4F26A6] rounded-full ring-2 ring-white shadow-md pointer-events-none -translate-x-1/2 z-10"
                            :style="`left: ${(priceRange[1] / maxSliderPrice) * 100}%;`"
                        ></div>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-500 font-semibold pt-0.5">
                        <span x-text="formatRupiah(priceRange[0])"></span>
                        <span x-text="formatRupiah(priceRange[1])"></span>
                    </div>
                </div>
            </div>

            <div class="h-[1px] bg-gray-100 w-full"></div>

            <!-- 3. Kondisi Section -->
            <div>
                <button
                    type="button"
                    @click="toggleSection('kondisi')"
                    class="w-full flex items-center justify-between text-[14px] sm:text-[14.5px] font-bold text-gray-900 mb-3"
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

                <div x-show="openSections.kondisi" class="space-y-3 pt-1">
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

            <!-- 4. Lokasi Section -->
            <div>
                <button
                    type="button"
                    @click="toggleSection('lokasi')"
                    class="w-full flex items-center justify-between text-[14px] sm:text-[14.5px] font-bold text-gray-900 mb-3"
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
        </div>

        <!-- Drawer Action Footer (Pinned Bottom) -->
        <div class="p-5 sm:p-7 pt-3 border-t border-gray-100 flex flex-col gap-2.5 shrink-0 bg-white">
            <button
                type="button"
                @click="applyFilters()"
                class="w-full py-3 rounded-xl text-center font-bold text-white bg-[#4F26A6] hover:bg-[#3E1D85] shadow-md shadow-[#4F26A6]/20 transition-all text-sm cursor-pointer"
            >
                Terapkan Filter
            </button>
            <button
                type="button"
                @click="resetFilters()"
                class="btn-reset-filter w-full py-2.5 rounded-xl text-center font-bold text-xs sm:text-[13px] cursor-pointer shadow-2xs"
            >
                Reset Filter
            </button>
        </div>
    </div>
</div>
