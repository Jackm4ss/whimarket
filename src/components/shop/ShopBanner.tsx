import React from 'react';

export const ShopBanner: React.FC = () => {
  return (
    <div className="w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-4 pb-6">
      {/* Banner Card matching exact 1058x200 banner mockup pixel-perfectly */}
      <div className="relative w-full rounded-2xl sm:rounded-3xl bg-[#4A19BB] overflow-hidden min-h-[175px] sm:min-h-[185px] lg:h-[200px] flex items-center justify-between shadow-lg shadow-[#4A19BB]/15">
        
        {/* ==================== EXACT USER BANNER ASSET (RIGHT HALF) ==================== */}
        <div className="absolute right-0 top-0 bottom-0 h-full w-auto pointer-events-none z-0 hidden sm:block">
          <img
            src="/assets/banner-stage-full.png"
            alt=""
            className="h-full w-auto object-cover object-right"
          />
        </div>

        {/* ==================== LEFT HEADLINE & TYPOGRAPHY ==================== */}
        {/* Matches exact mockup: starts at left 6.9% (~73px in 1058px banner) */}
        <div className="relative z-10 max-w-[560px] text-left pl-6 sm:pl-12 lg:pl-16 xl:pl-[72px] pr-4 py-6 flex flex-col justify-center">
          
          {/* Main Title: 2 lines with exact font metrics and #F7AC1D yellow accent */}
          <h1 className="text-[26px] sm:text-[32px] lg:text-[38px] xl:text-[40px] font-extrabold text-white tracking-[-0.03em] leading-[1.22] mb-3">
            <span className="block">Temukan Barang</span>
            <span className="block">
              Pre-loved <span className="text-[#F7AC1D]">Favoritmu</span>
            </span>
          </h1>

          {/* Subtitle: 1 line with clean opacity and tracking */}
          <p className="text-xs sm:text-[14px] lg:text-[15px] font-normal text-white/80 tracking-[-0.01em] leading-normal">
            Original, diverifikasi, dan penuh cerita.
          </p>

        </div>

      </div>
    </div>
  );
};
