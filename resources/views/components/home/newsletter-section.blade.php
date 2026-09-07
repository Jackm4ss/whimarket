<section class="w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-4 pb-0 mb-0" x-data="{
    email: '',
    subscribed: false,
    submit() {
        if (this.email) {
            this.subscribed = true;
            alert('Terima kasih! Email ' + this.email + ' berhasil berlangganan newsletter WhiMarket.');
            this.email = '';
        }
    }
}">
    <div class="bg-[#5B27B5] rounded-2xl sm:rounded-3xl p-6 sm:p-8 lg:px-10 lg:py-8 flex flex-col lg:flex-row items-center justify-between gap-6 lg:gap-10 shadow-[0_14px_40px_rgba(91,39,181,0.22)] relative overflow-hidden">
        <!-- Left Side: Icon & Copy -->
        <div class="flex items-start sm:items-center gap-4 sm:gap-6 w-full lg:w-auto">
            <div class="shrink-0 w-11 h-11 sm:w-14 sm:h-14 flex items-center justify-center text-white mt-0.5 sm:mt-0">
                <svg class="w-9 h-9 sm:w-12 sm:h-12" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 14 C19 10, 29 10, 29 14" />
                    <rect x="7" y="14" width="34" height="26" rx="4" />
                    <path d="M7 15 L24 28 L41 15" />
                    <path d="M7 39 L18 29" />
                    <path d="M41 39 L30 29" />
                </svg>
            </div>

            <div class="flex flex-col text-white">
                <h3 class="text-[17px] sm:text-xl lg:text-[22px] font-bold tracking-tight leading-[1.35] sm:leading-tight">
                    Dapatkan Update &amp; Promo Eksklusif
                </h3>
                <p class="text-white/80 text-[13px] sm:text-sm lg:text-[14.5px] mt-2 sm:mt-1.5 font-normal leading-relaxed">
                    Berlangganan newsletter kami dan dapatkan info terbaru dari Whimarket.
                </p>
            </div>
        </div>

        <!-- Right Side: Email Input Form -->
        <form @submit.prevent="submit" class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto shrink-0">
            <div class="relative w-full sm:w-[300px] md:w-[340px] lg:w-[360px]">
                <input
                    type="email"
                    x-model="email"
                    placeholder="Masukkan email kamu..."
                    required
                    class="w-full h-11 sm:h-12 px-4 rounded-xl bg-white text-gray-800 placeholder-gray-400 text-sm sm:text-[15px] focus:outline-none focus:ring-2 focus:ring-yellow-400 shadow-xs transition-all"
                />
            </div>
            <button
                type="submit"
                class="w-full sm:w-auto h-11 sm:h-12 px-6 sm:px-7 rounded-xl bg-[#FDBA2D] hover:bg-[#F59E0B] text-[#111827] font-bold text-sm sm:text-[15px] shadow-xs hover:shadow transition-all duration-200 shrink-0 whitespace-nowrap active:scale-[0.98] cursor-pointer"
            >
                Berlangganan
            </button>
        </form>
    </div>
</section>
