<div style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.875rem; color: #334155;">
    {{-- Status Banner --}}
    @php
        $statusState = $dispute->status;
        $statusClass = is_object($statusState) ? get_class($statusState) : (string) $statusState;
        
        $statusConfig = match (true) {
            str_contains($statusClass, 'OpenDispute') || $statusClass === 'open' => [
                'label' => 'Komplain Dibuka (Menunggu Tanggapan Seller)',
                'bg' => '#FEE2E2',
                'text' => '#DC2626',
                'border' => '#FECACA',
                'icon' => 'exclamation-circle'
            ],
            str_contains($statusClass, 'SellerResponded') || $statusClass === 'seller_responded' => [
                'label' => 'Seller Memberikan Bukti Bantahan (Perlu Keputusan)',
                'bg' => '#FEF3C7',
                'text' => '#D97706',
                'border' => '#FDE68A',
                'icon' => 'chat-bubble-left-right'
            ],
            str_contains($statusClass, 'UnderAdminReview') || $statusClass === 'under_admin_review' => [
                'label' => 'Dalam Mediasi Aktif Administrator',
                'bg' => '#E0F2FE',
                'text' => '#0284C7',
                'border' => '#BAE6FD',
                'icon' => 'magnifying-glass'
            ],
            str_contains($statusClass, 'ResolvedRefund') || $statusClass === 'resolved_refund' => [
                'label' => 'Dispute Selesai: Refund Pembeli Disetujui',
                'bg' => '#DCFCE7',
                'text' => '#15803D',
                'border' => '#BBF7D0',
                'icon' => 'arrow-path-rounded-square'
            ],
            default => [
                'label' => 'Dispute Selesai: Komplain Ditolak (Dana Diteruskan ke Seller)',
                'bg' => '#F1F5F9',
                'text' => '#475569',
                'border' => '#CBD5E1',
                'icon' => 'shield-check'
            ],
        };

        $order = $dispute->order;
        $buyer = $dispute->buyer ?? $order?->buyer;
        $seller = $order?->seller;
        $buyerPhone = $order?->address_snapshot['phone'] ?? $buyer?->phone;
        $sellerPhone = $seller?->user?->phone ?: ($seller?->user?->addresses?->first()?->phone ?? null);

        $cleanBuyerWa = null;
        if ($buyerPhone) {
            $digits = preg_replace('/[^0-9]/', '', (string) $buyerPhone);
            if (!empty($digits)) {
                $cleanBuyerWa = str_starts_with($digits, '0') ? '62' . substr($digits, 1) : (str_starts_with($digits, '8') ? '62' . $digits : $digits);
            }
        }
        $buyerWaUrl = $cleanBuyerWa ? 'https://wa.me/' . $cleanBuyerWa . '?text=' . rawurlencode("Halo Kak " . ($buyer?->name ?? '') . ", kami dari Tim Mediasi WhiMarket terkait komplain pesanan #" . ($order?->order_number ?? '')) : null;

        $cleanSellerWa = null;
        if ($sellerPhone) {
            $digits = preg_replace('/[^0-9]/', '', (string) $sellerPhone);
            if (!empty($digits)) {
                $cleanSellerWa = str_starts_with($digits, '0') ? '62' . substr($digits, 1) : (str_starts_with($digits, '8') ? '62' . $digits : $digits);
            }
        }
        $sellerWaUrl = $cleanSellerWa ? 'https://wa.me/' . $cleanSellerWa . '?text=' . rawurlencode("Halo Toko " . ($seller?->store_name ?? '') . ", kami dari Tim Mediasi WhiMarket terkait komplain pesanan #" . ($order?->order_number ?? '')) : null;

        $buyerEvidences = is_array($dispute->buyer_evidence_paths) ? $dispute->buyer_evidence_paths : [];
        $sellerEvidences = is_array($dispute->seller_evidence_paths) ? $dispute->seller_evidence_paths : [];
    @endphp

    {{-- Top Status Bar --}}
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; padding: 0.875rem 1rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 0.75rem;">
        <div>
            <div style="font-size: 0.75rem; color: #64748B; font-weight: 500;">Status Mediasi Sengketa</div>
            <div style="display: inline-flex; align-items: center; gap: 0.375rem; margin-top: 0.25rem; padding: 0.25rem 0.625rem; border-radius: 9999px; background: {{ $statusConfig['bg'] }}; color: {{ $statusConfig['text'] }}; border: 1px solid {{ $statusConfig['border'] }}; font-size: 0.8125rem; font-weight: 700;">
                {{ $statusConfig['label'] }}
            </div>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 0.75rem; color: #64748B; font-weight: 500;">Waktu Komplain Diajukan</div>
            <div style="font-size: 0.75rem; font-weight: 600; color: #1E293B; margin-top: 0.25rem;">
                {{ $dispute->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}
                <span style="font-weight: 400; color: #64748B;">({{ $dispute->created_at?->diffForHumans() }})</span>
            </div>
        </div>
    </div>

    {{-- Decision Summary if already resolved --}}
    @if($dispute->resolution_notes)
        <div style="padding: 1rem; border-radius: 0.75rem; background: #F8FAFC; border: 1px solid #CBD5E1; border-left: 4px solid #4F26A6;">
            <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #4F26A6; margin-bottom: 0.25rem;">
                Keputusan Mediasi Administrator
            </div>
            <div style="font-size: 0.875rem; color: #1E293B; line-height: 1.5; font-weight: 500;">
                &ldquo;{{ $dispute->resolution_notes }}&rdquo;
            </div>
            <div style="font-size: 0.75rem; color: #64748B; margin-top: 0.375rem;">
                Diputuskan oleh <strong>{{ $dispute->resolver?->name ?? 'Administrator' }}</strong> pada {{ $dispute->resolved_at?->translatedFormat('d F Y, H:i') }}.
            </div>
        </div>
    @endif

    {{-- Order and Involved Parties (2 Columns) --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 0.75rem;">
        {{-- Buyer Box --}}
        <div style="padding: 0.875rem; border: 1px solid #E2E8F0; border-radius: 0.75rem; background: #FFFFFF; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748B; margin-bottom: 0.375rem; display: flex; align-items: center; gap: 0.375rem;">
                    <svg style="width: 14px; height: 14px; color: #94A3B8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Pihak Pembeli (Buyer)
                </div>
                <div style="font-weight: 700; color: #0F172A; font-size: 0.875rem;">
                    {{ $buyer?->name ?? 'Pembeli WhiMarket' }}
                </div>
                <div style="font-size: 0.75rem; color: #64748B; margin-top: 0.25rem;">
                    Email: <span style="color: #0F172A; font-weight: 600;">{{ $buyer?->email ?? '-' }}</span>
                </div>
                <div style="font-size: 0.75rem; color: #64748B; margin-top: 0.125rem;">
                    No. HP: <span style="font-family: monospace; font-weight: 600; color: #0F172A;">{{ $buyerPhone ?: '-' }}</span>
                </div>
            </div>
            @if($buyerWaUrl)
                <div style="margin-top: 0.625rem; padding-top: 0.5rem; border-top: 1px dashed #E2E8F0;">
                    <a href="{{ $buyerWaUrl }}" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 0.375rem; background: #22C55E; color: #FFFFFF; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.625rem; border-radius: 9999px; text-decoration: none;">
                        <span>Chat WA Pembeli</span>
                        <svg style="width: 11px; height: 11px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
            @endif
        </div>

        {{-- Seller Box --}}
        <div style="padding: 0.875rem; border: 1px solid #E2E8F0; border-radius: 0.75rem; background: #FFFFFF; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748B; margin-bottom: 0.375rem; display: flex; align-items: center; gap: 0.375rem;">
                    <svg style="width: 14px; height: 14px; color: #94A3B8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Pihak Penjual (Seller)
                </div>
                <div style="font-weight: 700; color: #0F172A; font-size: 0.875rem;">
                    {{ $seller?->store_name ?? 'Toko Seller' }}
                    @if($seller?->username)
                        <span style="font-size: 0.75rem; font-weight: 500; color: #64748B;">({{ '@' . $seller->username }})</span>
                    @endif
                </div>
                <div style="font-size: 0.75rem; color: #64748B; margin-top: 0.25rem;">
                    Pemilik: <span style="color: #0F172A; font-weight: 600;">{{ $seller?->user?->name ?? '-' }}</span>
                </div>
                <div style="font-size: 0.75rem; color: #64748B; margin-top: 0.125rem;">
                    No. HP: <span style="font-family: monospace; font-weight: 600; color: #0F172A;">{{ $sellerPhone ?: '-' }}</span>
                </div>
            </div>
            @if($sellerWaUrl)
                <div style="margin-top: 0.625rem; padding-top: 0.5rem; border-top: 1px dashed #E2E8F0;">
                    <a href="{{ $sellerWaUrl }}" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 0.375rem; background: #22C55E; color: #FFFFFF; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.625rem; border-radius: 9999px; text-decoration: none;">
                        <span>Chat WA Seller</span>
                        <svg style="width: 11px; height: 11px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Order Summary Card --}}
    @if($order)
        <div style="padding: 0.875rem 1rem; border: 1px solid #E2E8F0; border-radius: 0.75rem; background: #FFFFFF; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
            <div>
                <span style="font-size: 0.75rem; color: #64748B; font-weight: 500;">Nomor Pesanan Terkait</span>
                <div style="font-family: monospace; font-size: 0.9375rem; font-weight: 800; color: #4F26A6;">
                    #{{ $order->order_number }}
                </div>
            </div>
            <div style="text-align: right;">
                <span style="font-size: 0.75rem; color: #64748B; font-weight: 500;">Nilai Transaksi (Dana Ditahan Escrow)</span>
                <div style="font-size: 1rem; font-weight: 800; color: #16A34A;">
                    Rp {{ number_format((float) $order->total_amount, 0, ',', '.') }}
                </div>
            </div>
        </div>
    @endif

    {{-- Buyer Complaint & Evidence --}}
    <div style="border: 1px solid #FECACA; border-radius: 0.75rem; overflow: hidden; background: #FFFFFF;">
        <div style="padding: 0.75rem 1rem; background: #FEF2F2; border-bottom: 1px solid #FECACA; display: flex; align-items: center; justify-content: space-between;">
            <div style="font-size: 0.8125rem; font-weight: 700; color: #991B1B; display: flex; align-items: center; gap: 0.375rem;">
                <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Keluhan Pembeli: {{ $dispute->reason }}</span>
            </div>
        </div>
        <div style="padding: 1rem; space-y: 0.75rem;">
            <div style="font-size: 0.875rem; color: #1F2937; line-height: 1.5; background: #FAF9FC; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #F1F5F9;">
                &ldquo;{{ $dispute->description }}&rdquo;
            </div>

            {{-- Buyer Photos --}}
            <div style="margin-top: 0.75rem;">
                <span style="font-size: 0.75rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.375rem;">
                    Foto Bukti Masalah Barang ({{ count($buyerEvidences) }} Foto):
                </span>
                @if(count($buyerEvidences) > 0)
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)); gap: 0.5rem;">
                        @foreach($buyerEvidences as $img)
                            <a href="{{ asset('storage/' . $img) }}" target="_blank" style="display: block; border-radius: 0.5rem; overflow: hidden; border: 1px solid #E2E8F0; aspect-ratio: 1; background: #F8FAFC;">
                                <img src="{{ asset('storage/' . $img) }}" alt="Bukti Pembeli" style="width: 100%; height: 100%; object-fit: cover;">
                            </a>
                        @endforeach
                    </div>
                @else
                    <div style="font-size: 0.75rem; color: #94A3B8; font-style: italic;">Tidak ada foto bukti yang diunggah pembeli.</div>
                @endif
            </div>

            {{-- Video Unboxing --}}
            @if($dispute->video_unboxing_path)
                <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px dashed #E2E8F0;">
                    <span style="font-size: 0.75rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.375rem;">
                        Video Unboxing Paket Pembeli:
                    </span>
                    <video controls style="width: 100%; max-height: 240px; border-radius: 0.5rem; background: #000000;">
                        <source src="{{ asset('storage/' . $dispute->video_unboxing_path) }}" type="video/mp4">
                        Browser Anda tidak mendukung tag video.
                    </video>
                </div>
            @endif
        </div>
    </div>

    {{-- Seller Counter Evidence & Rebuttal --}}
    <div style="border: 1px solid #BBF7D0; border-radius: 0.75rem; overflow: hidden; background: #FFFFFF;">
        <div style="padding: 0.75rem 1rem; background: #F0FDF4; border-bottom: 1px solid #BBF7D0; display: flex; align-items: center; justify-content: space-between;">
            <div style="font-size: 0.8125rem; font-weight: 700; color: #166534; display: flex; align-items: center; gap: 0.375rem;">
                <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Tanggapan &amp; Bukti Bantahan Seller</span>
            </div>
            @if($dispute->seller_response)
                <span style="font-size: 0.6875rem; font-weight: 700; color: #15803D; background: #DCFCE7; padding: 0.125rem 0.5rem; border-radius: 9999px;">
                    Seller Telah Menanggapi
                </span>
            @else
                <span style="font-size: 0.6875rem; font-weight: 700; color: #D97706; background: #FEF3C7; padding: 0.125rem 0.5rem; border-radius: 9999px;">
                    Menunggu Respon Seller
                </span>
            @endif
        </div>
        <div style="padding: 1rem; space-y: 0.75rem;">
            @if($dispute->seller_response)
                <div style="font-size: 0.875rem; color: #1F2937; line-height: 1.5; background: #FAF9FC; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #F1F5F9;">
                    &ldquo;{{ $dispute->seller_response }}&rdquo;
                </div>
            @else
                <div style="font-size: 0.8125rem; color: #64748B; font-style: italic; background: #F8FAFC; padding: 0.75rem; border-radius: 0.5rem;">
                    Seller belum memberikan tanggapan atau bukti bantahan atas komplain ini.
                </div>
            @endif

            {{-- Seller Photos --}}
            @if(count($sellerEvidences) > 0)
                <div style="margin-top: 0.75rem;">
                    <span style="font-size: 0.75rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.375rem;">
                        Foto Bukti Pengemasan / Pengiriman Seller ({{ count($sellerEvidences) }} Foto):
                    </span>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)); gap: 0.5rem;">
                        @foreach($sellerEvidences as $img)
                            <a href="{{ asset('storage/' . $img) }}" target="_blank" style="display: block; border-radius: 0.5rem; overflow: hidden; border: 1px solid #E2E8F0; aspect-ratio: 1; background: #F8FAFC;">
                                <img src="{{ asset('storage/' . $img) }}" alt="Bukti Seller" style="width: 100%; height: 100%; object-fit: cover;">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
