<x-layouts.app :title="$title" activeTab="checkout">
    <main
        class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-12"
        x-data="paymentUploadManager"
    >
        <div class="max-w-2xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-50 text-amber-800 text-xs font-bold mb-3 border border-amber-200/60">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" stroke-width="2"/><path stroke-width="2" d="M12 6v6l4 2"/>
                    </svg>
                    <span>Batas Pembayaran: 24 Jam</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-950 tracking-tight">
                    Selesaikan Pembayaran
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Pesanan <span class="font-mono font-bold text-[#4F26A6]">#{{ $order->order_number }}</span>
                </p>
            </div>

            <!-- Transfer Instruction Card -->
            <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_12px_40px_rgba(79,38,166,0.06)] p-6 sm:p-10 space-y-6">
                <!-- Bank Info Box -->
                <div class="bg-[#FAF9FC] rounded-2xl p-5 sm:p-6 border border-purple-100/70 space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 bg-[#00529B] text-white font-black text-xs rounded-lg tracking-wider">BCA</span>
                            <span class="text-sm font-bold text-gray-900">Bank Central Asia (BCA)</span>
                        </div>
                        <span class="text-xs text-gray-400 font-medium">Rekening Resmi WhiMarket</span>
                    </div>

                    <!-- Nomor Rekening -->
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Nomor Rekening Tujuan</span>
                        <div class="flex items-center justify-between gap-3">
                            <span class="font-mono text-xl sm:text-2xl font-black text-gray-950 tracking-wider">8271 9283 9182</span>
                            <button
                                type="button"
                                @click="copyText('827192839182', 'rek')"
                                class="px-3.5 py-1.5 rounded-xl border border-purple-200 text-[#4F26A6] hover:bg-[#F3EEFF] text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <rect x="9" y="9" width="13" height="13" rx="2" stroke-width="2"/><path stroke-width="2" d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                                </svg>
                                <span x-text="copiedRek ? 'Tersalin!' : 'Salin'"></span>
                            </button>
                        </div>
                        <span class="text-xs text-gray-500 mt-1 block">a/n <strong>PT WHIMARKET INDONESIA DIGITAL</strong></span>
                    </div>

                    <!-- Nominal Tagihan -->
                    <div class="pt-3 border-t border-gray-100">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Total Nominal yang Harus Ditransfer</span>
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-2xl sm:text-3xl font-black text-[#4F26A6] tracking-tight">
                                Rp {{ number_format((float)$order->grand_total, 0, ',', '.') }}
                            </span>
                            <button
                                type="button"
                                @click="copyText('{{ (int)$order->grand_total }}', 'amount')"
                                class="px-3.5 py-1.5 rounded-xl border border-purple-200 text-[#4F26A6] hover:bg-[#F3EEFF] text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <rect x="9" y="9" width="13" height="13" rx="2" stroke-width="2"/><path stroke-width="2" d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                                </svg>
                                <span x-text="copiedAmount ? 'Tersalin!' : 'Salin'"></span>
                            </button>
                        </div>
                    </div>
                </div>

                @if(!empty($isOutOfStock))
                    <div class="mt-6 p-6 rounded-2xl bg-rose-50 border border-rose-200 text-center space-y-3">
                        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-extrabold text-gray-900">Stok Barang Ini Telah Habis</h3>
                        <p class="text-xs sm:text-sm text-gray-600 max-w-md mx-auto leading-relaxed">
                            Penjual telah memperbarui stok barang untuk pesanan ini menjadi habis. Anda tidak dapat melanjutkan proses pembayaran.
                        </p>
                        <div class="pt-2">
                            <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-900 hover:bg-black text-white text-xs font-bold transition-all">
                                &larr; Kembali ke Daftar Pesanan
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Proof Upload Form -->
                    <form
                        action="{{ route('payment.upload', $order->order_number) }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="space-y-5 pt-2"
                        @submit="handleSubmit($event)"
                    >
                    @csrf

                    <div>
                        <h3 class="text-base font-extrabold text-gray-900 tracking-tight">
                            Upload Bukti Transfer Pembayaran
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Pastikan foto struk atau screenshot mutasi terlihat jelas dan memuat nomor rekening pengirim serta nominal transfer.
                        </p>
                    </div>

                    @if($errors->any())
                        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm space-y-1">
                            <div class="font-bold flex items-center gap-2">
                                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Terjadi kesalahan saat memproses bukti pembayaran:</span>
                            </div>
                            <ul class="list-disc list-inside space-y-0.5 pl-6 text-rose-700">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Bank Pengirim <span class="text-rose-500">*</span></label>
                            <input
                                type="text"
                                name="sender_bank_name"
                                value="{{ old('sender_bank_name') }}"
                                placeholder="Contoh: BCA / Mandiri / BRI"
                                required
                                class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 outline-none transition-all"
                            />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nama Pemilik Rekening <span class="text-rose-500">*</span></label>
                            <input
                                type="text"
                                name="sender_account_name"
                                value="{{ old('sender_account_name', auth()->user()->name) }}"
                                placeholder="Nama di mutasi rekening"
                                required
                                class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 outline-none transition-all"
                            />
                        </div>
                    </div>

                    <!-- Interactive Dropzone & Visual Preview (E-Commerce Standard) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Foto Struk / Screenshot Bukti Transfer <span class="text-rose-500">*</span>
                        </label>

                        <!-- Hidden Native File Input -->
                        <input
                            type="file"
                            name="proof"
                            x-ref="fileInput"
                            id="proof_file_input"
                            accept="image/jpeg,image/png,image/jpg,image/webp"
                            class="sr-only"
                            @change="handleFileSelect($event)"
                        />

                        <!-- State 1: Dropzone (No File Chosen Yet) -->
                        <div
                            x-show="!previewUrl"
                            @click="triggerFileInput()"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="handleDrop($event)"
                            class="border-2 border-dashed rounded-2xl p-6 sm:p-8 text-center cursor-pointer transition-all duration-200 select-none group"
                            :class="isDragging ? 'border-[#4F26A6] bg-[#F3EEFF]/80 scale-[1.01] shadow-inner' : 'border-[#4F26A6]/30 bg-[#FAF9FC] hover:bg-[#F3EEFF]/40 hover:border-[#4F26A6]/60'"
                        >
                            <div class="w-14 h-14 rounded-2xl bg-[#EDE9FE] text-[#4F26A6] mx-auto flex items-center justify-center mb-3 shadow-xs group-hover:scale-105 transition-transform">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h4 class="text-sm font-bold text-gray-900 mb-1">
                                Tarik &amp; lepas foto bukti transfer ke sini
                            </h4>
                            <p class="text-xs text-gray-500 mb-3.5">
                                atau klik tombol di bawah untuk memilih file dari perangkat Anda
                            </p>
                            <button
                                type="button"
                                @click.stop="triggerFileInput()"
                                class="px-4 py-2 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs shadow-sm transition-all inline-flex items-center gap-2 cursor-pointer pointer-events-auto"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                                <span>Pilih File Bukti</span>
                            </button>
                            <p class="text-[11px] text-gray-400 mt-3">
                                Mendukung file format JPG, PNG, atau WEBP (Maksimal 10MB)
                            </p>
                        </div>

                        <!-- State 2: Visual Receipt Card (File Chosen - Standard E-Commerce Preview) -->
                        <div
                            x-show="previewUrl"
                            x-cloak
                            class="bg-white border-2 border-[#4F26A6]/25 rounded-2xl p-4 sm:p-5 shadow-[0_6px_24px_rgba(79,38,166,0.06)] space-y-3.5"
                        >
                            <!-- Top Badges -->
                            <div class="flex items-center justify-between flex-wrap gap-2 pb-2.5 border-b border-gray-100">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/80 text-xs font-bold">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Foto Bukti Transfer Terpilih</span>
                                </span>
                                <span class="text-[11px] text-gray-400 font-medium">Siap Dikirim</span>
                            </div>

                            <!-- Preview Thumbnail & File Info -->
                            <div class="flex items-center gap-4">
                                <!-- Thumbnail with Lightbox Trigger -->
                                <div
                                    @click="showLightbox = true"
                                    class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-xl overflow-hidden bg-gray-100 border border-gray-200 shrink-0 cursor-pointer group shadow-2xs"
                                    title="Klik untuk melihat ukuran penuh"
                                >
                                    <img
                                        :src="previewUrl"
                                        alt="Bukti Transfer Preview"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"
                                    />
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                        <svg class="w-5 h-5 drop-shadow-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/>
                                        </svg>
                                    </div>
                                </div>

                                <!-- File Details -->
                                <div class="flex-1 min-w-0">
                                    <h5 class="text-xs sm:text-sm font-bold text-gray-900 truncate" x-text="fileName"></h5>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="px-2 py-0.5 rounded bg-purple-50 text-[#4F26A6] font-mono font-bold text-[10px]" x-text="fileExt"></span>
                                        <span class="text-xs text-gray-500 font-medium" x-text="fileSize"></span>
                                    </div>
                                    <p class="text-[11px] text-gray-400 mt-1 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <span>Tervalidasi &amp; aman untuk verifikasi admin</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Action Buttons: Ganti & Hapus -->
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100 gap-2">
                                <button
                                    type="button"
                                    @click="showLightbox = true"
                                    class="text-xs font-bold text-[#4F26A6] hover:underline flex items-center gap-1 cursor-pointer"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span>Lihat Penuh</span>
                                </button>

                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        @click="triggerFileInput()"
                                        class="px-3 py-1.5 rounded-xl border border-gray-200 text-gray-700 hover:border-[#4F26A6] hover:text-[#4F26A6] hover:bg-[#F3EEFF] text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        <span>Ganti Foto</span>
                                    </button>
                                    <button
                                        type="button"
                                        @click="clearFile()"
                                        class="px-3 py-1.5 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Inline Client-side Error Message -->
                        <template x-if="errorMessage">
                            <div class="mt-2.5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-center gap-2">
                                <svg class="w-4 h-4 shrink-0 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span x-text="errorMessage"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Escrow Trust Guarantee -->
                    <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200/60 flex items-center gap-2.5 text-xs text-amber-950">
                        <span class="font-handwriting text-lg text-[#F59E0B] font-bold shrink-0">Proteksi Penuh:</span>
                        <span>Dana Anda tersimpan aman di rekening escrow WhiMarket sampai barang Anda terima dan konfirmasi.</span>
                    </div>

                    <!-- Submit Button with Anti-Bottleneck Loading State -->
                    <button
                        type="submit"
                        id="btn-submit-payment"
                        :disabled="isSubmitting"
                        class="w-full py-4 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-sm sm:text-base shadow-lg shadow-[#4F26A6]/25 transition-all active:scale-[0.98] cursor-pointer flex items-center justify-center gap-2.5 disabled:opacity-75 disabled:cursor-wait"
                    >
                        <template x-if="!isSubmitting">
                            <span class="flex items-center gap-2">
                                <span>Kirim Bukti Pembayaran Sekarang</span>
                                <span>&rarr;</span>
                            </span>
                        </template>
                        <template x-if="isSubmitting">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Sedang Mengunggah Bukti Pembayaran...</span>
                            </span>
                        </template>
                    </button>
                </form>
                @endif
            </div>
        </div>

        <!-- Lightbox Modal (Enlarge Receipt View) -->
        <div
            x-show="showLightbox"
            x-cloak
            @keydown.escape.window="showLightbox = false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-xs"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div
                @click.outside="showLightbox = false"
                class="relative max-w-3xl w-full bg-white rounded-3xl overflow-hidden shadow-2xl p-4 sm:p-6 space-y-4"
            >
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#4F26A6]"></span>
                        <h4 class="text-sm font-bold text-gray-900 truncate max-w-xs sm:max-w-md" x-text="fileName || 'Bukti Transfer'"></h4>
                    </div>
                    <button
                        type="button"
                        @click="showLightbox = false"
                        class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center cursor-pointer transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="max-h-[70vh] overflow-auto flex items-center justify-center bg-gray-50 rounded-2xl p-2">
                    <img
                        :src="previewUrl"
                        alt="Bukti Transfer Penuh"
                        class="max-h-[65vh] w-auto max-w-full object-contain rounded-xl shadow-sm"
                    />
                </div>

                <div class="flex items-center justify-between text-xs text-gray-500 pt-1">
                    <span x-text="'Ukuran: ' + fileSize"></span>
                    <button
                        type="button"
                        @click="showLightbox = false"
                        class="px-4 py-2 rounded-xl bg-gray-900 hover:bg-black text-white font-bold cursor-pointer transition-all"
                    >
                        Tutup Pratinjau
                    </button>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
    function registerPaymentUploadManager() {
        if (window.__whiPaymentUploadRegistered) return;
        window.__whiPaymentUploadRegistered = true;

        Alpine.data('paymentUploadManager', () => ({
            copiedRek: false,
            copiedAmount: false,
            isDragging: false,
            file: null,
            fileName: '',
            fileSize: '',
            fileExt: '',
            previewUrl: null,
            errorMessage: '',
            isSubmitting: false,
            showLightbox: false,

            copyText(text, type) {
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(text);
                } else {
                    const textArea = document.createElement('textarea');
                    textArea.value = text;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                }
                if (type === 'rek') {
                    this.copiedRek = true;
                    setTimeout(() => { this.copiedRek = false; }, 2500);
                } else {
                    this.copiedAmount = true;
                    setTimeout(() => { this.copiedAmount = false; }, 2500);
                }
            },

            triggerFileInput() {
                if (this.$refs.fileInput) {
                    this.$refs.fileInput.click();
                }
            },

            formatBytes(bytes, decimals = 1) {
                if (!+bytes) return '0 B';
                const k = 1024;
                const dm = decimals < 0 ? 0 : decimals;
                const sizes = ['B', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
            },

            processFile(selectedFile) {
                this.errorMessage = '';
                if (!selectedFile) return;

                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                if (!validTypes.includes(selectedFile.type.toLowerCase())) {
                    this.errorMessage = 'Format file tidak didukung. Harap pilih foto berformat JPG, PNG, atau WEBP.';
                    this.clearFile();
                    return;
                }

                const maxSizeBytes = 10 * 1024 * 1024;
                if (selectedFile.size > maxSizeBytes) {
                    this.errorMessage = 'Ukuran file melebihi batas maksimal 10MB.';
                    this.clearFile();
                    return;
                }

                if (this.previewUrl) {
                    URL.revokeObjectURL(this.previewUrl);
                }

                this.file = selectedFile;
                this.fileName = selectedFile.name;
                this.fileSize = this.formatBytes(selectedFile.size);
                this.fileExt = (selectedFile.name.split('.').pop() || 'IMG').toUpperCase();
                this.previewUrl = URL.createObjectURL(selectedFile);
            },

            handleFileSelect(event) {
                const file = event.target.files && event.target.files[0];
                if (file) {
                    this.processFile(file);
                }
            },

            handleDrop(event) {
                this.isDragging = false;
                if (event.dataTransfer && event.dataTransfer.files && event.dataTransfer.files.length > 0) {
                    const file = event.dataTransfer.files[0];
                    try {
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        this.$refs.fileInput.files = dataTransfer.files;
                    } catch (e) {
                        console.warn('DataTransfer not supported, proceeding with file object', e);
                    }
                    this.processFile(file);
                }
            },

            clearFile() {
                if (this.previewUrl) {
                    URL.revokeObjectURL(this.previewUrl);
                    this.previewUrl = null;
                }
                this.file = null;
                this.fileName = '';
                this.fileSize = '';
                this.fileExt = '';
                this.errorMessage = '';
                if (this.$refs.fileInput) {
                    this.$refs.fileInput.value = '';
                }
            },

            handleSubmit(event) {
                if (!this.file && (!this.$refs.fileInput || !this.$refs.fileInput.files.length)) {
                    event.preventDefault();
                    this.errorMessage = 'Harap pilih foto bukti transfer sebelum mengirim.';
                    return;
                }
                this.isSubmitting = true;
            }
        }));
    }

    if (window.Alpine) {
        registerPaymentUploadManager();
    } else {
        document.addEventListener('alpine:init', registerPaymentUploadManager);
    }
    </script>
    @endpush
</x-layouts.app>
