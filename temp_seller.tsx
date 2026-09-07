import React, { useState, useMemo } from 'react';
import { Navbar } from '@/components/layout/Navbar';
import { Footer } from '@/components/layout/Footer';
import { VerifiedBadge } from '@/components/common/VerifiedBadge';
import { ProductCard } from '@/components/common/ProductCard';
import { SHOP_PRODUCTS_NEW } from '@/data/shopData';
import { Product } from '@/types';

interface SellerProfilePageProps {
  onNavigateHome?: () => void;
  onNavigateShop?: () => void;
  sellerId?: string;
}

export const SellerProfilePage: React.FC<SellerProfilePageProps> = ({
  onNavigateHome,
  onNavigateShop,
}) => {
  const [activeTab, setActiveTab] = useState<'produk' | 'ulasan'>('produk');
  const [isFollowing, setIsFollowing] = useState(false);
  const [isShareCopied, setIsShareCopied] = useState(false);
  const [isBioExpanded, setIsBioExpanded] = useState(false);
  const [selectedReviewFilter, setSelectedReviewFilter] = useState<string>('all');
  const [reviewSort, setReviewSort] = useState<string>('terbaru');
  const [selectedCategory, setSelectedCategory] = useState<string>('all');
  const [sortBy, setSortBy] = useState<'terbaru' | 'harga-terendah' | 'harga-tertinggi'>('terbaru');
  const [searchQuery, setSearchQuery] = useState('');

  // Filter products for Rachel Vennya or high quality catalog
  const [products, setProducts] = useState<Product[]>(() => {
    // Curated catalog for Rachel Vennya store
    return [
      {
        id: 'rv_1',
        title: 'Nike Dunk Low Purple (Used)',
        sellerName: 'Rachel Vennya',
        sellerAvatar: '/assets/avatar-rachel.png',
        verified: true,
        condition: 'Seperti Baru',
        priceText: 'Rp 1.200.000',
        priceNumber: 1200000,
        likes: 128,
        isLiked: false,
        image: '/assets/products/prod-dunk.png',
        category: 'fashion',
        location: 'Jakarta Selatan',
        href: '#rv-1',
      },
      {
        id: 'rv_2',
        title: 'Tas Michael Kors Original Brown',
        sellerName: 'Rachel Vennya',
        sellerAvatar: '/assets/avatar-rachel.png',
        verified: true,
        condition: 'Sangat Baik',
        priceText: 'Rp 2.450.000',
        priceNumber: 2450000,
        likes: 215,
        isLiked: false,
        image: '/assets/banner-chanel-bag.png',
        category: 'tas',
        location: 'Jakarta Selatan',
        href: '#rv-2',
      },
      {
        id: 'rv_3',
        title: 'Varsity Jacket Whimarket Exclusive',
        sellerName: 'Rachel Vennya',
        sellerAvatar: '/assets/avatar-rachel.png',
        verified: true,
        condition: 'Seperti Baru',
        priceText: 'Rp 650.000',
        priceNumber: 650000,
        likes: 94,
        isLiked: false,
        image: '/assets/products/prod-hoodie.png',
        category: 'fashion',
        location: 'Jakarta Selatan',
        href: '#rv-3',
      },
      {
        id: 'rv_4',
        title: 'Jaket Denim Vintage Washed',
        sellerName: 'Rachel Vennya',
        sellerAvatar: '/assets/avatar-rachel.png',
        verified: true,
        condition: 'Baik',
        priceText: 'Rp 450.000',
        priceNumber: 450000,
        likes: 142,
        isLiked: false,
        image: '/assets/products/prod-denim.png',
        category: 'fashion',
        location: 'Jakarta Selatan',
        href: '#rv-4',
      },
      {
        id: 'rv_5',
        title: 'Parfum Original Rare Luxury',
        sellerName: 'Rachel Vennya',
        sellerAvatar: '/assets/avatar-rachel.png',
        verified: true,
        condition: 'Sangat Baik',
        priceText: 'Rp 850.000',
        priceNumber: 850000,
        likes: 76,
        isLiked: false,
        image: '/assets/products/prod-parfum.png',
        category: 'kecantikan',
        location: 'Jakarta Selatan',
        href: '#rv-5',
      },
      {
        id: 'rv_6',
        title: 'Totebag Limited Edition White',
        sellerName: 'Rachel Vennya',
        sellerAvatar: '/assets/avatar-rachel.png',
        verified: true,
        condition: 'Seperti Baru',
        priceText: 'Rp 195.000',
        priceNumber: 195000,
        likes: 310,
        isLiked: false,
        image: '/assets/products/prod-totebag.png',
        category: 'merch',
        location: 'Jakarta Selatan',
        href: '#rv-6',
      },
    ];
  });

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

  const handleShare = () => {
    if (navigator.clipboard) {
      navigator.clipboard.writeText(window.location.href);
      setIsShareCopied(true);
      setTimeout(() => setIsShareCopied(false), 2000);
    }
  };

  const filteredProducts = useMemo(() => {
    return products.filter((item) => {
      if (selectedCategory !== 'all' && item.category !== selectedCategory) return false;
      if (searchQuery && !item.title.toLowerCase().includes(searchQuery.toLowerCase())) return false;
      return true;
    }).sort((a, b) => {
      if (sortBy === 'harga-terendah') return a.priceNumber - b.priceNumber;
      if (sortBy === 'harga-tertinggi') return b.priceNumber - a.priceNumber;
      return 0;
    });
  }, [products, selectedCategory, searchQuery, sortBy]);

  return (
    <div className="min-h-screen flex flex-col justify-between bg-[#FAF9FC] text-[#111827] selection:bg-[#4F26A6] selection:text-white">
      {/* 1. Global Navbar */}
      <Navbar
        activeTab="belanja"
        wishlistCount={2}
        cartCount={1}
        user={{
          name: 'Halo, Dimas',
          avatar: '/assets/avatar-jerome.png',
        }}
        onTabChange={(tab) => {
          if (tab === 'beranda') onNavigateHome?.();
          if (tab === 'belanja') onNavigateShop?.();
        }}
      />
      <main className="flex-1 w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-3 pb-20">
        {/* 2. Breadcrumbs: Beranda > Seller > Rachel Vennya */}
        <nav className="flex items-center gap-2 text-xs sm:text-[13px] text-gray-500 font-medium mb-3">
          <button
            onClick={onNavigateHome}
            className="hover:text-[#4F26A6] transition-colors cursor-pointer"
          >
            Beranda
          </button>
          <span className="text-gray-300 font-normal">&gt;</span>
          <button
            onClick={onNavigateShop}
            className="hover:text-[#4F26A6] transition-colors cursor-pointer"
          >
            Seller
          </button>
          <span className="text-gray-300 font-normal">&gt;</span>
          <span className="text-gray-900 font-bold">Rachel Vennya</span>
        </nav>

        {/* 3. Hero Banner (reduced height: aspect 1568/380 with max height constraint) */}
        <div className="relative w-full h-[180px] sm:h-[240px] md:h-[280px] lg:h-[300px] xl:h-[320px] rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xs">
          <img
            src="/assets/seller-banner-rachel.png"
            alt="Rachel Vennya Banner"
            className="w-full h-full object-cover object-center"
          />

          {/* "Bagikan Toko" Pill Button (Top Right inside banner) */}
          <button
            onClick={handleShare}
            className="absolute top-4 right-4 bg-white/95 hover:bg-white backdrop-blur-md px-3.5 py-1.5 rounded-full shadow-xs hover:shadow-sm border border-white/80 flex items-center gap-1.5 text-[12px] font-bold text-gray-800 hover:text-[#4F26A6] transition-all cursor-pointer group"
          >
            <svg
              className="w-3.5 h-3.5 text-gray-700 group-hover:text-[#4F26A6] transition-colors"
              fill="none"
              stroke="currentColor"
              strokeWidth="2"
              viewBox="0 0 24 24"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"
              />
            </svg>
            <span>{isShareCopied ? 'Tersalin!' : 'Bagikan Toko'}</span>
          </button>
        </div>
        {/* 4. Profile Row */}
        <div className="relative pt-0 mb-6 px-1 sm:px-2">
          {/* Desktop & Tablet (md and above, >=768px): 100% exact to mockup (Avatar on left, Info next to avatar, Buttons on right) */}
          <div className="hidden md:flex items-start justify-between gap-6">
            {/* Left: Avatar + Full Info side-by-side */}
            <div className="flex items-start gap-6 pl-4">
              {/* Circular Avatar */}
              <div className="-mt-16 xl:-mt-18 shrink-0 z-20">
                <img
                  src="/assets/avatar-rachel-exact.png"
                  alt="Rachel Vennya"
                  className="w-36 h-36 lg:w-40 lg:h-40 rounded-full object-cover ring-4 sm:ring-[5px] ring-white shadow-lg bg-white"
                />
              </div>

              {/* Seller Info (strictly NEXT to avatar, matching desktop mockup 1:1) */}
              <div className="flex flex-col pt-2.5">
                {/* Name + Verified Rosette */}
                <div className="flex items-center gap-2 mb-1">
                  <h1 className="text-[25px] xl:text-[28px] font-black text-[#111827] tracking-tight leading-tight">
                    Rachel Vennya
                  </h1>
                  <VerifiedBadge size="md" className="w-5.5 h-5.5 shrink-0" />
                </div>

                {/* Subtitle / Role */}
                <p className="text-[14px] text-gray-500 font-medium mb-1">
                  Selebgram
                </p>

                {/* Bio quote */}
                <p className="text-[14.5px] text-gray-700 font-normal mb-3">
                  &ldquo;Let good things find a new home ♡&rdquo;
                </p>

                {/* Desktop Stats Row: All in one line next to avatar */}
                <div className="flex items-center gap-3.5 text-[13.5px] text-gray-600 font-medium">
                  {/* Rating */}
                  <div className="inline-flex items-center gap-1.5 shrink-0">
                    <svg className="w-[18px] h-[18px] text-amber-400 fill-current shrink-0" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <span className="font-extrabold text-gray-900 text-[15px]">4.9</span>
                    <span className="text-gray-400 font-normal whitespace-nowrap text-[13px]">(1.2rb ulasan)</span>
                  </div>

                  <span className="text-gray-200 font-light">|</span>

                  {/* Barang Count */}
                  <div className="inline-flex items-center gap-1.5 shrink-0">
                    <svg className="w-[18px] h-[18px] text-gray-400 shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span className="font-semibold text-gray-800 whitespace-nowrap text-[13.5px]">112 Barang</span>
                  </div>

                  <span className="text-gray-200 font-light">|</span>

                  {/* Pengikut */}
                  <div className="inline-flex items-center gap-1.5 shrink-0">
                    <svg className="w-[18px] h-[18px] text-gray-400 shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span className="font-semibold text-gray-800 whitespace-nowrap text-[13.5px]">12.4rb Pengikut</span>
                  </div>

                  <span className="text-gray-200 font-light">|</span>

                  {/* Bergabung */}
                  <div className="inline-flex items-center gap-1.5 shrink-0">
                    <svg className="w-[18px] h-[18px] text-gray-400 shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                      <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                      <line x1="16" y1="2" x2="16" y2="6" />
                      <line x1="8" y1="2" x2="8" y2="6" />
                      <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    <span className="text-gray-500 font-normal whitespace-nowrap text-[13px]">Bergabung sejak Mar 2024</span>
                  </div>
                </div>
              </div>
            </div>

            {/* Right: Desktop Action Buttons */}
            <div className="flex items-center gap-3 pt-6 pr-1 shrink-0">
              <button
                type="button"
                onClick={() => setIsFollowing(!isFollowing)}
                className={`px-7 h-11 sm:h-11.5 rounded-xl text-[14px] font-bold flex items-center justify-center gap-2 transition-all cursor-pointer shadow-xs ${
                  isFollowing
                    ? 'bg-gray-100 text-gray-800 hover:bg-gray-200'
                    : 'bg-[#4F26A6] text-white hover:bg-[#3E1D85] shadow-[0_4px_16px_rgba(79,38,166,0.22)]'
                }`}
              >
                <svg className="w-4 h-4" fill="none" stroke="currentColor" strokeWidth="2.5" viewBox="0 0 24 24">
                  {isFollowing ? (
                    <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7" />
                  ) : (
                    <path strokeLinecap="round" strokeLinejoin="round" d="M12 4v16m8-8H4" />
                  )}
                </svg>
                <span>{isFollowing ? 'Mengikuti' : 'Ikuti Toko'}</span>
              </button>

              <button
                type="button"
                className="w-11 h-11 sm:w-11.5 sm:h-11.5 rounded-xl border border-gray-200/90 bg-white hover:bg-gray-50 flex items-center justify-center text-gray-700 transition-colors shadow-2xs cursor-pointer shrink-0"
                title="Menu Lainnya"
              >
                <svg className="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                </svg>
              </button>
            </div>
          </div>

          {/* Mobile Only (< 768px): Responsive layout matching user mobile preference */}
          <div className="flex flex-col md:hidden w-full">
            {/* Top row: Avatar + Name on left, 3-dots button on right */}
            <div className="flex items-start justify-between w-full gap-2 sm:gap-4">
              <div className="flex items-end gap-3 sm:gap-6 min-w-0">
                <div className="-mt-12 sm:-mt-16 shrink-0 z-20">
                  <img
                    src="/assets/avatar-rachel-exact.png"
                    alt="Rachel Vennya"
                    className="w-24 h-24 sm:w-28 sm:h-28 md:w-32 md:h-32 rounded-full object-cover ring-4 sm:ring-[5px] ring-white shadow-lg bg-white"
                  />
                </div>
                <div className="flex flex-col pt-5 sm:pt-7 md:pt-8 pb-1 min-w-0">
                  <div className="flex items-center gap-1.5 sm:gap-2">
                    <h1 className="text-[19px] sm:text-[25px] font-black text-[#111827] tracking-tight leading-tight whitespace-nowrap">
                      Rachel Vennya
                    </h1>
                    <VerifiedBadge size="md" className="w-4.5 h-4.5 sm:w-5 sm:h-5 shrink-0" />
                  </div>
                  <p className="text-[13px] sm:text-[14px] text-gray-500 font-medium mt-0.5">
                    Selebgram
                  </p>
                </div>
              </div>
              {/* 3-dots button aligned with name */}
              <button
                type="button"
                className="w-10 h-10 sm:w-11 sm:h-11 rounded-xl border border-gray-200/90 bg-white hover:bg-gray-50 items-center justify-center text-gray-700 transition-colors shadow-2xs cursor-pointer shrink-0 mt-5 sm:mt-7 md:mt-8 flex"
                title="Menu Lainnya"
              >
                <svg className="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                </svg>
              </button>
            </div>

            {/* Below photo: Bio Quote */}
            <p className="text-[14px] sm:text-[15px] text-gray-700 font-normal mt-3 mb-2.5">
              &ldquo;Let good things find a new home ♡&rdquo;
            </p>
            {/* Tablet Stats Row (sm to lg: >= 640px and < 1024px) */}
            <div className="hidden sm:flex flex-wrap items-center gap-x-3.5 gap-y-2 text-[13px] sm:text-[14px] text-gray-600 font-medium mb-3">
              <div className="inline-flex items-center gap-1.5 shrink-0">
                <svg className="w-[18px] h-[18px] text-amber-400 fill-current shrink-0" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span className="font-extrabold text-gray-900 text-[14.5px] sm:text-[15px]">4.9</span>
                <span className="text-gray-400 font-normal whitespace-nowrap text-[12.5px] sm:text-[13px]">(1.2rb ulasan)</span>
              </div>

              <span className="text-gray-200 font-light">|</span>

              <div className="inline-flex items-center gap-1.5 shrink-0">
                <svg className="w-[17px] h-[17px] text-gray-400 shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span className="font-semibold text-gray-800 whitespace-nowrap text-[13px] sm:text-[14px]">112 Barang</span>
              </div>

              <span className="text-gray-200 font-light">|</span>

              <div className="inline-flex items-center gap-1.5 shrink-0">
                <svg className="w-[17px] h-[17px] text-gray-400 shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span className="font-semibold text-gray-800 whitespace-nowrap text-[13px] sm:text-[14px]">12.4rb Pengikut</span>
              </div>

              <span className="text-gray-200 font-light">|</span>

              <div className="inline-flex items-center gap-1.5 shrink-0">
                <svg className="w-[17px] h-[17px] text-gray-400 shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                  <line x1="16" y1="2" x2="16" y2="6" />
                  <line x1="8" y1="2" x2="8" y2="6" />
                  <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
                <span className="text-gray-500 font-normal whitespace-nowrap text-[12.5px] sm:text-[13px]">Bergabung sejak Mar 2024</span>
              </div>
            </div>

            {/* Mobile-Only Stats Row strictly for < 640px: with outer left and right separators */}
            <div className="flex sm:hidden items-center justify-between w-full pt-3 pb-3 mb-1 px-1 border-x border-gray-200/80">
              {/* Rating */}
              <div className="flex flex-col items-center text-center flex-1">
                <svg className="w-5 h-5 text-amber-400 fill-current mb-1" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span className="text-[19px] font-black text-[#111827] leading-none">4.9</span>
                <span className="text-[12px] text-gray-400 font-normal mt-1.5 whitespace-nowrap">(1.2rb ulasan)</span>
              </div>

              <div className="h-8 w-[1px] bg-gray-200/80 shrink-0" />

              {/* Barang */}
              <div className="flex flex-col items-center text-center flex-1">
                <svg className="w-5 h-5 text-[#4F26A6] stroke-current fill-none mb-1" strokeWidth="2" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span className="text-[19px] font-black text-[#111827] leading-none">112</span>
                <span className="text-[12px] text-gray-400 font-normal mt-1.5 whitespace-nowrap">Barang</span>
              </div>

              <div className="h-8 w-[1px] bg-gray-200/80 shrink-0" />

              {/* Pengikut */}
              <div className="flex flex-col items-center text-center flex-1">
                <svg className="w-5 h-5 text-[#4F26A6] stroke-current fill-none mb-1" strokeWidth="2" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span className="text-[19px] font-black text-[#111827] leading-none">12.4rb</span>
                <span className="text-[12px] text-gray-400 font-normal mt-1.5 whitespace-nowrap">Pengikut</span>
              </div>
            </div>
            {/* + Ikuti Toko Button */}
            <div className="w-full pt-1">
              <button
                type="button"
                onClick={() => setIsFollowing(!isFollowing)}
                className={`w-full sm:w-auto px-7 h-11 sm:h-11.5 rounded-xl text-xs sm:text-[14px] font-bold flex items-center justify-center gap-2 transition-all cursor-pointer shadow-xs ${
                  isFollowing
                    ? 'bg-gray-100 text-gray-800 hover:bg-gray-200'
                    : 'bg-[#4F26A6] text-white hover:bg-[#3E1D85] shadow-[0_4px_16px_rgba(79,38,166,0.22)]'
                }`}
              >
                <svg className="w-4 h-4" fill="none" stroke="currentColor" strokeWidth="2.5" viewBox="0 0 24 24">
                  {isFollowing ? (
                    <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7" />
                  ) : (
                    <path strokeLinecap="round" strokeLinejoin="round" d="M12 4v16m8-8H4" />
                  )}
                </svg>
                <span>{isFollowing ? 'Mengikuti' : 'Ikuti Toko'}</span>
              </button>
            </div>
          </div>
          {/* 5. Tabs Navigation: on mobile/tablet justified evenly across width; on desktop left-aligned with pl-4 */}
          <div className="flex items-center justify-around sm:justify-around lg:justify-start gap-2 sm:gap-6 lg:gap-14 text-[15px] sm:text-[16px] md:text-[17px] font-bold mt-8 px-2 sm:px-4 lg:px-0 lg:pl-4 w-full">
            <div className="relative flex flex-col items-center flex-1 lg:flex-initial">
              <button
                onClick={() => setActiveTab('produk')}
                className={`pb-3 transition-colors cursor-pointer px-3 text-center w-full lg:w-auto ${
                  activeTab === 'produk'
                    ? 'text-[#4F26A6]'
                    : 'text-gray-500 hover:text-gray-900'
                }`}
              >
                Produk
              </button>
              {activeTab === 'produk' && (
                <div className="absolute -bottom-1 left-1/2 -translate-x-1/2 w-[90px] sm:w-[105px] lg:w-[115px] h-[3.5px] bg-[#4F26A6] rounded-full" />
              )}
            </div>

            <div className="relative flex flex-col items-center flex-1 lg:flex-initial">
              <button
                onClick={() => setActiveTab('ulasan')}
                className={`pb-3 transition-colors cursor-pointer px-3 text-center w-full lg:w-auto ${
                  activeTab === 'ulasan'
                    ? 'text-[#4F26A6]'
                    : 'text-gray-500 hover:text-gray-900'
                }`}
              >
                Ulasan
              </button>
              {activeTab === 'ulasan' && (
                <div className="absolute -bottom-1 left-1/2 -translate-x-1/2 w-[90px] sm:w-[105px] lg:w-[115px] h-[3.5px] bg-[#4F26A6] rounded-full" />
              )}
            </div>
          </div>
        </div>
        {activeTab === 'produk' && (
          <div className="mt-8 flex flex-col lg:flex-row items-start gap-6 xl:gap-8">
            {/* Left Column: Tentang Rachel Vennya Card matching exact mockup */}
            <div className="w-full lg:w-[320px] xl:w-[340px] shrink-0">
              <div className="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
                {/* Card Title */}
                <h3 className="text-[17px] font-bold text-[#111827] mb-3 tracking-tight">
                  Tentang Rachel Vennya
                </h3>

                {/* Bio Description */}
                <div className="text-[13px] sm:text-[13.5px] text-gray-600 leading-relaxed space-y-2">
                  <p>
                    Di sini aku jual barang pre-loved pribadi yang masih bagus dan layak pakai. Semoga bisa menemukan pemilik baru yang lebih cinta lagi ♡
                  </p>
                  {isBioExpanded && (
                    <p className="text-gray-500 pt-1 text-xs sm:text-[12.5px] leading-relaxed border-t border-gray-100 mt-2">
                      Semua koleksi dijamin original 100%, dirawat dengan baik dari lemari pribadi, dan dikemas secara higienis sebelum dikirimkan ke kamu.
                    </p>
                  )}
                </div>

                {/* Toggle Read More */}
                <button
                  type="button"
                  onClick={() => setIsBioExpanded(!isBioExpanded)}
                  className="mt-3.5 inline-flex items-center gap-1.5 text-xs sm:text-[13px] font-bold text-[#4F26A6] hover:text-[#3E1D85] transition-colors cursor-pointer"
                >
                  <span>{isBioExpanded ? 'Tutup Selengkapnya' : 'Baca Selengkapnya'}</span>
                  <svg
                    className={`w-3.5 h-3.5 transition-transform duration-200 ${isBioExpanded ? 'rotate-180' : ''}`}
                    fill="none"
                    stroke="currentColor"
                    strokeWidth="2.5"
                    viewBox="0 0 24 24"
                  >
                    <path strokeLinecap="round" strokeLinejoin="round" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>

                <div className="h-[1px] bg-gray-100 my-5" />

                {/* 3 Value Proposition Feature Rows with comfortable breathing room */}
                <div className="space-y-2">
                  {/* 1. Akun Terverifikasi */}
                  <div className="flex items-center gap-4 py-2">
                    <div className="w-9 h-9 flex items-center justify-center text-[#5022CE] shrink-0">
                      <svg className="w-7 h-7 fill-current" viewBox="0 0 24 24">
                        <path d="M12 2L4 5.5v5.8c0 5.25 3.41 10.15 8 11.35 4.59-1.2 8-6.1 8-11.35V5.5L12 2zm-1.2 14.2l-3.5-3.5 1.41-1.41 2.09 2.08 5.09-5.09 1.41 1.41-6.5 6.51z" />
                      </svg>
                    </div>
                    <div className="flex flex-col">
                      <h4 className="text-[14px] font-bold text-gray-900 leading-snug">
                        Akun Terverifikasi
                      </h4>
                      <p className="text-[12.5px] text-gray-500 mt-1 leading-snug">
                        Sudah diverifikasi oleh Whimarket
                      </p>
                    </div>
                  </div>

                  <div className="h-[1px] bg-gray-100/90 my-1.5" />

                  {/* 2. Respon Cepat */}
                  <div className="flex items-center gap-4 py-2">
                    <div className="w-9 h-9 flex items-center justify-center text-[#5022CE] shrink-0">
                      <svg className="w-6 h-6" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                      </svg>
                    </div>
                    <div className="flex flex-col">
                      <h4 className="text-[14px] font-bold text-gray-900 leading-snug">
                        Respon Cepat
                      </h4>
                      <p className="text-[12.5px] text-gray-500 mt-1 leading-snug">
                        Rata-rata membalas &lt; 1 jam
                      </p>
                    </div>
                  </div>

                  <div className="h-[1px] bg-gray-100/90 my-1.5" />

                  {/* 3. Pengiriman Aman */}
                  <div className="flex items-center gap-4 py-2">
                    <div className="w-9 h-9 flex items-center justify-center text-[#5022CE] shrink-0">
                      <svg className="w-6 h-6" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                        <rect x="3" y="6" width="18" height="15" rx="3" />
                        <path strokeLinecap="round" strokeLinejoin="round" d="M9 6V4a2 2 0 012-2h2a2 2 0 012 2v2" />
                        <path strokeLinecap="round" strokeLinejoin="round" d="M12 12v3" />
                        <circle cx="12" cy="13.5" r="1" fill="currentColor" />
                      </svg>
                    </div>
                    <div className="flex flex-col">
                      <h4 className="text-[14px] font-bold text-gray-900 leading-snug">
                        Pengiriman Aman
                      </h4>
                      <p className="text-[12.5px] text-gray-500 mt-1 leading-snug">
                        Setiap pesanan diproses dengan aman
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {/* Right Column: Catalog (Category Pills, Sorting, Product Grid) */}
            <div className="flex-1 w-full min-w-0">
              {/* Filter & Sort Bar */}
              <div className="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
                {/* Category Pills */}
                <div className="flex items-center gap-2 sm:gap-2.5 overflow-x-auto w-full sm:w-auto pb-1.5 sm:pb-0 scrollbar-none">
                  {[
                    { id: 'all', label: 'Semua' },
                    { id: 'fashion', label: 'Fashion' },
                    { id: 'tas', label: 'Tas & Aksesoris' },
                    { id: 'kecantikan', label: 'Kecantikan' },
                    { id: 'merch', label: 'Merchandise' },
                  ].map((cat) => (
                    <button
                      key={cat.id}
                      onClick={() => setSelectedCategory(cat.id)}
                      className={`px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl sm:rounded-2xl text-[13px] sm:text-[14px] font-bold whitespace-nowrap transition-all cursor-pointer ${
                        selectedCategory === cat.id
                          ? 'bg-[#4F26A6] text-white shadow-sm'
                          : 'bg-white text-gray-700 hover:bg-gray-100 hover:text-gray-900 border border-gray-200/90'
                      }`}
                    >
                      {cat.label}
                    </button>
                  ))}
                </div>

                {/* Product Count & Sort */}
                <div className="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-4">
                  <span className="text-xs sm:text-[13px] text-gray-500 font-medium">
                    Menampilkan <strong className="text-gray-900">{filteredProducts.length}</strong> produk
                  </span>

                  <select
                    value={sortBy}
                    onChange={(e) => setSortBy(e.target.value as any)}
                    aria-label="Urutan Produk"
                    className="bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs sm:text-[13px] font-semibold text-gray-700 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 cursor-pointer"
                  >
                    <option value="terbaru">Terbaru</option>
                    <option value="harga-terendah">Harga Terendah</option>
                    <option value="harga-tertinggi">Harga Tertinggi</option>
                  </select>
                </div>
              </div>

              {/* Product Cards Grid: 2 cols on mobile, 3 cols on tablet, 3-4 cols on desktop */}
              <div className="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 lg:gap-5">
                {filteredProducts.map((product) => (
                  <ProductCard
                    key={product.id}
                    product={product}
                    onLikeToggle={handleLikeToggle}
                  />
                ))}
              </div>
            </div>
          </div>
        )}
        {/* Tab Ulasan: Exact 1:1 Redesign from Mockup */}
        {activeTab === 'ulasan' && (
          <div className="mt-8 flex flex-col lg:flex-row items-start gap-8 w-full">
            {/* Left Column: Overall Rating Card & Verified Notice Box */}
            <div className="w-full lg:w-[310px] xl:w-[330px] shrink-0 space-y-4">
              {/* Card 1: Rating Keseluruhan Card */}
              <div className="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
                <h3 className="text-[17px] font-bold text-[#111827] tracking-tight mb-4">
                  Rating Keseluruhan
                </h3>

                {/* Score & Stars */}
                <div className="flex items-center gap-3 mb-1">
                  <span className="text-[44px] font-black text-[#111827] leading-none tracking-tight">
                    4.9
                  </span>
                  <div className="flex items-center gap-1 text-amber-400">
                    {[...Array(5)].map((_, i) => (
                      <svg key={i} className="w-5 h-5 fill-current" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    ))}
                  </div>
                </div>

                <p className="text-xs text-gray-500 font-medium mb-6">
                  dari 1.278 ulasan
                </p>

                {/* Breakdown Bars: 5, 4, 3, 2, 1 */}
                <div className="space-y-2.5 mb-6">
                  {[
                    { star: 5, count: '1.086', pct: 85 },
                    { star: 4, count: '142', pct: 15 },
                    { star: 3, count: '38', pct: 4 },
                    { star: 2, count: '8', pct: 1.5 },
                    { star: 1, count: '4', pct: 1 },
                  ].map((row) => (
                    <div key={row.star} className="flex items-center gap-3 text-gray-700 font-medium">
                      <span className="w-3.5 text-sm font-extrabold text-gray-900">{row.star}</span>
                      <svg className="w-4 h-4 text-amber-400 fill-current shrink-0" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      {/* Bar Track */}
                      <div className="flex-1 h-2.5 bg-purple-50 rounded-full overflow-hidden">
                        <div
                          className="h-full bg-[#5022CE] rounded-full transition-all duration-300"
                          style={{ width: `${row.pct}%` }}
                        />
                      </div>
                      <span className="w-11 text-right text-gray-500 text-[12.5px] font-bold">{row.count}</span>
                    </div>
                  ))}
                </div>


                {/* Ulasan Asli Verifikasi Box: Nested INSIDE the card below button matching exact mockup */}
                <div className="bg-[#F6F4F9] rounded-2xl p-4 flex items-center gap-3.5">
                  <div className="w-9 h-9 rounded-2xl bg-[#E8E2F4] flex items-center justify-center text-[#5022CE] shrink-0">
                    <svg className="w-5 h-5 fill-none stroke-current" strokeWidth="2.2" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                  </div>
                  <div className="text-[12.5px] text-gray-900 font-semibold leading-snug">
                    <p>Ulasan asli dari pembeli</p>
                    <p>terverifikasi di Whimarket.</p>
                  </div>
                </div>
              </div>
            </div>

            {/* Right Column: Semua Ulasan Header, Star Filter Pills, and Review Item Cards */}
            <div className="flex-1 w-full min-w-0 bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
              {/* Header row: Semua Ulasan title and Urutkan dropdown */}
              <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                <div>
                  <h2 className="text-[20px] sm:text-[22px] font-extrabold text-[#111827] tracking-tight">
                    Semua Ulasan
                  </h2>
                  <p className="text-xs sm:text-[13px] text-gray-500 font-normal mt-0.5">
                    Lihat pengalaman pembeli lain berbelanja di toko Rachel Vennya.
                  </p>
                </div>

                {/* Urutkan Dropdown */}
                <div className="flex items-center gap-2 self-start sm:self-auto">
                  <span className="text-xs text-gray-400 font-medium">Urutkan:</span>
                  <select
                    value={reviewSort}
                    onChange={(e) => setReviewSort(e.target.value)}
                    aria-label="Urutan Ulasan"
                    className="bg-white border border-gray-200/90 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] font-bold text-gray-800 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 cursor-pointer shadow-2xs"
                  >
                    <option value="terbaru">Terbaru</option>
                    <option value="tertinggi">Rating Tertinggi</option>
                    <option value="terendah">Rating Terendah</option>
                  </select>
                </div>
              </div>

              {/* Star Filter Pills Row: Semua (1.278), 5 star (1.086), 4 star (142), 3 star (38), 2 star (8), 1 star (4) */}
              <div className="flex items-center gap-2 sm:gap-2.5 overflow-x-auto pb-4 mb-6 border-b border-gray-100 scrollbar-none">
                {[
                  { id: 'all', label: 'Semua (1.278)' },
                  { id: '5', label: '5', count: '1.086', star: true },
                  { id: '4', label: '4', count: '142', star: true },
                  { id: '3', label: '3', count: '38', star: true },
                  { id: '2', label: '2', count: '8', star: true },
                  { id: '1', label: '1', count: '4', star: true },
                ].map((pill) => (
                  <button
                    key={pill.id}
                    onClick={() => setSelectedReviewFilter(pill.id)}
                    className={`px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-xl text-xs sm:text-[12.5px] font-bold whitespace-nowrap transition-all cursor-pointer flex items-center gap-1.5 shrink-0 ${
                      selectedReviewFilter === pill.id
                        ? 'border-2 border-[#5022CE] text-[#5022CE] bg-purple-50/50'
                        : 'border border-gray-200/80 text-gray-700 bg-white hover:bg-gray-50'
                    }`}
                  >
                    <span>{pill.label}</span>
                    {pill.star && (
                      <svg className="w-3.5 h-3.5 text-amber-400 fill-current shrink-0" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    )}
                    {pill.count && <span className="text-gray-400 font-normal">({pill.count})</span>}
                  </button>
                ))}
              </div>

              {/* Review Item List */}
              <div className="space-y-6 divide-y divide-gray-100">
                {/* Review Item 1: Anya Geraldine */}
                <div className="pt-6 first:pt-0">
                  <div className="flex flex-col md:flex-row md:items-stretch justify-between gap-6">
                    {/* Left: Reviewer details, rating stars, comment, photo thumbnails with vertical divider */}
                    <div className="flex-1 min-w-0 md:pr-6 md:border-r md:border-gray-200/80">
                      {/* Reviewer Name, avatar, verified purchase pill */}
                      <div className="flex items-center gap-3 mb-2">
                        <img
                          src="/assets/avatar-anya.png"
                          alt="Anya Geraldine"
                          className="w-10 h-10 rounded-full object-cover ring-1 ring-gray-100 shrink-0"
                        />
                        <div>
                          <div className="flex items-center gap-2">
                            <h4 className="text-[14.5px] font-bold text-gray-900">Anya Geraldine</h4>
                            <span className="text-xs text-gray-400 font-normal">3 hari lalu</span>
                          </div>
                          {/* Stars */}
                          <div className="flex items-center gap-1 text-amber-400 mt-1">
                            {[...Array(5)].map((_, i) => (
                              <svg key={i} className="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                              </svg>
                            ))}
                          </div>
                        </div>
                      </div>

                      {/* Comment */}
                      <p className="text-xs sm:text-[13.5px] text-gray-700 leading-relaxed mb-3">
                        Barangnya masih super bagus, sesuai deskripsi! Packing rapi banget dan pengiriman cepat. Makasih ka Rachel ♡
                      </p>

                      {/* 3 Real Product Photo Thumbnails from mockup */}
                      <div className="flex items-center gap-2.5">
                        <img
                          src="/assets/review-chanel-1.png"
                          alt="Review Chanel 1"
                          className="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover border border-gray-100 hover:scale-105 transition-transform cursor-pointer shadow-2xs"
                        />
                        <img
                          src="/assets/review-chanel-2.png"
                          alt="Review Chanel 2"
                          className="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover border border-gray-100 hover:scale-105 transition-transform cursor-pointer shadow-2xs"
                        />
                        <img
                          src="/assets/review-chanel-3.png"
                          alt="Review Chanel 3"
                          className="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover border border-gray-100 hover:scale-105 transition-transform cursor-pointer shadow-2xs"
                        />
                      </div>
                    </div>
                    {/* Right: Product Preview with fixed width to eliminate zigzag and align perfectly */}
                    <div className="flex items-center gap-3.5 sm:gap-4 shrink-0 pt-3 md:pt-0 w-full md:w-[280px]">
                      <div className="w-[84px] h-[84px] sm:w-[90px] sm:h-[90px] rounded-2xl bg-[#ECE8F1] shrink-0 overflow-hidden">
                        <img
                          src="/assets/products/prod-bag.png"
                          alt="Tas Charles & Keith Black"
                          className="w-full h-full object-cover"
                        />
                      </div>
                      <div className="flex flex-col justify-center min-w-0">
                        <span className="text-[12.5px] sm:text-[13px] font-bold text-gray-900 leading-tight truncate">Tas Charles &amp; Keith Black</span>
                        <span className="text-[13.5px] sm:text-[14px] font-extrabold text-[#5022CE] mt-0.5 leading-tight">Rp 850.000</span>
                        <a href="#produk" className="text-[12px] sm:text-[13px] text-[#5022CE] hover:text-[#3E1D85] font-bold mt-2 sm:mt-2.5 inline-flex items-center gap-1.5 transition-colors group">
                          <span>Lihat Produk</span>
                          <span className="group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
                {/* Review Item 2: Fuji An */}
                <div className="pt-6">
                  <div className="flex flex-col md:flex-row md:items-stretch justify-between gap-6">
                    <div className="flex-1 min-w-0 md:pr-6 md:border-r md:border-gray-200/80">
                      <div className="flex items-center gap-3 mb-2">
                        <img
                          src="/assets/avatars/avatar-fuji.png"
                          alt="Fuji An"
                          className="w-10 h-10 rounded-full object-cover ring-1 ring-gray-100 shrink-0"
                        />
                        <div>
                          <div className="flex items-center gap-2">
                            <h4 className="text-[14.5px] font-bold text-gray-900">Fuji An</h4>
                            <span className="text-xs text-gray-400 font-normal">1 minggu lalu</span>
                          </div>
                          <div className="flex items-center gap-1 text-amber-400 mt-1">
                            {[...Array(5)].map((_, i) => (
                              <svg key={i} className="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                              </svg>
                            ))}
                          </div>
                        </div>
                      </div>

                      <p className="text-xs sm:text-[13.5px] text-gray-700 leading-relaxed mb-3">
                        Hoodie-nya masih like new! Bahannya tebal dan nyaman banget dipakai. Suka banget, makasih! ♡
                      </p>

                      <div className="flex items-center gap-2.5">
                        <img
                          src="/assets/review-hoodie-1.png"
                          alt="Review Hoodie 1"
                          className="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover border border-gray-100 hover:scale-105 transition-transform cursor-pointer shadow-2xs"
                        />
                        <img
                          src="/assets/review-hoodie-2.png"
                          alt="Review Hoodie 2"
                          className="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover border border-gray-100 hover:scale-105 transition-transform cursor-pointer shadow-2xs"
                        />
                        <img
                          src="/assets/review-hoodie-3.png"
                          alt="Review Hoodie 3"
                          className="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover border border-gray-100 hover:scale-105 transition-transform cursor-pointer shadow-2xs"
                        />
                      </div>
                    </div>
                    <div className="flex items-center gap-3.5 sm:gap-4 shrink-0 pt-3 md:pt-0 w-full md:w-[280px]">
                      <div className="w-[84px] h-[84px] sm:w-[90px] sm:h-[90px] rounded-2xl bg-[#ECE8F1] shrink-0 overflow-hidden">
                        <img
                          src="/assets/products/prod-hoodie.png"
                          alt="Hoodie Plan Do"
                          className="w-full h-full object-cover"
                        />
                      </div>
                      <div className="flex flex-col justify-center min-w-0">
                        <span className="text-[12.5px] sm:text-[13px] font-bold text-gray-900 leading-tight truncate">Hoodie Plan Do</span>
                        <span className="text-[13.5px] sm:text-[14px] font-extrabold text-[#5022CE] mt-0.5 leading-tight">Rp 500.000</span>
                        <a href="#produk" className="text-[12px] sm:text-[13px] text-[#5022CE] hover:text-[#3E1D85] font-bold mt-2 sm:mt-2.5 inline-flex items-center gap-1.5 transition-colors group">
                          <span>Lihat Produk</span>
                          <span className="group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
                {/* Review Item 3: Raisy Febian */}
                <div className="pt-6">
                  <div className="flex flex-col md:flex-row md:items-stretch justify-between gap-6">
                    <div className="flex-1 min-w-0 md:pr-6 md:border-r md:border-gray-200/80">
                      <div className="flex items-center gap-3 mb-2">
                        <img
                          src="/assets/avatars/avatar-raisy.png"
                          alt="Raisy Febian"
                          className="w-10 h-10 rounded-full object-cover ring-1 ring-gray-100 shrink-0"
                        />
                        <div>
                          <div className="flex items-center gap-2">
                            <h4 className="text-[14.5px] font-bold text-gray-900">Raisy Febian</h4>
                            <span className="text-xs text-gray-400 font-normal">2 minggu lalu</span>
                          </div>
                          <div className="flex items-center gap-1 text-amber-400 mt-1">
                            {[...Array(5)].map((_, i) => (
                              <svg key={i} className="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                              </svg>
                            ))}
                          </div>
                        </div>
                      </div>

                      <p className="text-xs sm:text-[13.5px] text-gray-700 leading-relaxed mb-3">
                        Kondisi kartu masih sangat baik, original. Pengemasan juga aman banget. Recommended seller! 🙌
                      </p>

                      <div className="flex items-center gap-2.5">
                        <img
                          src="/assets/review-pokemon-1.png"
                          alt="Review Pokemon 1"
                          className="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover border border-gray-100 hover:scale-105 transition-transform cursor-pointer shadow-2xs"
                        />
                        <img
                          src="/assets/review-pokemon-2.png"
                          alt="Review Pokemon 2"
                          className="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover border border-gray-100 hover:scale-105 transition-transform cursor-pointer shadow-2xs"
                        />
                        <img
                          src="/assets/review-pokemon-3.png"
                          alt="Review Pokemon 3"
                          className="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover border border-gray-100 hover:scale-105 transition-transform cursor-pointer shadow-2xs"
                        />
                      </div>
                    </div>
                    <div className="flex items-center gap-3.5 sm:gap-4 shrink-0 pt-3 md:pt-0 w-full md:w-[280px]">
                      <div className="w-[84px] h-[84px] sm:w-[90px] sm:h-[90px] rounded-2xl bg-[#ECE8F1] shrink-0 overflow-hidden">
                        <img
                          src="/assets/products/prod-pokemon.png"
                          alt="Kartu Pokemon Rare"
                          className="w-full h-full object-cover"
                        />
                      </div>
                      <div className="flex flex-col justify-center min-w-0">
                        <span className="text-[12.5px] sm:text-[13px] font-bold text-gray-900 leading-tight truncate">Kartu Pokemon Rare</span>
                        <span className="text-[13.5px] sm:text-[14px] font-extrabold text-[#5022CE] mt-0.5 leading-tight">Rp 1.500.000</span>
                        <a href="#produk" className="text-[12px] sm:text-[13px] text-[#5022CE] hover:text-[#3E1D85] font-bold mt-2 sm:mt-2.5 inline-flex items-center gap-1.5 transition-colors group">
                          <span>Lihat Produk</span>
                          <span className="group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        )}
      </main>

      {/* 5. Global Footer */}
      <Footer />
    </div>
  );
};
