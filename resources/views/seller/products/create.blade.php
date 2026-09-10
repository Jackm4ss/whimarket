<x-layouts.app :title="$title" activeTab="seller-products">
    <main class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10">
        <!-- Top Bar -->
        <div class="flex items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
            <div>
                <a href="{{ route('seller.products.index') }}" class="text-xs sm:text-sm font-bold text-[#4F26A6] hover:underline mb-1 inline-block">
                    &larr; Kembali ke Katalog
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-950 tracking-tight">
                    Tambah Produk Baru
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                    Lengkapi detail informasi barang pre-loved atau merchandise tokomu.
                </p>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm space-y-1">
                <p class="font-bold">Periksa input formulir:</p>
                <ul class="list-disc list-inside text-xs">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('seller.products.store') }}"
            method="POST"
            enctype="multipart/form-data"
            x-data="{
                price: {{ old('price', 150000) }},
                variants: [
                    { name: 'All Size', price: {{ old('price', 150000) }}, stock: 5 }
                ],
                files: [],
                previews: [],
                isDragging: false,
                errorMessage: '',
                addVariant() {
                    this.variants.push({ name: '', price: this.price, stock: 1 });
                },
                removeVariant(idx) {
                    if (this.variants.length > 1) {
                        this.variants.splice(idx, 1);
                    }
                },
                triggerFileInput() {
                    this.$refs.fileInput.click();
                },
                handleFileChange(event) {
                    const selected = Array.from(event.target.files);
                    this.addFiles(selected);
                },
                handleDrop(event) {
                    this.isDragging = false;
                    const dropped = Array.from(event.dataTransfer.files).filter(f => f.type.startsWith('image/'));
                    this.addFiles(dropped);
                },
                addFiles(newFiles) {
                    this.errorMessage = '';
                    if (this.files.length + newFiles.length > 5) {
                        this.errorMessage = 'Maksimal 5 foto produk.';
                    }
                    const validFiles = newFiles.filter(f => {
                        const isValidType = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'].includes(f.type);
                        const isValidSize = f.size <= 5 * 1024 * 1024;
                        if (!isValidType) this.errorMessage = 'Hanya format JPG, PNG, atau WEBP yang didukung.';
                        if (!isValidSize) this.errorMessage = 'Ukuran setiap foto maksimal 5MB.';
                        return isValidType && isValidSize;
                    });
                    const availableSlots = 5 - this.files.length;
                    const toAdd = validFiles.slice(0, availableSlots);
                    toAdd.forEach(file => {
                        this.files.push(file);
                        this.previews.push({
                            url: URL.createObjectURL(file),
                            name: file.name,
                            size: (file.size / 1024).toFixed(0) + ' KB'
                        });
                    });
                    this.syncFileInput();
                },
                removePhoto(index) {
                    if (this.previews[index]) {
                        URL.revokeObjectURL(this.previews[index].url);
                    }
                    this.files.splice(index, 1);
                    this.previews.splice(index, 1);
                    this.syncFileInput();
                },
                setPrimaryPhoto(index) {
                    if (index > 0 && index < this.files.length) {
                        const file = this.files.splice(index, 1)[0];
                        const preview = this.previews.splice(index, 1)[0];
                        this.files.unshift(file);
                        this.previews.unshift(preview);
                        this.syncFileInput();
                    }
                },
                syncFileInput() {
                    const dt = new DataTransfer();
                    this.files.forEach(f => dt.items.add(f));
                    this.$refs.fileInput.files = dt.files;
                }
            }"
            class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start"
        >
            @csrf

            <!-- Left Form Column (8 cols) -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Basic Info Card -->
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8 space-y-5">
                    <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Informasi Dasar Produk</h3>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nama Produk <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Contoh: Jaket Denim Vintage Tour 2024"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Kategori <span class="text-rose-500">*</span></label>
                            <select
                                name="category_id"
                                required
                                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"
                            >
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Kondisi Barang <span class="text-rose-500">*</span></label>
                            <select
                                name="condition"
                                required
                                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"
                            >
                                @foreach($conditions as $cond)
                                    <option value="{{ $cond->value }}">{{ $cond->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Deskripsi Produk &amp; Cerita Personal <span class="text-rose-500">*</span></label>
                        <textarea
                            name="description"
                            rows="4"
                            placeholder="Ceritakan asal-usul barang, kapan dipakai, ukuran, dan kondisi detailnya..."
                            required
                            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none resize-none"
                        >{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Harga Dasar (Rp) <span class="text-rose-500">*</span></label>
                        <input
                            type="number"
                            name="price"
                            x-model="price"
                            min="1000"
                            step="1000"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-bold text-[#4F26A6] focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"
                        />
                    </div>
                </div>

                <!-- Product Images Upload Card -->
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8 space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 border-b border-gray-100 pb-4">
                        <div>
                            <h3 class="text-lg font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Foto Produk</span>
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">Format 1:1 direkomendasikan. Foto pertama otomatis menjadi foto utama katalog.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold px-3 py-1 rounded-full transition-all" :class="previews.length > 0 ? 'bg-[#F3EEFF] text-[#4F26A6]' : 'bg-gray-100 text-gray-500'">
                                <span x-text="previews.length"></span>/5 Foto Terpilih
                            </span>
                        </div>
                    </div>

                    <!-- Hidden Native File Input -->
                    <input
                        type="file"
                        name="images[]"
                        multiple
                        x-ref="fileInput"
                        accept="image/jpeg,image/png,image/webp,image/jpg"
                        @change="handleFileChange($event)"
                        class="hidden"
                    />

                    <!-- 5-Slot Photo Grid with Drag and Drop -->
                    <div
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="handleDrop($event)"
                        class="p-4 sm:p-5 rounded-2xl border-2 transition-all"
                        :class="isDragging ? 'border-[#4F26A6] bg-[#F3EEFF]/40 ring-4 ring-[#4F26A6]/10' : 'border-dashed border-gray-200 bg-[#FAF9FC]'"
                    >
                        <style>
                            .seller-photo-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); }
                            @media (min-width: 640px) { .seller-photo-grid { grid-template-columns: repeat(5, minmax(0, 1fr)) !important; } }
                        </style>
                        <div class="seller-photo-grid gap-2.5 sm:gap-3">
                            <!-- 5 Slots Loop (0 to 4) -->
                            <template x-for="slotIdx in [0, 1, 2, 3, 4]" :key="slotIdx">
                                <div>
                                    <!-- 1. Slot is FILLED with Preview -->
                                    <template x-if="previews[slotIdx]">
                                        <div class="relative group aspect-square rounded-2xl overflow-hidden border-2 border-gray-200/90 bg-white shadow-xs hover:border-[#4F26A6] transition-all">
                                            <img :src="previews[slotIdx].url" :alt="previews[slotIdx].name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />

                                            <!-- Foto Utama Badge for index 0 -->
                                            <template x-if="slotIdx === 0">
                                                <div class="absolute top-2 left-2 z-10">
                                                    <span class="px-2 py-0.5 rounded-lg bg-[#4F26A6] text-white text-[10px] font-extrabold shadow-sm flex items-center gap-1">
                                                        <svg class="w-3 h-3 text-[#F59E0B] fill-[#F59E0B]" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                                        <span>Utama</span>
                                                    </span>
                                                </div>
                                            </template>

                                            <!-- Slot Number Badge for index 1..4 -->
                                            <template x-if="slotIdx > 0">
                                                <div class="absolute top-2 left-2 z-10">
                                                    <span class="px-2 py-0.5 rounded-lg bg-black/60 backdrop-blur-xs text-white text-[10px] font-bold shadow-sm" x-text="`Foto ${slotIdx + 1}`"></span>
                                                </div>
                                            </template>

                                            <!-- Quick Delete Button -->
                                            <button
                                                type="button"
                                                @click="removePhoto(slotIdx)"
                                                class="absolute top-2 right-2 z-20 w-7 h-7 rounded-xl bg-white/95 hover:bg-rose-600 text-gray-700 hover:text-white flex items-center justify-center shadow-md transition-all cursor-pointer"
                                                title="Hapus foto ini"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>

                                            <!-- Hover Overlay Info & Actions -->
                                            <div class="absolute inset-x-0 bottom-0 p-2 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-between z-10">
                                                <span class="text-[10px] text-white/90 font-medium truncate max-w-[70px]" x-text="previews[slotIdx].size"></span>
                                                <template x-if="slotIdx > 0">
                                                    <button
                                                        type="button"
                                                        @click="setPrimaryPhoto(slotIdx)"
                                                        class="px-2 py-0.5 rounded bg-[#4F26A6] text-white text-[9px] font-bold hover:bg-[#3E1D85] transition-colors cursor-pointer shadow-xs"
                                                        title="Jadikan Foto Utama"
                                                    >
                                                        Jadikan Utama
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- 2. Next Available Upload Slot (Active button) -->
                                    <template x-if="!previews[slotIdx] && slotIdx === previews.length">
                                        <button
                                            type="button"
                                            @click="triggerFileInput()"
                                            class="w-full aspect-square rounded-2xl border-2 border-dashed border-[#4F26A6]/40 bg-[#F3EEFF]/40 hover:bg-[#F3EEFF]/80 hover:border-[#4F26A6] transition-all flex flex-col items-center justify-center p-3 text-center group cursor-pointer active:scale-95 shadow-2xs"
                                        >
                                            <div class="w-10 h-10 rounded-xl bg-white text-[#4F26A6] flex items-center justify-center mb-2 shadow-xs group-hover:scale-110 transition-transform">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                                </svg>
                                            </div>
                                            <span class="text-xs font-bold text-[#4F26A6] group-hover:text-[#3E1D85]" x-text="slotIdx === 0 ? '+ Foto Utama' : '+ Tambah Foto'"></span>
                                            <span class="text-[10px] text-gray-400 mt-0.5" x-text="slotIdx === 0 ? 'Wajib diisi' : 'Opsional'"></span>
                                        </button>
                                    </template>

                                    <!-- 3. Inactive/Locked Slot Placeholder (Waiting for previous slots) -->
                                    <template x-if="!previews[slotIdx] && slotIdx > previews.length">
                                        <div class="w-full aspect-square rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50/50 flex flex-col items-center justify-center p-3 text-center opacity-60">
                                            <svg class="w-7 h-7 text-gray-300 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="text-[11px] font-semibold text-gray-400" x-text="`Foto ${slotIdx + 1}`"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <!-- Bottom Tips & Upload Trigger -->
                        <div class="mt-4 pt-3 border-t border-gray-200/60 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-gray-500">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Tips: Foto yang jelas dan terang meningkatkan peluang terjual hingga 3x lipat.</span>
                            </div>
                            <button
                                type="button"
                                x-show="previews.length < 5"
                                @click="triggerFileInput()"
                                class="font-bold text-[#4F26A6] hover:underline cursor-pointer"
                            >
                                Pilih Dari File Manager
                            </button>
                        </div>
                    </div>

                    <!-- Validation Error Message Alert -->
                    <template x-if="errorMessage">
                        <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span x-text="errorMessage"></span>
                        </div>
                    </template>
                </div>

                <!-- Variants Builder Card -->
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Varian &amp; Stok</h3>
                            <p class="text-xs text-gray-400">Atur ukuran, warna, dan ketersediaan stok.</p>
                        </div>
                        <button
                            type="button"
                            @click="addVariant()"
                            class="px-3.5 py-1.5 rounded-xl border border-[#4F26A6] text-[#4F26A6] hover:bg-[#F3EEFF] text-xs font-bold transition-all cursor-pointer"
                        >
                            + Tambah Varian
                        </button>
                    </div>

                    <div class="space-y-3 pt-2">
                        <template x-for="(v, index) in variants" :key="index">
                            <div class="p-4 rounded-2xl bg-[#FAF9FC] border border-gray-100 flex flex-col sm:flex-row items-start sm:items-center gap-3">
                                <div class="flex-1 w-full sm:w-auto">
                                    <label class="block text-[11px] font-bold text-gray-500 mb-1">Nama Varian / Ukuran</label>
                                    <input
                                        type="text"
                                        :name="'variants[' + index + '][name]'"
                                        x-model="v.name"
                                        placeholder="Contoh: Hitam - L"
                                        required
                                        class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-800 focus:border-[#4F26A6] outline-none"
                                    />
                                </div>
                                <div class="w-full sm:w-36">
                                    <label class="block text-[11px] font-bold text-gray-500 mb-1">Harga (Rp)</label>
                                    <input
                                        type="number"
                                        :name="'variants[' + index + '][price]'"
                                        x-model="v.price"
                                        required
                                        class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-bold text-[#4F26A6] focus:border-[#4F26A6] outline-none"
                                    />
                                </div>
                                <div class="w-full sm:w-24">
                                    <label class="block text-[11px] font-bold text-gray-500 mb-1">Stok</label>
                                    <input
                                        type="number"
                                        :name="'variants[' + index + '][stock]'"
                                        x-model="v.stock"
                                        min="0"
                                        required
                                        class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-800 focus:border-[#4F26A6] outline-none"
                                    />
                                </div>
                                <div class="self-end sm:self-center pt-1 sm:pt-4">
                                    <button
                                        type="button"
                                        @click="removeVariant(index)"
                                        :disabled="variants.length === 1"
                                        class="p-2 text-gray-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer"
                                        title="Hapus Varian"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar: Publish & Guidelines (4 cols) -->
            <div class="lg:col-span-4 space-y-6 sticky top-24">
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-6 sm:p-7 space-y-5">
                    <h3 class="text-base font-extrabold text-gray-900 tracking-tight">Publikasi Produk</h3>
                    
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Produk yang diterbitkan akan langsung tampil di beranda, katalog `/belanja`, dan profil tokomu.
                    </p>

                    <div class="p-3.5 rounded-2xl bg-[#F3EEFF] border border-purple-200/50 flex items-center gap-2 text-xs text-[#4F26A6]">
                        <x-verified-badge size="sm" class="w-4 h-4 shrink-0" />
                        <span>Otomatis berbadge <strong>Verified Creator</strong>.</span>
                    </div>

                    <button
                        id="publish-product-btn"
                        type="submit"
                        class="w-full py-3.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-sm shadow-md shadow-[#4F26A6]/20 transition-all active:scale-[0.98] cursor-pointer text-center"
                    >
                        Simpan &amp; Tayangkan Produk
                    </button>
                </div>
            </div>
        </form>
    </main>
</x-layouts.app>
