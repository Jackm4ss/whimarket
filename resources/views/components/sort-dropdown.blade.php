@props([
    'options' => [
        'terbaru' => 'Urutan: Terbaru',
        'harga-rendah' => 'Harga Terendah',
        'harga-tinggi' => 'Harga Tertinggi',
        'terpopuler' => 'Terpopuler',
    ]
])

<div class="relative shrink-0" @click.outside="sortDropdownOpen = false">
    <button
        type="button"
        @click="sortDropdownOpen = !sortDropdownOpen"
        class="w-full inline-flex items-center justify-between gap-2.5 bg-white border border-gray-200 text-xs sm:text-[13px] font-semibold text-gray-800 rounded-xl px-3.5 sm:px-4 py-2 sm:py-2.5 focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] shadow-xs hover:border-gray-300 transition-all cursor-pointer whitespace-nowrap"
    >
        <span x-text="{
            @foreach($options as $val => $lbl)
                '{{ $val }}': '{{ $lbl }}',
            @endforeach
        }[sortBy] || 'Urutan: Terbaru'"></span>
        <svg
            class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200 shrink-0"
            :class="sortDropdownOpen ? 'rotate-180 text-[#4F26A6]' : ''"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Custom Floating Sort Options Panel -->
    <div
        x-show="sortDropdownOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-1"
        class="absolute right-0 top-full mt-1.5 min-w-full sm:min-w-[170px] bg-white border border-gray-100 rounded-2xl shadow-[0_12px_36px_rgba(0,0,0,0.12)] p-1.5 z-40 space-y-0.5 font-medium text-xs sm:text-[13px] text-gray-700"
    >
        @foreach($options as $val => $lbl)
            <button
                type="button"
                @click="sortBy = '{{ $val }}'; sortDropdownOpen = false"
                class="w-full text-left px-3.5 py-2 rounded-xl flex items-center hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors cursor-pointer whitespace-nowrap"
                :class="sortBy === '{{ $val }}' ? 'bg-[#F3EEFF] text-[#4F26A6] font-bold' : ''"
            >
                <span>{{ $lbl }}</span>
            </button>
        @endforeach
    </div>
</div>
