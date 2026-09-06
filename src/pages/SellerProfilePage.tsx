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
  const [activeTab, setActiveTab] = useState<'produk' | 'tentang' | 'ulasan'>('produk');
  const [isFollowing, setIsFollowing] = useState(false);
  const [isShareCopied, setIsShareCopied] = useState(false);
  const [isBioExpanded, setIsBioExpanded] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedCategory, setSelectedCategory] = useState('all');
  const [sortBy, setSortBy] = useState<'terbaru' | 'harga-terendah' | 'harga-tertinggi'>('terbaru');

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
          {/* Desktop (lg and above, >=1024px): 100% exact to mockup (Avatar on left, Info next to avatar, Buttons on right) */}
          <div className="hidden lg:flex items-start justify-between gap-6">
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
                      <path strokeLinecap="round" strokeLinejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
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

          {/* Tablet & Mobile (< 1024px): Responsive layout matching user preference */}
          <div className="flex flex-col lg:hidden w-full">
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
                  <path strokeLinecap="round" strokeLinejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
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
                onClick={() => setActiveTab('tentang')}
                className={`pb-3 transition-colors cursor-pointer px-3 text-center w-full lg:w-auto ${
                  activeTab === 'tentang'
                    ? 'text-[#4F26A6]'
                    : 'text-gray-500 hover:text-gray-900'
                }`}
              >
                Tentang
              </button>
              {activeTab === 'tentang' && (
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

                {/* 3 Value Proposition Feature Rows matching exact mockup */}
                <div className="space-y-4">
                  {/* 1. Akun Terverifikasi */}
                  <div className="flex items-center gap-3.5">
                    <div className="w-8 h-8 flex items-center justify-center text-[#5022CE] shrink-0">
                      <svg className="w-7 h-7 fill-current" viewBox="0 0 24 24">
                        <path d="M12 2L4 5.5v5.8c0 5.25 3.41 10.15 8 11.35 4.59-1.2 8-6.1 8-11.35V5.5L12 2zm-1.2 14.2l-3.5-3.5 1.41-1.41 2.09 2.08 5.09-5.09 1.41 1.41-6.5 6.51z" />
                      </svg>
                    </div>
                    <div className="flex flex-col">
                      <h4 className="text-[13.5px] font-bold text-gray-900 leading-tight">
                        Akun Terverifikasi
                      </h4>
                      <p className="text-[12px] text-gray-500 mt-0.5 leading-tight">
                        Sudah diverifikasi oleh Whimarket
                      </p>
                    </div>
                  </div>

                  <div className="h-[1px] bg-gray-100 my-1" />

                  {/* 2. Respon Cepat */}
                  <div className="flex items-center gap-3.5">
                    <div className="w-8 h-8 flex items-center justify-center text-[#5022CE] shrink-0">
                      <svg className="w-6 h-6" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                      </svg>
                    </div>
                    <div className="flex flex-col">
                      <h4 className="text-[13.5px] font-bold text-gray-900 leading-tight">
                        Respon Cepat
                      </h4>
                      <p className="text-[12px] text-gray-500 mt-0.5 leading-tight">
                        Rata-rata membalas &lt; 1 jam
                      </p>
                    </div>
                  </div>

                  <div className="h-[1px] bg-gray-100 my-1" />

                  {/* 3. Pengiriman Aman */}
                  <div className="flex items-center gap-3.5">
                    <div className="w-8 h-8 flex items-center justify-center text-[#5022CE] shrink-0">
                      <svg className="w-6 h-6" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                        <rect x="3" y="6" width="18" height="15" rx="3" />
                        <path strokeLinecap="round" strokeLinejoin="round" d="M9 6V4a2 2 0 012-2h2a2 2 0 012 2v2" />
                        <path strokeLinecap="round" strokeLinejoin="round" d="M12 12v3" />
                        <circle cx="12" cy="13.5" r="1" fill="currentColor" />
                      </svg>
                    </div>
                    <div className="flex flex-col">
                      <h4 className="text-[13.5px] font-bold text-gray-900 leading-tight">
                        Pengiriman Aman
                      </h4>
                      <p className="text-[12px] text-gray-500 mt-0.5 leading-tight">
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
        {/* Tab 2: Tentang Rachel Vennya matching exact mockup card */}
        {activeTab === 'tentang' && (
          <div className="mt-8 flex flex-col lg:flex-row items-start gap-8">
            {/* Left Card: 100% exact to user mockup */}
            <div className="w-full max-w-[390px] bg-white rounded-3xl p-6 sm:p-7 border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
              {/* Card Title */}
              <h3 className="text-[17px] sm:text-[18px] font-bold text-[#111827] mb-3 tracking-tight">
                Tentang Rachel Vennya
              </h3>

              {/* Bio Paragraph */}
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

              {/* Baca Selengkapnya Link */}
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

              {/* 3 Value Proposition Feature Rows */}
              <div className="space-y-4">
                {/* 1. Akun Terverifikasi */}
                <div className="flex items-center gap-3.5">
                  <div className="w-10 h-10 rounded-full bg-[#5022CE] text-white flex items-center justify-center shrink-0 shadow-sm">
                    <svg className="w-5 h-5 fill-current" viewBox="0 0 20 20">
                      <path fillRule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                    </svg>
                  </div>
                  <div className="flex flex-col">
                    <h4 className="text-[14px] font-bold text-gray-900 leading-tight">
                      Akun Terverifikasi
                    </h4>
                    <p className="text-[12px] text-gray-500 mt-0.5 leading-tight">
                      Sudah diverifikasi oleh Whimarket
                    </p>
                  </div>
                </div>

                <div className="h-[1px] bg-gray-50 my-1" />

                {/* 2. Respon Cepat */}
                <div className="flex items-center gap-3.5">
                  <div className="w-10 h-10 rounded-full bg-white border-2 border-[#5022CE] text-[#5022CE] flex items-center justify-center shrink-0">
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                  </div>
                  <div className="flex flex-col">
                    <h4 className="text-[14px] font-bold text-gray-900 leading-tight">
                      Respon Cepat
                    </h4>
                    <p className="text-[12px] text-gray-500 mt-0.5 leading-tight">
                      Rata-rata membalas &lt; 1 jam
                    </p>
                  </div>
                </div>

                <div className="h-[1px] bg-gray-50 my-1" />

                {/* 3. Pengiriman Aman */}
                <div className="flex items-center gap-3.5">
                  <div className="w-10 h-10 rounded-full bg-white border-2 border-[#5022CE] text-[#5022CE] flex items-center justify-center shrink-0">
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                  </div>
                  <div className="flex flex-col">
                    <h4 className="text-[14px] font-bold text-gray-900 leading-tight">
                      Pengiriman Aman
                    </h4>
                    <p className="text-[12px] text-gray-500 mt-0.5 leading-tight">
                      Setiap pesanan diproses dengan aman
                    </p>
                  </div>
                </div>
              </div>
            </div>

            {/* Right: Detail Informasi Toko */}
            <div className="flex-1 bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
              <h3 className="text-[18px] font-bold text-gray-900 mb-4">Informasi & Kebijakan Toko</h3>
              
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
                <div className="bg-[#FAF9FC] p-4 rounded-2xl border border-gray-100">
                  <p className="text-xs text-gray-400 font-semibold uppercase tracking-wider">Lokasi Pengiriman</p>
                  <p className="text-sm font-bold text-gray-900 mt-1">Jakarta Selatan, DKI Jakarta</p>
                </div>
                <div className="bg-[#FAF9FC] p-4 rounded-2xl border border-gray-100">
                  <p className="text-xs text-gray-400 font-semibold uppercase tracking-wider">Jasa Ekspedisi</p>
                  <p className="text-sm font-bold text-gray-900 mt-1">JNE, SiCepat, GoSend Instant</p>
                </div>
                <div className="bg-[#FAF9FC] p-4 rounded-2xl border border-gray-100">
                  <p className="text-xs text-gray-400 font-semibold uppercase tracking-wider">Jadwal Pengiriman</p>
                  <p className="text-sm font-bold text-gray-900 mt-1">Senin - Jumat (1-2 hari kerja)</p>
                </div>
                <div className="bg-[#FAF9FC] p-4 rounded-2xl border border-gray-100">
                  <p className="text-xs text-gray-400 font-semibold uppercase tracking-wider">Garansi Keaslian</p>
                  <p className="text-sm font-bold text-gray-900 mt-1">100% Uang Kembali jika Palsu</p>
                </div>
              </div>

              <h4 className="text-sm font-bold text-gray-900 mb-2">Catatan dari Seller</h4>
              <p className="text-xs sm:text-[13px] text-gray-600 leading-relaxed">
                Halo semuanya! Barang-barang di sini adalah barang pribadi yang aku beli langsung dari official store atau saat traveling. Kondisinya masih sangat terawat. Jika butuh detail foto lebih jelas atau video kondisi barang, bisa langsung chat ya. Terima kasih sudah mampir! ♡
              </p>
            </div>
          </div>
        )}

        {activeTab === 'ulasan' && (
          <div className="mt-8 bg-white rounded-2xl p-6 sm:p-8 border border-gray-100 shadow-sm max-w-4xl">
            <div className="flex items-center gap-4 mb-6">
              <div className="flex items-center gap-2">
                <svg className="w-8 h-8 text-amber-400 fill-current" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span className="text-3xl font-black text-gray-900">4.9</span>
              </div>
              <div>
                <p className="text-sm font-bold text-gray-900">Penilaian Toko</p>
                <p className="text-xs text-gray-500">Dari 1.248 pembeli terverifikasi</p>
              </div>
            </div>

            <div className="space-y-4 divide-y divide-gray-100">
              {[
                {
                  name: 'Nadia P.',
                  date: '2 hari lalu',
                  rating: 5,
                  comment: 'Barangnya bener-bener mulus kayak baru! Packaging rapi banget dan ada kartu ucapan terima kasihnya. Super recommended!',
                  product: 'Nike Dunk Low Purple',
                },
                {
                  name: 'Fikri A.',
                  date: '1 minggu lalu',
                  rating: 5,
                  comment: 'Tasnya original 100%, ada receipt dan dustbag lengkap. Pengiriman dari Rachel juga cepet banget.',
                  product: 'Tas Michael Kors Original Brown',
                },
                {
                  name: 'Siti Rahma',
                  date: '2 minggu lalu',
                  rating: 5,
                  comment: 'Bagus banget jaketnya, wangi lagi pas nyampe. Seneng banget bisa punya preloved dari Kak Rachel ♡',
                  product: 'Varsity Jacket Whimarket Exclusive',
                },
              ].map((rev, idx) => (
                <div key={idx} className="pt-4 first:pt-0">
                  <div className="flex items-center justify-between mb-1.5">
                    <span className="text-sm font-bold text-gray-900">{rev.name}</span>
                    <span className="text-xs text-gray-400">{rev.date}</span>
                  </div>
                  <div className="flex items-center gap-1 mb-2">
                    {[...Array(rev.rating)].map((_, i) => (
                      <svg key={i} className="w-3.5 h-3.5 text-amber-400 fill-current" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    ))}
                  </div>
                  <p className="text-xs sm:text-[13px] text-gray-700 mb-1.5 leading-relaxed">{rev.comment}</p>
                  <p className="text-[11px] text-[#4F26A6] font-semibold">Produk: {rev.product}</p>
                </div>
              ))}
            </div>
          </div>
        )}
      </main>

      {/* 5. Global Footer */}
      <Footer />
    </div>
  );
};
