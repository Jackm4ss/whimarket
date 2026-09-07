<x-layouts.app :title="$title . ' - WhiMarket'">
    <main class="w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-10 sm:py-14">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs sm:text-[13px] text-gray-500 font-medium mb-6">
            <a href="/" class="hover:text-[#4F26A6] transition-colors">Beranda</a>
            <span class="text-gray-300 font-normal">&gt;</span>
            <span class="text-gray-900 font-bold">{{ $title }}</span>
        </nav>

        <!-- Content Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 lg:p-12 border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.03)]">
            <div class="max-w-3xl">
                <!-- Header Badge & Icon -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#F3EEFF] text-[#4F26A6] text-xs font-bold mb-4">
                    <span>WhiMarket Information</span>
                </div>

                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#111827] tracking-tight mb-4 leading-tight">
                    {{ $title }}
                </h1>

                <p class="text-sm sm:text-base text-gray-500 leading-relaxed mb-8">
                    {{ $description }}
                </p>

                <div class="h-[1px] bg-gray-100 w-full mb-8"></div>

                <!-- Fallback Body Content -->
                <div class="space-y-6 text-sm sm:text-[15px] text-gray-700 leading-relaxed">
                    @foreach($sections as $section)
                        <div class="bg-[#FAF9FC] rounded-2xl p-5 sm:p-6 border border-gray-100">
                            <h2 class="text-base sm:text-lg font-bold text-gray-900 mb-2.5">
                                {{ $section['heading'] }}
                            </h2>
                            <p class="text-gray-600 leading-relaxed">
                                {{ $section['content'] }}
                            </p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-10 flex flex-wrap items-center gap-4">
                    <a
                        href="/belanja"
                        class="px-6 py-3 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white text-sm font-bold shadow-md shadow-[#4F26A6]/20 transition-all inline-flex items-center gap-2"
                    >
                        <span>Mulai Belanja Sekarang</span>
                        <span>&rarr;</span>
                    </a>
                    <a
                        href="/"
                        class="px-6 py-3 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-bold transition-all"
                    >
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>
