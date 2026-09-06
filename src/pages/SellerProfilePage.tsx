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
        {/* 4. Profile Row (Avatar + Details + Actions) */}
        <div className="relative pt-0 mb-6">
          <div className="flex flex-col md:flex-row items-start justify-between gap-4">
            {/* Left: Avatar overlapping banner bottom + Info */}
            <div className="flex flex-col sm:flex-row items-start sm:items-start gap-4 sm:gap-6 pl-1 sm:pl-4 w-full sm:w-auto">
              {/* Circular Avatar: native CSS rounded-full + ring-4 ring-white + shadow-lg */}
              <div className="-mt-14 sm:-mt-16 md:-mt-18 shrink-0 z-20">
                <img
                  src="/assets/avatar-rachel-exact.png"
                  alt="Rachel Vennya"
                  className="w-28 h-28 sm:w-32 sm:h-32 md:w-36 md:h-36 lg:w-40 lg:h-40 rounded-full object-cover ring-4 sm:ring-[5px] ring-white shadow-lg bg-white"
                />
              </div>

              {/* Seller Info */}
              <div className="flex flex-col pt-1 sm:pt-2.5">
                {/* Name + Verified Rosette */}
                <div className="flex items-center gap-2 mb-1">
                  <h1 className="text-[22px] sm:text-[25px] md:text-[28px] font-black text-[#111827] tracking-tight leading-tight">
                    Rachel Vennya
                  </h1>
                  <VerifiedBadge size="md" className="w-5 h-5 sm:w-5.5 sm:h-5.5" />
                </div>

                {/* Subtitle / Role */}
                <p className="text-[13.5px] sm:text-[14.5px] text-gray-500 font-medium mb-1.5">
                  Selebgram
                </p>

                {/* Bio quote */}
                <p className="text-[13.5px] sm:text-[14.5px] text-gray-700 font-normal mb-3">
                  &ldquo;Let good things find a new home ♡&rdquo;
                </p>

                {/* Stats Row */}
                <div className="flex flex-wrap items-center gap-3 sm:gap-4 text-[13px] sm:text-[14px] text-gray-600 font-medium">
                  {/* Rating */}
                  <div className="flex items-center gap-1.5">
                    <svg className="w-4 h-4 text-amber-400 fill-current" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <span className="font-extrabold text-gray-900 text-[14px] sm:text-[15px]">4.9</span>
                    <span className="text-gray-400 font-normal">(1.2rb ulasan)</span>
                  </div>

                  <span className="text-gray-200 font-light">|</span>

                  {/* Barang Count */}
                  <div className="flex items-center gap-1.5">
                    <svg className="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span className="font-semibold text-gray-800">112 Barang</span>
                  </div>

                  <span className="text-gray-200 font-light">|</span>

                  {/* Bergabung */}
                  <div className="flex items-center gap-1.5">
                    <svg className="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span className="text-gray-500 font-normal">Bergabung sejak Mar 2024</span>
                  </div>
                </div>
              </div>
            </div>

            {/* Right: Actions Button (+ Ikuti Toko, More Button) - spacious with breathing room */}
            <div className="flex items-center gap-3 pt-2 sm:pt-6 self-start md:self-auto pr-1">
              <button
                type="button"
                onClick={() => setIsFollowing(!isFollowing)}
                className={`px-6 sm:px-7 h-11 sm:h-11.5 rounded-xl text-xs sm:text-[14px] font-bold flex items-center gap-2 transition-all cursor-pointer shadow-xs ${
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
                className="w-11 h-11 sm:w-11.5 sm:h-11.5 rounded-xl border border-gray-200/90 bg-white hover:bg-gray-50 flex items-center justify-center text-gray-700 transition-colors shadow-2xs cursor-pointer"
                title="Menu Lainnya"
              >
                <svg className="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                </svg>
              </button>
            </div>
          </div>
          {/* 5. Tabs Navigation: Produk | Tentang | Ulasan - Enlarged typography and spacing */}
          <div className="border-b border-gray-200/80 flex items-center gap-8 sm:gap-10 text-[15px] sm:text-[16px] md:text-[17px] font-bold mt-8">
            <button
              onClick={() => setActiveTab('produk')}
              className={`pb-3.5 relative transition-colors cursor-pointer ${
                activeTab === 'produk'
                  ? 'text-[#4F26A6]'
                  : 'text-gray-500 hover:text-gray-900'
              }`}
            >
              <span>Produk</span>
              {activeTab === 'produk' && (
                <div className="absolute bottom-0 left-0 right-0 h-[3px] bg-[#4F26A6] rounded-full" />
              )}
            </button>

            <button
              onClick={() => setActiveTab('tentang')}
              className={`pb-3.5 relative transition-colors cursor-pointer ${
                activeTab === 'tentang'
                  ? 'text-[#4F26A6]'
                  : 'text-gray-500 hover:text-gray-900'
              }`}
            >
              <span>Tentang</span>
              {activeTab === 'tentang' && (
                <div className="absolute bottom-0 left-0 right-0 h-[3px] bg-[#4F26A6] rounded-full" />
              )}
            </button>

            <button
              onClick={() => setActiveTab('ulasan')}
              className={`pb-3.5 relative transition-colors cursor-pointer ${
                activeTab === 'ulasan'
                  ? 'text-[#4F26A6]'
                  : 'text-gray-500 hover:text-gray-900'
              }`}
            >
              <span>Ulasan</span>
              {activeTab === 'ulasan' && (
                <div className="absolute bottom-0 left-0 right-0 h-[3px] bg-[#4F26A6] rounded-full" />
              )}
            </button>
          </div>
        </div>
        {activeTab === 'produk' && (
          <div className="mt-8">
            {/* Filter & Sort Bar */}
            <div className="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
              {/* Category Pills - Enlarged */}
              <div className="flex items-center gap-2.5 sm:gap-3 overflow-x-auto w-full sm:w-auto pb-1.5 sm:pb-0 scrollbar-none">
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
                    className={`px-5 sm:px-5.5 py-2.5 rounded-xl sm:rounded-2xl text-[13.5px] sm:text-[14.5px] font-bold whitespace-nowrap transition-all cursor-pointer ${
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

            {/* Product Cards Grid */}
            <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4 lg:gap-6">
              {filteredProducts.map((product) => (
                <ProductCard
                  key={product.id}
                  product={product}
                  onLikeToggle={handleLikeToggle}
                />
              ))}
            </div>
          </div>
        )}

        {activeTab === 'tentang' && (
          <div className="mt-8 bg-white rounded-2xl p-6 sm:p-8 border border-gray-100 shadow-sm max-w-3xl">
            <h3 className="text-lg font-bold text-gray-900 mb-3">Tentang Rachel Vennya</h3>
            <p className="text-gray-600 text-sm leading-relaxed mb-4">
              Selamat datang di toko resmi preloved Rachel Vennya di WhiMarket! Semua barang yang dijual di sini merupakan koleksi pribadi yang dirawat dengan sangat baik dan dijamin 100% keasliannya.
            </p>
            <p className="text-gray-600 text-sm leading-relaxed mb-6">
              Sebagian hasil penjualan dari toko ini akan disalurkan untuk program pemberdayaan sosial dan donasi kemanusiaan.
            </p>
            <div className="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4 border-t border-gray-100">
              <div>
                <p className="text-xs text-gray-400 font-medium">Lokasi Pengiriman</p>
                <p className="text-sm font-bold text-gray-900 mt-0.5">Jakarta Selatan</p>
              </div>
              <div>
                <p className="text-xs text-gray-400 font-medium">Rata-rata Pengiriman</p>
                <p className="text-sm font-bold text-gray-900 mt-0.5">1-2 Hari Kerja</p>
              </div>
              <div>
                <p className="text-xs text-gray-400 font-medium">Respon Chat</p>
                <p className="text-sm font-bold text-gray-900 mt-0.5">&plusmn; 15 Menit</p>
              </div>
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
