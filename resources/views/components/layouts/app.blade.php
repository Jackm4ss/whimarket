<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="WhiMarket adalah marketplace terpercaya untuk belanja dan jual barang pre-loved serta merchandise eksklusif langsung dari kreator, artis, dan figur publik favoritmu.">
    <title>{{ $title ?? 'WhiMarket - Marketplace Pre-loved & Merchandise' }}</title>
    <link rel="icon" type="image/png" href="/assets/logo-whimarket.png">
    
    @if(request()->is('/'))
        <!-- Preload LCP Hero Product Image (Homepage Only) -->
        <link rel="preload" as="image" href="/assets/hero-product.png" fetchpriority="high">
    @endif
    <!-- Exact Google Fonts: Plus Jakarta Sans & Caveat with display=swap -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,700;1,800&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,700;1,800&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,700;1,800&display=swap" rel="stylesheet">
    </noscript>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col justify-between selection:bg-[#4F26A6] selection:text-white bg-[#FAF9FC] text-[#111827] font-sans antialiased" x-data="{ mobileMenuOpen: false }">

    @include('layouts.navbar', [
        'activeTab' => $activeTab ?? 'beranda',
        'wishlistCount' => $wishlistCount ?? 0,
        'cartCount' => $cartCount ?? 0,
        'user' => $user ?? null,
    ])

    <main class="flex-1">
        {{ $slot }}
    </main>

    @include('layouts.footer')
    <x-onboarding-modal />

</body>
</html>
