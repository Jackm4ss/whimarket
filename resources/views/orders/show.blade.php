<x-layouts.app :title="$title" activeTab="pesanan">
    <main
        class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10"
        x-data="{
            completeModalOpen: false,
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
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8 pb-5 sm:pb-6 border-b border-gray-100">
            <div class="space-y-2">
                <a
                    href="{{ route('orders.index') }}"
                    class="text-xs sm:text-sm font-bold text-[#4F26A6] hover:text-[#3E1D85] inline-flex items-center gap-1.5 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Pesanan Saya</span>
                </a>
                <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-gray-950 tracking-tight">
                        Pesanan <span class="font-mono text-gray-800">#{{ $order->order_number }}</span>
                    </h1>
                    <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold
                        {{ $order->status::$name === 'pending_payment' ? 'bg-amber-50 text-amber-800 border border-amber-200/70' : '' }}
                        {{ $order->status::$name === 'payment_verification' || $order->status::$name === 'processing' ? 'bg-purple-50 text-[#4F26A6] border border-purple-200/70' : '' }}
                        {{ $order->status::$name === 'paid' ? 'bg-purple-50 text-[#4F26A6] border border-purple-200/70' : '' }}
                        {{ $order->status::$name === 'shipped' ? 'bg-amber-50 text-amber-800 border border-amber-200/70' : '' }}
                        {{ $order->status::$name === 'delivered' || $order->status::$name === 'completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/70' : '' }}
                        {{ $order->status::$name === 'disputed' ? 'bg-rose-50 text-rose-700 border border-rose-200/70' : '' }}
                        {{ $order->status::$name === 'cancelled' ? 'bg-gray-100 text-gray-700 border border-gray-200' : '' }}
                    ">
                        {{ $order->status->label() }}
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-gray-500">
                    Dipesan pada {{ $order->created_at->format('d F Y, H:i') }} • Penjual: <a href="{{ url('/seller/@'.$order->seller->username) }}" class="font-bold text-gray-900 hover:text-[#4F26A6] transition-colors">{{ $order->seller->store_name }}</a>
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
        @php
            $isPendingPayment = $order->status::$name === 'pending_payment';
            $hasOutOfStockItem = $isPendingPayment && $order->items->contains(function ($item) {
                return ($item->variant?->stock ?? 0) <= 0 || ($item->variant?->product?->total_stock ?? 0) <= 0;
            });
        @endphp

        @if($isPendingPayment)
            @if($hasOutOfStockItem)
                <div class="mb-8 p-6 sm:p-7 rounded-3xl bg-rose-50 border border-rose-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-100 text-rose-800 text-xs font-bold mb-1">
                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>Stok Produk Habis</span>
                        </div>
                        <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Barang dalam Pesanan Ini Telah Habis</h3>
                        <p class="text-xs sm:text-sm text-gray-600 leading-relaxed max-w-xl">
                            Penjual telah memperbarui stok barang menjadi 0 sehingga pembayaran untuk pesanan ini tidak dapat dilanjutkan.
                        </p>
                    </div>
                </div>
            @else
                <div class="mb-8 p-6 sm:p-7 rounded-3xl bg-purple-50/80 border border-purple-200/60 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-900 text-xs font-bold mb-1">
                            <span>Menunggu Pembayaran</span>
                        </div>
                        <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Selesaikan Pembayaran Kamu</h3>
                        <p class="text-xs sm:text-sm text-gray-600">
                            Silakan lakukan transfer pembayaran sebelum batas waktu berakhir.
                        </p>
                    </div>
                    <a
                        href="{{ route('payment.show', $order->order_number) }}"
                        class="px-6 py-3 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs sm:text-sm shadow-md transition-all cursor-pointer whitespace-nowrap text-center"
                    >
                        Bayar Sekarang &rarr;
                    </a>
                </div>
            @endif
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
                    <button
                        type="button"
                        @click="completeModalOpen = true"
                        class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition-all cursor-pointer text-center"
                    >
                        Selesaikan Pesanan &rarr;
                    </button>
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
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-5 sm:p-8 space-y-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Daftar Barang</h3>
                        <span class="text-xs text-gray-400 font-semibold">{{ $order->items->count() }} Produk</span>
                    </div>

                    <div class="divide-y divide-gray-100 space-y-4">
                        @foreach($order->items as $item)
                            <div class="pt-4 first:pt-0 flex items-start sm:items-center justify-between gap-4">
                                <div class="flex items-start sm:items-center gap-3.5 sm:gap-4 flex-1 min-w-0">
                                    <img
                                        src="{{ $item->variant?->product?->primary_image_url ?? '/assets/products/prod-hoodie.png' }}"
                                        alt="{{ $item->product_name_snapshot }}"
                                        class="w-16 h-16 sm:w-18 sm:h-18 rounded-2xl object-cover border border-gray-100 bg-[#F3EEFF] shrink-0"
                                    />
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm sm:text-[15px] font-bold text-gray-900 leading-snug line-clamp-2">
                                            {{ $item->product_name_snapshot }}
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-1 flex items-center gap-2 flex-wrap">
                                            <span>Varian: <strong class="text-gray-700 font-semibold">{{ $item->variant_name_snapshot }}</strong></span>
                                            <span class="text-gray-300">•</span>
                                            <span>{{ $item->quantity }} pcs</span>
                                        </p>
                                        <p class="text-sm sm:text-base font-extrabold text-[#4F26A6] mt-1.5 sm:hidden">
                                            Rp {{ number_format((float)$item->subtotal, 0, ',', '.') }}
                                        </p>
                                        @if(($item->variant?->stock ?? 0) <= 0 || ($item->variant?->product?->total_stock ?? 0) <= 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-rose-50 text-rose-600 font-bold text-[10px] mt-1.5 border border-rose-200/60">
                                                Stok habis dari penjual
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="hidden sm:block text-right shrink-0">
                                    <span class="text-sm sm:text-base font-extrabold text-[#4F26A6]">
                                        Rp {{ number_format((float)$item->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Shipment & Tracking Info Card -->
                @if($order->shipment)
                    <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8 space-y-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Informasi Pengiriman &amp; Resi</h3>
                            <a
                                href="https://cekresi.com/?noresi={{ $order->shipment->tracking_number }}"
                                target="_blank"
                                class="text-xs font-bold text-[#4F26A6] hover:underline inline-flex items-center gap-1"
                            >
                                <span>Cek Tracking Eksternal</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="p-5 rounded-2xl bg-[#FAF9FC] border border-gray-100 flex items-start gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-white border border-gray-200/70 shadow-xs flex items-center justify-center shrink-0 text-[#4F26A6] mt-0.5">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-0.5">Kurir Pengiriman</span>
                                    <span class="font-bold text-gray-900 text-sm sm:text-base">{{ $order->shipment->courier_name }}</span>
                                </div>
                            </div>
                            <div class="p-5 rounded-2xl bg-[#FAF9FC] border border-gray-100 flex items-start gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-white border border-gray-200/70 shadow-xs flex items-center justify-center shrink-0 text-[#4F26A6] mt-0.5">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-0.5">Nomor Resi</span>
                                    <span class="font-mono font-bold text-gray-950 text-sm sm:text-base">{{ $order->shipment->tracking_number }}</span>
                                </div>
                            </div>
                        </div>

                        @if($order->shipment->pre_shipment_photo_path)
                            <div class="pt-4 border-t border-gray-100">
                                <span class="text-xs font-bold text-gray-500 block mb-2.5">Foto Kondisi Barang Saat Dipacking Seller:</span>
                                <a href="/storage/{{ $order->shipment->pre_shipment_photo_path }}" target="_blank" class="w-28 h-28 rounded-2xl overflow-hidden bg-gray-100 border border-gray-200 block hover:opacity-95 transition-opacity">
                                    <img src="/storage/{{ $order->shipment->pre_shipment_photo_path }}" alt="Foto Packing" class="w-full h-full object-cover" />
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8 space-y-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Informasi Pengiriman</h3>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#F3EEFF] text-[#4F26A6]">
                                <svg class="w-3.5 h-3.5 text-[#4F26A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Pilihan Resmi</span>
                            </span>
                        </div>

                        <div class="p-5 sm:p-6 rounded-2xl bg-[#FAF9FC] border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-5 sm:gap-6">
                            <div class="flex items-start gap-4">
                                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-white border border-gray-200/70 shadow-xs flex items-center justify-center shrink-0 text-[#4F26A6] mt-0.5">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                    </svg>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Layanan Pengiriman</span>
                                    <span class="font-bold text-gray-950 text-sm sm:text-base block">Pengiriman Standar / Reguler</span>
                                    <p class="text-xs sm:text-[13px] text-gray-500 leading-relaxed max-w-xl pt-0.5">
                                        Paket akan dikirim menggunakan ekspedisi terpercaya (J&amp;T / JNE / SiCepat). Nomor resi kurir akan diperbarui otomatis setelah penjual memproses dan menyerahkan paket.
                                    </p>
                                </div>
                            </div>

                            <div class="pt-4 md:pt-0 border-t md:border-t-0 md:border-l border-gray-200/80 md:pl-6 text-left md:text-right shrink-0">
                                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Ongkos Kirim</span>
                                <span class="font-black text-[#4F26A6] text-base sm:text-lg block tracking-tight">
                                    @if((float)$order->shipping_cost > 0)
                                        Rp {{ number_format((float)$order->shipping_cost, 0, ',', '.') }}
                                    @else
                                        Gratis Ongkir
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Address & Payment Breakdown (4 cols) -->
            <div class="lg:col-span-4 space-y-6 sticky top-24">
                <!-- Address Card -->
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-7 space-y-3">
                    <h3 class="text-base font-extrabold text-gray-900 tracking-tight">Alamat Tujuan</h3>
                    @php
                        $bAddr = $order->address_snapshot ?? [];
                        $bRegion = array_filter([
                            !empty($bAddr['district']) ? 'Kec. ' . $bAddr['district'] : null,
                            $bAddr['city'] ?? null,
                            $bAddr['province'] ?? null,
                            $bAddr['postal_code'] ?? null,
                        ]);
                    @endphp
                    <div class="text-xs text-gray-600 leading-relaxed space-y-1">
                        <p class="font-bold text-gray-900">{{ $bAddr['recipient_name'] ?? '-' }}</p>
                        <p class="text-gray-500">{{ $bAddr['phone'] ?? '-' }}</p>
                        <p>{{ $bAddr['full_address'] ?? '-' }}</p>
                        @if(!empty($bRegion))
                            <p class="text-gray-500">{{ implode(', ', $bRegion) }}</p>
                        @endif
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
                        @if((float)($order->admin_fee ?? 0) > 0)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span>Biaya Layanan</span>
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-[#4F26A6]">Fee Admin</span>
                            </div>
                            <span class="font-semibold text-gray-900">Rp {{ number_format((float)$order->admin_fee, 0, ',', '.') }}</span>
                        </div>
                        @endif
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

        <!-- Modal Konfirmasi Selesaikan Pesanan -->
        <div
            x-show="completeModalOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs overflow-y-auto"
        >
            <div
                @click.outside="completeModalOpen = false"
                x-show="completeModalOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                class="bg-white rounded-3xl overflow-hidden max-w-md w-full shadow-[0_25px_60px_rgba(79,38,166,0.22)] border border-gray-100 relative my-8 text-center"
            >
                <!-- Modal Banner Header with Generated Illustration -->
                <div class="relative h-32 sm:h-36 w-full overflow-hidden bg-gradient-to-br from-[#4F26A6] to-[#059669] flex items-center justify-center">
                    <img
                        src="/assets/modals/action-complete-header.png"
                        alt="Selesaikan Pesanan"
                        class="w-full h-full object-cover mix-blend-luminosity opacity-45 absolute inset-0"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-transparent to-transparent"></div>

                    <button
                        type="button"
                        @click="completeModalOpen = false"
                        class="absolute top-3.5 right-3.5 z-20 w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-md text-white flex items-center justify-center transition-all cursor-pointer"
                        title="Tutup"
                    >
                        ✕
                    </button>

                    <div class="relative z-10 text-center px-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-white text-[11px] font-extrabold mb-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Konfirmasi Penerimaan
                        </span>
                        <h3 class="text-lg sm:text-xl font-black text-white tracking-tight">Selesaikan Pesanan Ini?</h3>
                    </div>
                </div>

                <div class="p-6 sm:p-7 space-y-5 text-left">
                    <div class="p-4 rounded-2xl bg-[#FAF9FC] border border-gray-100/90 shadow-2xs">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Nomor Pesanan:</p>
                        <h4 class="text-sm font-extrabold font-mono text-[#4F26A6]">{{ $order->order_number }}</h4>
                        <p class="text-xs text-gray-600 font-medium mt-1">Total Transaksi: <strong class="text-gray-900">Rp {{ number_format((float)$order->grand_total, 0, ',', '.') }}</strong></p>
                    </div>

                    <p class="text-xs sm:text-[13px] text-gray-500 leading-relaxed text-center">
                        Pastikan barang sudah kamu terima dalam kondisi baik. Setelah diselesaikan, dana escrow akan otomatis diteruskan ke penjual.
                    </p>

                    <form action="{{ route('orders.complete', $order->order_number) }}" method="POST" class="grid grid-cols-2 gap-3 pt-1">
                        @csrf
                        <button
                            type="button"
                            @click="completeModalOpen = false"
                            class="w-full py-3 rounded-2xl border border-gray-200 hover:bg-gray-100 text-gray-700 font-bold text-xs sm:text-sm transition-all cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="w-full py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-emerald-600/25 transition-all cursor-pointer"
                        >
                            Ya, Selesaikan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>
