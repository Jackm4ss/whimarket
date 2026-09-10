<x-layouts.app :title="$title" activeTab="seller-settings">
    <main class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10">
        <!-- Top Bar with Navigation & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
            <div>
                <a href="{{ route('seller.dashboard') }}" class="text-xs sm:text-sm font-bold text-[#4F26A6] hover:underline mb-1.5 inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Dashboard</span>
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-950 tracking-tight">
                    Pengaturan Profil & Banner Toko
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                    Perbarui foto profil, banner toko, dan deskripsi publik tokomu agar lebih menarik bagi pembeli.
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
                    href="{{ route('password.edit') }}"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 hover:text-[#4F26A6] hover:bg-gray-50 font-bold text-xs sm:text-sm transition-all flex items-center gap-2"
                >
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 11V7a5 5 0 0110 0v4"/>
                    </svg>
                    <span>Ganti Password</span>
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
            <a href="{{ route('seller.payout-account.index') }}" class="pb-3 text-sm font-semibold text-gray-500 hover:text-gray-900 px-3 whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <span>Rekening Bank</span>
            </a>
            <a href="{{ route('seller.settings') }}" class="pb-3 text-sm font-bold text-[#4F26A6] border-b-2 border-[#4F26A6] px-3 whitespace-nowrap flex items-center gap-2">
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
                    <span>Periksa input formulir:</span>
                </p>
                <ul class="list-disc list-inside text-xs mt-1 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('seller.settings.update') }}"
            method="POST"
            enctype="multipart/form-data"
            x-data="{
                avatarPreview: '',
                currentAvatar: '{{ $seller->user?->avatar ?? '' }}',
                bannerPreview: '',
                currentBanner: '{{ $seller->banner_url }}',
                removeAvatar: false,
                removeBanner: false,
                bioLength: {{ strlen(old('bio', $seller->bio ?? '')) }},
                isSubmitting: false,

                onAvatarSelected(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    this.$dispatch('open-avatar-cropper', { file: file, target: 'seller-profile' });
                },

                adjustCurrentAvatar() {
                    const current = this.avatarPreview ? this.avatarPreview : (!this.removeAvatar ? this.currentAvatar : '');
                    if (current) {
                        this.$dispatch('open-avatar-cropper', { currentUrl: current, target: 'seller-profile' });
                    } else if (this.$refs.avatarInput) {
                        this.$refs.avatarInput.click();
                    }
                },

                onAvatarCropped(detail) {
                    if (!detail.target || detail.target === 'seller-profile') {
                        this.removeAvatar = false;
                        this.avatarPreview = detail.dataUrl;
                        if (this.$refs.avatarInput && detail.file) {
                            const dt = new DataTransfer();
                            dt.items.add(detail.file);
                            this.$refs.avatarInput.files = dt.files;
                        }
                    }
                },

                onBannerSelected(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    this.removeBanner = false;
                    try {
                        this.bannerPreview = URL.createObjectURL(file);
                    } catch (err) {
                        const reader = new FileReader();
                        reader.onload = (event) => {
                            this.bannerPreview = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },

                resetAvatar() {
                    this.avatarPreview = '';
                    this.removeAvatar = true;
                    if (this.$refs.avatarInput) {
                        this.$refs.avatarInput.value = '';
                    }
                },

                resetBanner() {
                    this.bannerPreview = '';
                    this.removeBanner = true;
                    if (this.$refs.bannerInput) {
                        this.$refs.bannerInput.value = '';
                    }
                }
            }"
            @avatar-cropped.window="onAvatarCropped($event.detail)"
            @submit="isSubmitting = true"
            class="space-y-8"
        >
            @csrf
            @method('PUT')

            <input type="hidden" name="remove_avatar" :value="removeAvatar ? '1' : '0'" />
            <input type="hidden" name="remove_banner" :value="removeBanner ? '1' : '0'" />

            <!-- Hidden File Inputs -->
            <input
                type="file"
                name="avatar"
                x-ref="avatarInput"
                accept="image/jpeg,image/png,image/webp,image/jpg"
                @change="onAvatarSelected($event)"
                class="hidden"
            />
            <input
                type="file"
                name="banner"
                x-ref="bannerInput"
                accept="image/jpeg,image/png,image/webp,image/jpg"
                @change="onBannerSelected($event)"
                class="hidden"
            />

            <!-- Section 1: Visual Identity (Banner & Profile Photo) -->
            <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8 space-y-6">
                <div class="border-b border-gray-100 pb-4">
                    <h2 class="text-lg font-extrabold text-gray-950 tracking-tight flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Identitas Visual Toko</span>
                    </h2>
                    <p class="text-xs sm:text-[13px] text-gray-500 mt-1">
                        Banner dan foto profil akan tampil langsung di halaman toko resmi dan kartu profilmu.
                    </p>
                </div>

                <!-- Live Store Header Preview Card -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                            Banner Toko & Foto Profil (Live Preview)
                        </label>
                        <span class="text-[11.5px] text-gray-400 font-medium">
                            Klik tombol kamera atau area untuk mengganti
                        </span>
                    </div>

                    <!-- Banner Container -->
                    <div class="relative w-full h-[180px] sm:h-[240px] md:h-[280px] lg:h-[300px] rounded-2xl sm:rounded-3xl overflow-hidden bg-purple-900/10 border border-gray-200/80 group">
                        <!-- Banner Image -->
                        <img
                            :src="bannerPreview ? bannerPreview : (removeBanner ? '/assets/default-seller-banner.png' : currentBanner)"
                            alt="Banner Toko"
                            class="w-full h-full object-cover object-center transition-all duration-300 group-hover:brightness-95"
                        />

                        <!-- Banner Change Button Overlay (Top Right) -->
                        <div class="absolute top-4 right-4 flex items-center gap-2 z-10">
                            <button
                                type="button"
                                @click="$refs.bannerInput.click()"
                                class="px-3.5 sm:px-4 py-2 rounded-xl bg-white/95 hover:bg-white text-gray-900 font-bold text-xs sm:text-[13px] shadow-sm hover:shadow transition-all backdrop-blur-md flex items-center gap-2 cursor-pointer border border-white/60 active:scale-95"
                            >
                                <svg class="w-4 h-4 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Ganti Banner</span>
                            </button>

                            <button
                                type="button"
                                x-show="bannerPreview || (!removeBanner && currentBanner !== '/assets/default-seller-banner.png')"
                                @click="resetBanner()"
                                class="p-2 rounded-xl bg-white/95 hover:bg-white text-rose-600 hover:text-rose-700 shadow-sm transition-all backdrop-blur-md cursor-pointer border border-white/60"
                                title="Reset ke banner standar"
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>

                        <!-- Drag/Click Prompt on Banner -->
                        <div
                            @click="$refs.bannerInput.click()"
                            class="absolute inset-0 bg-black/20 opacity-0 hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white cursor-pointer"
                        >
                            <svg class="w-10 h-10 mb-1 opacity-90 drop-shadow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-xs sm:text-sm font-bold drop-shadow">Klik untuk Unggah Banner Baru</span>
                        </div>
                    </div>

                    <!-- Profile Row (Avatar + Store Info Badge) -->
                    <div class="relative px-2 sm:px-6 pt-1 sm:pt-2 z-20 flex flex-col sm:flex-row sm:items-end justify-between gap-3 sm:gap-4">
                        <div class="flex items-end gap-3.5 sm:gap-5">
                            <!-- Circular Avatar with Camera Overlay (overlapping banner bottom edge) -->
                            <div class="relative group shrink-0 -mt-12 sm:-mt-16 md:-mt-20 z-30">
                                <div
                                    @click="adjustCurrentAvatar()"
                                    class="w-22 h-22 sm:w-28 sm:h-28 md:w-32 md:h-32 rounded-full ring-4 sm:ring-[5px] ring-white shadow-xl overflow-hidden bg-white cursor-pointer group/avatar relative"
                                    title="Klik untuk mengatur posisi atau mengganti foto"
                                >
                                    <template x-if="avatarPreview">
                                        <img :src="avatarPreview" alt="{{ $seller->store_name }}" class="w-full h-full object-cover object-center group-hover/avatar:scale-105 transition-transform duration-300" />
                                    </template>
                                    <template x-if="!avatarPreview && currentAvatar && !removeAvatar">
                                        <img :src="currentAvatar" alt="{{ $seller->store_name }}" class="w-full h-full object-cover object-center group-hover/avatar:scale-105 transition-transform duration-300" />
                                    </template>
                                    <template x-if="!avatarPreview && (!currentAvatar || removeAvatar)">
                                        <div class="w-full h-full bg-[#F3EEFF] text-[#4F26A6] flex items-center justify-center font-black text-2xl sm:text-3xl">
                                            {{ strtoupper(substr($seller->store_name, 0, 1)) }}
                                        </div>
                                    </template>
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/avatar:opacity-100 transition-opacity flex flex-col items-center justify-center text-white">
                                        <svg class="w-5 h-5 drop-shadow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                        <span class="text-[9px] sm:text-[10px] font-bold mt-0.5 drop-shadow">Atur Posisi</span>
                                    </div>
                                </div>

                                <!-- Camera Button Overlay on Avatar -->
                                <button
                                    type="button"
                                    @click="$refs.avatarInput.click()"
                                    class="absolute bottom-0 right-0 sm:bottom-1 sm:right-1 w-7 h-7 sm:w-10 sm:h-10 rounded-full bg-[#4F26A6] hover:bg-[#3E1D85] text-white flex items-center justify-center shadow-lg ring-2 ring-white transition-all cursor-pointer hover:scale-105 active:scale-95"
                                    title="Pilih File Foto Profil"
                                >
                                    <svg class="w-3.5 h-3.5 sm:w-4.5 sm:h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </button>
                            </div>

                            <!-- Avatar Action Buttons & Info -->
                            <div class="pt-2 sm:pt-0 sm:pb-2 min-w-0">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <h3 class="text-base sm:text-2xl font-black text-gray-900 tracking-tight leading-tight truncate">
                                        {{ $seller->store_name }}
                                    </h3>
                                    <x-verified-badge size="md" class="w-4 h-4 sm:w-5 sm:h-5 text-[#4F26A6] shrink-0" />
                                </div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <button
                                        type="button"
                                        @click="$refs.avatarInput.click()"
                                        class="text-xs font-bold text-[#4F26A6] hover:text-[#3E1D85] hover:underline cursor-pointer"
                                    >
                                        Pilih Foto Baru
                                    </button>
                                    <span class="text-gray-300">•</span>
                                    <button
                                        type="button"
                                        @click="adjustCurrentAvatar()"
                                        class="text-xs font-bold text-[#4F26A6] hover:text-[#3E1D85] hover:underline cursor-pointer"
                                    >
                                        Sesuaikan Posisi
                                    </button>
                                    <span class="text-gray-300">•</span>
                                    <button
                                        type="button"
                                        x-show="avatarPreview || (!removeAvatar && currentAvatar)"
                                        @click="resetAvatar()"
                                        class="text-xs font-semibold text-rose-600 hover:text-rose-700 hover:underline cursor-pointer"
                                    >
                                        Hapus Foto
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Badge VIP & Status -->
                        <div class="pb-2 flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-purple-50 border border-purple-100 text-[#4F26A6] text-xs font-bold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <span>Verified Creator</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Guidelines Card -->
                <div class="p-4 sm:p-5 rounded-2xl bg-[#FAF9FC] border border-purple-100/70 text-xs sm:text-[13px] text-gray-600 space-y-2">
                    <p class="font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Panduan Format Gambar Toko</span>
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-gray-500">
                        <div class="space-y-0.5">
                            <strong class="text-gray-700 block">Banner Toko:</strong>
                            <p>Rekomendasi rasio 4:1 (1568 × 380 px atau minimal 1200 × 300 px), format JPG/PNG/WEBP, maksimal 10MB.</p>
                        </div>
                        <div class="space-y-0.5">
                            <strong class="text-gray-700 block">Foto Profil / Logo Toko:</strong>
                            <p>Rekomendasi foto rasio 1:1 (persegi, minimal 500 × 500 px), format JPG/PNG/WEBP, maksimal 5MB.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Store Information Details -->
            <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8 space-y-6">
                <div class="border-b border-gray-100 pb-4">
                    <h2 class="text-lg font-extrabold text-gray-950 tracking-tight flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Informasi & Deskripsi Toko</span>
                    </h2>
                    <p class="text-xs sm:text-[13px] text-gray-500 mt-1">
                        Informasi yang akan dibaca oleh calon pembeli saat mengunjungi tokomu.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Store Name -->
                    <div>
                        <label for="store_name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Nama Toko Resmi <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="store_name"
                            name="store_name"
                            value="{{ old('store_name', $seller->store_name) }}"
                            required
                            placeholder="Contoh: Bintang Vintage Studio"
                            class="w-full h-11 sm:h-12 px-4 rounded-xl bg-[#F9FAFB] border border-gray-200 text-xs sm:text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] transition-all"
                        />
                    </div>

                    <!-- Store Username / Slug (Read-only) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Username Toko (Slug Publik)
                        </label>
                        <div class="relative flex items-center">
                            <span class="absolute left-4 text-xs sm:text-sm font-bold text-gray-400">@</span>
                            <input
                                type="text"
                                value="{{ $seller->username }}"
                                readonly
                                disabled
                                class="w-full h-11 sm:h-12 pl-8 pr-4 rounded-xl bg-gray-100/80 border border-gray-200 text-xs sm:text-sm text-gray-600 font-semibold cursor-not-allowed select-all"
                            />
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1.5">
                            URL tokomu: <code class="text-[#4F26A6] font-semibold">{{ url('/seller/@' . $seller->username) }}</code>
                        </p>
                    </div>
                </div>

                <!-- Bio Toko -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="bio" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                            Bio Toko / Deskripsi Singkat
                        </label>
                        <span class="text-[11px] text-gray-400 font-medium">
                            <span x-text="bioLength"></span>/1000 karakter
                        </span>
                    </div>
                    <textarea
                        id="bio"
                        name="bio"
                        rows="4"
                        @input="bioLength = $event.target.value.length"
                        maxlength="1000"
                        placeholder="Ceritakan tentang tokomu, inspirasi barang pre-loved yang kamu jual, atau jaminan keaslian barang koleksimu..."
                        class="w-full p-4 rounded-xl bg-[#F9FAFB] border border-gray-200 text-xs sm:text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] transition-all leading-relaxed"
                    >{{ old('bio', $seller->bio) }}</textarea>
                </div>
            </div>

            <!-- Section 3: Bank Account Details -->
            <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8 space-y-6">
                <div class="border-b border-gray-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-extrabold text-gray-950 tracking-tight flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <span>Rekening Pencairan Saldo (Payout)</span>
                        </h2>
                        <p class="text-xs sm:text-[13px] text-gray-500 mt-1">
                            Rekening bank yang digunakan untuk menerima pencairan hasil penjualan dari Admin WhiMarket.
                        </p>
                    </div>
                    <a
                        href="{{ route('seller.payout-account.index') }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#F3EEFF] text-[#4F26A6] hover:bg-[#EADDFE] font-bold text-xs transition-colors shrink-0"
                    >
                        <span>Halaman Khusus Rekening &rarr;</span>
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label for="bank_name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nama Bank</label>
                        <input
                            type="text"
                            id="bank_name"
                            name="bank_name"
                            value="{{ old('bank_name', $seller->bank_name) }}"
                            placeholder="Contoh: BCA / Mandiri / BNI"
                            class="w-full h-11 px-4 rounded-xl bg-[#F9FAFB] border border-gray-200 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] transition-all"
                        />
                    </div>
                    <div>
                        <label for="bank_account_number" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nomor Rekening</label>
                        <input
                            type="text"
                            id="bank_account_number"
                            name="bank_account_number"
                            value="{{ old('bank_account_number', $seller->bank_account_number) }}"
                            placeholder="1234567890"
                            class="w-full h-11 px-4 rounded-xl bg-[#F9FAFB] border border-gray-200 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] transition-all"
                        />
                    </div>
                    <div>
                        <label for="bank_account_name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Atas Nama Rekening</label>
                        <input
                            type="text"
                            id="bank_account_name"
                            name="bank_account_name"
                            value="{{ old('bank_account_name', $seller->bank_account_name) }}"
                            placeholder="Nama Sesuai Buku Tabungan"
                            class="w-full h-11 px-4 rounded-xl bg-[#F9FAFB] border border-gray-200 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] transition-all"
                        />
                    </div>
                </div>
            </div>

            <!-- Sticky / Bottom Actions Bar -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200/80">
                <a
                    href="{{ route('seller.dashboard') }}"
                    class="px-5 py-3 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 font-bold text-xs sm:text-sm transition-all cursor-pointer"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    :disabled="isSubmitting"
                    class="px-7 py-3 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-xs sm:text-sm shadow-md shadow-[#4F26A6]/20 transition-all flex items-center gap-2 cursor-pointer disabled:opacity-50 active:scale-[0.99]"
                >
                    <svg x-show="!isSubmitting" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <svg x-show="isSubmitting" x-cloak class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
                </button>
            </div>
        </form>

        <!-- Reusable Circular Avatar Cropper Modal -->
        <x-avatar-cropper-modal />
    </main>
</x-layouts.app>
