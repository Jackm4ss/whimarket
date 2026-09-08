<x-filament-widgets::widget>
    <div style="background: linear-gradient(135deg, #4F26A6 0%, #3E1D85 50%, #251058 100%); border-radius: 1.25rem; padding: 1.75rem 2rem; color: #FFFFFF; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border: 1px solid rgba(255,255,255,0.1);">
        <div style="max-width: 620px; z-index: 1;">
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.25rem 0.75rem; border-radius: 9999px; background: rgba(255,255,255,0.15); color: #F5BA47; font-size: 0.75rem; font-weight: 700; margin-bottom: 0.75rem;">
                ✨ WhiMarket Admin Console
            </div>
            <h2 style="font-size: 1.6rem; font-weight: 900; line-height: 1.25; margin: 0 0 0.5rem 0; color: #FFFFFF;">
                Pusat Kendali Marketplace Pre-loved
            </h2>
            <p style="font-size: 0.875rem; color: rgba(255,255,255,0.85); line-height: 1.5; margin: 0 0 1.25rem 0;">
                Pantau transaksi escrow real-time, verifikasi kreator VIP, mitigasi sengketa buyer-seller, dan kelola kelancaran pencairan dana payout.
            </p>
            <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                <a href="{{ route('filament.admin.resources.orders.index') }}" style="background: #FFFFFF; color: #4F26A6; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.75rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    Kelola Pesanan &rarr;
                </a>
                <a href="{{ route('filament.admin.resources.disputes.index') }}" style="background: rgba(255,255,255,0.15); color: #FFFFFF; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.75rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
                    Pusat Mediasi Sengketa
                </a>
                <a href="{{ route('filament.admin.resources.sellers.index') }}" style="background: rgba(255,255,255,0.15); color: #FFFFFF; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.75rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
                    Verifikasi Seller
                </a>
            </div>
        </div>

        <div style="flex-shrink: 0; z-index: 1; display: flex; align-items: center; justify-content: center;">
            <img
                src="/assets/admin-hero.png"
                alt="WhiMarket Analytics Illustration"
                style="height: 150px; width: auto; max-width: 240px; object-fit: contain; border-radius: 1rem; filter: drop-shadow(0 10px 25px rgba(0,0,0,0.35));"
            />
        </div>
    </div>
</x-filament-widgets::widget>
