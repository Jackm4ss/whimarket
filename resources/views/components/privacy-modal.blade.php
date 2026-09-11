<template x-teleport="body">
    <div
        x-show="openPrivacy"
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
            @click.outside="openPrivacy = false"
            class="bg-white rounded-3xl border border-gray-100 shadow-[0_25px_60px_rgba(79,38,166,0.25)] max-w-2xl w-full p-5 sm:p-7 relative my-auto flex flex-col max-h-[90vh] text-left"
        >
            <!-- Modal Header -->
            <div class="flex items-start justify-between pb-4 border-b border-gray-100 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#F3EEFF] text-[#4F26A6] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg sm:text-xl font-black text-gray-900 tracking-tight">
                            Kebijakan Privasi
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Komitmen perlindungan data pribadi dan privasi pengguna WhiMarket
                        </p>
                    </div>
                </div>
                <button
                    type="button"
                    @click="openPrivacy = false"
                    class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-800 flex items-center justify-center transition-colors cursor-pointer text-sm font-bold"
                    title="Tutup Modal"
                >
                    ✕
                </button>
            </div>

            <!-- Scrollable Modal Content -->
            <div class="flex-1 overflow-y-auto pr-2 my-4 space-y-5 text-xs sm:text-[13px] text-gray-600 leading-relaxed overscroll-contain">
                <div class="p-3.5 rounded-2xl bg-[#FAF9FC] border border-purple-100/70 text-gray-700">
                    <p class="font-bold text-[#4F26A6] mb-1">Jaminan Keamanan Data Anda:</p>
                    <p>WhiMarket menghargai dan melindungi setiap privasi dan data pribadi pengunjung serta pengguna platform kami. Dokumen ini menjelaskan bagaimana kami mengumpulkan, mengelola, melindungi, dan memperlakukan informasi pribadi Anda sesuai regulasi perlindungan data pribadi di Indonesia.</p>
                </div>

                <section class="space-y-1.5">
                    <h4 class="text-xs sm:text-sm font-extrabold text-gray-900 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md bg-purple-100 text-[#4F26A6] text-[11px] font-black inline-flex items-center justify-center">1</span>
                        Data yang Kami Kumpulkan
                    </h4>
                    <p>Saat Anda membuat akun atau bertransaksi di WhiMarket, kami dapat mengumpulkan informasi seperti:</p>
                    <ul class="list-disc list-inside space-y-1 pl-2 text-gray-600">
                        <li><strong>Identitas Diri:</strong> Nama lengkap, alamat email, jenis kelamin, serta foto profil/avatar.</li>
                        <li><strong>Data Kontak &amp; Alamat:</strong> Nomor telepon/WhatsApp aktif, alamat pengiriman lengkap, kecamatan, kota, dan kode pos.</li>
                        <li><strong>Data Keuangan Seller:</strong> Nama bank, nomor rekening, dan nama pemilik rekening untuk penyaluran dana (payout).</li>
                        <li><strong>Data Transaksi:</strong> Riwayat pesanan, nomor resi pengiriman, ulasan produk, dan bukti pembayaran manual.</li>
                    </ul>
                </section>

                <section class="space-y-1.5">
                    <h4 class="text-xs sm:text-sm font-extrabold text-gray-900 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md bg-purple-100 text-[#4F26A6] text-[11px] font-black inline-flex items-center justify-center">2</span>
                        Tujuan Penggunaan Data
                    </h4>
                    <p>Informasi yang terkumpul digunakan untuk memproses transaksi pesanan, memfasilitasi pengiriman barang melalui kurir rekanan, verifikasi pembayaran escrow, pemberitahuan pembaruan status transaksi, personalisasi rekomendasi fashion, dan penanganan sengketa (dispute mediation).</p>
                </section>

                <section class="space-y-1.5">
                    <h4 class="text-xs sm:text-sm font-extrabold text-gray-900 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md bg-purple-100 text-[#4F26A6] text-[11px] font-black inline-flex items-center justify-center">3</span>
                        Kerahasiaan &amp; Pembagian ke Pihak Ketiga
                    </h4>
                    <p>WhiMarket tidak akan pernah menjual atau menyewakan data pribadi Anda ke pihak ketiga manapun untuk tujuan periklanan tanpa persetujuan Anda. Data hanya dibagikan secara terbatas kepada:</p>
                    <ul class="list-disc list-inside space-y-1 pl-2 text-gray-600">
                        <li><strong>Mitra Ekspedisi/Kurir:</strong> Nama penerima, alamat tujuan, dan nomor telepon untuk pengantaran pesanan.</li>
                        <li><strong>Layanan Perbankan:</strong> Untuk verifikasi mutasi dan pencairan saldo penjualan seller.</li>
                        <li><strong>Otoritas Berwenang:</strong> Apabila diwajibkan oleh proses hukum yang sah di Republik Indonesia.</li>
                    </ul>
                </section>

                <section class="space-y-1.5">
                    <h4 class="text-xs sm:text-sm font-extrabold text-gray-900 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md bg-purple-100 text-[#4F26A6] text-[11px] font-black inline-flex items-center justify-center">4</span>
                        Keamanan &amp; Penyimpanan Data
                    </h4>
                    <p>Kata sandi Anda disimpan menggunakan algoritma hashing satu arah (Bcrypt) yang aman. Seluruh jalur komunikasi data diamankan dengan enkripsi HTTPS/TLS. Dokumen bukti pembayaran manual diverifikasi melalui inspeksi binary header sebelum disimpan di sistem storage terisolasi.</p>
                </section>

                <section class="space-y-1.5">
                    <h4 class="text-xs sm:text-sm font-extrabold text-gray-900 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md bg-purple-100 text-[#4F26A6] text-[11px] font-black inline-flex items-center justify-center">5</span>
                        Hak Pengguna &amp; Penghapusan Data
                    </h4>
                    <p>Anda berhak memperbarui informasi profil dan alamat pengiriman kapan saja melalui menu Pengaturan Akun. Jika Anda ingin meminta penghapusan akun beserta data riwayat, Anda dapat menghubungi tim dukungan resmi WhiMarket.</p>
                </section>
            </div>

            <!-- Modal Footer -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-2.5 shrink-0">
                <button
                    type="button"
                    @click="openPrivacy = false"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs sm:text-sm font-bold transition-all cursor-pointer"
                >
                    Tutup
                </button>
                <button
                    type="button"
                    @click="const cb = document.getElementById('terms'); if (cb) cb.checked = true; openPrivacy = false;"
                    class="px-5 py-2.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white text-xs sm:text-sm font-bold shadow-md shadow-[#4F26A6]/20 transition-all cursor-pointer flex items-center gap-1.5"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span>Setujui &amp; Lanjutkan</span>
                </button>
            </div>
        </div>
    </div>
</template>
