@props(['category'])

@php
    $isModel = is_object($category);
    $href = $isModel ? ($category->href ?? '/belanja?kategori=' . $category->slug) : ($category['href'] ?? '/belanja?kategori=' . ($category['id'] ?? 'all'));
    $name = $isModel ? $category->name : ($category['name'] ?? '');
    $image = $isModel ? $category->image : ($category['image'] ?? '');
    $bgColor = $isModel ? ($category->bg_color ?? '#4F18C8') : ($category['bgColor'] ?? '#4F18C8');
@endphp

<a href="{{ $href }}" class="w-[140px] sm:w-[160px] lg:w-[180px] shrink-0 flex flex-col items-center group snap-start">
    <div
        class="w-full aspect-square rounded-2xl sm:rounded-3xl overflow-hidden shadow-xs group-hover:shadow-lg group-hover:-translate-y-1.5 transition-all duration-300 flex items-center justify-center relative"
        style="background-color: {{ $bgColor }};"
    >
        <img
            src="{{ $image }}"
            alt="{{ $name }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 block"
            decoding="async"
        />
    </div>
    <span class="text-xs sm:text-[14.5px] font-bold text-gray-800 mt-2.5 sm:mt-3 text-center group-hover:text-[#4F26A6] transition-colors line-clamp-1">
        {{ $name }}
    </span>
</a>
