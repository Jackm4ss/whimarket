import React from 'react';

interface PaginationProps {
  currentPage: number;
  totalPages?: number;
  onPageChange?: (page: number) => void;
}

export const Pagination: React.FC<PaginationProps> = ({
  currentPage = 1,
  onPageChange,
}) => {
  return (
    <div className="flex items-center justify-center gap-1.5 sm:gap-2 pt-10 pb-4">
      {/* Prev Button */}
      <button
        type="button"
        disabled={currentPage <= 1}
        onClick={() => onPageChange?.(currentPage - 1)}
        className="w-9 h-9 rounded-xl border border-gray-200 bg-white flex items-center justify-center text-gray-400 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-gray-50 transition-colors"
      >
        <svg className="w-4 h-4" fill="none" stroke="currentColor" strokeWidth="2.5" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
      </button>

      {/* Pages: 1, 2, 3, 4, 5, ..., 62 */}
      {[1, 2, 3, 4, 5].map((page) => (
        <button
          key={page}
          type="button"
          onClick={() => onPageChange?.(page)}
          className={`w-9 h-9 rounded-xl text-xs sm:text-[13.5px] font-bold flex items-center justify-center transition-all ${
            page === currentPage
              ? 'bg-[#4F26A6] text-white shadow-xs'
              : 'bg-white border border-gray-100 text-gray-700 hover:bg-gray-50 hover:border-gray-200'
          }`}
        >
          {page}
        </button>
      ))}

      <span className="px-1 text-gray-400 text-xs font-semibold select-none">...</span>

      <button
        type="button"
        onClick={() => onPageChange?.(62)}
        className="w-9 h-9 rounded-xl bg-white border border-gray-100 text-gray-700 text-xs sm:text-[13.5px] font-bold flex items-center justify-center hover:bg-gray-50 hover:border-gray-200 transition-all"
      >
        62
      </button>

      {/* Next Button */}
      <button
        type="button"
        onClick={() => onPageChange?.(currentPage + 1)}
        className="w-9 h-9 rounded-xl border border-gray-200 bg-white flex items-center justify-center text-gray-700 hover:bg-gray-50 transition-colors"
      >
        <svg className="w-4 h-4" fill="none" stroke="currentColor" strokeWidth="2.5" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7" />
        </svg>
      </button>
    </div>
  );
};
