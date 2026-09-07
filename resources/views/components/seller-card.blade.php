@props(['seller'])

<div class="min-w-[195px] sm:min-w-[215px] lg:min-w-0 shrink-0 lg:shrink bg-white rounded-2xl sm:rounded-3xl border border-gray-100/90 shadow-[0_4px_18px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_25px_rgba(0,0,0,0.07)] hover:-translate-y-1 transition-all duration-300 p-4 sm:p-5 flex flex-col items-center text-center justify-between group snap-start">
    <div class="flex flex-col items-center w-full">
        <!-- Avatar Container -->
        <div class="relative mb-3.5">
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full p-1 bg-gray-50 ring-1 ring-gray-200/80 shadow-xs overflow-hidden">
                <img
                    src="{{ $seller['avatar'] }}"
                    alt="{{ $seller['name'] }}"
                    class="w-full h-full object-cover rounded-full group-hover:scale-105 transition-transform duration-300"
                    loading="lazy"
                />
            </div>
        </div>
        <!-- Name & Verified -->
        <div class="flex items-center justify-center gap-1.5 w-full">
            <h3 class="text-sm sm:text-[15.5px] font-bold text-gray-900 truncate max-w-[130px] sm:max-w-none">
                {{ $seller['name'] }}
            </h3>
            @if(!empty($seller['verified']))
                <x-verified-badge size="sm" class="w-4 h-4 shrink-0" />
            @endif
        </div>
        <!-- Role -->
        <span class="text-xs text-gray-400 font-medium mt-0.5 mb-3.5">{{ $seller['role'] }}</span>
        <!-- Stats Row -->
        <div class="flex items-center justify-center gap-3 text-xs sm:text-[12.5px] font-medium text-gray-500 mb-4 w-full">
            <span>{{ $seller['itemCount'] }} Barang</span>
            <span class="inline-flex items-center gap-1 text-gray-700 font-semibold">
                <svg class="w-3.5 h-3.5 text-[#F59E0B] fill-[#F59E0B]" viewBox="0 0 24 24">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                <span>{{ number_format($seller['rating'], 1) }}</span>
            </span>
        </div>
    </div>
    <!-- CTA Button -->
    <a
        href="{{ $seller['name'] === 'Rachel Vennya' ? '/seller/rachel-vennya' : '#toko-' . $seller['id'] }}"
        class="w-full py-2.5 rounded-xl border border-purple-200/90 text-[#4F26A6] font-bold text-xs sm:text-[13.5px] hover:bg-[#4F26A6] hover:text-white transition-all text-center"
    >
        Lihat Toko
    </a>
</div>
