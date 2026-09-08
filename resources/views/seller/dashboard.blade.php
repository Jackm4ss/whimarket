<x-layouts.app :title="$title" activeTab="seller-dashboard">
    <main class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10">
        <!-- Top Bar with Store Greeting & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl overflow-hidden bg-purple-100 ring-4 ring-purple-50 shrink-0">
                    <img
                        src="{{ $seller->user->avatar ?? '/assets/avatars/avatar-raisy.png' }}"
                        alt="{{ $seller->store_name }}"
                        class="w-full h-full object-cover"
                    />
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-950 tracking-tight">
                            {{ $seller->store_name }}
                        </h1>
                        <x-verified-badge size="md" class="w-5 h-5 text-[#4F26A6]" />
                    </div>
                    <p class="text-xs sm:text-sm text-gray-500 font-medium">
                        Seller Dashboard • Toko Aktif ({{ '@' . $seller->username }})
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap">
                <a
                    href="{{ route('seller.profile', '@' . $seller->username) }}"
                    target="_blank"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 hover:text-[#4F26A6] hover:bg-gray-50 font-bold text-xs sm:text-sm transition-all flex items-center gap-2"
                >
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span>Lihat Toko Publik</span>
                </a>
                <a
                    href="{{ route('seller.products.create') }}"
                    class="px-5 py-2.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs sm:text-sm shadow-md shadow-[#4F26A6]/20 transition-all flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Produk</span>
                </a>
            </div>
        </div>

        <!-- Metric Overview Cards (Grid 2 to 4 cols) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-10">
            <!-- Metric 1: Pending Orders -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-[13px] font-bold text-gray-500">Perlu Diproses</span>
                    <span class="w-8 h-8 rounded-xl bg-purple-50 text-[#4F26A6] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </span>
                </div>
                <div>
                    <span class="text-2xl sm:text-3xl font-black text-gray-900 leading-none">{{ $metrics['pending_orders'] }}</span>
                    <span class="text-xs text-gray-400 font-medium block mt-1">Pesanan baru masuk</span>
                </div>
            </div>

            <!-- Metric 2: Shipped Orders -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-[13px] font-bold text-gray-500">Sedang Dikirim</span>
                    <span class="w-8 h-8 rounded-xl bg-amber-50 text-[#F59E0B] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    </span>
                </div>
                <div>
                    <span class="text-2xl sm:text-3xl font-black text-gray-900 leading-none">{{ $metrics['shipped_orders'] }}</span>
                    <span class="text-xs text-gray-400 font-medium block mt-1">Dalam perjalanan kurir</span>
                </div>
            </div>

            <!-- Metric 3: Active Products -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-[13px] font-bold text-gray-500">Produk Aktif</span>
                    <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </span>
                </div>
                <div>
                    <span class="text-2xl sm:text-3xl font-black text-gray-900 leading-none">{{ $metrics['active_products'] }}</span>
                    <span class="text-xs text-gray-400 font-medium block mt-1">Tayang di katalog</span>
                </div>
            </div>

            <!-- Metric 4: Pending Payout -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-[13px] font-bold text-gray-500">Saldo Siap Cair</span>
                    <span class="w-8 h-8 rounded-xl bg-purple-50 text-[#4F26A6] flex items-center justify-center font-bold text-xs">Rp</span>
                </div>
                <div>
                    <span class="text-xl sm:text-2xl font-black text-[#4F26A6] leading-none">
                        Rp {{ number_format((float)$metrics['pending_payout'], 0, ',', '.') }}
                    </span>
                    <span class="text-xs text-gray-400 font-medium block mt-1">Menunggu transfer admin</span>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs for Seller Sections -->
        <div class="flex items-center gap-2 mb-6 border-b border-gray-200 overflow-x-auto no-scrollbar">
            <a href="{{ route('seller.dashboard') }}" class="pb-3 text-sm font-bold text-[#4F26A6] border-b-2 border-[#4F26A6] px-3 whitespace-nowrap">
                Ringkasan Toko
            </a>
            <a href="{{ route('seller.orders.index') }}" class="pb-3 text-sm font-semibold text-gray-500 hover:text-gray-900 px-3 whitespace-nowrap">
                Kelola Pesanan ({{ $metrics['pending_orders'] + $metrics['shipped_orders'] }})
            </a>
            <a href="{{ route('seller.products.index') }}" class="pb-3 text-sm font-semibold text-gray-500 hover:text-gray-900 px-3 whitespace-nowrap">
                Katalog Produk ({{ $metrics['active_products'] }})
            </a>
        </div>

        <!-- Recent Orders Section -->
        <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg sm:text-xl font-extrabold text-gray-900 tracking-tight">Pesanan Masuk Terbaru</h3>
                    <p class="text-xs sm:text-sm text-gray-500">Segera packing dan masukkan nomor resi sebelum batas waktu.</p>
                </div>
                <a href="{{ route('seller.orders.index') }}" class="text-xs sm:text-sm font-bold text-[#4F26A6] hover:underline">
                    Lihat Semua &rarr;
                </a>
            </div>

            @if($recentOrders->isEmpty())
                <div class="py-12 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-sm font-semibold">Belum ada pesanan masuk.</p>
                </div>
            @else
                <!-- Responsive Desktop Table / Mobile Card List -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-xs uppercase tracking-wider text-gray-400 border-b border-gray-100 pb-3">
                            <tr>
                                <th class="pb-3 font-bold">No. Pesanan</th>
                                <th class="pb-3 font-bold">Pembeli</th>
                                <th class="pb-3 font-bold">Total Nilai</th>
                                <th class="pb-3 font-bold">Status</th>
                                <th class="pb-3 font-bold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($recentOrders as $order)
                                <tr class="hover:bg-gray-50/70 transition-colors">
                                    <td class="py-4 font-mono font-bold text-gray-900">
                                        #{{ $order->order_number }}
                                        <span class="text-[11px] text-gray-400 font-sans block mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                    </td>
                                    <td class="py-4">
                                        <span class="font-semibold text-gray-800">{{ $order->buyer->name }}</span>
                                        <span class="text-xs text-gray-400 block">{{ $order->buyer->phone ?? '-' }}</span>
                                    </td>
                                    <td class="py-4 font-extrabold text-[#4F26A6]">
                                        Rp {{ number_format((float)$order->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            {{ $order->status::$name === 'paid' || $order->status::$name === 'processing' ? 'bg-purple-50 text-[#4F26A6] border border-purple-200/60' : '' }}
                                            {{ $order->status::$name === 'shipped' ? 'bg-amber-50 text-amber-800 border border-amber-200/60' : '' }}
                                            {{ $order->status::$name === 'delivered' || $order->status::$name === 'completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : '' }}
                                            {{ $order->status::$name === 'disputed' ? 'bg-rose-50 text-rose-700 border border-rose-200/60' : '' }}
                                        ">
                                            {{ $order->status->label() }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-right">
                                        <a href="{{ route('seller.orders.index') }}" class="px-3.5 py-1.5 rounded-xl bg-gray-100 hover:bg-[#4F26A6] hover:text-white text-gray-700 font-bold text-xs transition-all">
                                            Kelola
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </main>
</x-layouts.app>
