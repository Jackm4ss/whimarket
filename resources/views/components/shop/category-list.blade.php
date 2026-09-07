<div class="space-y-1.5 sm:space-y-2 pt-1">
    <template x-for="cat in [
        { id: 'all', name: 'Semua Kategori', icon: '<svg class=\'w-3.5 h-3.5\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\'><rect x=\'3\' y=\'3\' width=\'7\' height=\'7\' rx=\'1\'/><rect x=\'14\' y=\'3\' width=\'7\' height=\'7\' rx=\'1\'/><rect x=\'14\' y=\'14\' width=\'7\' height=\'7\' rx=\'1\'/><rect x=\'3\' y=\'14\' width=\'7\' height=\'7\' rx=\'1\'/></svg>' },
        { id: 'fashion', name: 'Fashion', icon: '<svg class=\'w-3.5 h-3.5\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\'><path d=\'M16 3l-4 2-4-2L3 6v4l3 1v10h12V11l3-1V6l-5-3z\'/></svg>' },
        { id: 'tas', name: 'Tas & Aksesoris', icon: '<svg class=\'w-3.5 h-3.5\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\'><path d=\'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z\'/></svg>' },
        { id: 'hobi', name: 'Hobi & Koleksi', icon: '<svg class=\'w-3.5 h-3.5\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\'><polygon points=\'12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2\'/></svg>' },
        { id: 'merchandise', name: 'Merchandise', icon: '<svg class=\'w-3.5 h-3.5\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\'><circle cx=\'12\' cy=\'8\' r=\'7\'/><polyline points=\'8.21 13.89 7 23 12 20 17 23 15.79 13.88\'/></svg>' },
        { id: 'elektronik', name: 'Elektronik', icon: '<svg class=\'w-3.5 h-3.5\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\'><rect x=\'2\' y=\'3\' width=\'20\' height=\'14\' rx=\'2\' ry=\'2\'/><line x1=\'8\' y1=\'21\' x2=\'16\' y2=\'21\'/><line x1=\'12\' y1=\'17\' x2=\'12\' y2=\'21\'/></svg>' },
        { id: 'kecantikan', name: 'Kecantikan', icon: '<svg class=\'w-3.5 h-3.5\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\'><path d=\'M12 2a4 4 0 0 0-4 4c0 3 4 8 4 8s4-5 4-8a4 4 0 0 0-4-4z\'/><circle cx=\'12\' cy=\'6\' r=\'1\'/></svg>' }
    ]" :key="cat.id">
        <button
            type="button"
            @click="selectedCategory = cat.id"
            class="w-full flex items-center gap-3 px-3.5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-[13px] font-medium transition-all"
            :class="selectedCategory === cat.id ? 'bg-[#EDE4FF] text-[#4F26A6] font-bold shadow-2xs' : 'text-gray-600 hover:text-[#4F26A6] hover:bg-gray-50'"
        >
            <span :class="selectedCategory === cat.id ? 'text-[#4F26A6]' : 'text-gray-400'" x-html="cat.icon"></span>
            <span x-text="cat.name"></span>
        </button>
    </template>
</div>
