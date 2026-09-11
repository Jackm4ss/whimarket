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
                                            @php
                                                $headerPhone = $order->buyer->phone;
                                                $headerCleanWa = null;
                                                if ($headerPhone) {
                                                    $hDigits = preg_replace('/[^0-9]/', '', (string) $headerPhone);
                                                    if (!empty($hDigits)) {
                                                        if (str_starts_with($hDigits, '0')) {
                                                            $headerCleanWa = '62' . substr($hDigits, 1);
                                                        } elseif (str_starts_with($hDigits, '8')) {
                                                            $headerCleanWa = '62' . $hDigits;
                                                        } elseif (str_starts_with($hDigits, '62')) {
                                                            $headerCleanWa = $hDigits;
                                                        } else {
                                                            $headerCleanWa = $hDigits;
                                                        }
                                                    }
                                                }
                                                $headerStore = $order->seller?->store_name ?? 'WhiMarket';
                                                $headerWaUrl = $headerCleanWa ? 'https://wa.me/' . $headerCleanWa . '?text=' . rawurlencode('Halo Kak ' . $order->buyer->name . ', saya dari toko ' . $headerStore . ' (WhiMarket) terkait pesanan #' . $order->order_number . '.') : null;
                                            @endphp
                                            <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                                <span class="text-[11px] text-gray-500 font-medium leading-tight font-mono">{{ $headerPhone ?? $order->buyer->email }}</span>
                                                @if($headerWaUrl)
                                                    <a
                                                        href="{{ $headerWaUrl }}"
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        style="color: #16A34A;"
                                                        class="inline-flex items-center gap-1 text-[11px] font-bold hover:underline"
                                                        title="Chat Pembeli via WhatsApp"
                                                    >
                                                        <svg style="color: #22C55E;" class="w-3 h-3 fill-current shrink-0" viewBox="0 0 24 24">
                                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2..."/>
                                                        </svg>
                                                        <span>WA</span>
                                                    </a>
                                                @endif
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

                                    $cleanPhone = $phone !== '-' ? preg_replace('/[^0-9]/', '', (string) $phone) : null;
                                    $cleanWa = null;
                                    if (!empty($cleanPhone)) {
                                        if (str_starts_with($cleanPhone, '0')) {
                                            $cleanWa = '62' . substr($cleanPhone, 1);
                                        } elseif (str_starts_with($cleanPhone, '8')) {
                                            $cleanWa = '62' . $cleanPhone;
                                        } elseif (str_starts_with($cleanPhone, '62')) {
                                            $cleanWa = $cleanPhone;
                                        } else {
                                            $cleanWa = $cleanPhone;
                                        }
                                    }
                                    $storeName = $order->seller?->store_name ?? 'WhiMarket';
                                    $waText = 'Halo Kak ' . $recipientName . ', saya seller dari toko ' . $storeName . ' (WhiMarket) terkait pesanan #' . $order->order_number . '. Apakah ada catatan atau konfirmasi pengiriman tambahan?';
                                    $waUrl = $cleanWa ? 'https://wa.me/' . $cleanWa . '?text=' . rawurlencode($waText) : null;
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
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold transition-all cursor-pointer shadow-2xs active:scale-95 border"
                                            :class="(copiedKey === 'addr_{{ $order->id }}' || copiedOrderId === {{ $order->id }}) ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-white hover:bg-gray-50 text-gray-700 border-gray-200 hover:border-gray-300'"
                                            title="Salin Alamat Lengkap"
                                        >
                                            <span x-show="copiedKey !== 'addr_{{ $order->id }}' && copiedOrderId !== {{ $order->id }}" class="flex items-center gap-1">
                                                <svg class="w-3 h-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                <span>Salin Alamat</span>
                                            </span>
                                            <span x-show="copiedKey === 'addr_{{ $order->id }}' || copiedOrderId === {{ $order->id }}" x-cloak class="flex items-center gap-1 text-emerald-700 font-bold">
                                                <svg class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                <span>Tersalin!</span>
                                            </span>
                                        </button>
                                    </div>

                                    <!-- Recipient info -->
                                    <div>
                                        <div class="flex items-center justify-between gap-2 flex-wrap mb-1.5">
                                            <span class="text-sm font-extrabold text-gray-950 tracking-tight">{{ $recipientName }}</span>
                                            @if($cleanWa)
                                                <a
                                                    href="{{ $waUrl }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    style="background-color: #22C55E; color: #ffffff;"
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg hover:opacity-90 text-[11px] font-bold shadow-xs transition-all active:scale-95 cursor-pointer shrink-0"
                                                    title="Hubungi Pembeli langsung di WhatsApp"
                                                >
                                                    <svg class="w-3.5 h-3.5 fill-current shrink-0" viewBox="0 0 24 24">
                                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2..."/>
                                                    </svg>
                                                    <span>Chat WA</span>
                                                </a>
                                            @endif
                                        </div>

                                        <!-- Phone info & Copy Phone button -->
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-xs font-mono font-semibold text-gray-700 inline-flex items-center gap-1.5 bg-white px-2.5 py-1 rounded-lg border border-gray-200/80 shadow-2xs">
                                                <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                                <span>{{ $phone }}</span>
                                            </span>

                                            @if($phone !== '-')
                                                <button
                                                    type="button"
                                                    @click="copyPhone({{ $order->id }}, {{ json_encode($phone) }})"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold transition-all cursor-pointer shadow-2xs active:scale-95 border"
                                                    :class="copiedKey === 'phone_{{ $order->id }}' ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-white hover:bg-gray-50 text-gray-700 border-gray-200 hover:border-gray-300'"
                                                    title="Salin Nomor HP"
                                                >
                                                    <span x-show="copiedKey !== 'phone_{{ $order->id }}'" class="flex items-center gap-1">
                                                        <svg class="w-3 h-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                        <span>Salin No. HP</span>
                                                    </span>
                                                    <span x-show="copiedKey === 'phone_{{ $order->id }}'" x-cloak class="flex items-center gap-1 text-emerald-700 font-bold">
                                                        <svg class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                        <span>Tersalin!</span>
                                                    </span>
                                                </button>
                                            @endif
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
                                    @elseif(in_array($order->status::$name, ['delivered', 'completed']))
                                        @php
                                            $payout = $order->payout;
                                        @endphp
                                        @if($payout && $payout->status === \App\Enums\PayoutStatus::PAID)
                                            <div class="p-3.5 rounded-2xl bg-emerald-50/90 border border-emerald-200 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                                <div class="flex items-start gap-2.5">
                                                    <span class="w-7 h-7 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                    </span>
                                                    <div>
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <span class="font-extrabold text-emerald-950">Saldo Telah Dicairkan</span>
                                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-200 text-emerald-900 border border-emerald-300 uppercase tracking-wider">Sukses</span>
                                                        </div>
                                                        <p class="text-emerald-800 text-[11.5px] mt-0.5">
                                                            Dana penjualan <strong>Rp {{ number_format((float)$payout->amount, 0, ',', '.') }}</strong> telah ditransfer ke {{ $payout->bank_details_snapshot['bank_name'] ?? ($seller->bank_name ?? 'Bank') }} ({{ $payout->bank_details_snapshot['account_number'] ?? ($seller->bank_account_number ?? '-') }}).
                                                        </p>
                                                    </div>
                                                </div>
                                                @if($payout->transfer_proof_path)
                                                    <button
                                                        type="button"
                                                        @click="openProofModal('{{ asset('storage/' . $payout->transfer_proof_path) }}', '{{ $order->order_number }}', 'Rp {{ number_format((float)$payout->amount, 0, ',', '.') }}', '{{ ($payout->bank_details_snapshot['bank_name'] ?? $seller->bank_name) . ' - ' . ($payout->bank_details_snapshot['account_number'] ?? $seller->bank_account_number) . ' (a/n ' . ($payout->bank_details_snapshot['account_name'] ?? $seller->bank_account_name) . ')' }}', '{{ $payout->processed_at?->translatedFormat('d M Y, H:i') }}')"
                                                        class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-all shadow-xs flex items-center justify-center gap-1.5 shrink-0 cursor-pointer self-stretch sm:self-auto"
                                                    >
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        <span>Lihat Bukti Transfer</span>
                                                    </button>
                                                @endif
                                            </div>
                                        @elseif($payout && in_array($payout->status, [\App\Enums\PayoutStatus::PENDING, \App\Enums\PayoutStatus::PROCESSING]))
                                            <div class="p-3.5 rounded-2xl bg-amber-50/90 border border-amber-200 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                                <div class="flex items-start gap-2.5">
                                                    <span class="w-7 h-7 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    </span>
                                                    <div>
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <span class="font-extrabold text-amber-950">Dalam Antrean Pencairan Saldo</span>
                                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-200 text-amber-900 border border-amber-300 uppercase tracking-wider">Menunggu Admin</span>
                                                        </div>
                                                        <p class="text-amber-800 text-[11.5px] mt-0.5">
                                                            Dana penjualan <strong>Rp {{ number_format((float)$payout->amount, 0, ',', '.') }}</strong> sedang diproses admin untuk ditransfer ke rekening {{ $seller->bank_name }} ({{ $seller->bank_account_number }}).
                                                        </p>
                                                    </div>
                                                </div>
                                                <a
                                                    href="{{ route('seller.payout-account.index') }}"
                                                    class="px-3.5 py-2 rounded-xl border border-amber-300 bg-white hover:bg-amber-50 text-amber-900 font-bold text-xs transition-all shadow-2xs shrink-0 self-stretch sm:self-auto text-center"
                                                >
                                                    <span>Cek Saldo</span>
                                                </a>
                                            </div>
                                        @elseif($order->status::$name === 'completed')
                                            <div class="p-3 rounded-2xl bg-purple-50/70 border border-purple-200/50 text-xs flex items-center justify-between gap-2">
                                                <span class="font-bold text-purple-900">Pesanan Selesai &bull; Dana Penjualan: Rp {{ number_format((float)$order->total_amount, 0, ',', '.') }}</span>
                                                <a href="{{ route('seller.payout-account.index') }}" class="text-[#4F26A6] font-extrabold hover:underline">Rekening Payout &rarr;</a>
                                            </div>
                                        @endif
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
            class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
            style="background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px);"
        >
            <div
                @click.outside="closeFulfillModal()"
                class="rounded-3xl border border-gray-100 shadow-[0_25px_60px_rgba(79,38,166,0.25)] max-w-lg w-full relative my-4 sm:my-8 overflow-hidden max-h-[92vh] flex flex-col"
                style="background-color: #ffffff;"
            >
                <!-- Header Illustration Banner -->
                <div class="relative h-28 sm:h-32 bg-gradient-to-r from-purple-100 via-[#F3EEFF] to-amber-50 flex items-center justify-between px-6 sm:px-8 border-b border-gray-100 shrink-0">
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
                        @click="closeFulfillModal()"
                        class="absolute top-3 right-3 w-7 h-7 rounded-full bg-white/80 hover:bg-white text-gray-600 flex items-center justify-center transition-all cursor-pointer shadow-2xs"
                        title="Tutup"
                    >
                        ✕
                    </button>
                </div>

                <form :action="'/seller/orders/' + activeOrderId + '/fulfill'" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden min-h-0">
                    @csrf
                    <div class="p-5 sm:p-6 overflow-y-auto flex-1 bg-white space-y-4" style="background-color: #ffffff;">

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

                    {{-- Foto Kondisi Barang Sebelum Dipacking --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Foto Kondisi Barang Sebelum Dipacking (Wajib) <span class="text-rose-500">*</span>
                        </label>

                        <!-- Hidden File Input -->
                        <input
                            type="file"
                            x-ref="preShipmentInput"
                            name="pre_shipment_photo"
                            accept="image/png,image/jpeg,image/jpg,image/webp"
                            class="sr-only"
                            @change="handlePreShipmentChange($event)"
                            :required="!preShipmentPreview"
                        />

                        <!-- Dropzone State (No File) -->
                        <div
                            x-show="!preShipmentPreview"
                            @click="triggerPreShipmentInput()"
                            @dragover.prevent="isDraggingPreShipment = true"
                            @dragleave.prevent="isDraggingPreShipment = false"
                            @drop.prevent="handlePreShipmentDrop($event)"
                            class="border-2 border-dashed rounded-2xl p-4 sm:p-5 text-center cursor-pointer transition-all duration-200 select-none group"
                            :class="isDraggingPreShipment ? 'border-[#4F26A6] bg-[#F3EEFF] scale-[1.01]' : 'border-[#4F26A6]/30 bg-[#FAF9FC] hover:bg-[#F3EEFF]/40 hover:border-[#4F26A6]/60'"
                        >
                            <div class="w-11 h-11 rounded-2xl bg-[#EDE9FE] text-[#4F26A6] mx-auto flex items-center justify-center mb-2 shadow-2xs group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <h4 class="text-xs font-bold text-gray-900 mb-0.5">
                                Klik untuk upload foto kondisi barang
                            </h4>
                            <p class="text-[11px] text-gray-500 mb-2.5">
                                atau tarik &amp; lepas file foto ke kotak ini
                            </p>
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#4F26A6] text-white text-xs font-bold shadow-sm group-hover:bg-[#3E1D85] transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                <span>Pilih Foto Barang</span>
                            </span>
                            <p class="text-[10px] text-gray-400 mt-2">
                                Format JPG, PNG, WEBP &bull; Maksimal 10MB
                            </p>
                        </div>

                        <!-- Live Image Preview Card -->
                        <div
                            x-show="preShipmentPreview"
                            x-cloak
                            class="bg-white border-2 border-[#4F26A6]/25 rounded-2xl p-3 sm:p-3.5 shadow-2xs space-y-2.5 transition-all"
                        >
                            <div class="flex items-center justify-between gap-2 pb-2 border-b border-gray-100">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold">
                                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Foto Barang Terpilih
                                </span>
                                <span class="text-[10.5px] font-mono text-gray-400 font-medium" x-text="preShipmentFileSize"></span>
                            </div>

                            <div class="flex items-center gap-3">
                                <!-- Thumbnail with zoom trigger -->
                                <div
                                    @click="openLightbox(preShipmentPreview, 'Foto Kondisi Barang Sebelum Dipacking')"
                                    class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-xl overflow-hidden bg-gray-100 border border-gray-200 shrink-0 cursor-pointer group shadow-2xs"
                                    title="Klik untuk melihat ukuran penuh"
                                >
                                    <img
                                        :src="preShipmentPreview"
                                        alt="Preview Kondisi Barang"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"
                                    />
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                        <svg class="w-4 h-4 drop-shadow-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                                    </div>
                                </div>

                                <!-- Info & Details -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-gray-900 truncate" x-text="preShipmentFileName"></p>
                                    <div class="flex items-center gap-1.5 mt-1">
                                        <span class="px-1.5 py-0.5 rounded bg-purple-50 text-[#4F26A6] font-mono font-bold text-[10px]" x-text="preShipmentFileExt"></span>
                                        <span class="text-[11px] text-emerald-600 font-medium flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                                            Siap diunggah
                                        </span>
                                    </div>
                                    <p class="text-[10.5px] text-gray-400 mt-1">
                                        Sebagai bukti autentik kondisi barang saat dipacking
                                    </p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-2 border-t border-gray-100 gap-2">
                                <button
                                    type="button"
                                    @click="triggerPreShipmentInput()"
                                    class="text-xs text-[#4F26A6] hover:text-[#3E1D85] font-bold flex items-center gap-1 cursor-pointer transition-colors"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    <span>Ganti Foto</span>
                                </button>
                                <button
                                    type="button"
                                    @click="clearPreShipment()"
                                    class="text-xs text-rose-500 hover:text-rose-700 font-semibold flex items-center gap-1 cursor-pointer transition-colors"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Foto Struk / Resi Pengiriman Fisik --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Foto Struk / Resi Pengiriman Fisik
                            </label>
                            <span class="text-[10px] font-bold text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">Opsional</span>
                        </div>

                        <!-- Hidden File Input -->
                        <input
                            type="file"
                            x-ref="receiptInput"
                            name="receipt_photo"
                            accept="image/png,image/jpeg,image/jpg,image/webp"
                            class="sr-only"
                            @change="handleReceiptChange($event)"
                        />

                        <!-- Dropzone State (No File) -->
                        <div
                            x-show="!receiptPreview"
                            @click="triggerReceiptInput()"
                            @dragover.prevent="isDraggingReceipt = true"
                            @dragleave.prevent="isDraggingReceipt = false"
                            @drop.prevent="handleReceiptDrop($event)"
                            class="border-2 border-dashed rounded-2xl p-4 sm:p-5 text-center cursor-pointer transition-all duration-200 select-none group"
                            :class="isDraggingReceipt ? 'border-[#4F26A6] bg-[#F3EEFF] scale-[1.01]' : 'border-gray-200 bg-[#FAF9FC] hover:bg-gray-100/70 hover:border-gray-300'"
                        >
                            <div class="w-11 h-11 rounded-2xl bg-gray-100 text-gray-600 mx-auto flex items-center justify-center mb-2 shadow-2xs group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h4 class="text-xs font-bold text-gray-800 mb-0.5">
                                Klik untuk upload foto struk / resi kurir
                            </h4>
                            <p class="text-[11px] text-gray-500 mb-2.5">
                                Bukti kertas fisik dari loket pengiriman agen kurir
                            </p>
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#0F172A] text-white text-xs font-bold shadow-sm group-hover:bg-black transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                <span>Pilih Foto Struk</span>
                            </span>
                            <p class="text-[10px] text-gray-400 mt-2">
                                Format JPG, PNG, WEBP &bull; Maksimal 10MB
                            </p>
                        </div>

                        <!-- Live Image Preview Card -->
                        <div
                            x-show="receiptPreview"
                            x-cloak
                            class="bg-white border-2 border-gray-200 rounded-2xl p-3 sm:p-3.5 shadow-2xs space-y-2.5 transition-all"
                        >
                            <div class="flex items-center justify-between gap-2 pb-2 border-b border-gray-100">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 text-[11px] font-bold">
                                    <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Foto Struk Terpilih
                                </span>
                                <span class="text-[10.5px] font-mono text-gray-400 font-medium" x-text="receiptFileSize"></span>
                            </div>

                            <div class="flex items-center gap-3">
                                <!-- Thumbnail with zoom trigger -->
                                <div
                                    @click="openLightbox(receiptPreview, 'Foto Struk / Resi Pengiriman Fisik')"
                                    class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-xl overflow-hidden bg-gray-100 border border-gray-200 shrink-0 cursor-pointer group shadow-2xs"
                                    title="Klik untuk melihat ukuran penuh"
                                >
                                    <img
                                        :src="receiptPreview"
                                        alt="Preview Foto Struk"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"
                                    />
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                        <svg class="w-4 h-4 drop-shadow-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                                    </div>
                                </div>

                                <!-- Info & Details -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-gray-900 truncate" x-text="receiptFileName"></p>
                                    <div class="flex items-center gap-1.5 mt-1">
                                        <span class="px-1.5 py-0.5 rounded bg-gray-100 text-gray-700 font-mono font-bold text-[10px]" x-text="receiptFileExt"></span>
                                        <span class="text-[11px] text-blue-600 font-medium flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 inline-block"></span>
                                            Bukti resi fisik
                                        </span>
                                    </div>
                                    <p class="text-[10.5px] text-gray-400 mt-1">
                                        Struk bukti penyerahan paket ke kurir
                                    </p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-2 border-t border-gray-100 gap-2">
                                <button
                                    type="button"
                                    @click="triggerReceiptInput()"
                                    class="text-xs text-gray-700 hover:text-gray-900 font-bold flex items-center gap-1 cursor-pointer transition-colors"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    <span>Ganti Struk</span>
                                </button>
                                <button
                                    type="button"
                                    @click="clearReceipt()"
                                    class="text-xs text-rose-500 hover:text-rose-700 font-semibold flex items-center gap-1 cursor-pointer transition-colors"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    </div>
                    <div class="px-5 sm:px-6 py-3.5 border-t border-gray-100 flex items-center justify-end gap-3 bg-white shrink-0" style="background-color: #ffffff;">
                        <button type="button" @click="closeFulfillModal()" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-gray-500 hover:text-gray-800 cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white text-xs font-bold shadow-md shadow-[#4F26A6]/20 transition-all cursor-pointer">
                            Simpan &amp; Update Status Dikirim
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Image Lightbox Modal -->
        <div
            x-show="lightboxOpen"
            x-cloak
            @keydown.escape.window="lightboxOpen = false"
            class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/80 backdrop-blur-xs"
        >
            <div
                @click.outside="lightboxOpen = false"
                class="relative max-w-xl w-full max-h-[90vh] bg-white rounded-3xl overflow-hidden shadow-2xl flex flex-col"
            >
                <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between bg-gray-50/80">
                    <span class="text-xs font-bold text-gray-800" x-text="lightboxTitle"></span>
                    <button
                        type="button"
                        @click="lightboxOpen = false"
                        class="w-7 h-7 rounded-full bg-white hover:bg-gray-200 text-gray-600 flex items-center justify-center cursor-pointer text-xs font-bold shadow-2xs"
                    >
                        ✕
                    </button>
                </div>
                <div class="p-3 bg-gray-950 flex items-center justify-center overflow-auto max-h-[75vh]">
                    <img :src="lightboxUrl" :alt="lightboxTitle" class="max-h-[70vh] w-auto max-w-full object-contain rounded-xl" />
                </div>
            </div>
        </div>
        <x-payout-proof-modal />
    </main>

    @push('scripts')
    <script>
        function registerSellerOrders() {
            Alpine.data('sellerOrdersManager', () => ({
                fulfillModalOpen: false,
                activeOrderId: null,
                activeOrderNumber: '',
                copiedKey: null,
                copiedOrderId: null,
                proofModalOpen: false,
                proofModalData: {
                    imageUrl: '',
                    orderNumber: '',
                    amount: '',
                    bank: '',
                    date: ''
                },
                openProofModal(imageUrl, orderNumber, amount, bank, date) {
                    this.proofModalData = { imageUrl, orderNumber, amount, bank, date };
                    this.proofModalOpen = true;
                },

                // Pre-shipment photo state
                preShipmentPreview: null,
                preShipmentFileName: '',
                preShipmentFileSize: '',
                preShipmentFileExt: '',
                isDraggingPreShipment: false,

                // Receipt photo state
                receiptPreview: null,
                receiptFileName: '',
                receiptFileSize: '',
                receiptFileExt: '',
                isDraggingReceipt: false,

                // Lightbox state
                lightboxOpen: false,
                lightboxUrl: '',
                lightboxTitle: '',

                openFulfillModal(orderId, orderNumber) {
                    this.activeOrderId = orderId;
                    this.activeOrderNumber = orderNumber;
                    this.clearPreShipment();
                    this.clearReceipt();
                    this.fulfillModalOpen = true;
                },

                closeFulfillModal() {
                    this.fulfillModalOpen = false;
                    this.clearPreShipment();
                    this.clearReceipt();
                },

                triggerPreShipmentInput() {
                    if (this.$refs.preShipmentInput) {
                        this.$refs.preShipmentInput.click();
                    }
                },

                handlePreShipmentChange(event) {
                    const file = event.target.files && event.target.files[0];
                    if (file) {
                        this.setPreShipmentFile(file);
                    }
                },

                handlePreShipmentDrop(event) {
                    this.isDraggingPreShipment = false;
                    const file = event.dataTransfer && event.dataTransfer.files && event.dataTransfer.files[0];
                    if (file) {
                        if (this.$refs.preShipmentInput) {
                            const dt = new DataTransfer();
                            dt.items.add(file);
                            this.$refs.preShipmentInput.files = dt.files;
                        }
                        this.setPreShipmentFile(file);
                    }
                },

                setPreShipmentFile(file) {
                    if (!file.type.startsWith('image/')) {
                        alert('File harus berupa gambar (JPG, PNG, atau WEBP).');
                        return;
                    }
                    if (file.size > 10 * 1024 * 1024) {
                        alert('Ukuran file foto maksimal 10MB.');
                        return;
                    }
                    if (this.preShipmentPreview) {
                        URL.revokeObjectURL(this.preShipmentPreview);
                    }
                    this.preShipmentPreview = URL.createObjectURL(file);
                    this.preShipmentFileName = file.name;
                    this.preShipmentFileSize = (file.size / 1024 > 1024)
                        ? (file.size / (1024 * 1024)).toFixed(2) + ' MB'
                        : (file.size / 1024).toFixed(0) + ' KB';
                    this.preShipmentFileExt = (file.name.split('.').pop() || 'IMG').toUpperCase();
                },

                clearPreShipment() {
                    if (this.preShipmentPreview) {
                        URL.revokeObjectURL(this.preShipmentPreview);
                        this.preShipmentPreview = null;
                    }
                    this.preShipmentFileName = '';
                    this.preShipmentFileSize = '';
                    this.preShipmentFileExt = '';
                    this.isDraggingPreShipment = false;
                    if (this.$refs.preShipmentInput) {
                        this.$refs.preShipmentInput.value = '';
                    }
                },

                triggerReceiptInput() {
                    if (this.$refs.receiptInput) {
                        this.$refs.receiptInput.click();
                    }
                },

                handleReceiptChange(event) {
                    const file = event.target.files && event.target.files[0];
                    if (file) {
                        this.setReceiptFile(file);
                    }
                },

                handleReceiptDrop(event) {
                    this.isDraggingReceipt = false;
                    const file = event.dataTransfer && event.dataTransfer.files && event.dataTransfer.files[0];
                    if (file) {
                        if (this.$refs.receiptInput) {
                            const dt = new DataTransfer();
                            dt.items.add(file);
                            this.$refs.receiptInput.files = dt.files;
                        }
                        this.setReceiptFile(file);
                    }
                },

                setReceiptFile(file) {
                    if (!file.type.startsWith('image/')) {
                        alert('File harus berupa gambar (JPG, PNG, atau WEBP).');
                        return;
                    }
                    if (file.size > 10 * 1024 * 1024) {
                        alert('Ukuran file foto maksimal 10MB.');
                        return;
                    }
                    if (this.receiptPreview) {
                        URL.revokeObjectURL(this.receiptPreview);
                    }
                    this.receiptPreview = URL.createObjectURL(file);
                    this.receiptFileName = file.name;
                    this.receiptFileSize = (file.size / 1024 > 1024)
                        ? (file.size / (1024 * 1024)).toFixed(2) + ' MB'
                        : (file.size / 1024).toFixed(0) + ' KB';
                    this.receiptFileExt = (file.name.split('.').pop() || 'IMG').toUpperCase();
                },

                clearReceipt() {
                    if (this.receiptPreview) {
                        URL.revokeObjectURL(this.receiptPreview);
                        this.receiptPreview = null;
                    }
                    this.receiptFileName = '';
                    this.receiptFileSize = '';
                    this.receiptFileExt = '';
                    this.isDraggingReceipt = false;
                    if (this.$refs.receiptInput) {
                        this.$refs.receiptInput.value = '';
                    }
                },

                openLightbox(url, title) {
                    if (!url) return;
                    this.lightboxUrl = url;
                    this.lightboxTitle = title || 'Pratinjau Foto';
                    this.lightboxOpen = true;
                },

                copyText(key, text) {
                    if (!text) return;
                    const self = this;
                    const applyFallback = () => {
                        const textarea = document.createElement('textarea');
                        textarea.value = text;
                        textarea.style.position = 'fixed';
                        textarea.style.opacity = '0';
                        document.body.appendChild(textarea);
                        textarea.select();
                        try {
                            document.execCommand('copy');
                        } catch (e) {
                            console.error(e);
                        }
                        document.body.removeChild(textarea);
                        self.copiedKey = key;
                        setTimeout(() => {
                            if (self.copiedKey === key) {
                                self.copiedKey = null;
                            }
                        }, 2000);
                    };

                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(text).then(() => {
                            self.copiedKey = key;
                            setTimeout(() => {
                                if (self.copiedKey === key) {
                                    self.copiedKey = null;
                                }
                            }, 2000);
                        }).catch(() => {
                            applyFallback();
                        });
                    } else {
                        applyFallback();
                    }
                },

                copyAddress(orderId, text) {
                    this.copiedOrderId = orderId;
                    this.copyText('addr_' + orderId, text);
                },

                copyPhone(orderId, phone) {
                    this.copyText('phone_' + orderId, phone);
                }
            }));
        }

        if (window.Alpine) {
            registerSellerOrders();
        } else {
            document.addEventListener('alpine:init', registerSellerOrders);
        }
    </script>
    @endpush
</x-layouts.app>
