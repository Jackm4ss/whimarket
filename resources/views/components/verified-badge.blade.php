@props(['size' => 'sm', 'className' => ''])

@php
    $sizeClass = match($size) {
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
        default => 'w-3.5 h-3.5 sm:w-4 sm:h-4',
    };
@endphp

<span class="inline-flex items-center shrink-0 {{ $className }}" title="Verified Creator">
    <svg class="{{ $sizeClass }} text-[#4F26A6] fill-[#4F26A6]" viewBox="0 0 24 24">
        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" fill="currentColor" />
    </svg>
</span>
