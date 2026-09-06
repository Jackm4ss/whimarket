import React from 'react';

export interface FilterState {
  category: string;
  condition: string[];
  priceMin: string;
  priceMax: string;
  location: string;
  shipping: string[];
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
  const categories = [
    { id: 'all', name: 'Semua Kategori', count: '1,240', icon: (
      <svg className="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
        <rect x="3" y="3" width="7" height="7" rx="1" />
        <rect x="14" y="3" width="7" height="7" rx="1" />
        <rect x="14" y="14" width="7" height="7" rx="1" />
        <rect x="3" y="14" width="7" height="7" rx="1" />
      </svg>
    )},
    { id: 'fashion', name: 'Fashion', count: '320', icon: (
      <svg className="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
        <path d="M16 3l-4 2-4-2L3 6v4l3 1v10h12V11l3-1V6l-5-3z"/>
      </svg>
    )},
    { id: 'tas', name: 'Tas & Aksesoris', count: '184', icon: (
      <svg className="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
        <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
      </svg>
    )},
    { id: 'hobi', name: 'Hobi & Koleksi', count: '98', icon: (
      <svg className="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
      </svg>
    )},
    { id: 'merchandise', name: 'Merchandise', count: '210', icon: (
      <svg className="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
        <circle cx="12" cy="8" r="7"/>
        <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>
      </svg>
    )},
    { id: 'elektronik', name: 'Elektronik', count: '156', icon: (
      <svg className="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
        <line x1="8" y1="21" x2="16" y2="21"/>
        <line x1="12" y1="17" x2="12" y2="21"/>
      </svg>
    )},
    { id: 'kecantikan', name: 'Kecantikan', count: '72', icon: (
      <svg className="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
        <path d="M12 2a4 4 0 0 0-4 4c0 3 4 8 4 8s4-5 4-8a4 4 0 0 0-4-4z"/>
        <circle cx="12" cy="6" r="1"/>
      </svg>
    )},
  ];

  const conditions = [
    { id: 'all', label: 'Semua Kondisi', count: null },
    { id: 'Like New', label: 'Seperti Baru', count: '432' },
    { id: 'Good', label: 'Sangat Baik', count: '528' },
    { id: 'Fair', label: 'Baik', count: '240' },
    { id: 'Poor', label: 'Cukup', count: '40' },
  ];

  const shippings = [
    { id: 'all', label: 'Semua Metode' },
    { id: 'instant', label: 'Instant (Same Day)' },
    { id: 'reguler', label: 'Reguler' },
    { id: 'hemat', label: 'Hemat' },
  ];

  const handleCategorySelect = (id: string) => {
    onFilterChange({ ...filters, category: id });
  };

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

  const handleShippingToggle = (id: string) => {
    if (id === 'all') {
      onFilterChange({ ...filters, shipping: ['all'] });
      return;
    }
    const current = filters.shipping.filter(s => s !== 'all');
    const updated = current.includes(id)
      ? current.filter(s => s !== id)
      : [...current, id];
    onFilterChange({ ...filters, shipping: updated.length === 0 ? ['all'] : updated });
  };

