import React from 'react';
import { Product } from '@/types';

interface ProductCardProps {
  product: Product;
}

export const ProductCard: React.FC<ProductCardProps> = ({ product }) => {
  return (
    <div className="bg-white rounded-2xl border border-gray-100/90 shadow-[0_4px_16px_rgba(0,0,0,0.04)] hover:shadow-[0_10px_26px_rgba(0,0,0,0.08)] hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col group">
      <div className="w-full aspect-[4/5] bg-gray-50 overflow-hidden relative flex items-center justify-center">
        <img
          src={product.image}
          alt={product.title}
          className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
          loading="lazy"
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
            {product.verified && (
              <svg className="w-3.5 h-3.5 text-[#4F26A6] fill-[#4F26A6] shrink-0" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" fill="currentColor"/>
              </svg>
            )}
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
