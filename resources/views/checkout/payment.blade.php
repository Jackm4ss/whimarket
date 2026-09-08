<x-layouts.app :title="$title" activeTab="checkout">
    <main
        class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-12"
        x-data="{
            copiedRek: false,
            copiedAmount: false,
            copyText(text, type) {
                navigator.clipboard.writeText(text);
                if (type === 'rek') {
                    this.copiedRek = true;
                    setTimeout(() => this.copiedRek = false, 2500);
                } else {
                    this.copiedAmount = true;
                    setTimeout(() => this.copiedAmount = false, 2500);
                }
            }
        }"
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

                <!-- Proof Upload Form -->
                <form
                    action="{{ route('payment.upload', $order->order_number) }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="space-y-4 pt-2"
                >
                    @csrf

                    <h3 class="text-base font-extrabold text-gray-900 tracking-tight">
                        Upload Bukti Transfer Pembayaran
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Bank Pengirim <span class="text-rose-500">*</span></label>
                            <input
                                type="text"
                                name="sender_bank_name"
                                placeholder="Contoh: BCA / Mandiri / BRI"
                                required
                                class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:border-[#4F26A6] outline-none"
                            />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nama Pemilik Rekening <span class="text-rose-500">*</span></label>
                            <input
                                type="text"
                                name="sender_account_name"
                                value="{{ auth()->user()->name }}"
                                placeholder="Nama di mutasi rekening"
                                required
                                class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:border-[#4F26A6] outline-none"
                            />
                        </div>
                    </div>

                    <!-- Dropzone upload file -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Foto Struk / Screenshot Bukti Transfer <span class="text-rose-500">*</span></label>
                        <div class="border-2 border-dashed border-[#4F26A6]/30 bg-[#F3EEFF]/20 rounded-2xl p-5 text-center hover:bg-[#F3EEFF]/30 transition-colors">
                            <input
                                type="file"
                                name="proof"
                                accept="image/*"
                                required
                                class="text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#4F26A6] file:text-white cursor-pointer"
                            />
                            <p class="text-[11px] text-gray-400 mt-1.5">Maksimal ukuran file: 10MB (JPG, PNG, WEBP)</p>
                        </div>
                    </div>

                    <!-- Escrow Trust Guarantee -->
                    <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200/60 flex items-center gap-2.5 text-xs text-amber-950">
                        <span class="font-handwriting text-lg text-[#F59E0B] font-bold shrink-0">Proteksi Penuh:</span>
                        <span>Dana kamu akan masuk ke rekening bersama escrow dan aman sampai kamu menerima serta memverifikasi barang.</span>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full py-4 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-sm sm:text-base shadow-lg shadow-[#4F26A6]/25 transition-all active:scale-[0.98] cursor-pointer"
                    >
                        Kirim Bukti Pembayaran Sekarang &rarr;
                    </button>
                </form>
            </div>
        </div>
    </main>
</x-layouts.app>
