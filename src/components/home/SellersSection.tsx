import React, { useRef } from 'react';
import { SELLERS } from '@/data/landingData';
import { SellerCard } from '@/components/common/SellerCard';

export const SellersSection: React.FC = () => {
  const scrollContainerRef = useRef<HTMLDivElement>(null);

  const scroll = (direction: 'left' | 'right') => {
    if (scrollContainerRef.current) {
      const scrollAmount = direction === 'left' ? -260 : 260;
      scrollContainerRef.current.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }
  };

  return (
    <section className="w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-2 pb-24 relative">
      {/* Section Header */}
      <div className="flex items-center justify-between mb-6 sm:mb-8">
        <h2 className="text-xl sm:text-2xl lg:text-[28px] font-extrabold text-[#111827] tracking-tight">
          Seller Populer
        </h2>
        <a
          href="#seller-populer"
          className="inline-flex items-center gap-1.5 text-sm sm:text-[15.5px] font-bold text-[#4F26A6] hover:text-[#3E1D85] transition-colors group shrink-0"
        >
          <span>Lihat Semua Seller</span>
          <svg
            className="w-4 h-4 sm:w-4.5 sm:h-4.5 text-[#4F26A6] group-hover:text-[#3E1D85] transform group-hover:translate-x-1 transition-transform"
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

      {/* Cards Container with Slider Track */}
      <div className="relative">
        {/* Desktop Floating Navigation Arrows (Visible on Desktop Only) */}
        <button
          onClick={() => scroll('left')}
          id="seller-prev-desktop"
          className="hidden lg:flex absolute -left-5 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white shadow-[0_4px_16px_rgba(0,0,0,0.18)] border border-gray-200/80 items-center justify-center text-gray-700 hover:text-[#4F26A6] active:scale-95 transition-all z-30 focus:outline-none"
          aria-label="Previous Seller"
        >
          <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M15 19l-7-7 7-7" />
          </svg>
        </button>

        <button
          onClick={() => scroll('right')}
          id="seller-next-desktop"
          className="hidden lg:flex absolute -right-5 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white shadow-[0_4px_16px_rgba(0,0,0,0.18)] border border-gray-200/80 items-center justify-center text-gray-700 hover:text-[#4F26A6] active:scale-95 transition-all z-30 focus:outline-none"
          aria-label="Next Seller"
        >
          <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M9 5l7 7-7 7" />
          </svg>
        </button>

        {/* Slider Track */}
        <div
          ref={scrollContainerRef}
          id="seller-track"
          className="flex lg:grid lg:grid-cols-5 overflow-x-auto lg:overflow-visible no-scrollbar scroll-smooth snap-x snap-mandatory gap-3.5 sm:gap-4 lg:gap-5 pb-4 lg:pb-0 -mx-4 px-4 sm:-mx-8 sm:px-8 md:-mx-12 md:px-12 lg:mx-0 lg:px-0"
        >
          {SELLERS.map((seller) => (
            <SellerCard key={seller.id} seller={seller} />
          ))}
        </div>

        {/* Mobile Slider Navigation Buttons */}
        <div className="flex lg:hidden items-center justify-center gap-3 mt-6">
          <button
            onClick={() => scroll('left')}
            id="seller-prev-bottom"
            className="w-10 h-10 rounded-full bg-white shadow-[0_2px_12px_rgba(0,0,0,0.1)] border border-gray-200/80 flex items-center justify-center text-gray-700 hover:text-[#4F26A6] active:bg-gray-50 transition-all focus:outline-none"
            aria-label="Previous Seller"
          >
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          <button
            onClick={() => scroll('right')}
            id="seller-next-bottom"
            className="w-10 h-10 rounded-full bg-white shadow-[0_2px_12px_rgba(0,0,0,0.1)] border border-gray-200/80 flex items-center justify-center text-gray-700 hover:text-[#4F26A6] active:bg-gray-50 transition-all focus:outline-none"
            aria-label="Next Seller"
          >
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>
      </div>
    </section>
  );
};
