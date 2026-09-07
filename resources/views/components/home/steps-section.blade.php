@props(['steps'])

<section id="cara-kerja" class="w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-2 pb-10 sm:pb-14">
    <!-- Section Header -->
    <div class="mb-8 sm:mb-12">
        <h2 class="text-xl sm:text-2xl lg:text-[28px] font-extrabold text-[#111827] tracking-tight">
            Cara Kerja
        </h2>
    </div>

    <!-- Steps Grid / Row with Connecting Lines -->
    <div class="relative grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 sm:gap-10 lg:gap-6 items-start">
        <!-- Desktop Connecting Dashed Lines -->
        <div class="hidden lg:block absolute top-[55px] left-[20%] w-[10%] border-t-2 border-dashed border-gray-300 z-0 pointer-events-none"></div>
        <div class="hidden lg:block absolute top-[55px] left-[45%] w-[10%] border-t-2 border-dashed border-gray-300 z-0 pointer-events-none"></div>
        <div class="hidden lg:block absolute top-[55px] left-[70%] w-[10%] border-t-2 border-dashed border-gray-300 z-0 pointer-events-none"></div>

        <!-- Mobile Continuous Curved Zigzag Trail with exact coordinates and color from React -->
        <svg class="block sm:hidden absolute inset-0 w-full h-full pointer-events-none z-0" viewBox="0 0 100 900" preserveAspectRatio="none" fill="none">
            <path
                d="M 70.8 90.3 C 92 125, 98 175, 93 220 C 88 245, 78 260, 70.0 272.9 C 45 315, 6 385, 9 445 C 12 475, 20 493, 28.3 507.1 C 45 530, 95 615, 92 680 C 88 715, 80 735, 71.4 752.4"
                stroke="#E2E8F0"
                stroke-width="2"
                stroke-linecap="round"
            />
        </svg>

        <!-- Step 1: Temukan Barang Favorit -->
        <div class="relative z-10 flex flex-col items-center text-center group">
            <div class="relative w-full h-[110px] flex items-center justify-center mb-5">
                <!-- Desktop Original Badge (top-left left-8) -->
                <div class="hidden lg:flex absolute -top-1 left-8 w-6 h-6 rounded-full bg-[#4F26A6] text-white font-extrabold text-xs items-center justify-center shadow-xs">
                    1
                </div>
                
                <div class="relative inline-flex items-center justify-center">
                    <!-- Mobile/Tablet Badge on Line -->
                    <div class="flex lg:hidden absolute -right-4 sm:-right-3.5 bottom-1 w-6 h-6 rounded-full bg-[#4F26A6] text-white font-extrabold text-xs items-center justify-center shadow-md z-30 ring-2 ring-white">
                        1
                    </div>
                    <img
                        src="{{ $steps[0]['image'] }}"
                        alt="{{ $steps[0]['title'] }}"
                        class="relative z-10 max-h-[118px] sm:max-h-[125px] lg:max-h-[96px] w-auto object-contain group-hover:scale-105 transition-transform duration-300 drop-shadow-xs"
                    />
                </div>
            </div>
            <h3 class="text-lg sm:text-[18.5px] lg:text-[16.5px] font-extrabold lg:font-bold text-[#111827] mb-2 leading-snug">
                {{ $steps[0]['title'] }}
            </h3>
            <p class="text-sm sm:text-[14px] lg:text-[13.5px] text-gray-600 lg:text-gray-500 leading-relaxed max-w-[270px] sm:max-w-[280px] lg:max-w-[240px]">
                {{ $steps[0]['description'] }}
            </p>
        </div>

        <!-- Step 2: Transaksi Aman -->
        <div class="relative z-10 flex flex-col items-center text-center group">
            <div class="relative w-full h-[110px] flex items-center justify-center mb-5">
                <!-- Desktop Original Badge (top-left left-8) -->
                <div class="hidden lg:flex absolute -top-1 left-8 w-6 h-6 rounded-full bg-[#4F26A6] text-white font-extrabold text-xs items-center justify-center shadow-xs">
                    2
                </div>
                
                <div class="relative inline-flex items-center justify-center">
                    <!-- Mobile/Tablet Badge on Line -->
                    <div class="flex lg:hidden absolute -right-2 sm:-right-1 top-8 w-6 h-6 rounded-full bg-[#4F26A6] text-white font-extrabold text-xs items-center justify-center shadow-md z-30 ring-2 ring-white">
                        2
                    </div>
                    <img
                        src="{{ $steps[1]['image'] }}"
                        alt="{{ $steps[1]['title'] }}"
                        class="relative z-10 max-h-[118px] sm:max-h-[125px] lg:max-h-[96px] w-auto object-contain group-hover:scale-105 transition-transform duration-300 drop-shadow-xs"
                    />
                </div>
            </div>
            <h3 class="text-lg sm:text-[18.5px] lg:text-[16.5px] font-extrabold lg:font-bold text-[#111827] mb-2 leading-snug">
                {{ $steps[1]['title'] }}
            </h3>
            <p class="text-sm sm:text-[14px] lg:text-[13.5px] text-gray-600 lg:text-gray-500 leading-relaxed max-w-[270px] sm:max-w-[280px] lg:max-w-[240px]">
                {{ $steps[1]['description'] }}
            </p>
        </div>

        <!-- Step 3: Barang Dikirim -->
        <div class="relative z-10 flex flex-col items-center text-center group">
            <div class="relative w-full h-[110px] flex items-center justify-center mb-5">
                <!-- Desktop Original Badge (top-left left-8) -->
                <div class="hidden lg:flex absolute -top-1 left-8 w-6 h-6 rounded-full bg-[#4F26A6] text-white font-extrabold text-xs items-center justify-center shadow-xs">
                    3
                </div>
                
                <div class="relative inline-flex items-center justify-center">
                    <!-- Mobile/Tablet Badge on Line -->
                    <div class="flex lg:hidden absolute left-1 sm:left-2 top-9 w-6 h-6 rounded-full bg-[#4F26A6] text-white font-extrabold text-xs items-center justify-center shadow-md z-30 ring-2 ring-white">
                        3
                    </div>
                    <img
                        src="{{ $steps[2]['image'] }}"
                        alt="{{ $steps[2]['title'] }}"
                        class="relative z-10 max-h-[130px] sm:max-h-[138px] lg:max-h-[124px] w-auto object-contain group-hover:scale-105 transition-transform duration-300 drop-shadow-xs"
                    />
                </div>
            </div>
            <h3 class="text-lg sm:text-[18.5px] lg:text-[16.5px] font-extrabold lg:font-bold text-[#111827] mb-2 leading-snug">
                {{ $steps[2]['title'] }}
            </h3>
            <p class="text-sm sm:text-[14px] lg:text-[13.5px] text-gray-600 lg:text-gray-500 leading-relaxed max-w-[270px] sm:max-w-[280px] lg:max-w-[240px]">
                {{ $steps[2]['description'] }}
            </p>
        </div>

        <!-- Step 4: Barang Diterima -->
        <div class="relative z-10 flex flex-col items-center text-center group">
            <div class="relative w-full h-[110px] flex items-center justify-center mb-5">
                <!-- Desktop Original Badge (top-left left-8) -->
                <div class="hidden lg:flex absolute -top-1 left-8 w-6 h-6 rounded-full bg-[#4F26A6] text-white font-extrabold text-xs items-center justify-center shadow-xs">
                    4
                </div>
                
                <div class="relative inline-flex items-center justify-center">
                    <!-- Mobile/Tablet Badge on Line -->
                    <div class="flex lg:hidden absolute -right-2 sm:-right-1 top-10 w-6 h-6 rounded-full bg-[#4F26A6] text-white font-extrabold text-xs items-center justify-center shadow-md z-30 ring-2 ring-white">
                        4
                    </div>
                    <img
                        src="{{ $steps[3]['image'] }}"
                        alt="{{ $steps[3]['title'] }}"
                        class="relative z-10 max-h-[118px] sm:max-h-[125px] lg:max-h-[96px] w-auto object-contain group-hover:scale-105 transition-transform duration-300 drop-shadow-xs"
                    />
                </div>
            </div>
            <h3 class="text-lg sm:text-[18.5px] lg:text-[16.5px] font-extrabold lg:font-bold text-[#111827] mb-2 leading-snug">
                {{ $steps[3]['title'] }}
            </h3>
            <p class="text-sm sm:text-[14px] lg:text-[13.5px] text-gray-600 lg:text-gray-500 leading-relaxed max-w-[270px] sm:max-w-[280px] lg:max-w-[240px]">
                {{ $steps[3]['description'] }}
            </p>
        </div>
    </div>
</section>
