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
                addVariant() {
                    this.variants.push({ name: '', price: this.price, stock: 1 });
                },
                removeVariant(idx) {
                    if (this.variants.length > 1) {
                        this.variants.splice(idx, 1);
                    }
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
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Foto Produk</h3>
                        <span class="text-xs text-gray-400 font-medium">Maksimal 5 foto (JPG, PNG, WEBP)</span>
                    </div>

                    <div class="border-2 border-dashed border-[#4F26A6]/30 bg-[#F3EEFF]/20 rounded-2xl p-6 text-center hover:bg-[#F3EEFF]/30 transition-colors">
                        <svg class="w-10 h-10 mx-auto text-[#4F26A6] mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm font-bold text-gray-700">Pilih Foto Produk</p>
                        <p class="text-xs text-gray-400 mt-1 mb-4">Pastikan pencahayaan terang dan foto menampilkan kondisi asli barang</p>
                        <input
                            type="file"
                            name="images[]"
                            multiple
                            accept="image/jpeg,image/png,image/webp"
                            class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#4F26A6] file:text-white hover:file:bg-[#3E1D85] cursor-pointer"
                        />
                    </div>
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
