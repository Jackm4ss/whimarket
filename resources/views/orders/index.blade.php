<x-layouts.app :title="$title" activeTab="pesanan">
    <main class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
            <div>
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#F3EEFF] text-[#4F26A6] text-xs font-bold mb-2.5">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span>Riwayat Transaksi</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-950 tracking-tight">
                    Pesanan Saya
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Pantau status pengiriman, resi kurir, dan masa pemeriksaan pesananmu.
                </p>
            </div>

            <a
                href="/belanja"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border-2 border-[#4F26A6] text-[#4F26A6] hover:bg-[#4F26A6]/5 font-bold text-xs sm:text-sm transition-all shrink-0"
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
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($orders->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-8 sm:p-14 text-center max-w-xl mx-auto my-8">
                <div class="w-24 h-24 sm:w-28 sm:h-28 mx-auto mb-6 rounded-full bg-[#F3EEFF] flex items-center justify-center">
                    <svg class="w-12 h-12 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h3 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight mb-2">
                    Belum Ada Pesanan
                </h3>
                <p class="text-sm text-gray-500 max-w-md mx-auto mb-8 leading-relaxed">
                    Kamu belum melakukan transaksi pembelian. Temukan barang impianmu dari kreator favorit di WhiMarket.
                </p>
                <a
                    href="/belanja"
                    class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-sm shadow-md shadow-[#4F26A6]/20 transition-all active:scale-[0.98]"
                >
                    <span>Mulai Belanja Sekarang</span>
                </a>
            </div>
        @else
            <!-- Orders List -->
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] overflow-hidden">
                        <!-- Top Order Bar -->
                        <div class="px-5 sm:px-6 py-4 bg-[#FAF9FC] border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex items-center gap-3">
                                <span class="font-mono font-bold text-gray-950 text-sm sm:text-base">#{{ $order->order_number }}</span>
                                <span class="text-xs text-gray-400 font-medium">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                <span class="text-gray-300 hidden sm:inline">•</span>
                                <span class="text-xs font-bold text-gray-700 hidden sm:inline">Toko: {{ $order->seller->store_name }}</span>
                            </div>

                            <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold
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

                        <!-- Order Content -->
                        <div class="p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                            <!-- Items Thumbnails & Info -->
                            <div class="space-y-3 flex-1 min-w-0">
                                @foreach($order->items as $item)
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-16 h-16 rounded-2xl bg-gray-100 overflow-hidden shrink-0 border border-gray-100">
                                            <img
                                                src="{{ $item->variant?->product?->primary_image_url ?? '/assets/products/prod-hoodie.png' }}"
                                                alt="{{ $item->product_name_snapshot }}"
                                                class="w-full h-full object-cover"
                                            />
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="text-sm sm:text-[15px] font-bold text-gray-900 truncate">
                                                {{ $item->product_name_snapshot }}
                                            </h4>
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                Varian: {{ $item->variant_name_snapshot }} • {{ $item->quantity }} pcs
                                            </p>
                                            <p class="text-xs font-bold text-[#4F26A6] mt-0.5">
                                                Rp {{ number_format((float)$item->subtotal, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Right: Total & Action -->
                            <div class="flex sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto pt-3 sm:pt-0 border-t sm:border-t-0 border-gray-100 gap-4 shrink-0">
                                <div class="text-left sm:text-right">
                                    <span class="text-xs text-gray-400 block font-medium">Total Pesanan:</span>
                                    <span class="text-lg sm:text-xl font-black text-[#4F26A6]">
                                        Rp {{ number_format((float)$order->grand_total, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    @if($order->status::$name === 'pending_payment')
                                        <a
                                            href="{{ route('payment.show', $order->order_number) }}"
                                            class="px-4 py-2 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs shadow-sm transition-all"
                                        >
                                            Bayar Sekarang
                                        </a>
                                    @endif
                                    <a
                                        href="{{ route('orders.show', $order->order_number) }}"
                                        class="px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:text-[#4F26A6] hover:bg-gray-50 font-bold text-xs transition-all"
                                    >
                                        Lihat Detail &rarr;
                                    </a>
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
    </main>
</x-layouts.app>
