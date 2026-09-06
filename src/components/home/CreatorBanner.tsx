import React from 'react';

export const CreatorBanner: React.FC = () => {
  return (
    <section className="w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-2 pb-24">
      <div className="relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-[0_14px_40px_rgba(79,38,166,0.18)] bg-[#501EB4] flex flex-col lg:flex-row items-center lg:items-stretch justify-between min-h-[260px] sm:min-h-[290px] lg:h-[305px] xl:h-[320px]">
        {/* Left Content */}
        <div className="z-20 w-full lg:w-[52%] p-7 sm:p-10 lg:pl-16 xl:pl-20 lg:pr-6 py-10 sm:py-12 flex flex-col items-center lg:items-start text-center lg:text-left justify-center">
          <h2 className="text-2xl sm:text-3xl lg:text-[34px] font-extrabold text-white leading-tight mb-2.5 sm:mb-3">
            Jadi Kreator di Whimarket
          </h2>
          <p className="text-[#D8CEF8] text-sm sm:text-[15px] lg:text-[15.5px] leading-[1.7] max-w-[480px] mb-7 sm:mb-8 font-normal">
            Jual barang pre-loved atau merchandise kamu dengan mudah dan aman. Jangkau ribuan penggemarmu sekarang!
          </p>
          <a
            href="#daftar-kreator"
            className="inline-flex items-center gap-2.5 px-7 py-3.5 bg-white text-[#4D17B6] hover:bg-gray-50 font-bold text-xs sm:text-[14.5px] rounded-xl shadow-sm hover:shadow-md transition-all transform hover:-translate-y-0.5 group"
          >
            <span>Daftar Sebagai Kreator</span>
            <svg
              className="w-4 h-4 text-[#4D17B6] group-hover:translate-x-1 transition-transform"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2.5"
              strokeLinecap="round"
              strokeLinejoin="round"
            >
              <line x1="5" y1="12" x2="19" y2="12" />
              <polyline points="12 5 19 12 12 19" />
            </svg>
          </a>
        </div>

        {/* Right Visual Area */}
        <div className="relative w-full lg:w-[48%] h-[260px] sm:h-[290px] lg:h-full flex items-end justify-center lg:justify-end overflow-hidden bg-[#F5A623] rounded-t-[100px] lg:rounded-t-none lg:rounded-l-[150px]">
          <img
            src="/assets/banner.png"
            alt="Jadi Kreator di Whimarket"
            className="relative z-10 w-auto h-[92%] sm:h-[95%] lg:h-[94%] object-contain object-bottom"
          />
        </div>
      </div>
    </section>
  );
};
