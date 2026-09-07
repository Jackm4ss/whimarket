@props(['category'])

<a href="{{ $category['href'] }}" class="flex flex-col items-center group">
    <div
        class="w-full aspect-square rounded-2xl sm:rounded-3xl overflow-hidden p-3.5 sm:p-4 shadow-xs group-hover:shadow-lg group-hover:-translate-y-1.5 transition-all duration-300 flex items-center justify-center"
        style="background-color: {{ $category['bgColor'] }};"
    >
        <img
            src="{{ $category['image'] }}"
            alt="{{ $category['name'] }}"
            class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
            loading="lazy"
        />
    </div>
    <span class="text-xs sm:text-[14.5px] font-bold text-gray-800 mt-2.5 sm:mt-3 text-center group-hover:text-[#4F26A6] transition-colors line-clamp-1">
        {{ $category['name'] }}
    </span>
</a>
