@if(session('show_onboarding_modal') && auth()->check())
<div
    x-data="{
        open: true,
        loading: false,
        form: {
            recipient_name: '{{ addslashes(auth()->user()->name) }}',
            phone: '{{ auth()->user()->phone ?? '' }}',
            province: 'DKI Jakarta',
            city: 'Jakarta Selatan',
            district: '',
            postal_code: '',
            full_address: '',
        },
        async submit() {
            if (!this.form.recipient_name || !this.form.phone || !this.form.full_address || !this.form.city) {
                alert('Silakan lengkapi data penerima dan alamat.');
                return;
            }
            this.loading = true;
            try {
                const res = await fetch('{{ route('onboarding.address') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(this.form)
                });
                const data = await res.json();
                if (data.success) {
                    this.open = false;
                    window.location.reload();
                } else {
                    alert('Gagal menyimpan alamat.');
                }
            } catch (e) {
                alert('Terjadi kesalahan koneksi.');
            } finally {
                this.loading = false;
            }
        },
        async skip() {
            try {
                await fetch('{{ route('onboarding.skip') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
            } catch (e) {}
            this.open = false;
        }
    }"
    x-show="open"
    x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs overflow-y-auto"
>
    <div
        @click.outside="skip()"
        class="bg-white rounded-3xl border border-gray-100/90 shadow-[0_20px_50px_rgba(79,38,166,0.18)] max-w-lg w-full p-6 sm:p-8 relative my-8 overflow-hidden"
    >
        <!-- Top Header Illustration Banner -->
        <div class="relative h-28 sm:h-32 -mx-6 -mt-6 sm:-mx-8 sm:-mt-8 mb-6 overflow-hidden bg-gradient-to-r from-purple-100 via-[#F3EEFF] to-amber-50 flex items-center justify-between px-6 sm:px-8 border-b border-gray-100">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/90 shadow-2xs text-[#4F26A6] text-[11px] font-extrabold mb-1.5">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" /><circle cx="12" cy="10" r="3" /></svg>
                    Langkah Terakhir
                </span>
                <h2 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">Lengkapi Alamat</h2>
            </div>
            <img
                src="/assets/modals/address-header.png"
                alt="Alamat Pengiriman"
                class="w-24 h-24 sm:w-28 sm:h-28 object-contain drop-shadow-md shrink-0 -mr-2"
            />
            <button
                type="button"
                @click="skip()"
                class="absolute top-3 right-3 w-7 h-7 rounded-full bg-white/80 hover:bg-white text-gray-600 flex items-center justify-center transition-all cursor-pointer shadow-2xs"
                title="Tutup"
            >
                ✕
            </button>
        </div>

        <p class="text-xs sm:text-sm text-gray-500 mb-5">
            Simpan alamatmu sekarang agar transaksi belanja &amp; checkout berikutnya jadi serba otomatis (Zero Pain Point).
        </p>

        <!-- Form Fields -->
        <div class="space-y-4 text-left">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nama Penerima</label>
                <input
                    type="text"
                    x-model="form.recipient_name"
                    placeholder="Contoh: Budi Pratama"
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"
                />
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nomor Handphone / WhatsApp</label>
                <input
                    type="tel"
                    x-model="form.phone"
                    placeholder="Contoh: 081234567890"
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none"
                />
            </div>

            <div
                x-data="regionSelectorComponent({
                    province: form.province,
                    city: form.city,
                    district: form.district,
                    postal_code: form.postal_code
                })"
                x-effect="form.province = selectedProvince; form.city = selectedCity; form.district = selectedDistrict; form.postal_code = selectedPostalCode;"
            >
                <x-region-select-fields />
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Alamat Lengkap (Nama Jalan, No. Rumah, RT/RW)</label>
                <textarea
                    x-model="form.full_address"
                    rows="2"
                    placeholder="Contoh: Jl. Senopati No. 45, RT 02 / RW 05"
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none resize-none"
                ></textarea>
            </div>
        </div>

        <!-- Sticky Note Decoration -->
        <div class="mt-4 p-3 rounded-xl bg-amber-50/80 border border-amber-200/60 flex items-center gap-2 text-xs text-amber-900">
            <span class="font-handwriting text-base text-[#F59E0B] font-bold shrink-0">Catatan:</span>
            <span>Alamat ini bisa diubah kapan saja di profil atau saat checkout.</span>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
            <button
                type="button"
                @click="skip()"
                class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-500 hover:text-gray-800 transition-colors cursor-pointer"
            >
                Lewati / Nanti Saja
            </button>
            <button
                type="button"
                @click="submit()"
                :disabled="loading"
                class="w-full sm:w-auto px-6 py-3 rounded-xl bg-[#4F26A6] hover:bg-[#3E1D85] text-white text-sm font-bold shadow-md shadow-[#4F26A6]/20 transition-all active:scale-[0.98] cursor-pointer flex items-center justify-center gap-2"
            >
                <span x-show="!loading">Simpan &amp; Lanjutkan</span>
                <span x-show="loading" class="inline-flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4 text-white" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    Menyimpan...
                </span>
            </button>
        </div>
    </div>
</div>
@endif
