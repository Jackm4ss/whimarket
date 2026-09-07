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
                            class="w-full sm:w-auto text-center justify-center px-6 sm:px-7 py-3 sm:py-3.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white text-xs sm:text-sm font-bold shadow-lg shadow-[#4F26A6]/20 transition-all inline-flex items-center gap-2 cursor-pointer hover:scale-[1.02] group"
                        >
                            <span>Eksplor Belanja</span>
                            <svg
                                class="w-4 h-4 text-white group-hover:translate-x-1 transition-transform"
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
                            decoding="async"
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
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Help / Contact Banner Matching Newsletter Source of Truth -->
        <div class="bg-[#5B27B5] rounded-2xl sm:rounded-3xl p-6 sm:p-8 lg:px-10 lg:py-8 flex flex-col lg:flex-row items-center justify-between gap-6 lg:gap-10 shadow-[0_14px_40px_rgba(91,39,181,0.22)] relative overflow-hidden">
            <!-- Left Side: Icon & Copy -->
            <div class="flex items-start sm:items-center gap-4 sm:gap-6 w-full lg:w-auto">
                <div class="shrink-0 w-11 h-11 sm:w-14 sm:h-14 flex items-center justify-center text-white mt-0.5 sm:mt-0">
                    <svg class="w-9 h-9 sm:w-12 sm:h-12" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M24 6C13.5 6 5 13.6 5 23c0 4.2 1.7 8.1 4.7 11.1L7 42l8.8-3.1C18.4 39.6 21.1 40 24 40c10.5 0 19-7.6 19-17s-8.5-17-19-17z" />
                        <circle cx="16" cy="23" r="2.2" fill="currentColor" stroke="none" />
                        <circle cx="24" cy="23" r="2.2" fill="currentColor" stroke="none" />
                        <circle cx="32" cy="23" r="2.2" fill="currentColor" stroke="none" />
                    </svg>
                </div>

                <div class="flex flex-col text-white">
                    <h3 class="text-[17px] sm:text-xl lg:text-[22px] font-bold tracking-tight leading-[1.35] sm:leading-tight">
                        Ada pertanyaan lain seputar {{ $title }}?
                    </h3>
                    <p class="text-white/80 text-[13px] sm:text-sm lg:text-[14.5px] mt-2 sm:mt-1.5 font-normal leading-relaxed">
                        Tim Customer Support kami siap mendampingi kamu dalam setiap langkah proses jual beli dan verifikasi akun.
                    </p>
                </div>
            </div>

            <!-- Right Side: Action Button -->
            <div class="w-full lg:w-auto shrink-0 flex items-center justify-start lg:justify-end">
                <a
                    href="/hubungi-kami"
                    class="w-full sm:w-auto h-11 sm:h-12 px-6 sm:px-7 rounded-xl bg-[#FDBA2D] hover:bg-[#F59E0B] text-[#111827] font-bold text-sm sm:text-[15px] shadow-xs hover:shadow transition-all duration-200 shrink-0 whitespace-nowrap active:scale-[0.98] cursor-pointer inline-flex items-center justify-center gap-2"
                >
                    <span>Hubungi Customer Care</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </main>
</x-layouts.app>
