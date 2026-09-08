<x-layouts.app :title="$title" activeTab="keranjang">
    <main
        class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10"
        x-data="{
            itemsCount: {{ $items->count() }},
            selectedSubtotal: {{ (float) $selectedSubtotal }},
            selectedCount: {{ $items->where('is_selected', true)->count() }},
            loading: false,
            async updateQuantity(itemId, newQty) {
                if (newQty < 1) return;
                try {
                    const res = await fetch('/keranjang/item/' + itemId, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ quantity: newQty })
                    });
                    const data = await res.json();
                    if (data.success) {
                        this.selectedSubtotal = data.selected_subtotal;
                        this.selectedCount = data.selected_count;
                        return data.quantity;
                    }
                } catch (e) {
                    console.error(e);
                }
                return newQty;
            },
            async toggleSelected(itemId, isSelected) {
                try {
                    const res = await fetch('/keranjang/item/' + itemId, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ is_selected: isSelected })
                    });
                    const data = await res.json();
                    if (data.success) {
                        this.selectedSubtotal = data.selected_subtotal;
                        this.selectedCount = data.selected_count;
                    }
                } catch (e) {
                    console.error(e);
                }
            },
            async removeItem(itemId) {
                if (!confirm('Hapus produk ini dari keranjang?')) return;
                try {
                    const res = await fetch('/keranjang/item/' + itemId, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    });
                    const data = await res.json();
                    if (data.success) {
                        window.location.reload();
                    }
                } catch (e) {
                    console.error(e);
                }
            },
            formatRupiah(num) {
                return 'Rp ' + Number(num).toLocaleString('id-ID');
            }
        }"
    >
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
            <div>
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#F3EEFF] text-[#4F26A6] text-xs font-bold mb-2.5">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1" /><circle cx="20" cy="21" r="1" />
                        <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6" />
                    </svg>
                    <span>Keranjang Belanja</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Keranjang Saya
                </h1>
                <p class="text-sm sm:text-[15px] text-gray-500 mt-1">
                    Kelola barang yang ingin kamu beli dari para kreator favorit.
                </p>
            </div>

            <a
                href="/belanja"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border-2 border-[#4F26A6] text-[#4F26A6] hover:bg-[#4F26A6]/5 font-bold text-xs sm:text-sm transition-all shrink-0"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span>Tambah Barang Lain</span>
            </a>
        </div>

        @if($items->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-8 sm:p-14 text-center max-w-xl mx-auto my-8">
                <div class="w-24 h-24 sm:w-28 sm:h-28 mx-auto mb-6 rounded-full bg-[#F3EEFF] flex items-center justify-center">
                    <svg class="w-12 h-12 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="9" cy="21" r="1" /><circle cx="20" cy="21" r="1" />
                        <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6" />
                    </svg>
                </div>
                <h3 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight mb-2">
                    Keranjangmu Masih Kosong
                </h3>
                <p class="text-sm text-gray-500 max-w-md mx-auto mb-8 leading-relaxed">
                    Yuk cari barang pre-loved original dan merchandise eksklusif favoritmu lalu masukkan ke keranjang.
                </p>
                <a
                    href="/belanja"
                    class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-sm shadow-md shadow-[#4F26A6]/20 transition-all active:scale-[0.98]"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" /><path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                    <span>Mulai Belanja</span>
                </a>
            </div>
        @else
            <!-- 2-Column Cart Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Left: Items Grouped by Seller (8 cols) -->
                <div class="lg:col-span-8 space-y-6">
                    @foreach($groupedItems as $sellerName => $sellerItems)
                        <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_20px_rgba(0,0,0,0.03)] overflow-hidden">
                            <!-- Seller Header Bar -->
                            <div class="px-5 sm:px-6 py-4 bg-[#FAF9FC] border-b border-gray-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full overflow-hidden bg-purple-100 shrink-0">
                                        <img
                                            src="{{ $sellerItems->first()->variant->product->seller->user->avatar ?? '/assets/avatars/avatar-raisy.png' }}"
                                            alt="{{ $sellerName }}"
                                            class="w-full h-full object-cover"
                                        />
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-sm font-bold text-gray-900">{{ $sellerName }}</span>
                                        <x-verified-badge size="sm" class="w-3.5 h-3.5" />
                                    </div>
                                </div>
                                <span class="text-xs font-semibold text-gray-400">Toko Terverifikasi</span>
                            </div>

                            <!-- Item Rows -->
                            <div class="divide-y divide-gray-100 p-5 sm:p-6 space-y-5">
                                @foreach($sellerItems as $item)
                                    <div
                                        class="pt-4 first:pt-0 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"
                                        x-data="{
                                            qty: {{ $item->quantity }},
                                            selected: {{ $item->is_selected ? 'true' : 'false' }},
                                            price: {{ (float) $item->variant->price }},
                                            max: {{ (int) $item->variant->stock }},
                                            async inc() {
                                                if (this.qty < this.max) {
                                                    this.qty = await updateQuantity({{ $item->id }}, this.qty + 1);
                                                }
                                            },
                                            async dec() {
                                                if (this.qty > 1) {
                                                    this.qty = await updateQuantity({{ $item->id }}, this.qty - 1);
                                                }
                                            },
                                            toggle() {
                                                this.selected = !this.selected;
                                                toggleSelected({{ $item->id }}, this.selected);
                                            }
                                        }"
                                    >
                                        <!-- Checkbox & Product Info -->
                                        <div class="flex items-center gap-3.5 sm:gap-4 flex-1 min-w-0">
                                            <!-- Checkbox -->
                                            <input
                                                type="checkbox"
                                                x-model="selected"
                                                @change="toggle()"
                                                class="w-5 h-5 rounded-md text-[#4F26A6] border-gray-300 focus:ring-[#4F26A6]/20 focus:ring-2 cursor-pointer shrink-0"
                                            />

                                            <!-- Thumbnail -->
                                            <a href="{{ route('product.detail', $item->variant->product->slug) }}" class="w-18 h-18 sm:w-20 sm:h-20 rounded-2xl overflow-hidden bg-gray-100 shrink-0 block border border-gray-100">
                                                <img
                                                    src="{{ $item->variant->product->primary_image_url }}"
                                                    alt="{{ $item->variant->product->name }}"
                                                    class="w-full h-full object-cover"
                                                />
                                            </a>

                                            <!-- Text Details -->
                                            <div class="flex flex-col min-w-0">
                                                <a href="{{ route('product.detail', $item->variant->product->slug) }}" class="text-sm sm:text-[15px] font-bold text-gray-900 hover:text-[#4F26A6] transition-colors truncate">
                                                    {{ $item->variant->product->name }}
                                                </a>
                                                <span class="text-xs text-gray-500 mt-0.5 font-medium">
                                                    Varian: <strong class="text-gray-700">{{ $item->variant->name }}</strong>
                                                </span>
                                                <span class="text-sm font-extrabold text-[#4F26A6] mt-1 sm:hidden">
                                                    Rp {{ number_format((float)$item->variant->price, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Right Controls: Price, Stepper & Delete -->
                                        <div class="flex items-center justify-between sm:justify-end gap-5 w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-t-0 border-gray-50">
                                            <!-- Desktop Price -->
                                            <div class="text-right hidden sm:block">
                                                <span class="text-sm sm:text-[15px] font-extrabold text-[#4F26A6] block" x-text="formatRupiah(price * qty)"></span>
                                                <span class="text-[11px] text-gray-400">@ Rp {{ number_format((float)$item->variant->price, 0, ',', '.') }}</span>
                                            </div>

                                            <!-- Quantity Stepper -->
                                            <div class="inline-flex items-center border border-gray-200 rounded-xl bg-white overflow-hidden shadow-2xs">
                                                <button
                                                    type="button"
                                                    @click="dec()"
                                                    :disabled="qty <= 1"
                                                    class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition-colors disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer"
                                                >
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/>
                                                    </svg>
                                                </button>
                                                <span class="w-8 text-center text-xs font-bold text-gray-900 select-none" x-text="qty"></span>
                                                <button
                                                    type="button"
                                                    @click="inc()"
                                                    :disabled="qty >= max"
                                                    class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition-colors disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer"
                                                >
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                </button>
                                            </div>

                                            <!-- Delete Button -->
                                            <button
                                                type="button"
                                                @click="removeItem({{ $item->id }})"
                                                class="text-gray-400 hover:text-rose-600 transition-colors p-1.5 rounded-lg hover:bg-rose-50 cursor-pointer"
                                                title="Hapus Produk"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Right: Sticky Order Summary (4 cols) -->
                <div class="lg:col-span-4 sticky top-24">
                    <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-6 sm:p-7">
                        <h3 class="text-lg sm:text-xl font-extrabold text-gray-900 tracking-tight mb-5">
                            Ringkasan Belanja
                        </h3>

                        <div class="space-y-3.5 text-sm text-gray-600 pb-5 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <span>Total Barang Dipilih</span>
                                <span class="font-bold text-gray-900" x-text="selectedCount + ' barang'"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Subtotal Produk</span>
                                <span class="font-extrabold text-[#4F26A6] text-base" x-text="formatRupiah(selectedSubtotal)"></span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-gray-400">
                                <span>Ongkos Kirim</span>
                                <span class="font-medium">Dihitung saat checkout</span>
                            </div>
                        </div>

                        <!-- Sticky Handwritten Note Accent -->
                        <div class="my-4 p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200/60 flex items-center gap-2.5">
                            <span class="text-lg font-handwriting text-[#F59E0B] font-bold shrink-0">Aman &amp; Terpercaya:</span>
                            <p class="text-[11.5px] text-amber-950 font-medium leading-tight">
                                Transaksi dilindungi Rekening Bersama Escrow resmi WhiMarket.
                            </p>
                        </div>

                        <!-- Grand Total -->
                        <div class="flex items-center justify-between pt-2 mb-6">
                            <span class="text-sm font-bold text-gray-700">Total Tagihan:</span>
                            <span class="text-xl sm:text-2xl font-black text-[#4F26A6] tracking-tight" x-text="formatRupiah(selectedSubtotal)"></span>
                        </div>

                        <!-- Checkout Button -->
                        <a
                            href="{{ route('checkout.index') }}"
                            :class="selectedCount === 0 ? 'opacity-50 pointer-events-none cursor-not-allowed' : ''"
                            class="w-full py-3.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-sm sm:text-[15px] shadow-md shadow-[#4F26A6]/20 transition-all flex items-center justify-center gap-2.5 active:scale-[0.98] text-center"
                        >
                            <span>Lanjut ke Checkout</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mobile Sticky Bottom Bar (Thumb-friendly mobile checkout) -->
            <div class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-gray-200/80 px-4 py-3 sm:hidden shadow-lg flex items-center justify-between gap-3">
                <div>
                    <span class="text-[11px] text-gray-500 block">Total Tagihan:</span>
                    <span class="text-base font-black text-[#4F26A6] leading-none" x-text="formatRupiah(selectedSubtotal)"></span>
                </div>
                <a
                    href="{{ route('checkout.index') }}"
                    :class="selectedCount === 0 ? 'opacity-50 pointer-events-none cursor-not-allowed' : ''"
                    class="px-6 py-3 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs shadow-md shadow-[#4F26A6]/20 transition-all flex items-center gap-1.5"
                >
                    <span>Checkout</span>
                    <span class="px-1.5 py-0.5 rounded-full bg-white/20 text-[10px]" x-text="selectedCount"></span>
                </a>
            </div>
        @endif
    </main>
</x-layouts.app>
