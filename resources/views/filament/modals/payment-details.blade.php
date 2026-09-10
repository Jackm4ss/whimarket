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

        $buyerUser = $order?->buyer;
        $addressPhone = $order?->address_snapshot['phone'] ?? null;
        $userPhone = $buyerUser?->phone ?? null;
        $buyerPhone = $addressPhone ?: $userPhone;

        $cleanWa = null;
        if ($buyerPhone) {
            $digits = preg_replace('/[^0-9]/', '', (string) $buyerPhone);
            if (!empty($digits)) {
                if (str_starts_with($digits, '0')) {
                    $cleanWa = '62' . substr($digits, 1);
                } elseif (str_starts_with($digits, '8')) {
                    $cleanWa = '62' . $digits;
                } elseif (str_starts_with($digits, '62')) {
                    $cleanWa = $digits;
                } else {
                    $cleanWa = $digits;
                }
            }
        }
        $waText = 'Halo ' . ($buyerUser?->name ?? 'Kak') . ', kami dari admin WhiMarket terkait verifikasi pembayaran pesanan #' . ($order?->order_number ?? '');
        $waUrl = $cleanWa ? 'https://wa.me/' . $cleanWa . '?text=' . rawurlencode($waText) : null;

        $seller = $order?->seller;
        $sellerUser = $seller?->user;
        $sellerEmail = $sellerUser?->email ?? null;
        $sellerPhone = $sellerUser?->phone ?: ($sellerUser?->addresses?->first()?->phone ?? null);

        $cleanSellerWa = null;
        if ($sellerPhone) {
            $digits = preg_replace('/[^0-9]/', '', (string) $sellerPhone);
            if (!empty($digits)) {
                if (str_starts_with($digits, '0')) {
                    $cleanSellerWa = '62' . substr($digits, 1);
                } elseif (str_starts_with($digits, '8')) {
                    $cleanSellerWa = '62' . $digits;
                } elseif (str_starts_with($digits, '62')) {
                    $cleanSellerWa = $digits;
                } else {
                    $cleanSellerWa = $digits;
                }
            }
        }
        $sellerWaText = 'Halo ' . ($sellerUser?->name ?? $seller?->store_name ?? 'Kak') . ' (' . ($seller?->store_name ?? 'Toko WhiMarket') . '), kami dari admin WhiMarket terkait verifikasi pembayaran pesanan #' . ($order?->order_number ?? '');
        $sellerWaUrl = $cleanSellerWa ? 'https://wa.me/' . $cleanSellerWa . '?text=' . rawurlencode($sellerWaText) : null;
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

    {{-- Quick Buyer & WhatsApp Contact Banner --}}
    @if($buyerPhone || $buyerUser)
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; padding: 0.75rem 1rem; background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 0.75rem;">
            <div style="display: flex; align-items: center; gap: 0.625rem;">
                <div style="width: 2.25rem; height: 2.25rem; border-radius: 9999px; background: #DCFCE7; display: flex; align-items: center; justify-content: center; color: #16A34A; flex-shrink: 0; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <svg style="width: 1.25rem; height: 1.25rem;" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                </div>
                <div>
                    <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #166534;">
                        Kontak WhatsApp / No. HP Pembeli
                    </div>
                    <div style="font-size: 0.875rem; font-weight: 700; color: #14532D; display: flex; align-items: center; flex-wrap: wrap; gap: 0.375rem; margin-top: 0.125rem;">
                        <span>{{ $buyerUser?->name ?? 'Pembeli' }}</span>
                        <span style="font-weight: 400; color: #16A34A;">&bull;</span>
                        <code style="background: #DCFCE7; color: #15803D; padding: 0.15rem 0.5rem; border-radius: 0.375rem; font-family: monospace; font-size: 0.8125rem; border: 1px solid #BBF7D0; user-select: all;" title="Klik dua kali untuk menyalin nomor">
                            {{ $buyerPhone ?: 'Nomor HP Belum Terdaftar' }}
                        </code>
                    </div>
                </div>
            </div>
            @if($waUrl)
                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 0.375rem; background: #16A34A; color: #FFFFFF; font-size: 0.75rem; font-weight: 700; padding: 0.375rem 0.75rem; border-radius: 9999px; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.1); transition: background 0.15s ease;" onmouseover="this.style.background='#15803D'" onmouseout="this.style.background='#16A34A'" title="Buka Chat WhatsApp Langsung">
                    <svg style="width: 13px; height: 13px;" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                    <span>Chat WhatsApp</span>
                    <svg style="width: 11px; height: 11px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            @endif
        </div>
    @endif
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
        {{-- Pengirim & Kontak Pembeli --}}
        <div style="padding: 0.875rem; border: 1px solid #E2E8F0; border-radius: 0.75rem; background: #FFFFFF; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748B; margin-bottom: 0.375rem; display: flex; align-items: center; gap: 0.375rem;">
                    <svg style="width: 14px; height: 14px; color: #94A3B8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Rekening Pengirim
                </div>
                <div style="font-weight: 700; color: #0F172A; font-size: 0.875rem;">
                    {{ $payment->sender_account_name ?: ($buyerUser?->name ?? 'Belum Diisi') }}
                </div>
                <div style="font-size: 0.75rem; color: #475569; margin-top: 0.25rem;">
                    Bank: <strong style="color: #1E293B;">{{ $payment->sender_bank_name ?: '-' }}</strong>
                </div>
                <div style="font-size: 0.75rem; color: #64748B; margin-top: 0.25rem;">
                    Akun Pembeli: {{ $buyerUser?->name ?? '-' }} ({{ $buyerUser?->email ?? '-' }})
                </div>
            </div>

            {{-- Kontak WhatsApp Pembeli --}}
            <div style="margin-top: 0.625rem; padding-top: 0.5rem; border-top: 1px dashed #E2E8F0;">
                <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #166534; margin-bottom: 0.25rem; display: flex; align-items: center; justify-content: space-between;">
                    <span style="display: flex; align-items: center; gap: 0.25rem;">
                        <svg style="width: 13px; height: 13px; color: #22C55E; flex-shrink: 0;" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        WhatsApp / HP
                    </span>
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.375rem; margin-top: 0.125rem;">
                    <span style="font-weight: 700; color: #0F172A; font-size: 0.8125rem; font-family: monospace;">
                        {{ $buyerPhone ?: 'Belum diisi' }}
                    </span>
                    @if($waUrl)
                        <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 0.25rem; background: #22C55E; color: #FFFFFF; font-size: 0.6875rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 9999px; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05);" title="Chat WhatsApp Pembeli">
                            <svg style="width: 10px; height: 10px;" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                            <span>Chat WA</span>
                        </a>
                    @endif
                </div>
                @if($addressPhone && $userPhone && $addressPhone !== $userPhone)
                    <div style="font-size: 0.6875rem; color: #64748B; margin-top: 0.25rem;">
                        No. Akun: {{ $userPhone }} &bull; Penerima: {{ $addressPhone }}
                    </div>
                @endif
            </div>
        </div>
        {{-- Toko Penjual (Ke toko siapa!) --}}
        <div style="padding: 0.875rem; border: 1px solid #E2E8F0; border-radius: 0.75rem; background: #FFFFFF; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748B; margin-bottom: 0.375rem; display: flex; align-items: center; gap: 0.375rem;">
                    <svg style="width: 14px; height: 14px; color: #94A3B8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Toko Penjual (Seller)
                </div>
                <div style="font-weight: 700; color: #0F172A; font-size: 0.875rem;">
                    @if($seller)
                        <a href="{{ route('filament.admin.resources.sellers.index', ['tableSearch' => $seller->store_name]) }}" target="_blank" style="color: #4F26A6; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;" title="Buka Profil Toko di Admin">
                            <span>{{ $seller->store_name }}</span>
                            <svg style="width: 12px; height: 12px; opacity: 0.7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    @else
                        <strong style="color: #334155;">Seller WhiMarket</strong>
                    @endif
                </div>
                @if($sellerUser && $sellerUser->name !== ($seller?->store_name ?? ''))
                    <div style="font-size: 0.75rem; color: #475569; margin-top: 0.15rem;">
                        Pemilik: <strong style="color: #1E293B;">{{ $sellerUser->name }}</strong>
                    </div>
                @endif
                <div style="font-size: 0.75rem; color: #64748B; margin-top: 0.25rem; display: flex; align-items: center; gap: 0.25rem; flex-wrap: wrap;">
                    <span>Email:</span>
                    @if($sellerEmail)
                        <a href="mailto:{{ $sellerEmail }}" style="color: #4F26A6; font-weight: 600; text-decoration: none; word-break: break-all;" title="Kirim Email ke Seller">
                            {{ $sellerEmail }}
                        </a>
                    @else
                        <span style="color: #94A3B8;">Belum terdaftar</span>
                    @endif
                </div>
                <div style="font-size: 0.75rem; color: #64748B; margin-top: 0.25rem; display: flex; align-items: center; gap: 0.375rem;">
                    <span>No. Pesanan:</span> 
                    @if($order)
                        <code style="background: #F1F5F9; padding: 0.15rem 0.4rem; border-radius: 0.25rem; font-family: monospace; font-size: 0.75rem; font-weight: 700; color: #4F26A6; user-select: all;" title="Nomor Pesanan">
                            #{{ $order->order_number }}
                        </code>
                    @else
                        <span>-</span>
                    @endif
                </div>
            </div>

            {{-- Kontak WhatsApp & No. HP Penjual --}}
            <div style="margin-top: 0.625rem; padding-top: 0.5rem; border-top: 1px dashed #E2E8F0;">
                <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #166534; margin-bottom: 0.25rem; display: flex; align-items: center; justify-content: space-between;">
                    <span style="display: flex; align-items: center; gap: 0.25rem;">
                        <svg style="width: 13px; height: 13px; color: #22C55E; flex-shrink: 0;" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2..."/>
                        </svg>
                        WhatsApp / No. HP Seller
                    </span>
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.375rem; margin-top: 0.125rem;">
                    <span style="font-weight: 700; color: #0F172A; font-size: 0.8125rem; font-family: monospace;">
                        {{ $sellerPhone ?: 'Belum diisi' }}
                    </span>
                    @if($sellerWaUrl)
                        <a href="{{ $sellerWaUrl }}" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 0.25rem; background: #22C55E; color: #FFFFFF; font-size: 0.6875rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 9999px; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05);" title="Chat WhatsApp Penjual">
                            <svg style="width: 10px; height: 10px;" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2..."/>
                            </svg>
                            <span>Chat WA</span>
                        </a>
                    @endif
                </div>
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
