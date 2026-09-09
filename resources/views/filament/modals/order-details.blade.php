<div style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.875rem; color: #334155;">
    {{-- Status Banner --}}
    @php
        $statusConfig = match ((string) $order->status) {
            'pending_payment' => ['label' => 'Menunggu Bayar', 'bg' => '#F1F5F9', 'text' => '#475569', 'border' => '#CBD5E1'],
            'payment_verification' => ['label' => 'Verifikasi Bayar', 'bg' => '#FEF3C7', 'text' => '#D97706', 'border' => '#FDE68A'],
            'processing' => ['label' => 'Perlu Diproses', 'bg' => '#E0F2FE', 'text' => '#0284C7', 'border' => '#BAE6FD'],
            'shipped' => ['label' => 'Sedang Dikirim', 'bg' => '#F3E8FF', 'text' => '#7E22CE', 'border' => '#E9D5FF'],
            'delivered', 'completed' => ['label' => 'Pesanan Selesai', 'bg' => '#DCFCE7', 'text' => '#15803D', 'border' => '#BBF7D0'],
            'cancelled' => ['label' => 'Dibatalkan', 'bg' => '#FEE2E2', 'text' => '#DC2626', 'border' => '#FECACA'],
            'disputed' => ['label' => 'Dalam Sengketa', 'bg' => '#FEE2E2', 'text' => '#DC2626', 'border' => '#FECACA'],
            default => ['label' => ucfirst((string) $order->status), 'bg' => '#F1F5F9', 'text' => '#475569', 'border' => '#E2E8F0'],
        };
    @endphp
    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.875rem 1rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 0.75rem;">
        <div>
            <div style="font-size: 0.75rem; color: #64748B; font-weight: 500;">Status Pesanan Saat Ini</div>
            <div style="display: inline-flex; align-items: center; gap: 0.375rem; margin-top: 0.25rem; padding: 0.25rem 0.625rem; border-radius: 9999px; background: {{ $statusConfig['bg'] }}; color: {{ $statusConfig['text'] }}; border: 1px solid {{ $statusConfig['border'] }}; font-size: 0.8125rem; font-weight: 700;">
                {{ $statusConfig['label'] }}
            </div>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 0.75rem; color: #64748B; font-weight: 500;">Waktu Pesanan Dibuat</div>
            <div style="font-size: 0.75rem; font-weight: 600; color: #1E293B; margin-top: 0.25rem;">
                {{ $order->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}
            </div>
        </div>
    </div>

    {{-- Items List --}}
    <div style="border: 1px solid #E2E8F0; border-radius: 0.75rem; overflow: hidden; background: #FFFFFF;">
        <div style="padding: 0.625rem 1rem; background: #F1F5F9; border-bottom: 1px solid #E2E8F0; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569;">
            Daftar Produk Dipesan ({{ $order->items->count() }})
        </div>
        <div style="padding: 0.5rem;">
            @forelse($order->items as $item)
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
                                    <a href="{{ route('filament.admin.resources.products.index', ['tableSearch' => $product->name]) }}" target="_blank" title="Buka Moderasi Produk: {{ $productName }}" style="color: #4F26A6; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
                                        <span>{{ $productName }}</span>
                                        <svg style="width: 12px; height: 12px; opacity: 0.7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
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
                            <div style="font-size: 0.75rem; color: #64748B; margin-top: 0.25rem; display: flex; align-items: center; gap: 0.375rem;">
                                <span>{{ $item->quantity }}x &bull; Rp {{ number_format($unitPrice, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    <div style="font-size: 0.875rem; font-weight: 700; color: #0F172A;">
                        Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}
                    </div>
                </div>
            @empty
                <div style="padding: 0.75rem; font-size: 0.75rem; color: #64748B; text-align: center;">Tidak ada rincian item.</div>
            @endforelse
        </div>
    </div>

    {{-- Shipping & Destination Details --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 0.75rem;">
        <div style="padding: 0.875rem; border: 1px solid #E2E8F0; border-radius: 0.75rem; background: #FFFFFF;">
            <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748B; margin-bottom: 0.375rem; display: flex; align-items: center; gap: 0.375rem;">
                <svg style="width: 14px; height: 14px; color: #94A3B8; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Tujuan Pengiriman
            </div>
            <div style="font-weight: 700; color: #0F172A; font-size: 0.875rem;">
                {{ $order->address_snapshot['recipient_name'] ?? $order->buyer?->name ?? '-' }}
            </div>
            <div style="font-size: 0.75rem; color: #475569; margin-top: 0.125rem;">
                {{ $order->address_snapshot['phone'] ?? $order->buyer?->phone ?? '-' }}
                @if($order->buyer?->email)
                    &bull; <span style="color: #64748B;">{{ $order->buyer->email }}</span>
                @endif
            </div>
            <div style="font-size: 0.75rem; color: #334155; margin-top: 0.375rem; line-height: 1.5;">
                <div style="font-weight: 500; color: #1E293B;">
                    {{ $order->address_snapshot['full_address'] ?? $order->address_snapshot['address_line'] ?? $order->address_snapshot['address'] ?? '-' }}
                </div>
                <div style="color: #64748B; margin-top: 0.125rem;">
                    {{ collect([
                        $order->address_snapshot['district'] ?? null,
                        $order->address_snapshot['city'] ?? null,
                        $order->address_snapshot['province'] ?? null,
                        $order->address_snapshot['postal_code'] ?? null,
                    ])->filter()->join(', ') }}
                </div>
            </div>
        </div>

        <div style="padding: 0.875rem; border: 1px solid #E2E8F0; border-radius: 0.75rem; background: #FFFFFF;">
            <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748B; margin-bottom: 0.375rem; display: flex; align-items: center; gap: 0.375rem;">
                <svg style="width: 14px; height: 14px; color: #94A3B8; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                Kurir & Resi Pengiriman
            </div>
            <div style="font-size: 0.75rem; color: #475569;">
                Kurir: 
                @if($order->shipment && $order->shipment->courier_name)
                    <strong style="color: #0F172A;">{{ $order->shipment->courier_name }}</strong>
                @else
                    <span style="color: #94A3B8; font-style: italic;">Belum diatur (Menunggu pengiriman)</span>
                @endif
            </div>
            <div style="font-size: 0.75rem; color: #475569; margin-top: 0.25rem;">
                No. Resi: 
                @if($order->shipment && $order->shipment->tracking_number)
                    <code style="background: #F1F5F9; padding: 0.15rem 0.375rem; border-radius: 0.25rem; color: #4F26A6; font-family: monospace; font-size: 0.75rem; font-weight: 600;">
                        {{ $order->shipment->tracking_number }}
                    </code>
                @else
                    <span style="color: #94A3B8; font-size: 0.75rem; font-style: italic;">Belum diinput seller</span>
                @endif
            </div>
            <div style="font-size: 0.75rem; color: #64748B; margin-top: 0.5rem;">
                Toko Penjual: 
                @if($order->seller)
                    <a href="{{ route('filament.admin.resources.sellers.index', ['tableSearch' => $order->seller->store_name]) }}" target="_blank" title="Buka Toko: {{ $order->seller->store_name }}" style="font-weight: 700; color: #4F26A6; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
                        <span>{{ $order->seller->store_name }}</span>
                        <svg style="width: 12px; height: 12px; opacity: 0.7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                @else
                    <strong style="color: #334155;">Seller WhiMarket</strong>
                @endif
            </div>
        </div>
    </div>

    {{-- Payment Summary --}}
    <div style="padding: 0.875rem; background: #FBF9FF; border: 1px solid #E9D5FF; border-radius: 0.75rem; display: flex; flex-direction: column; gap: 0.375rem; font-size: 0.75rem;">
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
        <div style="border-top: 1px solid #E5DBFF; padding-top: 0.375rem; display: flex; justify-content: space-between; font-size: 0.875rem; font-weight: 700; color: #4F26A6;">
            <span>Total Transaksi Escrow</span>
            <span style="font-size: 1rem; color: #4F26A6;">Rp {{ number_format((float) $order->grand_total, 0, ',', '.') }}</span>
        </div>
    </div>
</div>