  return (
    <aside className="w-full lg:w-[260px] xl:w-[280px] shrink-0 space-y-7 bg-white rounded-2xl p-5 border border-gray-100 shadow-sm self-start">
      {/* 1. Kategori */}
      <div>
        <h3 className="text-[15px] font-bold text-gray-900 mb-3.5 tracking-tight">Kategori</h3>
        <div className="space-y-1">
          {categories.map((cat) => {
            const isActive = filters.category === cat.id;
            return (
              <button
                key={cat.id}
                type="button"
                onClick={() => handleCategorySelect(cat.id)}
                className={`w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs sm:text-[13px] font-medium transition-all ${
                  isActive
                    ? 'bg-[#4F26A6] text-white shadow-xs font-semibold'
                    : 'text-gray-600 hover:text-[#4F26A6] hover:bg-[#F3EEFF]'
                }`}
              >
                <div className="flex items-center gap-2.5 truncate">
                  <span className={isActive ? 'text-white' : 'text-gray-400 group-hover:text-[#4F26A6]'}>
                    {cat.icon}
                  </span>
                  <span className="truncate">{cat.name}</span>
                </div>
                <span className={`text-[11px] ${isActive ? 'text-white/80' : 'text-gray-400'}`}>
                  {cat.count}
                </span>
              </button>
            );
          })}
        </div>
      </div>

      <div className="h-[1px] bg-gray-100 w-full" />

      {/* 2. Kondisi */}
      <div>
        <h3 className="text-[15px] font-bold text-gray-900 mb-3.5 tracking-tight">Kondisi</h3>
        <div className="space-y-2.5">
          {conditions.map((item) => {
            const isChecked = item.id === 'all'
              ? filters.condition.includes('all')
              : filters.condition.includes(item.id);

            return (
              <label
                key={item.id}
                className="flex items-center justify-between cursor-pointer select-none group text-xs sm:text-[13px]"
              >
                <div className="flex items-center gap-2.5">
                  <input
                    type="checkbox"
                    checked={isChecked}
                    onChange={() => handleConditionToggle(item.id)}
                    className="sr-only"
                  />
                  <div
                    className={`w-4 h-4 rounded-[4px] border flex items-center justify-center transition-colors ${
                      isChecked
                        ? 'bg-[#4F26A6] border-[#4F26A6]'
                        : 'border-gray-300 group-hover:border-gray-400 bg-white'
                    }`}
                  >
                    {isChecked && (
                      <svg className="w-3 h-3 text-white" fill="none" stroke="currentColor" strokeWidth="3" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12" />
                      </svg>
                    )}
                  </div>
                  <span className={`transition-colors ${isChecked ? 'text-gray-900 font-medium' : 'text-gray-600'}`}>
                    {item.label}
                  </span>
                </div>
                {item.count && (
                  <span className="text-[11px] text-gray-400">{item.count}</span>
                )}
              </label>
            );
          })}
        </div>
      </div>

      <div className="h-[1px] bg-gray-100 w-full" />

      {/* 3. Rentang Harga */}
      <div>
        <h3 className="text-[15px] font-bold text-gray-900 mb-3.5 tracking-tight">Rentang Harga</h3>
        <div className="flex items-center gap-2 mb-3">
          <div className="flex-1 flex items-center bg-[#F9FAFB] border border-gray-200 rounded-xl px-2.5 py-1.5 focus-within:border-[#4F26A6] focus-within:bg-white transition-colors">
            <span className="text-xs text-gray-400 font-semibold mr-1">Rp</span>
            <input
              type="text"
              placeholder="Min"
              value={filters.priceMin}
              onChange={(e) => onFilterChange({ ...filters, priceMin: e.target.value })}
              className="w-full bg-transparent text-xs text-gray-800 placeholder-gray-400 focus:outline-none"
            />
          </div>
          <span className="text-gray-400">-</span>
          <div className="flex-1 flex items-center bg-[#F9FAFB] border border-gray-200 rounded-xl px-2.5 py-1.5 focus-within:border-[#4F26A6] focus-within:bg-white transition-colors">
            <span className="text-xs text-gray-400 font-semibold mr-1">Rp</span>
            <input
              type="text"
              placeholder="Maks"
              value={filters.priceMax}
              onChange={(e) => onFilterChange({ ...filters, priceMax: e.target.value })}
              className="w-full bg-transparent text-xs text-gray-800 placeholder-gray-400 focus:outline-none"
            />
          </div>
        </div>

        {/* Purple Range Slider Graphic */}
        <div className="relative w-full h-4 flex items-center">
          <div className="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
            <div className="h-full bg-[#4F26A6] rounded-full w-[85%] ml-[10%]" />
          </div>
          <div className="absolute left-[10%] w-4 h-4 bg-[#4F26A6] rounded-full ring-2 ring-white shadow-sm cursor-pointer" />
          <div className="absolute right-[5%] w-4 h-4 bg-[#4F26A6] rounded-full ring-2 ring-white shadow-sm cursor-pointer" />
        </div>
      </div>

      <div className="h-[1px] bg-gray-100 w-full" />

      {/* 4. Lokasi Penjual */}
      <div>
        <h3 className="text-[15px] font-bold text-gray-900 mb-2 tracking-tight">Lokasi Penjual</h3>
        <div className="relative">
          <select
            value={filters.location}
            onChange={(e) => onFilterChange({ ...filters, location: e.target.value })}
            className="w-full appearance-none bg-[#F9FAFB] border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-[13px] text-gray-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4F26A6]/20 focus:border-[#4F26A6] cursor-pointer"
          >
            <option value="">Semua Lokasi</option>
            <option value="jakarta">Jabodetabek</option>
            <option value="bandung">Bandung</option>
            <option value="surabaya">Surabaya</option>
            <option value="yogyakarta">Yogyakarta</option>
          </select>
          <svg className="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7" />
          </svg>
        </div>
      </div>

      <div className="h-[1px] bg-gray-100 w-full" />

      {/* 5. Pengiriman */}
      <div>
        <h3 className="text-[15px] font-bold text-gray-900 mb-3.5 tracking-tight">Pengiriman</h3>
        <div className="space-y-2.5">
          {shippings.map((ship) => {
            const isChecked = ship.id === 'all'
              ? filters.shipping.includes('all')
              : filters.shipping.includes(ship.id);

            return (
              <label
                key={ship.id}
                className="flex items-center gap-2.5 cursor-pointer select-none group text-xs sm:text-[13px]"
              >
                <input
                  type="checkbox"
                  checked={isChecked}
                  onChange={() => handleShippingToggle(ship.id)}
                  className="sr-only"
                />
                <div
                  className={`w-4 h-4 rounded-[4px] border flex items-center justify-center transition-colors ${
                    isChecked
                      ? 'bg-[#4F26A6] border-[#4F26A6]'
                      : 'border-gray-300 group-hover:border-gray-400 bg-white'
                  }`}
                >
                  {isChecked && (
                    <svg className="w-3 h-3 text-white" fill="none" stroke="currentColor" strokeWidth="3" viewBox="0 0 24 24">
                      <polyline points="20 6 9 17 4 12" />
                    </svg>
                  )}
                </div>
                <span className={`transition-colors ${isChecked ? 'text-gray-900 font-medium' : 'text-gray-600'}`}>
                  {ship.label}
                </span>
              </label>
            );
          })}
        </div>
      </div>

      {/* Reset Filter Button */}
      <div className="pt-2">
        <button
          type="button"
          onClick={onReset}
          className="w-full py-2.5 rounded-xl border border-gray-200 text-gray-700 font-semibold text-xs sm:text-[13px] hover:bg-gray-50 hover:border-gray-300 transition-all flex items-center justify-center gap-2"
        >
          <svg className="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" strokeWidth="2.2" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
          <span>Reset Filter</span>
        </button>
      </div>
    </aside>
  );
};
