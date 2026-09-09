@props(['categories'])

<section class="w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-4 sm:pt-10 pb-2 sm:pb-16" x-data="{
    scroll(direction) {
        const container = this.$refs.categorySliderTrack;
        const amount = direction === 'left' ? -240 : 240;
        container.scrollBy({ left: amount, behavior: 'smooth' });
    }
}">
    <div class="flex items-center justify-between mb-6 sm:mb-8">
        <h2 class="text-xl sm:text-2xl lg:text-[28px] font-extrabold text-[#111827] tracking-tight">
            Pilihan Kategori Populer
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

    <!-- Category Slider Track: Horizontal on Mobile & Tablet (<1024px), Static 6-cols Grid on Desktop (>=1024px) -->
    <div class="relative">
        <div
            x-ref="categorySliderTrack"
            class="flex lg:grid lg:grid-cols-6 gap-3.5 sm:gap-4 lg:gap-5 overflow-x-auto lg:overflow-x-visible pb-4 lg:pb-0 scroll-smooth snap-x snap-mandatory [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
        >
            @foreach($categories as $category)
                <x-category-card :category="$category" />
            @endforeach

            <!-- 7th Card: Lihat Semua Kategori (Mobile & Tablet only, hidden on Desktop) -->
            <a href="/belanja" class="w-[130px] sm:w-[150px] shrink-0 flex lg:hidden flex-col items-center group snap-start cursor-pointer text-center">
                <div class="w-full aspect-square rounded-2xl sm:rounded-3xl bg-[#EDE4FF] border border-[#4F26A6]/20 shadow-xs group-hover:shadow-lg group-hover:-translate-y-1.5 transition-all duration-300 flex flex-col items-center justify-center p-3 text-center relative overflow-hidden">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#4F26A6] text-white flex items-center justify-center mb-1.5 shadow-xs group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </div>
                    <span class="text-[11px] sm:text-xs font-extrabold text-[#4F26A6] leading-tight px-1">
                        Lihat Semua Kategori
                    </span>
                </div>
            </a>
        </div>

        <!-- Mobile & Tablet Slider Navigation Buttons (Hidden on desktop lg) -->
        <div class="flex lg:hidden items-center justify-center gap-3 mt-3 sm:mt-6">
            <button
                type="button"
                @click="scroll('left')"
                class="w-10 h-10 rounded-full bg-white shadow-md border border-gray-100 flex items-center justify-center text-gray-700 hover:text-[#4F26A6] active:scale-95 transition-all cursor-pointer"
                aria-label="Scroll Kategori Kiri"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button
                type="button"
                @click="scroll('right')"
                class="w-10 h-10 rounded-full bg-white shadow-md border border-gray-100 flex items-center justify-center text-gray-700 hover:text-[#4F26A6] active:scale-95 transition-all cursor-pointer"
                aria-label="Scroll Kategori Kanan"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>
</section>
