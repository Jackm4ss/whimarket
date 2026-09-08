<x-layouts.app :title="$title" activeTab="seller-orders">
    <main
        class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10"
        x-data="{
            fulfillModalOpen: false,
            activeOrderId: null,
            activeOrderNumber: '',
            openFulfillModal(orderId, orderNumber) {
                this.activeOrderId = orderId;
                this.activeOrderNumber = orderNumber;
                this.fulfillModalOpen = true;
            }
        }"
    >
        <!-- Top Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
            <div>
                <a href="{{ route('seller.dashboard') }}" class="text-xs sm:text-sm font-bold text-[#4F26A6] hover:underline mb-1 inline-block">
                    &larr; Dashboard
                </a>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-950 tracking-tight">
                    Kelola Pesanan Masuk
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Pantau pesanan, siapkan packing, dan upload nomor resi pengiriman.
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

        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 mb-6 border-b border-gray-200 overflow-x-auto no-scrollbar">
            <a href="{{ route('seller.orders.index', ['status' => 'all']) }}" class="pb-3 text-sm font-bold {{ $currentTab === 'all' ? 'text-[#4F26A6] border-b-2 border-[#4F26A6]' : 'text-gray-500 hover:text-gray-900' }} px-3 whitespace-nowrap">
                Semua Pesanan
            </a>
            <a href="{{ route('seller.orders.index', ['status' => 'processing']) }}" class="pb-3 text-sm font-bold {{ $currentTab === 'processing' ? 'text-[#4F26A6] border-b-2 border-[#4F26A6]' : 'text-gray-500 hover:text-gray-900' }} px-3 whitespace-nowrap">
                Perlu Dikirim
            </a>
            <a href="{{ route('seller.orders.index', ['status' => 'shipped']) }}" class="pb-3 text-sm font-bold {{ $currentTab === 'shipped' ? 'text-[#4F26A6] border-b-2 border-[#4F26A6]' : 'text-gray-500 hover:text-gray-900' }} px-3 whitespace-nowrap">
                Sedang Dikirim
            </a>
            <a href="{{ route('seller.orders.index', ['status' => 'delivered']) }}" class="pb-3 text-sm font-bold {{ $currentTab === 'delivered' ? 'text-[#4F26A6] border-b-2 border-[#4F26A6]' : 'text-gray-500 hover:text-gray-900' }} px-3 whitespace-nowrap">
                Tiba di Pembeli
            </a>
            <a href="{{ route('seller.orders.index', ['status' => 'disputed']) }}" class="pb-3 text-sm font-bold {{ $currentTab === 'disputed' ? 'text-[#4F26A6] border-b-2 border-[#4F26A6]' : 'text-gray-500 hover:text-gray-900' }} px-3 whitespace-nowrap">
                Komplain / Sengketa
            </a>
        </div>

        @if($orders->isEmpty())
            <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-12 text-center text-gray-400">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-sm font-bold text-gray-700">Tidak ada pesanan pada status ini.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_20px_rgba(0,0,0,0.03)] overflow-hidden">
                        <!-- Order Top Header -->
                        <div class="px-5 sm:px-6 py-4 bg-[#FAF9FC] border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex items-center gap-3">
                                <span class="font-mono font-bold text-gray-950 text-sm sm:text-base">#{{ $order->order_number }}</span>
                                <span class="text-xs text-gray-400 font-medium">{{ $order->created_at->format('d M Y, H:i') }}</span>
                            </div>

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                {{ $order->status::$name === 'paid' || $order->status::$name === 'processing' ? 'bg-purple-50 text-[#4F26A6] border border-purple-200/60' : '' }}
                                {{ $order->status::$name === 'shipped' ? 'bg-amber-50 text-amber-800 border border-amber-200/60' : '' }}
                                {{ $order->status::$name === 'delivered' || $order->status::$name === 'completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : '' }}
                                {{ $order->status::$name === 'disputed' ? 'bg-rose-50 text-rose-700 border border-rose-200/60' : '' }}
                            ">
                                {{ $order->status->label() }}
                            </span>
                        </div>

                        <!-- Order Content -->
                        <div class="p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                            <!-- Left: Items (7 cols) -->
                            <div class="lg:col-span-7 space-y-3">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-2">Barang Pesanan</span>
                                @foreach($order->items as $item)
                                    <div class="flex items-center justify-between gap-3 text-sm">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-gray-900 truncate">{{ $item->product_name_snapshot }}</p>
                                            <p class="text-xs text-gray-400">Varian: {{ $item->variant_name_snapshot }} • {{ $item->quantity }} pcs</p>
                                        </div>
                                        <span class="font-bold text-[#4F26A6] text-sm shrink-0">
                                            Rp {{ number_format((float)$item->subtotal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach

                                <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs font-medium text-gray-500">
                                    <span>Total Penjualan</span>
                                    <span class="text-base font-extrabold text-[#4F26A6]">
                                        Rp {{ number_format((float)$order->total_amount, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Right: Destination Address & Action Buttons (5 cols) -->
                            <div class="lg:col-span-5 bg-[#FAF9FC] rounded-2xl p-4 sm:p-5 border border-gray-100 flex flex-col justify-between">
                                <div class="space-y-1.5 text-xs text-gray-600 mb-4">
                                    <span class="font-bold text-gray-900 block mb-1">Alamat Tujuan Pengiriman:</span>
                                    <p class="font-semibold text-gray-800">{{ $order->address_snapshot['recipient_name'] ?? '-' }} ({{ $order->address_snapshot['phone'] ?? '-' }})</p>
                                    <p class="leading-relaxed">{{ $order->address_snapshot['full_address'] ?? '-' }}, {{ $order->address_snapshot['city'] ?? '' }} {{ $order->address_snapshot['postal_code'] ?? '' }}</p>
                                </div>

                                <!-- Dynamic Action Buttons based on order state -->
                                <div class="space-y-2 pt-2 border-t border-gray-200/70">
                                    @if(in_array($order->status::$name, ['paid', 'processing']))
                                        <button
                                            type="button"
                                            @click="openFulfillModal({{ $order->id }}, '{{ $order->order_number }}')"
                                            class="w-full py-2.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-1.5 cursor-pointer"
                                        >
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            <span>Kirim Barang &amp; Input Resi</span>
                                        </button>
                                    @elseif($order->status::$name === 'shipped')
                                        <div class="p-2.5 rounded-xl bg-purple-50/70 border border-purple-200/50 text-xs text-purple-900 mb-2">
                                            <span class="font-bold">{{ $order->shipment->courier_name ?? 'Kurir' }}</span>:
                                            <span class="font-mono font-bold">{{ $order->shipment->tracking_number ?? '-' }}</span>
                                        </div>
                                        <form action="{{ route('seller.orders.claim_delivered', $order->id) }}" method="POST">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="w-full py-2.5 rounded-xl border-2 border-[#4F26A6] text-[#4F26A6] hover:bg-[#4F26A6] hover:text-white text-xs font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer"
                                            >
                                                <span>Ajukan Klaim: Paket Sampai</span>
                                            </button>
                                        </form>
                                    @elseif($order->status::$name === 'disputed' && $order->dispute)
                                        <a
                                            href="{{ route('seller.disputes.show', $order->dispute->id) }}"
                                            class="w-full py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition-all flex items-center justify-center gap-1.5"
                                        >
                                            <span>Lihat Komplain &amp; Berikan Bukti</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="p-4">
                    {{ $orders->links() }}
                </div>
            </div>
        @endif

        <!-- Fulfill Modal Popup -->
        <div
            x-show="fulfillModalOpen"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs overflow-y-auto"
        >
            <div
                @click.outside="fulfillModalOpen = false"
                class="bg-white rounded-3xl border border-gray-100 shadow-2xl max-w-lg w-full p-6 sm:p-8 relative my-8"
            >
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                    <div>
                        <h3 class="text-xl font-extrabold text-gray-900">Kirim Pesanan</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Pesanan <span class="font-mono font-bold text-[#4F26A6]" x-text="'#' + activeOrderNumber"></span></p>
                    </div>
                    <button type="button" @click="fulfillModalOpen = false" class="text-gray-400 hover:text-gray-700 cursor-pointer">✕</button>
                </div>

                <form :action="'/seller/orders/' + activeOrderId + '/fulfill'" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Ekspedisi / Kurir <span class="text-rose-500">*</span></label>
                        <select name="courier_name" required class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] outline-none">
                            <option value="J&T Express">J&amp;T Express</option>
                            <option value="JNE">JNE</option>
                            <option value="SiCepat">SiCepat</option>
                            <option value="Anteraja">Anteraja</option>
                            <option value="GoSend / GrabExpress">GoSend / GrabExpress Instant</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nomor Resi Pengiriman <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            name="tracking_number"
                            placeholder="Contoh: JT82910283921"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm font-mono font-bold text-gray-900 focus:border-[#4F26A6] outline-none"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Foto Kondisi Barang Sebelum Dipacking (Wajib) <span class="text-rose-500">*</span></label>
                        <input
                            type="file"
                            name="pre_shipment_photo"
                            accept="image/*"
                            required
                            class="text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#4F26A6] file:text-white cursor-pointer"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Foto Struk / Resi Pengiriman Fisik</label>
                        <input
                            type="file"
                            name="receipt_photo"
                            accept="image/*"
                            class="text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-200 file:text-gray-800 cursor-pointer"
                        />
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                        <button type="button" @click="fulfillModalOpen = false" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-gray-500 hover:text-gray-800 cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white text-xs font-bold shadow-md shadow-[#4F26A6]/20 transition-all cursor-pointer">
                            Simpan &amp; Update Status Dikirim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</x-layouts.app>
