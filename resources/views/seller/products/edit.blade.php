<x-layouts.app :title="$title" activeTab="seller-products">
    <main class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10">
        <!-- Top Bar -->
        <div class="flex items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
            <div>
                <a href="{{ route('seller.products.index') }}" class="text-xs sm:text-sm font-bold text-[#4F26A6] hover:underline mb-1.5 inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Katalog</span>
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-950 tracking-tight">
                    Edit Produk: {{ $product->name }}
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                    Perbarui informasi barang, kelola foto galeri, serta atur varian dan stok tokomu.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('product.detail', $product->slug) }}"
                    target="_blank"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 hover:text-[#4F26A6] hover:bg-gray-50 font-bold text-xs sm:text-sm transition-all flex items-center gap-2"
                >
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span>Lihat Halaman Publik</span>
                </a>
            </div>
        </div>

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
            id="edit-product-form"
            action="{{ route('seller.products.update', $product->id) }}"
            method="POST"
            enctype="multipart/form-data"
            x-data="{
                price: {{ old('price', (int)$product->price) }},
                existingImages: @js($product->images->map(fn($img) => [
                    'id' => $img->id,
                    'url' => $img->image_path,
                    'original_url' => $img->image_path,
                    'is_primary' => (bool)$img->is_primary,
                    'is_replaced' => false,
                ])->values()->all()),
                deletedImages: [],
                deleteExistingImage(imgId) {
                    if (this.existingImages.length <= 1 && this.newPreviews.length === 0) {
                        alert('Produk harus memiliki minimal 1 foto.');
                        return;
                    }
                    this.cancelReplace(imgId);
                    this.deletedImages.push(imgId);
                    this.existingImages = this.existingImages.filter(img => img.id !== imgId);
                },
                triggerReplace(imgId) {
                    const input = document.getElementById('replace-input-' + imgId);
                    if (input) input.click();
                },
                onReplaceSelected(e, imgId) {
                    const file = e.target.files[0];
                    if (!file) return;
                    const imgObj = this.existingImages.find(img => img.id === imgId);
                    if (imgObj) {
                        if (imgObj.previewUrl) {
                            URL.revokeObjectURL(imgObj.previewUrl);
                        }
                        imgObj.previewUrl = URL.createObjectURL(file);
                        imgObj.url = imgObj.previewUrl;
                        imgObj.is_replaced = true;
                    }
                },
                cancelReplace(imgId) {
                    const imgObj = this.existingImages.find(img => img.id === imgId);
                    if (imgObj) {
                        if (imgObj.previewUrl) {
                            URL.revokeObjectURL(imgObj.previewUrl);
                            imgObj.previewUrl = null;
                        }
                        imgObj.url = imgObj.original_url;
                        imgObj.is_replaced = false;
                        const input = document.getElementById('replace-input-' + imgId);
                        if (input) input.value = '';
                    }
                },
                newFiles: [],
                newPreviews: [],
                onFilesSelected(e) {
                    const files = Array.from(e.target.files);
                    files.forEach(file => {
                        this.newFiles.push(file);
                        this.newPreviews.push({
                            name: file.name,
                            size: (file.size / 1024).toFixed(0) + ' KB',
                            url: URL.createObjectURL(file)
                        });
                    });
                    this.syncNewFileInput();
                },
                removeNewFile(pIdx) {
                    if (this.newPreviews[pIdx]) {
                        URL.revokeObjectURL(this.newPreviews[pIdx].url);
                    }
                    this.newFiles.splice(pIdx, 1);
                    this.newPreviews.splice(pIdx, 1);
                    this.syncNewFileInput();
                },
                removeNewFiles() {
                    this.newPreviews.forEach(p => URL.revokeObjectURL(p.url));
                    this.newPreviews = [];
                    this.newFiles = [];
                    this.syncNewFileInput();
                },
                syncNewFileInput() {
                    const dt = new DataTransfer();
                    this.newFiles.forEach(f => dt.items.add(f));
                    if (this.$refs.fileInput) {
                        this.$refs.fileInput.files = dt.files;
                    }
                },
                variants: @js($product->variants->map(fn($v) => ['id' => $v->id, 'name' => $v->name, 'price' => (float)$v->price, 'stock' => $v->stock])->values()->all()),
                addVariant() {
                    this.variants.push({ id: null, name: '', price: this.price, stock: 1 });
                },
                removeVariant(idx) {
                    if (this.variants.length > 1) {
                        this.variants.splice(idx, 1);
                    } else {
                        alert('Produk harus memiliki minimal 1 varian.');
                    }
                }
            }"
            class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start"
        >
            @csrf
            @method('PUT')

            <!-- Hidden inputs for deleted images -->
            <template x-for="delId in deletedImages" :key="delId">
                <input type="hidden" name="deleted_images[]" :value="delId">
            </template>

            <!-- Left Form Column (8 cols) -->
            <div class="lg:col-span-8 space-y-6">
                <!-- 1. Basic Info Card -->
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8 space-y-5">
                    <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Informasi Dasar Produk</h3>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nama Produk <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $product->name) }}"
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
                                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none cursor-pointer"
                            >
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $product->category_id === $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Kondisi Barang <span class="text-rose-500">*</span></label>
                            <select
                                name="condition"
                                required
                                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none cursor-pointer"
                            >
                                @foreach($conditions as $cond)
                                    <option value="{{ $cond->value }}" {{ $product->condition->value === $cond->value ? 'selected' : '' }}>{{ $cond->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Deskripsi Produk <span class="text-rose-500">*</span></label>
                        <textarea
                            name="description"
                            rows="4"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none resize-none leading-relaxed"
                        >{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Status Produk <span class="text-rose-500">*</span></label>
                            <select
                                name="status"
                                required
                                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none cursor-pointer"
                            >
                                <option value="active" {{ $product->status->value === 'active' ? 'selected' : '' }}>Aktif (Tayang di Toko)</option>
                                <option value="inactive" {{ $product->status->value === 'inactive' ? 'selected' : '' }}>Nonaktif (Draft / Arsip)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 2. Product Images Management Card -->
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                        <div>
                            <h3 class="text-lg font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Foto &amp; Galeri Produk</span>
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">Kelola foto produk yang sedang tayang atau tambahkan foto baru.</p>
                        </div>
                        <span class="text-xs text-gray-400 font-medium">Maksimal 5MB per foto</span>
                    </div>

                    <!-- Existing Images Grid -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Foto Saat Ini (<span x-text="existingImages.length"></span>)</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <template x-for="(img, idx) in existingImages" :key="img.id">
                                <div class="relative group aspect-square rounded-2xl overflow-hidden border border-gray-200/80 bg-gray-50 shadow-xs">
                                    <img :src="img.url" alt="Foto Produk" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                    <!-- Top Left Badges -->
                                    <div class="absolute top-2 left-2 flex flex-col gap-1 z-10 pointer-events-none">
                                        <template x-if="idx === 0 || img.is_primary">
                                            <span class="px-2 py-0.5 rounded-lg bg-[#4F26A6] text-white text-[10px] font-bold shadow-xs">
                                                Foto Utama
                                            </span>
                                        </template>
                                        <template x-if="img.is_replaced">
                                            <span class="px-2 py-0.5 rounded-lg bg-amber-500 text-white text-[10px] font-bold shadow-xs flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                <span>Foto Diganti</span>
                                            </span>
                                        </template>
                                    </div>

                                    <!-- Top Right Delete Button -->
                                    <button
                                        type="button"
                                        @click="deleteExistingImage(img.id)"
                                        class="absolute top-2 right-2 w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-white/95 hover:bg-rose-600 text-gray-700 hover:text-white flex items-center justify-center shadow-md transition-all cursor-pointer z-10 active:scale-95"
                                        title="Hapus foto ini"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>

                                    <!-- Hidden File Input for Image Replacement -->
                                    <input
                                        type="file"
                                        :id="'replace-input-' + img.id"
                                        :name="'replace_images[' + img.id + ']'"
                                        accept="image/jpeg,image/png,image/webp,image/jpg"
                                        class="hidden"
                                        @change="onReplaceSelected($event, img.id)"
                                    />

                                    <!-- Bottom Action Bar (Ganti Foto & Batalkan) -->
                                    <div class="absolute inset-x-0 bottom-0 p-1.5 sm:p-2 bg-gradient-to-t from-black/85 via-black/50 to-transparent flex items-center gap-1.5 z-10">
                                        <template x-if="!img.is_replaced">
                                            <button
                                                type="button"
                                                @click.stop="triggerReplace(img.id)"
                                                class="w-full py-1.5 px-2 rounded-xl bg-white/95 hover:bg-white text-gray-900 text-[11px] font-extrabold flex items-center justify-center gap-1.5 shadow-sm transition-all cursor-pointer active:scale-95 hover:text-[#4F26A6]"
                                                title="Ganti foto ini"
                                            >
                                                <svg class="w-3.5 h-3.5 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                <span>Ganti Foto</span>
                                            </button>
                                        </template>

                                        <template x-if="img.is_replaced">
                                            <div class="w-full flex items-center gap-1.5">
                                                <button
                                                    type="button"
                                                    @click.stop="triggerReplace(img.id)"
                                                    class="flex-1 py-1.5 px-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-[11px] font-extrabold flex items-center justify-center gap-1 shadow-sm transition-all cursor-pointer active:scale-95"
                                                    title="Pilih foto lain"
                                                >
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                    <span>Ubah</span>
                                                </button>
                                                <button
                                                    type="button"
                                                    @click.stop="cancelReplace(img.id)"
                                                    class="w-7 h-7 rounded-xl bg-white/95 hover:bg-white text-gray-700 hover:text-rose-600 flex items-center justify-center shadow-sm transition-all cursor-pointer active:scale-95 shrink-0"
                                                    title="Batal ganti foto"
                                                >
                                                    <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <template x-if="existingImages.length === 0">
                            <p class="text-xs text-rose-500 font-semibold italic mt-1">Semua foto lama telah ditandai untuk dihapus. Pastikan mengunggah foto baru di bawah ini.</p>
                        </template>
                    </div>

                    <!-- Upload New Photos Area -->
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Unggah Foto Baru (Opsional)</label>
                        <div class="border-2 border-dashed border-[#4F26A6]/30 bg-[#F3EEFF]/20 rounded-2xl p-6 text-center hover:bg-[#F3EEFF]/30 transition-colors">
                            <svg class="w-9 h-9 mx-auto text-[#4F26A6] mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm font-bold text-gray-800">Pilih Foto Tambahan</p>
                            <p class="text-xs text-gray-500 mt-1 mb-4">Mendukung format JPG, PNG, atau WEBP. Foto baru akan ditambahkan ke galeri produk.</p>
                            <input
                                type="file"
                                name="images[]"
                                multiple
                                x-ref="fileInput"
                                accept="image/jpeg,image/png,image/webp,image/jpg"
                                @change="onFilesSelected($event)"
                                class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#4F26A6] file:text-white hover:file:bg-[#3E1D85] cursor-pointer"
                            />
                        </div>

                        <!-- Live Preview of Newly Selected Files -->
                        <template x-if="newPreviews.length > 0">
                            <div class="mt-4 p-4 rounded-2xl bg-[#FAF9FC] border border-gray-100 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-gray-700">Foto Baru yang Akan Diunggah (<span x-text="newPreviews.length"></span>):</span>
                                    <button type="button" @click="removeNewFiles()" class="text-xs font-bold text-rose-600 hover:underline cursor-pointer">
                                        Hapus Semua Foto Baru
                                    </button>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    <template x-for="(preview, pIdx) in newPreviews" :key="pIdx">
                                        <div class="relative group aspect-square rounded-2xl overflow-hidden border-2 border-purple-200/80 bg-white shadow-2xs">
                                            <img :src="preview.url" :alt="preview.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                            <button
                                                type="button"
                                                @click="removeNewFile(pIdx)"
                                                class="absolute top-2 right-2 w-7 h-7 rounded-xl bg-white/95 hover:bg-rose-600 text-gray-700 hover:text-white flex items-center justify-center shadow-md transition-all cursor-pointer z-10"
                                                title="Hapus foto ini"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                            <span class="absolute bottom-1.5 right-1.5 px-2 py-0.5 rounded-lg bg-black/70 backdrop-blur-xs text-white text-[9px] font-bold" x-text="preview.size"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- 3. Variants & Stock Builder Card -->
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8 space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                        <div>
                            <h3 class="text-lg font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                <span>Varian &amp; Stok Produk</span>
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">Kelola nama ukuran, harga masing-masing varian, serta jumlah stok tersedia.</p>
                        </div>
                        <button
                            type="button"
                            @click="addVariant()"
                            class="px-3.5 py-2 rounded-xl bg-[#F3EEFF] hover:bg-[#EADDFE] text-[#4F26A6] text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            <span>+ Tambah Varian</span>
                        </button>
                    </div>

                    <div class="space-y-3 pt-2">
                        <template x-for="(v, index) in variants" :key="index">
                            <div class="p-4 rounded-2xl bg-[#FAF9FC] border border-gray-100 flex flex-col sm:flex-row items-start sm:items-center gap-3">
                                <!-- Hidden variant ID if updating existing -->
                                <template x-if="v.id">
                                    <input type="hidden" :name="'variants[' + index + '][id]'" :value="v.id">
                                </template>

                                <div class="flex-1 w-full sm:w-auto">
                                    <label class="block text-[11px] font-bold text-gray-500 mb-1">Nama Varian / Ukuran <span class="text-rose-500">*</span></label>
                                    <input
                                        type="text"
                                        :name="'variants[' + index + '][name]'"
                                        x-model="v.name"
                                        placeholder="Contoh: Ukuran M / Warna Hitam"
                                        required
                                        class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-xs font-semibold text-gray-800 focus:border-[#4F26A6] focus:ring-1 focus:ring-[#4F26A6]/20 outline-none"
                                    />
                                </div>
                                <div class="w-full sm:w-40">
                                    <label class="block text-[11px] font-bold text-gray-500 mb-1">Harga (Rp) <span class="text-rose-500">*</span></label>
                                    <input
                                        type="number"
                                        :name="'variants[' + index + '][price]'"
                                        x-model="v.price"
                                        min="1000"
                                        step="1000"
                                        required
                                        class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-xs font-bold text-[#4F26A6] focus:border-[#4F26A6] focus:ring-1 focus:ring-[#4F26A6]/20 outline-none"
                                    />
                                </div>
                                <div class="w-full sm:w-28">
                                    <label class="block text-[11px] font-bold text-gray-500 mb-1">Stok Tersedia <span class="text-rose-500">*</span></label>
                                    <input
                                        type="number"
                                        :name="'variants[' + index + '][stock]'"
                                        x-model="v.stock"
                                        min="0"
                                        required
                                        class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-xs font-semibold text-gray-800 focus:border-[#4F26A6] focus:ring-1 focus:ring-[#4F26A6]/20 outline-none"
                                    />
                                </div>
                                <div class="self-end sm:self-center pt-1 sm:pt-4">
                                    <button
                                        type="button"
                                        @click="removeVariant(index)"
                                        :disabled="variants.length === 1"
                                        class="p-2.5 text-gray-400 hover:text-rose-600 rounded-xl hover:bg-rose-50 disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer transition-colors"
                                        title="Hapus Varian"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-4 space-y-6 sticky top-24">
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-6 sm:p-7 space-y-5">
                    <h3 class="text-base font-extrabold text-gray-900 tracking-tight">Simpan Perubahan</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Perubahan foto, harga dasar, serta varian dan stok akan langsung terupdate di katalog publik WhiMarket.
                    </p>

                    <div class="space-y-3 pt-2">
                        <button
                            type="submit"
                            id="btn-submit-product"
                            class="w-full py-3.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-sm shadow-md shadow-[#4F26A6]/20 transition-all active:scale-[0.98] cursor-pointer text-center flex items-center justify-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan Perubahan</span>
                        </button>

                        <a
                            href="{{ route('seller.products.index') }}"
                            class="w-full py-3 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 font-bold text-xs sm:text-sm transition-all text-center block"
                        >
                            Batal
                        </a>
                    </div>

                    <div class="border-t border-gray-100 pt-4 text-xs text-gray-400 space-y-1">
                        <p>Total Varian Aktif: <strong class="text-gray-800" x-text="variants.length"></strong></p>
                        <p>Total Stok: <strong class="text-gray-800" x-text="variants.reduce((acc, v) => acc + (parseInt(v.stock) || 0), 0)"></strong> item</p>
                    </div>
                </div>
            </div>
        </form>
    </main>
</x-layouts.app>
