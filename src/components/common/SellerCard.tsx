import React from 'react';
import { Creator } from '@/types';

interface SellerCardProps {
  seller: Creator;
}

export const SellerCard: React.FC<SellerCardProps> = ({ seller }) => {
  return (
    <div className="min-w-[195px] sm:min-w-[215px] lg:min-w-0 flex-shrink-0 lg:flex-shrink bg-white rounded-2xl sm:rounded-3xl border border-gray-100/90 shadow-[0_4px_18px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_25px_rgba(0,0,0,0.07)] hover:-translate-y-1 transition-all duration-300 p-4 sm:p-5 flex flex-col items-center text-center justify-between group snap-start">
      <div className="flex flex-col items-center w-full">
        {/* Avatar Container */}
        <div className="relative mb-3.5">
          <div className="w-16 h-16 sm:w-20 sm:h-20 rounded-full p-1 bg-gray-50 ring-1 ring-gray-200/80 shadow-sm overflow-hidden">
            <img
              src={seller.avatar}
              alt={seller.name}
              className="w-full h-full object-cover rounded-full group-hover:scale-105 transition-transform duration-300"
              loading="lazy"
            />
          </div>
        </div>
        {/* Name & Verified */}
        <div className="flex items-center justify-center gap-1.5 w-full">
          <h3 className="text-sm sm:text-[15.5px] font-bold text-gray-900 truncate max-w-[130px] sm:max-w-none">
            {seller.name}
          </h3>
          {seller.verified && (
            <svg className="w-4 h-4 text-[#4F26A6] fill-[#4F26A6] shrink-0" viewBox="0 0 24 24">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" fill="currentColor"/>
            </svg>
          )}
        </div>
        {/* Role */}
        <span className="text-xs text-gray-400 font-medium mt-0.5 mb-3.5">{seller.role}</span>
        {/* Stats Row */}
        <div className="flex items-center justify-center gap-3 text-xs sm:text-[12.5px] font-medium text-gray-500 mb-4 w-full">
          <span>{seller.itemCount} Barang</span>
          <span className="inline-flex items-center gap-1 text-gray-700 font-semibold">
            <svg className="w-3.5 h-3.5 text-[#F59E0B] fill-[#F59E0B]" viewBox="0 0 24 24">
              <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
            <span>{seller.rating.toFixed(1)}</span>
          </span>
        </div>
      </div>
      {/* CTA Button */}
      <a
        href={`#toko-${seller.id}`}
        className="w-full py-2.5 rounded-xl border border-purple-200/90 text-[#4F26A6] font-bold text-xs sm:text-[13.5px] hover:bg-[#4F26A6] hover:text-white transition-all text-center"
      >
        Lihat Toko
      </a>
    </div>
  );
};
