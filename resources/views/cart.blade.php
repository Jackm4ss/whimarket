<x-layouts.app :title="$title" activeTab="keranjang">
    <main
        class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-6 sm:pt-8 pb-24 sm:pb-32 lg:pb-36"
        x-data="cartManager({{ $items->count() }}, {{ (float) $selectedSubtotal }}, {{ $items->where('is_selected', true)->count() }})"
    >
        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs sm:text-[13px] text-gray-500 font-medium mb-6">
            <a href="/" class="hover:text-[#4F26A6] transition-colors">Beranda</a>
            <span class="text-gray-300 font-normal">&gt;</span>
            <span class="text-gray-900 font-bold">Keranjang Belanja</span>
        </nav>

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 sm:mb-10 pb-6 sm:pb-8 border-b border-gray-100">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Keranjang Saya
                </h1>
                <p class="text-sm sm:text-[15px] text-gray-500 mt-1.5">
                    Kelola barang yang ingin kamu beli dari para kreator favorit.
                </p>
            </div>
            @auth
                <a
                    href="/belanja"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border-2 border-[#4F26A6] text-[#4F26A6] hover:bg-[#4F26A6]/5 font-bold text-xs sm:text-sm transition-all shrink-0"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span>Tambah Barang Lain</span>
                </a>
            @endauth
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
                @guest
                    <p class="text-sm text-gray-500 max-w-md mx-auto mb-8 leading-relaxed">
                        Masuk ke akunmu untuk melihat barang belanja yang sudah kamu simpan, atau mulai belanja barang pre-loved favoritmu.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a
                            href="{{ route('login') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-sm shadow-md shadow-[#4F26A6]/20 transition-all active:scale-[0.98]"
                        >
                            <span>Masuk ke Akun</span>
                        </a>
                        <a
                            href="/belanja"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold text-sm transition-all"
                        >
                            <span>Mulai Belanja</span>
                        </a>
                    </div>
                @else
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
                @endguest
            </div>
        @else
            <!-- 2-Column Cart Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 xl:gap-10 items-start">
                <!-- Left: Items Grouped by Seller (8 cols) -->
                <div class="lg:col-span-8 space-y-6 sm:space-y-7">
                    <!-- Cart Items Header Bar -->
                    <div class="bg-white rounded-2xl px-6 py-4 border border-gray-100 shadow-[0_2px_12px_rgba(0,0,0,0.02)] flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#4F26A6]"></span>
                            <span class="text-xs sm:text-sm font-bold text-gray-800">
                                Daftar Produk di Keranjang (<span x-text="itemsCount"></span> Barang)
                            </span>
                        </div>
                        <span class="text-xs text-gray-500 font-medium">
                            <span class="text-[#4F26A6] font-bold" x-text="selectedCount"></span> dipilih
                        </span>
                    </div>

                    @foreach($groupedItems as $sellerName => $sellerItems)
                        <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] overflow-hidden">
                            <!-- Seller Header Bar -->
                            <div class="px-6 py-4.5 bg-[#FAF9FC] border-b border-gray-100 flex items-center justify-between">
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
                            <div class="divide-y divide-gray-100 p-6 sm:p-7 space-y-6">
                                @foreach($sellerItems as $item)
                                    @php
                                        $isItemActive = $item->variant?->product?->status === \App\Enums\ProductStatus::ACTIVE
                                            && ($item->variant?->product?->seller?->status === \App\Enums\SellerStatus::VERIFIED);
                                        $isSellerInactive = $item->variant?->product?->seller && $item->variant->product->seller->status !== \App\Enums\SellerStatus::VERIFIED;
                                    @endphp
                                    <div
                                        class="pt-6 first:pt-0 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 {{ ! $isItemActive ? 'opacity-70 bg-gray-50/80 p-3.5 rounded-2xl border border-dashed border-gray-200' : '' }}"
                                        x-data="cartRow({{ $item->quantity }}, {{ $item->is_selected ? 'true' : 'false' }}, {{ (float) $item->variant->price }}, {{ (int) $item->variant->stock }}, {{ $item->id }})"
                                    >
                                        <!-- Checkbox & Product Info -->
                                        <div class="flex items-center gap-3.5 sm:gap-4 flex-1 min-w-0">
                                            @if($isItemActive)
                                                <!-- Custom Brand Purple Checkbox (Zero Blue) -->
                                                <button
                                                    type="button"
                                                    @click="toggle()"
                                                    class="w-5 h-5 rounded-md flex items-center justify-center transition-all cursor-pointer shrink-0 border"
                                                    :class="selected ? 'bg-[#4F26A6] border-[#4F26A6] text-white shadow-2xs' : 'bg-white border-gray-300 hover:border-[#4F26A6]'"
                                                    title="Pilih Barang"
                                                >
                                                    <svg x-show="selected" class="w-3.5 h-3.5 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </button>
                                            @else
                                                <div class="w-5 h-5 rounded-md bg-gray-200 border border-gray-300 flex items-center justify-center shrink-0 cursor-not-allowed" title="Produk tidak aktif dan tidak dapat dipilih">
                                                    <span class="text-gray-500 font-bold text-xs">-</span>
                                                </div>
                                            @endif
                                            <!-- Thumbnail -->
                                            <a href="{{ route('product.detail', $item->variant->product->slug) }}" class="w-20 h-20 sm:w-22 sm:h-22 rounded-2xl overflow-hidden bg-gray-100 shrink-0 block border border-gray-100 relative">
                                                <img
                                                    src="{{ $item->variant->product->primary_image_url }}"
                                                    alt="{{ $item->variant->product->name }}"
                                                    class="w-full h-full object-cover"
                                                />
                                                @if(! $isItemActive)
                                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                                        <span class="text-[10px] font-bold text-white uppercase tracking-wider px-1.5 py-0.5 bg-black/60 rounded">Nonaktif</span>
                                                    </div>
                                                @endif
                                            </a>

                                            <!-- Text Details -->
                                            <div class="flex flex-col min-w-0">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <a href="{{ route('product.detail', $item->variant->product->slug) }}" class="text-sm sm:text-[15px] font-bold text-gray-900 hover:text-[#4F26A6] transition-colors truncate">
                                                        {{ $item->variant->product->name }}
                                                    </a>
                                                    @if(! $isItemActive)
                                                        <span class="px-2 py-0.5 rounded-md bg-rose-50 border border-rose-200 text-rose-600 text-[10.5px] font-bold">
                                                            {{ $isSellerInactive ? 'Toko Nonaktif' : 'Produk Tidak Aktif' }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="text-xs text-gray-500 mt-0.5 font-medium">
                                                    Varian: <strong class="text-gray-700">{{ $item->variant->name }}</strong>
                                                </span>
                                                <span class="text-sm font-extrabold text-[#4F26A6] mt-1 sm:hidden">
                                                    Rp {{ number_format((float)$item->variant->price, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Right Controls: Price, Stepper & Delete -->
                                        <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto pt-3 sm:pt-0 border-t sm:border-t-0 border-gray-50">
                                            <!-- Desktop Price -->
                                            <div class="text-right hidden sm:block">
                                                <span class="text-sm sm:text-[15px] font-extrabold text-[#4F26A6] block" x-text="formatRupiah(price * qty)"></span>
                                            </div>

                                            @if($isItemActive)
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
                                            @else
                                                <span class="text-xs font-semibold text-rose-500 italic">Tidak dapat dibeli</span>
                                            @endif
                                            <!-- Delete Button Triggering Custom Modal -->
                                            <button
                                                type="button"
                                                @click="promptDeleteItem({ id: {{ $item->id }}, title: '{{ addslashes($item->variant?->product?->name ?? 'Produk') }}', image: '{{ $item->variant?->product?->primary_image_url ?? '/assets/placeholder-product.png' }}', price: 'Rp {{ number_format((float)$item->variant?->price, 0, ',', '.') }}', seller: '{{ addslashes($item->variant?->product?->seller?->store_name ?? 'WhiMarket Creator') }}' })"
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
                    <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-7 sm:p-8 space-y-5">
                        <h3 class="text-lg sm:text-xl font-extrabold text-gray-900 tracking-tight">
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
                        <div class="my-5 p-4 rounded-2xl bg-amber-50/70 border border-amber-200/60 flex items-center gap-3">
                            <span class="text-lg font-handwriting text-[#F59E0B] font-bold shrink-0">Aman &amp; Terpercaya:</span>
                            <p class="text-[12px] text-amber-950 font-medium leading-relaxed">
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
                            class="w-full py-4 rounded-2xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-sm sm:text-[15px] shadow-md shadow-[#4F26A6]/20 transition-all flex items-center justify-center gap-2.5 active:scale-[0.98] text-center cursor-pointer"
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
        <!-- Modal Konfirmasi Hapus Produk dari Keranjang -->
        <div
            x-show="deleteModalOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs overflow-y-auto"
        >
            <div
                @click.outside="if (!isDeleting) deleteModalOpen = false"
                x-show="deleteModalOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                class="bg-white rounded-3xl overflow-hidden max-w-md w-full shadow-[0_25px_60px_rgba(79,38,166,0.22)] border border-gray-100 relative my-8 text-center"
            >
                <!-- Modal Banner Header with Generated Illustration -->
                <div class="relative h-32 sm:h-36 w-full overflow-hidden bg-gradient-to-br from-[#4F26A6] to-[#E11D48] flex items-center justify-center">
                    <img
                        src="/assets/modals/action-delete-header.png"
                        alt="Hapus Barang"
                        class="w-full h-full object-cover mix-blend-luminosity opacity-45 absolute inset-0"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-transparent to-transparent"></div>

                    <button
                        type="button"
                        @click="deleteModalOpen = false"
                        :disabled="isDeleting"
                        class="absolute top-3.5 right-3.5 z-20 w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-md text-white flex items-center justify-center transition-all cursor-pointer"
                        title="Tutup"
                    >
                        ✕
                    </button>

                    <div class="relative z-10 text-center px-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-white text-[11px] font-extrabold mb-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus dari Keranjang
                        </span>
                        <h3 class="text-lg sm:text-xl font-black text-white tracking-tight">Keluarkan Barang Ini?</h3>
                    </div>
                </div>

                <!-- Body Content Container -->
                <div class="p-6 sm:p-7 space-y-5 text-left">
                    <!-- Product Preview Card -->
                    <div class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-[#FAF9FC] border border-gray-100/90 shadow-2xs">
                        <img :src="deletingItemImage" alt="Produk" class="w-14 h-14 rounded-xl object-cover border border-gray-200 shrink-0 bg-white" />
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider" x-text="deletingItemSeller"></p>
                            <h4 class="text-xs sm:text-sm font-extrabold text-gray-900 truncate" x-text="deletingItemTitle"></h4>
                            <p class="text-xs sm:text-[13px] font-extrabold text-[#4F26A6] mt-0.5" x-text="deletingItemPrice"></p>
                        </div>
                    </div>

                    <p class="text-xs sm:text-[13px] text-gray-500 leading-relaxed text-center">
                        Barang ini akan dikeluarkan dari keranjang belanja kamu. Kamu tetap dapat menyimpannya ke Wishlist kapan saja.
                    </p>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-3 pt-1">
                        <button
                            type="button"
                            @click="deleteModalOpen = false"
                            :disabled="isDeleting"
                            class="w-full py-3 rounded-2xl border border-gray-200 hover:bg-gray-100 text-gray-700 font-bold text-xs sm:text-sm transition-all cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="button"
                            @click="confirmDeleteItem()"
                            :disabled="isDeleting"
                            class="w-full py-3 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-rose-600/25 transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
                        >
                            <span x-show="!isDeleting">Hapus Barang</span>
                            <span x-show="isDeleting" x-cloak class="inline-flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4 text-white" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                Menghapus...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
    function registerCartComponents() {
        if (window.__whiCartRegistered) return;
        window.__whiCartRegistered = true;

        Alpine.data('cartManager', (initCount, initSubtotal, initSelected) => ({
            itemsCount: initCount,
            selectedSubtotal: initSubtotal,
            selectedCount: initSelected,
            loading: false,
            deleteModalOpen: false,
            deletingItemId: null,
            deletingItemTitle: '',
            deletingItemImage: '',
            deletingItemPrice: '',
            deletingItemSeller: '',
            isDeleting: false,

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
                    } else {
                        alert(data.message || 'Gagal mengubah status pilihan barang.');
                        window.location.reload();
                    }
                    console.error(e);
                }
            },

            promptDeleteItem(item) {
                this.deletingItemId = item.id;
                this.deletingItemTitle = item.title;
                this.deletingItemImage = item.image;
                this.deletingItemPrice = item.price;
                this.deletingItemSeller = item.seller;
                this.deleteModalOpen = true;
            },

            async confirmDeleteItem() {
                if (!this.deletingItemId) return;
                this.isDeleting = true;
                try {
                    const res = await fetch('/keranjang/item/' + this.deletingItemId, {
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
                } finally {
                    this.isDeleting = false;
                    this.deleteModalOpen = false;
                }
            },

            formatRupiah(num) {
                return 'Rp ' + Number(num).toLocaleString('id-ID');
            }
        }));

        Alpine.data('cartRow', (initQty, initSelected, initPrice, initMax, itemId) => ({
            qty: initQty,
            selected: initSelected,
            price: initPrice,
            max: initMax,

            async inc() {
                if (this.qty < this.max) {
                    const parent = this.$root.closest('[x-data*="cartManager"]')?._x_dataStack?.[0];
                    if (parent) {
                        this.qty = await parent.updateQuantity(itemId, this.qty + 1);
                    }
                }
            },

            async dec() {
                if (this.qty > 1) {
                    const parent = this.$root.closest('[x-data*="cartManager"]')?._x_dataStack?.[0];
                    if (parent) {
                        this.qty = await parent.updateQuantity(itemId, this.qty - 1);
                    }
                }
            },

            toggle() {
                this.selected = !this.selected;
                const parent = this.$root.closest('[x-data*="cartManager"]')?._x_dataStack?.[0];
                if (parent) {
                    parent.toggleSelected(itemId, this.selected);
                }
            }
        }));
    }

    if (window.Alpine) {
        registerCartComponents();
    } else {
        document.addEventListener('alpine:init', registerCartComponents);
    }
    </script>
    @endpush
</x-layouts.app>
