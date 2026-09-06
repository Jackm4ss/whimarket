import React from 'react';

export const ShopBanner: React.FC = () => {
  return (
    <div className="w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-4 pb-6">
      {/* Deep Purple Solid Banner Card matching Mockup Image #1 */}
      <div className="relative w-full rounded-2xl sm:rounded-3xl bg-[#5222B5] overflow-hidden p-6 sm:p-8 lg:px-12 lg:py-8 flex flex-col lg:flex-row items-center justify-between min-h-[160px] lg:h-[185px] shadow-lg shadow-[#5222B5]/15">
        {/* Subtle Decorative Gradient Circles */}
        <div className="absolute -left-10 -bottom-10 w-48 h-48 rounded-full bg-white/5 blur-2xl pointer-events-none" />
        <div className="absolute right-1/3 top-0 w-64 h-64 rounded-full bg-white/5 blur-3xl pointer-events-none" />

        {/* Left Typography Area */}
        <div className="z-10 max-w-[540px] text-center lg:text-left flex flex-col justify-center">
          <h1 className="text-2xl sm:text-3xl lg:text-[34px] font-extrabold text-white tracking-tight leading-[1.2] mb-2">
            Temukan Barang<br />
            Pre-loved <span className="text-[#FDBA2D]">Favoritmu</span>
          </h1>
          <p className="text-xs sm:text-sm lg:text-[14px] text-white/80 leading-relaxed font-normal">
            Original, diverifikasi, dan penuh cerita.
          </p>
        </div>

        {/* Right Visual Composition: 4-Point Star + Purple Nike Dunk + Black Quilted Bag + Sticky Note */}
        <div className="relative z-10 w-full lg:w-auto flex items-center justify-center lg:justify-end gap-3 sm:gap-4 mt-5 lg:mt-0">
          {/* Decorative 4-Point Sparkle Star */}
          <div className="hidden sm:block absolute -left-12 top-2 text-white/90 pointer-events-none">
            <svg className="w-6 h-6 fill-white drop-shadow-sm" viewBox="0 0 24 24">
              <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z" />
            </svg>
          </div>
          <div className="hidden sm:block absolute -left-6 top-10 text-white/60 pointer-events-none scale-60">
            <svg className="w-5 h-5 fill-white" viewBox="0 0 24 24">
              <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z" />
            </svg>
          </div>

          {/* 1. Purple & White Nike Dunk Sneaker */}
          <div className="relative h-[85px] sm:h-[110px] lg:h-[125px] w-auto shrink-0 flex items-center justify-center">
            <img
              src="/assets/products/prod-dunk.png"
              alt="Nike Dunk Low Purple"
              className="h-full w-auto object-contain drop-shadow-[0_10px_20px_rgba(0,0,0,0.25)] transform -rotate-6 hover:rotate-0 transition-transform duration-300"
            />
          </div>

          {/* 2. Black Quilted Luxury Bag with Gold Chain */}
          <div className="relative h-[85px] sm:h-[110px] lg:h-[125px] w-auto shrink-0 flex items-center justify-center">
            <img
              src="/assets/products/prod-bag.png"
              alt="Luxury Quilted Handbag"
              className="h-full w-auto object-contain drop-shadow-[0_10px_20px_rgba(0,0,0,0.25)] hover:scale-105 transition-transform duration-300"
            />
          </div>

          {/* 3. Sticky Paper Note with Tape Accent */}
          <div className="relative shrink-0 rotate-3 transform hover:rotate-0 transition-transform duration-300 ml-1">
            <div className="relative bg-[#FAFAF7] rounded-sm px-3 sm:px-3.5 py-3 sm:py-3.5 w-[115px] sm:w-[130px] shadow-[0_10px_25px_rgba(0,0,0,0.18)] border border-gray-200/80 font-handwriting select-none">
              {/* Top Tape Strip */}
              <div className="absolute -top-2.5 left-1/2 -translate-x-1/2 w-10 sm:w-12 h-3.5 sm:h-4 bg-[#E8E6D8]/90 rounded-[2px] shadow-xs" />
              
              <div className="text-[11.5px] sm:text-[13px] font-bold text-gray-800 leading-[1.35] pt-0.5">
                <div>Lebih dari</div>
                <div>barang,</div>
                <div>ada cerita</div>
                <div className="flex items-center gap-1">
                  <span>di setiapnya.</span>
                  <span className="text-[#5222B5] text-xs">♡</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
