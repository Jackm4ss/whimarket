<x-layouts.app :title="$title" activeTab="seller-wishlists">
    <main class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10">
        <!-- Breadcrumb & Title Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-gray-500 mb-2">
                    <a href="{{ route('seller.dashboard') }}" class="hover:text-[#4F26A6] transition-colors">Seller Portal</a>
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-gray-900 font-bold">Peminat Wishlist Produk</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-950 tracking-tight flex items-center gap-3">
                    <span>Peminat Wishlist Produk</span>
                    <span class="px-3 py-1 rounded-full text-xs font-black bg-[#F3EEFF] text-[#4F26A6] border border-[#4F26A6]/20">
                        {{ number_format($totalWishlists, 0, ',', '.') }} Wishlist
                    </span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    Pantau produk tokomu yang disimpan ke dalam wishlist oleh para pembeli. Manfaatkan wawasan ini untuk mengelola ketersediaan stok produk favorit.
                </p>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <a
                    href="{{ route('seller.followers.index') }}"
                    class="h-10 sm:h-11 px-4 rounded-xl bg-[#F3EEFF] hover:bg-[#EADDFE] text-[#4F26A6] font-bold text-xs sm:text-sm transition-all flex items-center gap-2 cursor-pointer shadow-2xs border border-[#4F26A6]/20"
                >
                    <svg class="w-4 h-4 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>&larr; Lihat Pengikut Toko</span>
                </a>
                <a
                    href="{{ route('seller.products.index') }}"
                    class="h-10 sm:h-11 px-4 rounded-xl border border-gray-200 text-gray-700 hover:text-[#4F26A6] hover:bg-gray-50 font-bold text-xs sm:text-sm transition-all flex items-center gap-2 cursor-pointer shadow-2xs"
                >
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span>Katalog Produk</span>
                </a>
            </div>
        </div>

        <!-- Navigation Tabs for Seller Portal -->
        <div class="flex items-center gap-2 mb-8 border-b border-gray-200 overflow-x-auto no-scrollbar">
            <a href="{{ route('seller.dashboard') }}" class="pb-3 text-sm font-semibold text-gray-500 hover:text-gray-900 px-3 whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></svg>
                <span>Ringkasan Toko</span>
            </a>
            <a href="{{ route('seller.orders.index') }}" class="pb-3 text-sm font-semibold text-gray-500 hover:text-gray-900 px-3 whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Kelola Pesanan</span>
            </a>
            <a href="{{ route('seller.products.index') }}" class="pb-3 text-sm font-semibold text-gray-500 hover:text-gray-900 px-3 whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span>Katalog Produk</span>
            </a>
            <a href="{{ route('seller.followers.index') }}" class="pb-3 text-sm font-semibold text-gray-500 hover:text-gray-900 px-3 whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Pengikut Toko</span>
            </a>
            <a href="{{ route('seller.wishlists.index') }}" class="pb-3 text-sm font-bold text-[#4F26A6] border-b-2 border-[#4F26A6] px-3 whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span>Peminat Wishlist</span>
            </a>
            <a href="{{ route('seller.settings') }}" class="pb-3 text-sm font-semibold text-gray-500 hover:text-gray-900 px-3 whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Pengaturan Toko</span>
            </a>
        </div>

        <!-- 3 Insight Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-8">
            <!-- Card 1: Total Wishlists -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-[13px] font-bold text-gray-500">Total Wishlist Barang</span>
                    <span class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </span>
                </div>
                <div>
                    <span class="text-2xl sm:text-3xl font-black text-gray-950 leading-none">
                        {{ number_format($totalWishlists, 0, ',', '.') }}
                    </span>
                    <span class="text-xs text-gray-400 font-medium block mt-1">Total produk disimpan pembeli</span>
                </div>
            </div>

            <!-- Card 2: Unique Wishlisters -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-[13px] font-bold text-gray-500">Pembeli Peminat Unik</span>
                    <span class="w-9 h-9 rounded-xl bg-[#F3EEFF] text-[#4F26A6] flex items-center justify-center shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </span>
                </div>
                <div>
                    <span class="text-2xl sm:text-3xl font-black text-[#4F26A6] leading-none">
                        {{ number_format($uniqueWishlisters, 0, ',', '.') }}
                    </span>
                    <span class="text-xs text-gray-400 font-medium block mt-1">Akun pembeli yang berminat</span>
                </div>
            </div>

            <!-- Card 3: Products with Wishlists -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-[13px] font-bold text-gray-500">Produk Diminati</span>
                    <span class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </span>
                </div>
                <div>
                    <span class="text-2xl sm:text-3xl font-black text-emerald-700 leading-none">
                        {{ number_format($wishlistedProductsCount, 0, ',', '.') }}
                    </span>
                    <span class="text-xs text-gray-400 font-medium block mt-1">Dari total katalog tokomu</span>
                </div>
            </div>
        </div>

        <!-- View Tabs & Search Filter Toolbar -->
        <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-5 sm:p-6 mb-6 space-y-4">
            <!-- View Selector Pill Switch -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 pb-4 border-b border-gray-100">
                <div class="inline-flex p-1 bg-gray-100 rounded-2xl shrink-0 self-start">
                    <a
                        href="{{ route('seller.wishlists.index', array_merge(request()->except('page'), ['tab' => 'activity'])) }}"
                        class="px-4 py-2 rounded-xl text-xs sm:text-[13px] font-bold transition-all flex items-center gap-2 {{ $currentTab === 'activity' ? 'bg-white text-[#4F26A6] shadow-xs' : 'text-gray-600 hover:text-gray-900' }}"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Aktivitas Wishlist Terbaru</span>
                    </a>
                    <a
                        href="{{ route('seller.wishlists.index', array_merge(request()->except('page'), ['tab' => 'products'])) }}"
                        class="px-4 py-2 rounded-xl text-xs sm:text-[13px] font-bold transition-all flex items-center gap-2 {{ $currentTab === 'products' ? 'bg-white text-[#4F26A6] shadow-xs' : 'text-gray-600 hover:text-gray-900' }}"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        <span>Peringkat Produk Diminati</span>
                    </a>
                </div>

                <!-- Product Quick Dropdown Filter -->
                @if($filterProducts->isNotEmpty() && $currentTab === 'activity')
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500 font-semibold hidden sm:inline">Filter Produk:</span>
                        <form action="{{ route('seller.wishlists.index') }}" method="GET" class="shrink-0">
                            <input type="hidden" name="tab" value="activity">
                            @if($search !== '')
                                <input type="hidden" name="q" value="{{ $search }}">
                            @endif
                            <select
                                name="product_id"
                                onchange="this.form.submit()"
                                class="h-10 px-3 pr-8 rounded-xl bg-[#F9FAFB] border border-gray-200 text-xs text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 font-semibold cursor-pointer"
                            >
                                <option value="">Semua Produk ({{ $filterProducts->count() }})</option>
                                @foreach($filterProducts as $fp)
                                    <option value="{{ $fp->id }}" {{ (string)$productId === (string)$fp->id ? 'selected' : '' }}>
                                        {{ \Illuminate\Support\Str::limit($fp->name, 35) }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Search Form -->
            <form action="{{ route('seller.wishlists.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                <input type="hidden" name="tab" value="{{ $currentTab }}">
                @if(!empty($productId))
                    <input type="hidden" name="product_id" value="{{ $productId }}">
                @endif
                <div class="relative flex-1">
                    <input
                        type="text"
                        name="q"
                        value="{{ $search }}"
                        placeholder="{{ $currentTab === 'products' ? 'Cari berdasarkan nama produk...' : 'Cari nama produk atau nama pembeli peminat...' }}"
                        class="w-full h-11 pl-10 pr-10 rounded-xl bg-[#F9FAFB] border border-gray-200 text-xs sm:text-[13.5px] text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] transition-all"
                    />
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    @if($search !== '')
                        <a href="{{ route('seller.wishlists.index', ['tab' => $currentTab, 'product_id' => $productId]) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 p-1" title="Reset pencarian">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <button type="submit" class="h-11 px-5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs sm:text-sm shadow-xs transition-all cursor-pointer">
                        Cari
                    </button>
                    @if($search !== '' || !empty($productId))
                        <a href="{{ route('seller.wishlists.index', ['tab' => $currentTab]) }}" class="h-11 px-4 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold text-xs sm:text-sm flex items-center transition-all">
                            Reset Filter
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tab 1: Activity Stream View -->
        @if($currentTab === 'activity')
            <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] overflow-hidden">
                @if($activities->isEmpty())
                    <div class="py-16 px-4 text-center flex flex-col items-center justify-center">
                        <div class="w-16 h-16 rounded-3xl bg-rose-50 text-rose-600 flex items-center justify-center mb-4 shadow-2xs">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        @if($search !== '' || !empty($productId))
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1">Tidak Ada Aktivitas Wishlist Ditemukan</h3>
                            <p class="text-xs sm:text-sm text-gray-500 max-w-md leading-relaxed mb-6">
                                Tidak ada aktivitas yang cocok dengan kriteria filter pencarianmu.
                            </p>
                            <a href="{{ route('seller.wishlists.index') }}" class="px-5 py-2.5 rounded-xl bg-[#4F26A6] text-white font-bold text-xs sm:text-sm hover:bg-[#3E1D85] transition-all">
                                Lihat Semua Aktivitas
                            </a>
                        @else
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1">Belum Ada Wishlist pada Produk Tokomu</h3>
                            <p class="text-xs sm:text-sm text-gray-500 max-w-md leading-relaxed mb-6">
                                Ketika pembeli menekan tombol suka/wishlist pada produkmu, aktivitas dan data peminat akan muncul secara real-time di sini.
                            </p>
                        @endif
                    </div>
                @else
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-100 bg-gray-50/70 text-[11px] font-extrabold uppercase tracking-wider text-gray-500">
                                    <th class="py-3.5 px-6">Produk Toko</th>
                                    <th class="py-3.5 px-6">Pembeli Peminat</th>
                                    <th class="py-3.5 px-6">Waktu Ditambahkan</th>
                                    <th class="py-3.5 px-6 text-right">Ketersediaan Stok</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-xs sm:text-[13px]">
                                @foreach($activities as $act)
                                    @php
                                        $product = $act->product;
                                        $buyer = $act->user;
                                        $userInitial = strtoupper(substr($buyer->name ?? 'User', 0, 1));
                                        $emailParts = explode('@', $buyer->email ?? '');
                                        $maskedEmail = count($emailParts) === 2
                                            ? substr($emailParts[0], 0, 2) . '***@' . $emailParts[1]
                                            : ($buyer->email ?? '-');
                                        $stock = $product->variants->sum('stock');
                                    @endphp
                                    <tr class="hover:bg-gray-50/70 transition-colors">
                                        <!-- Product Info -->
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3">
                                                <img
                                                    src="{{ $product->primary_image_url }}"
                                                    alt="{{ $product->name }}"
                                                    class="w-12 h-12 rounded-xl object-cover bg-gray-100 shrink-0 border border-gray-100"
                                                />
                                                <div class="min-w-0 max-w-xs">
                                                    <a
                                                        href="{{ route('product.detail', $product->slug) }}"
                                                        target="_blank"
                                                        class="font-bold text-gray-900 hover:text-[#4F26A6] transition-colors line-clamp-1"
                                                    >
                                                        {{ $product->name }}
                                                    </a>
                                                    <div class="flex items-center gap-2 mt-0.5 text-[11px] text-gray-500">
                                                        <span class="font-extrabold text-[#4F26A6]">
                                                            Rp {{ number_format((float)$product->price, 0, ',', '.') }}
                                                        </span>
                                                        <span>•</span>
                                                        <span>{{ $product->category?->name ?? 'Kategori' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Buyer Info -->
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-2.5">
                                                @if(!empty($buyer->avatar))
                                                    <img
                                                        src="{{ $buyer->avatar }}"
                                                        alt="{{ $buyer->name }}"
                                                        class="w-8 h-8 rounded-full object-cover ring-2 ring-purple-100 shrink-0 bg-gray-100"
                                                    />
                                                @else
                                                    <div class="w-8 h-8 rounded-full bg-[#F3EEFF] text-[#4F26A6] font-extrabold text-xs flex items-center justify-center ring-2 ring-purple-100 shrink-0">
                                                        {{ $userInitial }}
                                                    </div>
                                                @endif
                                                <div class="min-w-0">
                                                    <p class="font-bold text-gray-900 truncate">{{ $buyer->name }}</p>
                                                    <p class="text-[11px] text-gray-400 truncate">{{ $maskedEmail }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Timestamp -->
                                        <td class="py-4 px-6 text-gray-600 whitespace-nowrap">
                                            <div class="font-semibold text-gray-800">
                                                {{ $act->created_at ? $act->created_at->translatedFormat('d M Y, H:i') : '-' }} WIB
                                            </div>
                                            <span class="text-[11px] text-gray-400">
                                                {{ $act->created_at ? $act->created_at->diffForHumans() : '' }}
                                            </span>
                                        </td>

                                        <!-- Stock status -->
                                        <td class="py-4 px-6 text-right whitespace-nowrap">
                                            @if($stock > 0)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    <span>Tersedia ({{ $stock }} pcs)</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    <span>Stok Habis</span>
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards (< 768px) -->
                    <div class="md:hidden divide-y divide-gray-100">
                        @foreach($activities as $act)
                            @php
                                $product = $act->product;
                                $buyer = $act->user;
                                $userInitial = strtoupper(substr($buyer->name ?? 'User', 0, 1));
                                $emailParts = explode('@', $buyer->email ?? '');
                                $maskedEmail = count($emailParts) === 2
                                    ? substr($emailParts[0], 0, 2) . '***@' . $emailParts[1]
                                    : ($buyer->email ?? '-');
                                $stock = $product->variants->sum('stock');
                            @endphp
                            <div class="p-4 space-y-3">
                                <!-- Product Header -->
                                <div class="flex items-center gap-3">
                                    <img
                                        src="{{ $product->primary_image_url }}"
                                        alt="{{ $product->name }}"
                                        class="w-11 h-11 rounded-xl object-cover bg-gray-100 shrink-0 border border-gray-100"
                                    />
                                    <div class="min-w-0 flex-1">
                                        <a
                                            href="{{ route('product.detail', $product->slug) }}"
                                            target="_blank"
                                            class="font-bold text-gray-900 text-xs sm:text-[13px] hover:text-[#4F26A6] line-clamp-1"
                                        >
                                            {{ $product->name }}
                                        </a>
                                        <div class="flex items-center gap-1.5 mt-0.5 text-[11px]">
                                            <span class="font-bold text-[#4F26A6]">Rp {{ number_format((float)$product->price, 0, ',', '.') }}</span>
                                            <span>•</span>
                                            @if($stock > 0)
                                                <span class="text-emerald-600 font-semibold">Stok {{ $stock }}</span>
                                            @else
                                                <span class="text-rose-600 font-bold">Stok Habis</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Buyer details -->
                                <div class="flex items-center justify-between gap-2 pt-2 border-t border-gray-50 text-xs">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div class="w-6 h-6 rounded-full bg-[#F3EEFF] text-[#4F26A6] font-bold text-[10px] flex items-center justify-center shrink-0">
                                            {{ $userInitial }}
                                        </div>
                                        <span class="font-semibold text-gray-800 truncate text-[11.5px]">{{ $buyer->name }}</span>
                                    </div>
                                    <span class="text-[10.5px] text-gray-400 whitespace-nowrap">
                                        {{ $act->created_at ? $act->created_at->diffForHumans() : '' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination Footer -->
                    @if($activities->hasPages())
                        <div class="p-4 sm:p-5 border-t border-gray-100 bg-gray-50/50">
                            {{ $activities->links() }}
                        </div>
                    @endif
                @endif
            </div>
        @else
            <!-- Tab 2: Products Ranked by Wishlist Count -->
            @if($products->isEmpty())
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-12 text-center flex flex-col items-center justify-center">
                    <div class="w-16 h-16 rounded-3xl bg-rose-50 text-rose-600 flex items-center justify-center mb-4 shadow-2xs">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1">Belum Ada Produk dengan Wishlist</h3>
                    <p class="text-xs sm:text-sm text-gray-500 max-w-md leading-relaxed mb-6">
                        Belum ada pembeli yang menambahkan produk tokomu ke daftar wishlist.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                    @foreach($products as $prod)
                        @php
                            $stock = $prod->variants->sum('stock');
                        @endphp
                        <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-md transition-all p-5 sm:p-6 flex flex-col justify-between group">
                            <div>
                                <!-- Top Row: Category + Wishlist Badge -->
                                <div class="flex items-center justify-between gap-2 mb-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-gray-100 text-gray-600">
                                        {{ $prod->category?->name ?? 'Fashion' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-rose-50 text-rose-600 border border-rose-200">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                                        <span>{{ $prod->wishlists_count }} Peminat</span>
                                    </span>
                                </div>

                                <!-- Product Info with Image -->
                                <div class="flex items-start gap-3.5 mb-4">
                                    <img
                                        src="{{ $prod->primary_image_url }}"
                                        alt="{{ $prod->name }}"
                                        class="w-16 h-16 rounded-2xl object-cover bg-gray-100 shrink-0 border border-gray-100"
                                    />
                                    <div class="min-w-0 flex-1">
                                        <a
                                            href="{{ route('product.detail', $prod->slug) }}"
                                            target="_blank"
                                            class="font-bold text-gray-900 group-hover:text-[#4F26A6] transition-colors line-clamp-2 text-sm sm:text-[14.5px] leading-snug"
                                        >
                                            {{ $prod->name }}
                                        </a>
                                        <p class="text-sm font-extrabold text-[#4F26A6] mt-1">
                                            Rp {{ number_format((float)$prod->price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Stock status -->
                                <div class="flex items-center justify-between text-xs py-2 px-3 rounded-xl bg-gray-50 mb-4">
                                    <span class="text-gray-500 font-semibold">Ketersediaan Stok:</span>
                                    @if($stock > 0)
                                        <span class="font-extrabold text-emerald-700">{{ $stock }} pcs tersedia</span>
                                    @else
                                        <span class="font-extrabold text-rose-600">Stok Habis</span>
                                    @endif
                                </div>

                                <!-- Recent Wishlisters Avatars Preview -->
                                @if($prod->wishlists->isNotEmpty())
                                    <div class="pt-2 border-t border-gray-100">
                                        <span class="text-[10.5px] font-extrabold uppercase tracking-wider text-gray-400 block mb-2">
                                            Peminat Terbaru:
                                        </span>
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            @foreach($prod->wishlists->take(6) as $w)
                                                @php
                                                    $u = $w->user;
                                                    $initial = strtoupper(substr($u->name ?? 'U', 0, 1));
                                                @endphp
                                                <div
                                                    class="inline-flex items-center gap-1 py-0.5 px-2 rounded-full bg-[#F3EEFF] text-[#4F26A6] text-[11px] font-bold"
                                                    title="{{ $u->name ?? 'User' }}"
                                                >
                                                    <span class="w-4 h-4 rounded-full bg-[#4F26A6] text-white text-[9px] flex items-center justify-center font-black">
                                                        {{ $initial }}
                                                    </span>
                                                    <span class="truncate max-w-[140px]">{{ $u->name }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Footer Action -->
                            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                                <a
                                    href="{{ route('seller.wishlists.index', ['tab' => 'activity', 'product_id' => $prod->id]) }}"
                                    class="text-xs font-bold text-[#4F26A6] hover:underline inline-flex items-center gap-1"
                                >
                                    <span>Lihat Semua Riwayat Peminat</span>
                                    <span>&rarr;</span>
                                </a>
                                <a
                                    href="{{ route('seller.products.edit', $prod->id) }}"
                                    class="text-xs font-semibold text-gray-500 hover:text-gray-900"
                                >
                                    Edit Stok
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination Footer -->
                @if($products->hasPages())
                    <div class="mt-8 p-4 sm:p-5 bg-white rounded-3xl border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)]">
                        {{ $products->links() }}
                    </div>
                @endif
            @endif
        @endif
    </main>
</x-layouts.app>
