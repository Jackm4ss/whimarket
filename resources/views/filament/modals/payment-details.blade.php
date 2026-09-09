<div style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.875rem; color: #334155;">
    {{-- Status Banner --}}
    @php
        $statusEnum = $payment->status instanceof \App\Enums\PaymentStatus 
            ? $payment->status 
            : \App\Enums\PaymentStatus::tryFrom((string) $payment->status);
            
        $statusConfig = match ($statusEnum) {
            \App\Enums\PaymentStatus::VERIFIED => ['label' => 'Terverifikasi Sah', 'bg' => '#DCFCE7', 'text' => '#15803D', 'border' => '#BBF7D0'],
            \App\Enums\PaymentStatus::PENDING_REVIEW => ['label' => 'Menunggu Verifikasi Admin', 'bg' => '#FEF3C7', 'text' => '#D97706', 'border' => '#FDE68A'],
            \App\Enums\PaymentStatus::REJECTED => ['label' => 'Bukti Ditolak', 'bg' => '#FEE2E2', 'text' => '#DC2626', 'border' => '#FECACA'],
            default => ['label' => 'Menunggu Transfer Pembeli', 'bg' => '#F1F5F9', 'text' => '#475569', 'border' => '#CBD5E1'],
        };
        $proofUrl = $payment->proof_path ? asset('storage/' . $payment->proof_path) : null;
        $order = $payment->order;
    @endphp

    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.875rem 1rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 0.75rem;">
        <div>
            <div style="font-size: 0.75rem; color: #64748B; font-weight: 500;">Status Pembayaran Saat Ini</div>
            <div style="display: inline-flex; align-items: center; gap: 0.375rem; margin-top: 0.25rem; padding: 0.25rem 0.625rem; border-radius: 9999px; background: {{ $statusConfig['bg'] }}; color: {{ $statusConfig['text'] }}; border: 1px solid {{ $statusConfig['border'] }}; font-size: 0.8125rem; font-weight: 700;">
                {{ $statusConfig['label'] }}
            </div>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 0.75rem; color: #64748B; font-weight: 500;">Waktu Pengunggahan Bukti</div>
            <div style="font-size: 0.75rem; font-weight: 600; color: #1E293B; margin-top: 0.25rem;">
                {{ $payment->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}
            </div>
        </div>
    </div>

    {{-- Rejection Note if any --}}
    @if($payment->rejection_reason)
        <div style="padding: 0.75rem 1rem; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 0.5rem; color: #991B1B; font-size: 0.8125rem;">
            <strong style="font-weight: 700;">Alasan Penolakan:</strong> {{ $payment->rejection_reason }}
        </div>
    @endif

    {{-- Transfer Proof Image Preview --}}
    <div style="border: 1px solid #E2E8F0; border-radius: 0.75rem; overflow: hidden; background: #FFFFFF;">
        <div style="padding: 0.625rem 1rem; background: #F1F5F9; border-bottom: 1px solid #E2E8F0; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; display: flex; justify-content: space-between; align-items: center;">
            <span>Bukti Transfer Pembeli</span>
            @if($proofUrl)
                <a href="{{ $proofUrl }}" target="_blank" style="color: #4F26A6; font-size: 0.75rem; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
                    Buka Gambar Penuh
                    <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            @endif
        </div>
        <div style="padding: 1rem; display: flex; justify-content: center; align-items: center; background: #F8FAFC; min-height: 180px;">
            @if($proofUrl)
                <img src="{{ $proofUrl }}" alt="Bukti Transfer #{{ $payment->id }}" style="max-height: 360px; max-width: 100%; border-radius: 0.5rem; border: 1px solid #E2E8F0; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05); object-fit: contain;">
            @else
                <div style="text-align: center; color: #94A3B8; padding: 2rem;">
                    <svg style="width: 2.5rem; height: 2.5rem; margin: 0 auto 0.5rem auto; color: #CBD5E1;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <div>Belum ada file bukti transfer yang diunggah pembeli</div>
                </div>
            @endif
        </div>
    </div>

    {{-- Items List (Lagi beli apa!) --}}
    @if($order && $order->items->isNotEmpty())
        <div style="border: 1px solid #E2E8F0; border-radius: 0.75rem; overflow: hidden; background: #FFFFFF;">
            <div style="padding: 0.625rem 1rem; background: #F1F5F9; border-bottom: 1px solid #E2E8F0; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; display: flex; justify-content: space-between; align-items: center;">
                <span>Produk yang Dibeli ({{ $order->items->count() }})</span>
                @if($order->seller)
                    <span style="text-transform: none; font-weight: normal; font-size: 0.75rem;">
                        Toko: 
                        <a href="{{ route('filament.admin.resources.sellers.index', ['tableSearch' => $order->seller->store_name]) }}" target="_blank" style="color: #4F26A6; font-weight: 700; text-decoration: none;">
                            {{ $order->seller->store_name }} &rarr;
                        </a>
                    </span>
                @endif
            </div>
            <div style="padding: 0.5rem;">
                @foreach($order->items as $item)
                    @php
                        $product = $item->variant?->product;
                        $imageUrl = $product?->primary_image_url ?? $product?->images?->first()?->image_path;
                        $productName = $item->product_name_snapshot ?? $product?->name ?? 'Produk WhiMarket';
                        $unitPrice = (float) ($item->price_snapshot ?? ($item->quantity > 0 ? $item->subtotal / $item->quantity : 0));
                    @endphp
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.625rem 0.5rem; border-bottom: 1px solid #F1F5F9;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 2.75rem; height: 2.75rem; border-radius: 0.5rem; background: #F1F5F9; border: 1px solid #E2E8F0; overflow: hidden; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                                @if($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $productName }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <svg style="width: 1.25rem; height: 1.25rem; color: #94A3B8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #0F172A; font-size: 0.875rem; display: flex; align-items: center; flex-wrap: wrap; gap: 0.375rem;">
                                    @if($product)
                                        <a href="{{ route('filament.admin.resources.products.index', ['tableSearch' => $product->name]) }}" target="_blank" style="color: #4F26A6; text-decoration: none;">
                                            {{ $productName }}
                                        </a>
                                    @else
                                        <span>{{ $productName }}</span>
                                    @endif
                                    @if($categoryName = $product?->category?->name)
                                        <span style="font-size: 0.6875rem; background: #EEF2FF; color: #4338CA; padding: 0.1rem 0.45rem; border-radius: 0.25rem; font-weight: 600; border: 1px solid #C7D2FE;">
                                            Kategori: {{ $categoryName }}
                                        </span>
                                    @endif
                                    @if($item->variant_name_snapshot)
                                        <span style="font-size: 0.6875rem; background: #F1F5F9; color: #475569; padding: 0.1rem 0.4rem; border-radius: 0.25rem; font-weight: 600; border: 1px solid #E2E8F0;">
                                            {{ $item->variant_name_snapshot }}
                                        </span>
                                    @endif
                                </div>
                                <div style="font-size: 0.75rem; color: #64748B; margin-top: 0.25rem;">
                                    {{ $item->quantity }}x &bull; Rp {{ number_format($unitPrice, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        <div style="font-size: 0.875rem; font-weight: 700; color: #0F172A;">
                            Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Details Grid (Pengirim, Toko, Rekening Tujuan) --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 0.75rem;">
        {{-- Pengirim --}}
        <div style="padding: 0.875rem; border: 1px solid #E2E8F0; border-radius: 0.75rem; background: #FFFFFF;">
            <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748B; margin-bottom: 0.375rem; display: flex; align-items: center; gap: 0.375rem;">
                <svg style="width: 14px; height: 14px; color: #94A3B8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Rekening Pengirim
            </div>
            <div style="font-weight: 700; color: #0F172A; font-size: 0.875rem;">
                {{ $payment->sender_account_name ?: ($payment->order?->buyer?->name ?? 'Belum Diisi') }}
            </div>
            <div style="font-size: 0.75rem; color: #475569; margin-top: 0.25rem;">
                Bank: <strong style="color: #1E293B;">{{ $payment->sender_bank_name ?: '-' }}</strong>
            </div>
            <div style="font-size: 0.75rem; color: #64748B; margin-top: 0.25rem;">
                Akun Pembeli: {{ $payment->order?->buyer?->name ?? '-' }} ({{ $payment->order?->buyer?->email ?? '-' }})
            </div>
        </div>

        {{-- Toko Penjual (Ke toko siapa!) --}}
        <div style="padding: 0.875rem; border: 1px solid #E2E8F0; border-radius: 0.75rem; background: #FFFFFF;">
            <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748B; margin-bottom: 0.375rem; display: flex; align-items: center; gap: 0.375rem;">
                <svg style="width: 14px; height: 14px; color: #94A3B8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Toko Penjual (Seller)
            </div>
            <div style="font-weight: 700; color: #0F172A; font-size: 0.875rem;">
                @if($order && $order->seller)
                    <a href="{{ route('filament.admin.resources.sellers.index', ['tableSearch' => $order->seller->store_name]) }}" target="_blank" style="color: #4F26A6; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
                        <span>{{ $order->seller->store_name }}</span>
                        <svg style="width: 12px; height: 12px; opacity: 0.7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                @else
                    <strong style="color: #334155;">Seller WhiMarket</strong>
                @endif
            </div>
            <div style="font-size: 0.75rem; color: #475569; margin-top: 0.25rem; display: flex; align-items: center; gap: 0.375rem;">
                <span>No. Pesanan:</span> 
                @if($order)
                    <code style="background: #F1F5F9; padding: 0.15rem 0.4rem; border-radius: 0.25rem; font-family: monospace; font-size: 0.75rem; font-weight: 700; color: #4F26A6; user-select: all;" title="Nomor Pesanan">
                        #{{ $order->order_number }}
                    </code>
                @else
                    <span>-</span>
                @endif
            </div>
            <div style="font-size: 0.75rem; color: #64748B; margin-top: 0.25rem;">
                Status Pesanan: <strong style="color: #334155;">{{ strtoupper((string) ($order?->status ?? '-')) }}</strong>
            </div>
        </div>

        {{-- Rekening Tujuan --}}
        <div style="padding: 0.875rem; border: 1px solid #E2E8F0; border-radius: 0.75rem; background: #FFFFFF;">
            <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748B; margin-bottom: 0.375rem; display: flex; align-items: center; gap: 0.375rem;">
                <svg style="width: 14px; height: 14px; color: #94A3B8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                </svg>
                Rekening Tujuan Marketplace
            </div>
            <div style="font-weight: 700; color: #0F172A; font-size: 0.875rem;">
                {{ $payment->bank_destination ?: 'BCA' }} Escrow WhiMarket
            </div>
            <div style="font-size: 0.75rem; color: #475569; margin-top: 0.25rem;">
                Atas Nama: <strong>PT WhiMarket Indonesia</strong>
            </div>
            <div style="font-size: 0.75rem; color: #64748B; margin-top: 0.25rem;">
                Tipe: Rekening Bersama Escrow Otomatis
            </div>
        </div>
    </div>

    {{-- Transaction & Linked Order Summary --}}
    <div style="padding: 0.875rem; background: #FBF9FF; border: 1px solid #E9D5FF; border-radius: 0.75rem; display: flex; flex-direction: column; gap: 0.375rem; font-size: 0.75rem;">
        @if($order)
            <div style="display: flex; justify-content: space-between; color: #64748B;">
                <span>Subtotal Produk</span>
                <span style="font-weight: 600; color: #1E293B;">Rp {{ number_format((float) $order->total_amount, 0, ',', '.') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; color: #64748B;">
                <span>Biaya Pengiriman (Ongkir)</span>
                <span style="font-weight: 600; color: #1E293B;">Rp {{ number_format((float) $order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            @if((float) ($order->admin_fee ?? 0) > 0)
            <div style="display: flex; justify-content: space-between; color: #64748B;">
                <span>Biaya Layanan (Fee Admin)</span>
                <span style="font-weight: 600; color: #1E293B;">Rp {{ number_format((float) $order->admin_fee, 0, ',', '.') }}</span>
            </div>
            @endif
            <div style="display: flex; justify-content: space-between; color: #64748B;">
                <span>Total Tagihan Pesanan</span>
                <span style="font-weight: 600; color: #1E293B;">Rp {{ number_format((float) $order->grand_total, 0, ',', '.') }}</span>
            </div>
        @endif
        <div style="border-top: 1px solid #E5DBFF; padding-top: 0.375rem; display: flex; justify-content: space-between; font-size: 0.875rem; font-weight: 700; color: #4F26A6;">
            <span>Nominal Ditransfer Pembeli</span>
            <span style="font-size: 1.0625rem; color: #4F26A6;">Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}</span>
        </div>
    </div>
</div>
