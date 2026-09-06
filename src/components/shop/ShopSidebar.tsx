import React, { useState } from 'react';

export interface FilterState {
  category: string;
  priceRange: [number, number];
  condition: string[];
  location: string;
  seller: string;
  extra: string[];
}

interface ShopSidebarProps {
  filters: FilterState;
  onFilterChange: (filters: FilterState) => void;
  onReset: () => void;
}

export const ShopSidebar: React.FC<ShopSidebarProps> = ({
  filters,
  onFilterChange,
  onReset,
}) => {
  // Accordion collapsed states
  const [openSections, setOpenSections] = useState({
    kategori: true,
    harga: true,
    kondisi: true,
    lokasi: true,
    seller: true,
    lainnya: true,
  });

  const toggleSection = (section: keyof typeof openSections) => {
    setOpenSections((prev) => ({ ...prev, [section]: !prev[section] }));
  };

  const categories = [
    { id: 'all', name: 'Semua Kategori', icon: (
      <svg className="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
        <rect x="3" y="3" width="7" height="7" rx="1" />
        <rect x="14" y="3" width="7" height="7" rx="1" />
        <rect x="14" y="14" width="7" height="7" rx="1" />
        <rect x="3" y="14" width="7" height="7" rx="1" />
      </svg>
    )},
    { id: 'fashion', name: 'Fashion', icon: (
      <svg className="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
        <path d="M16 3l-4 2-4-2L3 6v4l3 1v10h12V11l3-1V6l-5-3z"/>
      </svg>
    )},
    { id: 'tas', name: 'Tas & Aksesoris', icon: (
      <svg className="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
        <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
      </svg>
    )},
    { id: 'hobi', name: 'Hobi & Koleksi', icon: (
      <svg className="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
      </svg>
    )},
    { id: 'merchandise', name: 'Merchandise', icon: (
      <svg className="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
        <circle cx="12" cy="8" r="7"/>
        <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>
      </svg>
    )},
    { id: 'elektronik', name: 'Elektronik', icon: (
      <svg className="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
        <line x1="8" y1="21" x2="16" y2="21"/>
        <line x1="12" y1="17" x2="12" y2="21"/>
      </svg>
    )},
    { id: 'kecantikan', name: 'Kecantikan', icon: (
      <svg className="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
        <path d="M12 2a4 4 0 0 0-4 4c0 3 4 8 4 8s4-5 4-8a4 4 0 0 0-4-4z"/>
        <circle cx="12" cy="6" r="1"/>
      </svg>
    )},
  ];

  const conditions = [
    { id: 'all', label: 'Semua Kondisi' },
    { id: 'seperti-baru', label: 'Seperti Baru' },
    { id: 'sangat-baik', label: 'Sangat Baik' },
    { id: 'baik', label: 'Baik' },
    { id: 'cukup', label: 'Cukup' },
  ];

  const handleConditionToggle = (id: string) => {
    if (id === 'all') {
      onFilterChange({ ...filters, condition: ['all'] });
      return;
    }
    const current = filters.condition.filter(c => c !== 'all');
    const updated = current.includes(id)
      ? current.filter(c => c !== id)
      : [...current, id];
    onFilterChange({ ...filters, condition: updated.length === 0 ? ['all'] : updated });
  };

  const handleExtraToggle = (id: string) => {
    const current = filters.extra;
    const updated = current.includes(id)
      ? current.filter(x => x !== id)
      : [...current, id];
    onFilterChange({ ...filters, extra: updated });
  };

  return (
    <aside className="w-full lg:w-[220px] xl:w-[235px] shrink-0 space-y-5 bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-[0_2px_12px_rgba(0,0,0,0.03)] self-start">
      {/* 1. Kategori Section */}
      <div>
        <button
          type="button"
          onClick={() => toggleSection('kategori')}
          className="w-full flex items-center justify-between text-[13.5px] font-bold text-gray-900 mb-2.5"
        >
          <span>Kategori</span>
          <svg
            className={`w-3.5 h-3.5 text-gray-400 transition-transform duration-200 ${openSections.kategori ? '' : 'rotate-180'}`}
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 15l7-7 7 7" />
          </svg>
        </button>

        {openSections.kategori && (
          <div className="space-y-0.5">
            {categories.map((cat) => {
              const isActive = filters.category === cat.id;
              return (
                <button
                  key={cat.id}
                  type="button"
                  onClick={() => onFilterChange({ ...filters, category: cat.id })}
                  className={`w-full flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-xs font-medium transition-all ${
                    isActive
                      ? 'bg-[#F4EEFF] text-[#4F26A6] font-bold'
                      : 'text-gray-600 hover:text-[#4F26A6] hover:bg-gray-50'
                  }`}
                >
                  <span className={isActive ? 'text-[#4F26A6]' : 'text-gray-400'}>
                    {cat.icon}
                  </span>
                  <span>{cat.name}</span>
                </button>
              );
            })}
          </div>
        )}
      </div>

      <div className="h-[1px] bg-gray-100 w-full" />

      {/* 2. Harga Section */}
      <div>
        <button
          type="button"
          onClick={() => toggleSection('harga')}
          className="w-full flex items-center justify-between text-[13.5px] font-bold text-gray-900 mb-2.5"
        >
          <span>Harga</span>
          <svg
            className={`w-3.5 h-3.5 text-gray-400 transition-transform duration-200 ${openSections.harga ? '' : 'rotate-180'}`}
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 15l7-7 7 7" />
          </svg>
        </button>

        {openSections.harga && (
          <div className="pt-1">
            {/* Slider bar */}
            <div className="relative w-full h-4 flex items-center mb-2">
              <div className="w-full h-1 bg-gray-200 rounded-full overflow-hidden">
                <div className="h-full bg-[#4F26A6] rounded-full w-full" />
              </div>
              <div className="absolute left-0 w-3.5 h-3.5 bg-[#4F26A6] rounded-full ring-2 ring-white shadow-xs cursor-pointer" />
              <div className="absolute right-0 w-3.5 h-3.5 bg-[#4F26A6] rounded-full ring-2 ring-white shadow-xs cursor-pointer" />
            </div>
            {/* Price labels: Rp 0 ... Rp 50.000.000 */}
            <div className="flex items-center justify-between text-[10.5px] text-gray-400 font-medium">
              <span>Rp 0</span>
              <span>Rp 50.000.000</span>
            </div>
          </div>
        )}
      </div>

      <div className="h-[1px] bg-gray-100 w-full" />

      {/* 3. Kondisi Section */}
      <div>
        <button
          type="button"
          onClick={() => toggleSection('kondisi')}
          className="w-full flex items-center justify-between text-[13.5px] font-bold text-gray-900 mb-2.5"
        >
          <span>Kondisi</span>
          <svg
            className={`w-3.5 h-3.5 text-gray-400 transition-transform duration-200 ${openSections.kondisi ? '' : 'rotate-180'}`}
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 15l7-7 7 7" />
          </svg>
        </button>

        {openSections.kondisi && (
          <div className="space-y-2 pt-0.5">
            {conditions.map((item) => {
              const isChecked = item.id === 'all'
                ? filters.condition.includes('all')
                : filters.condition.includes(item.id);

              return (
                <label
                  key={item.id}
                  className="flex items-center gap-2 cursor-pointer select-none group text-xs text-gray-600"
                >
                  <input
                    type="checkbox"
                    checked={isChecked}
                    onChange={() => handleConditionToggle(item.id)}
                    className="sr-only"
                  />
                  <div
                    className={`w-3.5 h-3.5 rounded-[3px] border flex items-center justify-center transition-colors ${
                      isChecked
                        ? 'bg-[#4F26A6] border-[#4F26A6]'
                        : 'border-gray-300 bg-white group-hover:border-gray-400'
                    }`}
                  >
                    {isChecked && (
                      <svg className="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" strokeWidth="3" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12" />
                      </svg>
                    )}
                  </div>
                  <span className={isChecked ? 'text-gray-900 font-semibold' : ''}>
                    {item.label}
                  </span>
                </label>
              );
            })}
          </div>
        )}
      </div>

      <div className="h-[1px] bg-gray-100 w-full" />

      {/* 4. Lokasi Section */}
      <div>
        <button
          type="button"
          onClick={() => toggleSection('lokasi')}
          className="w-full flex items-center justify-between text-[13.5px] font-bold text-gray-900 mb-2.5"
        >
          <span>Lokasi</span>
          <svg
            className={`w-3.5 h-3.5 text-gray-400 transition-transform duration-200 ${openSections.lokasi ? '' : 'rotate-180'}`}
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 15l7-7 7 7" />
          </svg>
        </button>

        {openSections.lokasi && (
          <div className="relative">
            <select
              value={filters.location}
              onChange={(e) => onFilterChange({ ...filters, location: e.target.value })}
              className="w-full appearance-none bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] cursor-pointer"
            >
              <option value="">Pilih Lokasi</option>
              <option value="jakarta">Jakarta</option>
              <option value="bandung">Bandung</option>
              <option value="surabaya">Surabaya</option>
            </select>
            <svg className="w-3.5 h-3.5 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7" />
            </svg>
          </div>
        )}
      </div>

      <div className="h-[1px] bg-gray-100 w-full" />

      {/* 5. Seller Section */}
      <div>
        <button
          type="button"
          onClick={() => toggleSection('seller')}
          className="w-full flex items-center justify-between text-[13.5px] font-bold text-gray-900 mb-2.5"
        >
          <span>Seller</span>
          <svg
            className={`w-3.5 h-3.5 text-gray-400 transition-transform duration-200 ${openSections.seller ? '' : 'rotate-180'}`}
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 15l7-7 7 7" />
          </svg>
        </button>

        {openSections.seller && (
          <div className="relative">
            <select
              value={filters.seller}
              onChange={(e) => onFilterChange({ ...filters, seller: e.target.value })}
              className="w-full appearance-none bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] cursor-pointer"
            >
              <option value="">Semua Seller</option>
              <option value="rachel">Rachel Vennya</option>
              <option value="anya">Anya Geraldine</option>
              <option value="windah">Windah Basudara</option>
              <option value="cellos">Celloszx</option>
            </select>
            <svg className="w-3.5 h-3.5 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7" />
            </svg>
          </div>
        )}
      </div>

      <div className="h-[1px] bg-gray-100 w-full" />

      {/* 6. Lainnya Section */}
      <div>
        <button
          type="button"
          onClick={() => toggleSection('lainnya')}
          className="w-full flex items-center justify-between text-[13.5px] font-bold text-gray-900 mb-2.5"
        >
          <span>Lainnya</span>
          <svg
            className={`w-3.5 h-3.5 text-gray-400 transition-transform duration-200 ${openSections.lainnya ? '' : 'rotate-180'}`}
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 15l7-7 7 7" />
          </svg>
        </button>

        {openSections.lainnya && (
          <div className="space-y-2 pt-0.5">
            {[
              { id: 'garansi', label: 'Ada Garansi Keaslian' },
              { id: 'siap-kirim', label: 'Siap Dikirim' },
            ].map((item) => {
              const isChecked = filters.extra.includes(item.id);
              return (
                <label
                  key={item.id}
                  className="flex items-center gap-2 cursor-pointer select-none group text-xs text-gray-600"
                >
                  <input
                    type="checkbox"
                    checked={isChecked}
                    onChange={() => handleExtraToggle(item.id)}
                    className="sr-only"
                  />
                  <div
                    className={`w-3.5 h-3.5 rounded-[3px] border flex items-center justify-center transition-colors ${
                      isChecked
                        ? 'bg-[#4F26A6] border-[#4F26A6]'
                        : 'border-gray-300 bg-white group-hover:border-gray-400'
                    }`}
                  >
                    {isChecked && (
                      <svg className="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" strokeWidth="3" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12" />
                      </svg>
                    )}
                  </div>
                  <span className={isChecked ? 'text-gray-900 font-semibold' : ''}>
                    {item.label}
                  </span>
                </label>
              );
            })}
          </div>
        )}
      </div>

      {/* Reset Filter Button */}
      <div className="pt-2">
        <button
          type="button"
          onClick={onReset}
          className="w-full py-2 rounded-xl border border-gray-200 text-gray-700 font-semibold text-xs hover:bg-gray-50 hover:border-gray-300 transition-all flex items-center justify-center gap-2"
        >
          <svg className="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
          <span>Reset Filter</span>
        </button>
      </div>
    </aside>
  );
};
