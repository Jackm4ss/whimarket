@props(['sellers'])

<section id="seller-populer" class="w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-2 pb-3 sm:pb-24 relative" x-data="{
    scroll(direction) {
        const container = this.$refs.sliderTrack;
        const amount = direction === 'left' ? -310 : 310;
        container.scrollBy({ left: amount, behavior: 'smooth' });
    }
}">
    <!-- Section Header -->
    <div class="flex items-center justify-between mb-6 sm:mb-8">
        <h2 class="text-xl sm:text-2xl lg:text-[28px] font-extrabold text-[#111827] tracking-tight">
            Seller Populer
        </h2>
        <a
            href="/seller"
            class="inline-flex items-center gap-1.5 text-sm sm:text-[15.5px] font-bold text-[#4F26A6] hover:text-[#3E1D85] transition-colors group shrink-0"
        >
            <span>Lihat Semua Seller</span>
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

    <!-- Cards Container with Slider Track -->
    <div class="relative">
        <!-- Desktop Floating Navigation Arrows -->
        <button
            type="button"
            @click="scroll('left')"
            class="hidden lg:flex absolute -left-4 xl:-left-6 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white shadow-[0_4px_16px_rgba(0,0,0,0.12)] border border-gray-100 items-center justify-center text-gray-700 hover:text-[#4F26A6] hover:scale-105 transition-all cursor-pointer"
            aria-label="Scroll Left"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <button
            type="button"
            @click="scroll('right')"
            class="hidden lg:flex absolute -right-4 xl:-right-6 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white shadow-[0_4px_16px_rgba(0,0,0,0.12)] border border-gray-100 items-center justify-center text-gray-700 hover:text-[#4F26A6] hover:scale-105 transition-all cursor-pointer"
            aria-label="Scroll Right"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <!-- Slider Track -->
        <div
            x-ref="sliderTrack"
            class="flex gap-3.5 sm:gap-4 lg:gap-5 overflow-x-auto pb-4 scroll-smooth snap-x snap-mandatory [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
            @foreach(collect($sellers)->take(5) as $seller)
                <x-seller-card :seller="$seller" />
            @endforeach

            <!-- 6th Card: Lihat Semua Seller / Kategori -->
            <a
                href="/seller"
                class="w-[240px] sm:w-[260px] lg:w-[280px] shrink-0 bg-white rounded-2xl sm:rounded-3xl border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_32px_rgba(72,30,188,0.08)] transition-all flex flex-col justify-between p-6 text-center group min-h-[340px] snap-start"
            >
                <div class="my-auto flex flex-col items-center">
                    <div class="w-16 h-16 rounded-2xl bg-[#EDE4FF] text-[#4F26A6] flex items-center justify-center mb-4 shadow-xs group-hover:scale-110 group-hover:bg-[#4F26A6] group-hover:text-white transition-all">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-black text-gray-900 group-hover:text-[#4F26A6] transition-colors mb-1.5">
                        Lihat Semua Kategori
                    </h3>
                    <p class="text-xs sm:text-[13px] text-gray-500 font-medium max-w-[200px] leading-relaxed">
                        Jelajahi seluruh seller, artis, dan kreator favorit lainnya
                    </p>
                </div>
                <div class="pt-4 border-t border-gray-100">
                    <span class="w-full py-2.5 rounded-xl bg-[#F4EFFB] group-hover:bg-[#4F26A6] text-[#4F26A6] group-hover:text-white font-bold text-xs transition-all flex items-center justify-center gap-1.5 shadow-2xs">
                        <span>Lihat Semua Seller</span>
                        <span>&rarr;</span>
                    </span>
                </div>
            </a>

        <!-- Mobile Navigation Controls -->
        <div class="flex lg:hidden items-center justify-center gap-3 mt-3 sm:mt-6">
            <button
                type="button"
                @click="scroll('left')"
                class="w-10 h-10 rounded-full bg-white shadow-md border border-gray-100 flex items-center justify-center text-gray-700 hover:text-[#4F26A6] active:scale-95 transition-all"
                aria-label="Scroll Left"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button
                type="button"
                @click="scroll('right')"
                class="w-10 h-10 rounded-full bg-white shadow-md border border-gray-100 flex items-center justify-center text-gray-700 hover:text-[#4F26A6] active:scale-95 transition-all"
                aria-label="Scroll Right"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>
</section>
