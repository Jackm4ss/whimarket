<x-layouts.app :title="$title" activeTab="seller-products">
    <main
        class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10"
        x-data="{
            deleteModalOpen: false,
            deletingProductId: null,
            deletingProductName: '',
            promptDelete(id, name) {
                this.deletingProductId = id;
                this.deletingProductName = name;
                this.deleteModalOpen = true;
            }
        }"
    >
        <!-- Top Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
            <div>
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#F3EEFF] text-[#4F26A6] text-xs font-bold mb-2.5">
                    <x-verified-badge size="sm" class="w-3.5 h-3.5" />
                    <span>Katalog Produk Toko</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-950 tracking-tight">
                    Kelola Produk
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Atur koleksi barang pre-loved dan merchandise eksklusif tokomu.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('seller.dashboard') }}"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 font-bold text-xs sm:text-sm transition-all"
                >
                    &larr; Dashboard
                </a>
                <a
                    href="{{ route('seller.products.create') }}"
                    class="px-5 py-2.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs sm:text-sm shadow-md shadow-[#4F26A6]/20 transition-all flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Produk Baru</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Products Container -->
        <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] overflow-hidden">
            @if($products->isEmpty())
                <div class="py-16 text-center text-gray-400">
                    <svg class="w-14 h-14 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <h3 class="text-base font-bold text-gray-700 mb-1">Belum Ada Produk</h3>
                    <p class="text-xs text-gray-400 mb-5">Mulai upload barang pre-loved pertamamu sekarang.</p>
                    <a href="{{ route('seller.products.create') }}" class="px-5 py-2.5 rounded-xl bg-[#4F26A6] text-white font-bold text-xs">
                        + Tambah Produk
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-xs uppercase tracking-wider text-gray-400 border-b border-gray-100 bg-[#FAF9FC]">
                            <tr>
                                <th class="px-6 py-4 font-bold">Produk</th>
                                <th class="px-6 py-4 font-bold">Kategori</th>
                                <th class="px-6 py-4 font-bold">Harga</th>
                                <th class="px-6 py-4 font-bold">Varian &amp; Stok</th>
                                <th class="px-6 py-4 font-bold">Status</th>
                                <th class="px-6 py-4 font-bold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($products as $product)
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <!-- Image & Title -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3.5">
                                            <img
                                                src="{{ $product->primary_image_url }}"
                                                alt="{{ $product->name }}"
                                                class="w-14 h-14 rounded-2xl object-cover border border-gray-100 bg-gray-50 shrink-0"
                                            />
                                            <div class="min-w-0">
                                                <a href="{{ route('product.detail', $product->slug) }}" target="_blank" class="font-bold text-gray-900 hover:text-[#4F26A6] transition-colors truncate block max-w-xs">
                                                    {{ $product->name }}
                                                </a>
                                                <span class="text-xs text-gray-400 font-medium block mt-0.5">
                                                    {{ $product->condition?->label() }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Category -->
                                    <td class="px-6 py-4 font-medium text-gray-600">
                                        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">
                                            {{ $product->category->name ?? 'Merchandise' }}
                                        </span>
                                    </td>

                                    <!-- Price -->
                                    <td class="px-6 py-4 font-extrabold text-[#4F26A6]">
                                        Rp {{ number_format((float)$product->price, 0, ',', '.') }}
                                    </td>

                                    <!-- Variants & Stock -->
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-gray-800">{{ $product->variants->count() }} varian</span>
                                        @if($product->total_stock <= 0)
                                            <span class="text-xs font-bold text-rose-600 block mt-0.5">Total stok: 0 (Habis)</span>
                                        @else
                                            <span class="text-xs text-gray-400 block mt-0.5">Total stok: {{ $product->total_stock }}</span>
                                        @endif
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4">
                                        @if($product->status->value === 'active')
                                            @if($product->total_stock <= 0)
                                                <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200/60 text-xs font-semibold">
                                                    Stok Habis
                                                </span>
                                            @else
                                                <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/60 text-xs font-semibold">
                                                    Aktif
                                                </span>
                                            @endif
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-semibold">
                                                {{ ucfirst($product->status->value) }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a
                                                href="{{ route('seller.products.edit', $product->id) }}"
                                                class="px-3 py-1.5 rounded-xl border border-gray-200 text-gray-700 hover:text-[#4F26A6] hover:bg-gray-50 text-xs font-bold transition-all"
                                            >
                                                Edit
                                            </a>
                                            <button
                                                type="button"
                                                @click="promptDelete({{ $product->id }}, '{{ addslashes($product->name) }}')"
                                                class="p-1.5 rounded-xl text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition-all cursor-pointer"
                                                title="Hapus Produk"
                                            >
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-gray-100">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
        <!-- Modal Konfirmasi Hapus Produk Toko -->
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
                @click.outside="deleteModalOpen = false"
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
                        alt="Hapus Produk"
                        class="w-full h-full object-cover mix-blend-luminosity opacity-45 absolute inset-0"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-transparent to-transparent"></div>

                    <button
                        type="button"
                        @click="deleteModalOpen = false"
                        class="absolute top-3.5 right-3.5 z-20 w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-md text-white flex items-center justify-center transition-all cursor-pointer"
                        title="Tutup"
                    >
                        ✕
                    </button>

                    <div class="relative z-10 text-center px-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-white text-[11px] font-extrabold mb-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Katalog Produk Toko
                        </span>
                        <h3 class="text-lg sm:text-xl font-black text-white tracking-tight">Hapus Produk Ini?</h3>
                    </div>
                </div>

                <div class="p-6 sm:p-7 space-y-5 text-left">
                    <div class="p-4 rounded-2xl bg-[#FAF9FC] border border-gray-100/90 shadow-2xs">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Produk:</p>
                        <h4 class="text-sm font-extrabold text-gray-900" x-text="deletingProductName"></h4>
                    </div>

                    <p class="text-xs sm:text-[13px] text-gray-500 leading-relaxed text-center">
                        Produk ini akan dihapus secara permanen dari etalase tokomu dan tidak dapat dikembalikan.
                    </p>

                    <form :action="'/seller/products/' + deletingProductId" method="POST" class="grid grid-cols-2 gap-3 pt-1">
                        @csrf
                        @method('DELETE')
                        <button
                            type="button"
                            @click="deleteModalOpen = false"
                            class="w-full py-3 rounded-2xl border border-gray-200 hover:bg-gray-100 text-gray-700 font-bold text-xs sm:text-sm transition-all cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="w-full py-3 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-rose-600/25 transition-all cursor-pointer"
                        >
                            Ya, Hapus Produk
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>
