<template x-teleport="body">
    <div
        x-show="proofModalOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
        style="background-color: rgba(15, 23, 42, 0.7) !important; backdrop-filter: blur(6px) !important;"
    >
        <div
            @click.outside="proofModalOpen = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="rounded-3xl border border-gray-100 shadow-[0_25px_60px_rgba(0,0,0,0.35)] max-w-lg w-full overflow-hidden flex flex-col max-h-[92vh] relative z-10"
            style="background-color: #ffffff !important; opacity: 1 !important;"
        >
            <!-- Modal Header -->
            <div class="px-6 py-5 border-b border-emerald-100 flex items-center justify-between shrink-0" style="background-color: #F0FDF4 !important;">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-sm shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-gray-950">Bukti Transfer Pencairan Dana</h3>
                        <p class="text-xs text-emerald-800">Dana resmi ditransfer oleh Administrator WhiMarket</p>
                    </div>
                </div>
                <button
                    type="button"
                    @click="proofModalOpen = false"
                    class="w-8 h-8 rounded-full bg-white/80 hover:bg-white text-gray-400 hover:text-gray-700 flex items-center justify-center transition-colors shadow-2xs cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="p-6 space-y-5 overflow-y-auto" style="background-color: #ffffff !important;">
                <!-- Info Summary Box -->
                <div class="p-4 rounded-2xl border border-gray-100 space-y-3" style="background-color: #F9FAFB !important;">
                    <div class="flex items-center justify-between text-xs pb-2.5 border-b border-gray-200/60">
                        <span class="text-gray-500 font-medium">Nomor Pesanan</span>
                        <span class="font-mono font-bold text-gray-900" x-text="proofModalData.orderNumber || '-'"></span>
                    </div>
                    <div class="flex items-center justify-between text-xs pb-2.5 border-b border-gray-200/60">
                        <span class="text-gray-500 font-medium">Nominal Bersih (Payout)</span>
                        <span class="font-extrabold text-emerald-600 text-sm" x-text="proofModalData.amount || '-'"></span>
                    </div>
                    <div class="flex items-center justify-between text-xs pb-2.5 border-b border-gray-200/60">
                        <span class="text-gray-500 font-medium">Rekening Tujuan</span>
                        <span class="font-bold text-gray-900 text-right" x-text="proofModalData.bank || '-'"></span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-500 font-medium">Waktu Transfer</span>
                        <span class="font-bold text-gray-900 text-right" x-text="proofModalData.date || '-'"></span>
                    </div>
                </div>

                <!-- Receipt Image Container -->
                <div>
                    <span class="text-xs font-bold text-gray-700 block mb-2">Screenshot Bukti Transfer Bank:</span>
                    <div class="rounded-2xl border border-gray-200 overflow-hidden bg-gray-900/5 flex items-center justify-center max-h-[360px]">
                        <template x-if="proofModalData.imageUrl">
                            <img
                                :src="proofModalData.imageUrl"
                                alt="Bukti Transfer Bank"
                                class="w-full h-auto max-h-[360px] object-contain cursor-zoom-in"
                                @click="window.open(proofModalData.imageUrl, '_blank')"
                            />
                        </template>
                        <template x-if="!proofModalData.imageUrl">
                            <div class="py-12 text-center text-gray-400">
                                <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-xs">Gambar bukti transfer tidak tersedia</p>
                            </div>
                        </template>
                    </div>
                    <p class="text-[11px] text-gray-400 mt-1.5 text-center">
                        Klik gambar untuk melihat ukuran penuh di tab baru.
                    </p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3 shrink-0" style="background-color: #F9FAFB !important;">
                <template x-if="proofModalData.imageUrl">
                    <a
                        :href="proofModalData.imageUrl"
                        target="_blank"
                        class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-100 text-gray-700 font-bold text-xs transition-all flex items-center gap-1.5"
                    >
                        <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span>Buka Gambar Asli</span>
                    </a>
                </template>
                <button
                    type="button"
                    @click="proofModalOpen = false"
                    class="px-6 py-2.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs transition-all shadow-xs cursor-pointer"
                >
                    <span>Tutup</span>
                </button>
            </div>
        </div>
    </div>
</template>
