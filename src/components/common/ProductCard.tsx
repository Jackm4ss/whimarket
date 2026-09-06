import React from 'react';
import { Product } from '@/types';
import { VerifiedBadge } from '@/components/common/VerifiedBadge';

interface ProductCardProps {
  product: Product;
  onLikeToggle?: (id: string) => void;
  className?: string;
}

export const ProductCard: React.FC<ProductCardProps> = ({ product, onLikeToggle, className }) => {
  return (
    <div className={`bg-white rounded-2xl border border-gray-100/90 shadow-[0_4px_16px_rgba(0,0,0,0.04)] hover:shadow-[0_10px_26px_rgba(0,0,0,0.08)] hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col group ${className || ''}`}>
      {/* Product Image Stage */}
      <div className="w-full aspect-[4/5] bg-gray-100 overflow-hidden relative flex items-center justify-center">
        
        {/* Wishlist Heart Button at Top Right matching exact mockup: circular white button with shadow */}
        <button
          type="button"
          onClick={(e) => {
            e.preventDefault();
            e.stopPropagation();
            onLikeToggle?.(product.id);
          }}
          className="absolute top-2.5 right-2.5 z-10 w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white/95 backdrop-blur-xs shadow-md flex items-center justify-center text-gray-700 hover:text-[#4F26A6] transition-transform hover:scale-105"
          title="Simpan ke Wishlist"
        >
          <svg
            className={`w-3.5 h-3.5 sm:w-4 sm:h-4 ${product.isLiked ? 'fill-[#4F26A6] text-[#4F26A6]' : 'fill-none stroke-current'}`}
            viewBox="0 0 24 24"
            strokeWidth="2.2"
            strokeLinecap="round"
            strokeLinejoin="round"
          >
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
          </svg>
        </button>

        <img
          src={product.image}
          alt={product.title}
          className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 z-0"
        />

        {/* Condition Tag Badge at Bottom Left matching exact mockup: white solid rounded pill */}
        {product.condition && (
          <div className="absolute bottom-2.5 left-2.5 z-20 pointer-events-none">
            <span className="px-2.5 sm:px-3 py-1 rounded-lg sm:rounded-xl bg-white text-gray-900 text-[11px] sm:text-[12px] font-bold shadow-md border border-black/5">
              {product.condition}
            </span>
          </div>
        )}
      </div>

      {/* Card Info Body */}
      <div className="p-3.5 sm:p-4 flex flex-col flex-1 justify-between">
        <div>
          {/* Seller Name & Verified Rosette */}
          <div className="flex items-center gap-2">
            <img
              src={product.sellerAvatar}
              alt={product.sellerName}
              className="w-5 h-5 sm:w-6 sm:h-6 rounded-full object-cover shrink-0 ring-1 ring-gray-100"
              loading="lazy"
            />
            <span className="text-xs sm:text-[13px] font-bold text-gray-900 truncate">
              {product.sellerName}
            </span>
            {product.verified && <VerifiedBadge size="sm" className="w-3.5 h-3.5" />}
          </div>

          {/* Product Title */}
          <h3 className="text-[13.5px] sm:text-[14.5px] font-medium text-gray-700 mt-2 mb-3 line-clamp-1">
            {product.title}
          </h3>
        </div>

        {/* Price & Likes Count */}
        <div className="flex items-center justify-between pt-1">
          <span className="text-sm sm:text-[15.5px] font-bold text-[#4F26A6]">
            {product.priceText}
          </span>
          <div className="inline-flex items-center gap-1 text-xs text-gray-400 font-medium">
            <svg
              className="w-3.5 h-3.5 text-gray-400"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2"
              strokeLinecap="round"
              strokeLinejoin="round"
            >
              <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
            </svg>
            <span>{product.likes}</span>
          </div>
        </div>
      </div>
    </div>
  );
};
