<x-layouts.app :title="$title" activeTab="profile">
    <main class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs sm:text-[13px] text-gray-500 font-medium mb-6">
            <a href="/" class="hover:text-[#4F26A6] transition-colors cursor-pointer">Beranda</a>
            <span class="text-gray-300 font-normal">&gt;</span>
            <span class="text-gray-500">Pengaturan Akun</span>
            <span class="text-gray-300 font-normal">&gt;</span>
            <span class="text-gray-900 font-bold">Ganti Password</span>
        </nav>

        <div class="max-w-2xl mx-auto">
            <!-- Header Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 md:p-10 border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.03)] space-y-6">
                <div class="flex items-start justify-between gap-4 pb-6 border-b border-gray-100">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#F3EEFF] text-[#4F26A6] text-xs font-bold mb-3">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 11V7a5 5 0 0110 0v4"/>
                            </svg>
                            <span>Keamanan Akun</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black text-gray-950 tracking-tight">
                            Ganti Password
                        </h1>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            Perbarui kata sandi akunmu secara berkala untuk menjaga keamanan data dan transaksi belanja.
                        </p>
                    </div>

                    <div class="w-12 h-12 rounded-2xl bg-[#F3EEFF] text-[#4F26A6] flex items-center justify-center shrink-0 shadow-2xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                    </div>
                </div>

                @if(session('success'))
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm space-y-1">
                        <p class="font-bold flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>Gagal memperbarui password:</span>
                        </p>
                        <ul class="list-disc list-inside text-xs mt-1 space-y-0.5">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Change Password Form -->
                <form
                    action="/akun/password"
                    method="POST"
                    x-data="{
                        showCurrent: false,
                        showNew: false,
                        showConfirm: false,
                        submitting: false
                    }"
                    @submit="submitting = true"
                    class="space-y-5"
                >
                    @csrf
                    @method('PUT')

                    @if(!empty(auth()->user()->password))
                        <!-- Password Saat Ini -->
                        <div>
                            <label for="current_password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Password Saat Ini <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    :type="showCurrent ? 'text' : 'password'"
                                    id="current_password"
                                    name="current_password"
                                    required
                                    placeholder="Masukkan password saat ini"
                                    class="w-full rounded-2xl border border-gray-200 bg-white pl-4 pr-11 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"
                                />
                                <button
                                    type="button"
                                    @click="showCurrent = !showCurrent"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors cursor-pointer"
                                    tabindex="-1"
                                >
                                    <svg x-show="!showCurrent" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showCurrent" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Password Baru -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Password Baru <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                :type="showNew ? 'text' : 'password'"
                                id="password"
                                name="password"
                                required
                                minlength="8"
                                placeholder="Minimal 8 karakter"
                                class="w-full rounded-2xl border border-gray-200 bg-white pl-4 pr-11 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"
                            />
                            <button
                                type="button"
                                @click="showNew = !showNew"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors cursor-pointer"
                                tabindex="-1"
                            >
                                <svg x-show="!showNew" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showNew" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                        <p class="text-[11.5px] text-gray-500 mt-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            <span>Gunakan minimal 8 karakter dengan kombinasi huruf dan angka.</span>
                        </p>
                    </div>

                    <!-- Konfirmasi Password Baru -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Konfirmasi Password Baru <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                :type="showConfirm ? 'text' : 'password'"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                minlength="8"
                                placeholder="Ulangi password baru"
                                class="w-full rounded-2xl border border-gray-200 bg-white pl-4 pr-11 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"
                            />
                            <button
                                type="button"
                                @click="showConfirm = !showConfirm"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors cursor-pointer"
                                tabindex="-1"
                            >
                                <svg x-show="!showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showConfirm" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Actions -->
                    <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <a
                            href="{{ auth()->user()->isSeller() ? route('seller.settings') : '/' }}"
                            class="w-full sm:w-auto px-5 py-3 rounded-2xl border border-gray-200 text-gray-600 hover:text-gray-900 hover:bg-gray-50 text-xs sm:text-sm font-bold text-center transition-all cursor-pointer"
                        >
                            Batal
                        </a>
                        <button
                            type="submit"
                            id="btn-update-password"
                            :disabled="submitting"
                            class="w-full sm:w-auto px-7 py-3 rounded-2xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white text-xs sm:text-sm font-bold shadow-md shadow-[#4F26A6]/25 transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
                        >
                            <span x-show="!submitting">Perbarui Password</span>
                            <span x-show="submitting" x-cloak class="inline-flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4 text-white" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</x-layouts.app>
