import React from 'react';

export const ShopBanner: React.FC = () => {
  return (
    <div className="w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-4 pb-6">
      {/* Banner Card using user-provided exact custom vector mockup asset */}
      <div className="relative w-full rounded-2xl sm:rounded-3xl bg-[#4813CD] overflow-hidden min-h-[175px] sm:min-h-[185px] lg:h-[195px] flex items-center justify-between px-6 sm:px-10 lg:px-14 shadow-lg shadow-[#4813CD]/15">
        
        {/* ==================== EXACT USER VECTOR BACKGROUND ASSET ==================== */}
        <div className="absolute right-0 top-0 bottom-0 h-full w-auto pointer-events-none z-0">
          <img
            src="/assets/banner-vector-bg.png"
            alt=""
            className="h-full w-auto object-cover object-right"
          />
        </div>

        {/* Left Headline & Typography Area */}
        <div className="relative z-10 max-w-[560px] text-left py-4">
          <h1 className="text-2xl sm:text-3xl lg:text-[36px] xl:text-[38px] font-black text-white tracking-tight leading-[1.18] mb-2 sm:mb-2.5">
            Temukan Barang<br />
            Pre-loved <span className="text-[#FDBA2D]">Favoritmu</span>
          </h1>
          <p className="text-xs sm:text-[14px] lg:text-[14.5px] text-white/85 font-normal tracking-wide">
            Original, diverifikasi, dan penuh cerita.
          </p>
        </div>


      </div>
    </div>
  );
};
