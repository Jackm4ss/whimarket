@props([
    'nameProvince' => 'province',
    'nameCity' => 'city',
    'nameDistrict' => 'district',
    'namePostalCode' => 'postal_code',
])

<div class="space-y-3">
    <!-- Searchable Location Auto-Suggest Bar (Tokopedia / Shopee Style) -->
    <div class="relative" @click.outside="showSearchResults = false">
        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider flex items-center justify-between gap-2 flex-wrap">
            <span class="flex items-center gap-1.5 text-[#4F26A6]">
                <svg class="w-3.5 h-3.5 text-[#4F26A6] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span class="truncate">Cari Kota, Kecamatan, atau Kode Pos</span>
            </span>
            <span class="text-[10.5px] font-semibold text-gray-400 normal-case shrink-0">Cepat &amp; Otomatis</span>
        </label>

        <div class="relative">
            <input
                type="text"
                x-model="searchQuery"
                @input="onSearchInput($event)"
                @focus="if(searchResults.length > 0) showSearchResults = true"
                placeholder="Cari kota atau kecamatan..."
                class="w-full rounded-xl border border-gray-200 bg-white pl-10 pr-10 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"
            />
            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <!-- Spinner / Clear button -->
            <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-1">
                <svg x-show="isSearching" x-cloak class="w-4 h-4 animate-spin text-[#4F26A6]" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <button
                    type="button"
                    x-show="searchQuery && !isSearching"
                    x-cloak
                    @click="clearSearch()"
                    class="w-5 h-5 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-400 hover:text-gray-600 flex items-center justify-center text-xs transition-colors cursor-pointer"
                    title="Hapus pencarian"
                >
                    ✕
                </button>
            </div>
        </div>

        <!-- Floating Search Results Dropdown -->
        <div
            x-show="showSearchResults && searchResults.length > 0"
            x-cloak
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-1"
            class="absolute left-0 right-0 top-full mt-1.5 z-50 bg-white border border-gray-200 rounded-2xl shadow-[0_25px_60px_rgba(0,0,0,0.25)] overflow-hidden divide-y divide-gray-100 max-h-64 overflow-y-auto"
        >
            <template x-for="item in searchResults" :key="item.id">
                <button
                    type="button"
                    @click="selectSearchResult(item)"
                    class="w-full text-left px-4 py-3 bg-white hover:bg-[#F3EEFF] transition-colors flex items-start gap-3 cursor-pointer group"
                >
                    <div class="w-7 h-7 rounded-lg bg-[#F3EEFF] text-[#4F26A6] flex items-center justify-center shrink-0 mt-0.5 group-hover:scale-105 transition-transform">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs sm:text-sm font-bold text-gray-900 group-hover:text-[#4F26A6] transition-colors truncate" x-text="item.title"></p>
                        <p class="text-[11.5px] text-gray-500 truncate" x-text="item.subtitle"></p>
                    </div>
                </button>
            </template>
        </div>

        <!-- No Results Notice -->
        <div
            x-show="showSearchResults && !isSearching && searchResults.length === 0 && searchQuery.length >= 2"
            x-cloak
            class="absolute left-0 right-0 top-full mt-1.5 z-50 bg-white border border-gray-100 rounded-2xl shadow-lg p-4 text-center text-xs text-gray-500"
        >
            Tidak menemukan wilayah dengan kata kunci tersebut. Silakan pilih dari dropdown manual di bawah.
        </div>
    </div>

    <!-- Selection Confirmation Pill / Toggle Manual View -->
    <div x-show="selectedProvince" class="p-2.5 sm:p-3 rounded-2xl bg-purple-50/80 border border-purple-200/60 flex items-center justify-between gap-2.5 text-xs">
        <div class="flex items-center gap-2 min-w-0 flex-1">
            <span class="w-5 h-5 rounded-full bg-[#4F26A6] text-white flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </span>
            <div class="text-purple-950 font-bold min-w-0 flex-1">
                <span class="text-purple-700 font-semibold mr-1">Wilayah Terpilih:</span>
                <span class="text-purple-950 font-extrabold">
                    <span x-text="selectedDistrict ? selectedDistrict + ', ' : ''"></span>
                    <span x-text="selectedCity ? selectedCity + ', ' : ''"></span>
                    <span x-text="selectedProvince"></span>
                    <span x-text="selectedPostalCode ? ' (' + selectedPostalCode + ')' : ''"></span>
                </span>
            </div>
        </div>
        <button
            type="button"
            @click="showManualSelection = !showManualSelection"
            class="text-[11px] font-bold text-[#4F26A6] hover:underline shrink-0 cursor-pointer whitespace-nowrap"
            x-text="showManualSelection ? 'Sembunyikan' : 'Ubah / Rincian'"
        ></button>
    </div>

    <!-- Cascading Dropdowns (Always rendered for hidden values, collapsible for cleaner UI) -->
    <div x-show="showManualSelection || !selectedProvince" class="space-y-3 pt-1">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <!-- Provinsi Dropdown -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">
                    Provinsi <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <select
                        @change="onProvinceSelect($event)"
                        required
                        class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none cursor-pointer disabled:bg-gray-50 disabled:text-gray-400"
                        :disabled="loadingProvinces"
                    >
                        <option value="">-- Pilih Provinsi --</option>
                        <template x-for="p in provinces" :key="p.code">
                            <option
                                :value="p.code"
                                :selected="selectedProvinceCode == p.code || selectedProvince.toLowerCase() == p.name.toLowerCase()"
                                x-text="p.name"
                            ></option>
                        </template>
                    </select>
                    <input type="hidden" name="{{ $nameProvince }}" :value="selectedProvince" />
                </div>
            </div>

            <!-- Kota / Kabupaten Dropdown -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">
                    Kota / Kabupaten <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <select
                        @change="onRegencySelect($event)"
                        required
                        class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none cursor-pointer disabled:bg-gray-50 disabled:text-gray-400"
                        :disabled="!selectedProvinceCode || loadingRegencies"
                    >
                        <option
                            value=""
                            x-text="loadingRegencies ? 'Memuat Kota/Kabupaten...' : (!selectedProvinceCode ? 'Pilih Provinsi Dulu' : '-- Pilih Kota / Kabupaten --')"
                        ></option>
                        <template x-for="r in regencies" :key="r.code">
                            <option
                                :value="r.code"
                                :selected="selectedRegencyCode == r.code || selectedCity.toLowerCase() == r.name.toLowerCase()"
                                x-text="r.name"
                            ></option>
                        </template>
                    </select>
                    <input type="hidden" name="{{ $nameCity }}" :value="selectedCity" />
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <!-- Kecamatan Dropdown -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">
                    Kecamatan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <select
                        @change="onDistrictSelect($event)"
                        required
                        class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none cursor-pointer disabled:bg-gray-50 disabled:text-gray-400"
                        :disabled="!selectedRegencyCode || loadingDistricts"
                    >
                        <option
                            value=""
                            x-text="loadingDistricts ? 'Memuat Kecamatan...' : (!selectedRegencyCode ? 'Pilih Kota Dulu' : '-- Pilih Kecamatan --')"
                        ></option>
                        <template x-for="d in districts" :key="d.code">
                            <option
                                :value="d.code"
                                :selected="selectedDistrictCode == d.code || selectedDistrict.toLowerCase() == d.name.toLowerCase()"
                                x-text="d.name"
                            ></option>
                        </template>
                    </select>
                    <input type="hidden" name="{{ $nameDistrict }}" :value="selectedDistrict" />
                </div>
            </div>

            <!-- Kode Pos Dropdown / Auto-filled -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">
                    Kode Pos <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div x-show="villages.length > 0">
                        <select
                            @change="onVillageSelect($event)"
                            class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none cursor-pointer"
                        >
                            <option value="">-- Pilih Kelurahan / Kode Pos --</option>
                            <template x-for="v in villages" :key="v.code">
                                <option
                                    :value="v.postal_code"
                                    :selected="selectedPostalCode == v.postal_code"
                                    x-text="(v.postal_code ? v.postal_code + ' - ' : '') + v.name"
                                ></option>
                            </template>
                        </select>
                    </div>
                    <div x-show="villages.length === 0">
                        <input
                            type="text"
                            :value="selectedPostalCode"
                            @input="selectedPostalCode = $event.target.value"
                            placeholder="Contoh: 12190"
                            class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none disabled:bg-gray-50 disabled:text-gray-400"
                            :disabled="!selectedDistrictCode"
                        />
                    </div>
                    <input type="hidden" name="{{ $namePostalCode }}" :value="selectedPostalCode" />
                </div>
            </div>
        </div>
    </div>
</div>
