import React from 'react';

export const ShopBanner: React.FC = () => {
  return (
    <div className="w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-4 pb-6">
      {/* Banner Card matching exact 1088x200 banner mockup with custom vector background */}
      <div className="relative w-full rounded-2xl sm:rounded-3xl bg-[#491FB6] overflow-hidden min-h-[175px] sm:min-h-[185px] lg:h-[195px] flex items-center justify-between px-6 sm:px-10 lg:px-14 shadow-lg shadow-[#491FB6]/15">
        
        {/* ==================== EXACT CUSTOM VECTOR BACKGROUND SHAPES ==================== */}
        <svg
          className="absolute right-0 top-0 bottom-0 h-full w-auto pointer-events-none z-0"
          viewBox="0 0 600 200"
          preserveAspectRatio="none"
          fill="none"
        >
          {/* Main Arched Dome Wave (#7444DF / #7846E2) */}
          <path
            d="M 140 200 C 140 70, 240 12, 340 12 C 430 12, 520 50, 600 95 L 600 200 Z"
            fill="#7444DF"
            fillOpacity="0.85"
          />
          {/* Secondary Soft Ambient Wave (#8A56EC) */}
          <path
            d="M 280 0 C 380 0, 500 25, 600 70 L 600 0 Z"
            fill="#8A56EC"
            fillOpacity="0.45"
          />
          {/* Bottom Left Curve Accent Under Sneaker */}
          <path
            d="M 0 200 C 20 165, 80 145, 150 160 C 200 170, 230 200, 230 200 Z"
            fill="#6027D1"
            fillOpacity="0.75"
          />
        </svg>

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

        {/* Center Sparkle Stars */}
        <div className="hidden md:flex absolute left-[47%] lg:left-[51%] top-1/2 -translate-y-1/2 flex-col items-center pointer-events-none z-10">
          {/* Top-left larger 4-point sparkle star */}
          <div className="text-white transform -translate-x-3 -translate-y-2">
            <svg className="w-6 h-6 fill-white drop-shadow-sm" viewBox="0 0 24 24">
              <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z" />
            </svg>
          </div>
          {/* Bottom-right smaller sparkle star */}
          <div className="text-white/80 transform translate-x-2 translate-y-1 scale-75">
            <svg className="w-5 h-5 fill-white" viewBox="0 0 24 24">
              <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z" />
            </svg>
          </div>
        </div>

        {/* Right Visual Stage (White Pedestal Disc + Nike Dunk + Chanel Bag + Sticky Note) */}
        <div className="relative z-20 hidden sm:flex items-end justify-end h-full shrink-0 pb-1 sm:pb-2">
          
          {/* White Circular Stage Pedestal Surface */}
          <div className="absolute -bottom-7 right-6 sm:right-8 w-[320px] sm:w-[360px] lg:w-[410px] h-[36px] sm:h-[42px] bg-gradient-to-b from-[#FFFFFF] via-[#F6F6F9] to-[#D8D8E2] rounded-full shadow-[0_12px_28px_rgba(0,0,0,0.25)] border-t border-white/80 pointer-events-none" />

          {/* Stage Assets Container */}
          <div className="relative flex items-end gap-2 sm:gap-2.5 lg:gap-3.5 pr-2">
            
            {/* 1. Nike Dunk Low Purple Sneaker resting on pedestal */}
            <div className="relative z-20 h-[105px] sm:h-[125px] lg:h-[140px] w-auto shrink-0 mb-1">
              <img
                src="/assets/banner-nike-dunk.png"
                alt="Nike Dunk Low Purple"
                className="h-full w-auto object-contain drop-shadow-[0_12px_20px_rgba(0,0,0,0.30)] transform -rotate-[4deg] hover:rotate-0 transition-transform duration-300"
              />
            </div>

            {/* 2. Black Quilted Flap Bag with Gold Chain Draped Forward */}
            <div className="relative z-20 h-[95px] sm:h-[115px] lg:h-[130px] w-auto shrink-0 mb-1">
              <img
                src="/assets/banner-chanel-bag.png"
                alt="Black Quilted Handbag"
                className="h-full w-auto object-contain drop-shadow-[0_10px_20px_rgba(0,0,0,0.32)] hover:scale-105 transition-transform duration-300"
              />
            </div>

            {/* 3. White Sticky Note with Tape Accent & Double Dash */}
            <div className="relative z-20 mb-2 sm:mb-3 shrink-0 rotate-[4deg] transform hover:rotate-0 transition-transform duration-300">
              <div className="relative bg-[#FAFAF7] rounded-[3px] p-3 sm:p-3.5 pt-3.5 sm:pt-4 pb-3 w-[115px] sm:w-[125px] lg:w-[130px] shadow-[0_10px_24px_rgba(0,0,0,0.20)] border border-gray-200/90 font-handwriting select-none">
                {/* Top Tape Strip */}
                <div className="absolute -top-2.5 left-1/2 -translate-x-1/2 w-10 sm:w-11 h-3.5 sm:h-4 bg-[#E8E6D8] rounded-[1px] shadow-2xs" />
                
                <div className="text-[12px] sm:text-[13px] font-bold text-[#111827] leading-[1.3] pt-0.5">
                  <div>Lebih dari</div>
                  <div>barang,</div>
                  <div>ada cerita</div>
                  <div>di setiapnya.</div>
                </div>

                {/* Decorative Two Purple Lines doodle at bottom right */}
                <div className="absolute bottom-2 right-2 text-[#4D18C8] flex flex-col gap-0.5">
                  <div className="w-3 h-[2px] bg-[#4D18C8] rounded-full transform -rotate-12" />
                  <div className="w-3 h-[2px] bg-[#4D18C8] rounded-full transform -rotate-12 ml-0.5" />
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  );
};
