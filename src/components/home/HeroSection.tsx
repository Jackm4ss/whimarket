import React from 'react';
import { VerifiedBadge } from '@/components/common/VerifiedBadge';

export const HeroSection: React.FC = () => {
  return (
    <main className="max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 py-6 sm:py-8 lg:py-6 flex-1 flex flex-col justify-center">
      <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-6 items-center">
        {/* ==================== LEFT HERO COLUMN ==================== */}
        <div className="lg:col-span-5 flex flex-col items-center lg:items-start text-center lg:text-left z-10">
          {/* Category Pill Badge */}
          <div className="inline-flex items-center gap-2 sm:gap-2.5 px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-full bg-[#F3EEFF] text-[#4F26A6] text-xs sm:text-[13.5px] font-bold tracking-wide mb-4 sm:mb-6">
            <svg className="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#4F26A6] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
              <path d="M12 2v20M2 12h20M4.93 4.93l14.14 14.14M4.93 19.07l14.14-14.14" />
            </svg>
            <span>Marketplace Pre-loved &amp; Merchandise</span>
          </div>

          {/* Hero Headline */}
          <div className="relative mb-3 sm:mb-4 w-full flex flex-col items-center lg:items-start">
            <h1 className="text-4xl sm:text-5xl md:text-6xl xl:text-[66px] leading-[1.08] font-extrabold tracking-tight">
              <span className="block text-[#111827]">Dari mereka.</span>
              <span className="block text-[#4F26A6]">Untuk kamu.</span>
            </h1>

            {/* Decorative Golden Underline Swoosh */}
            <svg className="w-[280px] sm:w-[360px] lg:w-[420px] h-[18px] sm:h-[22px] lg:h-[26px] text-[#F59E0B] -mt-1 mx-auto lg:mx-0" viewBox="0 0 420 26" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M 4 18 C 100 4, 300 4, 414 18" stroke="#F59E0B" strokeWidth="5.2" strokeLinecap="round" />
            </svg>
          </div>

          {/* Paragraph Description */}
          <p className="text-sm sm:text-base lg:text-[17px] text-[#4B5563] leading-relaxed max-w-[500px] mb-6 sm:mb-8 mx-auto lg:mx-0">
            Temukan barang pre-loved dan merchandise personal milik artis, selebgram, dan streamer favoritmu.<br className="hidden sm:inline" />
            Original, diverifikasi, dan terpercaya.
          </p>

          {/* CTA Action Buttons */}
          <div className="flex flex-col xs:flex-row items-stretch xs:items-center justify-center lg:justify-start gap-3 sm:gap-4 mb-6 sm:mb-7 w-full xs:w-auto">
            {/* Primary CTA Button */}
            <a
              href="/belanja"
              className="w-full xs:w-[210px] sm:w-[220px] h-12 sm:h-[52px] inline-flex items-center justify-center gap-2.5 sm:gap-3 bg-[#4F26A6] border-2 border-[#4F26A6] text-white text-sm sm:text-[15.5px] font-bold rounded-xl hover:bg-[#3E1D85] hover:border-[#3E1D85] transition-all shadow-md shadow-[#4F26A6]/20"
            >
              <svg className="w-4 h-4 sm:w-5 sm:h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.4" strokeLinecap="round" strokeLinejoin="round">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                <line x1="3" y1="6" x2="21" y2="6" />
                <path d="M16 10a4 4 0 0 1-8 0" />
              </svg>
              <span>Belanja Sekarang</span>
            </a>

            {/* Secondary CTA Button */}
            <a
              href="#seller"
              className="w-full xs:w-[210px] sm:w-[220px] h-12 sm:h-[52px] inline-flex items-center justify-center gap-2.5 sm:gap-3 bg-transparent border-2 border-[#4F26A6] text-[#4F26A6] text-sm sm:text-[15.5px] font-bold rounded-xl hover:bg-[#4F26A6]/5 transition-all"
            >
              <svg className="w-4 h-4 sm:w-5 sm:h-5 text-[#4F26A6]" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.4" strokeLinecap="round" strokeLinejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
              </svg>
              <span>Jelajahi Seller</span>
            </a>
          </div>

          {/* Trust Note with Shield Check Icon (Inline & Center Aligned) */}
          <div className="w-full text-center lg:text-left mb-4 lg:mb-0 text-xs sm:text-[14px] text-[#4B5563]">
            <p className="text-center lg:text-left leading-snug mx-auto lg:mx-0 max-w-[340px] sm:max-w-none">
              <span className="inline-flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-[#F3EEFF] align-middle mr-2 -mt-0.5 shrink-0">
                <svg className="w-4 h-4 text-[#4F26A6]" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                  <polyline points="9 12 11 14 15 10" />
                </svg>
              </span>
              <span>Setiap seller <strong className="text-[#4F26A6] font-bold">diverifikasi</strong> sebelum dapat menjual di WhiMarket.</span>
            </p>
          </div>
        </div>

        {/* ==================== RIGHT HERO COLUMN (Visual Stage - 100% Pixel-Perfect Match with index.html) ==================== */}
        <div className="lg:col-span-7 relative flex items-center justify-center min-h-[380px] sm:min-h-[460px] md:min-h-[520px] lg:h-[560px] w-full mt-4 lg:mt-0">
          
          {/* Large Arch Background Panel (z-0 so it renders in front of page background and behind stage) */}
          <div className="absolute w-[280px] sm:w-[380px] md:w-[440px] lg:w-[490px] h-[340px] sm:h-[440px] md:h-[500px] lg:h-[550px] arch-panel top-0 sm:top-1 left-1/2 -translate-x-1/2 lg:-translate-x-[52%] z-0"></div>
          {/* ==================== PIXEL-PERFECT VERIFIED CREATOR BADGE ==================== */}
          <div className="absolute top-2 sm:top-3 left-2 sm:left-4 z-20 scale-75 sm:scale-90 lg:scale-100 origin-top-left">
            <div className="relative w-[104px] h-[104px] rounded-full shadow-[0_10px_26px_rgba(79,38,166,0.16)] flex items-center justify-center">
              <svg className="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" xmlnsXlink="http://www.w3.org/1999/xlink">
                <defs>
                  {/* Top Arc: from left to right across top */}
                  <path id="badgeTopArc" d="M 16,50 A 34,34 0 0,1 84,50" />
                  {/* Bottom Arc: from left to right curving down so letters stand upright facing center */}
                  <path id="badgeBottomArc" d="M 17,50 A 33,33 0 0,0 83,50" />
                </defs>

                {/* Solid White Background Disc */}
                <circle cx="50" cy="50" r="49" fill="#FFFFFF" />

                {/* Outer Thin Light-Purple Border */}
                <circle cx="50" cy="50" r="48" fill="none" stroke="#E2D9F3" strokeWidth="1.3" />

                {/* Top Arc Text: VERIFIED */}
                <text fontSize="7.4" fontWeight="800" fill="#4F26A6" letterSpacing="0.25em" fontFamily="'Plus Jakarta Sans', sans-serif">
                  <textPath xlinkHref="#badgeTopArc" startOffset="50%" textAnchor="middle">VERIFIED</textPath>
                </text>

                {/* Bottom Arc Text: CREATOR (Upright) */}
                <text fontSize="7.4" fontWeight="800" fill="#4F26A6" letterSpacing="0.25em" fontFamily="'Plus Jakarta Sans', sans-serif">
                  <textPath xlinkHref="#badgeBottomArc" startOffset="50%" textAnchor="middle">CREATOR</textPath>
                </text>

                {/* Left Diamond Dot Glyph */}
                <polygon points="15.5,47.5 17.5,50 15.5,52.5 13.5,50" fill="#4F26A6" />

                {/* Right Diamond Dot Glyph */}
                <polygon points="84.5,47.5 86.5,50 84.5,52.5 82.5,50" fill="#4F26A6" />

                {/* Center 8-Lobe Scalloped Rosette Seal */}
                <g fill="#4F26A6">
                  <circle cx="50" cy="50" r="16" />
                  <circle cx="62.50" cy="50.00" r="6" />
                  <circle cx="58.84" cy="58.84" r="6" />
                  <circle cx="50.00" cy="62.50" r="6" />
                  <circle cx="41.16" cy="58.84" r="6" />
                  <circle cx="37.50" cy="50.00" r="6" />
                  <circle cx="41.16" cy="41.16" r="6" />
                  <circle cx="50.00" cy="37.50" r="6" />
                  <circle cx="58.84" cy="41.16" r="6" />
                </g>

                {/* White Solid Checkmark */}
                <path d="M 44 50 L 48 54.5 L 56.5 45" fill="none" stroke="#FFFFFF" strokeWidth="2.8" strokeLinecap="round" strokeLinejoin="round" />
              </svg>
            </div>
          </div>

          {/* Main Product Stage Composition */}
          <div className="relative z-10 w-[320px] sm:w-[440px] md:w-[500px] lg:w-[560px] h-[300px] sm:h-[400px] md:h-[460px] lg:h-[520px] flex items-center justify-center">
            <img 
              src="/assets/hero-product.png" 
              alt="WhiMarket Creator Pre-loved Products" 
              className="w-full h-full object-contain filter drop-shadow-md"
            />

            {/* Decorative Purple Doodle Slash (Bottom Left) */}
            <div className="absolute bottom-4 sm:bottom-8 -left-4 sm:-left-8 z-20">
              <svg className="w-6 h-6 sm:w-9 sm:h-9 text-[#4F26A6]" viewBox="0 0 32 32" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round">
                <line x1="8" y1="24" x2="16" y2="8" />
                <line x1="16" y1="26" x2="24" y2="10" />
              </svg>
            </div>
          </div>

          {/* Floating Creator Profile Cards (Right Stack) with ONLY Single Elevated Custom Vector */}
          <div className="absolute right-0 sm:right-2 lg:right-0 top-3 sm:top-5 flex flex-col gap-1.5 sm:gap-2.5 z-30 scale-75 sm:scale-90 lg:scale-100 origin-top-right">
            
            {/* Single Custom Purple 4-Point Sparkle Star Vector above Rachel Vennya */}
            <div className="absolute -top-7 sm:-top-8 right-6 sm:right-8 z-40 pointer-events-none">
              <svg className="w-6 h-6 sm:w-7 sm:h-7 text-[#4F26A6]" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                <path d="M12 2v20M2 12h20M4.93 4.93l14.14 14.14M4.93 19.07l14.14-14.14" />
              </svg>
            </div>
            
            {/* Creator 1: Rachel Vennya */}
            <div className="bg-white/95 backdrop-blur-md rounded-2xl p-2 sm:p-2.5 px-3 sm:px-3.5 shadow-[0_8px_24px_rgba(0,0,0,0.06)] border border-gray-100 flex items-center gap-2.5 sm:gap-3 w-[195px] sm:w-[220px] transition-transform hover:-translate-y-0.5">
              <img src="/assets/avatar-rachel.png" alt="Rachel Vennya" className="w-9 h-9 sm:w-11 sm:h-11 rounded-full object-cover shrink-0 ring-2 ring-purple-100" />
              <div className="flex flex-col min-w-0">
                <div className="flex items-center gap-1 sm:gap-1.5">
                  <span className="text-xs sm:text-[13.5px] font-bold text-gray-900 truncate">Rachel Vennya</span>
                  <VerifiedBadge size="sm" className="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                </div>
                <span className="text-[10px] sm:text-[11.5px] text-gray-500 font-medium">@rachelvennya</span>
                <div className="flex items-center gap-1 text-[9.5px] sm:text-[11px] text-gray-500 font-medium mt-0.5">
                  <span className="text-[#F59E0B] font-bold">★ 4.9</span>
                  <span className="text-gray-300">•</span>
                  <span>120 terjual</span>
                </div>
              </div>
            </div>

            {/* Creator 2: Jerome Polin */}
            <div className="bg-white/95 backdrop-blur-md rounded-2xl p-2 sm:p-2.5 px-3 sm:px-3.5 shadow-[0_8px_24px_rgba(0,0,0,0.06)] border border-gray-100 flex items-center gap-2.5 sm:gap-3 w-[195px] sm:w-[220px] transition-transform hover:-translate-y-0.5">
              <img src="/assets/avatar-jerome.png" alt="Jerome Polin" className="w-9 h-9 sm:w-11 sm:h-11 rounded-full object-cover shrink-0 ring-2 ring-purple-100" />
              <div className="flex flex-col min-w-0">
                <div className="flex items-center gap-1 sm:gap-1.5">
                  <span className="text-xs sm:text-[13.5px] font-bold text-gray-900 truncate">Jerome Polin</span>
                  <VerifiedBadge size="sm" className="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                </div>
                <span className="text-[10px] sm:text-[11.5px] text-gray-500 font-medium">@jeromepolin</span>
                <div className="flex items-center gap-1 text-[9.5px] sm:text-[11px] text-gray-500 font-medium mt-0.5">
                  <span className="text-[#F59E0B] font-bold">★ 5.0</span>
                  <span className="text-gray-300">•</span>
                  <span>85 terjual</span>
                </div>
              </div>
            </div>

            {/* Creator 3: Anya Geraldine */}
            <div className="bg-white/95 backdrop-blur-md rounded-2xl p-2 sm:p-2.5 px-3 sm:px-3.5 shadow-[0_8px_24px_rgba(0,0,0,0.06)] border border-gray-100 flex items-center gap-2.5 sm:gap-3 w-[195px] sm:w-[220px] transition-transform hover:-translate-y-0.5">
              <img src="/assets/avatar-anya.png" alt="Anya Geraldine" className="w-9 h-9 sm:w-11 sm:h-11 rounded-full object-cover shrink-0 ring-2 ring-purple-100" />
              <div className="flex flex-col min-w-0">
                <div className="flex items-center gap-1 sm:gap-1.5">
                  <span className="text-xs sm:text-[13.5px] font-bold text-gray-900 truncate">Anya Geraldine</span>
                  <VerifiedBadge size="sm" className="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                </div>
                <span className="text-[10px] sm:text-[11.5px] text-gray-500 font-medium">@anyageraldine</span>
                <div className="flex items-center gap-1 text-[9.5px] sm:text-[11px] text-gray-500 font-medium mt-0.5">
                  <span className="text-[#F59E0B] font-bold">★ 4.9</span>
                  <span className="text-gray-300">•</span>
                  <span>95 terjual</span>
                </div>
              </div>
            </div>
          </div>

          {/* Sticky Paper Note (Bottom Right) */}
          <div className="absolute right-1 sm:right-4 -bottom-3 sm:-bottom-2 z-30 rotate-[-3.5deg] scale-80 sm:scale-90 lg:scale-100 origin-bottom-right transition-transform hover:rotate-0">
            <div className="relative bg-[#FAFAF6] rounded-sm p-3.5 sm:p-4 pt-4 sm:pt-5 pb-3 sm:pb-4 w-[150px] sm:w-[172px] shadow-[0_12px_28px_rgba(0,0,0,0.08)] border border-gray-200/70 flex flex-col font-handwriting">
              <div className="absolute -top-3 sm:-top-3.5 left-1/2 -translate-x-1/2 w-14 sm:w-16 h-4 sm:h-5 tape-strip rounded-sm"></div>
              
              <div className="text-lg sm:text-[21px] leading-[1.3] font-bold space-y-0.5">
                <div className="text-[#111827]">Original.</div>
                <div className="text-[#4F26A6]">Personal.</div>
                <div className="text-[#111827] pt-0.5">Dari mereka,</div>
                <div className="text-[#111827] flex items-center gap-1">
                  <span>untuk kamu.</span>
                  <span className="text-[#4F26A6] text-base sm:text-[18px]">♡</span>
                </div>
              </div>
            </div>

            {/* Decorative 3-line slash doodle */}
            <div className="absolute -top-3 -right-4 sm:-right-6 z-20">
              <svg className="w-5 h-5 sm:w-6 sm:h-6 text-[#4F26A6]" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round">
                <line x1="6" y1="18" x2="12" y2="6"/>
                <line x1="12" y1="20" x2="18" y2="8"/>
                <line x1="18" y1="22" x2="22" y2="12"/>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </main>
  );
};
