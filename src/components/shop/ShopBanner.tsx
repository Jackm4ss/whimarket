import React from 'react';

export const ShopBanner: React.FC = () => {
  return (
    <div className="w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-3 pb-8">
      {/* Breadcrumb */}
      <nav className="flex items-center gap-2 text-xs sm:text-[13px] text-gray-500 mb-3 font-medium">
        <a href="#beranda" className="hover:text-[#4F26A6] transition-colors">Belanja</a>
        <span className="text-gray-400">›</span>
        <span className="text-[#4F26A6] font-semibold">Semua Produk</span>
      </nav>

      {/* Hero Banner Card */}
      <div className="relative w-full rounded-2xl sm:rounded-3xl bg-gradient-to-r from-[#F2EDFB] via-[#EFE9FA] to-[#ECE3FA] overflow-hidden p-6 sm:p-8 lg:px-12 lg:py-9 flex flex-col md:flex-row items-center justify-between min-h-[170px] lg:h-[190px]">
        {/* Left Typography */}
        <div className="z-10 max-w-[560px] text-center md:text-left">
          <h1 className="text-2xl sm:text-3xl lg:text-[34px] font-extrabold text-[#111827] tracking-tight leading-tight mb-2 sm:mb-2.5">
            Temukan Barang <span className="text-[#4F26A6]">Favoritmu</span>
          </h1>
          <p className="text-xs sm:text-sm lg:text-[14.5px] text-gray-600 leading-relaxed font-normal">
            Produk pre-loved dan merchandise original dari kreator favorit, semua dalam satu tempat.
          </p>
        </div>

        {/* Right Visual Graphic Area */}
        <div className="relative w-full md:w-auto h-full flex items-center justify-center md:justify-end mt-4 md:mt-0">
          {/* Handwritten Slogan with Heart & Sparkles */}
          <div className="hidden lg:flex flex-col items-end mr-6 font-handwriting select-none transform -rotate-2">
            <div className="text-[#5B27B5] text-lg lg:text-[21px] font-bold leading-tight">
              Pre-loved
            </div>
            <div className="text-[#5B27B5] text-base lg:text-[19px] font-bold leading-tight -mt-0.5">
              More Stories
            </div>
            <div className="text-[#5B27B5] text-base lg:text-[19px] font-bold leading-tight flex items-center gap-1 -mt-0.5">
              <span>Brighter Tomorrow</span>
              <span className="text-xs">♥</span>
            </div>
          </div>

          {/* Yellow Sparkles */}
          <div className="hidden sm:block absolute right-36 top-1 text-[#F59E0B] pointer-events-none">
            <svg className="w-5 h-5 fill-[#F59E0B]" viewBox="0 0 24 24">
              <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z" />
            </svg>
          </div>
          <div className="hidden sm:block absolute right-4 bottom-2 text-[#F59E0B] pointer-events-none scale-75">
            <svg className="w-4 h-4 fill-[#F59E0B]" viewBox="0 0 24 24">
              <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z" />
            </svg>
          </div>

          {/* Folded Purple Hoodie Asset */}
          <div className="relative h-[110px] sm:h-[130px] lg:h-[145px] w-auto shrink-0 flex items-center justify-center">
            <img
              src="/assets/banner-hoodie.png"
              alt="Purple Hoodie"
              className="h-full w-auto object-contain drop-shadow-md transform -rotate-6 hover:rotate-0 transition-transform duration-300"
            />
          </div>
        </div>
      </div>
    </div>
  );
};
