<div style="background-color: #FFFFFF; border-radius: 0.75rem; display: flex; flex-direction: column; gap: 1rem; font-size: 0.875rem; color: #334155;">
    @php
        $statusState = $product->status instanceof \App\Enums\ProductStatus
            ? $product->status
            : \App\Enums\ProductStatus::tryFrom((string) $product->status);

        $statusConfig = match ($statusState) {
            \App\Enums\ProductStatus::ACTIVE => ['label' => 'Aktif Tayang (Live)', 'bg' => '#DCFCE7', 'text' => '#15803D', 'border' => '#BBF7D0'],
            \App\Enums\ProductStatus::INACTIVE => ['label' => 'Nonaktif / Arsip', 'bg' => '#F1F5F9', 'text' => '#475569', 'border' => '#CBD5E1'],
            \App\Enums\ProductStatus::REJECTED => ['label' => 'Ditolak Moderasi', 'bg' => '#FEE2E2', 'text' => '#DC2626', 'border' => '#FECACA'],
            default => ['label' => ucfirst((string) $product->status), 'bg' => '#F1F5F9', 'text' => '#475569', 'border' => '#E2E8F0'],
        };

        $totalStock = (int) ($product->total_stock ?? $product->variants->sum('stock'));
        $variantCount = $product->variants->count();
        $conditionLabel = $product->condition instanceof \App\Enums\ProductCondition
            ? $product->condition->label()
            : ($product->condition ? ucfirst(str_replace('_', ' ', (string) $product->condition)) : 'Kondisi Baik');

        $seller = $product->seller;
        $phoneClean = $seller?->user?->phone ? preg_replace('/[^0-9]/', '', $seller->user->phone) : null;
        $waNumber = $phoneClean ? (str_starts_with($phoneClean, '0') ? '62' . substr($phoneClean, 1) : $phoneClean) : null;
    @endphp

    {{-- Top Price & Status Banner --}}
    <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 0.75rem; padding: 1rem; background: linear-gradient(135deg, #FAF5FF 0%, #F3E8FF 100%); border: 1px solid #E9D5FF; border-radius: 0.75rem;">
        <div>
            <div style="font-size: 0.75rem; color: #6B21A8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                Harga Listing Produk
            </div>
            <div style="font-size: 1.5rem; font-weight: 800; color: #4F26A6; margin-top: 0.125rem;">
                Rp {{ number_format((float) $product->price, 0, ',', '.') }}
            </div>
            <div style="font-size: 0.75rem; color: #7E22CE; font-weight: 500; margin-top: 0.125rem;">
                Total Stok: <strong>{{ $totalStock }} unit</strong> ({{ $variantCount }} varian)
            </div>
        </div>
        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.25rem;">
            <div style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.3125rem 0.75rem; border-radius: 9999px; background: {{ $statusConfig['bg'] }}; color: {{ $statusConfig['text'] }}; border: 1px solid {{ $statusConfig['border'] }}; font-size: 0.8125rem; font-weight: 700;">
                {{ $statusConfig['label'] }}
            </div>
            <div style="font-size: 0.75rem; color: #6B21A8; font-weight: 500;">
                Listing sejak: {{ $product->created_at?->translatedFormat('d M Y') ?? '-' }}
            </div>
        </div>
    </div>

    {{-- Product Photos Gallery --}}
    <div style="border: 1px solid #E2E8F0; border-radius: 0.75rem; overflow: hidden; background: #FFFFFF;">
        <div style="padding: 0.625rem 1rem; background: #F8FAFC; border-bottom: 1px solid #E2E8F0; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569;">
            Foto & Galeri Produk
        </div>
        <div style="padding: 1rem; display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center;">
            <div style="position: relative; width: 100px; height: 100px; border-radius: 0.5rem; overflow: hidden; border: 2px solid #9333EA; background: #F1F5F9; flex-shrink: 0;">
                <img
                    src="{{ $product->primary_image_url }}"
                    alt="{{ $product->name }}"
                    style="width: 100%; height: 100%; object-fit: cover;"
                    onerror="this.src='/assets/products/prod-hoodie.png'"
                />
                <span style="position: absolute; bottom: 2px; left: 2px; right: 2px; background: rgba(79, 38, 166, 0.85); color: #FFFFFF; font-size: 0.5625rem; font-weight: 700; text-align: center; padding: 1px 2px; border-radius: 3px;">
                    Foto Utama
                </span>
            </div>
            @foreach($product->images->where('is_primary', false) as $img)
                <div style="width: 80px; height: 80px; border-radius: 0.5rem; overflow: hidden; border: 1px solid #E2E8F0; background: #F1F5F9; flex-shrink: 0;">
                    <img
                        src="{{ $img->image_path }}"
                        alt="Foto Tambahan"
                        style="width: 100%; height: 100%; object-fit: cover;"
                        onerror="this.src='/assets/products/prod-hoodie.png'"
                    />
                </div>
            @endforeach
        </div>
    </div>

    {{-- Product General Information --}}
    <div style="border: 1px solid #E2E8F0; border-radius: 0.75rem; overflow: hidden; background: #FFFFFF;">
        <div style="padding: 0.625rem 1rem; background: #F8FAFC; border-bottom: 1px solid #E2E8F0; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569;">
            Informasi Spesifikasi & Listing
        </div>
        <div style="padding: 1rem; display: flex; flex-direction: column; gap: 0.75rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 0.5rem; border-bottom: 1px solid #F1F5F9;">
                <span style="color: #64748B; font-size: 0.8125rem;">Nama Produk:</span>
                <span style="font-weight: 700; color: #0F172A; text-align: right; max-width: 65%;">{{ $product->name }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.5rem; border-bottom: 1px solid #F1F5F9;">
                <span style="color: #64748B; font-size: 0.8125rem;">Kategori:</span>
                <span style="display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; border-radius: 9999px; background: #F3EEFF; color: #4F26A6; border: 1px solid #DDD6FE; font-size: 0.75rem; font-weight: 700;">
                    {{ $product->category?->name ?? 'Kategori Umum' }}
                </span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.5rem; border-bottom: 1px solid #F1F5F9;">
                <span style="color: #64748B; font-size: 0.8125rem;">Kondisi Barang:</span>
                <span style="display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; border-radius: 9999px; background: #EFF6FF; color: #1E40AF; border: 1px solid #BFDBFE; font-size: 0.75rem; font-weight: 700;">
                    {{ $conditionLabel }}
                </span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.5rem; border-bottom: 1px solid #F1F5F9;">
                <span style="color: #64748B; font-size: 0.8125rem;">Slug URL:</span>
                <a href="{{ url('/produk/' . $product->slug) }}" target="_blank" rel="noopener noreferrer" style="color: #4F26A6; font-size: 0.75rem; font-weight: 600; text-decoration: underline; font-family: monospace;">
                    /produk/{{ $product->slug }}
                </a>
            </div>
            <div>
                <div style="color: #64748B; font-size: 0.8125rem; margin-bottom: 0.375rem;">Deskripsi Produk:</div>
                <div style="padding: 0.75rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 0.5rem; color: #1E293B; font-size: 0.8125rem; line-height: 1.5; white-space: pre-line;">
                    {{ $product->description ?: 'Tidak ada deskripsi rinci untuk produk ini.' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Seller Information --}}
    @if($seller)
        <div style="border: 1px solid #E2E8F0; border-radius: 0.75rem; overflow: hidden; background: #FFFFFF;">
            <div style="padding: 0.625rem 1rem; background: #F8FAFC; border-bottom: 1px solid #E2E8F0; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569;">
                Toko Mitra & Creator Penjual
            </div>
            <div style="padding: 1rem; display: flex; flex-direction: column; gap: 0.75rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.5rem; border-bottom: 1px solid #F1F5F9;">
                    <span style="color: #64748B; font-size: 0.8125rem;">Nama Toko:</span>
                    <div style="display: flex; align-items: center; gap: 0.375rem;">
                        <span style="font-weight: 700; color: #0F172A;">{{ $seller->store_name }}</span>
                        @if($seller->isVerified())
                            <svg style="width: 14px; height: 14px; color: #4F26A6; flex-shrink: 0;" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.5rem; border-bottom: 1px solid #F1F5F9;">
                    <span style="color: #64748B; font-size: 0.8125rem;">Username Toko:</span>
                    <a href="{{ url('/seller/@' . $seller->username) }}" target="_blank" rel="noopener noreferrer" style="font-weight: 600; color: #4F26A6; text-decoration: none;">
                        {{ '@' . $seller->username }} ↗
                    </a>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.5rem; border-bottom: 1px solid #F1F5F9;">
                    <span style="color: #64748B; font-size: 0.8125rem;">Pemilik Akun:</span>
                    <span style="font-weight: 600; color: #1E293B;">{{ $seller->user?->name ?? '-' }} ({{ $seller->user?->email ?? '-' }})</span>
                </div>
                @if($waNumber)
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #64748B; font-size: 0.8125rem;">WhatsApp Mitra:</span>
                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.5rem; border-radius: 0.375rem; background: #22C55E; color: #FFFFFF; font-size: 0.6875rem; font-weight: 700; text-decoration: none;">
                            <svg style="width: 12px; height: 12px;" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                            </svg>
                            Hubungi Seller
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Variant & Stock Breakdown Table --}}
    <div style="border: 1px solid #E2E8F0; border-radius: 0.75rem; overflow: hidden; background: #FFFFFF;">
        <div style="padding: 0.625rem 1rem; background: #F8FAFC; border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center;">
            <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569;">
                Rincian Varian & Stok Barang
            </div>
            <span style="font-size: 0.6875rem; color: #64748B; font-weight: 600;">
                {{ $variantCount }} Varian Terdaftar
            </span>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; text-align: left; border-collapse: collapse; font-size: 0.8125rem;">
                <thead>
                    <tr style="background: #F1F5F9; color: #475569; border-bottom: 1px solid #E2E8F0;">
                        <th style="padding: 0.5rem 0.75rem; font-weight: 600;">Varian</th>
                        <th style="padding: 0.5rem 0.75rem; font-weight: 600;">SKU</th>
                        <th style="padding: 0.5rem 0.75rem; font-weight: 600;">Harga Satuan</th>
                        <th style="padding: 0.5rem 0.75rem; font-weight: 600; text-align: right;">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($product->variants as $variant)
                        <tr style="border-bottom: 1px solid #F1F5F9;">
                            <td style="padding: 0.5rem 0.75rem; font-weight: 600; color: #0F172A;">
                                {{ $variant->name }}
                            </td>
                            <td style="padding: 0.5rem 0.75rem; color: #64748B; font-family: monospace; font-size: 0.75rem;">
                                {{ $variant->sku ?: '-' }}
                            </td>
                            <td style="padding: 0.5rem 0.75rem; font-weight: 700; color: #4F26A6;">
                                Rp {{ number_format((float) $variant->price, 0, ',', '.') }}
                            </td>
                            <td style="padding: 0.5rem 0.75rem; text-align: right;">
                                @if($variant->stock > 0)
                                    <span style="display: inline-flex; align-items: center; padding: 0.15rem 0.5rem; border-radius: 9999px; background: #DCFCE7; color: #15803D; font-size: 0.6875rem; font-weight: 700;">
                                        {{ $variant->stock }} unit
                                    </span>
                                @else
                                    <span style="display: inline-flex; align-items: center; padding: 0.15rem 0.5rem; border-radius: 9999px; background: #FEE2E2; color: #DC2626; font-size: 0.6875rem; font-weight: 700;">
                                        Habis (0)
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 1rem; text-align: center; color: #94A3B8;">
                                Belum ada varian untuk produk ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Action Link Buttons --}}
    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: flex-end; padding-top: 0.5rem;">
        @if($seller)
            <a
                href="{{ url('/seller/@' . $seller->username) }}"
                target="_blank"
                rel="noopener noreferrer"
                style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 0.875rem; border-radius: 0.5rem; background: #F8FAFC; color: #334155; border: 1px solid #CBD5E1; font-size: 0.75rem; font-weight: 600; text-decoration: none;"
            >
                Kunjungi Toko Seller ↗
            </a>
        @endif
        <a
            href="{{ url('/produk/' . $product->slug) }}"
            target="_blank"
            rel="noopener noreferrer"
            style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 0.875rem; border-radius: 0.5rem; background: #4F26A6; color: #FFFFFF; font-size: 0.75rem; font-weight: 700; text-decoration: none;"
        >
            Buka di Storefront WhiMarket ↗
        </a>
    </div>
</div>
