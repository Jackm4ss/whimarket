<x-layouts.app :title="$title" activeTab="seller-dashboard">
    <main
        class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10"
        x-data="{
            isShareCopied: false,
            copyStoreLink(url) {
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(url);
                    this.isShareCopied = true;
                    setTimeout(() => { this.isShareCopied = false; }, 2500);
                }
            }
        }"
    >
        {{-- Inactive / Suspended Store Notice --}}
        @if($seller->status !== \App\Enums\SellerStatus::VERIFIED)
            <div class="mb-6 rounded-2xl bg-amber-50 border-2 border-amber-300 p-5 sm:p-6 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <h2 class="text-base sm:text-lg font-black text-amber-900">
                                @if($seller->status === \App\Enums\SellerStatus::SUSPENDED)
                                    Toko Anda Sedang Dinonaktifkan oleh Administrator
                                @elseif($seller->status === \App\Enums\SellerStatus::PENDING)
                                    Toko Anda Sedang Menunggu Moderasi Administrator
                                @elseif($seller->status === \App\Enums\SellerStatus::REJECTED)
                                    Pendaftaran Toko Anda Ditolak
                                @else
                                    Status Toko: {{ $seller->status->label() }}
                                @endif
                            </h2>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-200 text-amber-900 border border-amber-300">
                                {{ $seller->status->label() }}
                            </span>
                        </div>
                        <p class="text-sm text-amber-800 leading-relaxed mb-3">
                            @if(!empty($seller->rejection_reason))
                                <strong>Catatan Admin:</strong> &ldquo;{{ $seller->rejection_reason }}&rdquo;
                            @else
                                Saat ini toko Anda sedang dinonaktifkan sementara. Selama periode nonaktif, seluruh produk Anda tidak dapat dibeli oleh pembeli di storefront dan Anda tidak dapat menambah atau menerbitkan produk baru.
                            @endif
                        </p>
                        <div class="flex flex-wrap items-center gap-4 text-xs sm:text-sm text-amber-900 font-semibold pt-2 border-t border-amber-200/80">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-amber-700 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Pesanan yang sedang berlangsung tetap dapat Anda proses dan selesaikan.</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-amber-700 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Hubungi tim bantuan jika membutuhkan evaluasi pembukaan kembali toko.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- 1. Standalone Hero Banner matching Image #2 -->
        <div class="relative w-full h-[180px] sm:h-[240px] md:h-[280px] lg:h-[300px] xl:h-[320px] rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xs">
            <img
                src="{{ $seller->banner_url }}"
                alt="Banner {{ $seller->store_name }}"
                class="w-full h-full object-cover object-center"
            />
            <div class="absolute top-3 right-3 sm:top-4 sm:right-4 flex items-center gap-1.5 sm:gap-2 max-w-[calc(100%-24px)] z-20">
                <a
                    href="{{ route('seller.settings') }}"
                    class="bg-white/95 hover:bg-white backdrop-blur-md px-2.5 sm:px-3.5 py-1.5 rounded-full shadow-xs hover:shadow-sm border border-white/80 flex items-center gap-1 text-[11px] sm:text-[12px] font-bold text-gray-800 hover:text-[#4F26A6] transition-all cursor-pointer group shrink-0"
                >
                    <svg class="w-3.5 h-3.5 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="hidden sm:inline">Ubah Banner &amp; Profil</span>
                    <span class="sm:hidden">Ubah Banner</span>
                </a>
                <button
                    type="button"
                    @click="copyStoreLink('{{ route('seller.profile', '@' . $seller->username) }}')"
                    class="bg-white/95 hover:bg-white backdrop-blur-md px-2.5 sm:px-3.5 py-1.5 rounded-full shadow-xs hover:shadow-sm border border-white/80 flex items-center gap-1 text-[11px] sm:text-[12px] font-bold text-gray-800 hover:text-[#4F26A6] transition-all cursor-pointer group shrink-0"
                >
                    <svg class="w-3.5 h-3.5 text-gray-700 group-hover:text-[#4F26A6] transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                    <span x-text="isShareCopied ? 'Tersalin!' : 'Bagikan'"></span>
                </button>
            </div>
        </div>

        <!-- 2. Profile Row (100% exact to Image #2) -->
        <div class="relative pt-0 mb-8 px-1 sm:px-2">
            <!-- Desktop & Tablet (md and above, >=768px): 100% exact to Image #2 -->
            <div class="hidden md:flex items-start justify-between gap-6">
                <!-- Left: Avatar + Full Info side-by-side -->
                <div class="flex items-start gap-6 pl-4">
                    <!-- Circular Avatar with Camera overlay -->
                    <div class="-mt-16 xl:-mt-18 shrink-0 z-20">
                        <a href="{{ route('seller.settings') }}" class="relative block group" title="Ubah Foto Profil">
                            <img
                                src="{{ $seller->avatar_url }}"
                                alt="{{ $seller->store_name }}"
                                class="w-36 h-36 lg:w-40 lg:h-40 rounded-full object-cover ring-4 sm:ring-[5px] ring-white shadow-lg bg-white group-hover:brightness-95 transition-all"
                            />
                            <span class="absolute bottom-1 right-1 w-8 h-8 rounded-full bg-[#4F26A6] text-white flex items-center justify-center ring-2 ring-white shadow group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </span>
                        </a>
                    </div>

                    <!-- Seller Info (strictly NEXT to avatar, matching Image #2 1:1) -->
                    <div class="flex flex-col pt-1 sm:pt-2">
                        <!-- Name + Verified Rosette -->
                        <div class="flex items-center gap-2 mb-1">
                            <h1 class="text-[25px] xl:text-[28px] font-black text-[#111827] tracking-tight leading-tight">
                                {{ $seller->store_name }}
                            </h1>
                            @if($seller->isVerified())
                                <x-verified-badge size="md" class="w-5.5 h-5.5 shrink-0" />
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                    {{ $seller->status->label() }}
                                </span>
                            @endif
                        </div>

                        <!-- Subtitle / Role -->
                        <p class="text-[14px] {{ $seller->isVerified() ? 'text-gray-500 font-medium' : 'text-amber-700 font-bold' }} mb-1">
                            {{ $seller->isVerified() ? 'Verified Creator' : 'Toko Dinonaktifkan Sementara' }}
                        </p>
                        <!-- Bio quote -->
                        @if(!empty($seller->bio))
                            <p class="text-[14.5px] text-gray-700 font-normal mb-3">
                                &ldquo;{{ $seller->bio }}&rdquo;
                            </p>
                        @endif

                        <!-- Desktop Stats Row: All in one line next to avatar -->
                        <div class="flex items-center gap-3.5 text-[13.5px] text-gray-600 font-medium">
                            <!-- Rating -->
                            <div class="inline-flex items-center gap-1.5 shrink-0">
                                <svg class="w-[18px] h-[18px] text-amber-400 fill-current shrink-0" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                @if(isset($stats['rating']) && $stats['rating'] !== null)
                                    <span class="font-extrabold text-gray-900 text-[15px]">{{ $stats['rating'] }}</span>
                                    <span class="text-gray-400 font-normal whitespace-nowrap text-[13px]">({{ $stats['review_count'] ?? 0 }} ulasan)</span>
                                @else
                                    <span class="font-bold text-gray-700 text-[14px]">Belum ada ulasan</span>
                                    <span class="text-gray-400 font-normal whitespace-nowrap text-[13px]">(0 ulasan)</span>
                                @endif
                            </div>

                            <span class="text-gray-200 font-light">|</span>

                            <!-- Barang Count -->
                            <div class="inline-flex items-center gap-1.5 shrink-0">
                                <svg class="w-[18px] h-[18px] text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <span class="font-semibold text-gray-800 whitespace-nowrap text-[13.5px]">{{ $metrics['total_products'] }} Barang</span>
                            </div>

                            <span class="text-gray-200 font-light">|</span>

                            <!-- Pengikut -->
                            <div class="inline-flex items-center gap-1.5 shrink-0">
                                <svg class="w-[18px] h-[18px] text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="font-semibold text-gray-800 whitespace-nowrap text-[13.5px]">
                                    {{ !empty($stats['follower_count']) ? $stats['follower_count'] : '0' }} Pengikut
                                </span>
                            </div>

                            <span class="text-gray-200 font-light">|</span>

                            <!-- Bergabung -->
                            <div class="inline-flex items-center gap-1.5 shrink-0 text-gray-500">
                                <svg class="w-[18px] h-[18px] text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                                <span class="text-gray-500 font-normal whitespace-nowrap text-[13px]">Bergabung sejak {{ $seller->created_at ? $seller->created_at->translatedFormat('M Y') : 'Sep 2026' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Desktop Action Buttons matching Image #2 -->
                <div class="flex items-center gap-3 pt-3 pr-1 shrink-0">
                    <a
                        href="{{ route('seller.profile', '@' . $seller->username) }}"
                        target="_blank"
                        class="px-4.5 h-11 sm:h-11.5 rounded-xl border border-gray-200 text-gray-700 hover:text-[#4F26A6] hover:bg-gray-50 font-bold text-xs sm:text-[13.5px] transition-all flex items-center gap-2 cursor-pointer shadow-xs"
                    >
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span>Lihat Toko Publik</span>
                    </a>

                    @if($seller->isVerified())
                        <a
                            href="{{ route('seller.products.create') }}"
                            class="px-6 h-11 sm:h-11.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs sm:text-[14px] shadow-[0_4px_16px_rgba(79,38,166,0.22)] transition-all flex items-center gap-2 cursor-pointer active:scale-98"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            <span>Tambah Produk</span>
                        </a>
                    @else
                        <button
                            type="button"
                            onclick="alert('Toko Anda sedang dinonaktifkan oleh administrator. Anda belum dapat menambah produk baru.')"
                            class="px-5 h-11 sm:h-11.5 rounded-xl bg-gray-200 text-gray-500 font-bold text-xs sm:text-[14px] transition-all flex items-center gap-2 cursor-not-allowed opacity-80"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            <span>Tambah Produk (Nonaktif)</span>
                        </button>
                    @endif
                </div>
            </div>
            <!-- Mobile Only (< 768px): 100% exact to Image #2 -->
            <!-- Mobile Only (< 768px): Polished Spacing & Clean Alignment -->
            <div class="flex flex-col md:hidden w-full">
                <!-- Top row: Avatar + Name on left -->
                <div class="flex items-start justify-between w-full gap-3">
                    <div class="flex items-end gap-3.5 min-w-0 flex-1">
                        <div class="-mt-12 sm:-mt-16 shrink-0 z-20">
                            <a href="{{ route('seller.settings') }}" class="relative block group" title="Ubah Foto Profil">
                                <img
                                    src="{{ $seller->avatar_url }}"
                                    alt="{{ $seller->store_name }}"
                                    class="w-22 h-22 sm:w-26 sm:h-26 rounded-full object-cover ring-4 sm:ring-[5px] ring-white shadow-lg bg-white"
                                />
                                <span class="absolute bottom-0 right-0 w-6 h-6 rounded-full bg-[#4F26A6] text-white flex items-center justify-center ring-2 ring-white shadow">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </span>
                            </a>
                        </div>
                        <div class="flex flex-col pt-3 sm:pt-6 pb-1 min-w-0 flex-1">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <h1 class="text-[18px] sm:text-[22px] font-black text-[#111827] tracking-tight leading-tight">
                                    {{ $seller->store_name }}
                                </h1>
                                @if($seller->isVerified())
                                    <x-verified-badge size="md" class="w-4.5 h-4.5 text-[#4F26A6] shrink-0" />
                                @else
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                        {{ $seller->status->label() }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-1.5 mt-0.5 text-xs text-gray-500 font-medium">
                                <span class="{{ $seller->isVerified() ? 'text-[#4F26A6] font-bold' : 'text-amber-700 font-bold' }}">
                                    {{ $seller->isVerified() ? 'Verified Creator' : 'Toko Dinonaktifkan' }}
                                </span>
                                <span class="text-gray-300">•</span>
                                <span class="text-gray-500 truncate">{{ '@' . $seller->username }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @if(!empty($seller->bio))
                    <p class="text-[13.5px] text-gray-700 font-normal mt-2.5 mb-3 leading-relaxed">
                        &ldquo;{{ $seller->bio }}&rdquo;
                    </p>
                @endif

                <!-- Mobile-Only Stats Row: Clean Rounded White Card with Internal Dividers -->
                <div class="grid grid-cols-3 divide-x divide-gray-100 bg-white rounded-2xl border border-gray-100/90 shadow-2xs py-2.5 px-1 mb-3.5 text-center">
                    <div class="flex flex-col items-center justify-center px-1">
                        <svg class="w-4 h-4 text-amber-400 fill-current mb-1" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        @if(isset($stats['rating']) && $stats['rating'] !== null)
                            <span class="text-[14px] font-black text-gray-900 leading-none">
                                {{ $stats['rating'] }}
                            </span>
                            <span class="text-[11px] text-gray-400 font-normal mt-1 whitespace-nowrap">
                                ({{ isset($stats['review_count']) ? $stats['review_count'] : 0 }} ulasan)
                            </span>
                        @else
                            <span class="text-[11.5px] sm:text-[12px] font-bold text-gray-800 leading-tight text-center">
                                Belum ada ulasan
                            </span>
                            <span class="text-[10.5px] text-gray-400 font-normal mt-0.5 whitespace-nowrap">
                                (0 ulasan)
                            </span>
                        @endif
                    </div>
                    <div class="flex flex-col items-center justify-center px-1">
                        <svg class="w-4 h-4 text-[#4F26A6] stroke-current fill-none mb-1" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="text-[14px] font-black text-gray-900 leading-none">{{ $metrics['total_products'] }}</span>
                        <span class="text-[11px] text-gray-400 font-normal mt-1 whitespace-nowrap">Barang</span>
                    </div>

                    <div class="flex flex-col items-center justify-center px-1">
                        <svg class="w-4 h-4 text-[#4F26A6] stroke-current fill-none mb-1" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-[14px] font-black text-gray-900 leading-none">
                            {{ !empty($stats['follower_count']) ? $stats['follower_count'] : '0' }}
                        </span>
                        <span class="text-[11px] text-gray-400 font-normal mt-1 whitespace-nowrap">Pengikut</span>
                    </div>
                </div>

                <!-- Mobile Action Buttons -->
                <div class="grid grid-cols-2 gap-2.5 pt-0.5">
                    <a
                        href="{{ route('seller.profile', '@' . $seller->username) }}"
                        target="_blank"
                        class="h-11 rounded-xl border border-gray-200 text-gray-700 font-bold text-xs flex items-center justify-center gap-1.5 shadow-2xs hover:bg-gray-50 active:scale-98 transition-all"
                    >
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span>Lihat Toko</span>
                    </a>
                    @if($seller->isVerified())
                        <a
                            href="{{ route('seller.products.create') }}"
                            class="h-11 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-md shadow-[#4F26A6]/20 active:scale-98 transition-all"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            <span>+ Produk</span>
                        </a>
                    @else
                        <button
                            type="button"
                            onclick="alert('Toko Anda sedang dinonaktifkan oleh administrator. Anda belum dapat menambah produk baru.')"
                            class="h-11 rounded-xl bg-gray-200 text-gray-500 font-bold text-xs flex items-center justify-center gap-1.5 opacity-80 cursor-not-allowed"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            <span>+ Produk (Terkunci)</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        <!-- Metric Overview Cards (4 Pillars of Store Health) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <!-- Metric 1: Pending Orders -->
            <a href="{{ route('seller.orders.index', ['status' => 'paid']) }}" class="bg-white rounded-3xl p-5 sm:p-6 border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-md transition-all flex flex-col justify-between group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-[13px] font-bold text-gray-500">Perlu Diproses</span>
                    <span class="w-8 h-8 rounded-xl bg-purple-50 text-[#4F26A6] group-hover:bg-[#4F26A6] group-hover:text-white transition-colors flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </span>
                </div>
                <div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl sm:text-3xl font-black text-gray-900 leading-none">{{ $metrics['pending_orders'] }}</span>
                        @if($metrics['pending_orders'] > 0)
                            <span class="px-2 py-0.5 rounded-full bg-purple-100 text-[#4F26A6] text-[10px] font-bold">Siap kirim</span>
                        @endif
                    </div>
                    <span class="text-xs text-gray-400 font-medium block mt-1">Pesanan baru masuk</span>
                </div>
            </a>

            <!-- Metric 2: Shipped Orders -->
            <a href="{{ route('seller.orders.index', ['status' => 'shipped']) }}" class="bg-white rounded-3xl p-5 sm:p-6 border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-md transition-all flex flex-col justify-between group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-[13px] font-bold text-gray-500">Sedang Dikirim</span>
                    <span class="w-8 h-8 rounded-xl bg-amber-50 text-[#F59E0B] group-hover:bg-[#F59E0B] group-hover:text-white transition-colors flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    </span>
                </div>
                <div>
                    <span class="text-2xl sm:text-3xl font-black text-gray-900 leading-none">{{ $metrics['shipped_orders'] }}</span>
                    <span class="text-xs text-gray-400 font-medium block mt-1">Dalam perjalanan kurir</span>
                </div>
            </a>

            <!-- Metric 3: Active Products -->
            <a href="{{ route('seller.products.index') }}" class="bg-white rounded-3xl p-5 sm:p-6 border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-md transition-all flex flex-col justify-between group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-[13px] font-bold text-gray-500">Produk Aktif</span>
                    <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </span>
                </div>
                <div>
                    <span class="text-2xl sm:text-3xl font-black text-gray-900 leading-none">{{ $metrics['active_products'] }}</span>
                    <span class="text-xs text-gray-400 font-medium block mt-1">Tayang di katalog toko</span>
                </div>
            </a>

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

        <!-- Navigation Tabs for Seller Portal -->
        <div class="flex items-center gap-2 mb-8 border-b border-gray-200 overflow-x-auto no-scrollbar">
            <a href="{{ route('seller.dashboard') }}" class="pb-3 text-sm font-bold text-[#4F26A6] border-b-2 border-[#4F26A6] px-3 whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></svg>
                <span>Ringkasan Toko</span>
            </a>
            <a href="{{ route('seller.orders.index') }}" class="pb-3 text-sm font-semibold text-gray-500 hover:text-gray-900 px-3 whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Kelola Pesanan ({{ $metrics['pending_orders'] + $metrics['shipped_orders'] }})</span>
            </a>
            <a href="{{ route('seller.products.index') }}" class="pb-3 text-sm font-semibold text-gray-500 hover:text-gray-900 px-3 whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span>Katalog Produk ({{ $metrics['total_products'] }})</span>
            </a>
            <a href="{{ route('seller.settings') }}" class="pb-3 text-sm font-semibold text-gray-500 hover:text-gray-900 px-3 whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Pengaturan Toko</span>
            </a>
        </div>

        <!-- Two Column Main Layout: 8 cols left (Orders & Live Catalog) + 4 cols right (Checklist, Stats, Tips) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Main Column (8 cols) -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Section 1: Pesanan Masuk Terbaru -->
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                        <div>
                            <h2 class="text-lg sm:text-xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                <span>Pesanan Masuk Terbaru</span>
                            </h2>
                            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Segera proses pesanan dan unggah nomor resi sebelum batas waktu pengiriman.</p>
                        </div>
                        <a href="{{ route('seller.orders.index') }}" class="text-xs sm:text-sm font-bold text-[#4F26A6] hover:underline whitespace-nowrap inline-flex items-center gap-1">
                            <span>Lihat Semua</span>
                            <span>&rarr;</span>
                        </a>
                    </div>

                    @if($recentOrders->isEmpty())
                        <!-- Helpful, Action-Oriented Empty State -->
                        <div class="py-10 px-4 text-center flex flex-col items-center justify-center">
                            <div class="w-16 h-16 rounded-3xl bg-[#F3EEFF] text-[#4F26A6] flex items-center justify-center mb-4 shadow-2xs">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1.5">Belum Ada Pesanan Masuk</h3>
                            <p class="text-xs sm:text-sm text-gray-500 max-w-md leading-relaxed mb-6">
                                Toko Anda sudah siap beroperasi! Bagikan tautan tokomu ke media sosial (Instagram, TikTok, WhatsApp) agar followers dapat langsung membeli barang koleksimu.
                            </p>
                            <div class="flex items-center gap-3 flex-wrap justify-center">
                                <button
                                    type="button"
                                    @click="copyStoreLink('{{ route('seller.profile', '@' . $seller->username) }}')"
                                    class="px-4 py-2.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs sm:text-sm transition-all shadow-sm flex items-center gap-2 cursor-pointer"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    <span x-text="isShareCopied ? 'Link Toko Tersalin!' : 'Bagikan Link Toko'"></span>
                                </button>
                                <a
                                    href="{{ route('seller.profile', '@' . $seller->username) }}"
                                    target="_blank"
                                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 font-bold text-xs sm:text-sm transition-all"
                                >
                                    Lihat Toko Publik
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Order Table -->
                        <div class="overflow-x-auto no-scrollbar -mx-6 sm:mx-0 px-6 sm:px-0">
                            <table class="w-full min-w-[700px] text-left text-sm">
                                <thead class="text-xs uppercase tracking-wider text-gray-400 border-b border-gray-100 pb-3 whitespace-nowrap">
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
                                            <td class="py-4 font-mono font-bold text-gray-900 whitespace-nowrap">
                                                #{{ $order->order_number }}
                                                <span class="text-[11px] text-gray-400 font-sans block mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                            </td>
                                            <td class="py-4 whitespace-nowrap">
                                                <span class="font-semibold text-gray-800">{{ $order->buyer->name }}</span>
                                                <span class="text-xs text-gray-400 block">{{ $order->buyer->phone ?? '-' }}</span>
                                            </td>
                                            <td class="py-4 font-extrabold text-[#4F26A6] whitespace-nowrap">
                                                Rp {{ number_format((float)$order->total_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap
                                                    {{ in_array($order->status::$name, ['paid', 'processing', 'payment_verification'], true) ? 'bg-purple-50 text-[#4F26A6] border border-purple-200/60' : '' }}
                                                    {{ $order->status::$name === 'pending_payment' ? 'bg-amber-50 text-amber-800 border border-amber-200/60' : '' }}
                                                    {{ $order->status::$name === 'shipped' ? 'bg-amber-50 text-amber-800 border border-amber-200/60' : '' }}
                                                    {{ $order->status::$name === 'delivered' || $order->status::$name === 'completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : '' }}
                                                    {{ $order->status::$name === 'disputed' ? 'bg-rose-50 text-rose-700 border border-rose-200/60' : '' }}
                                                ">
                                                    {{ $order->status->label() }}
                                                </span>
                                            </td>
                                            <td class="py-4 text-right whitespace-nowrap">
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

                <!-- Section 2: Katalog Produk Toko (Live Product Showcase) -->
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                        <div>
                            <h2 class="text-lg sm:text-xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                <span>Katalog Produk Toko</span>
                            </h2>
                            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Produk yang sedang tayang dan siap dibeli oleh followers dan pelangganmu.</p>
                        </div>
                        <a href="{{ route('seller.products.index') }}" class="text-xs sm:text-sm font-bold text-[#4F26A6] hover:underline whitespace-nowrap inline-flex items-center gap-1">
                            <span>Kelola Produk ({{ $metrics['total_products'] }})</span>
                            <span>&rarr;</span>
                        </a>
                    </div>

                    @if($recentProducts->isEmpty())
                        <div class="py-10 px-4 text-center flex flex-col items-center justify-center">
                            <div class="w-16 h-16 rounded-3xl bg-[#F3EEFF] text-[#4F26A6] flex items-center justify-center mb-4 shadow-2xs">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1.5">Belum Ada Produk di Toko</h3>
                            <p class="text-xs sm:text-sm text-gray-500 max-w-md leading-relaxed mb-5">
                                Mulai jual barang pre-loved eksklusif atau merchandise resmi pertamamu dengan mengunggah foto dan atur variannya.
                            </p>
                            <a
                                href="{{ route('seller.products.create') }}"
                                class="px-5 py-2.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs sm:text-sm transition-all shadow-md shadow-[#4F26A6]/20 inline-flex items-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                <span>Tambah Produk Pertamamu</span>
                            </a>
                        </div>
                    @else
                        <!-- Products Grid in Overview -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($recentProducts as $prod)
                                <div class="p-4 rounded-2xl bg-[#FAF9FC] border border-gray-100 hover:border-purple-200 transition-all flex items-start gap-3.5 group">
                                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden bg-gray-100 border border-gray-200/60 shrink-0">
                                        <img
                                            src="{{ $prod->primary_image_url }}"
                                            alt="{{ $prod->name }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        />
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-1.5 mb-1">
                                            <span class="px-2 py-0.5 rounded-md bg-white border border-gray-200 text-gray-700 text-[10px] font-bold">
                                                {{ $prod->condition?->label() }}
                                            </span>
                                            <span class="text-[11px] text-gray-400 font-medium truncate">
                                                {{ $prod->category->name ?? 'Merchandise' }}
                                            </span>
                                        </div>
                                        <h4 class="text-xs sm:text-sm font-bold text-gray-900 truncate leading-snug">
                                            {{ $prod->name }}
                                        </h4>
                                        <p class="text-xs sm:text-sm font-black text-[#4F26A6] mt-0.5">
                                            Rp {{ number_format((float)$prod->price, 0, ',', '.') }}
                                        </p>
                                        <div class="flex items-center justify-between pt-2 mt-2 border-t border-gray-200/60 text-[11px] text-gray-500">
                                            <span>{{ $prod->variants->count() }} Varian • Stok: <strong>{{ $prod->total_stock }}</strong></span>
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('seller.products.edit', $prod->id) }}" class="font-bold text-[#4F26A6] hover:underline">
                                                    Edit
                                                </a>
                                                <span class="text-gray-300">|</span>
                                                <a href="{{ route('product.detail', $prod->slug) }}" target="_blank" class="font-bold text-gray-600 hover:text-gray-900 hover:underline">
                                                    Lihat
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Sidebar Column (4 cols: Readiness Checklist, Store Profile Card & Seller Guidelines) -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Checklist Toko Siap Jual Card -->
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-7 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <h3 class="text-base font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                            <svg class="w-4.5 h-4.5 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Kesiapan Toko</span>
                        </h3>
                        @php
                            $completedChecklistCount = collect($checklist)->filter()->count();
                            $totalChecklistCount = count($checklist);
                            $checklistPercent = round(($completedChecklistCount / $totalChecklistCount) * 100);
                        @endphp
                        <span class="px-2.5 py-0.5 rounded-full bg-purple-50 text-[#4F26A6] text-xs font-extrabold">
                            {{ $checklistPercent }}% Siap
                        </span>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full h-2 bg-purple-50 rounded-full overflow-hidden">
                        <div class="h-full bg-[#4F26A6] rounded-full transition-all duration-500" style="width: {{ $checklistPercent }}%"></div>
                    </div>

                    <!-- Checklist Items -->
                    <div class="space-y-3 pt-1 text-xs">
                        <!-- Item 1: Verified Store -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span class="font-semibold text-gray-800">Toko Terverifikasi Resmi</span>
                            </div>
                            <span class="text-[11px] font-bold text-emerald-600">Aktif</span>
                        </div>

                        <!-- Item 2: Avatar & Banner -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                @if($checklist['has_avatar'] && $checklist['has_banner'])
                                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                @else
                                    <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 font-bold text-[10px]">
                                        !
                                    </span>
                                @endif
                                <span class="font-semibold text-gray-800">Banner &amp; Foto Profil</span>
                            </div>
                            <a href="{{ route('seller.settings') }}" class="text-[11px] font-bold text-[#4F26A6] hover:underline">
                                {{ $checklist['has_avatar'] && $checklist['has_banner'] ? 'Ubah' : 'Lengkapi' }}
                            </a>
                        </div>

                        <!-- Item 3: Products -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                @if($checklist['has_products'])
                                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                @else
                                    <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 font-bold text-[10px]">
                                        !
                                    </span>
                                @endif
                                <span class="font-semibold text-gray-800">Upload Produk (Min. 1)</span>
                            </div>
                            <a href="{{ route('seller.products.create') }}" class="text-[11px] font-bold text-[#4F26A6] hover:underline">
                                {{ $checklist['has_products'] ? 'Tambah' : 'Upload' }}
                            </a>
                        </div>

                        <!-- Item 4: Payout Bank Account -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                @if($checklist['has_bank'])
                                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                @else
                                    <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 font-bold text-[10px]">
                                        !
                                    </span>
                                @endif
                                <span class="font-semibold text-gray-800">Rekening Bank Pencairan</span>
                            </div>
                            <a href="{{ route('seller.settings') }}" class="text-[11px] font-bold text-[#4F26A6] hover:underline">
                                {{ $checklist['has_bank'] ? 'Detail' : 'Isi Rekening' }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Panduan & Tips Sukses Seller Card -->
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-7 space-y-4">
                    <h3 class="text-base font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                        <svg class="w-4.5 h-4.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                        <span>Tips Sukses Jualan</span>
                    </h3>

                    <div class="space-y-3.5 text-xs text-gray-600">
                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-lg bg-purple-50 text-[#4F26A6] font-bold flex items-center justify-center shrink-0 text-xs">1</span>
                            <div>
                                <strong class="text-gray-900 block mb-0.5">Deskripsi Jujur &amp; Menarik</strong>
                                <p class="text-gray-500 leading-relaxed">Sertakan detail ukuran, kondisi minus pemakaian (jika ada), dan cerita otentik di balik barang tersebut.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-lg bg-purple-50 text-[#4F26A6] font-bold flex items-center justify-center shrink-0 text-xs">2</span>
                            <div>
                                <strong class="text-gray-900 block mb-0.5">Dokumentasi Sebelum Kirim</strong>
                                <p class="text-gray-500 leading-relaxed">Foto barang saat dipacking rapi untuk bukti keamanan jika terjadi kendala kurir atau komplain pembeli.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-lg bg-purple-50 text-[#4F26A6] font-bold flex items-center justify-center shrink-0 text-xs">3</span>
                            <div>
                                <strong class="text-gray-900 block mb-0.5">Pencairan Dana Escrow Aman</strong>
                                <p class="text-gray-500 leading-relaxed">Dana escrow otomatis masuk ke Saldo Siap Cair setelah pembeli mengonfirmasi penerimaan barang.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-gray-100">
                        <a href="/panduan-seller" class="text-xs font-bold text-[#4F26A6] hover:underline flex items-center justify-between">
                            <span>Baca Panduan Lengkap Seller</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>
