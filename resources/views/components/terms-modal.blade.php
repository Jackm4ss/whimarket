<template x-teleport="body">
    <div
        x-show="openTerms"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-4 bg-black/60 backdrop-blur-xs overflow-y-auto"
    >
        <div
            @click.outside="openTerms = false"
            class="bg-white rounded-3xl border border-gray-100 shadow-[0_25px_60px_rgba(79,38,166,0.25)] max-w-2xl w-full p-5 sm:p-7 relative my-auto flex flex-col max-h-[90vh] text-left"
        >
            <!-- Modal Header -->
            <div class="flex items-start justify-between pb-4 border-b border-gray-100 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#F3EEFF] text-[#4F26A6] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg sm:text-xl font-black text-gray-900 tracking-tight">
                            Syarat &amp; Ketentuan Penggunaan
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            WhiMarket Indonesia • Berlaku sejak September 2026
                        </p>
                    </div>
                </div>
                <button
                    type="button"
                    @click="openTerms = false"
                    class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-800 flex items-center justify-center transition-colors cursor-pointer text-sm font-bold"
                    title="Tutup Modal"
                >
                    ✕
                </button>
            </div>

            <!-- Scrollable Modal Content -->
            <div class="flex-1 overflow-y-auto pr-2 my-4 space-y-5 text-xs sm:text-[13px] text-gray-600 leading-relaxed overscroll-contain">
                <div class="p-3.5 rounded-2xl bg-[#FAF9FC] border border-purple-100/70 text-gray-700">
                    <p class="font-bold text-[#4F26A6] mb-1">Penting untuk Dibaca:</p>
                    <p>Selamat datang di WhiMarket! Dengan mendaftar dan menggunakan platform kami, Anda menyetujui seluruh ketentuan operasional transaksi, jaminan rekening bersama (escrow), perlindungan hak cipta, dan kepatuhan hukum Indonesia di bawah ini.</p>
                </div>

                <section class="space-y-1.5">
                    <h4 class="text-xs sm:text-sm font-extrabold text-gray-900 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md bg-purple-100 text-[#4F26A6] text-[11px] font-black inline-flex items-center justify-center">1</span>
                        Ketentuan Akun &amp; Identitas Pengguna
                    </h4>
                    <p>Setiap pengguna wajib memberikan informasi pendaftaran yang benar, lengkap, dan terkini (termasuk nama lengkap, alamat email aktif, dan nomor WhatsApp valid). Akun bersifat personal dan tidak boleh dialihkan kepada pihak lain tanpa persetujuan tertulis dari WhiMarket.</p>
                </section>

                <section class="space-y-1.5">
                    <h4 class="text-xs sm:text-sm font-extrabold text-gray-900 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md bg-purple-100 text-[#4F26A6] text-[11px] font-black inline-flex items-center justify-center">2</span>
                        Keaslian Barang Pre-Loved &amp; Merchandise
                    </h4>
                    <p>WhiMarket adalah wadah kurasi khusus pre-loved dan merchandise asli dari kreator dan figur publik terverifikasi. Seluruh penjual dilarang keras memperjualbelikan barang tiruan (palsu/KW), barang hasil curian, atau barang terlarang menurut hukum Republik Indonesia. Pelanggaran akan mengakibatkan penutupan toko permanen dan pelaporan hukum.</p>
                </section>

                <section class="space-y-1.5">
                    <h4 class="text-xs sm:text-sm font-extrabold text-gray-900 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md bg-purple-100 text-[#4F26A6] text-[11px] font-black inline-flex items-center justify-center">3</span>
                        Sistem Rekening Bersama (Escrow Protection)
                    </h4>
                    <p>Demi keamanan transaksi, pembayaran pembeli ditampung secara aman dalam rekening penampungan bersama (escrow) WhiMarket. Dana penjual baru akan diteruskan setelah barang diterima pembeli dalam kondisi sesuai atau setelah melewati batas waktu inspeksi resmi.</p>
                </section>

                <section class="space-y-1.5">
                    <h4 class="text-xs sm:text-sm font-extrabold text-gray-900 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md bg-purple-100 text-[#4F26A6] text-[11px] font-black inline-flex items-center justify-center">4</span>
                        Pengiriman &amp; Pemenuhan Pesanan
                    </h4>
                    <p>Penjual wajib mengemas pesanan dengan aman dan menginput nomor resi pelacakan kurir resmi yang sah, disertai foto paket sebelum pengiriman. Pembeli bertanggung jawab memastikan alamat pengiriman lengkap dan nomor telepon aktif untuk konfirmasi kurir.</p>
                </section>

                <section class="space-y-1.5">
                    <h4 class="text-xs sm:text-sm font-extrabold text-gray-900 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md bg-purple-100 text-[#4F26A6] text-[11px] font-black inline-flex items-center justify-center">5</span>
                        Masa Inspeksi 48 Jam &amp; Mediasi Dispute
                    </h4>
                    <p>Pembeli memiliki masa inspeksi selama 48 jam terhitung sejak kurir mencatat status paket diterima. Pengajuan komplain wajib menyertakan video pembukaan kemasan (unboxing) yang utuh tanpa jeda/editing. Tim resolusi WhiMarket bertindak sebagai mediator netral untuk memutuskan pengembalian dana atau rilis pembayaran.</p>
                </section>

                <section class="space-y-1.5">
                    <h4 class="text-xs sm:text-sm font-extrabold text-gray-900 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md bg-purple-100 text-[#4F26A6] text-[11px] font-black inline-flex items-center justify-center">6</span>
                        Perubahan Ketentuan
                    </h4>
                    <p>WhiMarket berhak memperbarui Syarat &amp; Ketentuan ini sewaktu-waktu. Pengguna disarankan untuk meninjau halaman ini secara berkala. Penggunaan berkelanjutan atas layanan setelah perubahan dipublikasikan dianggap sebagai persetujuan mengikat.</p>
                </section>
            </div>

            <!-- Modal Footer -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-2.5 shrink-0">
                <button
                    type="button"
                    @click="openTerms = false"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs sm:text-sm font-bold transition-all cursor-pointer"
                >
                    Tutup
                </button>
                <button
                    type="button"
                    @click="const cb = document.getElementById('terms'); if (cb) cb.checked = true; openTerms = false;"
                    class="px-5 py-2.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white text-xs sm:text-sm font-bold shadow-md shadow-[#4F26A6]/20 transition-all cursor-pointer flex items-center gap-1.5"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span>Setujui &amp; Lanjutkan</span>
                </button>
            </div>
        </div>
    </div>
</template>
