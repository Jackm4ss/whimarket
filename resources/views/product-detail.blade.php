<x-layouts.app :title="$title" activeTab="belanja">
    <div 
        class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-6"
        x-data="{
            gallery: @js($product['gallery']),
            currentIndex: 0,
            selectedColor: 'purple',
            selectedSize: '{{ $product['default_size'] }}',
            quantity: 1,
            maxStock: {{ $product['stock'] }},
            wishlisted: false,
            lightboxOpen: false,
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

            <!-- Top Action Icons (Desktop only: hidden below lg) -->
            <div class="hidden lg:flex items-center gap-2">
                <!-- Wishlist Button Desktop -->
                <button 
                    type="button"
                    @click="wishlisted = !wishlisted"
                    class="w-10 h-10 rounded-xl border border-gray-200 bg-white flex items-center justify-center text-gray-600 hover:text-[#4F26A6] transition-all shadow-sm cursor-pointer"
                    :class="{ 'text-red-500 border-red-200 bg-red-50/40': wishlisted }"
                    title="Tambah ke Wishlist"
                >
                    <svg class="w-5 h-5" :fill="wishlisted ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Main Product Section: 2-column Layout (Left Gallery | Right Details & Options) -->
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-14 items-start mb-10 sm:mb-14 w-full">
            
            <!-- SECTION 1: Gallery (Thumbnails list + Big stage container) -->
            <div class="flex flex-col sm:flex-row gap-4 items-start w-full lg:w-[480px] xl:w-[500px] shrink-0">
                <!-- Main Featured Stage Image (With Tokopedia-style Hover Zoom) -->
                <div 
                    class="sm:order-2 relative w-full aspect-square bg-[#ECE5F6] rounded-2xl overflow-hidden shadow-sm cursor-crosshair select-none group"
                    @mouseenter="isZoomed = true"
                    @mouseleave="isZoomed = false"
                    @mousemove="handleMouseMove($event)"
                >
                    <!-- Wishlist Heart Button (Mobile & Tablet inside card: top-3 right-3) -->
                    <button
                        type="button"
                        @click.prevent.stop="wishlisted = !wishlisted"
                        style="right: 14px; top: 14px;"
                        class="lg:hidden absolute z-20 w-9 h-9 rounded-full bg-white/95 backdrop-blur-sm shadow-md border border-black/5 flex items-center justify-center transition-transform hover:scale-105 active:scale-95 cursor-pointer"
                        :class="wishlisted ? 'text-[#4F26A6]' : 'text-gray-700 hover:text-[#4F26A6]'"
                        title="Simpan ke Wishlist"
                    >
                        <svg
                            class="w-4.5 h-4.5 transition-colors"
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

                <!-- Thumbnails Strip (Below Stage on Mobile, Left on Desktop) -->
                <div class="sm:order-1 flex sm:flex-col gap-2 shrink-0 overflow-x-auto sm:overflow-visible w-full sm:w-[68px] pb-1 sm:pb-0 no-scrollbar">
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

                    <!-- Down Arrow Button Indicator -->
                    <div class="hidden sm:flex justify-center pt-0.5">
                        <button 
                            type="button" 
                            class="w-7 h-7 rounded-full bg-white shadow-sm border border-gray-100 flex items-center justify-center text-gray-500 hover:text-gray-900 transition-colors"
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

                <!-- Seller Profile Bar -->
                <div class="flex items-center gap-2.5 mb-3">
                    <a href="{{ $product['seller']['href'] }}" class="relative shrink-0">
                        <img 
                            src="{{ $product['seller']['avatar'] }}" 
                            alt="{{ $product['seller']['name'] }}" 
                            width="36"
                            height="36"
                            class="w-9 h-9 rounded-full object-cover"
                        />
                    </a>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-1.5">
                            <a href="{{ $product['seller']['href'] }}" class="text-[14px] font-bold text-gray-900 hover:text-[#4F26A6] transition-colors">
                                {{ $product['seller']['name'] }}
                            </a>
                            <x-verified-badge size="sm" class="w-3.5 h-3.5 text-[#4F26A6]" />
                        </div>
                        <span class="text-[11.5px] text-gray-500 font-medium leading-tight">
                            {{ $product['seller']['role'] }}
                        </span>
                    </div>
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
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-[13.5px] font-bold text-gray-900">
                            Pilih Ukuran
                        </label>
                        <a href="#panduan-ukuran" class="inline-flex items-center gap-1.5 text-[12.5px] font-semibold text-[#4F26A6] hover:underline">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Panduan Ukuran
                        </a>
                    </div>
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


        <!-- Lightbox Modal with Next & Previous Navigation -->
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
