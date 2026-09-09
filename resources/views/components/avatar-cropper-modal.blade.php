<div
    x-data="avatarCropperModal()"
    @open-avatar-cropper.window="handleOpen($event.detail)"
    x-show="isOpen"
    x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-black/75 backdrop-blur-sm overflow-y-auto"
>
    <!-- Modal Dialog Box -->
    <div
        @click.outside="closeModal()"
        class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_25px_60px_rgba(79,38,166,0.25)] max-w-md w-full p-5 sm:p-6 relative my-auto overflow-hidden text-left select-none"
    >
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-2xl bg-purple-50 text-[#4F26A6] flex items-center justify-center shrink-0 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 class="text-base sm:text-lg font-black text-gray-900 tracking-tight leading-snug truncate">
                        Sesuaikan Foto Profil
                    </h3>
                    <p class="text-[11.5px] sm:text-xs text-gray-500 truncate">
                        Geser foto di dalam lingkaran untuk posisi terbaik
                    </p>
                </div>
            </div>
            <button
                type="button"
                @click="closeModal()"
                class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition-colors cursor-pointer shrink-0"
                title="Tutup"
            >
                ✕
            </button>
        </div>

        <!-- Hidden input for choosing another photo inside modal -->
        <input
            type="file"
            accept="image/jpeg,image/png,image/jpg,image/webp"
            x-ref="modalFileInput"
            @change="onModalFileSelected($event)"
            class="hidden"
        />

        <!-- Loading State -->
        <div x-show="isLoading" class="h-72 flex flex-col items-center justify-center gap-3 text-gray-400">
            <svg class="animate-spin w-8 h-8 text-[#4F26A6]" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <span class="text-xs font-semibold">Memuat gambar...</span>
        </div>

        <!-- Main Interactive Crop Stage -->
        <div x-show="!isLoading" class="space-y-4">
            <!-- Viewport Stage -->
            <div class="relative flex items-center justify-center">
                <div
                    x-ref="cropStage"
                    @pointerdown="onPointerDown($event)"
                    @pointermove="onPointerMove($event)"
                    @pointerup="onPointerUp($event)"
                    @pointercancel="onPointerUp($event)"
                    @wheel="onWheel($event)"
                    :class="isDragging ? 'cursor-grabbing' : 'cursor-grab'"
                    class="relative w-[280px] h-[280px] sm:w-[320px] sm:h-[320px] rounded-3xl overflow-hidden bg-gray-950 touch-none shadow-inner border border-gray-800 select-none flex items-center justify-center"
                >
                    <!-- Draggable & Scalable Image -->
                    <template x-if="imageSrc">
                        <img
                            :src="imageSrc"
                            alt="Pratinjau Foto"
                            :style="imageTransformStyle"
                            class="absolute pointer-events-none user-select-none max-w-none"
                            draggable="false"
                        />
                    </template>

                    <!-- Circular Mask Overlay (Vignette) -->
                    <div
                        class="absolute pointer-events-none rounded-full border-2 border-white/95 shadow-[0_0_0_9999px_rgba(15,23,42,0.72)]"
                        :style="'width: ' + circleDiameter + 'px; height: ' + circleDiameter + 'px;'"
                    >
                        <!-- Subtle Alignment Grid Crosshairs (visible while adjusting) -->
                        <div
                            class="w-full h-full rounded-full transition-opacity duration-200 pointer-events-none flex items-center justify-center"
                            :class="isDragging ? 'opacity-40' : 'opacity-15'"
                        >
                            <div class="w-full h-[1px] bg-white absolute"></div>
                            <div class="h-full w-[1px] bg-white absolute"></div>
                            <div class="w-2/3 h-2/3 rounded-full border border-dashed border-white absolute"></div>
                        </div>
                    </div>

                    <!-- Drag Hint Pill -->
                    <div
                        x-show="!hasMoved"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="absolute bottom-3 px-3 py-1 rounded-full bg-black/60 backdrop-blur-md text-white/90 text-[10.5px] font-semibold pointer-events-none flex items-center gap-1.5 shadow-xs"
                    >
                        <span>💡</span>
                        <span>Tahan &amp; geser foto di dalam lingkaran</span>
                    </div>
                </div>
            </div>

            <!-- Controls Section -->
            <div class="space-y-3 bg-[#FAF9FC] p-3.5 sm:p-4 rounded-2xl border border-gray-100">
                <!-- Zoom Slider -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs font-bold text-gray-700">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#4F26A6]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/>
                            </svg>
                            <span>Perbesar / Perkecil</span>
                        </span>
                        <span class="text-[11px] font-mono text-gray-500" x-text="Math.round(relativeZoom * 100) + '%'">100%</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <button
                            type="button"
                            @click="zoomOut()"
                            class="w-7 h-7 rounded-lg bg-white border border-gray-200 hover:border-[#4F26A6] text-gray-700 hover:text-[#4F26A6] font-bold text-sm flex items-center justify-center transition-colors cursor-pointer"
                            title="Perkecil"
                        >
                            −
                        </button>
                        <input
                            type="range"
                            min="1.0"
                            max="3.5"
                            step="0.01"
                            :value="relativeZoom"
                            @input="setRelativeZoom(parseFloat($event.target.value))"
                            class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-[#4F26A6]"
                        />
                        <button
                            type="button"
                            @click="zoomIn()"
                            class="w-7 h-7 rounded-lg bg-white border border-gray-200 hover:border-[#4F26A6] text-gray-700 hover:text-[#4F26A6] font-bold text-sm flex items-center justify-center transition-colors cursor-pointer"
                            title="Perbesar"
                        >
                            +
                        </button>
                    </div>
                </div>

                <!-- Action Tools (Rotate, Center, Pick Other) -->
                <div class="flex items-center justify-between gap-2 pt-1 border-t border-gray-200/60">
                    <button
                        type="button"
                        @click="rotateClockwise()"
                        class="px-2.5 py-1.5 rounded-xl bg-white border border-gray-200 hover:border-[#4F26A6] text-gray-700 hover:text-[#4F26A6] text-[11.5px] font-bold flex items-center gap-1.5 transition-colors cursor-pointer"
                        title="Putar 90 Derajat"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Putar 90°</span>
                    </button>

                    <button
                        type="button"
                        @click="resetPosition()"
                        class="px-2.5 py-1.5 rounded-xl bg-white border border-gray-200 hover:border-[#4F26A6] text-gray-700 hover:text-[#4F26A6] text-[11.5px] font-bold flex items-center gap-1.5 transition-colors cursor-pointer"
                        title="Kembali ke Tengah"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m0 14v1m8-8h-1M5 12H4m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707"/>
                        </svg>
                        <span>Pusatkan</span>
                    </button>

                    <button
                        type="button"
                        @click="$refs.modalFileInput.click()"
                        class="px-2.5 py-1.5 rounded-xl bg-white border border-gray-200 hover:border-[#4F26A6] text-gray-700 hover:text-[#4F26A6] text-[11.5px] font-bold flex items-center gap-1.5 transition-colors cursor-pointer"
                        title="Pilih file gambar lain"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <span>Ganti Foto</span>
                    </button>
                </div>
            </div>

            <!-- Modal Footer Buttons -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <button
                    type="button"
                    @click="closeModal()"
                    class="px-4 py-2.5 rounded-xl text-xs font-semibold text-gray-500 hover:text-gray-800 transition-colors cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="button"
                    @click="applyCrop()"
                    :disabled="isLoading"
                    class="px-5 sm:px-6 py-2.5 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white text-xs sm:text-sm font-bold shadow-md shadow-[#4F26A6]/20 transition-all active:scale-[0.98] cursor-pointer flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Terapkan Foto</span>
                </button>
            </div>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
    document.addEventListener('alpine:init', function () {
        Alpine.data('avatarCropperModal', function () {
            return {
                isOpen: false,
                isLoading: false,
                imageSrc: '',
                imageElement: null,
                imageNaturalWidth: 0,
                imageNaturalHeight: 0,
                target: null,

                circleDiameter: 250,

                // Transforms
                scale: 1.0,
                minScale: 1.0,
                maxScale: 3.5,
                relativeZoom: 1.0,
                translateX: 0,
                translateY: 0,
                rotation: 0,

                // Interaction state
                isDragging: false,
                hasMoved: false,
                dragStartX: 0,
                dragStartY: 0,
                initialTranslateX: 0,
                initialTranslateY: 0,
                activePointers: new Map(),
                initialPinchDistance: 0,
                initialPinchZoom: 1.0,

                get imageTransformStyle() {
                    if (!this.imageNaturalWidth || !this.imageNaturalHeight) return '';
                    var left = '50%';
                    var top = '50%';
                    var ml = -(this.imageNaturalWidth / 2) + 'px';
                    var mt = -(this.imageNaturalHeight / 2) + 'px';
                    var transform = 'translate(' + this.translateX + 'px, ' + this.translateY + 'px) rotate(' + this.rotation + 'deg) scale(' + this.scale + ')';
                    return 'left: ' + left + '; top: ' + top + '; width: ' + this.imageNaturalWidth + 'px; height: ' + this.imageNaturalHeight + 'px; margin-left: ' + ml + '; margin-top: ' + mt + '; transform: ' + transform + '; transform-origin: center center;';
                },

                handleOpen(detail) {
                    this.target = (detail && detail.target) ? detail.target : null;
                    if (detail && detail.file) {
                        this.openWithFile(detail.file);
                    } else if (detail && detail.currentUrl) {
                        this.openWithUrl(detail.currentUrl);
                    }
                },

                openWithFile(file) {
                    var self = this;
                    this.isLoading = true;
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        self.initImage(e.target.result);
                    };
                    reader.onerror = function () {
                        self.isLoading = false;
                        alert('Gagal membaca file gambar.');
                    };
                    reader.readAsDataURL(file);
                },

                openWithUrl(url) {
                    this.initImage(url);
                },

                initImage(src) {
                    var self = this;
                    this.isLoading = true;
                    this.imageSrc = src;
                    var img = new Image();
                    img.crossOrigin = 'anonymous';
                    img.onload = function () {
                        self.imageNaturalWidth = img.naturalWidth;
                        self.imageNaturalHeight = img.naturalHeight;
                        self.imageElement = img;
                        self.rotation = 0;
                        self.hasMoved = false;
                        self.calculateInitialTransform();
                        self.isLoading = false;
                        self.isOpen = true;
                    };
                    img.onerror = function () {
                        self.isLoading = false;
                        alert('Gagal memuat pratinjau gambar. Silakan unggah gambar dari perangkat kamu.');
                    };
                    img.src = src;
                },

                calculateInitialTransform() {
                    var isRotated = (this.rotation % 180 !== 0);
                    var effW = isRotated ? this.imageNaturalHeight : this.imageNaturalWidth;
                    var effH = isRotated ? this.imageNaturalWidth : this.imageNaturalHeight;

                    // Ensure minimum scale completely covers circular cutout
                    this.minScale = Math.max(this.circleDiameter / effW, this.circleDiameter / effH);
                    this.maxScale = this.minScale * 3.5;
                    this.relativeZoom = 1.0;
                    this.scale = this.minScale;
                    this.translateX = 0;
                    this.translateY = 0;
                    this.clampPosition();
                },

                clampPosition() {
                    var isRotated = (this.rotation % 180 !== 0);
                    var effW = isRotated ? this.imageNaturalHeight : this.imageNaturalWidth;
                    var effH = isRotated ? this.imageNaturalWidth : this.imageNaturalHeight;

                    var renderedW = effW * this.scale;
                    var renderedH = effH * this.scale;

                    var maxOffsetX = Math.max(0, (renderedW - this.circleDiameter) / 2);
                    var maxOffsetY = Math.max(0, (renderedH - this.circleDiameter) / 2);

                    if (this.translateX > maxOffsetX) this.translateX = maxOffsetX;
                    if (this.translateX < -maxOffsetX) this.translateX = -maxOffsetX;
                    if (this.translateY > maxOffsetY) this.translateY = maxOffsetY;
                    if (this.translateY < -maxOffsetY) this.translateY = -maxOffsetY;
                },

                setRelativeZoom(val) {
                    var bounded = Math.min(Math.max(val, 1.0), 3.5);
                    this.relativeZoom = Math.round(bounded * 100) / 100;
                    this.scale = this.minScale * this.relativeZoom;
                    this.hasMoved = true;
                    this.clampPosition();
                },

                zoomIn() {
                    this.setRelativeZoom(this.relativeZoom + 0.25);
                },

                zoomOut() {
                    this.setRelativeZoom(this.relativeZoom - 0.25);
                },

                rotateClockwise() {
                    this.rotation = (this.rotation + 90) % 360;
                    var isRotated = (this.rotation % 180 !== 0);
                    var effW = isRotated ? this.imageNaturalHeight : this.imageNaturalWidth;
                    var effH = isRotated ? this.imageNaturalWidth : this.imageNaturalHeight;

                    this.minScale = Math.max(this.circleDiameter / effW, this.circleDiameter / effH);
                    this.maxScale = this.minScale * 3.5;
                    this.scale = this.minScale * this.relativeZoom;
                    this.hasMoved = true;
                    this.clampPosition();
                },

                resetPosition() {
                    this.translateX = 0;
                    this.translateY = 0;
                    this.setRelativeZoom(1.0);
                },

                onPointerDown(e) {
                    if (!this.imageElement) return;
                    this.isDragging = true;
                    this.hasMoved = true;
                    this.activePointers.set(e.pointerId, { x: e.clientX, y: e.clientY });

                    try {
                        e.currentTarget.setPointerCapture(e.pointerId);
                    } catch (err) {}

                    if (this.activePointers.size === 1) {
                        this.dragStartX = e.clientX;
                        this.dragStartY = e.clientY;
                        this.initialTranslateX = this.translateX;
                        this.initialTranslateY = this.translateY;
                    } else if (this.activePointers.size === 2) {
                        var pts = Array.from(this.activePointers.values());
                        this.initialPinchDistance = Math.hypot(pts[0].x - pts[1].x, pts[0].y - pts[1].y);
                        this.initialPinchZoom = this.relativeZoom;
                    }
                },

                onPointerMove(e) {
                    if (!this.isDragging) return;
                    if (this.activePointers.has(e.pointerId)) {
                        this.activePointers.set(e.pointerId, { x: e.clientX, y: e.clientY });
                    }

                    if (this.activePointers.size === 1) {
                        var deltaX = e.clientX - this.dragStartX;
                        var deltaY = e.clientY - this.dragStartY;
                        this.translateX = this.initialTranslateX + deltaX;
                        this.translateY = this.initialTranslateY + deltaY;
                        this.clampPosition();
                    } else if (this.activePointers.size === 2) {
                        var pts = Array.from(this.activePointers.values());
                        var currentDist = Math.hypot(pts[0].x - pts[1].x, pts[0].y - pts[1].y);
                        if (this.initialPinchDistance > 0) {
                            var ratio = currentDist / this.initialPinchDistance;
                            this.setRelativeZoom(this.initialPinchZoom * ratio);
                        }
                    }
                },

                onPointerUp(e) {
                    this.activePointers.delete(e.pointerId);
                    try {
                        e.currentTarget.releasePointerCapture(e.pointerId);
                    } catch (err) {}

                    if (this.activePointers.size === 0) {
                        this.isDragging = false;
                    } else if (this.activePointers.size === 1) {
                        var remaining = Array.from(this.activePointers.values())[0];
                        this.dragStartX = remaining.x;
                        this.dragStartY = remaining.y;
                        this.initialTranslateX = this.translateX;
                        this.initialTranslateY = this.translateY;
                    }
                },

                onWheel(e) {
                    e.preventDefault();
                    var delta = e.deltaY < 0 ? 0.15 : -0.15;
                    this.setRelativeZoom(this.relativeZoom + delta);
                },

                onModalFileSelected(e) {
                    var file = e.target.files[0];
                    if (file) {
                        this.openWithFile(file);
                        e.target.value = '';
                    }
                },

                closeModal() {
                    this.isOpen = false;
                    this.isDragging = false;
                    this.activePointers.clear();
                },

                applyCrop() {
                    var self = this;
                    if (!this.imageElement) return;

                    var outputSize = 512;
                    var canvas = document.createElement('canvas');
                    canvas.width = outputSize;
                    canvas.height = outputSize;
                    var ctx = canvas.getContext('2d');

                    ctx.imageSmoothingEnabled = true;
                    ctx.imageSmoothingQuality = 'high';

                    var ratio = outputSize / this.circleDiameter;

                    ctx.translate(outputSize / 2 + this.translateX * ratio, outputSize / 2 + this.translateY * ratio);
                    ctx.rotate((this.rotation * Math.PI) / 180);

                    var drawW = this.imageNaturalWidth * this.scale * ratio;
                    var drawH = this.imageNaturalHeight * this.scale * ratio;

                    try {
                        ctx.drawImage(
                            this.imageElement,
                            -drawW / 2,
                            -drawH / 2,
                            drawW,
                            drawH
                        );
                    } catch (err) {
                        alert('Tidak dapat memproses foto ini. Silakan unggah foto dari galeri/file kamu.');
                        return;
                    }

                    try {
                        canvas.toBlob(function (blob) {
                            if (!blob) {
                                alert('Gagal memproses gambar avatar.');
                                return;
                            }
                            var file = new File([blob], 'avatar.jpg', { type: 'image/jpeg', lastModified: Date.now() });
                            var dataUrl = canvas.toDataURL('image/jpeg', 0.92);

                            window.dispatchEvent(new CustomEvent('avatar-cropped', {
                                detail: {
                                    file: file,
                                    blob: blob,
                                    dataUrl: dataUrl,
                                    target: self.target
                                }
                            }));

                            self.closeModal();
                        }, 'image/jpeg', 0.92);
                    } catch (err) {
                        alert('Gambar tidak dapat diekspor karena batasan keamanan sumber gambar eksternal. Silakan pilih foto dari file lokal.');
                    }
                }
            };
        });
    });
</script>
@endpush
@endonce
