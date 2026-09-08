<x-layouts.app :title="$title" activeTab="pesanan">
    <main
        class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10"
        x-data="{
            deadline: {{ $order->inspection_deadline_at ? $order->inspection_deadline_at->timestamp * 1000 : 'null' }},
            hoursLeft: '',
            init() {
                if (this.deadline) {
                    this.updateCountdown();
                    setInterval(() => this.updateCountdown(), 1000);
                }
            },
            updateCountdown() {
                const now = new Date().getTime();
                const diff = this.deadline - now;
                if (diff <= 0) {
                    this.hoursLeft = 'Waktu pemeriksaan berakhir';
                    return;
                }
                const h = Math.floor(diff / (1000 * 60 * 60));
                const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((diff % (1000 * 60)) / 1000);
                this.hoursLeft = `${h} jam ${m} menit ${s} detik`;
            }
        }"
    >
        <!-- Top Bar -->
        <div class="flex items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
            <div>
                <a href="{{ route('orders.index') }}" class="text-xs sm:text-sm font-bold text-[#4F26A6] hover:underline mb-1 inline-block">
                    &larr; Kembali ke Pesanan Saya
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-950 tracking-tight">
                    Pesanan #{{ $order->order_number }}
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                    Dipesan pada {{ $order->created_at->format('d F Y, H:i') }} • Penjual: <strong>{{ $order->seller->store_name }}</strong>
                </p>
            </div>

            <div>
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold
                    {{ $order->status::$name === 'pending_payment' ? 'bg-amber-50 text-amber-800 border border-amber-200/60' : '' }}
                    {{ $order->status::$name === 'payment_verification' || $order->status::$name === 'processing' ? 'bg-purple-50 text-[#4F26A6] border border-purple-200/60' : '' }}
                    {{ $order->status::$name === 'paid' ? 'bg-purple-50 text-[#4F26A6] border border-purple-200/60' : '' }}
                    {{ $order->status::$name === 'shipped' ? 'bg-amber-50 text-amber-800 border border-amber-200/60' : '' }}
                    {{ $order->status::$name === 'delivered' || $order->status::$name === 'completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : '' }}
                    {{ $order->status::$name === 'disputed' ? 'bg-rose-50 text-rose-700 border border-rose-200/60' : '' }}
                    {{ $order->status::$name === 'cancelled' ? 'bg-gray-100 text-gray-700 border border-gray-200' : '' }}
                ">
                    {{ $order->status->label() }}
                </span>
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

        <!-- 48-Hour Inspection Countdown Banner (Active when Delivered) -->
        @if($order->status::$name === 'delivered')
            <div class="mb-8 p-6 sm:p-7 rounded-3xl bg-amber-50/90 border border-amber-200/80 shadow-[0_8px_30px_rgba(245,158,11,0.08)] flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-1">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-100 text-amber-900 text-xs font-bold mb-1">
                        <svg class="w-4 h-4 text-amber-600 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="12" cy="12" r="10" stroke-width="2"/><path stroke-width="2" d="M12 6v6l4 2"/>
                        </svg>
                        <span>Masa Pemeriksaan 48 Jam Aktif</span>
                    </div>
                    <h3 class="text-xl font-extrabold text-gray-900 tracking-tight">
                        Paket Sudah Tiba! Sisa Waktu Pemeriksaan: <span class="text-[#4F26A6] font-mono" x-text="hoursLeft"></span>
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-600 max-w-2xl leading-relaxed">
                        Periksa keaslian dan kondisi barang. Jika sesuai, silakan selesaikan pesanan agar dana segera dicairkan ke penjual. Jika ada ketidaksesuaian atau cacat, ajukan komplain sebelum batas waktu berakhir.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0 flex-wrap">
                    <a
                        href="{{ route('dispute.create', $order->order_number) }}"
                        class="px-5 py-3 rounded-xl border-2 border-rose-300 text-rose-700 hover:bg-rose-50 font-bold text-xs transition-all cursor-pointer text-center"
                    >
                        Ajukan Komplain (Dispute)
                    </a>
                    <form action="{{ route('orders.complete', $order->order_number) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin barang sudah diterima dengan baik dan ingin menyelesaikan transaksi?');">
                        @csrf
                        <button
                            type="submit"
                            class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition-all cursor-pointer text-center"
                        >
                            Selesaikan Pesanan &rarr;
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Fast Path: Shipped state confirmation button -->
        @if($order->status::$name === 'shipped')
            <div class="mb-8 p-6 rounded-3xl bg-purple-50/80 border border-purple-200/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <h3 class="text-base font-extrabold text-gray-900">Paket Dalam Perjalanan</h3>
                    <p class="text-xs text-gray-600">
                        Kurir <strong>{{ $order->shipment->courier_name ?? 'Kurir' }}</strong> dengan no. resi <span class="font-mono font-bold">{{ $order->shipment->tracking_number ?? '-' }}</span>. Jika paket sudah sampai di tanganmu, konfirmasi di sini:
                    </p>
                </div>
                <form action="{{ route('orders.confirm_delivered', $order->order_number) }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="px-6 py-3 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs shadow-md shadow-[#4F26A6]/20 transition-all cursor-pointer whitespace-nowrap"
                    >
                        Barang Sudah Diterima &rarr;
                    </button>
                </form>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Column: Items & Shipment Details (8 cols) -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Items Card -->
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8 space-y-5">
                    <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Daftar Barang</h3>

                    <div class="divide-y divide-gray-100 space-y-4">
                        @foreach($order->items as $item)
                            <div class="pt-4 first:pt-0 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3.5">
                                    <img
                                        src="{{ $item->variant?->product?->primary_image_url ?? '/assets/products/prod-hoodie.png' }}"
                                        alt="{{ $item->product_name_snapshot }}"
                                        class="w-16 h-16 rounded-2xl object-cover border border-gray-100 bg-gray-50 shrink-0"
                                    />
                                    <div>
                                        <h4 class="text-sm sm:text-[15px] font-bold text-gray-900">
                                            {{ $item->product_name_snapshot }}
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            Varian: {{ $item->variant_name_snapshot }} • {{ $item->quantity }} pcs
                                        </p>
                                    </div>
                                </div>
                                <span class="text-sm sm:text-base font-extrabold text-[#4F26A6]">
                                    Rp {{ number_format((float)$item->subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Shipment & Tracking Info Card -->
                @if($order->shipment)
                    <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Informasi Pengiriman &amp; Resi</h3>
                            <a
                                href="https://cekresi.com/?noresi={{ $order->shipment->tracking_number }}"
                                target="_blank"
                                class="text-xs font-bold text-[#4F26A6] hover:underline"
                            >
                                Cek Tracking Eksternal &rarr;
                            </a>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="p-4 rounded-2xl bg-[#FAF9FC] border border-gray-100">
                                <span class="text-gray-400 block mb-1">Kurir Pengiriman:</span>
                                <span class="font-bold text-gray-900 text-sm">{{ $order->shipment->courier_name }}</span>
                            </div>
                            <div class="p-4 rounded-2xl bg-[#FAF9FC] border border-gray-100">
                                <span class="text-gray-400 block mb-1">Nomor Resi:</span>
                                <span class="font-mono font-bold text-gray-900 text-sm">{{ $order->shipment->tracking_number }}</span>
                            </div>
                        </div>

                        @if($order->shipment->pre_shipment_photo_path)
                            <div class="pt-3 border-t border-gray-100">
                                <span class="text-xs font-bold text-gray-500 block mb-2">Foto Kondisi Barang Saat Dipacking Seller:</span>
                                <a href="/storage/{{ $order->shipment->pre_shipment_photo_path }}" target="_blank" class="w-28 h-28 rounded-2xl overflow-hidden bg-gray-100 border border-gray-200 block">
                                    <img src="/storage/{{ $order->shipment->pre_shipment_photo_path }}" alt="Foto Packing" class="w-full h-full object-cover" />
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Right Column: Address & Payment Breakdown (4 cols) -->
            <div class="lg:col-span-4 space-y-6 sticky top-24">
                <!-- Address Card -->
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-7 space-y-3">
                    <h3 class="text-base font-extrabold text-gray-900 tracking-tight">Alamat Tujuan</h3>
                    <div class="text-xs text-gray-600 leading-relaxed space-y-1">
                        <p class="font-bold text-gray-900">{{ $order->address_snapshot['recipient_name'] ?? '-' }}</p>
                        <p class="text-gray-500">{{ $order->address_snapshot['phone'] ?? '-' }}</p>
                        <p>{{ $order->address_snapshot['full_address'] ?? '-' }}, {{ $order->address_snapshot['district'] ?? '' }}, {{ $order->address_snapshot['city'] ?? '' }} {{ $order->address_snapshot['postal_code'] ?? '' }}</p>
                    </div>
                </div>

                <!-- Payment Breakdown Card -->
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-7 space-y-3.5">
                    <h3 class="text-base font-extrabold text-gray-900 tracking-tight">Rincian Pembayaran</h3>

                    <div class="space-y-2.5 text-xs text-gray-600 pb-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <span>Subtotal Barang</span>
                            <span class="font-semibold text-gray-900">Rp {{ number_format((float)$order->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Ongkos Kirim</span>
                            <span class="font-semibold text-gray-900">Rp {{ number_format((float)$order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Metode Bayar</span>
                            <span class="font-semibold text-gray-900">Transfer Manual BCA</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1 text-sm font-extrabold">
                        <span class="text-gray-700">Total Transaksi:</span>
                        <span class="text-lg text-[#4F26A6]">Rp {{ number_format((float)$order->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>
