@props(['sellers'])

<section id="seller-populer" class="w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-2 pb-3 sm:pb-24 relative" x-data="{
    scroll(direction) {
        const container = this.$refs.sliderTrack;
        const amount = direction === 'left' ? -260 : 260;
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
            class="flex lg:grid lg:grid-cols-5 gap-3.5 sm:gap-4 lg:gap-5 overflow-x-auto lg:overflow-x-visible pb-4 lg:pb-0 scroll-smooth snap-x snap-mandatory [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
        >
            @foreach($sellers as $seller)
                <x-seller-card :seller="$seller" />
            @endforeach
        </div>

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
