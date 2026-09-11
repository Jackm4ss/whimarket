<div style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.875rem; color: #334155;">
    {{-- Status Banner --}}
    @php
        $statusState = $seller->status instanceof \App\Enums\SellerStatus
            ? $seller->status
            : \App\Enums\SellerStatus::tryFrom((string) $seller->status);

        $statusConfig = match ($statusState) {
            \App\Enums\SellerStatus::VERIFIED => ['label' => 'Terverifikasi (Aktif)', 'bg' => '#DCFCE7', 'text' => '#15803D', 'border' => '#BBF7D0'],
            \App\Enums\SellerStatus::PENDING => ['label' => 'Menunggu Moderasi', 'bg' => '#FEF3C7', 'text' => '#D97706', 'border' => '#FDE68A'],
            \App\Enums\SellerStatus::REJECTED => ['label' => 'Ditolak', 'bg' => '#FEE2E2', 'text' => '#DC2626', 'border' => '#FECACA'],
            \App\Enums\SellerStatus::SUSPENDED => ['label' => 'Nonaktif (Ditangguhkan)', 'bg' => '#F1F5F9', 'text' => '#475569', 'border' => '#CBD5E1'],
            default => ['label' => ucfirst((string) $seller->status), 'bg' => '#F1F5F9', 'text' => '#475569', 'border' => '#E2E8F0'],
        };

        $phoneClean = $seller->user?->phone ? preg_replace('/[^0-9]/', '', $seller->user->phone) : null;
        $waNumber = $phoneClean ? (str_starts_with($phoneClean, '0') ? '62' . substr($phoneClean, 1) : $phoneClean) : null;
    @endphp

    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.875rem 1rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 0.75rem;">
        <div>
            <div style="font-size: 0.75rem; color: #64748B; font-weight: 500;">Status Toko</div>
            <div style="display: inline-flex; align-items: center; gap: 0.375rem; margin-top: 0.25rem; padding: 0.25rem 0.625rem; border-radius: 9999px; background: {{ $statusConfig['bg'] }}; color: {{ $statusConfig['text'] }}; border: 1px solid {{ $statusConfig['border'] }}; font-size: 0.8125rem; font-weight: 700;">
                {{ $statusConfig['label'] }}
            </div>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 0.75rem; color: #64748B; font-weight: 500;">Tanggal Bergabung</div>
            <div style="font-size: 0.75rem; font-weight: 600; color: #1E293B; margin-top: 0.25rem;">
                {{ $seller->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}
            </div>
        </div>
    </div>

    {{-- Rejection Note if any --}}
    @if($seller->rejection_reason)
        <div style="padding: 0.75rem 1rem; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 0.5rem; color: #991B1B; font-size: 0.8125rem;">
            <strong style="font-weight: 700;">Catatan Admin / Alasan:</strong> {{ $seller->rejection_reason }}
        </div>
    @endif

    {{-- Kontak & Identitas Pemilik --}}
    <div style="border: 1px solid #E2E8F0; border-radius: 0.75rem; overflow: hidden; background: #FFFFFF;">
        <div style="padding: 0.625rem 1rem; background: #F1F5F9; border-bottom: 1px solid #E2E8F0; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569;">
            Identitas & Kontak Pemilik Toko
        </div>
        <div style="padding: 1rem; display: flex; flex-direction: column; gap: 0.75rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 0.5rem; border-bottom: 1px solid #F1F5F9;">
                <span style="color: #64748B; font-size: 0.8125rem;">Nama Toko:</span>
                <span style="font-weight: 700; color: #0F172A;">{{ $seller->store_name }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 0.5rem; border-bottom: 1px solid #F1F5F9;">
                <span style="color: #64748B; font-size: 0.8125rem;">Username / Handle:</span>
                <span style="font-weight: 600; color: #4F26A6;">{{ '@' . $seller->username }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 0.5rem; border-bottom: 1px solid #F1F5F9;">
                <span style="color: #64748B; font-size: 0.8125rem;">Nama Lengkap Pemilik:</span>
                <span style="font-weight: 600; color: #1E293B;">{{ $seller->user?->name ?? '-' }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 0.5rem; border-bottom: 1px solid #F1F5F9;">
                <span style="color: #64748B; font-size: 0.8125rem;">Email Pemilik:</span>
                <span style="font-weight: 600; color: #1E293B;">{{ $seller->user?->email ?? '-' }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 0.5rem; border-bottom: 1px solid #F1F5F9;">
                <span style="color: #64748B; font-size: 0.8125rem;">Jenis Kelamin Pemilik:</span>
                <span style="font-weight: 600; color: #1E293B;">{{ $seller->user?->gender?->label() ?? '-' }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #64748B; font-size: 0.8125rem;">Nomor Telepon / WhatsApp:</span>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="font-weight: 700; color: #0F172A; font-family: monospace; font-size: 0.9375rem;">
                        {{ $seller->user?->phone ?? '-' }}
                    </span>
                    @if($waNumber)
                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.5rem; border-radius: 0.375rem; background: #22C55E; color: #FFFFFF; font-size: 0.6875rem; font-weight: 700; text-decoration: none;">
                            <svg style="width: 12px; height: 12px;" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                            </svg>
                            WhatsApp
                        </a>
                    @endif
                    @if($seller->user?->phone)
                        <a href="tel:{{ $seller->user->phone }}" style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.5rem; border-radius: 0.375rem; background: #F1F5F9; color: #334155; border: 1px solid #CBD5E1; font-size: 0.6875rem; font-weight: 700; text-decoration: none;">
                            Telp
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Rekening Bank Payout --}}
    <div style="border: 1px solid #E2E8F0; border-radius: 0.75rem; overflow: hidden; background: #FFFFFF;">
        <div style="padding: 0.625rem 1rem; background: #F1F5F9; border-bottom: 1px solid #E2E8F0; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569;">
            Informasi Rekening Bank Pencairan Dana (Payout)
        </div>
        <div style="padding: 1rem; display: flex; flex-direction: column; gap: 0.75rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 0.5rem; border-bottom: 1px solid #F1F5F9;">
                <span style="color: #64748B; font-size: 0.8125rem;">Bank:</span>
                <span style="font-weight: 700; color: #0F172A;">{{ $seller->bank_name }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 0.5rem; border-bottom: 1px solid #F1F5F9;">
                <span style="color: #64748B; font-size: 0.8125rem;">Nomor Rekening:</span>
                <span style="font-weight: 700; color: #4F26A6; font-family: monospace; font-size: 0.9375rem;">{{ $seller->bank_account_number }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <span style="color: #64748B; font-size: 0.8125rem;">Atas Nama Rekening:</span>
                <span style="font-weight: 600; color: #1E293B;">{{ $seller->bank_account_name }}</span>
            </div>
        </div>
    </div>

    {{-- Bio Toko & Statistik --}}
    <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
        <div style="border: 1px solid #E2E8F0; border-radius: 0.75rem; padding: 0.875rem 1rem; background: #F8FAFC;">
            <div style="font-size: 0.75rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.375rem;">
                Bio & Deskripsi Toko
            </div>
            <div style="color: #1E293B; font-size: 0.8125rem; line-height: 1.4;">
                {{ $seller->bio ?: 'Tidak ada deskripsi bio toko.' }}
            </div>
        </div>
    </div>
</div>
