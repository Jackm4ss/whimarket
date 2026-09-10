<x-layouts.app :title="$title" activeTab="pesanan">
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-6 border-b border-gray-100">
            <div>
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#F3EEFF] text-[#4F26A6] text-xs font-bold mb-2.5">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span>Riwayat Transaksi</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-950 tracking-tight">
                    Pesanan Saya
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    Pantau status pengiriman, nomor resi kurir, dan riwayat pesanan belanjamu.
                </p>
            </div>

            <a
                href="/belanja"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border-2 border-[#4F26A6] text-[#4F26A6] hover:bg-[#4F26A6]/5 font-bold text-xs sm:text-sm transition-all shrink-0 active:scale-[0.98]"
            >
                <span>Belanja Lagi</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filter & Search Container Card -->
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] p-4 sm:p-5 mb-6 space-y-4">
            <!-- Search Bar -->
            <form method="GET" action="{{ route('orders.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                <input type="hidden" name="status" value="{{ $currentTab }}">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari berdasarkan nama produk, toko penjual, atau no. pesanan (#WHI-...)"
                        class="w-full pl-10 pr-10 py-2.5 text-xs sm:text-sm rounded-xl border border-gray-200 bg-[#FAF9FC] focus:bg-white focus:outline-none focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/15 transition-all text-gray-900 placeholder:text-gray-400"
                    />
                    @if($search)
                        <a
                            href="{{ route('orders.index', ['status' => $currentTab]) }}"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600"
                            title="Hapus pencarian"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    @endif
                </div>
                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs sm:text-sm shadow-sm transition-all shrink-0 active:scale-[0.98]"
                >
                    Cari
                </button>
            </form>

            <!-- Status Tabs (Tokopedia / Shopee style) -->
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pt-3 border-t border-gray-100 pb-1">
                @php
                    $tabs = [
                        'all' => ['label' => 'Semua Pesanan', 'count' => $counts['all'] ?? 0],
                        'unpaid' => ['label' => 'Menunggu Pembayaran', 'count' => $counts['unpaid'] ?? 0],
                        'processing' => ['label' => 'Diproses', 'count' => $counts['processing'] ?? 0],
                        'shipped' => ['label' => 'Sedang Dikirim', 'count' => $counts['shipped'] ?? 0],
                        'delivered' => ['label' => 'Tiba di Tujuan', 'count' => $counts['delivered'] ?? 0],
                        'completed' => ['label' => 'Selesai', 'count' => $counts['completed'] ?? 0],
                        'cancelled' => ['label' => 'Dibatalkan', 'count' => $counts['cancelled'] ?? 0],
                    ];
                @endphp

                @foreach($tabs as $key => $tab)
                    @php
                        $isActive = $currentTab === $key;
                    @endphp
                    <a
                        href="{{ route('orders.index', array_filter(['status' => $key, 'search' => $search])) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-all select-none
                            {{ $isActive
                                ? 'bg-[#4F26A6] text-white shadow-sm shadow-[#4F26A6]/25'
                                : 'bg-[#FAF9FC] text-gray-600 hover:text-gray-950 hover:bg-gray-100 border border-gray-200/60' }}"
                    >
                        <span>{{ $tab['label'] }}</span>
                        @if($tab['count'] > 0)
                            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-[10px] font-extrabold leading-none
                                {{ $isActive ? 'bg-white/25 text-white' : 'bg-gray-200/80 text-gray-700' }}">
                                {{ $tab['count'] }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        @if($orders->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_8px_30px_rgba(0,0,0,0.03)] p-8 sm:p-14 text-center max-w-xl mx-auto my-8">
                <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto mb-5 rounded-full bg-[#F3EEFF] flex items-center justify-center">
                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                @if($search)
                    <h3 class="text-lg sm:text-xl font-extrabold text-gray-900 tracking-tight mb-2">
                        Pesanan Tidak Ditemukan
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-500 max-w-md mx-auto mb-6 leading-relaxed">
                        Tidak ada pesanan yang sesuai dengan kata kunci <span class="font-bold text-gray-800">"{{ $search }}"</span>.
                    </p>
                    <a
                        href="{{ route('orders.index', ['status' => $currentTab]) }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:text-[#4F26A6] hover:border-[#4F26A6] font-bold text-xs transition-all"
                    >
                        Hapus Filter Pencarian
                    </a>
                @elseif($currentTab !== 'all')
                    <h3 class="text-lg sm:text-xl font-extrabold text-gray-900 tracking-tight mb-2">
                        Tidak Ada Pesanan
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-500 max-w-md mx-auto mb-6 leading-relaxed">
                        Saat ini kamu tidak memiliki pesanan dengan status
                        <span class="block mt-1.5 font-bold text-gray-900 text-sm sm:text-base">
                            "{{ $tabs[$currentTab]['label'] ?? $currentTab }}"
                        </span>
                    </p>
                    <a
                        href="{{ route('orders.index') }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:text-[#4F26A6] hover:border-[#4F26A6] font-bold text-xs transition-all"
                    >
                        Lihat Semua Pesanan
                    </a>
                @else
                    <h3 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight mb-2">
                        Belum Ada Pesanan
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-500 max-w-md mx-auto mb-8 leading-relaxed">
                        Kamu belum melakukan transaksi pembelian. Temukan barang impian dan merchandise favoritmu di WhiMarket!
                    </p>
                    <a
                        href="/belanja"
                        class="inline-flex items-center justify-center gap-2.5 px-6 py-3 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs sm:text-sm shadow-md shadow-[#4F26A6]/20 transition-all active:scale-[0.98]"
                    >
                        <span>Mulai Belanja Sekarang</span>
                    </a>
                @endif
            </div>
        @else
            <!-- Orders List -->
            <div class="space-y-5">
                @foreach($orders as $order)
                    @php
                        $isPendingPayment = $order->status::$name === 'pending_payment';
                        $hasOutOfStockItem = $isPendingPayment && $order->items->contains(function ($item) {
                            return ($item->variant?->stock ?? 0) <= 0 || ($item->variant?->product?->total_stock ?? 0) <= 0;
                        });
                    @endphp

                    <!-- Single Order Card (Tokopedia / Shopee Standard) -->
                    <div class="bg-white rounded-2xl sm:rounded-3xl border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.03)] hover:shadow-[0_6px_28px_rgba(0,0,0,0.05)] transition-all duration-200 overflow-hidden">
                        <!-- Top Order Bar (Store info, date, invoice, status badge) -->
                        <div class="px-5 sm:px-6 py-3.5 bg-[#FAF9FC] border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap">
                                <!-- Seller Profile Photo + Store name -->
                                <div class="flex items-center gap-2">
                                    @php
                                        $sellerAvatar = $order->seller?->avatar_url ?? 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 128 128"><rect width="128" height="128" rx="64" fill="%23F3EEFF"/><text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle" font-family="sans-serif" font-weight="900" font-size="52" fill="%234F26A6">W</text></svg>';
                                    @endphp
                                    @if($order->seller)
                                        <a
                                            href="{{ url('/seller/@'.$order->seller->username) }}"
                                            class="inline-flex items-center gap-2 group"
                                            title="Kunjungi Toko {{ $order->seller->store_name }}"
                                        >
                                            <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full overflow-hidden bg-[#F3EEFF] border border-gray-200/80 ring-1 ring-[#4F26A6]/10 shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                                                <img
                                                    src="{{ $sellerAvatar }}"
                                                    alt="{{ $order->seller->store_name }}"
                                                    loading="lazy"
                                                    class="w-full h-full object-cover"
                                                />
                                            </div>
                                            <span class="font-bold text-gray-900 text-xs sm:text-sm group-hover:text-[#4F26A6] transition-colors inline-flex items-center gap-1">
                                                <span>{{ $order->seller->store_name }}</span>
                                                <svg class="w-3 h-3 text-gray-400 group-hover:text-[#4F26A6] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </span>
                                        </a>
                                    @else
                                        <div class="inline-flex items-center gap-2">
                                            <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full overflow-hidden bg-[#F3EEFF] border border-gray-200 shrink-0">
                                                <img
                                                    src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='128' height='128' viewBox='0 0 128 128'><rect width='128' height='128' rx='64' fill='%23F3EEFF'/><text x='50%' y='54%' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-weight='900' font-size='52' fill='%234F26A6'>W</text></svg>"
                                                    alt="WhiMarket Seller"
                                                    class="w-full h-full object-cover"
                                                />
                                            </div>
                                            <span class="font-bold text-gray-900 text-xs sm:text-sm">WhiMarket Seller</span>
                                        </div>
                                    @endif
                                </div>

                                <span class="text-gray-300 hidden sm:inline">•</span>

                                <!-- Date -->
                                <span class="text-xs text-gray-500 font-medium">
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </span>

                                <span class="text-gray-300 hidden sm:inline">•</span>

                                <!-- Order Invoice Code -->
                                <span class="font-mono font-semibold text-gray-600 bg-white px-2 py-0.5 rounded-md border border-gray-200/80 text-[11px] sm:text-xs">
                                    #{{ $order->order_number }}
                                </span>
                            </div>

                            <!-- Status Badge -->
                            <div>
                                @if($hasOutOfStockItem)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200/80">
                                        <svg class="w-3.5 h-3.5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        <span>Stok Habis dari Penjual</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold
                                        {{ $order->status::$name === 'pending_payment' ? 'bg-amber-50 text-amber-800 border border-amber-200/70' : '' }}
                                        {{ $order->status::$name === 'payment_verification' ? 'bg-[#F3EEFF] text-[#4F26A6] border border-purple-200/70' : '' }}
                                        {{ $order->status::$name === 'paid' || $order->status::$name === 'processing' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200/70' : '' }}
                                        {{ $order->status::$name === 'shipped' ? 'bg-sky-50 text-sky-800 border border-sky-200/70' : '' }}
                                        {{ $order->status::$name === 'delivered' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200/70' : '' }}
                                        {{ $order->status::$name === 'completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/70' : '' }}
                                        {{ $order->status::$name === 'disputed' ? 'bg-rose-50 text-rose-700 border border-rose-200/70' : '' }}
                                        {{ $order->status::$name === 'cancelled' ? 'bg-gray-100 text-gray-700 border border-gray-200' : '' }}
                                    ">
                                        @if($order->status::$name === 'pending_payment')
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @elseif($order->status::$name === 'payment_verification')
                                            <svg class="w-3.5 h-3.5 text-[#4F26A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @elseif($order->status::$name === 'shipped')
                                            <svg class="w-3.5 h-3.5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                        @elseif($order->status::$name === 'delivered' || $order->status::$name === 'completed')
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                        <span>{{ $order->status->label() }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Body (Grid: Products on Left, Summary on Right) -->
                        <div class="p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                            <!-- Left: Items Section (8 cols) -->
                            <div class="lg:col-span-8 space-y-3.5">
                                @foreach($order->items as $item)
                                    <div class="flex items-start sm:items-center gap-3.5 sm:gap-4 {{ !$loop->first ? 'pt-3.5 border-t border-gray-100' : '' }}">
                                        <!-- Product Thumbnail -->
                                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-[#F3EEFF] overflow-hidden shrink-0 border border-gray-100">
                                            <img
                                                src="{{ $item->variant?->product?->primary_image_url ?? '/assets/products/prod-hoodie.png' }}"
                                                alt="{{ $item->product_name_snapshot }}"
                                                loading="lazy"
                                                class="w-full h-full object-cover"
                                            />
                                        </div>

                                        <!-- Product Info -->
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm sm:text-[15px] font-bold text-gray-900 leading-snug line-clamp-2">
                                                {{ $item->product_name_snapshot }}
                                            </h4>
                                            <div class="flex items-center gap-2 mt-1 text-xs text-gray-500 flex-wrap">
                                                <span>Varian: <strong class="text-gray-700 font-semibold">{{ $item->variant_name_snapshot }}</strong></span>
                                                <span class="text-gray-300">•</span>
                                                <span>{{ $item->quantity }} barang</span>
                                                <span class="text-gray-300">•</span>
                                                <span class="font-semibold text-gray-800">
                                                    Rp {{ number_format((float)($item->price_snapshot ?? $item->subtotal), 0, ',', '.') }}
                                                </span>
                                            </div>

                                            @if(($item->variant?->stock ?? 0) <= 0 || ($item->variant?->product?->total_stock ?? 0) <= 0)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-rose-50 text-rose-600 font-bold text-[10px] mt-1.5 border border-rose-200/60">
                                                    Stok habis dari penjual
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Right: Total Summary (4 cols) -->
                            <div class="lg:col-span-4 border-t lg:border-t-0 lg:border-l border-gray-100 pt-4 lg:pt-0 lg:pl-6 flex flex-row lg:flex-col justify-between items-center lg:items-end gap-2 shrink-0">
                                <div class="text-left lg:text-right">
                                    <span class="text-xs text-gray-400 font-medium block">Total Belanja</span>
                                    <span class="text-xl sm:text-2xl font-black text-[#4F26A6] block mt-0.5 tracking-tight">
                                        Rp {{ number_format((float)$order->grand_total, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[11px] text-gray-400 block mt-0.5">
                                        Ongkir: Rp {{ number_format((float)$order->shipping_cost, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer: Status Prompt & Contextual Actions -->
                        <div class="px-5 sm:px-6 py-3.5 bg-[#FAF9FC] border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <!-- Left: Helpful Status Note / Courier Tracking -->
                            <div class="flex items-center gap-2 text-xs">
                                @if($isPendingPayment)
                                    <div class="flex items-center gap-2 text-amber-700 font-medium">
                                        <svg class="w-4 h-4 shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Segera selesaikan pembayaran sebelum pesanan batal otomatis.</span>
                                    </div>
                                @elseif($order->status::$name === 'payment_verification')
                                    <div class="flex items-center gap-2 text-[#4F26A6] font-medium">
                                        <svg class="w-4 h-4 shrink-0 text-[#4F26A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Bukti transfer sedang diverifikasi oleh Admin WhiMarket.</span>
                                    </div>
                                @elseif($order->status::$name === 'paid' || $order->status::$name === 'processing')
                                    <div class="flex items-center gap-2 text-gray-600 font-medium">
                                        <svg class="w-4 h-4 shrink-0 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        <span>Penjual sedang menyiapkan pesanan untuk diserahkan ke kurir.</span>
                                    </div>
                                @elseif($order->status::$name === 'shipped')
                                    <div class="flex items-center gap-2 text-gray-700 font-medium flex-wrap">
                                        <svg class="w-4 h-4 shrink-0 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                        </svg>
                                        <span>
                                            Dikirim via <strong class="text-gray-900">{{ $order->shipment?->courier_name ?? 'Pengiriman Reguler' }}</strong>
                                            @if($order->shipment?->tracking_number)
                                                • Resi: <code class="font-mono font-bold text-gray-950 bg-white px-1.5 py-0.5 rounded border border-gray-200">{{ $order->shipment->tracking_number }}</code>
                                            @endif
                                        </span>
                                    </div>
                                @elseif($order->status::$name === 'delivered')
                                    <div class="flex items-center gap-2 text-emerald-700 font-medium">
                                        <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span>Paket telah tiba. Periksa barang sebelum konfirmasi pesanan selesai.</span>
                                    </div>
                                @elseif($order->status::$name === 'completed')
                                    <div class="flex items-center gap-2 text-gray-500 font-medium">
                                        <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Transaksi selesai. Terima kasih telah berbelanja di WhiMarket!</span>
                                    </div>
                                @elseif($order->status::$name === 'disputed')
                                    <div class="flex items-center gap-2 text-rose-700 font-medium">
                                        <svg class="w-4 h-4 shrink-0 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        <span>Pesanan sedang dalam mediasi sengketa dengan tim WhiMarket.</span>
                                    </div>
                                @elseif($order->status::$name === 'cancelled')
                                    <div class="flex items-center gap-2 text-gray-500 font-medium">
                                        <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        <span>Pesanan ini telah dibatalkan.</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Right: Action Buttons -->
                            <div class="flex items-center gap-2 sm:gap-2.5 shrink-0 justify-end flex-wrap">
                                <!-- Secondary Action: View Details -->
                                <a
                                    href="{{ route('orders.show', $order->order_number) }}"
                                    class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-gray-200 bg-white text-gray-700 hover:text-[#4F26A6] hover:border-[#4F26A6] font-bold text-xs transition-all shadow-xs"
                                >
                                    <span>Lihat Rincian</span>
                                </a>

                                <!-- Primary Contextual Actions -->
                                @if($isPendingPayment)
                                    @if($hasOutOfStockItem)
                                        <span class="px-4 py-2 rounded-xl bg-gray-100 text-gray-400 font-bold text-xs border border-gray-200 cursor-not-allowed select-none" title="Stok barang telah habis">
                                            Stok Habis
                                        </span>
                                    @else
                                        <a
                                            href="{{ route('payment.show', $order->order_number) }}"
                                            class="inline-flex items-center justify-center gap-1.5 px-5 py-2 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs shadow-sm shadow-[#4F26A6]/20 transition-all active:scale-[0.98]"
                                        >
                                            <span>Bayar Sekarang</span>
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                            </svg>
                                        </a>
                                    @endif
                                @elseif($order->status::$name === 'shipped')
                                    <form method="POST" action="{{ route('orders.confirm_delivered', $order->order_number) }}" onsubmit="return confirm('Konfirmasi bahwa pesanan ini telah kamu terima?')">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-sm transition-all active:scale-[0.98]"
                                        >
                                            Konfirmasi Terima
                                        </button>
                                    </form>
                                @elseif($order->status::$name === 'delivered')
                                    <a
                                        href="{{ route('orders.show', $order->order_number) }}"
                                        class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition-all active:scale-[0.98]"
                                    >
                                        Konfirmasi Selesai
                                    </a>
                                @elseif($order->status::$name === 'completed')
                                    @if($order->items->first()?->variant?->product)
                                        <a
                                            href="{{ route('product.detail', $order->items->first()->variant->product->slug) }}"
                                            class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-[#F3EEFF] text-[#4F26A6] hover:bg-[#4F26A6] hover:text-white font-bold text-xs transition-all"
                                        >
                                            Beli Lagi
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="pt-4 flex justify-center">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        @endif
    </main>
</x-layouts.app>
