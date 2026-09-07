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
  });
  const toggleSection = (section: keyof typeof openSections) => {
    setOpenSections((prev) => ({ ...prev, [section]: !prev[section] }));
  };

  // Searchable location state
  const [isLocationDropdownOpen, setIsLocationDropdownOpen] = useState(false);
  const [locationSearchQuery, setLocationSearchQuery] = useState('');

  const locationsList = [
    { id: '', name: 'Semua Lokasi' },
    { id: 'jabodetabek', name: 'Jabodetabek' },
    { id: 'jakarta-selatan', name: 'Jakarta Selatan' },
    { id: 'jakarta-barat', name: 'Jakarta Barat' },
    { id: 'jakarta-pusat', name: 'Jakarta Pusat' },
    { id: 'jakarta-utara', name: 'Jakarta Utara' },
    { id: 'jakarta-timur', name: 'Jakarta Timur' },
    { id: 'bandung', name: 'Bandung' },
    { id: 'surabaya', name: 'Surabaya' },
    { id: 'yogyakarta', name: 'Yogyakarta' },
    { id: 'semarang', name: 'Semarang' },
    { id: 'medan', name: 'Medan' },
    { id: 'bali', name: 'Bali & Denpasar' },
    { id: 'makassar', name: 'Makassar' },
  ];

  const filteredLocations = locationsList.filter((loc) =>
    loc.name.toLowerCase().includes(locationSearchQuery.toLowerCase())
  );

  const selectedLocationName =
    locationsList.find((l) => l.id === filters.location)?.name || 'Pilih Lokasi';
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
    <aside className="w-full lg:w-[240px] xl:w-[255px] shrink-0 space-y-6 sm:space-y-7 bg-white rounded-2xl p-5 sm:p-6 border border-gray-100 shadow-[0_2px_12px_rgba(0,0,0,0.03)] self-start">
      {/* 1. Kategori Section */}
      <div>
        <button
          type="button"
          onClick={() => toggleSection('kategori')}
          className="w-full flex items-center justify-between text-[14px] sm:text-[14.5px] font-bold text-gray-900 mb-3 sm:mb-3.5"
        >
          <span>Kategori</span>
          <svg
            className={`w-4 h-4 text-gray-400 transition-transform duration-200 ${openSections.kategori ? '' : 'rotate-180'}`}
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 15l7-7 7 7" />
          </svg>
        </button>

        {openSections.kategori && (
          <div className="space-y-1.5 sm:space-y-2 pt-1">
            {categories.map((cat) => {
              const isActive = filters.category === cat.id;
              return (
                <button
                  key={cat.id}
                  type="button"
                  onClick={() => onFilterChange({ ...filters, category: cat.id })}
                  className={`w-full flex items-center gap-3 px-3.5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-[13px] font-medium transition-all ${
                    isActive
                      ? 'bg-[#EDE4FF] text-[#4F26A6] font-bold shadow-2xs'
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
          className="w-full flex items-center justify-between text-[14px] sm:text-[14.5px] font-bold text-gray-900 mb-3 sm:mb-3.5"
        >
          <span>Harga</span>
          <svg
            className={`w-4 h-4 text-gray-400 transition-transform duration-200 ${openSections.harga ? '' : 'rotate-180'}`}
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 15l7-7 7 7" />
          </svg>
        </button>

        {openSections.harga && (
          <div className="pt-1.5 space-y-3">
            {/* Real Interactive Dual-Thumb Range Slider */}
            <div className="relative w-full flex items-center h-6">
              {/* Background Track */}
              <div className="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                <div
                  className="h-full bg-[#4F26A6] rounded-full"
                  style={{
                    marginLeft: `${(filters.priceRange[0] / 50000000) * 100}%`,
                    width: `${((filters.priceRange[1] - filters.priceRange[0]) / 50000000) * 100}%`,
                  }}
                />
              </div>

              {/* Min Input Slider */}
              <input
                type="range"
                min="0"
                max="50000000"
                step="500000"
                value={filters.priceRange[0]}
                onChange={(e) => {
                  const val = Math.min(Number(e.target.value), filters.priceRange[1] - 500000);
                  onFilterChange({ ...filters, priceRange: [val, filters.priceRange[1]] });
                }}
                className="absolute inset-0 w-full appearance-none bg-transparent pointer-events-auto cursor-pointer accent-[#4F26A6] opacity-0 z-20"
              />

              {/* Max Input Slider */}
              <input
                type="range"
                min="0"
                max="50000000"
                step="500000"
                value={filters.priceRange[1]}
                onChange={(e) => {
                  const val = Math.max(Number(e.target.value), filters.priceRange[0] + 500000);
                  onFilterChange({ ...filters, priceRange: [filters.priceRange[0], val] });
                }}
                className="absolute inset-0 w-full appearance-none bg-transparent pointer-events-auto cursor-pointer accent-[#4F26A6] opacity-0 z-30"
              />

              {/* Visual Draggable Thumb Knobs */}
              <div
                className="absolute w-4 h-4 bg-[#4F26A6] rounded-full ring-2 ring-white shadow-md pointer-events-none transition-transform -translate-x-1/2 z-10"
                style={{ left: `${(filters.priceRange[0] / 50000000) * 100}%` }}
              />
              <div
                className="absolute w-4 h-4 bg-[#4F26A6] rounded-full ring-2 ring-white shadow-md pointer-events-none transition-transform -translate-x-1/2 z-10"
                style={{ left: `${(filters.priceRange[1] / 50000000) * 100}%` }}
              />
            </div>

            {/* Dynamic Interactive Price Labels */}
            <div className="flex items-center justify-between text-xs text-gray-500 font-semibold pt-0.5">
              <span>Rp {filters.priceRange[0].toLocaleString('id-ID')}</span>
              <span>Rp {filters.priceRange[1].toLocaleString('id-ID')}</span>
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
          className="w-full flex items-center justify-between text-[14px] sm:text-[14.5px] font-bold text-gray-900 mb-3 sm:mb-3.5"
        >
          <span>Kondisi</span>
          <svg
            className={`w-4 h-4 text-gray-400 transition-transform duration-200 ${openSections.kondisi ? '' : 'rotate-180'}`}
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 15l7-7 7 7" />
          </svg>
        </button>

        {openSections.kondisi && (
          <div className="space-y-3 sm:space-y-3.5 pt-1">
            {conditions.map((item) => {
              const isChecked = item.id === 'all'
                ? filters.condition.includes('all')
                : filters.condition.includes(item.id);

              return (
                <label
                  key={item.id}
                  className="flex items-center gap-3 cursor-pointer select-none group text-xs sm:text-[13px] text-gray-600 hover:text-gray-900 py-0.5"
                >
                  <input
                    type="checkbox"
                    checked={isChecked}
                    onChange={() => handleConditionToggle(item.id)}
                    className="sr-only"
                  />
                  <div
                    className={`w-4 h-4 sm:w-4.5 sm:h-4.5 rounded-[4px] border flex items-center justify-center transition-colors shrink-0 ${
                      isChecked
                        ? 'bg-[#4F26A6] border-[#4F26A6]'
                        : 'border-gray-300 bg-white group-hover:border-gray-400'
                    }`}
                  >
                    {isChecked && (
                      <svg className="w-3 h-3 text-white" fill="none" stroke="currentColor" strokeWidth="3" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12" />
                      </svg>
                    )}
                  </div>
                  <span className={isChecked ? 'text-gray-900 font-semibold' : 'text-gray-700 font-medium'}>
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
          className="w-full flex items-center justify-between text-[14px] sm:text-[14.5px] font-bold text-gray-900 mb-3 sm:mb-3.5"
        >
          <span>Lokasi</span>
          <svg
            className={`w-4 h-4 text-gray-400 transition-transform duration-200 ${openSections.lokasi ? '' : 'rotate-180'}`}
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 15l7-7 7 7" />
          </svg>
        </button>

        {openSections.lokasi && (
          <div className="relative pt-1">
            {/* Trigger Button */}
            <button
              type="button"
              onClick={() => setIsLocationDropdownOpen(!isLocationDropdownOpen)}
              className="w-full flex items-center justify-between bg-[#FAFAFC] border border-gray-200 rounded-xl px-4 py-3 text-xs sm:text-[13px] text-gray-700 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] cursor-pointer transition-all"
            >
              <span className={filters.location ? 'text-gray-900 font-semibold truncate' : 'text-gray-500 truncate'}>
                {selectedLocationName}
              </span>
              <svg
                className={`w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0 ${isLocationDropdownOpen ? 'rotate-180 text-[#4F26A6]' : ''}`}
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            {/* Searchable Dropdown Floating Panel */}
            {isLocationDropdownOpen && (
              <div className="absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-100 rounded-2xl shadow-[0_12px_36px_rgba(0,0,0,0.12)] p-2.5 z-50">
                {/* Search Input Box */}
                <div className="relative mb-2">
                  <input
                    type="text"
                    autoFocus
                    placeholder="Cari kota atau daerah..."
                    value={locationSearchQuery}
                    onChange={(e) => setLocationSearchQuery(e.target.value)}
                    className="w-full h-8 pl-7 pr-2 rounded-lg bg-gray-50 border border-gray-200 text-xs text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#4F26A6] focus:border-[#4F26A6]"
                  />
                  <svg className="w-3.5 h-3.5 text-gray-400 absolute left-2 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8" /><line x1="21" y1="21" x2="16.65" y2="16.65" />
                  </svg>
                </div>

                {/* Filtered Location List Options */}
                <div className="max-h-48 overflow-y-auto space-y-0.5 divide-y divide-gray-50">
                  {filteredLocations.length > 0 ? (
                    filteredLocations.map((loc) => {
                      const isSelected = filters.location === loc.id;
                      return (
                        <button
                          key={loc.id || 'all'}
                          type="button"
                          onClick={() => {
                            onFilterChange({ ...filters, location: loc.id });
                            setIsLocationDropdownOpen(false);
                            setLocationSearchQuery('');
                          }}
                          className={`w-full text-left px-2.5 py-1.5 rounded-lg text-xs transition-colors flex items-center justify-between ${
                            isSelected
                              ? 'bg-[#F4EEFF] text-[#4F26A6] font-bold'
                              : 'text-gray-700 hover:bg-gray-50 hover:text-[#4F26A6]'
                          }`}
                        >
                          <span>{loc.name}</span>
                          {isSelected && (
                            <svg className="w-3.5 h-3.5 text-[#4F26A6]" fill="none" stroke="currentColor" strokeWidth="2.5" viewBox="0 0 24 24">
                              <polyline points="20 6 9 17 4 12" />
                            </svg>
                          )}
                        </button>
                      );
                    })
                  ) : (
                    <div className="py-3 text-center text-xs text-gray-400">
                      Lokasi tidak ditemukan
                    </div>
                  )}
                </div>
              </div>
            )}
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
