<x-layouts.app :title="$title" activeTab="seller-orders">
    <main class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10">
        <!-- Top Bar -->
        <div class="flex items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
            <div>
                <a href="{{ route('seller.orders.index') }}" class="text-xs sm:text-sm font-bold text-[#4F26A6] hover:underline mb-1 inline-block">
                    &larr; Kembali ke Daftar Pesanan
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-950 tracking-tight">
                    Komplain Pesanan #{{ $dispute->order->order_number }}
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                    Tinjau keluhan pembeli dan berikan tanggapan serta bukti kondisi saat packing.
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left: Dispute Details & Buyer Evidence (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Buyer Claim Card -->
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8 space-y-5">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">
                            {{ $dispute->status->label() }}
                        </span>
                        <span class="text-xs text-gray-400 font-medium">{{ $dispute->created_at->format('d M Y, H:i') }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Alasan Komplain</span>
                        <h3 class="text-lg font-bold text-gray-900">{{ $dispute->reason }}</h3>
                    </div>

                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Deskripsi Masalah dari Pembeli</span>
                        <p class="text-sm text-gray-700 leading-relaxed bg-[#FAF9FC] p-4 rounded-2xl border border-gray-100">
                            {{ $dispute->description }}
                        </p>
                    </div>

                    <!-- Buyer Photo Evidences -->
                    @if(!empty($dispute->buyer_evidence_paths))
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-2">Foto Bukti dari Pembeli</span>
                            <div class="grid grid-cols-3 gap-3">
                                @foreach($dispute->buyer_evidence_paths as $p)
                                    <a href="/storage/{{ $p }}" target="_blank" class="aspect-square rounded-2xl overflow-hidden bg-gray-100 border border-gray-200 block">
                                        <img src="/storage/{{ $p }}" alt="Bukti Pembeli" class="w-full h-full object-cover hover:scale-105 transition-transform" />
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right: Seller Response Form (5 cols) -->
            <div class="lg:col-span-5 space-y-6 sticky top-24">
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8 space-y-5">
                    <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Tanggapan Toko</h3>

                    @if($dispute->seller_response)
                        <div class="p-4 rounded-2xl bg-purple-50/60 border border-purple-200/50 space-y-2">
                            <span class="text-xs font-bold text-[#4F26A6] block uppercase tracking-wider">Tanggapan Terkirim:</span>
                            <p class="text-xs text-gray-800 leading-relaxed">{{ $dispute->seller_response }}</p>
                        </div>
                    @else
                        <form action="{{ route('seller.disputes.respond', $dispute->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Klarifikasi / Penjelasan Seller <span class="text-rose-500">*</span></label>
                                <textarea
                                    name="seller_response"
                                    rows="4"
                                    required
                                    placeholder="Jelaskan kondisi barang saat dipacking dan tanggapan tokomu..."
                                    class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] outline-none resize-none"
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Foto Bukti Tandingan Packing</label>
                                <input
                                    type="file"
                                    name="seller_evidence[]"
                                    multiple
                                    accept="image/*"
                                    class="text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#4F26A6] file:text-white cursor-pointer"
                                />
                            </div>

                            <button
                                type="submit"
                                class="w-full py-3 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs shadow-md shadow-[#4F26A6]/20 transition-all cursor-pointer"
                            >
                                Kirim Tanggapan ke Admin
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>
