<x-layouts.app :title="$title" activeTab="seller-followers">
    <main class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10">
        <!-- Breadcrumb & Title Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-gray-500 mb-2">
                    <a href="{{ route('seller.dashboard') }}" class="hover:text-[#4F26A6] transition-colors">Seller Portal</a>
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-gray-900 font-bold">Pengikut Toko</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-950 tracking-tight flex items-center gap-3">
                    <span>Pengikut Toko</span>
                    <span class="px-3 py-1 rounded-full text-xs font-black bg-[#F3EEFF] text-[#4F26A6] border border-[#4F26A6]/20">
                        {{ number_format($totalFollowers, 0, ',', '.') }} Pengikut
                    </span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    Daftar pembeli yang mengikuti tokomu. Pelajari minat mereka dan pertahankan interaksi untuk mendorong transaksi berulang.
                </p>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <a
                    href="{{ route('seller.profile', '@' . $seller->username) }}"
                    target="_blank"
                    class="h-10 sm:h-11 px-4 rounded-xl border border-gray-200 text-gray-700 hover:text-[#4F26A6] hover:bg-gray-50 font-bold text-xs sm:text-sm transition-all flex items-center gap-2 cursor-pointer shadow-2xs"
                >
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span>Lihat Toko Publik</span>
                </a>
                <a
                    href="{{ route('seller.wishlists.index') }}"
                    class="h-10 sm:h-11 px-4 rounded-xl bg-[#F3EEFF] hover:bg-[#EADDFE] text-[#4F26A6] font-bold text-xs sm:text-sm transition-all flex items-center gap-2 cursor-pointer shadow-2xs border border-[#4F26A6]/20"
                >
                    <svg class="w-4 h-4 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span>Lihat Peminat Wishlist &rarr;</span>
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
            <a href="{{ route('seller.followers.index') }}" class="pb-3 text-sm font-bold text-[#4F26A6] border-b-2 border-[#4F26A6] px-3 whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Pengikut Toko</span>
            </a>
            <a href="{{ route('seller.wishlists.index') }}" class="pb-3 text-sm font-semibold text-gray-500 hover:text-gray-900 px-3 whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
        @php
            $loyalCount = collect($orderCounts)->filter(fn($c) => $c > 0)->count();
            $newFollowersCount = max(0, $totalFollowers - $loyalCount);
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-8">
            <!-- Card 1: Total Followers -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-[13px] font-bold text-gray-500">Total Pengikut</span>
                    <span class="w-9 h-9 rounded-xl bg-[#F3EEFF] text-[#4F26A6] flex items-center justify-center shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </span>
                </div>
                <div>
                    <span class="text-2xl sm:text-3xl font-black text-gray-950 leading-none">
                        {{ number_format($totalFollowers, 0, ',', '.') }}
                    </span>
                    <span class="text-xs text-gray-400 font-medium block mt-1">Pembeli aktif mengikuti tokomu</span>
                </div>
            </div>

            <!-- Card 2: Loyal Customers among followers -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-[13px] font-bold text-gray-500">Pelanggan yang Berbelanja</span>
                    <span class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </span>
                </div>
                <div>
                    <span class="text-2xl sm:text-3xl font-black text-emerald-700 leading-none">
                        {{ number_format($loyalCount, 0, ',', '.') }}
                    </span>
                    <span class="text-xs text-gray-400 font-medium block mt-1">Pernah menyelesaikan pesanan</span>
                </div>
            </div>

            <!-- Card 3: Wishlist bridge -->
            <a href="{{ route('seller.wishlists.index') }}" class="bg-gradient-to-br from-purple-50/70 to-white rounded-3xl p-5 sm:p-6 border border-purple-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-md transition-all flex flex-col justify-between group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-[13px] font-bold text-[#4F26A6]">Peminat Barang Toko</span>
                    <span class="w-9 h-9 rounded-xl bg-[#4F26A6] text-white flex items-center justify-center shadow-2xs group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </span>
                </div>
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-purple-900 font-bold">Lihat siapa saja yang menyukai produk tokomu</span>
                        <span class="text-xs font-black text-[#4F26A6] group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </div>
                    <span class="text-[11px] text-gray-500 font-medium block mt-1">Cek daftar wishlist &amp; produk terpopuler</span>
                </div>
            </a>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-5 sm:p-6 mb-6">
            <form action="{{ route('seller.followers.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                <div class="relative flex-1">
                    <input
                        type="text"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Cari nama atau email pengikut toko..."
                        class="w-full h-11 pl-10 pr-10 rounded-xl bg-[#F9FAFB] border border-gray-200 text-xs sm:text-[13.5px] text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] transition-all"
                    />
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    @if($search !== '')
                        <a href="{{ route('seller.followers.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 p-1" title="Reset pencarian">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <button type="submit" class="h-11 px-5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs sm:text-sm shadow-xs transition-all cursor-pointer">
                        Cari Pengikut
                    </button>
                    @if($search !== '')
                        <a href="{{ route('seller.followers.index') }}" class="h-11 px-4 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold text-xs sm:text-sm flex items-center transition-all">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Followers List Content -->
        <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] overflow-hidden">
            @if($followers->isEmpty())
                <div class="py-16 px-4 text-center flex flex-col items-center justify-center">
                    <div class="w-16 h-16 rounded-3xl bg-[#F3EEFF] text-[#4F26A6] flex items-center justify-center mb-4 shadow-2xs">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    @if($search !== '')
                        <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1">Tidak Ada Pengikut Ditemukan</h3>
                        <p class="text-xs sm:text-sm text-gray-500 max-w-md leading-relaxed mb-6">
                            Tidak ada pengikut dengan kata kunci &ldquo;{{ $search }}&rdquo;. Coba gunakan kata kunci nama lain.
                        </p>
                        <a href="{{ route('seller.followers.index') }}" class="px-5 py-2.5 rounded-xl bg-[#4F26A6] text-white font-bold text-xs sm:text-sm hover:bg-[#3E1D85] transition-all">
                            Lihat Semua Pengikut
                        </a>
                    @else
                        <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1">Belum Ada Pengikut Toko</h3>
                        <p class="text-xs sm:text-sm text-gray-500 max-w-md leading-relaxed mb-6">
                            Toko Anda siap menjangkau pelanggan! Bagikan tautan tokomu ke media sosial agar pembeli mulai mengikuti dan mendapatkan update produk terbaru.
                        </p>
                        <a href="{{ route('seller.profile', '@' . $seller->username) }}" target="_blank" class="px-5 py-2.5 rounded-xl bg-[#4F26A6] text-white font-bold text-xs sm:text-sm hover:bg-[#3E1D85] transition-all">
                            Kunjungi Toko Publik
                        </a>
                    @endif
                </div>
            @else
                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50/70 text-[11px] font-extrabold uppercase tracking-wider text-gray-500">
                                <th class="py-3.5 px-6">Pembeli / Pengikut</th>
                                <th class="py-3.5 px-6">Mulai Mengikuti</th>
                                <th class="py-3.5 px-6">Status Belanja</th>
                                <th class="py-3.5 px-6 text-right">Aksi / Info</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-xs sm:text-[13px]">
                            @foreach($followers as $f)
                                @php
                                    $followerUser = $f->user;
                                    $ordersDone = $orderCounts[$f->user_id] ?? 0;
                                    $userInitial = strtoupper(substr($followerUser->name ?? 'User', 0, 1));
                                    $emailParts = explode('@', $followerUser->email ?? '');
                                    $maskedEmail = count($emailParts) === 2
                                        ? substr($emailParts[0], 0, 2) . '***@' . $emailParts[1]
                                        : ($followerUser->email ?? '-');
                                @endphp
                                <tr class="hover:bg-gray-50/70 transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            @if(!empty($followerUser->avatar))
                                                <img
                                                    src="{{ $followerUser->avatar }}"
                                                    alt="{{ $followerUser->name }}"
                                                    class="w-10 h-10 rounded-full object-cover ring-2 ring-purple-100 shrink-0 bg-gray-100"
                                                />
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-[#F3EEFF] text-[#4F26A6] font-black text-sm flex items-center justify-center ring-2 ring-purple-100 shrink-0">
                                                    {{ $userInitial }}
                                                </div>
                                            @endif
                                            <div class="min-w-0">
                                                <p class="font-bold text-gray-900 truncate">{{ $followerUser->name }}</p>
                                                <p class="text-[11px] text-gray-400 truncate">{{ $maskedEmail }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-gray-600 whitespace-nowrap">
                                        <div class="font-semibold text-gray-800">
                                            {{ $f->created_at ? $f->created_at->translatedFormat('d M Y, H:i') : '-' }} WIB
                                        </div>
                                        <span class="text-[11px] text-gray-400">
                                            {{ $f->created_at ? $f->created_at->diffForHumans() : '' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        @if($ordersDone > 0)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                <span>Pernah Belanja ({{ $ordersDone }} Pesanan)</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                                <span>Pengikut Baru</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold bg-[#F3EEFF] text-[#4F26A6]">
                                            Aktif Mengikuti
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View (< 768px) -->
                <div class="md:hidden divide-y divide-gray-100">
                    @foreach($followers as $f)
                        @php
                            $followerUser = $f->user;
                            $ordersDone = $orderCounts[$f->user_id] ?? 0;
                            $userInitial = strtoupper(substr($followerUser->name ?? 'User', 0, 1));
                            $emailParts = explode('@', $followerUser->email ?? '');
                            $maskedEmail = count($emailParts) === 2
                                ? substr($emailParts[0], 0, 2) . '***@' . $emailParts[1]
                                : ($followerUser->email ?? '-');
                        @endphp
                        <div class="p-4 space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    @if(!empty($followerUser->avatar))
                                        <img
                                            src="{{ $followerUser->avatar }}"
                                            alt="{{ $followerUser->name }}"
                                            class="w-10 h-10 rounded-full object-cover ring-2 ring-purple-100 shrink-0 bg-gray-100"
                                        />
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-[#F3EEFF] text-[#4F26A6] font-black text-sm flex items-center justify-center ring-2 ring-purple-100 shrink-0">
                                            {{ $userInitial }}
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <p class="font-bold text-gray-900 truncate text-[13.5px]">{{ $followerUser->name }}</p>
                                        <p class="text-[11px] text-gray-400 truncate">{{ $maskedEmail }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-[#F3EEFF] text-[#4F26A6] shrink-0">
                                    Mengikuti
                                </span>
                            </div>

                            <div class="flex items-center justify-between pt-1 border-t border-gray-50 text-xs">
                                <div>
                                    <span class="text-gray-400 block text-[10px]">Mulai Mengikuti:</span>
                                    <span class="font-semibold text-gray-700 text-[11.5px]">
                                        {{ $f->created_at ? $f->created_at->translatedFormat('d M Y') : '-' }} ({{ $f->created_at ? $f->created_at->diffForHumans() : '' }})
                                    </span>
                                </div>
                                <div>
                                    @if($ordersDone > 0)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span>{{ $ordersDone }}x Belanja</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-semibold bg-gray-100 text-gray-600">
                                            <span>Pengikut Baru</span>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination Footer -->
                @if($followers->hasPages())
                    <div class="p-4 sm:p-5 border-t border-gray-100 bg-gray-50/50">
                        {{ $followers->links() }}
                    </div>
                @endif
            @endif
        </div>
    </main>
</x-layouts.app>
