<x-layouts.app :title="$title" activeTab="wishlist">
    <main class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-6 sm:pt-8 pb-24 sm:pb-32 lg:pb-36">
        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs sm:text-[13px] text-gray-500 font-medium mb-6">
            <a href="/" class="hover:text-[#4F26A6] transition-colors">Beranda</a>
            <span class="text-gray-300 font-normal">&gt;</span>
            <span class="text-gray-900 font-bold">Wishlist Saya</span>
        </nav>

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 sm:mb-10 pb-6 sm:pb-8 border-b border-gray-100">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                        Wishlist Saya
                    </h1>
                    @auth
                        @if($wishlists->isNotEmpty())
                            <span class="text-xs font-bold text-[#4F26A6] bg-[#F3EEFF] px-3 py-1 rounded-full border border-[#4F26A6]/10">
                                {{ $wishlists->count() }} Barang
                            </span>
                        @endif
                    @endauth
                </div>
                <p class="text-sm sm:text-[15px] text-gray-500 mt-1.5">
                    @guest
                        Masuk ke akunmu untuk melihat dan menyimpan produk favoritmu.
                    @else
                        Daftar barang pre-loved dan merchandise kreator favorit yang kamu simpan.
                    @endguest
                </p>
            </div>
            @auth
                <a
                    href="/belanja"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border-2 border-[#4F26A6] text-[#4F26A6] hover:bg-[#4F26A6]/5 font-bold text-xs sm:text-sm transition-all shrink-0"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <span>Eksplor Produk Lain</span>
                </a>
            @endauth
        </div>

        @if($wishlists->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-8 sm:p-14 text-center max-w-xl mx-auto my-8">
                <div class="w-24 h-24 sm:w-28 sm:h-28 mx-auto mb-6 rounded-full bg-[#F3EEFF] flex items-center justify-center">
                    <svg class="w-12 h-12 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                @guest
                    <h3 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight mb-2">
                        Masuk untuk Melihat Wishlist
                    </h3>
                    <p class="text-sm text-gray-500 max-w-md mx-auto mb-8 leading-relaxed">
                        Fitur simpan wishlist membutuhkan akun WhiMarket. Yuk masuk atau daftar sekarang agar barang favoritmu tersimpan rapi dan tidak hilang.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a
                            href="{{ route('login') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-sm shadow-md shadow-[#4F26A6]/20 transition-all active:scale-[0.98]"
                        >
                            <span>Masuk ke Akun</span>
                        </a>
                        <a
                            href="{{ route('register') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold text-sm transition-all"
                        >
                            <span>Daftar Akun</span>
                        </a>
                    </div>
                @else
                    <h3 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight mb-2">
                        Wishlist Kamu Masih Kosong
                    </h3>
                    <p class="text-sm text-gray-500 max-w-md mx-auto mb-8 leading-relaxed">
                        Kamu belum menyimpan barang impianmu. Cari barang pre-loved original dan merchandise unik dari kreator favoritmu sekarang!
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
                @endguest
            </div>
        @else
            <!-- Wishlist Products Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-5 lg:gap-6">
                @foreach($wishlists as $item)
                    @if($item->product)
                        <x-product-card :product="$item->product" :isLiked="true" :showAddToCart="true" />
                    @endif
                @endforeach
            </div>
        @endif
    </main>
</x-layouts.app>
