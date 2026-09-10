<x-layouts.app :title="$title" activeTab="profile-settings">
    <main
        class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10"
        @avatar-cropped.window="onAvatarCropped($event.detail)"
        x-data="{
            currentTab: '{{ $activeTab ?? 'biodata' }}',
            avatarPreview: '{{ $user->avatar ?? '' }}',
            removeAvatar: false,
            
            // Address modal states
            addModalOpen: false,
            editModalOpen: false,
            deleteModalOpen: false,
            editingAddress: {
                id: null,
                recipient_name: '',
                phone: '',
                province: '',
                city: '',
                district: '',
                postal_code: '',
                full_address: '',
                is_default: false
            },
            editingPhoneDisplay: '',
            get editFullPhone() {
                const cleaned = (this.editingPhoneDisplay || '').replace(/[^0-9]/g, '');
                return cleaned.length > 0 ? '+62' + cleaned : '';
            },
            deletingAddressId: null,
            deletingAddressName: '',

            onAvatarChange(e) {
                const file = e.target.files[0];
                if (file) {
                    this.$dispatch('open-avatar-cropper', { file: file, target: 'buyer-profile' });
                }
            },
            adjustCurrentAvatar() {
                if (this.avatarPreview) {
                    this.$dispatch('open-avatar-cropper', { currentUrl: this.avatarPreview, target: 'buyer-profile' });
                } else if (this.$refs.avatarInput) {
                    this.$refs.avatarInput.click();
                }
            },
            onAvatarCropped(detail) {
                if (!detail.target || detail.target === 'buyer-profile') {
                    this.removeAvatar = false;
                    this.avatarPreview = detail.dataUrl;
                    if (this.$refs.avatarInput && detail.file) {
                        const dt = new DataTransfer();
                        dt.items.add(detail.file);
                        this.$refs.avatarInput.files = dt.files;
                    }
                }
            },
            deleteAvatar() {
                this.avatarPreview = '';
                this.removeAvatar = true;
                if (this.$refs.avatarInput) {
                    this.$refs.avatarInput.value = '';
                }
            },
            openEditModal(addr) {
                this.editingAddress = { ...addr };
                let p = addr.phone || '';
                let d = p.replace(/[^0-9]/g, '');
                if (d.startsWith('62')) d = d.substring(2);
                else if (d.startsWith('0')) d = d.substring(1);
                this.editingPhoneDisplay = d;
                this.editModalOpen = true;
                this.$dispatch('load-edit-address', addr);
            },
            handleEditPhoneInput(e) {
                let val = e.target.value.replace(/[^0-9+]/g, '');
                if (val.startsWith('+62')) {
                    val = val.substring(3);
                } else if (val.startsWith('62')) {
                    val = val.substring(2);
                } else if (val.startsWith('0')) {
                    val = val.substring(1);
                }
                val = val.replace(/[^0-9]/g, '');
                this.editingPhoneDisplay = val;
                e.target.value = val;
            },
            promptDeleteAddress(id, name) {
                this.deletingAddressId = id;
                this.deletingAddressName = name;
                this.deleteModalOpen = true;
            }
        }"
    >
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs sm:text-[13px] text-gray-500 font-medium mb-6">
            <a href="/" class="hover:text-[#4F26A6] transition-colors cursor-pointer">Beranda</a>
            <span class="text-gray-300 font-normal">&gt;</span>
            <span class="text-gray-500">Akun Saya</span>
            <span class="text-gray-300 font-normal">&gt;</span>
            <span class="text-gray-900 font-bold">Pengaturan Profil &amp; Alamat</span>
        </nav>

        <!-- Top Header Card -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-950 tracking-tight">
                    Pengaturan Akun
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    Kelola data pribadi, foto avatar, dan alamat pengiriman belanja kamu agar proses transaksi selalu otomatis.
                </p>
            </div>

            <div class="flex items-center gap-2.5 shrink-0">
                <a
                    href="{{ route('password.edit') }}"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 hover:border-[#4F26A6] text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF]/40 text-xs sm:text-sm font-bold transition-all flex items-center gap-2"
                >
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 11V7a5 5 0 0110 0v4"/>
                    </svg>
                    <span>Ganti Password</span>
                </a>
            </div>
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
                <p class="font-bold flex items-center gap-2 text-xs sm:text-sm">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Terdapat kesalahan pengisian data:</span>
                </p>
                <ul class="list-disc list-inside text-xs mt-1 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Layout with Tabs -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.03)] overflow-hidden">
            <!-- Tabs Bar -->
            <div class="flex items-center gap-2 sm:gap-6 px-6 sm:px-8 border-b border-gray-100 bg-[#FAF9FC]">
                <button
                    type="button"
                    @click="currentTab = 'biodata'"
                    class="py-4 px-2 text-xs sm:text-sm font-extrabold transition-all border-b-2 cursor-pointer flex items-center gap-2"
                    :class="currentTab === 'biodata' ? 'border-[#4F26A6] text-[#4F26A6]' : 'border-transparent text-gray-500 hover:text-gray-900'"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Biodata Diri</span>
                </button>

                <button
                    type="button"
                    @click="currentTab = 'alamat'"
                    class="py-4 px-2 text-xs sm:text-sm font-extrabold transition-all border-b-2 cursor-pointer flex items-center gap-2"
                    :class="currentTab === 'alamat' ? 'border-[#4F26A6] text-[#4F26A6]' : 'border-transparent text-gray-500 hover:text-gray-900'"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Daftar Alamat ({{ $addresses->count() }})</span>
                </button>
            </div>

            <!-- ==================== TAB 1: BIODATA DIRI ==================== -->
            <div x-show="currentTab === 'biodata'" class="p-6 sm:p-8 md:p-10">
                <form action="{{ route('profile.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="remove_avatar" :value="removeAvatar ? '1' : '0'" />

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                        <!-- Left Column: Avatar Management -->
                        <div class="lg:col-span-4 flex flex-col items-center text-center p-6 bg-[#FAF9FC] rounded-2xl border border-gray-100">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Foto Profil</span>

                            <!-- Avatar Stage -->
                            <div
                                @click="adjustCurrentAvatar()"
                                class="relative w-28 h-28 sm:w-32 sm:h-32 rounded-full overflow-hidden bg-white ring-4 ring-purple-100 shadow-md flex items-center justify-center mb-4 cursor-pointer group transition-all hover:ring-[#4F26A6]/40"
                                title="Klik untuk mengatur posisi foto atau memilih foto baru"
                            >
                                <template x-if="avatarPreview">
                                    <div class="relative w-full h-full">
                                        <img :src="avatarPreview" alt="{{ $user->name }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white">
                                            <svg class="w-5 h-5 drop-shadow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span class="text-[10px] font-bold mt-1 drop-shadow">Atur Posisi</span>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="!avatarPreview">
                                    <div class="w-full h-full bg-[#F3EEFF] text-[#4F26A6] flex flex-col items-center justify-center font-black text-3xl group-hover:bg-[#EAE1FF] transition-colors">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                </template>
                            </div>

                            <!-- Upload & Remove Buttons -->
                            <div class="w-full space-y-2">
                                <label class="w-full py-2.5 px-4 rounded-xl bg-white border border-gray-200 hover:border-[#4F26A6] text-[#4F26A6] text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Pilih Foto Baru</span>
                                    <input
                                        type="file"
                                        name="avatar"
                                        accept="image/jpeg,image/png,image/jpg,image/webp"
                                        x-ref="avatarInput"
                                        @change="onAvatarChange($event)"
                                        class="hidden"
                                    />
                                </label>

                                <button
                                    type="button"
                                    x-show="avatarPreview"
                                    @click="adjustCurrentAvatar()"
                                    class="w-full py-2 px-4 rounded-xl bg-purple-50 hover:bg-purple-100 text-[#4F26A6] text-xs font-bold transition-colors cursor-pointer flex items-center justify-center gap-1.5"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                    </svg>
                                    <span>Sesuaikan Posisi Foto</span>
                                </button>

                                <button
                                    type="button"
                                    x-show="avatarPreview"
                                    @click="deleteAvatar()"
                                    class="w-full py-2 px-4 rounded-xl text-rose-600 hover:bg-rose-50 text-xs font-bold transition-colors cursor-pointer"
                                >
                                    Hapus Foto
                                </button>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-4 leading-relaxed">
                                Format: JPG, PNG, WEBP.<br>Ukuran maksimal 5MB.
                            </p>
                        </div>

                        <!-- Right Column: Profile Form Fields -->
                        <div class="lg:col-span-8 space-y-5 text-left">
                            <div>
                                <label for="name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Nama Lengkap <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name', $user->name) }}"
                                    required
                                    placeholder="Contoh: Budi Pratama"
                                    class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"
                                />
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Alamat Email <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', $user->email) }}"
                                    required
                                    placeholder="nama@email.com"
                                    class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"
                                />
                            </div>

                            @php
                                $rawUserPhone = old('phone', $user->phone ?? '');
                                $initialPhoneDigits = '';
                                if (!empty($rawUserPhone)) {
                                    $digits = preg_replace('/[^0-9]/', '', $rawUserPhone);
                                    if (str_starts_with($digits, '62')) {
                                        $initialPhoneDigits = substr($digits, 2);
                                    } elseif (str_starts_with($digits, '0')) {
                                        $initialPhoneDigits = substr($digits, 1);
                                    } else {
                                        $initialPhoneDigits = $digits;
                                    }
                                }
                            @endphp

                            <div
                                x-data="{
                                    phoneDisplay: '{{ $initialPhoneDigits }}',
                                    get fullPhone() {
                                        const cleaned = (this.phoneDisplay || '').replace(/[^0-9]/g, '');
                                        return cleaned.length > 0 ? '+62' + cleaned : '';
                                    },
                                    handleInput(e) {
                                        let val = e.target.value.replace(/[^0-9+]/g, '');
                                        if (val.startsWith('+62')) {
                                            val = val.substring(3);
                                        } else if (val.startsWith('62')) {
                                            val = val.substring(2);
                                        } else if (val.startsWith('0')) {
                                            val = val.substring(1);
                                        }
                                        val = val.replace(/[^0-9]/g, '');
                                        this.phoneDisplay = val;
                                        e.target.value = val;
                                    },
                                    clearPhone() {
                                        this.phoneDisplay = '';
                                        this.$refs.phoneInput.focus();
                                    }
                                }"
                            >
                                <label for="phone_input" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Nomor Handphone / WhatsApp
                                </label>

                                <!-- Hidden input for form submission with full normalized E.164 phone -->
                                <input
                                    type="hidden"
                                    id="phone"
                                    name="phone"
                                    :value="fullPhone"
                                    value="{{ !empty($rawUserPhone) ? ($initialPhoneDigits ? '+62' . $initialPhoneDigits : '') : '' }}"
                                />

                                <!-- E-commerce style +62 Prefix Input Container -->
                                <div class="relative flex items-stretch rounded-2xl border border-gray-200 bg-white transition-all overflow-hidden focus-within:border-[#4F26A6] focus-within:ring-2 focus-within:ring-[#4F26A6]/20">
                                    <!-- Country Code Badge (+62) -->
                                    <div class="flex items-center gap-2 px-3.5 sm:px-4 py-3 bg-gray-50 border-r border-gray-200 text-gray-700 select-none shrink-0">
                                        <!-- Indonesia Flag Icon -->
                                        <div class="w-5 h-3.5 rounded-xs overflow-hidden shadow-2xs border border-gray-300 flex flex-col shrink-0" title="Indonesia">
                                            <div class="h-1/2 w-full bg-[#E70011]"></div>
                                            <div class="h-1/2 w-full bg-white"></div>
                                        </div>
                                        <span class="text-sm font-extrabold text-gray-900 tracking-tight">+62</span>
                                    </div>

                                    <!-- Phone Number Input -->
                                    <input
                                        type="tel"
                                        id="phone_input"
                                        x-ref="phoneInput"
                                        x-model="phoneDisplay"
                                        value="{{ $initialPhoneDigits }}"
                                        @input="handleInput($event)"
                                        @paste="setTimeout(() => handleInput({ target: $refs.phoneInput }), 0)"
                                        oninput="if(this.value.startsWith('+62')) this.value=this.value.slice(3); else if(this.value.startsWith('62')) this.value=this.value.slice(2); else if(this.value.startsWith('0')) this.value=this.value.slice(1); this.value=this.value.replace(/[^0-9]/g,''); var h=document.getElementById('phone'); if(h) h.value = this.value ? '+62' + this.value : ''; var p=document.getElementById('saved_phone_preview'); if(p) p.textContent = this.value ? '+62' + this.value : '';"
                                        placeholder="812-3456-7890"
                                        maxlength="15"
                                        class="w-full bg-transparent px-4 py-3 text-sm text-gray-900 font-medium placeholder:text-gray-400 focus:outline-none tracking-wide"
                                        autocomplete="tel-national"
                                    />

                                    <!-- Clear Button (when input has value) -->
                                    <div class="flex items-center pr-3" x-show="phoneDisplay" x-cloak>
                                        <button
                                            type="button"
                                            @click="clearPhone()"
                                            class="w-6 h-6 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-400 hover:text-gray-600 flex items-center justify-center transition-colors text-xs font-bold"
                                            title="Hapus nomor"
                                        >
                                            &times;
                                        </button>
                                    </div>
                                </div>

                                <!-- Helper Text with Live Saved Preview -->
                                <div class="mt-1.5 flex items-center justify-between text-[11.5px] text-gray-400">
                                    <span>Digunakan kurir ekspedisi untuk konfirmasi pengantaran & notifikasi WhatsApp.</span>
                                    <span class="font-mono text-gray-500 font-bold tracking-tight hidden sm:inline" :class="phoneDisplay ? '' : 'opacity-0'">
                                        Format tersimpan: <span id="saved_phone_preview" class="text-[#4F26A6]" x-text="fullPhone">{{ !empty($initialPhoneDigits) ? '+62' . $initialPhoneDigits : '' }}</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Account Info Badges -->
                            <div class="pt-3 border-t border-gray-100 flex items-center gap-4 flex-wrap">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-500 font-medium">Status Akun:</span>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-[#F3EEFF] text-[#4F26A6]">
                                        {{ ucfirst($user->role?->value ?? 'Buyer') }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-500 font-medium">Bergabung:</span>
                                    <span class="text-xs text-gray-900 font-bold">
                                        {{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button
                                    type="submit"
                                    class="px-8 py-3 rounded-2xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white text-xs sm:text-sm font-bold shadow-md shadow-[#4F26A6]/25 transition-all cursor-pointer"
                                >
                                    Simpan Perubahan Profil
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- ==================== TAB 2: DAFTAR ALAMAT ==================== -->
            <div x-show="currentTab === 'alamat'" x-cloak class="p-6 sm:p-8 md:p-10">
                <!-- Top Action Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-base sm:text-lg font-extrabold text-gray-950">
                            Alamat Pengiriman Kamu
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Pilih satu alamat utama yang akan otomatis terpakai saat proses checkout pesanan.
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="addModalOpen = true"
                        class="px-5 py-2.5 rounded-2xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white text-xs sm:text-sm font-bold shadow-md shadow-[#4F26A6]/20 transition-all flex items-center justify-center gap-2 cursor-pointer self-start sm:self-auto"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Alamat Baru</span>
                    </button>
                </div>

                @if($addresses->isEmpty())
                    <!-- Empty State -->
                    <div class="py-12 px-6 text-center max-w-md mx-auto">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-3xl bg-[#F3EEFF] text-[#4F26A6] flex items-center justify-center shadow-2xs">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-base font-extrabold text-gray-900 mb-1">Belum Ada Alamat Tersimpan</h4>
                        <p class="text-xs text-gray-500 mb-6 leading-relaxed">
                            Tambahkan alamat tempat tinggalmu sekarang agar saat menemukan barang incaran, kamu bisa checkout dalam satu klik.
                        </p>
                        <button
                            type="button"
                            @click="addModalOpen = true"
                            class="px-6 py-2.5 rounded-2xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white text-xs font-bold transition-all shadow-xs cursor-pointer"
                        >
                            + Tambah Alamat Sekarang
                        </button>
                    </div>
                @else
                    <!-- Address List Cards -->
                    <div class="space-y-4">
                        @foreach($addresses as $addr)
                            <div
                                class="p-5 sm:p-6 rounded-2xl sm:rounded-3xl border transition-all flex flex-col sm:flex-row sm:items-start justify-between gap-5 {{ $addr->is_default ? 'border-2 border-[#4F26A6] bg-[#FAF9FC]' : 'border-gray-200/90 hover:border-gray-300 bg-white' }}"
                            >
                                <div class="space-y-1.5 flex-1 min-w-0">
                                    <div class="flex items-center gap-2.5 flex-wrap">
                                        <h4 class="font-extrabold text-sm sm:text-base text-gray-900">{{ $addr->recipient_name }}</h4>
                                        <span class="text-xs text-gray-400 font-mono">({{ $addr->phone }})</span>
                                        @if($addr->is_default)
                                            <span class="px-2.5 py-0.5 rounded-md bg-[#4F26A6] text-white text-[10.5px] font-extrabold shadow-2xs">
                                                Alamat Utama
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-xs sm:text-[13.5px] text-gray-700 leading-relaxed font-normal">
                                        {{ $addr->full_address }}
                                    </p>

                                    <p class="text-xs text-gray-500 font-medium">
                                        {{ $addr->district ? $addr->district . ', ' : '' }}{{ $addr->city }}, {{ $addr->province }} {{ $addr->postal_code }}
                                    </p>
                                </div>

                                <!-- Action Buttons on Card -->
                                <div class="flex items-center gap-2 sm:self-center shrink-0 flex-wrap">
                                    @if(!$addr->is_default)
                                        <form action="{{ route('addresses.default', $addr->id) }}" method="POST">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="px-3.5 py-2 rounded-xl border border-gray-200 hover:border-[#4F26A6] text-gray-700 hover:text-[#4F26A6] text-xs font-bold transition-all cursor-pointer hover:bg-[#F3EEFF]/40"
                                            >
                                                Jadikan Utama
                                            </button>
                                        </form>
                                    @endif

                                    <button
                                        type="button"
                                        @click="openEditModal({
                                            id: {{ $addr->id }},
                                            recipient_name: '{{ addslashes($addr->recipient_name) }}',
                                            phone: '{{ addslashes($addr->phone) }}',
                                            province: '{{ addslashes($addr->province) }}',
                                            city: '{{ addslashes($addr->city) }}',
                                            district: '{{ addslashes($addr->district ?? '') }}',
                                            postal_code: '{{ addslashes($addr->postal_code ?? '') }}',
                                            full_address: '{{ addslashes($addr->full_address) }}',
                                            is_default: {{ $addr->is_default ? 'true' : 'false' }}
                                        })"
                                        class="px-3.5 py-2 rounded-xl border border-gray-200 hover:border-gray-400 text-gray-700 text-xs font-bold transition-all cursor-pointer hover:bg-gray-50"
                                    >
                                        Ubah
                                    </button>

                                    <button
                                        type="button"
                                        @click="promptDeleteAddress({{ $addr->id }}, '{{ addslashes($addr->recipient_name) }}')"
                                        class="p-2 rounded-xl text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                                        title="Hapus Alamat"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- ==================== MODAL TAMBAH ALAMAT ==================== -->
        <div
            x-show="addModalOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-black/60 backdrop-blur-xs overflow-y-auto"
        >
            <div
                @click.outside="addModalOpen = false"
                style="max-height: 90dvh;"
                class="bg-white rounded-3xl border border-gray-100 shadow-[0_25px_60px_rgba(79,38,166,0.22)] max-w-lg w-full p-4 sm:p-6 md:p-8 relative flex flex-col min-h-0 overflow-hidden my-auto"
            >
                <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-100 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#4F26A6] flex items-center justify-center">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-extrabold text-gray-900">Tambah Alamat Pengiriman</h3>
                    </div>
                    <button type="button" @click="addModalOpen = false" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition-colors cursor-pointer" title="Tutup">✕</button>
                </div>

                <form action="{{ route('addresses.store') }}" method="POST" class="flex flex-col flex-1 min-h-0 overflow-hidden text-left">
                    @csrf
                    <div class="space-y-3.5 flex-1 min-h-0 overflow-y-auto pr-1 sm:pr-2 overscroll-contain">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Nama Penerima <span class="text-rose-500">*</span></label>
                        <input type="text" name="recipient_name" required placeholder="Contoh: Budi Pratama" class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"/>
                    </div>
                    <div
                        x-data="{
                            phoneDisplay: '',
                            get fullPhone() {
                                const cleaned = (this.phoneDisplay || '').replace(/[^0-9]/g, '');
                                return cleaned.length > 0 ? '+62' + cleaned : '';
                            },
                            handleInput(e) {
                                let val = e.target.value.replace(/[^0-9+]/g, '');
                                if (val.startsWith('+62')) {
                                    val = val.substring(3);
                                } else if (val.startsWith('62')) {
                                    val = val.substring(2);
                                } else if (val.startsWith('0')) {
                                    val = val.substring(1);
                                }
                                val = val.replace(/[^0-9]/g, '');
                                this.phoneDisplay = val;
                                e.target.value = val;
                            }
                        }"
                    >
                        <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">No. Handphone / WhatsApp <span class="text-rose-500">*</span></label>
                        <input type="hidden" name="phone" :value="fullPhone" />
                        <div class="relative flex items-stretch rounded-xl border border-gray-200 bg-white transition-all overflow-hidden focus-within:border-[#4F26A6] focus-within:ring-2 focus-within:ring-[#4F26A6]/20">
                            <div class="flex items-center gap-1.5 px-3 py-2.5 bg-gray-50 border-r border-gray-200 text-gray-700 select-none shrink-0">
                                <div class="w-4 h-3 rounded-xs overflow-hidden shadow-2xs border border-gray-300 flex flex-col shrink-0" title="Indonesia">
                                    <div class="h-1/2 w-full bg-[#E70011]"></div>
                                    <div class="h-1/2 w-full bg-white"></div>
                                </div>
                                <span class="text-xs sm:text-sm font-extrabold text-gray-900 tracking-tight">+62</span>
                            </div>
                            <input
                                type="tel"
                                x-model="phoneDisplay"
                                @input="handleInput($event)"
                                @paste="setTimeout(() => handleInput({ target: $el }), 0)"
                                placeholder="812-3456-7890"
                                maxlength="15"
                                required
                                class="w-full bg-transparent px-3 py-2.5 text-sm text-gray-900 font-medium placeholder:text-gray-400 focus:outline-none tracking-wide"
                                autocomplete="tel-national"
                            />
                            <div class="flex items-center pr-2.5" x-show="phoneDisplay" x-cloak>
                                <button
                                    type="button"
                                    @click="phoneDisplay = ''"
                                    class="w-5 h-5 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-400 hover:text-gray-600 flex items-center justify-center transition-colors text-xs font-bold cursor-pointer"
                                    title="Hapus nomor"
                                >
                                    &times;
                                 </button>
                            </div>
                        </div>
                    </div>
                    <div x-data="regionSelectorComponent()">
                        <x-region-select-fields />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Alamat Lengkap <span class="text-rose-500">*</span></label>
                        <textarea name="full_address" rows="2" required placeholder="Nama Jalan, Gedung, No. Rumah, RT/RW" class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none resize-none"></textarea>
                    </div>

                    <label class="flex items-center gap-2 pt-1 cursor-pointer select-none">
                        <input type="checkbox" name="is_default" value="1" class="w-4 h-4 rounded text-[#4F26A6] accent-[#4F26A6] focus:ring-[#4F26A6]" />
                        <span class="text-xs text-gray-700 font-semibold">Jadikan sebagai alamat pengiriman utama</span>
                    </label>
                    </div>

                    <div class="pt-3 mt-3 sm:pt-4 sm:mt-4 border-t border-gray-100 flex items-center justify-end gap-3 shrink-0">
                        <button type="button" @click="addModalOpen = false" class="px-4 py-2.5 text-xs font-semibold text-gray-500 hover:text-gray-800 transition-colors cursor-pointer">Batal</button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white text-xs font-bold shadow-md shadow-[#4F26A6]/20 transition-all cursor-pointer">Simpan Alamat</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ==================== MODAL UBAH ALAMAT ==================== -->
        <div
            x-show="editModalOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-black/60 backdrop-blur-xs overflow-y-auto"
        >
            <div
                @click.outside="editModalOpen = false"
                style="max-height: 90dvh;"
                class="bg-white rounded-3xl border border-gray-100 shadow-[0_25px_60px_rgba(79,38,166,0.22)] max-w-lg w-full p-4 sm:p-6 md:p-8 relative flex flex-col min-h-0 overflow-hidden my-auto"
            >
                <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-100 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#4F26A6] flex items-center justify-center">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-extrabold text-gray-900">Ubah Alamat Pengiriman</h3>
                    </div>
                    <button type="button" @click="editModalOpen = false" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition-colors cursor-pointer" title="Tutup">✕</button>
                </div>

                <form :action="'/akun/alamat/' + editingAddress.id" method="POST" class="flex flex-col flex-1 min-h-0 overflow-hidden text-left">
                    @csrf
                    @method('PUT')
                    <div class="space-y-3.5 flex-1 min-h-0 overflow-y-auto pr-1 sm:pr-2 overscroll-contain">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Nama Penerima <span class="text-rose-500">*</span></label>
                        <input type="text" name="recipient_name" x-model="editingAddress.recipient_name" required class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">No. Handphone / WhatsApp <span class="text-rose-500">*</span></label>
                        <input type="hidden" name="phone" :value="editFullPhone" />
                        <div class="relative flex items-stretch rounded-xl border border-gray-200 bg-white transition-all overflow-hidden focus-within:border-[#4F26A6] focus-within:ring-2 focus-within:ring-[#4F26A6]/20">
                            <div class="flex items-center gap-1.5 px-3 py-2.5 bg-gray-50 border-r border-gray-200 text-gray-700 select-none shrink-0">
                                <div class="w-4 h-3 rounded-xs overflow-hidden shadow-2xs border border-gray-300 flex flex-col shrink-0" title="Indonesia">
                                    <div class="h-1/2 w-full bg-[#E70011]"></div>
                                    <div class="h-1/2 w-full bg-white"></div>
                                </div>
                                <span class="text-xs sm:text-sm font-extrabold text-gray-900 tracking-tight">+62</span>
                            </div>
                            <input
                                type="tel"
                                x-model="editingPhoneDisplay"
                                @input="handleEditPhoneInput($event)"
                                @paste="setTimeout(() => handleEditPhoneInput({ target: $el }), 0)"
                                placeholder="812-3456-7890"
                                maxlength="15"
                                required
                                class="w-full bg-transparent px-3 py-2.5 text-sm text-gray-900 font-medium placeholder:text-gray-400 focus:outline-none tracking-wide"
                                autocomplete="tel-national"
                            />
                            <div class="flex items-center pr-2.5" x-show="editingPhoneDisplay" x-cloak>
                                <button
                                    type="button"
                                    @click="editingPhoneDisplay = ''"
                                    class="w-5 h-5 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-400 hover:text-gray-600 flex items-center justify-center transition-colors text-xs font-bold cursor-pointer"
                                    title="Hapus nomor"
                                >
                                    &times;
                                </button>
                            </div>
                        </div>
                    </div>
                    <div
                        x-data="regionSelectorComponent({
                            province: editingAddress.province,
                            city: editingAddress.city,
                            district: editingAddress.district,
                            postal_code: editingAddress.postal_code
                        })"
                        @load-edit-address.window="matchAndLoadProvince($event.detail.province); selectedCity = $event.detail.city; selectedDistrict = $event.detail.district; selectedPostalCode = $event.detail.postal_code"
                    >
                        <x-region-select-fields />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Alamat Lengkap <span class="text-rose-500">*</span></label>
                        <textarea name="full_address" rows="2" x-model="editingAddress.full_address" required class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none resize-none"></textarea>
                    </div>

                    <label class="flex items-center gap-2 pt-1 cursor-pointer select-none">
                        <input type="checkbox" name="is_default" value="1" :checked="editingAddress.is_default" class="w-4 h-4 rounded text-[#4F26A6] accent-[#4F26A6] focus:ring-[#4F26A6]" />
                        <span class="text-xs text-gray-700 font-semibold">Jadikan sebagai alamat pengiriman utama</span>
                    </label>
                    </div>

                    <div class="pt-3 mt-3 sm:pt-4 sm:mt-4 border-t border-gray-100 flex items-center justify-end gap-3 shrink-0">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2.5 text-xs font-semibold text-gray-500 hover:text-gray-800 transition-colors cursor-pointer">Batal</button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white text-xs font-bold shadow-md shadow-[#4F26A6]/20 transition-all cursor-pointer">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ==================== MODAL HAPUS ALAMAT ==================== -->
        <div
            x-show="deleteModalOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs overflow-y-auto"
        >
            <div
                @click.outside="deleteModalOpen = false"
                x-show="deleteModalOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                class="bg-white rounded-3xl overflow-hidden max-w-md w-full shadow-[0_25px_60px_rgba(79,38,166,0.22)] border border-gray-100 relative my-8 text-center"
            >
                <!-- Modal Banner Header with Generated Illustration -->
                <div class="relative h-32 sm:h-36 w-full overflow-hidden bg-gradient-to-br from-[#4F26A6] to-[#E11D48] flex items-center justify-center">
                    <img
                        src="/assets/modals/action-delete-header.png"
                        alt="Hapus Alamat"
                        class="w-full h-full object-cover mix-blend-luminosity opacity-45 absolute inset-0"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-transparent to-transparent"></div>

                    <button
                        type="button"
                        @click="deleteModalOpen = false"
                        class="absolute top-3.5 right-3.5 z-20 w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-md text-white flex items-center justify-center transition-all cursor-pointer"
                        title="Tutup"
                    >
                        ✕
                    </button>

                    <div class="relative z-10 text-center px-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-white text-[11px] font-extrabold mb-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Daftar Alamat Akun
                        </span>
                        <h3 class="text-lg sm:text-xl font-black text-white tracking-tight">Hapus Alamat Ini?</h3>
                    </div>
                </div>

                <div class="p-6 sm:p-7 space-y-5 text-left">
                    <div class="p-4 rounded-2xl bg-[#FAF9FC] border border-gray-100/90 shadow-2xs">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Penerima:</p>
                        <h4 class="text-sm font-extrabold text-gray-900" x-text="deletingAddressName"></h4>
                    </div>

                    <p class="text-xs sm:text-[13px] text-gray-500 leading-relaxed text-center">
                        Alamat ini akan dihapus dari daftar akunmu dan tidak dapat digunakan lagi untuk pengiriman pesanan.
                    </p>

                    <form :action="'/akun/alamat/' + deletingAddressId" method="POST" class="grid grid-cols-2 gap-3 pt-1">
                        @csrf
                        @method('DELETE')
                        <button
                            type="button"
                            @click="deleteModalOpen = false"
                            class="w-full py-3 rounded-2xl border border-gray-200 hover:bg-gray-100 text-gray-700 font-bold text-xs sm:text-sm transition-all cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="w-full py-3 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-rose-600/25 transition-all cursor-pointer"
                        >
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reusable Circular Avatar Cropper Modal -->
        <x-avatar-cropper-modal />
    </main>
</x-layouts.app>
