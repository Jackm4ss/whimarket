<x-filament-widgets::widget>
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 1rem; padding: 1.5rem 1.75rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04); display: flex; flex-direction: column; gap: 1.25rem;">
        <div>
            <h2 style="font-size: 1.375rem; font-weight: 800; line-height: 1.3; margin: 0 0 0.35rem 0; color: #0F172A; letter-spacing: -0.02em;">
                Halo, Administrator Marketplace
            </h2>
            <p style="font-size: 0.875rem; color: #64748B; line-height: 1.5; margin: 0 0 1.25rem 0;">
                Pantau aktivitas marketplace dan kelola pesanan yang perlu segera diproses hari ini.
            </p>

            {{-- Action Cards Grid (4 columns across full width) --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 0.875rem;">
                {{-- Card 1: Orders --}}
                <a href="{{ route('filament.admin.resources.orders.index') }}" style="background: #EFF6FF; border: 1px solid #DBEAFE; border-radius: 0.75rem; padding: 0.875rem 1.125rem; text-decoration: none; display: flex; flex-direction: column; gap: 0.25rem; transition: transform 0.15s, box-shadow 0.15s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.05)'" onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                    <span style="font-size: 0.75rem; font-weight: 600; color: #3B82F6;">Perlu Diproses</span>
                    <span style="font-size: 1.625rem; font-weight: 800; color: #1E40AF; line-height: 1.2;">{{ $pendingOrdersCount }}</span>
                    <span style="font-size: 0.6875rem; color: #60A5FA;">Pesanan Baru &rarr;</span>
                </a>

                {{-- Card 2: Payments --}}
                <a href="{{ route('filament.admin.resources.payments.index') }}" style="background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 0.75rem; padding: 0.875rem 1.125rem; text-decoration: none; display: flex; flex-direction: column; gap: 0.25rem; transition: transform 0.15s, box-shadow 0.15s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.05)'" onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                    <span style="font-size: 0.75rem; font-weight: 600; color: #D97706;">Verifikasi Bayar</span>
                    <span style="font-size: 1.625rem; font-weight: 800; color: #92400E; line-height: 1.2;">{{ $pendingPaymentsCount }}</span>
                    <span style="font-size: 0.6875rem; color: #F59E0B;">Cek Bukti Transfer &rarr;</span>
                </a>

                {{-- Card 3: Disputes --}}
                <a href="{{ route('filament.admin.resources.disputes.index') }}" style="background: {{ $pendingDisputesCount > 0 ? '#FEF2F2' : '#F0FDF4' }}; border: 1px solid {{ $pendingDisputesCount > 0 ? '#FECACA' : '#DCFCE7' }}; border-radius: 0.75rem; padding: 0.875rem 1.125rem; text-decoration: none; display: flex; flex-direction: column; gap: 0.25rem; transition: transform 0.15s, box-shadow 0.15s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.05)'" onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                    <span style="font-size: 0.75rem; font-weight: 600; color: {{ $pendingDisputesCount > 0 ? '#DC2626' : '#16A34A' }};">Sengketa Escrow</span>
                    <span style="font-size: 1.625rem; font-weight: 800; color: {{ $pendingDisputesCount > 0 ? '#991B1B' : '#15803D' }}; line-height: 1.2;">{{ $pendingDisputesCount }}</span>
                    <span style="font-size: 0.6875rem; color: {{ $pendingDisputesCount > 0 ? '#EF4444' : '#22C55E' }};">{{ $pendingDisputesCount > 0 ? 'Butuh Mediasi &rarr;' : 'Aman (0 Kasus)' }}</span>
                </a>

                {{-- Card 4: Payouts --}}
                <a href="{{ route('filament.admin.resources.payouts.index') }}" style="background: #FAF5FF; border: 1px solid #E9D5FF; border-radius: 0.75rem; padding: 0.875rem 1.125rem; text-decoration: none; display: flex; flex-direction: column; gap: 0.25rem; transition: transform 0.15s, box-shadow 0.15s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.05)'" onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                    <span style="font-size: 0.75rem; font-weight: 600; color: #8B5CF6;">Pencairan Dana</span>
                    <span style="font-size: 1.625rem; font-weight: 800; color: #6D28D9; line-height: 1.2;">{{ $pendingPayoutsCount }}</span>
                    <span style="font-size: 0.6875rem; color: #A78BFA;">Siap Cair &rarr;</span>
                </a>
            </div>
        </div>

        {{-- Bottom Shortcuts Bar --}}
        <div style="border-top: 1px solid #F1F5F9; padding-top: 0.875rem; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                <a href="{{ route('filament.admin.resources.orders.index') }}" style="background: #4F26A6; color: #FFFFFF; padding: 0.45rem 1rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.375rem; box-shadow: 0 1px 2px 0 rgba(79, 38, 166, 0.2); transition: background 0.15s;" onmouseover="this.style.background='#3E1D85'" onmouseout="this.style.background='#4F26A6'">
                    Kelola Pesanan &rarr;
                </a>
                <a href="{{ route('filament.admin.resources.payments.index') }}" style="background: #F8FAFC; color: #334155; border: 1px solid #E2E8F0; padding: 0.45rem 0.875rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.375rem; transition: all 0.15s;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='#F8FAFC'">
                    Verifikasi Pembayaran ({{ $pendingPaymentsCount }})
                </a>
                <a href="{{ route('filament.admin.resources.disputes.index') }}" style="background: #F8FAFC; color: #334155; border: 1px solid #E2E8F0; padding: 0.45rem 0.875rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.375rem; transition: all 0.15s;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='#F8FAFC'">
                    Pusat Mediasi Sengketa
                </a>
                <a href="{{ route('filament.admin.resources.sellers.index') }}" style="background: #F8FAFC; color: #334155; border: 1px solid #E2E8F0; padding: 0.45rem 0.875rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.375rem; transition: all 0.15s;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='#F8FAFC'">
                    Mitra Toko ({{ $totalSellersCount }})
                </a>
                <a href="{{ route('filament.admin.resources.products.index') }}" style="background: #F8FAFC; color: #334155; border: 1px solid #E2E8F0; padding: 0.45rem 0.875rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.375rem; transition: all 0.15s;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='#F8FAFC'">
                    Moderasi Produk ({{ $totalProductsCount }})
                </a>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>