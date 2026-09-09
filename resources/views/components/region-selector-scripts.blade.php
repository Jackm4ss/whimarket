<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('regionSelectorComponent', (initialData = {}) => ({
            provinces: [],
            regencies: [],
            districts: [],
            villages: [],
            postalCodes: [],

            selectedProvince: initialData.province || '',
            selectedProvinceCode: '',
            selectedCity: initialData.city || '',
            selectedRegencyCode: '',
            selectedDistrict: initialData.district || '',
            selectedDistrictCode: '',
            selectedPostalCode: initialData.postal_code || '',

            // Instant location search state (Tokopedia / Shopee style)
            searchQuery: '',
            searchResults: [],
            isSearching: false,
            showSearchResults: false,
            showManualSelection: !initialData.province,
            searchDebounceTimer: null,

            loadingProvinces: false,
            loadingRegencies: false,
            loadingDistricts: false,
            loadingVillages: false,

            async init() {
                await this.loadProvinces();
                if (this.selectedProvince) {
                    await this.matchAndLoadProvince(this.selectedProvince);
                    if (this.selectedDistrict && this.selectedCity) {
                        this.searchQuery = (this.selectedDistrict ? 'Kec. ' + this.selectedDistrict + ', ' : '') + this.selectedCity + ', ' + this.selectedProvince + (this.selectedPostalCode ? ' (' + this.selectedPostalCode + ')' : '');
                    }
                }
            },

            // Instant search handler with 250ms debounce
            onSearchInput(e) {
                const query = e.target.value ? e.target.value.trim() : '';
                this.searchQuery = e.target.value;

                if (this.searchDebounceTimer) {
                    clearTimeout(this.searchDebounceTimer);
                }

                if (query.length < 2) {
                    this.searchResults = [];
                    this.showSearchResults = false;
                    this.isSearching = false;
                    return;
                }

                this.isSearching = true;

                this.searchDebounceTimer = setTimeout(async () => {
                    try {
                        const res = await fetch('/api/regions/search?q=' + encodeURIComponent(query));
                        const json = await res.json();
                        if (json.success && Array.isArray(json.data)) {
                            this.searchResults = json.data;
                            this.showSearchResults = true;
                        } else {
                            this.searchResults = [];
                        }
                    } catch (err) {
                        console.error('Error saat mencari wilayah:', err);
                        this.searchResults = [];
                    } finally {
                        this.isSearching = false;
                    }
                }, 250);
            },

            // Select item from instant search suggestions
            async selectSearchResult(item) {
                this.selectedProvince = item.province || '';
                this.selectedProvinceCode = item.province_code || '';
                this.selectedCity = item.city || '';
                this.selectedRegencyCode = item.regency_code || '';
                this.selectedDistrict = item.district || '';
                this.selectedDistrictCode = item.district_code || '';
                this.selectedPostalCode = item.postal_code || '';

                this.searchQuery = item.title ? (item.title + (item.subtitle ? ', ' + item.subtitle : '')) : '';
                this.showSearchResults = false;

                // Load regencies and districts in background so dropdowns stay in sync if expanded
                if (this.selectedProvinceCode) {
                    await this.loadRegencies(this.selectedProvinceCode);
                    if (this.selectedRegencyCode) {
                        await this.loadDistricts(this.selectedRegencyCode);
                        if (this.selectedDistrictCode) {
                            await this.loadVillages(this.selectedDistrictCode);
                        }
                    }
                }

                // Dispatch event for parent (e.g. checkout shipping rate update)
                this.$dispatch('province-selected', {
                    code: this.selectedProvinceCode,
                    name: this.selectedProvince
                });
            },

            clearSearch() {
                this.searchQuery = '';
                this.searchResults = [];
                this.showSearchResults = false;
            },

            async loadProvinces() {
                this.loadingProvinces = true;
                try {
                    const res = await fetch('/api/regions/provinces');
                    const json = await res.json();
                    if (json.success && Array.isArray(json.data)) {
                        this.provinces = json.data;
                    }
                } catch (e) {
                    console.error('Gagal memuat provinsi:', e);
                } finally {
                    this.loadingProvinces = false;
                }
            },

            async matchAndLoadProvince(nameOrCode) {
                const target = String(nameOrCode).toLowerCase().trim();
                const found = this.provinces.find(p => p.code === target || p.name.toLowerCase() === target || p.name.toLowerCase().includes(target) || target.includes(p.name.toLowerCase()));
                if (found) {
                    this.selectedProvinceCode = found.code;
                    this.selectedProvince = found.name;
                    await this.loadRegencies(found.code);
                    if (this.selectedCity) {
                        await this.matchAndLoadRegency(this.selectedCity);
                    }
                }
            },

            async onProvinceSelect(e) {
                const code = e.target.value;
                this.selectedProvinceCode = code;
                const found = this.provinces.find(p => p.code === code);
                this.selectedProvince = found ? found.name : '';

                // Reset child selections
                this.selectedCity = '';
                this.selectedRegencyCode = '';
                this.regencies = [];
                this.selectedDistrict = '';
                this.selectedDistrictCode = '';
                this.districts = [];
                this.selectedPostalCode = '';
                this.villages = [];
                this.postalCodes = [];

                if (code) {
                    await this.loadRegencies(code);
                }

                // Dispatch event for parent components (e.g. checkout shipping rate update)
                this.$dispatch('province-selected', {
                    code: this.selectedProvinceCode,
                    name: this.selectedProvince
                });
            },

            async loadRegencies(provinceCode) {
                this.loadingRegencies = true;
                try {
                    const res = await fetch('/api/regions/regencies?province_code=' + encodeURIComponent(provinceCode));
                    const json = await res.json();
                    if (json.success && Array.isArray(json.data)) {
                        this.regencies = json.data;
                    }
                } catch (e) {
                    console.error('Gagal memuat kota/kabupaten:', e);
                } finally {
                    this.loadingRegencies = false;
                }
            },

            async matchAndLoadRegency(nameOrCode) {
                const target = String(nameOrCode).toLowerCase().trim();
                const found = this.regencies.find(r => r.code === target || r.name.toLowerCase() === target || r.name.toLowerCase().includes(target) || target.includes(r.name.toLowerCase()));
                if (found) {
                    this.selectedRegencyCode = found.code;
                    this.selectedCity = found.name;
                    await this.loadDistricts(found.code);
                    if (this.selectedDistrict) {
                        await this.matchAndLoadDistrict(this.selectedDistrict);
                    }
                }
            },

            async onRegencySelect(e) {
                const code = e.target.value;
                this.selectedRegencyCode = code;
                const found = this.regencies.find(r => r.code === code);
                this.selectedCity = found ? found.name : '';

                // Reset child selections
                this.selectedDistrict = '';
                this.selectedDistrictCode = '';
                this.districts = [];
                this.selectedPostalCode = '';
                this.villages = [];
                this.postalCodes = [];

                if (code) {
                    await this.loadDistricts(code);
                }
            },

            async loadDistricts(regencyCode) {
                this.loadingDistricts = true;
                try {
                    const res = await fetch('/api/regions/districts?regency_code=' + encodeURIComponent(regencyCode));
                    const json = await res.json();
                    if (json.success && Array.isArray(json.data)) {
                        this.districts = json.data;
                    }
                } catch (e) {
                    console.error('Gagal memuat kecamatan:', e);
                } finally {
                    this.loadingDistricts = false;
                }
            },

            async matchAndLoadDistrict(nameOrCode) {
                const target = String(nameOrCode).toLowerCase().trim();
                const found = this.districts.find(d => d.code === target || d.name.toLowerCase() === target || d.name.toLowerCase().includes(target) || target.includes(d.name.toLowerCase()));
                if (found) {
                    this.selectedDistrictCode = found.code;
                    this.selectedDistrict = found.name;
                    await this.loadVillages(found.code);
                }
            },

            async onDistrictSelect(e) {
                const code = e.target.value;
                this.selectedDistrictCode = code;
                const found = this.districts.find(d => d.code === code);
                this.selectedDistrict = found ? found.name : '';

                this.selectedPostalCode = '';
                this.villages = [];
                this.postalCodes = [];

                if (code) {
                    await this.loadVillages(code);
                }
            },

            async loadVillages(districtCode) {
                this.loadingVillages = true;
                try {
                    const res = await fetch('/api/regions/villages?district_code=' + encodeURIComponent(districtCode));
                    const json = await res.json();
                    if (json.success && Array.isArray(json.data)) {
                        this.villages = json.data;
                        this.postalCodes = json.postal_codes || [];
                        if (this.postalCodes.length === 1 && !this.selectedPostalCode) {
                            this.selectedPostalCode = String(this.postalCodes[0]);
                        }
                    }
                } catch (e) {
                    console.error('Gagal memuat kelurahan/desa:', e);
                } finally {
                    this.loadingVillages = false;
                }
            },

            onVillageSelect(e) {
                const val = e.target.value;
                if (val) {
                    this.selectedPostalCode = val;
                }
            }
        }));
    });
</script>
