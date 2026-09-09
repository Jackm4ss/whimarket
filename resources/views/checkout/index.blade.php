<x-layouts.app :title="$title" activeTab="checkout">
    <main
        class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10"
        x-data="checkoutManager({
            addresses: {{ Js::from($addressesData) }},
            selectedAddressId: {{ $defaultAddress->id ?? 'null' }},
            subtotal: {{ (float) $subtotal }},
            adminFee: {{ (float) $adminFee }},
            defaultShippingCost: {{ (int) $shippingCost }},
            defaultZone: '{{ addslashes($defaultZone) }}',
            defaultEtd: '{{ addslashes($defaultEtd) }}',
            userName: '{{ addslashes(auth()->user()->name) }}',
            userPhone: '{{ addslashes(auth()->user()->phone ?? '') }}',
            onboardingUrl: '{{ route('onboarding.address') }}',
            csrfToken: '{{ csrf_token() }}'
        })"
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
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-xl bg-[#F3EEFF] text-[#4F26A6] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </span>
                            <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Alamat Pengiriman</h3>
                        </div>
                        <button
                            type="button"
                            @click="addressModalOpen = true"
                            class="text-xs sm:text-sm font-bold text-[#4F26A6] hover:underline cursor-pointer"
                        >
                            Pilih / Tambah Alamat &rarr;
                        </button>
                    </div>

                    @if($defaultAddress)
                        <template x-if="selectedAddress">
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
                            </div>
                        </template>
                        <input type="hidden" name="address_id" :value="selectedAddressId" />
                    @else
                        <div class="text-center py-6 text-gray-500">
                            <p class="text-sm mb-3">Belum ada alamat pengiriman.</p>
                            <button
                                type="button"
                                @click="newAddressModalOpen = true"
                                class="px-5 py-2.5 rounded-xl bg-[#4F26A6] text-white font-bold text-xs shadow-sm cursor-pointer"
                            >
                                + Tambah Alamat Sekarang
                            </button>
                        </div>
                    @endif
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
                            <span class="font-bold text-gray-900" x-text="formatRupiah(currentShippingCost)"></span>
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
                    <div class="flex items-center justify-between pt-1 mb-6">
                        <span class="text-sm font-bold text-gray-700">Total Pembayaran:</span>
                        <span class="text-xl sm:text-2xl font-black text-[#4F26A6] tracking-tight" x-text="formatRupiah(grandTotal)"></span>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="!selectedAddressId"
                        class="w-full py-4 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-sm sm:text-[15px] shadow-lg shadow-[#4F26A6]/25 transition-all active:scale-[0.98] cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed text-center"
                    >
                        Buat Pesanan &amp; Bayar Sekarang &rarr;
                    </button>
                </div>
            </div>
        </form>

        <!-- Address Selection Modal -->
        <div
            x-show="addressModalOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs overflow-y-auto"
        >
            <div
                @click.outside="addressModalOpen = false"
                class="bg-white rounded-3xl border border-gray-100 shadow-[0_25px_60px_rgba(79,38,166,0.2)] max-w-lg w-full p-6 sm:p-8 relative my-8"
            >
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#4F26A6] flex items-center justify-center">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" /><circle cx="12" cy="10" r="3" /></svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-extrabold text-gray-900">Pilih Alamat Pengiriman</h3>
                    </div>
                    <button type="button" @click="addressModalOpen = false" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition-colors cursor-pointer" title="Tutup">✕</button>
                </div>

                <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
                    @foreach($addressesData as $addr)
                        <div
                            @click="selectAddress({{ Js::from($addr) }})"
                            class="p-4 rounded-2xl border transition-all cursor-pointer"
                            :class="selectedAddressId === {{ $addr['id'] }} ? 'border-2 border-[#4F26A6] bg-[#F3EEFF]/20 shadow-2xs' : 'border-gray-200 hover:border-purple-200 bg-white'"
                        >
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-sm text-gray-900">{{ $addr['recipient_name'] }}</span>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded-md bg-purple-50 text-[#4F26A6] text-[10.5px] font-bold">
                                        Ongkir: Rp {{ number_format($addr['shipping_cost'], 0, ',', '.') }}
                                    </span>
                                    @if($addr['is_default'])
                                        <span class="px-2 py-0.5 rounded-md bg-[#F3EEFF] text-[#4F26A6] text-[10.5px] font-extrabold">Utama</span>
                                    @endif
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mb-1">{{ $addr['phone'] }}</p>
                            <p class="text-xs text-gray-700 leading-relaxed">{{ $addr['full_address'] }}, {{ $addr['district'] ? 'Kec. ' . $addr['district'] . ', ' : '' }}{{ $addr['city'] }}, {{ $addr['province'] }} {{ $addr['postal_code'] }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4 mt-4 border-t border-gray-100 flex items-center justify-between">
                    <button
                        type="button"
                        @click="addressModalOpen = false; newAddressModalOpen = true"
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
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs overflow-y-auto"
        >
            <div
                @click.outside="newAddressModalOpen = false"
                class="bg-white rounded-3xl border border-gray-100 shadow-[0_25px_60px_rgba(79,38,166,0.2)] max-w-lg w-full p-6 sm:p-8 relative my-8"
            >
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#4F26A6] flex items-center justify-center">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-extrabold text-gray-900">Tambah Alamat Baru</h3>
                    </div>
                    <button type="button" @click="newAddressModalOpen = false" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition-colors cursor-pointer" title="Tutup">✕</button>
                </div>

                <div class="space-y-3.5 text-left">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Nama Penerima</label>
                        <input type="text" x-model="newAddress.recipient_name" placeholder="Contoh: Budi Pratama" class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">No. Handphone / WhatsApp</label>
                        <input type="tel" x-model="newAddress.phone" placeholder="Contoh: 081234567890" class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"/>
                    </div>
                    <div
                        x-data="regionSelectorComponent()"
                        x-effect="newAddress.province = selectedProvince; newAddress.city = selectedCity; newAddress.district = selectedDistrict; newAddress.postal_code = selectedPostalCode;"
                    >
                        <x-region-select-fields />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Alamat Lengkap</label>
                        <textarea x-model="newAddress.full_address" rows="2" placeholder="Nama Jalan, Gedung, No. Rumah, RT/RW" class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none resize-none"></textarea>
                    </div>
                </div>

                <div class="pt-4 mt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" @click="newAddressModalOpen = false" class="px-4 py-2.5 text-xs font-semibold text-gray-500 hover:text-gray-800 transition-colors cursor-pointer">Batal</button>
                    <button type="button" @click="saveNewAddress()" class="px-6 py-2.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white text-xs font-bold shadow-md shadow-[#4F26A6]/20 transition-all cursor-pointer">Simpan Alamat</button>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('checkoutManager', (config) => ({
                addresses: config.addresses || [],
                selectedAddressId: config.selectedAddressId,
                subtotal: Number(config.subtotal),
                adminFee: Number(config.adminFee || 0),
                newAddress: {
                    recipient_name: config.userName || '',
                    phone: config.userPhone || '',
                    province: '',
                    city: '',
                    district: '',
                    postal_code: '',
                    full_address: '',
                },
                get selectedAddress() {
                    return this.addresses.find(a => a.id === this.selectedAddressId) || (this.addresses.length > 0 ? this.addresses[0] : null);
                },
                get currentShippingCost() {
                    return this.selectedAddress ? Number(this.selectedAddress.shipping_cost) : config.defaultShippingCost;
                },
                get currentShippingZone() {
                    return this.selectedAddress ? this.selectedAddress.shipping_zone : config.defaultZone;
                },
                get currentShippingEtd() {
                    return this.selectedAddress ? this.selectedAddress.shipping_etd : config.defaultEtd;
                },
                get grandTotal() {
                    return this.subtotal + this.currentShippingCost + this.adminFee;
                },
                selectAddress(addr) {
                    this.selectedAddressId = addr.id;
                    this.addressModalOpen = false;
                },
                async saveNewAddress() {
                    if (!this.newAddress.recipient_name || !this.newAddress.phone || !this.newAddress.full_address || !this.newAddress.province || !this.newAddress.city) {
                        alert('Lengkapi semua data alamat termasuk Provinsi dan Kota.');
                        return;
                    }
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
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Gagal menyimpan alamat.');
                    }
                } catch (e) {
                    alert('Gagal menyimpan alamat.');
                }
            },
            formatRupiah(num) {
                return 'Rp ' + Number(num).toLocaleString('id-ID');
            }
        }));
    });
</script>
@endpush
</x-layouts.app>
