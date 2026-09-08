@props(['seller'])

@php
    $isModel = is_object($seller);
    $name = $isModel ? $seller->store_name : ($seller['name'] ?? '');
    $username = $isModel ? $seller->username : str_replace('@', '', $seller['handle'] ?? ($seller['username'] ?? ''));
    $profileUrl = '/seller/@' . $username;
    $avatar = $isModel ? ($seller->user?->avatar ?? '/assets/avatars/avatar-raisy.png') : ($seller['avatar'] ?? '/assets/avatars/avatar-raisy.png');
    $role = $isModel ? 'Verified Creator' : ($seller['role'] ?? 'Kreator');
    $verified = $isModel ? $seller->isVerified() : (!empty($seller['verified']));
    $rating = $isModel ? 4.9 : ($seller['rating'] ?? 5.0);
    $itemCount = $isModel ? $seller->products()->count() : ($seller['itemCount'] ?? 10);
    $followerCount = $isModel ? '12.5rb' : ($seller['followerCount'] ?? ($seller['salesCount'] ? $seller['salesCount'] . 'rb' : '10.5rb'));
    $reviewCount = $isModel ? '1.2rb' : ($seller['reviewCount'] ?? '1.2rb');
    $cardBg = $isModel ? '/assets/seller-card-cover-rachel.png' : ($seller['cardBg'] ?? '/assets/seller-card-cover-rachel.png');
@endphp

<div class="w-[260px] sm:w-[280px] lg:w-[280px] xl:w-[290px] shrink-0 bg-white rounded-2xl sm:rounded-3xl border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_32px_rgba(72,30,188,0.08)] transition-all flex flex-col justify-between group overflow-hidden snap-start text-left">
    <!-- Top Banner Section -->
    <div class="relative w-full h-[125px] sm:h-[135px] bg-[#E8DEFD] overflow-hidden">
        <img
            src="{{ $cardBg }}"
            alt="{{ $name }}"
            decoding="async"
            class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
        />
    </div>

    <!-- Circular Avatar Overlapping Banner and White Card Body -->
    <div class="relative -mt-8 ml-5 sm:ml-6 z-20 w-[64px] h-[64px] sm:w-[70px] sm:h-[70px] rounded-full p-0.5 bg-white ring-4 ring-white shadow-md overflow-hidden shrink-0">
        <img
            src="{{ $avatar }}"
            alt="{{ $name }}"
            class="w-full h-full object-cover rounded-full"
        />
    </div>

    <!-- Card Body Information -->
    <div class="px-5 sm:px-6 pt-2.5 pb-5 sm:pb-6 flex-1 flex flex-col justify-between">
        <div>
            <!-- Name & Verified Badge -->
            <div class="flex items-center gap-1.5 mb-0.5">
                <h3 class="text-[16px] sm:text-[17.5px] font-extrabold text-[#111827] tracking-tight hover:text-[#481EBC] transition-colors truncate">
                    <a href="{{ $profileUrl }}">{{ $name }}</a>
                </h3>
                @if(!empty($verified))
                    <x-verified-badge size="sm" class="w-4 h-4 shrink-0" />
                @endif
            </div>

            <!-- Role -->
            <p class="text-xs text-gray-400 font-medium mb-3">{{ $role }}</p>

            <!-- Rating Stars & Review Count -->
            <div class="flex items-center gap-1.5 text-xs text-gray-500 mb-3.5">
                <div class="flex items-center gap-0.5 text-[#F59E0B]">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    @endfor
                </div>
                <span class="font-bold text-gray-800">{{ number_format($rating, 1) }}</span>
                <span class="text-gray-400 font-normal">({{ $reviewCount }} ulasan)</span>
            </div>

            <!-- Horizontal Divider Separator Line -->
            <div class="w-full border-t border-gray-200 my-3.5"></div>

            <!-- Stats Columns: Barang & Pengikut with Vertical Separator -->
            <div class="grid grid-cols-2 divide-x divide-gray-200 text-center mb-5">
                <div class="pr-2">
                    <span class="text-[17px] sm:text-[18px] font-extrabold text-[#111827] block leading-tight">{{ $itemCount }}</span>
                    <span class="text-[12px] text-gray-400 font-medium block mt-0.5">Barang</span>
                </div>
                <div class="pl-2">
                    <span class="text-[17px] sm:text-[18px] font-extrabold text-[#111827] block leading-tight">{{ $followerCount }}</span>
                    <span class="text-[12px] text-gray-400 font-medium block mt-0.5">Pengikut</span>
                </div>
            </div>
        </div>

        <!-- Action Button: Lihat Toko -->
        <a
            href="{{ $profileUrl }}"
            class="w-full py-2.5 rounded-xl border border-purple-200 text-[#481EBC] hover:bg-[#481EBC] hover:text-white font-bold text-xs sm:text-[13px] transition-all text-center block cursor-pointer"
        >
            Lihat Toko
        </a>
    </div>
</div>
