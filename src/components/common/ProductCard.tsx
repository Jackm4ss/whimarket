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
    'New': 'bg-[#DCFCE7] text-[#15803D]',
    'Good': 'bg-[#DCFCE7] text-[#15803D]',
    'Like New': 'bg-[#F3F4F6] text-[#374151]',
    'Fair': 'bg-[#FEF3C7] text-[#D97706]',
  }[product.condition || 'Good'];

  return (
    <div className={`bg-white rounded-2xl border border-gray-100/90 shadow-[0_2px_12px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_24px_rgba(0,0,0,0.06)] hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col group ${className || ''}`}>
      <div className="w-full aspect-square bg-[#F4F4F6] overflow-hidden relative flex items-center justify-center p-2.5">
        {/* Custom Purple Badge (Original, Terverifikasi, Rare) at Top Left */}
        {product.badge && (
          <div className="absolute top-2.5 left-2.5 z-10">
            <span className="px-2 py-0.5 rounded-[5px] text-[10px] font-bold bg-[#4F26A6] text-white shadow-xs">
              {product.badge}
            </span>
          </div>
        )}

        {/* Condition Tag at Bottom Left - only render if product explicitly has condition */}
        {product.condition && (
          <div className="absolute bottom-2.5 left-2.5 z-10">
            <span className={`px-2 py-0.5 rounded-[5px] text-[10px] font-bold ${conditionBadgeStyle}`}>
              {product.condition}
            </span>
          </div>
        )}

        {/* Wishlist Heart Button at Top Right matching mockup (borderless, transparent bg with grey outline heart) */}
        <button
          type="button"
          onClick={(e) => {
            e.preventDefault();
            e.stopPropagation();
            onLikeToggle?.(product.id);
          }}
          className="absolute top-2.5 right-2.5 z-10 p-1 flex items-center justify-center text-gray-500 hover:text-[#4F26A6] transition-colors"
          title="Simpan ke Wishlist"
        >
          <svg className={`w-4 h-4 ${product.isLiked ? 'fill-[#4F26A6] text-[#4F26A6]' : 'fill-none stroke-current'}`} viewBox="0 0 24 24" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
          </svg>
        </button>
        <img
          src={product.image}
          alt={product.title}
          className="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
        />
      </div>
      <div className="p-3 sm:p-3.5 flex flex-col flex-1 justify-between">
        <div>
          <div className="flex items-center gap-1.5">
            <img
              src={product.sellerAvatar}
              alt={product.sellerName}
              className="w-5 h-5 rounded-full object-cover shrink-0 ring-1 ring-gray-100"
              loading="lazy"
            />
            <span className="text-[12px] sm:text-[12.5px] font-bold text-gray-900 truncate">
              {product.sellerName}
            </span>
            {product.verified && <VerifiedBadge size="sm" className="w-3.5 h-3.5" />}
          </div>
          <h3 className="text-[13px] sm:text-[13.5px] font-normal text-gray-800 mt-1.5 mb-2 line-clamp-1">
            {product.title}
          </h3>
        </div>
        <div className="flex items-center justify-between pt-0.5">
          <span className="text-xs sm:text-[14px] font-extrabold text-[#4F26A6]">
            {product.priceText}
          </span>
          <div className="inline-flex items-center gap-1 text-[11px] text-gray-400 font-medium">
            <svg className="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
            <span>{product.likes}</span>
          </div>
        </div>
      </div>
    </div>
  );
};
