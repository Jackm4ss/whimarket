<x-layouts.app :title="$title" activeTab="seller-products">
    <main class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-10">
        <!-- Top Bar -->
        <div class="flex items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
            <div>
                <a href="{{ route('seller.products.index') }}" class="text-xs sm:text-sm font-bold text-[#4F26A6] hover:underline mb-1 inline-block">
                    &larr; Kembali ke Katalog
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-950 tracking-tight">
                    Edit Produk: {{ $product->name }}
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                    Perbarui informasi barang pre-loved tokomu.
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
            action="{{ route('seller.products.update', $product->id) }}"
            method="POST"
            class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start"
        >
            @csrf
            @method('PUT')

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
                                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"
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
                                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"
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
                            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none resize-none"
                        >{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Harga Dasar (Rp) <span class="text-rose-500">*</span></label>
                            <input
                                type="number"
                                name="price"
                                value="{{ old('price', (int)$product->price) }}"
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
                                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"
                            >
                                <option value="active" {{ $product->status->value === 'active' ? 'selected' : '' }}>Aktif (Tayang)</option>
                                <option value="inactive" {{ $product->status->value === 'inactive' ? 'selected' : '' }}>Nonaktif (Draft)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar: Action Card (4 cols) -->
            <div class="lg:col-span-4 space-y-6 sticky top-24">
                <div class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-6 sm:p-7 space-y-5">
                    <h3 class="text-base font-extrabold text-gray-900 tracking-tight">Simpan Perubahan</h3>
                    <p class="text-xs text-gray-500">Perubahan akan langsung terupdate pada katalog pembeli.</p>

                    <button
                        type="submit"
                        class="w-full py-3.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-sm shadow-md shadow-[#4F26A6]/20 transition-all active:scale-[0.98] cursor-pointer text-center"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </main>
</x-layouts.app>
