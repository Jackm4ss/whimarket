import React from 'react';
import { STEPS } from '@/data/landingData';

export const StepsSection: React.FC = () => {
  return (
    <section id="cara-kerja" className="w-full max-w-[1536px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 xl:px-20 pt-2 pb-10 sm:pb-14">
      {/* Section Header */}
      <div className="mb-8 sm:mb-12">
        <h2 className="text-xl sm:text-2xl lg:text-[28px] font-extrabold text-[#111827] tracking-tight">
          Cara Kerja
        </h2>
      </div>

      {/* Steps Grid / Row with Connecting Dashed Lines */}
      <div className="relative grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 sm:gap-10 lg:gap-6 items-start">
        {/* Desktop Connecting Dashed Lines */}
        <div className="hidden lg:block absolute top-[55px] left-[20%] w-[10%] border-t-2 border-dashed border-gray-300 z-0 pointer-events-none"></div>
        <div className="hidden lg:block absolute top-[55px] left-[45%] w-[10%] border-t-2 border-dashed border-gray-300 z-0 pointer-events-none"></div>
        <div className="hidden lg:block absolute top-[55px] left-[70%] w-[10%] border-t-2 border-dashed border-gray-300 z-0 pointer-events-none"></div>

        {/* Mobile Continuous Curved Zigzag Trail */}
        <svg className="block sm:hidden absolute inset-0 w-full h-full pointer-events-none z-0" viewBox="0 0 100 900" preserveAspectRatio="none" fill="none">
          <path
            d="M 70.8 90.3 C 92 125, 98 175, 93 220 C 88 245, 78 260, 70.0 272.9 C 45 315, 6 385, 9 445 C 12 475, 20 493, 28.3 507.1 C 45 530, 95 615, 92 680 C 88 715, 80 735, 71.4 752.4"
            stroke="#E2E8F0"
            strokeWidth="2"
            strokeLinecap="round"
          />
        </svg>

        {/* Step 1: Temukan Barang Favorit */}
        <div className="relative z-10 flex flex-col items-center text-center group">
          <div className="relative w-full h-[110px] flex items-center justify-center mb-5">
            <div className="hidden lg:flex absolute -top-1 left-8 w-6 h-6 rounded-full bg-[#4F26A6] text-white font-extrabold text-xs items-center justify-center shadow-sm">
              1
            </div>
            <div className="relative inline-flex items-center justify-center">
              <div className="flex lg:hidden absolute -right-4 sm:-right-3.5 bottom-1 w-6 h-6 rounded-full bg-[#4F26A6] text-white font-extrabold text-xs items-center justify-center shadow-md z-30 ring-2 ring-white">
                1
              </div>
              <img
                src={STEPS[0].image}
                alt={STEPS[0].title}
                className="relative z-10 max-h-[118px] sm:max-h-[125px] lg:max-h-[96px] w-auto object-contain group-hover:scale-105 transition-transform duration-300 drop-shadow-sm"
              />
            </div>
          </div>
          <h3 className="text-lg sm:text-[18.5px] lg:text-[16.5px] font-extrabold lg:font-bold text-[#111827] mb-2 leading-snug">
            {STEPS[0].title}
          </h3>
          <p className="text-sm sm:text-[14px] lg:text-[13.5px] text-gray-600 lg:text-gray-500 leading-relaxed max-w-[270px] sm:max-w-[280px] lg:max-w-[240px]">
            {STEPS[0].description}
          </p>
        </div>

        {/* Step 2: Transaksi Aman */}
        <div className="relative z-10 flex flex-col items-center text-center group">
          <div className="relative w-full h-[110px] flex items-center justify-center mb-5">
            <div className="hidden lg:flex absolute -top-1 left-8 w-6 h-6 rounded-full bg-[#4F26A6] text-white font-extrabold text-xs items-center justify-center shadow-sm">
              2
            </div>
            <div className="relative inline-flex items-center justify-center">
              <div className="flex lg:hidden absolute -right-2 sm:-right-1 top-8 w-6 h-6 rounded-full bg-[#4F26A6] text-white font-extrabold text-xs items-center justify-center shadow-md z-30 ring-2 ring-white">
                2
              </div>
              <img
                src={STEPS[1].image}
                alt={STEPS[1].title}
                className="relative z-10 max-h-[118px] sm:max-h-[125px] lg:max-h-[96px] w-auto object-contain group-hover:scale-105 transition-transform duration-300 drop-shadow-sm"
              />
            </div>
          </div>
          <h3 className="text-lg sm:text-[18.5px] lg:text-[16.5px] font-extrabold lg:font-bold text-[#111827] mb-2 leading-snug">
            {STEPS[1].title}
          </h3>
          <p className="text-sm sm:text-[14px] lg:text-[13.5px] text-gray-600 lg:text-gray-500 leading-relaxed max-w-[270px] sm:max-w-[280px] lg:max-w-[240px]">
            {STEPS[1].description}
          </p>
        </div>

        {/* Step 3: Barang Dikirim */}
        <div className="relative z-10 flex flex-col items-center text-center group">
          <div className="relative w-full h-[110px] flex items-center justify-center mb-5">
            <div className="hidden lg:flex absolute -top-1 left-8 w-6 h-6 rounded-full bg-[#4F26A6] text-white font-extrabold text-xs items-center justify-center shadow-sm">
              3
            </div>
            <div className="relative inline-flex items-center justify-center">
              <div className="flex lg:hidden absolute left-1 sm:left-2 top-9 w-6 h-6 rounded-full bg-[#4F26A6] text-white font-extrabold text-xs items-center justify-center shadow-md z-30 ring-2 ring-white">
                3
              </div>
              <img
                src={STEPS[2].image}
                alt={STEPS[2].title}
                className="relative z-10 max-h-[130px] sm:max-h-[138px] lg:max-h-[124px] w-auto object-contain group-hover:scale-105 transition-transform duration-300 drop-shadow-sm"
              />
            </div>
          </div>
          <h3 className="text-lg sm:text-[18.5px] lg:text-[16.5px] font-extrabold lg:font-bold text-[#111827] mb-2 leading-snug">
            {STEPS[2].title}
          </h3>
          <p className="text-sm sm:text-[14px] lg:text-[13.5px] text-gray-600 lg:text-gray-500 leading-relaxed max-w-[270px] sm:max-w-[280px] lg:max-w-[240px]">
            {STEPS[2].description}
          </p>
        </div>

        {/* Step 4: Barang Diterima */}
        <div className="relative z-10 flex flex-col items-center text-center group">
          <div className="relative w-full h-[110px] flex items-center justify-center mb-5">
            <div className="hidden lg:flex absolute -top-1 left-8 w-6 h-6 rounded-full bg-[#4F26A6] text-white font-extrabold text-xs items-center justify-center shadow-sm">
              4
            </div>
            <div className="relative inline-flex items-center justify-center">
              <div className="flex lg:hidden absolute -right-2 sm:-right-1 top-10 w-6 h-6 rounded-full bg-[#4F26A6] text-white font-extrabold text-xs items-center justify-center shadow-md z-30 ring-2 ring-white">
                4
              </div>
              <img
                src={STEPS[3].image}
                alt={STEPS[3].title}
                className="relative z-10 max-h-[118px] sm:max-h-[125px] lg:max-h-[96px] w-auto object-contain group-hover:scale-105 transition-transform duration-300 drop-shadow-sm"
              />
            </div>
          </div>
          <h3 className="text-lg sm:text-[18.5px] lg:text-[16.5px] font-extrabold lg:font-bold text-[#111827] mb-2 leading-snug">
            {STEPS[3].title}
          </h3>
          <p className="text-sm sm:text-[14px] lg:text-[13.5px] text-gray-600 lg:text-gray-500 leading-relaxed max-w-[270px] sm:max-w-[280px] lg:max-w-[240px]">
            {STEPS[3].description}
          </p>
        </div>
      </div>
    </section>
  );
};
