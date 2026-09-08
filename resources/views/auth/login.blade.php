<x-layouts.app :title="$title" activeTab="">
    <main class="min-h-[calc(100vh-140px)] flex items-center justify-center py-8 sm:py-14 px-4 sm:px-6 lg:px-8 bg-[#FAF9FC]">
        <div class="w-full max-w-5xl bg-white rounded-3xl sm:rounded-[32px] border border-gray-100/90 shadow-[0_12px_40px_rgba(0,0,0,0.05)] overflow-hidden flex flex-col lg:flex-row">
            
            <!-- Left Column: Form Area -->
            <div class="w-full lg:w-[54%] p-6 sm:p-10 lg:p-12 flex flex-col justify-between">
                <div>
                    <!-- Header -->
                    <div class="mb-8">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#F3EEFF] text-[#4F26A6] text-xs font-bold mb-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#4F26A6]"></span>
                            <span>Selamat Datang Kembali</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">
                            Masuk ke Akun
                        </h1>
                        <p class="text-xs sm:text-[14px] text-gray-500 mt-1.5 leading-relaxed">
                            Akses koleksi pre-loved dan merchandise kreator favoritmu.
                        </p>
                    </div>

                    <!-- Google SSO Button -->
                    <a
                        href="{{ route('auth.google.redirect') }}"
                        class="w-full flex items-center justify-center gap-3 py-3 px-4 rounded-2xl border border-gray-200 hover:border-gray-300 hover:bg-gray-50/80 bg-white text-gray-700 font-bold text-xs sm:text-[13.5px] transition-all shadow-2xs cursor-pointer group"
                    >
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"/>
                            <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.26v3.15C3.25 21.36 7.33 24 12 24z"/>
                            <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.14-1.55.38-2.27V6.58H1.26C.46 8.16 0 9.94 0 12s.46 3.84 1.26 5.42l4.02-3.15z"/>
                            <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.25 2.64 1.26 6.58l4.02 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                        </svg>
                        <span>Masuk dengan Google</span>
                    </a>

                    <!-- Divider -->
                    <div class="relative my-6 flex items-center justify-center">
                        <div class="border-t border-gray-100 w-full"></div>
                        <span class="bg-white px-3 text-[11.5px] font-semibold text-gray-400 uppercase tracking-wider shrink-0">
                            atau email
                        </span>
                    </div>

                    <!-- Email & Password Form -->
                    <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                        @csrf

                        @if ($errors->any())
                            <div class="p-3.5 rounded-2xl bg-red-50 border border-red-100 text-red-700 text-xs font-medium space-y-1">
                                @foreach ($errors->all() as $error)
                                    <p class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>{{ $error }}</span>
                                    </p>
                                @endforeach
                            </div>
                        @endif

                        <div>
                            <label for="email" class="block text-xs font-bold text-gray-700 mb-1.5">
                                Alamat Email
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                placeholder="nama@email.com"
                                class="w-full h-11 px-4 rounded-xl bg-[#F9FAFB] border border-gray-200 text-xs sm:text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] transition-all"
                            />
                        </div>

                        <div x-data="{ show: false }">
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="block text-xs font-bold text-gray-700">
                                    Kata Sandi
                                </label>
                                <a href="#" class="text-[11.5px] font-semibold text-[#4F26A6] hover:underline">
                                    Lupa sandi?
                                </a>
                            </div>
                            <div class="relative">
                                <input
                                    :type="show ? 'text' : 'password'"
                                    id="password"
                                    name="password"
                                    required
                                    placeholder="••••••••"
                                    class="w-full h-11 pl-4 pr-10 rounded-xl bg-[#F9FAFB] border border-gray-200 text-xs sm:text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] transition-all"
                                />
                                <button
                                    type="button"
                                    @click="show = !show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors cursor-pointer"
                                >
                                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center pt-1">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded text-[#4F26A6] focus:ring-[#4F26A6]/20 border-gray-300 cursor-pointer">
                                <span class="text-xs text-gray-600 font-medium">Ingat saya di perangkat ini</span>
                            </label>
                        </div>

                        <button
                            type="submit"
                            class="w-full h-11 sm:h-12 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs sm:text-sm shadow-md shadow-[#4F26A6]/20 transition-all cursor-pointer flex items-center justify-center gap-2 active:scale-[0.99]"
                        >
                            <span>Masuk ke Akun</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </form>

                    <!-- Fast Dev Login helper for Testing -->
                    @if(!app()->isProduction())
                        <div class="mt-6 pt-5 border-t border-dashed border-gray-200">
                            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-2">Akses Cepat (Dev):</span>
                            <div class="grid grid-cols-3 gap-2">
                                <a href="{{ route('auth.dev-login', 'buyer') }}" class="px-2.5 py-1.5 rounded-lg bg-gray-100 hover:bg-[#F3EEFF] text-gray-700 hover:text-[#4F26A6] text-[11px] font-bold text-center transition-colors">
                                    Buyer Demo
                                </a>
                                <a href="{{ route('auth.dev-login', 'seller') }}" class="px-2.5 py-1.5 rounded-lg bg-gray-100 hover:bg-[#F3EEFF] text-gray-700 hover:text-[#4F26A6] text-[11px] font-bold text-center transition-colors">
                                    Seller Demo
                                </a>
                                <a href="{{ route('auth.dev-login', 'admin') }}" class="px-2.5 py-1.5 rounded-lg bg-gray-100 hover:bg-[#F3EEFF] text-gray-700 hover:text-[#4F26A6] text-[11px] font-bold text-center transition-colors">
                                    Admin Demo
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Footer Switch Link -->
                <div class="pt-6 mt-6 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-500 font-medium">
                        Belum punya akun WhiMarket?
                        <a href="{{ route('register') }}" class="text-[#4F26A6] font-bold hover:underline ml-1">
                            Daftar Sekarang
                        </a>
                    </p>
                </div>
            </div>

            <!-- Right Column: Visual Brand Hero Banner -->
            <div class="w-full lg:w-[46%] bg-gradient-to-br from-[#4F26A6] via-[#3E1D85] to-[#261058] p-8 sm:p-10 lg:p-12 text-white flex flex-col justify-between relative overflow-hidden">
                <!-- Background ambient glow -->
                <div class="absolute -right-20 -top-20 w-72 h-72 rounded-full bg-[#8E5BF5]/25 blur-3xl pointer-events-none"></div>
                <div class="absolute -left-20 -bottom-20 w-72 h-72 rounded-full bg-[#F59E0B]/20 blur-3xl pointer-events-none"></div>

                <!-- Top Brand Badge -->
                <div class="relative z-10 flex items-center justify-between">
                    <img src="/assets/logo-whimarket.png" alt="WhiMarket" class="h-8 w-auto brightness-0 invert" />
                    <span class="text-[11px] px-3 py-1 rounded-full bg-white/15 backdrop-blur-md text-white/90 font-bold">
                        100% Terverifikasi
                    </span>
                </div>

                <!-- Center 3D Illustration -->
                <div class="relative z-10 my-8 flex items-center justify-center">
                    <div class="relative">
                        <img
                            src="/assets/auth-hero.png"
                            alt="WhiMarket Pre-loved Collection"
                            class="w-60 sm:w-72 h-auto object-contain drop-shadow-[0_20px_40px_rgba(0,0,0,0.35)] rounded-2xl"
                        />
                    </div>
                </div>

                <!-- Bottom Copy -->
                <div class="relative z-10 space-y-2">
                    <div class="flex items-center gap-1 text-[#F5BA47]">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                        <span class="text-xs font-bold text-white/90 ml-1">4.9 / 5.0 Rating Toko</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-white leading-snug">
                        Barang Langsung dari Figur Favoritmu
                    </h2>
                    <p class="text-xs sm:text-[13px] text-white/75 leading-relaxed font-normal">
                        Semua transaksi dilindungi sistem rekening bersama (escrow) WhiMarket sampai barang kamu terima dengan selamat.
                    </p>
                </div>
            </div>

        </div>
    </main>
</x-layouts.app>
