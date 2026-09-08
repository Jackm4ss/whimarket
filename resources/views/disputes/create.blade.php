<x-layouts.app :title="$title" activeTab="pesanan">
    <main class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-12">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <a href="{{ route('orders.show', $order->order_number) }}" class="text-xs sm:text-sm font-bold text-[#4F26A6] hover:underline mb-2 inline-block">
                    &larr; Kembali ke Detail Pesanan
                </a>
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-rose-50 text-rose-700 text-xs font-bold mb-3 border border-rose-200/60 block mx-auto w-fit">
                    <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>Pusat Resolusi Masalah &amp; Mediasi</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-950 tracking-tight">
                    Ajukan Komplain Pesanan
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Pesanan <span class="font-mono font-bold text-[#4F26A6]">#{{ $order->order_number }}</span> • Penjual: <strong>{{ $order->seller->store_name }}</strong>
                </p>
            </div>

            <!-- Dispute Filing Card -->
            <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_12px_40px_rgba(79,38,166,0.06)] p-6 sm:p-10 space-y-6">
                <!-- Escrow Freeze Notice -->
                <div class="p-4 rounded-2xl bg-amber-50/80 border border-amber-200/70 flex items-start gap-3 text-xs text-amber-950">
                    <svg class="w-5 h-5 text-[#F59E0B] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="space-y-0.5">
                        <span class="font-bold block">Dana Ditahan di Escrow:</span>
                        <p class="leading-relaxed">Pengajuan komplain akan membekukan batas waktu 48 jam dan menahan dana di rekening bersama WhiMarket sampai penjual menanggapi dan Admin membuat keputusan.</p>
                    </div>
                </div>

                <form action="{{ route('dispute.store', $order->order_number) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <!-- Reason Dropdown -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Alasan Komplain <span class="text-rose-500">*</span></label>
                        <select name="reason" required class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none">
                            <option value="Barang Rusak / Cacat Fisik">Barang Rusak / Cacat Fisik saat Diterima</option>
                            <option value="Tidak Sesuai Deskripsi / Foto">Barang Tidak Sesuai Deskripsi atau Foto</option>
                            <option value="Barang Kurang / Tertinggal">Barang Kurang / Aksesoris Tertinggal</option>
                            <option value="Diduga Palsu / Tidak Original">Diduga Palsu / Tidak Original</option>
                            <option value="Barang Berbeda yang Dikirim">Barang Berbeda yang Dikirim oleh Penjual</option>
                        </select>
                    </div>

                    <!-- Problem Description -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Jelaskan Kendala Secara Rinci <span class="text-rose-500">*</span></label>
                        <textarea
                            name="description"
                            rows="4"
                            required
                            placeholder="Ceritakan kondisi barang saat unboxing, bagian mana yang rusak/berbeda, dan apa yang kamu harapkan..."
                            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none resize-none"
                        ></textarea>
                    </div>

                    <!-- Photo Evidences -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Foto Bukti Kerusakan / Ketidaksesuaian</label>
                        <div class="border-2 border-dashed border-gray-200 rounded-2xl p-5 text-center hover:bg-gray-50 transition-colors">
                            <input
                                type="file"
                                name="evidence[]"
                                multiple
                                accept="image/*"
                                class="text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#4F26A6] file:text-white cursor-pointer"
                            />
                            <p class="text-[11px] text-gray-400 mt-1.5">Unggah foto jelas bagian cacat atau perbandingan dengan deskripsi</p>
                        </div>
                    </div>

                    <!-- Video Unboxing Upload -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">File Video Unboxing (Sangat Dianjurkan)</label>
                        <div class="border-2 border-dashed border-gray-200 rounded-2xl p-5 text-center hover:bg-gray-50 transition-colors">
                            <input
                                type="file"
                                name="video_unboxing"
                                accept="video/mp4,video/quicktime,video/webm"
                                class="text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-800 file:text-white cursor-pointer"
                            />
                            <p class="text-[11px] text-gray-400 mt-1.5">Video rekaman membuka paket dari awal tanpa jeda (maks. 50MB)</p>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full py-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm sm:text-base shadow-lg shadow-rose-600/20 transition-all active:scale-[0.98] cursor-pointer"
                    >
                        Kirim Pengajuan Komplain &rarr;
                    </button>
                </form>
            </div>
        </div>
    </main>
</x-layouts.app>
