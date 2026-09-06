import React, { useState, useMemo } from 'react';
import { Navbar } from '@/components/layout/Navbar';
import { Footer } from '@/components/layout/Footer';
import { ShopBanner } from '@/components/shop/ShopBanner';
import { ShopSidebar, FilterState } from '@/components/shop/ShopSidebar';
import { ProductCard } from '@/components/common/ProductCard';
import { Pagination } from '@/components/common/Pagination';
import { SHOP_PRODUCTS } from '@/data/landingData';

type SortOption = 'terbaru' | 'harga-rendah' | 'harga-tinggi' | 'terpopuler';

interface ShopPageProps {
  onNavigateHome?: () => void;
}

export const ShopPage: React.FC<ShopPageProps> = ({ onNavigateHome }) => {
  const [filters, setFilters] = useState<FilterState>({
    category: 'all',
    condition: ['all'],
    priceMin: '',
    priceMax: '',
    location: '',
    shipping: ['all'],
  });

  const [sortOption, setSortOption] = useState<SortOption>('terbaru');
  const [viewMode, setViewMode] = useState<'grid' | 'list'>('grid');
  const [currentPage, setCurrentPage] = useState(1);
  const [products, setProducts] = useState(SHOP_PRODUCTS);
  const [isMobileFilterOpen, setIsMobileFilterOpen] = useState(false);

  // User state for logged-in header matching mockup
  const loggedInUser = {
    name: 'Halo, Dimas',
    avatar: '/assets/avatar-anya.png',
  };

  const handleResetFilters = () => {
    setFilters({
      category: 'all',
      condition: ['all'],
      priceMin: '',
      priceMax: '',
      location: '',
      shipping: ['all'],
    });
  };

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

  // Filter and Sort Logic
  const filteredProducts = useMemo(() => {
    return products.filter((item) => {
      // Category filter
      if (filters.category !== 'all' && item.category !== filters.category) {
        return false;
      }
      // Condition filter
      if (
        !filters.condition.includes('all') &&
        item.condition &&
        !filters.condition.includes(item.condition)
      ) {
        return false;
      }
      return true;
    });
  }, [products, filters]);

  return (
    <div className="min-h-screen flex flex-col justify-between bg-[#FAF9FC] text-[#111827] selection:bg-[#4F26A6] selection:text-white">
      {/* 1. Global Header with Belanja active, badges 2 & 3, user Dimas */}
      <Navbar
        activeTab="belanja"
        wishlistCount={2}
        cartCount={3}
        user={loggedInUser}
        onTabChange={(tab) => {
          if (tab === 'beranda') onNavigateHome?.();
        }}
      />

      <main className="flex-1 w-full pb-16">
        {/* 2. Shop Hero Banner */}
        <ShopBanner />

        {/* 3. Main Catalog Container */}
        <div className="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20">
          <div className="flex flex-col lg:flex-row items-start gap-6 xl:gap-8">
            {/* Desktop Left Sidebar */}
            <div className="hidden lg:block">
              <ShopSidebar
                filters={filters}
                onFilterChange={setFilters}
                onReset={handleResetFilters}
              />
            </div>

            {/* Mobile Filter Toggle Button */}
            <div className="lg:hidden w-full flex items-center justify-between pb-2">
              <button
                type="button"
                onClick={() => setIsMobileFilterOpen(true)}
                className="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200 text-xs sm:text-sm font-semibold text-gray-700 shadow-xs hover:border-[#4F26A6]"
              >
                <svg className="w-4 h-4 text-[#4F26A6]" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span>Filter Produk</span>
              </button>
            </div>

            {/* Mobile Filter Drawer Modal */}
            {isMobileFilterOpen && (
              <div className="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 backdrop-blur-xs p-0 sm:p-4">
                <div className="bg-white w-full max-w-md max-h-[90vh] overflow-y-auto rounded-t-3xl sm:rounded-2xl p-5 shadow-2xl">
                  <div className="flex items-center justify-between pb-4 border-b border-gray-100 mb-4">
                    <h2 className="text-base font-bold text-gray-900">Filter</h2>
                    <button
                      type="button"
                      onClick={() => setIsMobileFilterOpen(false)}
                      className="p-1 rounded-lg text-gray-400 hover:text-gray-700"
                    >
                      <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                  <ShopSidebar
                    filters={filters}
                    onFilterChange={setFilters}
                    onReset={handleResetFilters}
                  />
                  <div className="pt-4">
                    <button
                      type="button"
                      onClick={() => setIsMobileFilterOpen(false)}
                      className="w-full py-3 rounded-xl bg-[#4F26A6] text-white font-bold text-sm shadow-md"
                    >
                      Terapkan Filter
                    </button>
                  </div>
                </div>
              </div>
            )}

            {/* Right Catalog Area */}
            <div className="flex-1 w-full min-w-0">
              {/* Top Controls Bar */}
              <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 sm:pb-6">
                <div className="text-xs sm:text-[13.5px] font-semibold text-gray-700">
                  <span className="font-extrabold text-[#111827]">1.240</span> produk ditemukan
                </div>

                <div className="flex items-center gap-3 self-end sm:self-auto">
                  {/* Sorting Dropdown */}
                  <div className="flex items-center gap-2">
                    <span className="text-xs sm:text-[13px] text-gray-500 font-medium">Urutan:</span>
                    <div className="relative">
                      <select
                        value={sortOption}
                        onChange={(e) => setSortOption(e.target.value as SortOption)}
                        className="appearance-none bg-white border border-gray-200 rounded-xl pl-3 pr-8 py-1.5 text-xs sm:text-[13px] font-semibold text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] cursor-pointer shadow-xs"
                      >
                        <option value="terbaru">Terbaru</option>
                        <option value="harga-rendah">Harga Terendah</option>
                        <option value="harga-tinggi">Harga Tertinggi</option>
                        <option value="terpopuler">Terpopuler</option>
                      </select>
                      <svg className="w-3.5 h-3.5 text-gray-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.2" d="M19 9l-7 7-7-7" />
                      </svg>
                    </div>
                  </div>

                  {/* Grid vs List View Toggle */}
                  <div className="flex items-center gap-1 bg-white border border-gray-200 rounded-xl p-0.5 shadow-xs">
                    <button
                      type="button"
                      onClick={() => setViewMode('grid')}
                      className={`p-1.5 rounded-lg transition-colors ${
                        viewMode === 'grid'
                          ? 'bg-[#4F26A6] text-white shadow-xs'
                          : 'text-gray-400 hover:text-gray-700'
                      }`}
                      title="Tampilan Grid"
                    >
                      <svg className="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                        <rect x="3" y="3" width="7" height="7" rx="1" fill="currentColor" />
                        <rect x="14" y="3" width="7" height="7" rx="1" fill="currentColor" />
                        <rect x="14" y="14" width="7" height="7" rx="1" fill="currentColor" />
                        <rect x="3" y="14" width="7" height="7" rx="1" fill="currentColor" />
                      </svg>
                    </button>
                    <button
                      type="button"
                      onClick={() => setViewMode('list')}
                      className={`p-1.5 rounded-lg transition-colors ${
                        viewMode === 'list'
                          ? 'bg-[#4F26A6] text-white shadow-xs'
                          : 'text-gray-400 hover:text-gray-700'
                      }`}
                      title="Tampilan List"
                    >
                      <svg className="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round">
                        <line x1="8" y1="6" x2="21" y2="6" />
                        <line x1="8" y1="12" x2="21" y2="12" />
                        <line x1="8" y1="18" x2="21" y2="18" />
                        <circle cx="4" cy="6" r="1.5" fill="currentColor" />
                        <circle cx="4" cy="12" r="1.5" fill="currentColor" />
                        <circle cx="4" cy="18" r="1.5" fill="currentColor" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>

              {/* 15 Product Cards Grid (5 columns on xl/lg, 3 on md, 2 on sm) */}
              <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4 lg:gap-4.5">
                {filteredProducts.map((product) => (
                  <ProductCard
                    key={product.id}
                    product={product}
                    onLikeToggle={handleLikeToggle}
                  />
                ))}
              </div>

              {/* Pagination */}
              <Pagination
                currentPage={currentPage}
                onPageChange={(page) => setCurrentPage(page)}
              />
            </div>
          </div>
        </div>
      </main>

      {/* 4. Global Footer */}
      <Footer />
    </div>
  );
};
