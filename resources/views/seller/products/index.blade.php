<x-layouts.app :title="$title" activeTab="seller-products">
    <main class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10">
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
                                        <span class="text-xs text-gray-400 block mt-0.5">Total stok: {{ $product->total_stock }}</span>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4">
                                        @if($product->status->value === 'active')
                                            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/60 text-xs font-semibold">
                                                Aktif
                                            </span>
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
                                            <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini dari toko?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 rounded-xl text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition-all cursor-pointer">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
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
    </main>
</x-layouts.app>
