<x-layouts.app :title="$title . ' - WhiMarket'">
    <main class="w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-12">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs sm:text-[13px] text-gray-500 font-medium mb-6">
            <a href="/" class="hover:text-[#4F26A6] transition-colors">Beranda</a>
            <span class="text-gray-300 font-normal">&gt;</span>
            <span class="text-gray-900 font-bold">{{ $title }}</span>
        </nav>

        <!-- Hero Card with Rich Visuals & 3D Illustration -->
        <div class="relative bg-white rounded-3xl p-6 sm:p-10 lg:p-12 border border-gray-100 shadow-[0_4px_30px_rgba(0,0,0,0.03)] overflow-hidden mb-8 sm:mb-10">
            <!-- Decorative Subtle Purple & Yellow Glows -->
            <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-[#EDE4FF]/50 blur-3xl pointer-events-none"></div>
            <div class="absolute right-40 -bottom-20 w-64 h-64 rounded-full bg-[#FEF3C7]/40 blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col-reverse lg:flex-row items-center justify-between gap-8 lg:gap-12">
                <!-- Left: Typography & Value Props -->
                <div class="flex-1 max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#F3EEFF] text-[#4F26A6] text-xs font-extrabold mb-4 shadow-2xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span>WhiMarket Official Guide</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl xl:text-[42px] font-black text-[#111827] tracking-tight mb-4 leading-[1.2]">
                        {{ $title }}
                    </h1>

                    <p class="text-sm sm:text-base lg:text-[16.5px] text-gray-600 leading-relaxed mb-6 font-normal">
                        {{ $description }}
                    </p>

                    <!-- Quick Highlight Stats / Bullets -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 pt-2 mb-8">
                        <div class="bg-[#FAF9FC] rounded-2xl p-3.5 border border-gray-100/90 text-left">
                            <span class="text-xs text-gray-400 font-medium block">Standar Layanan</span>
                            <span class="text-sm sm:text-[15px] font-black text-[#4F26A6] mt-0.5 block">100% Terverifikasi</span>
                        </div>
                        <div class="bg-[#FAF9FC] rounded-2xl p-3.5 border border-gray-100/90 text-left">
                            <span class="text-xs text-gray-400 font-medium block">Keamanan Dana</span>
                            <span class="text-sm sm:text-[15px] font-black text-[#4F26A6] mt-0.5 block">Sistem Escrow</span>
                        </div>
                        <div class="bg-[#FAF9FC] rounded-2xl p-3.5 border border-gray-100/90 text-left col-span-2 sm:col-span-1">
                            <span class="text-xs text-gray-400 font-medium block">Dukungan Seller</span>
                            <span class="text-sm sm:text-[15px] font-black text-[#4F26A6] mt-0.5 block">24/7 Ramah</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4 w-full">
                        <a
                            href="/belanja"
                            class="w-full sm:w-auto text-center justify-center px-6 sm:px-7 py-3 sm:py-3.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white text-xs sm:text-sm font-bold shadow-lg shadow-[#4F26A6]/20 transition-all inline-flex items-center gap-2 cursor-pointer hover:scale-[1.02]"
                        >
                            <span>Eksplor Belanja</span>
                            <span>&rarr;</span>
                        </a>
                        <a
                            href="/"
                            class="w-full sm:w-auto text-center justify-center px-5 sm:px-6 py-3 sm:py-3.5 rounded-xl bg-white border-2 border-purple-200/90 hover:bg-[#4F26A6]/5 text-[#4F26A6] text-xs sm:text-sm font-bold transition-all cursor-pointer"
                        >
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>

                <!-- Right: Generated 3D Asset in Stylized Frame -->
                <div class="w-full sm:w-[320px] lg:w-[360px] xl:w-[400px] shrink-0 flex items-center justify-center">
                    <div class="relative w-full aspect-square max-w-[340px] sm:max-w-[380px] rounded-3xl bg-gradient-to-b from-[#F3EEFF]/60 via-[#FAF9FC] to-white p-4 sm:p-6 border border-purple-100/80 shadow-[0_12px_36px_rgba(79,38,166,0.06)] flex items-center justify-center group">
                        <img
                            src="{{ $image ?? '/assets/info/cara-jual.png' }}"
                            alt="{{ $title }}"
                            class="w-full h-full object-contain drop-shadow-md group-hover:scale-105 transition-transform duration-300"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Sections Cards Grid -->
        <div class="mb-10">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg sm:text-xl font-extrabold text-[#111827] tracking-tight">
                        Rincian &amp; Ketentuan Lengkap
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Informasi komprehensif untuk mendukung pengalaman transaksi terbaikmu di WhiMarket.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6">
                @foreach($sections as $index => $section)
                    <div class="bg-white rounded-2xl sm:rounded-3xl p-6 sm:p-7 border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.02)] hover:shadow-[0_8px_30px_rgba(79,38,166,0.08)] hover:-translate-y-1 transition-all flex flex-col justify-between group">
                        <div>
                            <!-- Number Indicator -->
                            <div class="w-9 h-9 rounded-xl bg-[#EDE4FF] text-[#4F26A6] font-black text-sm flex items-center justify-center mb-4 group-hover:bg-[#4F26A6] group-hover:text-white transition-colors">
                                0{{ $index + 1 }}
                            </div>

                            <h3 class="text-[15.5px] sm:text-[16.5px] font-bold text-gray-900 mb-2.5 leading-snug">
                                {{ $section['heading'] }}
                            </h3>

                            <p class="text-xs sm:text-[13.5px] text-gray-600 leading-relaxed font-normal">
                                {{ $section['content'] }}
                            </p>
                        </div>

                        <div class="pt-5 mt-5 border-t border-gray-100 flex items-center gap-1.5 text-xs font-bold text-[#4F26A6]">
                            <span>Pelajari lebih lanjut</span>
                            <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Interactive FAQ / Help Banner at the Bottom -->
        <div class="bg-gradient-to-r from-[#4F26A6] to-[#6A39D4] rounded-3xl p-6 sm:p-8 lg:p-10 text-white flex flex-col sm:flex-row items-center justify-between gap-6 shadow-[0_14px_40px_rgba(79,38,166,0.25)]">
            <div class="max-w-xl">
                <span class="text-xs font-extrabold uppercase tracking-wider text-yellow-300">Pusat Bantuan WhiMarket</span>
                <h3 class="text-lg sm:text-2xl font-black mt-1 leading-tight">Ada pertanyaan lain seputar {{ $title }}?</h3>
                <p class="text-xs sm:text-sm text-purple-100 mt-2 leading-relaxed font-normal">
                    Tim Customer Support kami siap mendampingi kamu dalam setiap langkah proses jual beli dan verifikasi akun.
                </p>
            </div>
            <div class="w-full sm:w-auto flex items-center justify-center shrink-0">
                <a
                    href="/hubungi-kami"
                    class="w-full sm:w-auto text-center px-6 py-3 rounded-xl bg-[#FDBA2D] hover:bg-[#F59E0B] text-gray-900 text-xs sm:text-sm font-bold shadow-md transition-all active:scale-95 cursor-pointer"
                >
                    Hubungi Customer Care
                </a>
            </div>
        </div>
    </main>
</x-layouts.app>
