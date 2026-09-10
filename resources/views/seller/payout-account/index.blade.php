<x-layouts.app :title="$title" activeTab="seller-payout-account">
    <main class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10">
        <!-- Top Bar with Navigation & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
            <div>
                <a href="{{ route('seller.dashboard') }}" class="text-xs sm:text-sm font-bold text-[#4F26A6] hover:underline mb-1.5 inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Dashboard</span>
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-950 tracking-tight">
                    Rekening Bank Pencairan Dana (Payout)
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                    Kelola rekening bank tujuan untuk pencairan otomatis saldo penjualan pesananmu melalui sistem escrow WhiMarket.
                </p>
            </div>

            <div class="flex items-center gap-3">
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
                    href="{{ route('seller.settings') }}"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 hover:text-[#4F26A6] hover:bg-gray-50 font-bold text-xs sm:text-sm transition-all flex items-center gap-2"
                >
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Pengaturan Toko</span>
                </a>
            </div>
        </div>

        <!-- Subnav Tabs (Standard Seller Navigation) -->
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
            <a href="{{ route('seller.wishlists.index') }}" class="pb-3 text-sm font-semibold text-gray-500 hover:text-gray-900 px-3 whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span>Peminat Wishlist</span>
            </a>
            <a href="{{ route('seller.payout-account.index') }}" class="pb-3 text-sm font-bold text-[#4F26A6] border-b-2 border-[#4F26A6] px-3 whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <span>Rekening Bank</span>
            </a>
            <a href="{{ route('seller.settings') }}" class="pb-3 text-sm font-semibold text-gray-500 hover:text-gray-900 px-3 whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Pengaturan Toko</span>
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm space-y-1">
                <p class="font-bold flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Terdapat kesalahan pada formulir rekening:</span>
                </p>
                <ul class="list-disc list-inside text-xs mt-1 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Payout Overview Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-[13px] font-bold text-gray-500">Total Dana Dicairkan</span>
                    <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </span>
                </div>
                <div>
                    <span class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">
                        Rp {{ number_format((float) $stats['total_paid'], 0, ',', '.') }}
                    </span>
                    <span class="text-[11px] font-medium text-gray-400 block mt-0.5">{{ $stats['paid_count'] }} penarikan berhasil</span>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-[13px] font-bold text-gray-500">Dalam Proses Transfer</span>
                    <span class="w-8 h-8 rounded-xl bg-amber-50 text-[#F59E0B] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                </div>
                <div>
                    <span class="text-xl sm:text-2xl font-black text-amber-600 tracking-tight">
                        Rp {{ number_format((float) $stats['pending_amount'], 0, ',', '.') }}
                    </span>
                    <span class="text-[11px] font-medium text-gray-400 block mt-0.5">Escrow pesanan menunggu proses admin</span>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-[13px] font-bold text-gray-500">Rekening Tujuan Utama</span>
                    <span class="w-8 h-8 rounded-xl bg-purple-50 text-[#4F26A6] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-lg sm:text-xl font-black text-gray-900 tracking-tight">
                            {{ $seller->bank_name ?: 'Belum Diatur' }}
                        </span>
                        @if($seller->bank_account_number)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Aktif
                            </span>
                        @endif
                    </div>
                    <span class="text-[11px] font-medium text-gray-400 block mt-0.5 truncate font-mono">
                        {{ $seller->bank_account_number ? 'No. ' . $seller->bank_account_number : 'Lengkapi data rekening di bawah' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Main Content Area: 2 Columns on Desktop -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Column: Virtual ATM Card & Edit Form (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Visual Realistic Bank Payout Card (E-Commerce Standard) -->
                <div class="relative overflow-hidden rounded-3xl text-white p-6 sm:p-8 shadow-[0_16px_36px_rgba(79,38,166,0.3)] border border-purple-400/30" style="background: linear-gradient(135deg, #2A085C 0%, #4F26A6 50%, #170535 100%);">
                    <!-- Background Watermark Pattern -->
                    <div class="absolute -right-10 -bottom-10 w-48 h-48 rounded-full bg-white/5 blur-2xl pointer-events-none"></div>
                    <div class="absolute -left-10 -top-10 w-48 h-48 rounded-full bg-purple-400/10 blur-xl pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col justify-between min-h-[200px] sm:min-h-[220px]">
                        <!-- Card Header: EMV Chip, NFC Wave & Bank Badge -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <!-- Metallic Gold EMV Chip -->
                                <div class="w-11 h-8 rounded-lg p-1 shadow-inner border border-amber-300 flex flex-col justify-between shrink-0" style="background: linear-gradient(135deg, #FDE68A 0%, #F59E0B 50%, #D97706 100%);">
                                    <div class="w-full h-0.5 bg-amber-900/40 rounded-full"></div>
                                    <div class="w-3/4 h-0.5 bg-amber-900/40 rounded-full"></div>
                                    <div class="w-full h-0.5 bg-amber-900/40 rounded-full"></div>
                                </div>
                                <!-- Contactless NFC Wave -->
                                <svg class="w-5 h-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.5 7.5A6.5 6.5 0 0 1 15 14m-3.5-3.5A3 3 0 0 1 13 14M5 4a10.5 10.5 0 0 1 11 11" />
                                </svg>
                            </div>

                            <!-- Bank Name Badge -->
                            <div class="px-3.5 py-1.5 rounded-xl text-xs sm:text-sm font-black tracking-wider uppercase shadow-xs" style="background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.35); backdrop-filter: blur(8px); color: #FFFFFF;">
                                {{ $seller->bank_name ?: 'WHIMARKET PAYOUT' }}
                            </div>
                        </div>

                        <!-- Card Number -->
                        <div class="my-4 sm:my-6">
                            <div class="text-[10px] sm:text-xs text-purple-200 font-semibold uppercase tracking-wider mb-1">
                                Nomor Rekening Pencairan
                            </div>
                            <div class="font-mono text-xl sm:text-2xl font-bold tracking-widest text-white drop-shadow flex items-center gap-2 flex-wrap">
                                @if(!empty($seller->bank_account_number))
                                    <span>{{ trim(chunk_split($seller->bank_account_number, 4, ' ')) }}</span>
                                @else
                                    <span class="text-purple-200 tracking-normal text-base font-sans font-medium">Belum ada nomor rekening terdaftar</span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Footer: Account Holder & Verification Status -->
                        <div class="flex items-end justify-between border-t border-white/15 pt-4">
                            <div>
                                <div class="text-[9.5px] sm:text-[10px] text-purple-200 uppercase font-semibold tracking-wider">
                                    Atas Nama Pemilik
                                </div>
                                <div class="text-sm sm:text-base font-extrabold text-white tracking-wide uppercase truncate max-w-[220px] sm:max-w-[280px]">
                                    {{ $seller->bank_account_name ?: ($seller->store_name ?: 'Nama Pemilik Rekening') }}
                                </div>
                            </div>

                            <div class="text-right">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] sm:text-[11px] font-bold" style="background: rgba(16, 185, 129, 0.25); color: #A7F3D0; border: 1px solid rgba(16, 185, 129, 0.45); backdrop-filter: blur(4px);">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span>Terverifikasi Payout</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Card: Ubah Rekening Bank -->
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8 space-y-6">
                    <div class="border-b border-gray-100 pb-4 flex items-center justify-between">
                        <div>
                            <h2 class="text-lg sm:text-xl font-extrabold text-gray-950 tracking-tight flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span>Perbarui Rekening Bank</span>
                            </h2>
                            <p class="text-xs text-gray-500 mt-1">
                                Pastikan nomor rekening dan nama pemilik sesuai dengan buku tabungan untuk kelancaran pencairan dana.
                            </p>
                        </div>
                    </div>

                    <form
                        action="{{ route('seller.payout-account.update') }}"
                        method="POST"
                        class="space-y-5"
                        x-data="{
                            selectedBank: '{{ old('bank_name', $seller->bank_name ?? 'BCA') }}',
                            accountNumber: '{{ old('bank_account_number', $seller->bank_account_number ?? '') }}',
                            accountName: '{{ old('bank_account_name', $seller->bank_account_name ?? '') }}',
                            isCustomBank: false,
                            customBankName: '',
                            init() {
                                const knownBanks = {{ Js::from(array_keys($availableBanks)) }};
                                if (this.selectedBank && !knownBanks.includes(this.selectedBank)) {
                                    this.isCustomBank = true;
                                    this.customBankName = this.selectedBank;
                                    this.selectedBank = 'OTHER';
                                }
                            },
                            selectBank(bankKey) {
                                this.selectedBank = bankKey;
                                this.isCustomBank = (bankKey === 'OTHER');
                            }
                        }"
                    >
                        @csrf
                        @method('PUT')

                        <!-- Bank Selection -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Bank Tujuan Pencairan <span class="text-rose-500">*</span>
                            </label>

                            <!-- Popular Bank Quick Pills -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 mb-3">
                                @foreach(['BCA', 'Mandiri', 'BRI', 'BNI'] as $quickBank)
                                    <button
                                        type="button"
                                        @click="selectBank('{{ $quickBank }}')"
                                        class="h-11 rounded-xl font-bold text-xs sm:text-sm border transition-all cursor-pointer flex items-center justify-center gap-1.5"
                                        :class="selectedBank === '{{ $quickBank }}' ? 'bg-[#F3EEFF] border-[#4F26A6] text-[#4F26A6] shadow-xs' : 'bg-white border-gray-200 text-gray-700 hover:border-gray-300 hover:bg-gray-50'"
                                    >
                                        <span>{{ $quickBank }}</span>
                                        <template x-if="selectedBank === '{{ $quickBank }}'">
                                            <svg class="w-3.5 h-3.5 text-[#4F26A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </template>
                                    </button>
                                @endforeach
                            </div>

                            <!-- Full Bank Select Dropdown -->
                            <div class="relative">
                                <select
                                    id="bank_selector"
                                    x-model="selectedBank"
                                    @change="isCustomBank = ($event.target.value === 'OTHER')"
                                    class="w-full h-11 sm:h-12 px-4 rounded-xl bg-[#F9FAFB] border border-gray-200 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] transition-all cursor-pointer appearance-none"
                                >
                                    <option value="" disabled>-- Pilih Bank Komersial / Syariah --</option>
                                    @foreach($availableBanks as $bankCode => $bankLabel)
                                        <option value="{{ $bankCode }}">{{ $bankLabel }}</option>
                                    @endforeach
                                    <option value="OTHER">Bank Lainnya (Ketik Manual)</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7 7"/></svg>
                                </div>
                            </div>

                            <!-- Custom Bank Name Input (if OTHER selected) -->
                            <div x-show="isCustomBank" x-cloak class="mt-3">
                                <label for="custom_bank_name" class="block text-[11px] font-bold text-gray-600 mb-1">Nama Bank Lainnya</label>
                                <input
                                    type="text"
                                    id="custom_bank_name"
                                    x-model="customBankName"
                                    placeholder="Contoh: Bank BPD DIY / Bank Jateng"
                                    class="w-full h-11 px-4 rounded-xl bg-[#F9FAFB] border border-gray-200 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] transition-all"
                                />
                            </div>

                            <!-- Real Hidden Input Submitted to Server -->
                            <input
                                type="hidden"
                                name="bank_name"
                                :value="isCustomBank ? customBankName : selectedBank"
                            />
                        </div>

                        <!-- Account Number Input -->
                        <div>
                            <label for="bank_account_number" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Nomor Rekening <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    type="text"
                                    id="bank_account_number"
                                    name="bank_account_number"
                                    x-model="accountNumber"
                                    @input="accountNumber = $event.target.value.replace(/[^0-9\-]/g, '')"
                                    required
                                    placeholder="Contoh: 1234567890"
                                    class="w-full h-11 sm:h-12 px-4 rounded-xl bg-[#F9FAFB] border border-gray-200 text-xs sm:text-sm text-gray-900 font-mono tracking-wider focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] transition-all"
                                />
                            </div>
                            <p class="text-[11px] text-gray-400 mt-1.5">
                                Masukkan nomor rekening murni tanpa spasi (hanya angka).
                            </p>
                        </div>

                        <!-- Account Holder Name Input -->
                        <div>
                            <label for="bank_account_name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Nama Pemilik Rekening <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="bank_account_name"
                                name="bank_account_name"
                                x-model="accountName"
                                required
                                placeholder="Nama lengkap sesuai buku tabungan"
                                class="w-full h-11 sm:h-12 px-4 rounded-xl bg-[#F9FAFB] border border-gray-200 text-xs sm:text-sm text-gray-900 uppercase focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] transition-all"
                            />
                            <p class="text-[11px] text-gray-400 mt-1.5">
                                Harap pastikan nama sama persis dengan yang tercantum di mutasi rekening bank Anda.
                            </p>
                        </div>

                        <!-- Legal Agreement & Security Checkbox -->
                        <div class="p-4 rounded-2xl bg-[#F9FAFB] border border-gray-200/80">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="confirm_agreement"
                                    value="1"
                                    required
                                    class="mt-0.5 w-4 h-4 rounded text-[#4F26A6] focus:ring-[#4F26A6] border-gray-300"
                                />
                                <span class="text-xs text-gray-600 leading-relaxed">
                                    Saya menyatakan bahwa rekening di atas adalah milik sah saya atau perwakilan resmi toko <strong class="text-gray-900">{{ $seller->store_name }}</strong>. Saya bertanggung jawab penuh atas kebenaran data nomor rekening untuk pencairan saldo penjualan.
                                </span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2 flex items-center justify-end">
                            <button
                                type="submit"
                                class="w-full sm:w-auto px-8 h-12 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs sm:text-sm transition-all shadow-[0_4px_16px_rgba(79,38,166,0.25)] flex items-center justify-center gap-2 cursor-pointer active:scale-98"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <span>Simpan &amp; Terapkan Rekening</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: Payout Guidelines & Recent Transactions (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Security & Payout Policy Card -->
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-7 space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-gray-100">
                        <div class="w-9 h-9 rounded-xl bg-[#F3EEFF] text-[#4F26A6] flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-gray-950">Panduan Pencairan Dana</h3>
                            <span class="text-[11px] text-gray-400">Standar keamanan rekening WhiMarket</span>
                        </div>
                    </div>

                    <div class="space-y-3.5 text-xs text-gray-600 leading-relaxed">
                        <div class="flex items-start gap-3">
                            <span class="w-5 h-5 rounded-full bg-purple-50 text-[#4F26A6] font-extrabold text-[11px] flex items-center justify-center shrink-0 mt-0.5">1</span>
                            <p>
                                <strong class="text-gray-900">Pencairan Otomatis:</strong> Dana penjualan pesanan otomatis dicairkan ke rekening aktif setelah pembeli mengonfirmasi pesanan atau batas pemeriksaan 48 jam berakhir.
                            </p>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="w-5 h-5 rounded-full bg-purple-50 text-[#4F26A6] font-extrabold text-[11px] flex items-center justify-center shrink-0 mt-0.5">2</span>
                            <p>
                                <strong class="text-gray-900">Bebas Biaya Transfer:</strong> WhiMarket tidak membebankan biaya penarikan saldo ke rekening bank manapun di seluruh Indonesia.
                            </p>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="w-5 h-5 rounded-full bg-purple-50 text-[#4F26A6] font-extrabold text-[11px] flex items-center justify-center shrink-0 mt-0.5">3</span>
                            <p>
                                <strong class="text-gray-900">Perubahan Rekening:</strong> Rekening yang kamu simpan langsung menjadi rekening tujuan utama untuk seluruh pesanan yang belum dicairkan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Recent Payout Transactions Card -->
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-7 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <div>
                            <h3 class="text-sm font-extrabold text-gray-950">Riwayat Pencairan Saldo</h3>
                            <span class="text-[11px] text-gray-400">Pencairan dana yang ditransfer oleh Admin</span>
                        </div>
                        <span class="text-xs font-bold text-[#4F26A6]">
                            {{ $recentPayouts->total() }} Transaksi
                        </span>
                    </div>

                    @if($recentPayouts->isNotEmpty())
                        <div class="divide-y divide-gray-100">
                            @foreach($recentPayouts as $payout)
                                <div class="py-3.5 first:pt-0 last:pb-0 flex items-center justify-between gap-3">
                                    <div class="space-y-0.5 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-gray-900 font-mono">
                                                #{{ $payout->order?->order_number ?? 'PO-' . $payout->id }}
                                            </span>
                                            @php
                                                $statusEnum = $payout->status;
                                                $statusConfig = match($statusEnum) {
                                                    \App\Enums\PayoutStatus::PAID => ['label' => 'Dicairkan', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                                    \App\Enums\PayoutStatus::PROCESSING => ['label' => 'Diproses', 'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
                                                    \App\Enums\PayoutStatus::FAILED => ['label' => 'Gagal', 'class' => 'bg-rose-50 text-rose-700 border-rose-200'],
                                                    default => ['label' => 'Menunggu', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                                };
                                            @endphp
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold border {{ $statusConfig['class'] }}">
                                                {{ $statusConfig['label'] }}
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-gray-400">
                                            {{ $payout->created_at->format('d M Y, H:i') }} &bull; {{ $payout->bank_details_snapshot['bank_name'] ?? $seller->bank_name }}
                                        </p>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <span class="text-xs sm:text-sm font-extrabold text-[#4F26A6] block">
                                            Rp {{ number_format((float) $payout->amount, 0, ',', '.') }}
                                        </span>
                                        @if($payout->transfer_proof_path)
                                            <a
                                                href="{{ asset('storage/' . $payout->transfer_proof_path) }}"
                                                target="_blank"
                                                class="text-[10.5px] font-bold text-gray-500 hover:text-[#4F26A6] underline inline-flex items-center gap-0.5"
                                            >
                                                <span>Bukti Transfer</span>
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($recentPayouts->hasPages())
                            <div class="pt-3 border-t border-gray-100">
                                {{ $recentPayouts->links() }}
                            </div>
                        @endif
                    @else
                        <div class="py-8 text-center space-y-2">
                            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-[#4F26A6] flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h4 class="text-xs sm:text-sm font-bold text-gray-800">Belum Ada Riwayat Pencairan</h4>
                            <p class="text-[11px] text-gray-400 max-w-xs mx-auto leading-relaxed">
                                Dana hasil penjualan akan tercatat otomatis di sini setelah pembeli menyelesaikan pesanan.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>
