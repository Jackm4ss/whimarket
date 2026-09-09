<x-layouts.app :title="$title" activeTab="seller-orders">
    <main
        class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10"
        x-data="sellerOrdersManager"
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
                        <div class="px-5 sm:px-6 py-4 bg-[#FAF9FC] border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3 sm:gap-4 flex-wrap">
                                {{-- Buyer avatar + info --}}
                                @if($order->buyer)
                                    <div class="flex items-center gap-2.5 shrink-0">
                                        <img
                                            src="{{ $order->buyer->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($order->buyer->name).'&background=F3EEFF&color=4F26A6&bold=true&size=40' }}"
                                            alt="{{ $order->buyer->name }}"
                                            width="36"
                                            height="36"
                                            class="w-9 h-9 rounded-full object-cover ring-2 ring-white shadow-sm"
                                        />
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-900 leading-tight">{{ $order->buyer->name }}</span>
                                            <span class="text-[11px] text-gray-400 font-medium leading-tight">{{ $order->buyer->phone ?? $order->buyer->email }}</span>
                                        </div>
                                    </div>
                                    <span class="text-gray-200 hidden sm:block">|</span>
                                @endif
                                {{-- Order number + date --}}
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-gray-950 text-[13px] sm:text-sm">#{{ $order->order_number }}</span>
                                    <span class="text-xs text-gray-400 font-medium">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                {{ in_array($order->status::$name, ['paid', 'processing', 'payment_verification'], true) ? 'bg-purple-50 text-[#4F26A6] border border-purple-200/60' : '' }}
                                {{ $order->status::$name === 'pending_payment' ? 'bg-amber-50 text-amber-800 border border-amber-200/60' : '' }}
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
                                    @php
                                        $productImage = $item->variant?->product?->primary_image_url ?? '/assets/placeholder-product.png';
                                    @endphp
                                    <div class="flex items-center gap-3 text-sm">
                                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl overflow-hidden bg-[#F3EEFF] border border-gray-100 shrink-0">
                                            <img
                                                src="{{ $productImage }}"
                                                alt="{{ $item->product_name_snapshot }}"
                                                width="64"
                                                height="64"
                                                loading="lazy"
                                                decoding="async"
                                                class="w-full h-full object-cover"
                                            />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-gray-900 truncate">{{ $item->product_name_snapshot }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5">Varian: {{ $item->variant_name_snapshot }} &bull; {{ $item->quantity }} pcs</p>
                                            <p class="text-sm font-bold text-[#4F26A6] mt-1 sm:hidden">
                                                Rp {{ number_format((float)$item->subtotal, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <span class="font-bold text-[#4F26A6] text-sm shrink-0 hidden sm:block">
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
                            <div class="lg:col-span-5 bg-[#FAF9FC] rounded-2xl p-4 sm:p-5 border border-gray-100 flex flex-col justify-between gap-4">
                                @php
                                    $addr = $order->address_snapshot ?? [];
                                    $recipientName = $addr['recipient_name'] ?? ($order->buyer?->name ?? 'Pembeli');
                                    $phone = $addr['phone'] ?? ($order->buyer?->phone ?? '-');
                                    $fullAddress = $addr['full_address'] ?? '-';
                                    $district = $addr['district'] ?? null;
                                    $city = $addr['city'] ?? null;
                                    $province = $addr['province'] ?? null;
                                    $postalCode = $addr['postal_code'] ?? null;

                                    $regionItems = [];
                                    if (!empty($district)) {
                                        $regionItems[] = 'Kec. ' . $district;
                                    }
                                    if (!empty($city)) {
                                        $regionItems[] = $city;
                                    }
                                    if (!empty($province)) {
                                        $regionItems[] = $province;
                                    }
                                    if (!empty($postalCode)) {
                                        $regionItems[] = $postalCode;
                                    }
                                    $regionLine = implode(', ', $regionItems);

                                    $clipboardAddress = trim($recipientName . " (" . $phone . ")\n" . $fullAddress . (!empty($regionLine) ? "\n" . $regionLine : ''));
                                @endphp

                                <div class="space-y-3">
                                    <!-- Section Header: Badge & Salin Alamat Button -->
                                    <div class="flex items-center justify-between gap-2 pb-2.5 border-b border-gray-200/70">
                                        <div class="flex items-center gap-1.5 text-xs font-bold text-[#4F26A6]">
                                            <span class="w-6 h-6 rounded-lg bg-[#F3EEFF] flex items-center justify-center text-[#4F26A6] shrink-0">
                                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                            <span class="tracking-tight">Alamat Tujuan Pengiriman</span>
                                        </div>

                                        <button
                                            type="button"
                                            @click="copyAddress({{ $order->id }}, {{ json_encode($clipboardAddress) }})"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold transition-all cursor-pointer shadow-2xs active:scale-95"
                                            :class="copiedOrderId === {{ $order->id }} ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 hover:border-gray-300'"
                                            title="Salin Alamat Lengkap"
                                        >
                                            <span x-show="copiedOrderId !== {{ $order->id }}" class="flex items-center gap-1">
                                                <svg class="w-3 h-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                <span>Salin Alamat</span>
                                            </span>
                                            <span x-show="copiedOrderId === {{ $order->id }}" x-cloak class="flex items-center gap-1 text-emerald-700 font-bold">
                                                <svg class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                <span>Tersalin!</span>
                                            </span>
                                        </button>
                                    </div>

                                    <!-- Recipient info -->
                                    <div>
                                        <div class="flex items-baseline gap-2 flex-wrap">
                                            <span class="text-sm font-extrabold text-gray-950 tracking-tight">{{ $recipientName }}</span>
                                            <span class="text-xs font-mono font-semibold text-gray-600 inline-flex items-center gap-1 bg-white px-2 py-0.5 rounded-md border border-gray-100 shadow-2xs">
                                                <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                                {{ $phone }}
                                            </span>
                                        </div>

                                        <!-- Street detail -->
                                        <p class="text-xs text-gray-800 font-medium leading-relaxed mt-1.5">
                                            {{ $fullAddress }}
                                        </p>

                                        <!-- Complete region hierarchy: Kecamatan, Kota, Provinsi, Kode Pos -->
                                        @if(!empty($regionLine))
                                            <p class="text-[11.5px] text-gray-500 font-normal leading-relaxed mt-0.5">
                                                {{ $regionLine }}
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Shipping Courier & Cost Box -->
                                    <div class="p-3 rounded-xl bg-white border border-gray-100 shadow-2xs flex items-center justify-between text-xs gap-3">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-[#F3EEFF] flex items-center justify-center text-[#4F26A6] shrink-0">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                @if($order->shipment)
                                                    <p class="text-[10.5px] text-gray-400 font-medium uppercase tracking-wider leading-none">Kurir Pengiriman</p>
                                                    <p class="text-xs font-bold text-gray-900 truncate mt-0.5">
                                                        {{ $order->shipment->courier_name }} (Resi: {{ $order->shipment->tracking_number }})
                                                    </p>
                                                @else
                                                    <p class="text-[10.5px] text-gray-400 font-medium uppercase tracking-wider leading-none">Layanan Pengiriman</p>
                                                    <p class="text-xs font-bold text-gray-900 truncate mt-0.5">
                                                        Layanan: Pengiriman Reguler (Rp {{ number_format((float)$order->shipping_cost, 0, ',', '.') }})
                                                    </p>
                                                    <p class="text-[10.5px] text-gray-400 mt-0.5">Bebas pilih kurir terdekat saat kirim barang.</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <p class="text-[10.5px] text-gray-400 font-medium uppercase tracking-wider leading-none">Ongkos Kirim</p>
                                            <p class="text-xs font-extrabold text-[#4F26A6] mt-0.5">
                                                @if((float)$order->shipping_cost > 0)
                                                    Rp {{ number_format((float)$order->shipping_cost, 0, ',', '.') }}
                                                @else
                                                    Gratis Ongkir
                                                @endif
                                            </p>
                                        </div>
                                    </div>
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
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs overflow-y-auto"
        >
            <div
                @click.outside="fulfillModalOpen = false"
                class="bg-white rounded-3xl border border-gray-100 shadow-[0_25px_60px_rgba(79,38,166,0.2)] max-w-lg w-full relative my-8 overflow-hidden"
            >
                <!-- Header Illustration Banner -->
                <div class="relative h-28 sm:h-32 bg-gradient-to-r from-purple-100 via-[#F3EEFF] to-amber-50 flex items-center justify-between px-6 sm:px-8 border-b border-gray-100">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/90 shadow-2xs text-[#4F26A6] text-[10.5px] font-extrabold mb-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Konfirmasi Pengiriman
                        </span>
                        <h3 class="text-lg sm:text-xl font-black text-gray-900 tracking-tight">Kirim Pesanan</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Pesanan <span class="font-mono font-bold text-[#4F26A6]" x-text="'#' + activeOrderNumber"></span></p>
                    </div>
                    <img
                        src="/assets/modals/shipping-header.png"
                        alt="Kirim Pesanan"
                        class="w-24 h-24 sm:w-28 sm:h-28 object-contain drop-shadow-md shrink-0 -mr-2"
                    />
                    <button
                        type="button"
                        @click="fulfillModalOpen = false"
                        class="absolute top-3 right-3 w-7 h-7 rounded-full bg-white/80 hover:bg-white text-gray-600 flex items-center justify-center transition-all cursor-pointer shadow-2xs"
                        title="Tutup"
                    >
                        ✕
                    </button>
                </div>

                <div class="p-6 sm:p-7">
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
        </div>
    </main>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sellerOrdersManager', () => ({
                fulfillModalOpen: false,
                activeOrderId: null,
                activeOrderNumber: '',
                copiedOrderId: null,
                openFulfillModal(orderId, orderNumber) {
                    this.activeOrderId = orderId;
                    this.activeOrderNumber = orderNumber;
                    this.fulfillModalOpen = true;
                },
                copyAddress(orderId, text) {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(text).then(() => {
                            this.copiedOrderId = orderId;
                            setTimeout(() => {
                                if (this.copiedOrderId === orderId) {
                                    this.copiedOrderId = null;
                                }
                            }, 2000);
                        });
                    }
                }
            }));
        });
    </script>
    @endpush
</x-layouts.app>
