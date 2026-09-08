{{-- Mirror of layouts/navbar.blade.php --}}
@include('layouts.navbar', [
    'activeTab' => $activeTab ?? 'beranda',
    'wishlistCount' => $wishlistCount ?? 0,
    'cartCount' => $cartCount ?? 0,
    'user' => $user ?? null,
])
