<div style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.875rem; color: #334155; background-color: #ffffff !important; opacity: 1 !important;">
    <!-- Zone Summary Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; padding: 1rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 0.75rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="width: 44px; height: 44px; border-radius: 0.5rem; background: rgba(79, 38, 166, 0.1); color: #4F26A6; display: flex; align-items: center; justify-content: center; font-family: monospace; font-weight: 700; font-size: 0.9375rem;">
                {{ $zone->zone_code }}
            </div>
            <div>
                <h4 style="font-weight: 700; color: #0F172A; font-size: 1rem; margin: 0;">{{ $zone->name }}</h4>
                <p style="font-size: 0.75rem; color: #64748B; margin: 0.25rem 0 0 0;">Urutan Prioritas #{{ $zone->sort_order }} &bull; {{ count($zone->provinces ?? []) }} Provinsi Terdaftar</p>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            @if($zone->is_free_shipping)
                <span style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0;">
                    <svg style="width: 14px; height: 14px; shrink-0: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Promo Bebas Ongkir
                </span>
            @endif
            @if($zone->is_active)
                <span style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; background: #DEF7EC; color: #03543F; border: 1px solid #BCF0DA;">
                    Aktif
                </span>
            @else
                <span style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; background: #FDE8E8; color: #9B1C1C; border: 1px solid #FBD5D5;">
                    Nonaktif
                </span>
            @endif
        </div>
    </div>

    <!-- Logistics Details Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 0.75rem;">
        <div style="padding: 0.875rem; border: 1px solid #E2E8F0; border-radius: 0.75rem; background: #FFFFFF;">
            <span style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748B; display: block;">Tarif Flat Normal</span>
            <div style="margin-top: 0.25rem; display: flex; align-items: baseline; gap: 0.375rem;">
                <span style="font-size: 1.125rem; font-weight: 800; color: #0F172A;">Rp {{ number_format((float) $zone->rate, 0, ',', '.') }}</span>
                <span style="font-size: 0.75rem; color: #64748B;">/ kg barang</span>
            </div>
        </div>
        <div style="padding: 0.875rem; border: 1px solid #E2E8F0; border-radius: 0.75rem; background: #FFFFFF;">
            <span style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748B; display: block;">Estimasi Waktu Sampai (ETD)</span>
            <div style="margin-top: 0.25rem; display: flex; align-items: center; gap: 0.375rem; color: #0F172A; font-weight: 700; font-size: 0.9375rem;">
                <svg style="width: 16px; height: 16px; color: #4F26A6; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ $zone->etd ?? '2-4 hari kerja' }}</span>
            </div>
        </div>
    </div>

    <!-- Courier Notes -->
    @if($zone->courier_notes)
        <div style="padding: 0.75rem 1rem; border: 1px solid #E9D5FF; background: #FAF5FF; border-radius: 0.75rem; display: flex; align-items: flex-start; gap: 0.625rem;">
            <svg style="width: 18px; height: 18px; color: #4F26A6; flex-shrink: 0; margin-top: 0.125rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div style="font-size: 0.75rem; color: #4C1D95; line-height: 1.5;">
                <strong style="font-weight: 700; color: #4F26A6;">Catatan Ekspedisi / Rute:</strong>
                <span>{{ $zone->courier_notes }}</span>
            </div>
        </div>
    @endif

    <!-- Covered Provinces List -->
    <div>
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
            <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #0F172A; display: flex; align-items: center; gap: 0.375rem;">
                <svg style="width: 14px; height: 14px; color: #4F26A6;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Provinsi dalam Cakupan Zona Ini
            </div>
            <span style="font-size: 0.75rem; font-weight: 600; color: #64748B;">{{ count($zone->provinces ?? []) }} Wilayah</span>
        </div>

        <div style="max-height: 240px; overflow-y: auto; padding: 0.75rem; border: 1px solid #E2E8F0; border-radius: 0.75rem; background: #F8FAFC; display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 0.5rem;">
            @forelse($zone->provinces ?? [] as $province)
                <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.625rem; border-radius: 0.5rem; background: #FFFFFF; border: 1px solid #E2E8F0; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);">
                    <svg style="width: 14px; height: 14px; color: #16A34A; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span style="font-size: 0.75rem; font-weight: 600; color: #1E293B; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $province }}</span>
                </div>
            @empty
                <div style="grid-column: 1 / -1; padding: 1.5rem; text-align: center; font-size: 0.75rem; color: #64748B; font-style: italic;">
                    Belum ada provinsi yang ditambahkan ke zona ini.
                </div>
            @endforelse
        </div>
    </div>
</div>
