<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'WhiMarket - Marketplace Pre-loved & Merchandise' }}</title>
    <link rel="icon" type="image/png" href="/assets/logo-whimarket.png">
    
    <!-- Exact Google Fonts: Plus Jakarta Sans & Caveat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,700;1,800&display=swap" rel="stylesheet">
    
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

</body>
</html>
