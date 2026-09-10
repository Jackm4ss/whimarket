<x-layouts.app :title="$title" activeTab="checkout">
    <script>
    window.checkoutConfig = {
        addresses: @json($addressesData),
        selectedAddressId: {{ $defaultAddress->id ?? 'null' }},
        subtotal: {{ (float) $subtotal }},
        adminFee: {{ (float) $adminFee }},
        defaultShippingCost: {{ (int) $shippingCost }},
        defaultZone: @json($defaultZone),
        defaultEtd: @json($defaultEtd),
        userName: @json(auth()->user()->name),
        userPhone: @json(auth()->user()->phone ?? ''),
        onboardingUrl: @json(route('onboarding.address')),
        csrfToken: @json(csrf_token())
    };

    function extractPhoneDigits(raw) {
        if (!raw) return '';
        var digits = String(raw).replace(/[^0-9]/g, '');
        if (digits.startsWith('62')) {
            return digits.substring(2);
        }
        if (digits.startsWith('0')) {
            return digits.substring(1);
        }
        return digits;
    }

    window.checkoutManager = function(config) {
        config = config || window.checkoutConfig || {};
        return {
            addressModalOpen: false,
            newAddressModalOpen: false,
            openedFromAddressModal: false,
            savingAddress: false,
            saveError: '',
            addresses: config.addresses || [],
            selectedAddressId: config.selectedAddressId || null,
            subtotal: Number(config.subtotal),
            adminFee: Number(config.adminFee || 0),
            defaultShippingCost: Number(config.defaultShippingCost || 0),
            defaultZone: config.defaultZone || '',
            defaultEtd: config.defaultEtd || '',
            newAddress: {
                recipient_name: config.userName || '',
                phoneDisplay: extractPhoneDigits(config.userPhone || ''),
                phone: '',
                province: '',
                city: '',
                district: '',
                postal_code: '',
                full_address: '',
            },
            get fullPhone() {
                var cleaned = (this.newAddress.phoneDisplay || '').replace(/[^0-9]/g, '');
                return cleaned.length > 0 ? '+62' + cleaned : '';
            },
            handlePhoneInput(e) {
                var val = e.target.value.replace(/[^0-9+]/g, '');
                if (val.startsWith('+62')) {
                    val = val.substring(3);
                } else if (val.startsWith('62')) {
                    val = val.substring(2);
                } else if (val.startsWith('0')) {
                    val = val.substring(1);
                }
                val = val.replace(/[^0-9]/g, '');
                this.newAddress.phoneDisplay = val;
                e.target.value = val;
            },
            clearPhone() {
                this.newAddress.phoneDisplay = '';
                if (this.$refs.checkoutPhoneInput) {
                    this.$refs.checkoutPhoneInput.value = '';
                    this.$refs.checkoutPhoneInput.focus();
                }
            },
            get selectedAddress() {
                if (!this.selectedAddressId) return null;
                return this.addresses.find(a => a.id === this.selectedAddressId) || null;
            },
            get hasAddress() {
                return !!this.selectedAddress;
            },
            get currentShippingCost() {
                return this.selectedAddress ? Number(this.selectedAddress.shipping_cost) : 0;
            },
            get currentShippingZone() {
                return this.selectedAddress ? this.selectedAddress.shipping_zone : '';
            },
            get currentShippingEtd() {
                return this.selectedAddress ? this.selectedAddress.shipping_etd : '';
            },
            get grandTotal() {
                return this.subtotal + this.currentShippingCost + this.adminFee;
            },
            selectAddress(addr) {
                this.selectedAddressId = addr.id;
                this.addressModalOpen = false;
            },
            openAddAddress() {
                this.openedFromAddressModal = this.addressModalOpen;
                this.addressModalOpen = false;
                setTimeout(() => {
                    this.newAddressModalOpen = true;
                    this.saveError = '';
                }, 50);
            },
            closeNewAddressModal() {
                this.newAddressModalOpen = false;
                if (this.openedFromAddressModal && this.addresses.length > 0) {
                    setTimeout(() => {
                        this.addressModalOpen = true;
                    }, 50);
                }
            },
            openAddressSelector() {
                if (this.addresses.length > 0) {
                    this.addressModalOpen = true;
                } else {
                    this.openAddAddress();
                }
            },
            async saveNewAddress() {
                this.saveError = '';
                const modal = document.getElementById('modal-new-address');
                const prov = modal ? (modal.querySelector('input[name="province"]')?.value || '') : '';
                const city = modal ? (modal.querySelector('input[name="city"]')?.value || '') : '';
                const dist = modal ? (modal.querySelector('input[name="district"]')?.value || '') : '';
                const post = modal ? (modal.querySelector('input[name="postal_code"]')?.value || '') : '';

                this.newAddress.province = prov || this.newAddress.province;
                this.newAddress.city = city || this.newAddress.city;
                this.newAddress.district = dist || this.newAddress.district;
                this.newAddress.postal_code = post || this.newAddress.postal_code;

                this.newAddress.phone = this.fullPhone;

                if (!this.newAddress.recipient_name || !this.newAddress.phone || !this.newAddress.full_address || !this.newAddress.province || !this.newAddress.city) {
                    this.saveError = 'Lengkapi data nama penerima, no. handphone, alamat lengkap, dan pilih kota/provinsi.';
                    return;
                }
                if ((this.newAddress.phoneDisplay || '').length < 8) {
                    this.saveError = 'Nomor handphone minimal 8 digit.';
                    return;
                }
                this.savingAddress = true;
                try {
                    const res = await fetch(config.onboardingUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': config.csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(this.newAddress)
                    });
                    const data = await res.json();
                    if (res.ok && data.success) {
                        window.location.reload();
                    } else {
                        this.saveError = data.message || 'Gagal menyimpan alamat. Periksa kelengkapan data.';
                    }
                } catch (e) {
                    this.saveError = 'Terjadi kesalahan koneksi saat menyimpan alamat.';
                } finally {
                    this.savingAddress = false;
                }
            },
            formatRupiah(num) {
                return 'Rp ' + Number(num).toLocaleString('id-ID');
            }
        };
    };
    if (window.Alpine) {
        Alpine.data('checkoutManager', () => window.checkoutManager(window.checkoutConfig));
    } else {
        document.addEventListener('alpine:init', () => {
            Alpine.data('checkoutManager', () => window.checkoutManager(window.checkoutConfig));
        });
    }
    </script>
    <div
        class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10"
        x-data="checkoutManager(window.checkoutConfig)"
    >
        <!-- Header -->
        <div class="flex items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
            <div>
                <a href="{{ route('cart.index') }}" class="text-xs sm:text-sm font-bold text-[#4F26A6] hover:underline mb-1 inline-block">
                    &larr; Kembali ke Keranjang
                </a>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-950 tracking-tight">
                    Checkout Pesanan
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Periksa alamat tujuan pengiriman dan rincian barang sebelum melanjutkan transfer.
                </p>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            @csrf

            <!-- Left: Address & Items (8 cols) -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Address Card -->
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-xl bg-[#F3EEFF] text-[#4F26A6] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </span>
                            <h3 class="text-base sm:text-lg font-extrabold text-gray-900 tracking-tight">Alamat Pengiriman</h3>
                        </div>
                        <button
                            type="button"
                            @click.stop="openAddressSelector()"
                            class="text-xs sm:text-sm font-bold text-[#4F26A6] hover:underline cursor-pointer flex items-center gap-1"
                        >
                            <span x-text="hasAddress ? 'Ganti Alamat &rarr;' : '+ Tambah Alamat'"></span>
                        </button>
                    </div>

                    <!-- State 1: When Address is Selected -->
                    <template x-if="hasAddress">
                        <div class="p-4 sm:p-5 rounded-2xl bg-[#FAF9FC] border border-gray-100 flex items-start justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-bold text-gray-900" x-text="selectedAddress.recipient_name"></span>
                                    <span class="text-xs text-gray-400 font-medium" x-text="'(' + selectedAddress.phone + ')'"></span>
                                    <template x-if="selectedAddress.is_default">
                                        <span class="px-2 py-0.5 rounded-md bg-[#F3EEFF] text-[#4F26A6] text-[10px] font-bold">Utama</span>
                                    </template>
                                </div>
                                <p class="text-xs text-gray-600 leading-relaxed max-w-xl">
                                    <span x-text="selectedAddress.full_address"></span>,
                                    <span x-text="selectedAddress.district ? 'Kec. ' + selectedAddress.district + ', ' : ''"></span>
                                    <span x-text="selectedAddress.city + ', ' + selectedAddress.province + ' ' + selectedAddress.postal_code"></span>
                                </p>
                            </div>
                            <button
                                type="button"
                                @click.stop="openAddressSelector()"
                                class="hidden sm:inline-flex text-xs font-bold text-[#4F26A6] hover:bg-purple-50 px-3 py-1.5 rounded-xl transition-colors cursor-pointer border border-[#4F26A6]/20"
                            >
                                Ubah
                            </button>
                        </div>
                    </template>

                    <!-- State 2: When NO Address is Selected -->
                    <template x-if="!hasAddress">
                        <div class="p-6 sm:p-7 rounded-2xl bg-amber-50/30 border-2 border-dashed border-amber-200/80 text-center flex flex-col items-center justify-center">
                            <div class="w-12 h-12 rounded-2xl bg-amber-100/80 text-amber-700 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <h4 class="text-sm font-bold text-gray-900 mb-1">Belum Ada Alamat Pengiriman</h4>
                            <p class="text-xs text-gray-500 max-w-sm mb-4">
                                Masukkan alamat tujuan pengiriman agar kurir dan ongkos kirim ke lokasi tujuanmu dapat dihitung.
                            </p>
                            <button
                                type="button"
                                @click.stop="openAddressSelector()"
                                class="px-5 py-2.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs shadow-md shadow-[#4F26A6]/20 transition-all active:scale-[0.98] cursor-pointer flex items-center gap-1.5"
                            >
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                                <span>Pilih / Tambah Alamat</span>
                            </button>
                        </div>
                    </template>

                    <input type="hidden" name="address_id" :value="selectedAddressId || ''" />
                </div>


                <!-- Review Items Card -->
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8 space-y-6">
                    <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Rincian Barang yang Dipesan</h3>

                    <div class="divide-y divide-gray-100 space-y-4">
                        @foreach($items as $item)
                            <div class="pt-4 first:pt-0 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3.5">
                                    <img
                                        src="{{ $item->variant->product->primary_image_url }}"
                                        alt="{{ $item->variant->product->name }}"
                                        class="w-16 h-16 rounded-2xl object-cover border border-gray-100 bg-gray-50 shrink-0"
                                    />
                                    <div>
                                        <h4 class="text-sm sm:text-[15px] font-bold text-gray-900">
                                            {{ $item->variant->product->name }}
                                        </h4>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            Seller: <strong class="text-gray-700">{{ $item->variant->product->seller->store_name }}</strong>
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            Varian: <span class="font-semibold text-gray-800">{{ $item->variant->name }}</span> • {{ $item->quantity }} pcs
                                        </p>
                                    </div>
                                </div>

                                <div class="text-right shrink-0">
                                    <span class="text-sm sm:text-base font-extrabold text-[#4F26A6] block">
                                        Rp {{ number_format((float)$item->variant->price * $item->quantity, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[11px] text-gray-400">@ Rp {{ number_format((float)$item->variant->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Shipping Courier Option (Single Official Option) -->
                    <div class="pt-5 border-t border-gray-100">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2.5">Opsi Pengiriman</label>

                        <!-- State 1: When Address is Selected -->
                        <template x-if="hasAddress">
                            <div class="p-4 rounded-2xl border-2 border-[#4F26A6] bg-[#F3EEFF]/25 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-[#F3EEFF] text-[#4F26A6] flex items-center justify-center shrink-0 mt-0.5">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-sm font-bold text-gray-900">Pengiriman Standar / Reguler</span>
                                            <span class="px-2 py-0.5 rounded-md bg-[#4F26A6] text-white text-[10px] font-extrabold uppercase tracking-wide">Pilihan Resmi</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-0.5" x-text="currentShippingZone + ' • Estimasi ' + currentShippingEtd"></p>
                                        <p class="text-[11.5px] text-gray-600 italic mt-1 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-[#4F26A6] shrink-0 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>Catatan: Dikirim menggunakan ekspedisi terpercaya (J&amp;T / JNE / SiCepat).</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-left sm:text-right sm:self-center pl-12 sm:pl-0">
                                    <span class="text-base sm:text-lg font-black text-[#4F26A6]" x-text="formatRupiah(currentShippingCost)"></span>
                                </div>
                            </div>
                        </template>

                        <!-- State 2: When Address is NOT Selected (Standard E-commerce empty state) -->
                        <template x-if="!hasAddress">
                            <div class="p-4 rounded-2xl border border-dashed border-gray-200 bg-gray-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gray-100 text-gray-400 flex items-center justify-center shrink-0 mt-0.5">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-sm font-bold text-gray-700">Pengiriman Belum Dipilih</span>
                                            <span class="px-2 py-0.5 rounded-md bg-gray-200 text-gray-600 text-[10px] font-bold uppercase tracking-wide">Menunggu Alamat</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            Pilih atau tambahkan alamat pengiriman terlebih dahulu untuk melihat opsi kurir dan ongkos kirim.
                                        </p>
                                    </div>
                                </div>
                                <div class="text-left sm:text-right sm:self-center pl-12 sm:pl-0">
                                    <button
                                        type="button"
                                        @click.stop="openAddressSelector()"
                                        class="px-3.5 py-1.5 rounded-xl bg-purple-50 hover:bg-purple-100 text-[#4F26A6] font-bold text-xs transition-colors cursor-pointer inline-flex items-center gap-1"
                                    >
                                        <span>Pilih Alamat</span>
                                        <span>&rarr;</span>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Right: Order Summary Sticky (4 cols) -->
            <div class="lg:col-span-4 sticky top-24 space-y-6">
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-6 sm:p-7">
                    <h3 class="text-lg sm:text-xl font-extrabold text-gray-900 tracking-tight mb-5">
                        Ringkasan Pembayaran
                    </h3>

                    <div class="space-y-3 text-sm text-gray-600 pb-5 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <span>Subtotal Produk ({{ $items->sum('quantity') }} pcs)</span>
                            <span class="font-bold text-gray-900">Rp {{ number_format((float)$subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Ongkos Kirim</span>
                            <template x-if="hasAddress">
                                <span class="font-bold text-gray-900" x-text="formatRupiah(currentShippingCost)"></span>
                            </template>
                            <template x-if="!hasAddress">
                                <span class="text-xs font-semibold text-gray-400 bg-gray-100 px-2 py-0.5 rounded-md">Pilih Alamat</span>
                            </template>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span>Biaya Layanan</span>
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-[#4F26A6]">Fee Admin</span>
                            </div>
                            <span class="font-bold text-gray-900" x-text="adminFee > 0 ? formatRupiah(adminFee) : 'Gratis'"></span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-emerald-700 font-semibold bg-emerald-50/60 p-2 rounded-xl">
                            <span>Biaya Proteksi Escrow</span>
                            <span>Gratis</span>
                        </div>
                    </div>

                    <!-- Escrow Trust Note -->
                    <div class="my-4 p-3.5 rounded-2xl bg-purple-50/70 border border-purple-200/50 flex items-center gap-2.5">
                        <x-verified-badge size="sm" class="w-4 h-4 text-[#4F26A6] shrink-0" />
                        <p class="text-[11.5px] text-purple-950 font-medium leading-tight">
                            Dana ditahan aman di Rekening Bersama Escrow WhiMarket sampai kamu menerima &amp; memeriksa barang.
                        </p>
                    </div>

                    <!-- Grand Total -->
                    <div class="pt-1 mb-6">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-gray-700">Total Pembayaran:</span>
                            <span class="text-xl sm:text-2xl font-black text-[#4F26A6] tracking-tight" x-text="formatRupiah(grandTotal)"></span>
                        </div>
                        <template x-if="!hasAddress">
                            <p class="text-[11px] text-gray-400 text-right mt-0.5 italic">
                                *Belum termasuk ongkos kirim
                            </p>
                        </template>
                    </div>

                    <!-- Submit / Action Button -->
                    <template x-if="hasAddress">
                        <button
                            type="submit"
                            class="w-full py-4 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-sm sm:text-[15px] shadow-lg shadow-[#4F26A6]/25 transition-all active:scale-[0.98] cursor-pointer text-center"
                        >
                            Buat Pesanan &amp; Bayar Sekarang &rarr;
                        </button>
                    </template>
                    <template x-if="!hasAddress">
                        <button
                            type="button"
                            @click.stop="openAddressSelector()"
                            class="w-full py-4 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold text-sm sm:text-[15px] transition-all active:scale-[0.98] cursor-pointer text-center flex items-center justify-center gap-2"
                        >
                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
                            <span>Pilih / Tambah Alamat Pengiriman</span>
                        </button>
                    </template>
                </div>
            </div>
        </form>

        <!-- Address Selection Modal -->
        <div
            x-show="addressModalOpen && !newAddressModalOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[80] flex items-center justify-center p-4 py-6 bg-black/60 backdrop-blur-xs overflow-y-auto"
        >
            <div
                @click.outside="addressModalOpen = false"
                style="max-height: 90dvh;"
                class="bg-white rounded-3xl border border-gray-100 shadow-[0_25px_60px_rgba(79,38,166,0.2)] max-w-lg w-full p-4 sm:p-7 relative flex flex-col min-h-0 overflow-hidden my-auto"
            >
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#4F26A6] flex items-center justify-center">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" /><circle cx="12" cy="10" r="3" /></svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-extrabold text-gray-900">Pilih Alamat Pengiriman</h3>
                    </div>
                    <button type="button" @click="addressModalOpen = false" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition-colors cursor-pointer" title="Tutup">✕</button>
                </div>

                <div class="space-y-3 flex-1 min-h-0 overflow-y-auto pr-1 overscroll-contain">
                    @forelse($addressesData as $addr)
                        <div
                            @click="selectAddress({{ Js::from($addr) }})"
                            class="p-4 rounded-2xl border transition-all cursor-pointer"
                            :class="selectedAddressId === {{ $addr['id'] }} ? 'border-2 border-[#4F26A6] bg-[#F3EEFF]/20 shadow-2xs' : 'border-gray-200 hover:border-purple-200 bg-white'"
                        >
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2.5">
                                    <div
                                        class="w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0 transition-colors"
                                        :class="selectedAddressId === {{ $addr['id'] }} ? 'border-[#4F26A6] bg-[#4F26A6]' : 'border-gray-300 bg-white'"
                                    >
                                        <div class="w-1.5 h-1.5 rounded-full bg-white" x-show="selectedAddressId === {{ $addr['id'] }}"></div>
                                    </div>
                                    <span class="font-bold text-sm text-gray-900">{{ $addr['recipient_name'] }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded-md bg-purple-50 text-[#4F26A6] text-[10.5px] font-bold">
                                        Ongkir: Rp {{ number_format($addr['shipping_cost'], 0, ',', '.') }}
                                    </span>
                                    @if($addr['is_default'])
                                        <span class="px-2 py-0.5 rounded-md bg-[#F3EEFF] text-[#4F26A6] text-[10.5px] font-extrabold">Utama</span>
                                    @endif
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mb-1 ml-6.5">{{ $addr['phone'] }}</p>
                            <p class="text-xs text-gray-700 leading-relaxed ml-6.5">{{ $addr['full_address'] }}, {{ $addr['district'] ? 'Kec. ' . $addr['district'] . ', ' : '' }}{{ $addr['city'] }}, {{ $addr['province'] }} {{ $addr['postal_code'] }}</p>
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-400 text-xs">
                            Belum ada alamat tersimpan. Silakan tambahkan alamat baru.
                        </div>
                    @endforelse
                </div>
                <div class="pt-4 mt-4 border-t border-gray-100 flex items-center justify-between shrink-0">
                    <button
                        type="button"
                        @click.stop="openAddAddress()"
                        class="text-xs font-bold text-[#4F26A6] hover:text-[#3E1D85] transition-colors cursor-pointer flex items-center gap-1"
                    >
                        <span>+ Tambah Alamat Baru</span>
                    </button>
                    <button
                        type="button"
                        @click="addressModalOpen = false"
                        class="px-5 py-2.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-xs font-bold text-white transition-all cursor-pointer shadow-xs"
                    >
                        Gunakan Alamat
                    </button>
                </div>
            </div>
        </div>

        <!-- Add New Address Modal -->
        <div
            x-show="newAddressModalOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[90] flex items-center justify-center p-3 sm:p-4 bg-black/60 backdrop-blur-xs overflow-y-auto"
        >
            <div
                id="modal-new-address"
                @click.outside="closeNewAddressModal()"
                style="max-height: 90dvh;"
                class="bg-white rounded-3xl border border-gray-100 shadow-[0_25px_60px_rgba(79,38,166,0.2)] max-w-lg w-full p-4 sm:p-6 relative flex flex-col min-h-0 overflow-hidden my-auto"
            >
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#4F26A6] flex items-center justify-center">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-extrabold text-gray-900">Tambah Alamat Baru</h3>
                    </div>
                    <button type="button" @click="closeNewAddressModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition-colors cursor-pointer" title="Tutup">✕</button>
                </div>

                <div class="space-y-2.5 sm:space-y-3.5 text-left flex-1 min-h-0 overflow-y-auto pr-1 sm:pr-2 overscroll-contain">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Nama Penerima</label>
                        <input type="text" x-model="newAddress.recipient_name" placeholder="Contoh: Budi Pratama" class="w-full rounded-xl border border-gray-200 px-3.5 py-2 sm:py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">No. Handphone / WhatsApp</label>
                        <div class="relative flex items-stretch rounded-xl border border-gray-200 bg-white transition-all overflow-hidden focus-within:border-[#4F26A6] focus-within:ring-2 focus-within:ring-[#4F26A6]/20">
                            <div class="flex items-center gap-1.5 px-3 py-2 sm:py-2.5 bg-gray-50 border-r border-gray-200 text-gray-700 select-none shrink-0">
                                <div class="w-4 h-3 rounded-xs overflow-hidden shadow-2xs border border-gray-300 flex flex-col shrink-0" title="Indonesia">
                                    <div class="h-1/2 w-full bg-[#E70011]"></div>
                                    <div class="h-1/2 w-full bg-white"></div>
                                </div>
                                <span class="text-xs sm:text-sm font-extrabold text-gray-900 tracking-tight">+62</span>
                            </div>
                            <input
                                type="tel"
                                x-ref="checkoutPhoneInput"
                                x-model="newAddress.phoneDisplay"
                                @input="handlePhoneInput($event)"
                                @paste="setTimeout(() => handlePhoneInput({ target: $el }), 0)"
                                placeholder="812-3456-7890"
                                maxlength="15"
                                class="w-full bg-transparent px-3 py-2 sm:py-2.5 text-sm text-gray-900 font-medium placeholder:text-gray-400 focus:outline-none tracking-wide"
                                autocomplete="tel-national"
                            />
                            <div class="flex items-center pr-2.5" x-show="newAddress.phoneDisplay" x-cloak>
                                <button
                                    type="button"
                                    @click="clearPhone()"
                                    class="w-5 h-5 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-400 hover:text-gray-600 flex items-center justify-center transition-colors text-xs font-bold cursor-pointer"
                                    title="Hapus nomor"
                                >
                                    &times;
                                </button>
                            </div>
                        </div>
                    </div>
                    <div
                        x-data="regionSelectorComponent()"
                        x-effect="newAddress.province = selectedProvince; newAddress.city = selectedCity; newAddress.district = selectedDistrict; newAddress.postal_code = selectedPostalCode;"
                    >
                        <x-region-select-fields />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Alamat Lengkap</label>
                        <textarea x-model="newAddress.full_address" rows="2" placeholder="Nama Jalan, Gedung, No. Rumah, RT/RW" class="w-full rounded-xl border border-gray-200 px-3.5 py-2 sm:py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none resize-none"></textarea>
                    </div>
                </div>
                <template x-if="saveError">
                    <div class="mt-3 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span x-text="saveError"></span>
                    </div>
                </template>

                <div class="pt-4 mt-4 border-t border-gray-100 flex items-center justify-end gap-3 shrink-0">
                    <button type="button" @click="closeNewAddressModal()" class="px-4 py-2.5 text-xs font-semibold text-gray-500 hover:text-gray-800 transition-colors cursor-pointer">Batal</button>
                    <button
                        type="button"
                        @click="saveNewAddress()"
                        :disabled="savingAddress"
                        class="px-6 py-2.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white text-xs font-bold shadow-md shadow-[#4F26A6]/20 transition-all cursor-pointer flex items-center gap-2 disabled:opacity-60"
                    >
                        <svg x-show="savingAddress" class="animate-spin w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <span x-text="savingAddress ? 'Menyimpan...' : 'Simpan Alamat'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
