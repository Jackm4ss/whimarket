<x-layouts.app :title="$title" activeTab="seller">
    <main class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-12">
        <div class="max-w-2xl mx-auto">
            <!-- Header Badge -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#F3EEFF] text-[#4F26A6] text-xs font-bold mb-3 shadow-2xs">
                    <x-verified-badge size="sm" class="w-3.5 h-3.5" />
                    <span>Program Kreator &amp; Publik Figur Terverifikasi</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-950 tracking-tight">
                    Aktivasi Toko Seller WhiMarket
                </h1>
                <p class="text-sm sm:text-[15px] text-gray-500 mt-2 max-w-lg mx-auto leading-relaxed">
                    Masukkan Kode Akses VIP khusus yang telah diberikan oleh Admin WhiMarket untuk mengaktifkan tokomu seketika.
                </p>
            </div>

            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Activation Card -->
            <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_12px_40px_rgba(79,38,166,0.06)] p-6 sm:p-10">
                <form action="{{ route('seller.register.submit') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- VIP Code Input -->
                    <div>
                        <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">
                            Kode Akses VIP (Wajib) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                name="code"
                                value="{{ old('code', request()->query('code')) }}"
                                placeholder="Contoh: WHI-VIP-XXXX"
                                required
                                class="w-full uppercase font-mono tracking-widest text-base sm:text-lg font-bold rounded-2xl border-2 border-purple-200 bg-[#FDFBFF] px-4 py-3.5 text-[#4F26A6] focus:border-[#4F26A6] focus:bg-white focus:ring-4 focus:ring-[#4F26A6]/15 transition-all outline-none"
                            />
                            <div class="absolute right-4 top-1/2 -translate-y-1/2">
                                <span class="text-xs font-bold text-[#4F26A6] bg-[#F3EEFF] px-2.5 py-1 rounded-lg">VIP Code</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Belum punya kode? Hubungi Admin via DM Instagram / TikTok <strong>@whimarket</strong>.</span>
                        </p>
                    </div>

                    <div class="border-t border-gray-100 my-4"></div>

                    <!-- Store Name -->
                    <div>
                        <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">
                            Nama Toko / Brand <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="store_name"
                            value="{{ old('store_name', auth()->user()->name . ' Official') }}"
                            placeholder="Contoh: Rachel Vennya Official / Jerome Merch"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"
                        />
                    </div>

                    <!-- Bio -->
                    <div>
                        <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">
                            Deskripsi Singkat / Bio Toko
                        </label>
                        <textarea
                            name="bio"
                            rows="2"
                            placeholder="Jelaskan jenis barang yang kamu jual (pre-loved, merchandise resmi, dll)"
                            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none resize-none"
                        >{{ old('bio') }}</textarea>
                    </div>

                    <!-- Bank Details for Payout -->
                    <div class="bg-[#FAF9FC] rounded-2xl p-4 sm:p-5 border border-purple-100/70 space-y-4">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-bold uppercase tracking-wider text-[#4F26A6]">Rekening Bank Pencairan Dana (Payout)</span>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Bank</label>
                                <select
                                    name="bank_name"
                                    required
                                    class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"
                                >
                                    <option value="BCA" selected>Bank BCA</option>
                                    <option value="Mandiri">Bank Mandiri</option>
                                    <option value="BNI">Bank BNI</option>
                                    <option value="BRI">Bank BRI</option>
                                    <option value="BSI">Bank Syariah Indonesia</option>
                                    <option value="CIMB">CIMB Niaga</option>
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Nomor Rekening</label>
                                <input
                                    type="text"
                                    name="bank_account_number"
                                    value="{{ old('bank_account_number') }}"
                                    placeholder="Contoh: 8271928392"
                                    required
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Pemilik Rekening</label>
                            <input
                                type="text"
                                name="bank_account_name"
                                value="{{ old('bank_account_name', auth()->user()->name) }}"
                                placeholder="Harus sesuai dengan buku tabungan"
                                required
                                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"
                            />
                        </div>
                    </div>

                    <!-- Trust Note -->
                    <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200/60 flex items-center gap-2.5 text-xs text-amber-950">
                        <span class="font-handwriting text-lg text-[#F59E0B] font-bold shrink-0">Penting:</span>
                        <span>Setelah kode akses diverifikasi, tokomu langsung mendapatkan badge <strong>Verified Rosette</strong> dan dapat langsung menambah produk.</span>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full py-4 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-base shadow-lg shadow-[#4F26A6]/25 transition-all active:scale-[0.98] cursor-pointer flex items-center justify-center gap-2"
                    >
                        <x-verified-badge size="sm" class="w-4 h-4 text-white" />
                        <span>Verifikasi Kode &amp; Aktifkan Toko Sekarang</span>
                    </button>
                </form>
            </div>
        </div>
    </main>
</x-layouts.app>
