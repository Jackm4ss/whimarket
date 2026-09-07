@props(['categories'])

<section class="w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-6 sm:pt-10 pb-16">
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

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5 sm:gap-4 lg:gap-5">
        @foreach($categories as $category)
            <x-category-card :category="$category" />
        @endforeach
    </div>
</section>
