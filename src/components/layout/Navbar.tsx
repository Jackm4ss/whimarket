import React, { useState, useEffect } from 'react';
import { cn } from '@/lib/utils';

export const Navbar: React.FC = () => {
  const [isScrolled, setIsScrolled] = useState(false);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const [isBelanjaOpen, setIsBelanjaOpen] = useState(false);
  const [isKategoriOpen, setIsKategoriOpen] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      const scrollY = window.scrollY;
      if (!isScrolled && scrollY > 15) {
        setIsScrolled(true);
      } else if (isScrolled && scrollY <= 5) {
        setIsScrolled(false);
      }
    };

    window.addEventListener('scroll', handleScroll, { passive: true });
    return () => window.removeEventListener('scroll', handleScroll);
  }, [isScrolled]);

  return (
    <>
      <header
        id="main-header"
        className={cn(
          'w-full sticky top-0 z-40 transition-colors duration-200 border-b border-gray-100 lg:border-transparent',
          isScrolled
            ? '!bg-white shadow-[0_4px_20px_-2px_rgba(0,0,0,0.06)] !border-gray-100'
            : 'bg-white/95 sm:bg-white/90 lg:bg-transparent backdrop-blur-md lg:backdrop-blur-none'
        )}
      >
        <div
          id="header-container"
          className="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-4 sm:py-5 flex items-center justify-between gap-4 lg:gap-6"
        >
          {/* Left: Brand Logo & Links */}
          <div className="flex items-center gap-6 xl:gap-8">
            <a href="#" className="flex items-center group shrink-0">
              <img
                src="/assets/logo-whimarket.png"
                alt="WhiMarket"
                className="h-8 sm:h-10 lg:h-[42px] w-auto object-contain mix-blend-multiply"
              />
            </a>

            {/* Desktop Navigation Links */}
            <nav className="hidden lg:flex items-center gap-9 xl:gap-10 text-[15px] xl:text-[15.5px] font-semibold text-gray-700">
              {/* Beranda (Active) */}
              <div className="relative py-1 text-[#4F26A6] font-bold flex flex-col items-center">
                <a href="#beranda">Beranda</a>
                <span className="absolute -bottom-2 left-0 right-0 h-[3px] bg-[#4F26A6] rounded-full"></span>
              </div>

              {/* Dropdown: Belanja */}
              <div className="relative group py-1">
                <button className="flex items-center gap-1.5 text-gray-700 font-semibold hover:text-[#4F26A6] transition-colors focus:outline-none py-1">
                  <span>Belanja</span>
                  <svg
                    className="w-3 h-3 text-gray-400 group-hover:text-[#4F26A6] transition-transform duration-200 group-hover:rotate-180"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
                <div className="absolute left-0 top-full pt-2 w-56 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 z-50 transform translate-y-1 group-hover:translate-y-0">
                  <div className="bg-white rounded-2xl shadow-[0_12px_36px_rgba(0,0,0,0.12)] border border-gray-100 p-2 space-y-0.5">
                    <a href="#semua-produk" className="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                      <svg className="w-4 h-4 text-gray-500 group-hover/item:text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/>
                      </svg>
                      <span>Semua Produk</span>
                    </a>
                    <a href="#barang-terbaru" className="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                      <svg className="w-4 h-4 text-gray-500 group-hover/item:text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                      </svg>
                      <span>Produk Terbaru</span>
                    </a>
                    <a href="#seller-populer" className="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                      <svg className="w-4 h-4 text-gray-500 group-hover/item:text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                      </svg>
                      <span>Produk Populer</span>
                    </a>
                    <a href="#promo-spesial" className="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                      <svg className="w-4 h-4 text-gray-500 group-hover/item:text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                      </svg>
                      <span>Promo Spesial</span>
                    </a>
                  </div>
                </div>
              </div>

              {/* Dropdown: Kategori */}
              <div className="relative group py-1">
                <button className="flex items-center gap-1 text-gray-700 font-semibold hover:text-[#4F26A6] transition-colors focus:outline-none py-1">
                  <span>Kategori</span>
                  <svg
                    className="w-3 h-3 text-gray-400 group-hover:text-[#4F26A6] transition-transform duration-200 group-hover:rotate-180"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
                <div className="absolute left-0 top-full pt-2 w-60 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 z-50 transform translate-y-1 group-hover:translate-y-0">
                  <div className="bg-white rounded-2xl shadow-[0_12px_36px_rgba(0,0,0,0.12)] border border-gray-100 p-2 space-y-0.5">
                    <a href="#kategori-fashion" className="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                      <svg className="w-4 h-4 text-gray-500 group-hover/item:text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M16 3l-4 2-4-2L3 6v4l3 1v10h12V11l3-1V6l-5-3z"/>
                      </svg>
                      <span>Fashion</span>
                    </a>
                    <a href="#kategori-tas" className="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                      <svg className="w-4 h-4 text-gray-500 group-hover/item:text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                      </svg>
                      <span>Tas &amp; Aksesoris</span>
                    </a>
                    <a href="#kategori-hobi" className="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                      <svg className="w-4 h-4 text-gray-500 group-hover/item:text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                      </svg>
                      <span>Hobi &amp; Koleksi</span>
                    </a>
                    <a href="#kategori-merchandise" className="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                      <svg className="w-4 h-4 text-gray-500 group-hover/item:text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M12 8v13m0-13V3m0 5l4.5-2.5m-4.5 2.5L7.5 5.5M20 12l-8 4.5L4 12m16 0V7l-8-4.5L4 7v5"/>
                      </svg>
                      <span>Merchandise</span>
                    </a>
                    <a href="#kategori-elektronik" className="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                      <svg className="w-4 h-4 text-gray-500 group-hover/item:text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="3"/>
                      </svg>
                      <span>Elektronik</span>
                    </a>
                    <a href="#kategori-kecantikan" className="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-gray-700 hover:text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                      <svg className="w-4 h-4 text-gray-500 group-hover/item:text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                      </svg>
                      <span>Kecantikan</span>
                    </a>
                    <div className="border-t border-gray-100 my-1"></div>
                    <a href="#kategori" className="flex items-center gap-3 px-3 py-2 rounded-xl text-xs sm:text-[13px] font-semibold text-[#4F26A6] hover:bg-[#F3EEFF] transition-colors group/item">
                      <svg className="w-4 h-4 text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                        <circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/>
                      </svg>
                      <span>Lihat Semua Kategori</span>
                    </a>
                  </div>
                </div>
              </div>
            </nav>
          </div>

          {/* Center-Right Area: Search Bar */}
          <div className="flex-1 max-w-[320px] md:max-w-[380px] lg:max-w-[440px] xl:max-w-[480px] relative hidden sm:block">
            <div className="relative w-full">
              <input
                type="text"
                placeholder="Cari produk, kategori, atau apapun..."
                className="w-full h-11 sm:h-[44px] pl-10 pr-4 rounded-xl bg-[#F9FAFB] border border-gray-200 text-xs sm:text-[14px] text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] transition-all"
              />
              <svg className="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
              </svg>
            </div>
          </div>

          {/* Right Area: Wishlist, Cart, Divider, Auth Buttons */}
          <div className="flex items-center gap-2 sm:gap-3 shrink-0">
            {/* Wishlist Icon */}
            <a href="#wishlist" className="relative p-2 text-gray-700 hover:text-[#4F26A6] transition-colors rounded-xl hover:bg-gray-50" title="Favorit">
              <svg className="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" strokeWidth="1.8" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
              </svg>
              <span className="absolute top-0.5 right-0.5 w-4 h-4 sm:w-[18px] sm:h-[18px] rounded-full bg-[#4F26A6] text-white text-[9.5px] sm:text-[10px] font-extrabold flex items-center justify-center shadow-xs">
                0
              </span>
            </a>

            {/* Shopping Cart Icon */}
            <a href="#keranjang" className="relative p-2 text-gray-700 hover:text-[#4F26A6] transition-colors rounded-xl hover:bg-gray-50" title="Keranjang">
              <svg className="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" strokeWidth="1.8" viewBox="0 0 24 24">
                <circle cx="9" cy="21" r="1"/>
                <circle cx="20" cy="21" r="1"/>
                <path strokeLinecap="round" strokeLinejoin="round" d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
              </svg>
              <span className="absolute top-0.5 right-0.5 w-4 h-4 sm:w-[18px] sm:h-[18px] rounded-full bg-[#4F26A6] text-white text-[9.5px] sm:text-[10px] font-extrabold flex items-center justify-center shadow-xs">
                0
              </span>
            </a>

            <div className="hidden lg:block h-6 w-[1px] bg-gray-200 mx-1"></div>

            {/* Auth Actions (Desktop) */}
            <div className="hidden lg:flex items-center gap-2.5">
              <a href="#masuk" className="px-5 h-[44px] flex items-center justify-center rounded-xl text-[15px] font-semibold text-[#4F26A6] border-[1.5px] border-[#4F26A6] hover:bg-[#4F26A6]/5 transition-all">
                Masuk
              </a>
              <a href="#daftar" className="px-6 h-[44px] flex items-center justify-center rounded-xl text-[15px] font-semibold text-white bg-[#4F26A6] hover:bg-[#3E1D85] shadow-sm transition-all">
                Daftar
              </a>
            </div>

            {/* Mobile Menu Hamburger Button */}
            <button
              id="mobile-menu-btn"
              onClick={() => setIsMobileMenuOpen(true)}
              className="lg:hidden p-2 rounded-xl text-gray-700 hover:bg-gray-100 hover:text-[#4F26A6] transition-colors focus:outline-none ml-1"
              aria-label="Toggle Menu"
            >
              <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.2" d="M4 6h16M4 12h16M4 18h16"/>
              </svg>
            </button>
          </div>
        </div>
      </header>

      {/* Mobile Drawer Overlay */}
      <div
        id="sidebar-overlay"
        onClick={() => setIsMobileMenuOpen(false)}
        className={cn(
          'fixed inset-0 bg-black/40 backdrop-blur-sm z-50 transition-opacity duration-300',
          isMobileMenuOpen ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'
        )}
      ></div>

      {/* Mobile Sidebar */}
      <aside
        id="mobile-sidebar"
        className={cn(
          'fixed top-0 right-0 bottom-0 w-[300px] sm:w-[340px] bg-white z-50 shadow-2xl flex flex-col justify-between p-6 transition-transform duration-300 ease-in-out',
          isMobileMenuOpen ? 'translate-x-0' : 'translate-x-full'
        )}
      >
        <div className="flex flex-col">
          <div className="flex items-center justify-between pb-4 border-b border-gray-200">
            <img src="/assets/logo-whimarket.png" alt="WhiMarket" className="h-8 w-auto object-contain mix-blend-multiply"/>
            <button
              onClick={() => setIsMobileMenuOpen(false)}
              className="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center transition-colors focus:outline-none"
              aria-label="Close Menu"
            >
              <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <div className="mt-4 relative sm:hidden">
            <input
              type="text"
              placeholder="Cari produk, kategori..."
              className="w-full h-10 pl-9 pr-3 rounded-xl bg-[#F9FAFB] border border-gray-200 text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:border-[#4F26A6]"
            />
            <svg className="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
              <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
          </div>

          <nav className="flex flex-col mt-3 divide-y divide-gray-100 text-[15px] sm:text-[16px] font-bold text-gray-800">
            <a href="#beranda" className="sidebar-link py-3.5 text-[#4F26A6] flex items-center justify-between">
              <span>Beranda</span>
              <span className="w-2 h-2 rounded-full bg-[#4F26A6]"></span>
            </a>

            {/* Belanja Accordion */}
            <div className="py-3 flex flex-col">
              <div
                className="flex items-center justify-between text-gray-800 cursor-pointer"
                onClick={() => setIsBelanjaOpen(!isBelanjaOpen)}
              >
                <span>Belanja</span>
                <svg className={cn('w-4 h-4 text-gray-400 transition-transform duration-200', isBelanjaOpen && 'rotate-180')} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>
              {isBelanjaOpen && (
                <div className="flex flex-col pl-2 pt-2 space-y-1 text-xs font-semibold text-gray-700">
                  <a href="#semua-produk" className="sidebar-link flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors">
                    <svg className="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></svg>
                    <span>Semua Produk</span>
                  </a>
                  <a href="#barang-terbaru" className="sidebar-link flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors">
                    <svg className="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    <span>Produk Terbaru</span>
                  </a>
                  <a href="#seller-populer" className="sidebar-link flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors">
                    <svg className="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                    <span>Produk Populer</span>
                  </a>
                  <a href="#promo-spesial" className="sidebar-link flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors">
                    <svg className="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span>Promo Spesial</span>
                  </a>
                </div>
              )}
            </div>

            {/* Kategori Accordion */}
            <div className="py-3 flex flex-col">
              <div
                className="flex items-center justify-between text-gray-800 cursor-pointer"
                onClick={() => setIsKategoriOpen(!isKategoriOpen)}
              >
                <span>Kategori</span>
                <svg className={cn('w-4 h-4 text-gray-400 transition-transform duration-200', isKategoriOpen && 'rotate-180')} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>
              {isKategoriOpen && (
                <div className="flex flex-col pl-2 pt-2 space-y-1 text-xs font-semibold text-gray-700">
                  <a href="#kategori-fashion" className="sidebar-link flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors">
                    <svg className="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M16 3l-4 2-4-2L3 6v4l3 1v10h12V11l3-1V6l-5-3z"/></svg>
                    <span>Fashion</span>
                  </a>
                  <a href="#kategori-tas" className="sidebar-link flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors">
                    <svg className="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span>Tas &amp; Aksesoris</span>
                  </a>
                  <a href="#kategori-hobi" className="sidebar-link flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors">
                    <svg className="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                    <span>Hobi &amp; Koleksi</span>
                  </a>
                  <a href="#kategori-merchandise" className="sidebar-link flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors">
                    <svg className="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M12 8v13m0-13V3m0 5l4.5-2.5m-4.5 2.5L7.5 5.5M20 12l-8 4.5L4 12m16 0V7l-8-4.5L4 7v5"/></svg>
                    <span>Merchandise</span>
                  </a>
                  <a href="#kategori-elektronik" className="sidebar-link flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors">
                    <svg className="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="3"/></svg>
                    <span>Elektronik</span>
                  </a>
                  <a href="#kategori-kecantikan" className="sidebar-link flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-[#F3EEFF] hover:text-[#4F26A6] transition-colors">
                    <svg className="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                    <span>Kecantikan</span>
                  </a>
                  <a href="#kategori" className="sidebar-link flex items-center gap-2.5 py-2 px-2.5 rounded-lg text-[#4F26A6] font-bold hover:bg-[#F3EEFF] transition-colors pt-2 border-t border-gray-100">
                    <svg className="w-4 h-4 text-[#4F26A6] shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/></svg>
                    <span>Lihat Semua Kategori</span>
                  </a>
                </div>
              )}
            </div>
          </nav>
        </div>

        <div className="pt-4 border-t border-gray-200 flex flex-col gap-2.5">
          <a href="#daftar" className="w-full py-3 rounded-xl text-center font-bold text-white bg-[#4F26A6] hover:bg-[#3E1D85] shadow-md shadow-[#4F26A6]/20 transition-all text-sm">
            Daftar Sekarang
          </a>
          <a href="#masuk" className="w-full py-3 rounded-xl text-center font-bold text-[#4F26A6] border-2 border-[#4F26A6] hover:bg-[#4F26A6]/5 transition-all text-sm">
            Masuk ke Akun
          </a>
        </div>
      </aside>
    </>
  );
};
