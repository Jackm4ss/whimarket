@props(['products'])

<section id="barang-terbaru" class="w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-1 sm:pt-2 pb-14 sm:pb-20">
    <div class="flex items-center justify-between mb-6 sm:mb-8">
        <h2 class="text-xl sm:text-2xl lg:text-[28px] font-extrabold text-[#111827] tracking-tight">
            Barang Terbaru
        </h2>
        <a
            href="/belanja"
            class="inline-flex items-center gap-1.5 text-xs sm:text-[14px] font-bold text-[#4F26A6] hover:text-[#3E1D85] transition-colors group"
        >
            <span>Lihat Semua</span>
            <svg
                class="w-4 h-4 text-[#4F26A6] group-hover:translate-x-1 transition-transform"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2.5"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <line x1="5" y1="12" x2="19" y2="12" />
                <polyline points="12 5 19 12 12 19" />
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 lg:gap-5">
        @foreach(collect($products)->take(5) as $product)
            <x-product-card :product="$product" />
        @endforeach

        <!-- 6th Card: Lihat Semua Kategori / Produk -->
        <a
            href="/belanja"
            class="bg-white rounded-2xl border border-gray-100/90 shadow-[0_4px_16px_rgba(0,0,0,0.04)] hover:shadow-[0_10px_26px_rgba(0,0,0,0.08)] hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col items-center justify-center p-6 text-center group min-h-[280px]"
        >
            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-[#EDE4FF] text-[#4F26A6] flex items-center justify-center mb-4 shadow-xs group-hover:scale-110 group-hover:bg-[#4F26A6] group-hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </div>
            <h3 class="text-sm sm:text-base font-extrabold text-gray-900 group-hover:text-[#4F26A6] transition-colors mb-1">
                Lihat Semua Kategori
            </h3>
            <p class="text-xs text-gray-400 font-medium">
                Temukan ratusan barang pre-loved lainnya
            </p>
            <span class="mt-4 text-xs font-bold text-[#4F26A6] group-hover:translate-x-1 transition-transform inline-flex items-center gap-1">
                <span>Belanja Sekarang</span>
                <span>&rarr;</span>
            </span>
        </a>
    </div>
</section>
