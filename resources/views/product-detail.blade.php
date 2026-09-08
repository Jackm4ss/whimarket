<x-layouts.app :title="$title" activeTab="belanja">
    <div 
        class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-6"
        x-data="{
            gallery: @js($product['gallery']),
            currentIndex: 0,
            selectedColor: 'purple',
            selectedSize: '{{ $product['default_size'] }}',
            quantity: 1,
            maxStock: {{ (int) ($product['stock'] ?? 100) }},
            wishlisted: false,
            lightboxOpen: false,
            isFollowing: false,
            isZoomed: false,
            zoomX: 50,
            zoomY: 50,
            get selectedImage() {
                return this.gallery[this.currentIndex]?.main || '{{ $product['gallery'][0]['main'] }}';
            },
            selectByIndex(index) {
                this.currentIndex = (index + this.gallery.length) % this.gallery.length;
            },
            selectImage(imgSrc) {
                const foundIndex = this.gallery.findIndex(g => g.main === imgSrc);
                if (foundIndex !== -1) {
                    this.currentIndex = foundIndex;
                }
            },
            nextImage() {
                this.currentIndex = (this.currentIndex + 1) % this.gallery.length;
            },
            prevImage() {
                this.currentIndex = (this.currentIndex - 1 + this.gallery.length) % this.gallery.length;
            },
            handleMouseMove(e) {
                const rect = e.currentTarget.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 100;
                const y = ((e.clientY - rect.top) / rect.height) * 100;
                this.zoomX = Math.max(0, Math.min(100, x));
                this.zoomY = Math.max(0, Math.min(100, y));
            },
            increment() {
                if (this.quantity < this.maxStock) this.quantity++;
            },
            decrement() {
                if (this.quantity > 1) this.quantity--;
            },
            selectColor(colorId, imageSrc) {
                this.selectedColor = colorId;
                if (imageSrc) {
                    const idx = this.gallery.findIndex(g => g.main === imageSrc);
                    if (idx !== -1) {
                        this.currentIndex = idx;
                    }
                }
            }
        }"
    >
        <!-- Top Row: Breadcrumbs & Actions -->
        <div class="flex items-center justify-between gap-3 mb-4 sm:mb-6">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-1.5 sm:gap-2 text-[13px] sm:text-[14px] text-gray-500 font-medium overflow-x-auto no-scrollbar py-1">
                @foreach($product['breadcrumbs'] as $index => $crumb)
                    @if($crumb['href'])
                        <a href="{{ $crumb['href'] }}" class="hover:text-[#4F26A6] transition-colors">
                            {{ $crumb['name'] }}
                        </a>
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    @else
                        <span class="text-gray-900 font-bold truncate max-w-[140px] sm:max-w-none">
                            {{ $crumb['name'] }}
                        </span>
                    @endif
                @endforeach
            </nav>
            <!-- Top Action Icons (Removed to unify inside product image card) -->
        </div>

        <!-- Main Product Section: 2-column Layout (Left Gallery | Right Details & Options) -->
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-14 items-start mb-10 sm:mb-14 w-full">
            
            <!-- SECTION 1: Gallery (Thumbnails list + Big stage container) -->
            <div class="flex flex-col sm:flex-row gap-4 items-start w-full lg:w-[480px] xl:w-[500px] shrink-0">
                <!-- Main Featured Stage Image (With Tokopedia-style Hover Zoom on desktop, click-to-open lightbox on mobile/tablet) -->
                <div 
                    class="sm:order-2 relative w-full aspect-square bg-[#ECE5F6] rounded-2xl overflow-hidden shadow-sm lg:cursor-crosshair cursor-pointer select-none group"
                    @click="if (window.innerWidth < 1024) lightboxOpen = true"
                    @mouseenter="if (window.innerWidth >= 1024) isZoomed = true"
                    @mouseleave="isZoomed = false"
                    @mousemove="if (window.innerWidth >= 1024) handleMouseMove($event)"
                >
                    <!-- Wishlist Heart Button (Exact copy from product-card.blade.php, strictly top right) -->
                    <button
                        type="button"
                        @click.prevent.stop="wishlisted = !wishlisted"
                        style="position: absolute !important; top: 12px !important; right: 12px !important; left: auto !important;"
                        class="z-20 w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white/95 backdrop-blur-xs shadow-md flex items-center justify-center transition-transform hover:scale-105 cursor-pointer"
                        :class="wishlisted ? 'text-[#4F26A6]' : 'text-gray-700 hover:text-[#4F26A6]'"
                        title="Simpan ke Wishlist"
                    >
                        <svg
                            class="w-4 h-4 sm:w-4.5 sm:h-4.5 transition-colors"
                            viewBox="0 0 24 24"
                            :fill="wishlisted ? 'currentColor' : 'none'"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                        </svg>
                    </button>
                    <!-- Base Product Image (Cover) -->
                    <img 
                        :src="selectedImage" 
                        alt="{{ $product['title'] }}" 
                        width="500"
                        height="500"
                        decoding="async"
                        class="w-full h-full object-cover block transition-transform duration-100 ease-out pointer-events-none"
                        :style="isZoomed ? `transform: scale(2.2); transform-origin: ${zoomX}% ${zoomY}%;` : 'transform: scale(1);'"
                    />

                    <!-- Expand / Zoom Lightbox Button Bottom Right (Solid white) -->
                    <button 
                        type="button"
                        @click.stop="lightboxOpen = true"
                        class="absolute bottom-4 right-4 z-20 w-9 h-9 rounded-full bg-white shadow-lg border border-black/5 flex items-center justify-center text-gray-800 hover:text-[#4F26A6] transition-all transform hover:scale-105 active:scale-95 cursor-pointer"
                        title="Perbesar Gambar Penuh"
                    >
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                        </svg>
                    </button>
                </div>

                <!-- Thumbnails Strip (Scrollable container: horizontal on mobile, vertical max-height scrollable on desktop) -->
                <div class="sm:order-1 flex sm:flex-col gap-2 shrink-0 overflow-x-auto sm:overflow-y-auto w-full sm:w-[72px] max-h-[500px] pb-1 sm:pb-0 no-scrollbar select-none">
                    @foreach($product['gallery'] as $index => $img)
                        <button 
                            type="button"
                            @click="selectByIndex({{ $index }})"
                            class="relative w-[64px] h-[64px] sm:w-[68px] sm:h-[68px] rounded-xl overflow-hidden bg-[#F3EEFF]/40 border-2 transition-all p-1 flex items-center justify-center shrink-0 cursor-pointer"
                            :class="currentIndex === {{ $index }} ? 'border-[#4F26A6]' : 'border-transparent hover:border-gray-200 opacity-90 hover:opacity-100'"
                        >
                            <img 
                                src="{{ $img['thumb'] }}" 
                                alt="{{ $img['alt'] }}" 
                                width="64"
                                height="64"
                                decoding="async"
                                class="w-full h-full object-cover rounded-lg"
                            />
                        </button>
                    @endforeach

                    <!-- Scroll Down Arrow Indicator (Visible when more images) -->
                    <div class="hidden sm:flex justify-center pt-0.5 shrink-0">
                        <button 
                            type="button" 
                            @click="nextImage()"
                            class="w-7 h-7 rounded-full bg-white shadow-sm border border-gray-100 flex items-center justify-center text-gray-500 hover:text-gray-900 transition-colors cursor-pointer hover:bg-gray-50"
                            title="Foto Selanjutnya"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Details & Purchasing Options (Takes all remaining width) -->
            <div class="flex-1 w-full min-w-0 flex flex-col">
                <div class="mb-2.5">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[12px] font-semibold bg-[#F3EEFF] text-[#4F26A6]">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        {{ $product['badge'] }}
                    </span>
                </div>

                <!-- Product Title -->
                <h1 class="text-2xl sm:text-[28px] font-extrabold text-gray-950 tracking-tight leading-snug mb-3">
                    {{ $product['title'] }}
                </h1>

                <!-- Seller Profile Bar with Ikuti Toko Button directly beside -->
                <div class="flex items-center gap-3 sm:gap-4 mb-4 flex-wrap">
                    <div class="flex items-center gap-2.5 shrink-0">
                        <a href="{{ $product['seller']['href'] }}" class="relative shrink-0">
                            <img 
                                src="{{ $product['seller']['avatar'] }}" 
                                alt="{{ $product['seller']['name'] }}" 
                                width="40"
                                height="40"
                                class="w-10 h-10 rounded-full object-cover"
                            />
                        </a>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ $product['seller']['href'] }}" class="text-[14px] font-bold text-gray-900 hover:text-[#4F26A6] transition-colors">
                                    {{ $product['seller']['name'] }}
                                </a>
                                <x-verified-badge size="sm" class="w-3.5 h-3.5 text-[#4F26A6] shrink-0" />
                            </div>
                            <span class="text-[11.5px] text-gray-500 font-medium leading-tight">
                                {{ $product['seller']['role'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Button Ikuti Toko directly beside seller info -->
                    <button
                        type="button"
                        @click="isFollowing = !isFollowing"
                        class="shrink-0 px-3 sm:px-3.5 h-8 rounded-lg text-[12px] sm:text-[12.5px] font-bold flex items-center justify-center gap-1.5 transition-all cursor-pointer shadow-2xs active:scale-95"
                        :class="isFollowing ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-[#4F26A6] text-white hover:bg-[#3E1D85] shadow-[0_2px_8px_rgba(79,38,166,0.2)]'"
                    >
                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path x-show="isFollowing" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            <path x-show="!isFollowing" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="whitespace-nowrap" x-text="isFollowing ? 'Mengikuti' : 'Ikuti Toko'"></span>
                    </button>
                </div>
                <!-- Rating, Reviews & Terjual -->
                <div class="flex items-center gap-2 text-[13px] text-gray-600 mb-4">
                    <div class="flex items-center gap-0.5 text-[#F59E0B]">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="font-bold text-gray-900 ml-0.5">{{ $product['rating'] }}</span>
                    <span class="text-gray-500">({{ $product['review_count'] }} ulasan)</span>
                    <span class="text-gray-300">|</span>
                    <span class="font-bold text-gray-900">{{ $product['sold_count'] }}</span>
                    <span class="text-gray-500">terjual</span>
                </div>

                <!-- Big Price -->
                <div class="text-[32px] sm:text-[36px] font-black text-[#4F26A6] tracking-tight leading-none mb-4">
                    {{ $product['price_formatted'] }}
                </div>

                <!-- Product Description -->
                <p class="text-[13.5px] sm:text-[14px] text-gray-500 leading-relaxed mb-5">
                    {{ $product['description'] }}
                </p>

                <!-- Color Selection (Pilih Warna) -->
                <div class="mb-5">
                    <label class="block text-[13.5px] font-bold text-gray-900 mb-2">
                        Pilih Warna
                    </label>
                    <div class="flex items-center gap-2.5">
                        @foreach($product['colors'] as $c)
                            <button 
                                type="button"
                                @click="selectColor('{{ $c['id'] }}', '{{ $c['image'] }}')"
                                class="w-[56px] h-[56px] rounded-xl border-2 p-1 bg-[#F9F7FC] flex items-center justify-center transition-all overflow-hidden"
                                :class="selectedColor === '{{ $c['id'] }}' ? 'border-[#4F26A6]' : 'border-transparent hover:border-gray-200'"
                                title="{{ $c['name'] }}"
                            >
                                <img 
                                    src="{{ $c['image'] }}" 
                                    alt="{{ $c['name'] }}" 
                                    width="48"
                                    height="48"
                                    decoding="async"
                                    class="w-full h-full object-cover rounded-lg"
                                />
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Size Selection (Pilih Ukuran) -->
                <div class="mb-5">
                    <label class="block text-[13.5px] font-bold text-gray-900 mb-2">
                        Pilih Ukuran
                    </label>
                    <div class="flex items-center gap-2">
                        @foreach($product['sizes'] as $size)
                            <button 
                                type="button"
                                @click="selectedSize = '{{ $size }}'"
                                class="w-14 h-10 rounded-xl border text-[13.5px] font-bold transition-all flex items-center justify-center"
                                :class="selectedSize === '{{ $size }}' ? 'border-[#4F26A6] text-[#4F26A6] bg-[#F4EFFB]' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-300'"
                            >
                                {{ $size }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Quantity (Jumlah) -->
                <div class="mb-6">
                    <label class="block text-[13.5px] font-bold text-gray-900 mb-2">
                        Jumlah
                    </label>
                    <div class="flex items-center gap-4">
                        <!-- Counter Control -->
                        <div class="inline-flex items-center border border-gray-200 rounded-xl bg-white overflow-hidden shadow-sm">
                            <button 
                                type="button"
                                @click="decrement()"
                                class="w-9 h-9 flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition-colors"
                                :disabled="quantity <= 1"
                                :class="{ 'opacity-30 cursor-not-allowed': quantity <= 1 }"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/>
                                </svg>
                            </button>
                            <span class="w-10 text-center text-[13.5px] font-bold text-gray-900 select-none" x-text="quantity"></span>
                            <button 
                                type="button"
                                @click="increment()"
                                class="w-9 h-9 flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition-colors"
                                :disabled="quantity >= maxStock"
                                :class="{ 'opacity-30 cursor-not-allowed': quantity >= maxStock }"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Stock Indicator -->
                        <span class="text-[13px] text-gray-500 font-medium">
                            Stok tersedia: <span class="text-gray-900 font-bold" x-text="maxStock"></span>
                        </span>
                    </div>
                </div>

                <!-- Purchase Buttons -->
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <button 
                        type="button"
                        class="w-full sm:flex-1 h-12 rounded-xl border-2 border-[#4F26A6] bg-white text-[#4F26A6] font-bold text-[14.5px] hover:bg-[#F3EEFF]/60 transition-all flex items-center justify-center gap-2 shadow-sm"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Tambah ke Keranjang
                    </button>
                    <button 
                        type="button"
                        class="w-full sm:flex-1 h-12 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold text-[14.5px] transition-all flex items-center justify-center shadow-md shadow-[#4F26A6]/20"
                    >
                        Beli Sekarang
                    </button>
                </div>
            </div>
        </div>

        <!-- Trust Features Bar (Section Keunggulan WhiMarket - Fully Responsive Mobile, Tablet & Desktop) -->
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-gray-200/80 shadow-[0_4px_20px_rgba(0,0,0,0.03)] p-5 sm:p-7 lg:py-8 lg:px-4 mb-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-y-8 sm:gap-x-0 lg:gap-0">
                <!-- Item 1: Original & Terverifikasi -->
                <div class="flex items-center justify-start sm:justify-center relative sm:px-6 lg:px-4 pb-6 sm:pb-0 border-b sm:border-b-0 border-gray-100">
                    <div class="flex items-center gap-3.5 sm:gap-4 w-full sm:w-auto max-w-[270px]">
                        <div class="w-13 h-13 sm:w-14 sm:h-14 min-w-[52px] min-h-[52px] sm:min-w-[56px] sm:min-h-[56px] rounded-full flex items-center justify-center shrink-0" style="background-color: #EDE9FE;">
                            <svg class="w-6.5 h-6.5 sm:w-7 sm:h-7 text-[#4F26A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <h4 class="text-[15px] sm:text-[15.5px] font-bold text-gray-900 leading-tight tracking-tight">Original &amp; Terverifikasi</h4>
                            <p class="text-[12.5px] sm:text-[13px] text-gray-500 font-normal mt-1 leading-snug">Produk 100% asli</p>
                        </div>
                    </div>
                    <!-- Separator line for Tablet (Col 1 to Col 2) and Desktop -->
                    <div class="hidden sm:block absolute right-0 top-1/2 -translate-y-1/2 w-[1px] h-10 sm:h-11 bg-gray-200"></div>
                </div>

                <!-- Item 2: Dari Kreator Favoritmu -->
                <div class="flex items-center justify-start sm:justify-center relative sm:px-6 lg:px-4 pb-6 sm:pb-0 border-b sm:border-b-0 border-gray-100">
                    <div class="flex items-center gap-3.5 sm:gap-4 w-full sm:w-auto max-w-[270px]">
                        <div class="w-13 h-13 sm:w-14 sm:h-14 min-w-[52px] min-h-[52px] sm:min-w-[56px] sm:min-h-[56px] rounded-full flex items-center justify-center shrink-0" style="background-color: #EDE9FE;">
                            <svg class="w-6.5 h-6.5 sm:w-7 sm:h-7 text-[#4F26A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <h4 class="text-[15px] sm:text-[15.5px] font-bold text-gray-900 leading-tight tracking-tight">Dari Kreator Favoritmu</h4>
                            <p class="text-[12.5px] sm:text-[13px] text-gray-500 font-normal mt-1 leading-snug">Langsung dari kreator pilihan</p>
                        </div>
                    </div>
                    <!-- Separator line for Desktop only (hidden on tablet 2-col wrap) -->
                    <div class="hidden lg:block absolute right-0 top-1/2 -translate-y-1/2 w-[1px] h-11 bg-gray-200"></div>
                </div>

                <!-- Item 3: Pengiriman Cepat -->
                <div class="flex items-center justify-start sm:justify-center relative sm:px-6 lg:px-4 pb-6 sm:pb-0 border-b sm:border-b-0 border-gray-100">
                    <div class="flex items-center gap-3.5 sm:gap-4 w-full sm:w-auto max-w-[270px]">
                        <div class="w-13 h-13 sm:w-14 sm:h-14 min-w-[52px] min-h-[52px] sm:min-w-[56px] sm:min-h-[56px] rounded-full flex items-center justify-center shrink-0" style="background-color: #EDE9FE;">
                            <svg class="w-6.5 h-6.5 sm:w-7 sm:h-7 text-[#4F26A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                            </svg>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <h4 class="text-[15px] sm:text-[15.5px] font-bold text-gray-900 leading-tight tracking-tight">Pengiriman Cepat</h4>
                            <p class="text-[12.5px] sm:text-[13px] text-gray-500 font-normal mt-1 leading-snug">Diproses dalam 1×24 jam</p>
                        </div>
                    </div>
                    <!-- Separator line for Tablet (Col 3 to Col 4) and Desktop -->
                    <div class="hidden sm:block absolute right-0 top-1/2 -translate-y-1/2 w-[1px] h-10 sm:h-11 bg-gray-200"></div>
                </div>

                <!-- Item 4: Transaksi Aman -->
                <div class="flex items-center justify-start sm:justify-center relative sm:px-6 lg:px-4">
                    <div class="flex items-center gap-3.5 sm:gap-4 w-full sm:w-auto max-w-[270px]">
                        <div class="w-13 h-13 sm:w-14 sm:h-14 min-w-[52px] min-h-[52px] sm:min-w-[56px] sm:min-h-[56px] rounded-full flex items-center justify-center shrink-0" style="background-color: #EDE9FE;">
                            <svg class="w-6.5 h-6.5 sm:w-7 sm:h-7 text-[#4F26A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <h4 class="text-[15px] font-bold text-gray-900 leading-tight tracking-tight">Transaksi Aman</h4>
                            <p class="text-[12.5px] sm:text-[13px] text-gray-500 font-normal mt-1 leading-snug">Dengan sistem escrow</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Ulasan Produk (Consistent 1:1 with Seller Profile Reviews) -->
        <section class="mt-4 mb-14" x-data="{ reviewFilter: 'all', reviewSort: 'terbaru', sortDropdownOpen: false }">
            <div class="flex flex-col lg:flex-row items-start gap-8">
                <!-- Left Column: Rating Keseluruhan Sidebar Card -->
                <div class="w-full lg:w-[310px] xl:w-[330px] shrink-0 space-y-4">
                    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
                        <h3 class="text-base sm:text-[17px] font-extrabold text-gray-900 tracking-tight mb-4">
                            Rating Produk
                        </h3>
                        <div class="flex items-baseline gap-3 mb-1">
                            <span class="text-5xl font-black text-gray-900 tracking-tight">{{ $product['rating_summary']['rating'] }}</span>
                            <div class="flex items-center gap-1 text-amber-400">
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 font-medium mb-6">dari {{ $product['rating_summary']['total_reviews'] }} ulasan</p>

                        <!-- Breakdown Bars -->
                        <div class="space-y-2.5 mb-6">
                            @foreach($product['rating_summary']['breakdown'] as $row)
                                <div class="flex items-center gap-3 text-gray-700 font-medium">
                                    <span class="w-3.5 text-sm font-extrabold text-gray-900">{{ $row['star'] }}</span>
                                    <svg class="w-4 h-4 text-amber-400 fill-current shrink-0" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <div class="flex-1 h-2.5 bg-purple-50 rounded-full overflow-hidden">
                                        <div class="h-full bg-[#5022CE] rounded-full" style="width: {{ $row['pct'] }}%"></div>
                                    </div>
                                    <span class="w-11 text-right text-gray-500 text-[12.5px] font-bold">{{ $row['count'] }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Verified Note Box -->
                        <div class="bg-[#F6F4F9] rounded-2xl p-4 flex items-center gap-3.5">
                            <div class="w-9 h-9 rounded-2xl bg-[#E8E2F4] flex items-center justify-center text-[#5022CE] shrink-0">
                                <svg class="w-5 h-5 fill-none stroke-current" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div class="text-[12.5px] text-gray-900 font-semibold leading-snug">
                                <p>Ulasan asli dari pembeli</p>
                                <p>terverifikasi di Whimarket.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Reviews List & Star Filters -->
                <div class="flex-1 w-full min-w-0 bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 items-end">
                        <div class="w-full sm:w-auto">
                            <h2 class="text-[20px] sm:text-[22px] font-extrabold text-[#111827] tracking-tight">
                                Ulasan Pembeli
                            </h2>
                            <p class="text-xs sm:text-[13px] text-gray-500 font-normal mt-0.5">
                                Pengalaman nyata pembeli produk ini dari Celloszx.
                            </p>
                        </div>

                        <!-- Urutkan Dropdown (Right-aligned on mobile and desktop) -->
                        <div class="relative shrink-0 w-fit" @click.outside="sortDropdownOpen = false">
                            <button
                                type="button"
                                @click="sortDropdownOpen = !sortDropdownOpen"
                                class="inline-flex items-center justify-between gap-2.5 bg-white border border-gray-200 text-xs sm:text-[13px] font-semibold text-gray-800 rounded-xl px-3.5 sm:px-4 py-2 sm:py-2.5 focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] shadow-xs hover:border-gray-300 transition-all cursor-pointer whitespace-nowrap"
                            >
                                <span x-text="reviewSort === 'terbaru' ? 'Urutan: Terbaru' : (reviewSort === 'tertinggi' ? 'Rating Tertinggi' : 'Rating Terendah')"></span>
                                <svg
                                    class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200 shrink-0"
                                    :class="sortDropdownOpen ? 'rotate-180 text-[#4F26A6]' : ''"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Custom Floating Sort Options Panel -->
                            <div
                                x-show="sortDropdownOpen"
                                x-cloak
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-1"
                                class="absolute right-0 left-0 top-full mt-1.5 min-w-full bg-white border border-gray-100 rounded-2xl shadow-[0_12px_36px_rgba(0,0,0,0.12)] p-1.5 z-40 space-y-0.5 font-medium text-xs sm:text-[13px] text-gray-700"
                                style="display: none;"
                            >
                                <button
                                    type="button"
                                    @click="reviewSort = 'terbaru'; sortDropdownOpen = false"
                                    class="w-full text-left px-3.5 py-2 rounded-xl flex items-center hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors cursor-pointer"
                                    :class="reviewSort === 'terbaru' ? 'bg-[#F3EEFF] text-[#4F26A6] font-bold' : ''"
                                >
                                    <span>Urutan: Terbaru</span>
                                </button>
                                <button
                                    type="button"
                                    @click="reviewSort = 'tertinggi'; sortDropdownOpen = false"
                                    class="w-full text-left px-3.5 py-2 rounded-xl flex items-center hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors cursor-pointer"
                                    :class="reviewSort === 'tertinggi' ? 'bg-[#F3EEFF] text-[#4F26A6] font-bold' : ''"
                                >
                                    <span>Rating Tertinggi</span>
                                </button>
                                <button
                                    type="button"
                                    @click="reviewSort = 'terendah'; sortDropdownOpen = false"
                                    class="w-full text-left px-3.5 py-2 rounded-xl flex items-center hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors cursor-pointer"
                                    :class="reviewSort === 'terendah' ? 'bg-[#F3EEFF] text-[#4F26A6] font-bold' : ''"
                                >
                                    <span>Rating Terendah</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Star Filter Pills Row -->
                    <div class="flex items-center gap-2 sm:gap-2.5 overflow-x-auto pb-4 mb-6 border-b border-gray-100 [scrollbar-width:none]">
                        <button
                            type="button"
                            @click="reviewFilter = 'all'"
                            class="px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-xl text-xs sm:text-[12.5px] font-bold whitespace-nowrap transition-all cursor-pointer flex items-center gap-1.5 shrink-0"
                            :class="reviewFilter === 'all' ? 'border-2 border-[#5022CE] text-[#5022CE] bg-purple-50/50' : 'border border-gray-200/80 text-gray-700 bg-white hover:bg-gray-50'"
                        >
                            <span>Semua ({{ $product['rating_summary']['total_reviews'] }})</span>
                        </button>
                        <template x-for="p in [
                            { id: '5', label: '5', count: '528' },
                            { id: '4', label: '4', count: '68' },
                            { id: '3', label: '3', count: '16' },
                            { id: '2', label: '2', count: '5' },
                            { id: '1', label: '1', count: '3' }
                        ]" :key="p.id">
                            <button
                                type="button"
                                @click="reviewFilter = p.id"
                                class="px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-xl text-xs sm:text-[12.5px] font-bold whitespace-nowrap transition-all cursor-pointer flex items-center gap-1.5 shrink-0"
                                :class="reviewFilter === p.id ? 'border-2 border-[#5022CE] text-[#5022CE] bg-purple-50/50' : 'border border-gray-200/80 text-gray-700 bg-white hover:bg-gray-50'"
                            >
                                <span x-text="p.label"></span>
                                <svg class="w-3.5 h-3.5 text-amber-400 fill-current shrink-0" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="text-gray-400 font-normal" x-text="'(' + p.count + ')'"></span>
                            </button>
                        </template>
                    </div>

                    <!-- Review Items List -->
                    <div class="divide-y divide-gray-100">
                        @foreach($product['reviews'] as $rev)
                            <div class="py-6 first:pt-0 last:pb-0">
                                <div class="flex items-center gap-3 mb-2.5">
                                    <img src="{{ $rev['user_avatar'] }}" alt="{{ $rev['user_name'] }}" class="w-10 h-10 rounded-full object-cover ring-1 ring-gray-100 shrink-0" />
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-[14.5px] font-bold text-gray-900">{{ $rev['user_name'] }}</h4>
                                            <span class="text-xs text-gray-400 font-normal">{{ $rev['date'] }}</span>
                                        </div>
                                        <div class="flex items-center gap-0.5 text-amber-400 mt-1">
                                            @for($i = 0; $i < $rev['rating']; $i++)
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <p class="text-[13.5px] sm:text-[14px] text-gray-700 leading-relaxed mb-3">
                                    {{ $rev['comment'] }}
                                </p>
                                @if(!empty($rev['photos']))
                                    <div class="flex items-center gap-2.5 mb-3">
                                        @foreach($rev['photos'] as $pImg)
                                            <img src="{{ $pImg }}" alt="Review photo" class="w-20 h-20 sm:w-22 sm:h-22 rounded-xl object-cover border border-gray-100 hover:scale-105 transition-transform cursor-pointer shadow-2xs" />
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        <div 
            x-show="lightboxOpen" 
            x-cloak
            @keydown.escape.window="lightboxOpen = false"
            @keydown.left.window="if(lightboxOpen) prevImage()"
            @keydown.right.window="if(lightboxOpen) nextImage()"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/85 backdrop-blur-md p-4 sm:p-6 select-none"
        >
            <div class="relative max-w-2xl w-full aspect-square bg-white rounded-3xl overflow-hidden shadow-2xl flex items-center justify-center p-0">
                <!-- Close Button floating on top right (Solid white) -->
                <button 
                    @click="lightboxOpen = false"
                    class="absolute top-4 right-4 z-30 w-10 h-10 rounded-full bg-white shadow-lg border border-black/5 text-gray-800 hover:text-gray-950 flex items-center justify-center transition-all hover:scale-105 active:scale-95 cursor-pointer"
                    title="Tutup (Esc)"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <!-- Previous Image Button (Vertically centered on left) -->
                <button 
                    @click.stop="prevImage()"
                    style="left: 16px; top: 50%; transform: translateY(-50%);"
                    class="absolute z-30 w-11 h-11 rounded-full bg-white/95 hover:bg-white backdrop-blur-md shadow-xl text-gray-800 hover:text-[#4F26A6] flex items-center justify-center transition-all cursor-pointer hover:scale-110 active:scale-95"
                    title="Gambar Sebelumnya (←)"
                >
                    <svg class="w-6 h-6 -ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <!-- Next Image Button (Vertically centered on right) -->
                <button 
                    @click.stop="nextImage()"
                    style="right: 16px; top: 50%; transform: translateY(-50%);"
                    class="absolute z-30 w-11 h-11 rounded-full bg-white/95 hover:bg-white backdrop-blur-md shadow-xl text-gray-800 hover:text-[#4F26A6] flex items-center justify-center transition-all cursor-pointer hover:scale-110 active:scale-95"
                    title="Gambar Berikutnya (→)"
                >
                    <svg class="w-6 h-6 -mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <!-- Current Image Indicator Badge (Bottom Center) -->
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-30 px-3.5 py-1 rounded-full bg-black/60 backdrop-blur-md text-white text-[12px] font-semibold tracking-wider">
                    <span x-text="currentIndex + 1"></span> / <span x-text="gallery.length"></span>
                </div>

                <!-- Full Bleed Image View -->
                <img :src="selectedImage" alt="Zoomed view" class="w-full h-full object-cover block" />
            </div>
        </div>

    </div>
</x-layouts.app>
