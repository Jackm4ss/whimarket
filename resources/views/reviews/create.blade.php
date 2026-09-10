<x-layouts.app :title="$title" activeTab="pesanan">
    <main class="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-8 sm:py-12">
        <div class="max-w-3xl mx-auto">
            @php
                $seller = $order->seller;
                $sellerAvatar = $seller?->avatar_url ?? 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 128 128"><rect width="128" height="128" rx="64" fill="%23F3EEFF"/><text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle" font-family="sans-serif" font-weight="900" font-size="52" fill="%234F26A6">W</text></svg>';
            @endphp

            <!-- Top Navigation & Badge Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-gray-100">
                <a
                    href="{{ route('orders.show', $order->order_number) }}"
                    class="text-xs sm:text-sm font-bold text-[#4F26A6] hover:text-[#3E1D85] inline-flex items-center gap-1.5 transition-colors group"
                >
                    <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Detail Pesanan</span>
                </a>

                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-800 text-[11px] sm:text-xs font-bold border border-amber-200/80 shadow-2xs w-fit">
                    <svg class="w-3.5 h-3.5 text-[#F59E0B] fill-current" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span>Penilaian Pembeli Terverifikasi</span>
                </div>
            </div>

            <!-- Page Title Block -->
            <div class="mb-5 space-y-1">
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-gray-950 tracking-tight">
                    Beri Ulasan Produk
                </h1>
                <p class="text-xs sm:text-sm text-gray-500">
                    Beri penilaian objektif untuk barang yang telah kamu terima dari toko penjual.
                </p>
            </div>

            <!-- Seller Store & Order Info Card -->
            <div class="mb-8 p-4 sm:p-5 rounded-2xl bg-white border border-gray-100 shadow-[0_8px_30px_rgba(79,38,166,0.04)] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5 min-w-0">
                    <!-- Seller Profile Photo / Avatar -->
                    <a
                        href="{{ $seller ? url('/seller/@'.$seller->username) : '#' }}"
                        class="relative shrink-0 group block"
                        title="Kunjungi Toko {{ $seller?->store_name }}"
                    >
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl overflow-hidden bg-[#F3EEFF] border border-gray-200/80 ring-2 ring-[#4F26A6]/10 shadow-xs group-hover:scale-105 transition-transform flex items-center justify-center">
                            <img
                                src="{{ $sellerAvatar }}"
                                alt="{{ $seller?->store_name ?? 'Penjual' }}"
                                class="w-full h-full object-cover"
                            />
                        </div>
                    </a>

                    <!-- Seller & Order Details -->
                    <div class="min-w-0 space-y-1">
                        <div class="flex items-center gap-1.5 text-xs text-gray-500 flex-wrap">
                            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Penjual</span>
                            <span class="text-gray-300">•</span>
                            <span class="text-[11px] font-mono text-gray-500">No. Pesanan: <strong class="font-bold text-gray-900">#{{ $order->order_number }}</strong></span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <a
                                href="{{ $seller ? url('/seller/@'.$seller->username) : '#' }}"
                                class="text-sm sm:text-base font-extrabold text-gray-950 hover:text-[#4F26A6] transition-colors truncate"
                            >
                                {{ $seller?->store_name ?? 'Penjual WhiMarket' }}
                            </a>
                            @if($seller?->isVerified())
                                <x-verified-badge size="sm" class="w-4 h-4 text-[#4F26A6] shrink-0" />
                            @endif
                        </div>
                        @if($seller)
                            <p class="text-[11px] text-gray-500 font-medium truncate flex items-center gap-2">
                                <span>{{ '@' . $seller->username }}</span>
                                @if($seller->average_rating > 0)
                                    <span class="text-gray-300">•</span>
                                    <span class="inline-flex items-center gap-0.5 text-amber-600 font-bold">
                                        <svg class="w-3 h-3 fill-current text-amber-500" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span>{{ number_format($seller->average_rating, 1) }}</span>
                                    </span>
                                @endif
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Action Button: Visit Store -->
                @if($seller)
                    <div class="sm:shrink-0 flex items-center pt-2.5 sm:pt-0 border-t sm:border-t-0 border-gray-100">
                        <a
                            href="{{ url('/seller/@'.$seller->username) }}"
                            target="_blank"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 text-xs font-bold text-[#4F26A6] hover:text-[#3E1D85] px-4 py-2 rounded-xl bg-[#F3EEFF] hover:bg-[#ECE5FF] border border-[#4F26A6]/15 transition-all shadow-2xs group"
                        >
                            <span>Kunjungi Toko</span>
                            <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Flash alerts -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-sm font-semibold flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-800 text-sm font-semibold flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-800 text-sm space-y-1">
                    @foreach($errors->all() as $err)
                        <p class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            <span>{{ $err }}</span>
                        </p>
                    @endforeach
                </div>
            @endif

            @php
                $initialItemId = $targetItemId ?: ($order->items->firstWhere('review', null)?->id ?? $order->items->first()->id);
            @endphp

            <script>
                window.reviewPageManager = function() {
                    return {
                        activeItemId: {{ $initialItemId }},
                        ratings: {
                            @foreach($order->items as $item)
                                {{ $item->id }}: {{ $item->review ? $item->review->rating : 5 }},
                            @endforeach
                        },
                        hoverRatings: {
                            @foreach($order->items as $item)
                                {{ $item->id }}: 0,
                            @endforeach
                        },
                        ratingLabels: {
                            1: 'Kecewa! Barang tidak sesuai atau memiliki cacat.',
                            2: 'Kurang Puas! Kualitas atau kondisi di bawah ekspektasi.',
                            3: 'Cukup! Barang sesuai dengan deskripsi standar.',
                            4: 'Puas! Kondisi barang bagus dan pengemasan rapi.',
                            5: 'Sangat Puas! Kualitas istimewa & pelayanan mantap pol!'
                        },
                        photos: {
                            @foreach($order->items as $item)
                                {{ $item->id }}: [],
                            @endforeach
                        },
                        videos: {
                            @foreach($order->items as $item)
                                {{ $item->id }}: null,
                            @endforeach
                        },
                        dataTransfers: {
                            @foreach($order->items as $item)
                                {{ $item->id }}: new DataTransfer(),
                            @endforeach
                        },
                        isSubmitting: false,

                        syncPhotoInput(itemId) {
                            const input = document.getElementById('photos_input_' + itemId);
                            if (input && this.dataTransfers[itemId]) {
                                input.files = this.dataTransfers[itemId].files;
                            }
                        },

                        addPhotos(itemId, event) {
                            const newFiles = Array.from(event.target.files);
                            if (!newFiles.length) return;

                            const currentCount = this.photos[itemId].length;
                            if (currentCount + newFiles.length > 5) {
                                alert('Maksimal 5 foto per ulasan. Kamu sudah memilih ' + currentCount + ' foto.');
                                event.target.value = '';
                                return;
                            }

                            for (const file of newFiles) {
                                if (file.type.startsWith('image/')) {
                                    this.dataTransfers[itemId].items.add(file);
                                    const url = URL.createObjectURL(file);
                                    const sizeStr = file.size > 1024 * 1024 
                                        ? (file.size / (1024 * 1024)).toFixed(1) + ' MB'
                                        : Math.round(file.size / 1024) + ' KB';
                                    this.photos[itemId].push({
                                        name: file.name,
                                        size: sizeStr,
                                        url: url
                                    });
                                }
                            }
                            this.syncPhotoInput(itemId);
                            event.target.value = '';
                        },

                        removePhoto(itemId, index) {
                            if (this.photos[itemId][index]) {
                                URL.revokeObjectURL(this.photos[itemId][index].url);
                                this.photos[itemId].splice(index, 1);

                                const dt = new DataTransfer();
                                const currentFiles = Array.from(this.dataTransfers[itemId].files);
                                currentFiles.forEach((file, idx) => {
                                    if (idx !== index) dt.items.add(file);
                                });
                                this.dataTransfers[itemId] = dt;
                                this.syncPhotoInput(itemId);
                            }
                        },

                        handleVideo(itemId, event) {
                            const file = event.target.files[0];
                            if (!file) return;

                            if (file.size > 52428800) {
                                alert('Ukuran video melebihi batas 50MB. Silakan pilih video yang lebih pendek.');
                                event.target.value = '';
                                return;
                            }

                            if (this.videos[itemId]) {
                                URL.revokeObjectURL(this.videos[itemId].url);
                            }

                            const url = URL.createObjectURL(file);
                            const sizeStr = file.size < 1048576
                                ? (file.size / 1024).toFixed(0) + ' KB'
                                : (file.size / (1024 * 1024)).toFixed(1) + ' MB';
                            this.videos[itemId] = {
                                name: file.name,
                                size: sizeStr,
                                url: url
                            };
                        },

                        removeVideo(itemId) {
                            if (this.videos[itemId]) {
                                URL.revokeObjectURL(this.videos[itemId].url);
                                this.videos[itemId] = null;
                            }
                            const input = document.getElementById('video_input_' + itemId);
                            if (input) input.value = '';
                        },

                        submitForm(itemId, event) {
                            if (this.isSubmitting) return;

                            let totalBytes = 0;
                            if (this.dataTransfers[itemId]?.files) {
                                for (const file of this.dataTransfers[itemId].files) {
                                    if (file.size > 5242880) {
                                        alert('Foto "' + file.name + '" melebihi batas 5MB. Silakan pilih foto yang lebih kecil.');
                                        return;
                                    }
                                    totalBytes += file.size;
                                }
                            }

                            const videoInput = document.getElementById('video_input_' + itemId);
                            if (videoInput?.files?.[0]) {
                                const vFile = videoInput.files[0];
                                if (vFile.size > 52428800) {
                                    alert('Video "' + vFile.name + '" melebihi batas 50MB. Silakan pilih video yang lebih pendek atau kompres terlebih dahulu.');
                                    return;
                                }
                                totalBytes += vFile.size;
                            }

                            if (totalBytes > 62914560) {
                                alert('Total berkas yang diunggah melebihi batas 60MB. Silakan kurangi ukuran atau jumlah media.');
                                return;
                            }
                            this.isSubmitting = true;
                            const formEl = document.getElementById('review_form_' + itemId) || (event?.target && event.target.tagName === 'FORM' ? event.target : event?.target?.closest('form'));
                            if (formEl) {
                                HTMLFormElement.prototype.submit.call(formEl);
                            }
                        }
                    };
                };
            </script>

            <div
                x-data="reviewPageManager()"
                class="space-y-6"
            >
                <!-- Item Selector Tabs (if multi items) -->
                @if($order->items->count() > 1)
                    <div class="bg-white rounded-2xl border border-gray-100 p-2 shadow-xs flex items-center gap-2 overflow-x-auto">
                        @foreach($order->items as $idx => $item)
                            <button
                                type="button"
                                @click="activeItemId = {{ $item->id }}"
                                class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-bold transition-all shrink-0 cursor-pointer"
                                :class="activeItemId === {{ $item->id }}
                                    ? 'bg-[#4F26A6] text-white shadow-xs'
                                    : 'bg-gray-50 text-gray-700 hover:bg-gray-100'"
                            >
                                <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px]"
                                    :class="activeItemId === {{ $item->id }} ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'">
                                    {{ $idx + 1 }}
                                </span>
                                <span class="max-w-[140px] sm:max-w-[180px] truncate">{{ $item->product_name_snapshot }}</span>
                                @if($item->review)
                                    <span class="inline-flex items-center text-amber-300">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    </span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                @endif

                @foreach($order->items as $item)
                    <div
                        x-show="activeItemId === {{ $item->id }}"
                        class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_12px_40px_rgba(79,38,166,0.06)] p-6 sm:p-10 space-y-6"
                    >
                        <!-- Product Info Card Header -->
                        <div class="flex items-start sm:items-center gap-4 p-4 rounded-2xl bg-[#FAF9FC] border border-gray-100">
                            <img
                                src="{{ $item->variant?->product?->primary_image_url ?? '/assets/products/prod-hoodie.png' }}"
                                alt="{{ $item->product_name_snapshot }}"
                                class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover border border-gray-200/80 bg-white shrink-0"
                            />
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm sm:text-base font-extrabold text-gray-950 leading-snug line-clamp-2">
                                    {{ $item->product_name_snapshot }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-2 flex-wrap">
                                    <span>Varian: <strong class="text-gray-700 font-semibold">{{ $item->variant_name_snapshot }}</strong></span>
                                    @if(! str_contains(strtolower($item->variant_name_snapshot), 'pcs'))
                                        <span class="text-gray-300">•</span>
                                        <span>{{ $item->quantity }} pcs</span>
                                    @endif
                                    <span class="text-gray-300">•</span>
                                    <span class="font-bold text-[#4F26A6]">Rp {{ number_format((float)$item->subtotal, 0, ',', '.') }}</span>
                                </p>
                            </div>
                        </div>

                        @if($item->review)
                            <!-- State A: Already Reviewed -->
                            <div class="p-6 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 space-y-4">
                                <div class="flex items-center justify-between gap-3 flex-wrap">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-extrabold">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>Sudah Diulas</span>
                                        </span>
                                        <span class="text-xs text-gray-500 font-medium">
                                            {{ $item->review->created_at->translatedFormat('d M Y, H:i') }}
                                        </span>
                                    </div>
                                    @if($item->variant?->product)
                                        <a
                                            href="{{ route('product.detail', $item->variant->product->slug) }}#ulasan"
                                            class="text-xs font-bold text-[#4F26A6] hover:underline inline-flex items-center gap-1"
                                        >
                                            <span>Lihat di Halaman Produk</span>
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>

                                <!-- Star Rating Given -->
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center gap-1 text-[#F59E0B]">
                                        @for($s = 1; $s <= 5; $s++)
                                            <svg class="w-5 h-5 {{ $s <= $item->review->rating ? 'fill-current' : 'text-gray-200 fill-current' }}" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-sm font-extrabold text-gray-900">{{ $item->review->rating }} / 5 Bintang</span>
                                </div>

                                <!-- Comment -->
                                @if($item->review->comment)
                                    <p class="text-sm text-gray-700 leading-relaxed bg-white p-4 rounded-xl border border-emerald-100/80">
                                        "{{ $item->review->comment }}"
                                    </p>
                                @endif

                                <!-- Photos -->
                                @if(!empty($item->review->photos))
                                    <div>
                                        <p class="text-xs font-bold text-gray-600 mb-2">Foto Ulasan:</p>
                                        <div class="flex items-center gap-3 flex-wrap">
                                            @foreach($item->review->photos as $pUrl)
                                                <a href="{{ $pUrl }}" target="_blank" class="block w-20 h-20 rounded-xl overflow-hidden border border-gray-200 hover:scale-105 transition-transform shadow-2xs">
                                                    <img src="{{ $pUrl }}" alt="Foto Ulasan" class="w-full h-full object-cover" />
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Video -->
                                @if(!empty($item->review->video))
                                    <div>
                                        <p class="text-xs font-bold text-gray-600 mb-2">Video Unboxing:</p>
                                        <video controls src="{{ $item->review->video }}" class="w-full max-w-sm rounded-2xl bg-black shadow-sm max-h-60"></video>
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- State B: Review Form -->
                            <form
                                id="review_form_{{ $item->id }}"
                                action="{{ route('reviews.store', $order->order_number) }}"
                                method="POST"
                                enctype="multipart/form-data"
                                @submit.prevent="submitForm({{ $item->id }}, $event)"
                                class="space-y-6"
                            >
                                @csrf
                                <input type="hidden" name="order_item_id" value="{{ $item->id }}" />
                                <input type="hidden" name="rating" :value="ratings[{{ $item->id }}]" />

                                <!-- Hidden Real Input for accumulated photos -->
                                <input
                                    type="file"
                                    id="photos_input_{{ $item->id }}"
                                    name="photos[]"
                                    multiple
                                    accept="image/jpeg,image/png,image/webp"
                                    class="hidden"
                                />

                                <!-- Rating Stars Selector -->
                                <div class="space-y-2 text-center py-2">
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Kualitas Produk Secara Keseluruhan <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="flex items-center justify-center gap-2 sm:gap-3 my-2">
                                        @for($star = 1; $star <= 5; $star++)
                                            <button
                                                type="button"
                                                @click="ratings[{{ $item->id }}] = {{ $star }}"
                                                @mouseenter="hoverRatings[{{ $item->id }}] = {{ $star }}"
                                                @mouseleave="hoverRatings[{{ $item->id }}] = 0"
                                                class="p-1 sm:p-2 rounded-xl transition-transform hover:scale-125 cursor-pointer focus:outline-none"
                                            >
                                                <svg
                                                    class="w-8 h-8 sm:w-10 sm:h-10 transition-colors"
                                                    :class="({{ $star }} <= (hoverRatings[{{ $item->id }}] || ratings[{{ $item->id }}]))
                                                        ? 'text-[#F59E0B] fill-current'
                                                        : 'text-gray-200 fill-current'"
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </button>
                                        @endfor
                                    </div>
                                    <p
                                        class="text-xs sm:text-sm font-bold transition-colors min-h-[22px]"
                                        :class="ratings[{{ $item->id }}] >= 4 ? 'text-emerald-600' : (ratings[{{ $item->id }}] === 3 ? 'text-amber-600' : 'text-rose-600')"
                                        x-text="ratingLabels[hoverRatings[{{ $item->id }}] || ratings[{{ $item->id }}]]"
                                    ></p>
                                </div>

                                <!-- Review Comment Field -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Ulasan Kamu (Opsional)
                                        </label>
                                        <span class="text-[11px] text-gray-400 font-medium">Maks. 2000 karakter</span>
                                    </div>
                                    <textarea
                                        name="comment"
                                        rows="4"
                                        maxlength="2000"
                                        placeholder="Ceritakan kepuasanmu tentang kualitas barang, kecocokan ukuran, pengemasan paket oleh penjual, atau hal menarik lainnya..."
                                        class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none resize-none leading-relaxed"
                                    ></textarea>
                                </div>

                                <!-- Section 1: Review Photos Multi-Upload & Previews -->
                                <div class="space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Foto Produk / Unboxing (Opsional)
                                        </label>
                                        <span class="text-[11px] font-semibold text-[#4F26A6]" x-text="photos[{{ $item->id }}].length + '/5 Foto Terpilih'"></span>
                                    </div>

                                    <!-- Grid of Photo Previews + Add Card -->
                                    <div class="space-y-3">
                                        <div class="flex items-center gap-3 flex-wrap">
                                            <!-- Existing Selected Photos Thumbnails -->
                                            <template x-for="(photo, pIdx) in photos[{{ $item->id }}]" :key="pIdx">
                                                <div class="relative group w-24 h-24 sm:w-28 sm:h-28 rounded-2xl overflow-hidden border border-gray-200 bg-white shadow-2xs">
                                                    <img :src="photo.url" :alt="photo.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform" />
                                                    
                                                    <!-- Delete Button -->
                                                    <button
                                                        type="button"
                                                        @click="removePhoto({{ $item->id }}, pIdx)"
                                                        style="background-color: #E11D48; color: #ffffff;"
                                                        class="absolute top-1.5 right-1.5 z-10 w-6 h-6 rounded-full hover:opacity-90 flex items-center justify-center shadow-md transition-transform active:scale-90 cursor-pointer"
                                                        title="Hapus foto ini"
                                                    >
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>

                                                    <!-- Photo Index Badge -->
                                                    <div class="absolute bottom-1 left-1 px-1.5 py-0.5 rounded-md bg-black/60 text-[10px] font-bold text-white leading-none">
                                                        <span x-text="'#' + (pIdx + 1)"></span>
                                                    </div>
                                                </div>
                                            </template>

                                            <!-- Add Photo Trigger Card (visible when count < 5) -->
                                            <template x-if="photos[{{ $item->id }}].length < 5">
                                                <label class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl border-2 border-dashed border-[#4F26A6]/30 hover:border-[#4F26A6] bg-[#FAF9FC] hover:bg-[#F3EEFF]/40 flex flex-col items-center justify-center p-2 cursor-pointer transition-all text-center group shrink-0">
                                                    <svg class="w-6 h-6 text-[#4F26A6]/70 group-hover:text-[#4F26A6] group-hover:scale-110 transition-transform mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    <span class="text-[11px] font-bold text-gray-700 group-hover:text-[#4F26A6] leading-tight">
                                                        Tambah Foto
                                                    </span>
                                                    <span class="text-[9px] text-gray-400 mt-0.5" x-text="'(sisa ' + (5 - photos[{{ $item->id }}].length) + ')'"></span>
                                                    <input
                                                        type="file"
                                                        multiple
                                                        accept="image/jpeg,image/png,image/webp"
                                                        @change="addPhotos({{ $item->id }}, $event)"
                                                        class="photo-picker-input hidden"
                                                    />
                                                </label>
                                            </template>
                                        </div>

                                        <p class="text-[11px] text-gray-400">
                                            Format: JPG, PNG, WEBP. Maksimal 5 foto (maks. 5MB per file).
                                        </p>
                                    </div>
                                </div>

                                <!-- Section 2: Review Video Upload & Live Player Preview -->
                                <div class="space-y-2.5 pt-2 border-t border-gray-100">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Video Unboxing / Review (Opsional)
                                        </label>
                                        <span class="text-[11px] font-semibold text-gray-400">Maks. 1 video (maks. 50MB)</span>
                                    </div>
                                    <!-- Single Persistent Video Input -->
                                    <input
                                        type="file"
                                        id="video_input_{{ $item->id }}"
                                        name="video"
                                        accept="video/mp4,video/quicktime,video/webm,video/x-msvideo"
                                        @change="handleVideo({{ $item->id }}, $event)"
                                        class="hidden"
                                    />

                                    <!-- State: No Video Selected -->
                                    <div x-show="!videos[{{ $item->id }}]">
                                        <button
                                            type="button"
                                            @click="document.getElementById('video_input_{{ $item->id }}').click()"
                                            class="w-full flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-200 hover:border-[#4F26A6]/60 rounded-2xl cursor-pointer bg-[#FAF9FC] hover:bg-[#F3EEFF]/30 transition-all group text-center"
                                        >
                                            <div class="w-12 h-12 rounded-2xl bg-white border border-gray-200/80 shadow-2xs flex items-center justify-center text-gray-400 group-hover:text-[#4F26A6] group-hover:scale-105 transition-all mb-2.5">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <span class="text-xs font-bold text-gray-800 group-hover:text-[#4F26A6] transition-colors">
                                                + Unggah Video Unboxing
                                            </span>
                                            <span class="text-[11px] text-gray-400 mt-1">
                                                Format: MP4, MOV, WEBM, AVI (maks. 50MB)
                                            </span>
                                        </button>
                                    </div>

                                    <!-- State: Video Selected with Live Player Preview Card -->
                                    <div x-show="videos[{{ $item->id }}]" x-cloak class="w-full">
                                        <div class="p-4 sm:p-5 rounded-2xl border border-[#4F26A6]/20 bg-[#FAF9FC] overflow-hidden shadow-2xs w-full">
                                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4.5 w-full min-w-0">
                                                <!-- Video Player Preview -->
                                                <div
                                                    class="w-full sm:w-64 md:w-72 shrink-0 aspect-video rounded-xl overflow-hidden bg-black shadow-inner border border-gray-300 relative flex items-center justify-center"
                                                    style="min-width: 0; max-height: 170px;"
                                                >
                                                    <video
                                                        :src="videos[{{ $item->id }}]?.url"
                                                        controls
                                                        preload="metadata"
                                                        class="w-full h-full object-contain bg-black"
                                                        style="max-height: 170px; max-width: 100%;"
                                                    ></video>
                                                </div>

                                                <!-- Video File Info & Action Buttons -->
                                                <div class="w-full sm:flex-1 min-w-0 space-y-2.5 flex flex-col justify-center">
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-200/80 text-emerald-700 text-[11px] font-bold">
                                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            <span>Video Siap Diunggah</span>
                                                        </span>
                                                        <span class="px-2 py-0.5 rounded-md bg-gray-100 text-[11px] font-mono font-semibold text-gray-600" x-text="videos[{{ $item->id }}]?.size"></span>
                                                    </div>

                                                    <h5 class="text-xs sm:text-sm font-extrabold text-gray-900 truncate" x-text="videos[{{ $item->id }}]?.name" title="Nama video"></h5>
                                                    <p class="text-[11px] text-gray-500 leading-relaxed">
                                                        Video unboxing siap diunggah bersama ulasan produk ini.
                                                    </p>

                                                    <div class="flex items-center gap-2.5 pt-0.5 flex-wrap">
                                                        <button
                                                            type="button"
                                                            @click="document.getElementById('video_input_{{ $item->id }}').click()"
                                                            class="px-3.5 py-1.5 rounded-xl border border-gray-300 bg-white hover:bg-gray-50 text-[11px] font-bold text-gray-700 cursor-pointer shadow-2xs transition-all active:scale-95 inline-flex items-center gap-1.5"
                                                        >
                                                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                            </svg>
                                                            <span>Ganti Video</span>
                                                        </button>
                                                        <button
                                                            type="button"
                                                            @click="removeVideo({{ $item->id }})"
                                                            class="px-3.5 py-1.5 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-100 text-[11px] font-bold text-rose-700 cursor-pointer transition-all active:scale-95 inline-flex items-center gap-1.5"
                                                        >
                                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            <span>Hapus Video</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Loading indicator during upload -->
                                <div
                                    x-show="isSubmitting"
                                    x-cloak
                                    class="p-4 rounded-2xl bg-purple-50/90 border border-purple-200/80 text-purple-900 text-xs flex items-center gap-3 shadow-2xs"
                                >
                                    <svg class="animate-spin w-5 h-5 text-[#4F26A6] shrink-0" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    <div class="space-y-0.5">
                                        <p class="font-bold text-gray-950">Sedang Mengunggah Ulasan &amp; Berkas Media...</p>
                                        <p class="text-gray-600">Mohon tunggu beberapa saat dan jangan menutup atau memuat ulang halaman.</p>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                                    <a
                                        href="{{ route('orders.show', $order->order_number) }}"
                                        class="px-5 py-3 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-bold transition-all cursor-pointer"
                                    >
                                        Nanti Saja
                                    </a>
                                    <button
                                        type="submit"
                                        :disabled="isSubmitting"
                                        :class="isSubmitting ? 'opacity-70 cursor-not-allowed' : 'hover:opacity-95 active:scale-[0.98]'"
                                        style="background-color: #4F26A6; color: #ffffff;"
                                        class="px-6 py-3 rounded-xl text-white text-xs font-bold shadow-md transition-all cursor-pointer inline-flex items-center gap-2"
                                    >
                                        <template x-if="!isSubmitting">
                                            <span class="inline-flex items-center gap-2">
                                                <svg class="w-4 h-4 text-[#F59E0B] fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                <span>Kirim Ulasan Sekarang</span>
                                            </span>
                                        </template>
                                        <template x-if="isSubmitting">
                                            <span class="inline-flex items-center gap-2">
                                                <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                                </svg>
                                                <span>Mengunggah Media...</span>
                                            </span>
                                        </template>
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </main>
</x-layouts.app>
