<div class="w-full max-w-[420px] sm:max-w-[445px] mx-auto bg-white rounded-[28px] sm:rounded-3xl border border-gray-200/90 shadow-[0_12px_40px_rgba(0,0,0,0.04)] p-7 sm:p-9 text-center" x-data="{ showPassword: false }">
    <div class="w-18 h-18 sm:w-20 sm:h-20 rounded-full bg-[#F3EEFF] flex items-center justify-center mx-auto mb-5 shadow-xs">
        <svg class="w-9 h-9 sm:w-10 sm:h-10 text-[#4F26A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/>
        </svg>
    </div>

    <!-- Title & Subtitle -->
    <h1 class="text-2xl sm:text-[28px] font-black text-gray-950 tracking-tight mb-2">
        Login Admin
    </h1>
    <p class="text-xs sm:text-[13px] text-gray-500 leading-relaxed mb-7">
        Selamat datang kembali!<br>
        Masuk untuk mengakses dashboard admin WhiMarket.
    </p>

    <!-- Login Form -->
    <form wire:submit="authenticate" class="space-y-4 text-left">
        <!-- Email Input Field -->
        <div>
            <label for="email" class="block text-xs sm:text-[13px] font-bold text-gray-900 mb-1.5">
                Email
            </label>
            <div class="relative rounded-2xl border border-gray-200/90 bg-[#F9FAFB]/80 focus-within:bg-white focus-within:border-[#4F26A6] focus-within:ring-2 focus-within:ring-[#4F26A6]/10 transition-all overflow-hidden shadow-2xs">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>
                </div>
                <input
                    type="email"
                    id="email"
                    wire:model="data.email"
                    value="{{ $data['email'] ?? 'admin@whimarket.com' }}"
                    placeholder="admin@whimarket.com"
                    required
                    autofocus
                    class="w-full pl-10 pr-4 py-3 text-xs sm:text-sm text-gray-900 placeholder-gray-400 bg-transparent border-none focus:outline-none focus:ring-0"
                />
            </div>
            @error('data.email')
                <p class="text-[11px] text-rose-500 font-semibold mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Input Field -->
        <div>
            <label for="password" class="block text-xs sm:text-[13px] font-bold text-gray-900 mb-1.5">
                Password
            </label>
            <div class="relative rounded-2xl border border-gray-200/90 bg-[#F9FAFB]/80 focus-within:bg-white focus-within:border-[#4F26A6] focus-within:ring-2 focus-within:ring-[#4F26A6]/10 transition-all overflow-hidden shadow-2xs">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <input
                    :type="showPassword ? 'text' : 'password'"
                    id="password"
                    wire:model="data.password"
                    placeholder="Masukkan password"
                    required
                    class="w-full pl-10 pr-10 py-3 text-xs sm:text-sm text-gray-900 placeholder-gray-400 bg-transparent border-none focus:outline-none focus:ring-0"
                />
                <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition-colors cursor-pointer"
                    title="Lihat kata sandi"
                >
                    <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                    </svg>
                </button>
            </div>
            @error('data.password')
                <p class="text-[11px] text-rose-500 font-semibold mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Options Row: Remember Me & Forgot Password -->
        <div class="flex items-center pt-1 mb-2" x-data="{ checked: false }">
            <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                <button
                    type="button"
                    @click="checked = !checked; $wire.set('data.remember', checked)"
                    class="w-4 h-4 rounded-md border flex items-center justify-center transition-all cursor-pointer shrink-0"
                    :class="checked ? 'bg-[#4F26A6] border-[#4F26A6] text-white shadow-2xs' : 'bg-white border-gray-300 hover:border-[#4F26A6]'"
                >
                    <svg x-show="checked" x-cloak class="w-2.5 h-2.5 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </button>
                <span class="text-xs sm:text-[12.5px] text-gray-600 font-medium">Ingat saya</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button
            type="submit"
            wire:loading.attr="disabled"
            class="w-full py-3.5 rounded-2xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-sm sm:text-base shadow-md shadow-[#4F26A6]/20 transition-all flex items-center justify-center gap-2 active:scale-[0.98] cursor-pointer"
        >
            <span wire:loading.remove wire:target="authenticate" class="inline-flex items-center gap-2">
                <span>Login</span>
                <svg class="w-4 h-4 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </span>
            <span wire:loading wire:target="authenticate" class="inline-flex items-center gap-2">
                <svg class="animate-spin w-4 h-4 text-white" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                <span>Memproses...</span>
            </span>
        </button>
    </form>

    <!-- Info Callout Box with Exclamation Mark Icon -->
    <div class="mt-6 p-3.5 sm:p-4 rounded-2xl bg-[#F3EEFF] border border-[#4F26A6]/15 flex items-start gap-3 text-left">
        <svg class="w-5 h-5 text-[#4F26A6] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-[12px] sm:text-[12.5px] text-gray-700 leading-relaxed">
            Akses ini khusus untuk tim admin WhiMarket.<br>
            Pastikan Anda menggunakan akun yang terdaftar.
        </p>
    </div>
    <!-- Secondary Action: Back to Home -->
    <div class="mt-6 pt-5 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-2.5 text-xs text-center sm:text-left">
        <span class="text-gray-500">Bukan tim admin?</span>
        <a href="/" class="font-bold text-[#4F26A6] hover:text-[#3E1D85] hover:underline inline-flex items-center gap-1">
            <span>Kembali ke Halaman Utama</span>
            <span aria-hidden="true">&rarr;</span>
        </a>
    </div>

    <!-- Bottom Brand Logo & Tagline -->
    <div class="mt-7 sm:mt-8 text-center flex flex-col items-center">
        <a href="/" class="inline-block transition-transform hover:scale-105" title="Kunjungi Halaman Utama WhiMarket">
            <img src="/assets/logo-whimarket.png" alt="WhiMarket" class="h-6 sm:h-7 w-auto object-contain mb-1" />
        </a>
        <a href="/" class="text-[11px] sm:text-xs text-gray-400 hover:text-[#4F26A6] font-medium transition-colors">
            Marketplace Pre-loved &amp; Merchandise &bull; <strong class="font-semibold text-gray-500">whimarket.com</strong>
        </a>
    </div>
</div>
