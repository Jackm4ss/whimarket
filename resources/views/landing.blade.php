<x-layouts.app title="WhiMarket - Marketplace Pre-loved & Merchandise" activeTab="beranda">
    <x-home.hero-section />
    <x-home.feature-bar />
    <x-home.category-section :categories="$categories" />
    <x-home.products-section :products="$products" />
    <x-home.sellers-section :sellers="$sellers" />
    <x-home.creator-banner />
    <x-home.steps-section :steps="$steps" />
    <x-home.newsletter-section />
</x-layouts.app>
