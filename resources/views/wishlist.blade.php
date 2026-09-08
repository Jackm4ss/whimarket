<x-layouts.app :title="$title" activeTab="wishlist">
    <main class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
            <div>
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#F3EEFF] text-[#4F26A6] text-xs font-bold mb-2.5">
                    <svg class="w-3.5 h-3.5 text-[#4F26A6]" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    <span>Koleksi Favoritmu</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Wishlist Saya
                </h1>
                <p class="text-sm sm:text-[15px] text-gray-500 mt-1">
                    Daftar barang pre-loved dan merchandise kreator yang kamu simpan.
                </p>
            </div>

            <a
                href="/belanja"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border-2 border-[#4F26A6] text-[#4F26A6] hover:bg-[#4F26A6]/5 font-bold text-xs sm:text-sm transition-all shrink-0"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span>Lanjut Belanja</span>
            </a>
        </div>

        @if($wishlists->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-8 sm:p-14 text-center max-w-xl mx-auto my-8">
                <div class="w-24 h-24 sm:w-28 sm:h-28 mx-auto mb-6 rounded-full bg-[#F3EEFF] flex items-center justify-center">
                    <svg class="w-12 h-12 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h3 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight mb-2">
                    Wishlist Masih Kosong
                </h3>
                <p class="text-sm text-gray-500 max-w-md mx-auto mb-8 leading-relaxed">
                    Belum ada barang yang kamu simpan. Temukan barang pre-loved & merchandise unik dari kreator favoritmu sekarang.
                </p>
                <a
                    href="/belanja"
                    class="inline-flex items-center justify-center gap-2.5 px-6 py-3 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-sm shadow-md shadow-[#4F26A6]/20 transition-all active:scale-[0.98]"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <span>Mulai Cari Produk</span>
                </a>
            </div>
        @else
            <!-- Wishlist Products Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3.5 sm:gap-5 lg:gap-6">
                @foreach($wishlists as $item)
                    @if($item->product)
                        <x-product-card :product="$item->product" />
                    @endif
                @endforeach
            </div>
        @endif
    </main>
</x-layouts.app>
