import React, { useState } from 'react';
import { PRODUCTS } from '@/data/landingData';
import { ProductCard } from '@/components/common/ProductCard';

export const ProductsSection: React.FC = () => {
  const [products, setProducts] = useState(PRODUCTS);

  const handleLikeToggle = (id: string) => {
    setProducts((prev) =>
      prev.map((item) =>
        item.id === id
          ? {
              ...item,
              isLiked: !item.isLiked,
              likes: item.isLiked ? item.likes - 1 : item.likes + 1,
            }
          : item
      )
    );
  };

  return (
    <section className="w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-2 pb-20">
      {/* Section Header */}
      <div className="flex items-center justify-between mb-6 sm:mb-8">
        <h2 className="text-xl sm:text-2xl lg:text-[28px] font-extrabold text-[#111827] tracking-tight">
          Barang Terbaru
        </h2>
        <a
          href="#semua-barang"
          className="inline-flex items-center gap-1.5 text-xs sm:text-[14px] font-bold text-[#4F26A6] hover:text-[#3E1D85] transition-colors group"
        >
          <span>Lihat Semua</span>
          <svg
            className="w-4 h-4 text-[#4F26A6] group-hover:translate-x-1 transition-transform"
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

      {/* 5 Product Cards Grid */}
      <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 lg:gap-6">
        {products.map((product) => (
          <ProductCard key={product.id} product={product} onLikeToggle={handleLikeToggle} />
        ))}
      </div>
    </section>
  );
};
