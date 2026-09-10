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

    @if(count($products) > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 lg:gap-6">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 p-8 sm:p-12 text-center shadow-[0_2px_12px_rgba(0,0,0,0.02)]">
            <p class="text-sm sm:text-base text-gray-500 font-medium">Belum ada produk yang tersedia saat ini.</p>
        </div>
    @endif
</section>
