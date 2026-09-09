@props([
    'livewire' => null,
])

<x-filament-panels::layout.base :livewire="$livewire">
    @push('styles')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css'])
        <style>
            body, .fi-body, input, button, select, textarea {
                font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;
            }
        </style>
    @endpush

    <div class="min-h-screen w-full bg-[#F4F5FA] text-[#111827] flex flex-col selection:bg-[#4F26A6] selection:text-white antialiased">
        <!-- Top Navigation Bar ala E-Commerce Portal -->
        <header class="w-full bg-white/95 backdrop-blur-md border-b border-gray-200/80 sticky top-0 z-30 px-4 sm:px-8 py-3 sm:py-3.5 flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-3">
                <a href="/" class="flex items-center gap-2 group transition-opacity hover:opacity-90" title="Kembali ke Beranda WhiMarket">
                    <img src="/assets/logo-whimarket.png" alt="WhiMarket" class="h-7 sm:h-8 w-auto object-contain" />
                </a>
            </div>

            <a
                href="/"
                class="inline-flex items-center gap-2 sm:gap-2.5 px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-full bg-white hover:bg-[#FAF9FC] text-gray-800 hover:text-[#4F26A6] border border-gray-200/90 hover:border-[#4F26A6]/35 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-[0_6px_20px_rgba(79,38,166,0.12)] transition-all duration-200 active:scale-[0.97] group cursor-pointer select-none"
            >
                <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#F3EEFF] text-[#4F26A6] group-hover:bg-[#4F26A6] group-hover:text-white flex items-center justify-center transition-all duration-200 shrink-0 shadow-2xs">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <span class="font-extrabold text-xs sm:text-[13px] tracking-tight">
                    <span class="hidden sm:inline">Kembali ke Halaman Utama</span>
                    <span class="sm:hidden">Halaman Utama</span>
                </span>
                <svg class="w-3.5 h-3.5 text-gray-300 group-hover:text-[#4F26A6] transition-transform duration-200 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col justify-center items-center py-8 sm:py-14 px-4 sm:px-6">
            {{ $slot }}
        </main>

        <!-- Clean Subtle Footer -->
        <footer class="w-full py-4 text-center border-t border-gray-200/60 text-[11px] sm:text-xs text-gray-400">
            <span>&copy; {{ date('Y') }} WhiMarket. Hak Cipta Dilindungi.</span>
        </footer>
    </div>
</x-filament-panels::layout.base>
