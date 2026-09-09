<x-layouts.app :title="$title" activeTab="">
    <main
        class="min-h-[calc(100vh-140px)] flex items-center justify-center py-10 sm:py-16 px-4 sm:px-6 bg-[#FAF9FC]"
        x-data="{
            role: '{{ old('role', $initialRole ?? 'buyer') }}',
            showPassword: false
        }"
    >
        <div class="w-full max-w-[480px] bg-white rounded-3xl border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-6 sm:p-9 text-left">
            
            <!-- WhiMarket Centered Brand Logo -->
            <div class="text-center mb-6">
                <a href="/" class="inline-block hover:opacity-90 transition-opacity">
                    <img src="/assets/logo-whimarket.png" alt="WhiMarket" class="h-9 w-auto mx-auto" />
                </a>
            </div>

            <!-- Role Selector: Buyer vs Seller Tabs -->
            <div class="grid grid-cols-2 p-1 bg-gray-100/90 rounded-2xl mb-6 select-none">
                <button
                    type="button"
                    @click="role = 'buyer'"
                    class="py-2.5 rounded-xl text-xs sm:text-[13px] font-bold transition-all flex items-center justify-center gap-2 cursor-pointer"
                    :class="role === 'buyer' ? 'bg-white text-[#4F26A6] shadow-xs' : 'text-gray-500 hover:text-gray-900'"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span>Sebagai Pembeli</span>
                </button>
                <button
                    type="button"
                    @click="role = 'seller'"
                    class="py-2.5 rounded-xl text-xs sm:text-[13px] font-bold transition-all flex items-center justify-center gap-2 cursor-pointer"
                    :class="role === 'seller' ? 'bg-[#4F26A6] text-white shadow-xs' : 'text-gray-500 hover:text-gray-900'"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>Sebagai Seller</span>
                </button>
            </div>

            <!-- Header Titles based on Role -->
            <div class="mb-6">
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight" x-text="role === 'seller' ? 'Masuk ke Portal Seller' : 'Masuk ke Akun Pembeli'">
                </h1>
                <p class="text-xs sm:text-[13.5px] text-gray-500 mt-1 leading-relaxed" x-text="role === 'seller' ? 'Kelola pesanan, katalog merchandise, dan pencairan saldo tokomu.' : 'Beli barang pre-loved & merchandise original langsung dari kreator idolamu.'">
                </p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="role" :value="role" />

                @if ($errors->any())
                    <div class="p-3.5 rounded-xl bg-red-50 border border-red-100 text-red-700 text-xs font-medium space-y-1">
                        @foreach ($errors->all() as $error)
                            <p class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $error }}</span>
                            </p>
                        @endforeach
                    </div>
                @endif

                @if (session('info'))
                    <div class="p-3.5 rounded-xl bg-purple-50 border border-purple-100 text-[#4F26A6] text-xs font-medium">
                        {{ session('info') }}
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

                <div>
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
                            :type="showPassword ? 'text' : 'password'"
                            id="password"
                            name="password"
                            required
                            placeholder="••••••••"
                            class="w-full h-11 pl-4 pr-10 rounded-xl bg-[#F9FAFB] border border-gray-200 text-xs sm:text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] transition-all"
                        />
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors cursor-pointer"
                        >
                            <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center pt-0.5">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-[#4F26A6] accent-[#4F26A6] focus:ring-[#4F26A6]/20 border-gray-300 cursor-pointer">
                        <span class="text-xs text-gray-600 font-medium">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full h-11 sm:h-12 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs sm:text-sm shadow-md shadow-[#4F26A6]/20 transition-all cursor-pointer flex items-center justify-center gap-2 active:scale-[0.99]"
                >
                    <span x-text="role === 'seller' ? 'Masuk ke Portal Seller' : 'Masuk ke Akun'"></span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <!-- Dev Quick Access Shortcuts for Testing -->
            @if(!app()->isProduction())
                <div class="mt-6 pt-5 border-t border-dashed border-gray-200">
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-2 text-center">Akses Cepat (Dev Login):</span>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <a href="{{ route('auth.dev-login', 'buyer') }}" class="px-2 py-1.5 rounded-lg bg-gray-50 hover:bg-[#F3EEFF] text-gray-700 hover:text-[#4F26A6] text-[11px] font-bold text-center transition-colors">
                            Buyer Demo
                        </a>
                        <a href="{{ route('auth.dev-login', 'seller') }}" class="px-2 py-1.5 rounded-lg bg-gray-50 hover:bg-[#F3EEFF] text-gray-700 hover:text-[#4F26A6] text-[11px] font-bold text-center transition-colors">
                            Celloszx
                        </a>
                        <a href="{{ route('auth.dev-login', 'bintang') }}" class="px-2 py-1.5 rounded-lg bg-[#F3EEFF] text-[#4F26A6] text-[11px] font-bold text-center transition-colors border border-[#4F26A6]/20">
                            Bintang (E2E)
                        </a>
                        <a href="{{ route('auth.dev-login', 'admin') }}" class="px-2 py-1.5 rounded-lg bg-gray-50 hover:bg-[#F3EEFF] text-gray-700 hover:text-[#4F26A6] text-[11px] font-bold text-center transition-colors">
                            Admin Demo
                        </a>
                    </div>
                </div>
            @endif

            <!-- Switch to Register Link -->
            <div class="pt-5 mt-5 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-500 font-medium">
                    <span x-text="role === 'seller' ? 'Belum punya toko resmi?' : 'Belum punya akun WhiMarket?'"></span>
                    <a :href="'{{ route('register') }}?role=' + role" class="text-[#4F26A6] font-bold hover:underline ml-1">
                        Daftar Sekarang
                    </a>
                </p>
            </div>

        </div>
    </main>
</x-layouts.app>
