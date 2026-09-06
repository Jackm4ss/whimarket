import React from 'react';
import { Product } from '@/types';
import { VerifiedBadge } from '@/components/common/VerifiedBadge';

interface ProductCardProps {
  product: Product;
  onLikeToggle?: (id: string) => void;
  className?: string;
}

export const ProductCard: React.FC<ProductCardProps> = ({ product, onLikeToggle, className }) => {
  const conditionBadgeStyle = {
    'New': 'bg-[#E8F8F0] text-[#0D9488]',
    'Good': 'bg-[#DCFCE7] text-[#15803D]',
    'Like New': 'bg-[#F3F4F6] text-[#4B5563]',
    'Fair': 'bg-[#FEF3C7] text-[#D97706]',
  }[product.condition || 'Good'];

  return (
    <div className={`bg-white rounded-2xl border border-gray-100/90 shadow-[0_4px_16px_rgba(0,0,0,0.03)] hover:shadow-[0_10px_24px_rgba(0,0,0,0.07)] hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col group ${className || ''}`}>
      <div className="w-full aspect-square bg-[#F8F8FA] overflow-hidden relative flex items-center justify-center p-2">
        {/* Condition Tag at Bottom Left */}
        {product.condition && (
          <div className="absolute bottom-2.5 left-2.5 z-10">
            <span className={`px-2 py-0.5 rounded-md text-[10.5px] sm:text-[11px] font-bold ${conditionBadgeStyle}`}>
              {product.condition}
            </span>
          </div>
        )}

        {/* Wishlist Heart Button at Top Right */}
        <button
          type="button"
          onClick={(e) => {
            e.preventDefault();
            e.stopPropagation();
            onLikeToggle?.(product.id);
          }}
          className="absolute top-2.5 right-2.5 z-10 w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white/90 backdrop-blur-sm shadow-sm border border-gray-100 flex items-center justify-center text-gray-400 hover:text-[#4F26A6] transition-colors"
          title="Simpan ke Wishlist"
        >
          <svg className={`w-3.5 h-3.5 sm:w-4 sm:h-4 ${product.isLiked ? 'fill-[#4F26A6] text-[#4F26A6]' : 'fill-none stroke-currentColor'}`} viewBox="0 0 24 24" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
          </svg>
        </button>

        <img
          src={product.image}
          alt={product.title}
          className="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
        />
      </div>
      <div className="p-3.5 sm:p-4 flex flex-col flex-1 justify-between">
        <div>
          <div className="flex items-center gap-2">
            <img
              src={product.sellerAvatar}
              alt={product.sellerName}
              className="w-6 h-6 rounded-full object-cover shrink-0 ring-1 ring-gray-100"
              loading="lazy"
            />
            <span className="text-xs sm:text-[13px] font-bold text-gray-900 truncate">
              {product.sellerName}
            </span>
            {product.verified && <VerifiedBadge size="sm" className="w-3.5 h-3.5" />}
          </div>
          <h3 className="text-[13.5px] sm:text-[14.5px] font-medium text-gray-700 mt-2 mb-3 line-clamp-1">
            {product.title}
          </h3>
        </div>
        <div className="flex items-center justify-between pt-1">
          <span className="text-sm sm:text-[15.5px] font-bold text-[#4F26A6]">
            {product.priceText}
          </span>
          <div className="inline-flex items-center gap-1 text-xs text-gray-400 font-medium">
            <svg className="w-3.5 h-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
            <span>{product.likes}</span>
          </div>
        </div>
      </div>
    </div>
  );
};
