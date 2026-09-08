<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Buyer\CartController;
use App\Http\Controllers\Buyer\CatalogController;
use App\Http\Controllers\Buyer\CheckoutController;
use App\Http\Controllers\Buyer\DisputeController;
use App\Http\Controllers\Buyer\OnboardingController;
use App\Http\Controllers\Buyer\OrderController;
use App\Http\Controllers\Buyer\SearchController;
use App\Http\Controllers\Buyer\WishlistController;
use App\Http\Controllers\Seller\SellerPortalController;
use App\Http\Controllers\Seller\SellerProductController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');
Route::get('/auth/dev-login/{role?}', [GoogleAuthController::class, 'devLogin'])->name('auth.dev-login');

// Onboarding Routes
Route::middleware('auth')->group(function () {
    Route::post('/onboarding/address', [OnboardingController::class, 'saveAddress'])->name('onboarding.address');
    Route::post('/onboarding/skip', [OnboardingController::class, 'skip'])->name('onboarding.skip');
});

// Catalog Routes
Route::get('/', [CatalogController::class, 'home'])->name('home');
Route::get('/belanja', [CatalogController::class, 'shop'])->name('shop');
Route::get('/produk/{slug}', [CatalogController::class, 'productDetail'])->name('product.detail');
Route::get('/seller', [CatalogController::class, 'sellerDirectory'])->name('seller.directory');
Route::get('/seller/{username}', [CatalogController::class, 'sellerProfile'])->name('seller.profile')->where('username', '@[A-Za-z0-9_.-]+');
Route::get('/api/search-suggest', [SearchController::class, 'suggest'])->name('search.suggest');

// Wishlist Routes
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/toggle/{productId}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

// Cart Routes
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/keranjang/item/{id}', [CartController::class, 'updateItem'])->name('cart.update');
Route::delete('/keranjang/item/{id}', [CartController::class, 'removeItem'])->name('cart.remove');

// Checkout & Payment Routes
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/pembayaran/{orderNumber}', [CheckoutController::class, 'showPayment'])->name('payment.show');
    Route::post('/pembayaran/{orderNumber}/upload', [CheckoutController::class, 'uploadProof'])->name('payment.upload');

    // Buyer Order Management
    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/pesanan/{orderNumber}/terima', [OrderController::class, 'confirmDelivered'])->name('orders.confirm_delivered');
    Route::post('/pesanan/{orderNumber}/selesai', [OrderController::class, 'completeOrder'])->name('orders.complete');

    // Buyer Dispute
    Route::get('/pesanan/{orderNumber}/komplain', [DisputeController::class, 'create'])->name('dispute.create');
    Route::post('/pesanan/{orderNumber}/komplain', [DisputeController::class, 'store'])->name('dispute.store');

    // Seller Portal
    Route::get('/seller/register', [SellerPortalController::class, 'showRegister'])->name('seller.register');
    Route::post('/seller/register', [SellerPortalController::class, 'register'])->name('seller.register.submit');

    // Seller Protected Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/seller/dashboard', [SellerPortalController::class, 'dashboard'])->name('seller.dashboard');
        Route::get('/seller/orders', [SellerPortalController::class, 'orders'])->name('seller.orders.index');
        Route::post('/seller/orders/{id}/fulfill', [SellerPortalController::class, 'fulfill'])->name('seller.orders.fulfill');
        Route::post('/seller/orders/{id}/claim-delivered', [SellerPortalController::class, 'claimDelivered'])->name('seller.orders.claim_delivered');
        Route::get('/seller/disputes/{id}', [SellerPortalController::class, 'disputeDetail'])->name('seller.disputes.show');
        Route::post('/seller/disputes/{id}/respond', [SellerPortalController::class, 'disputeRespond'])->name('seller.disputes.respond');

        // Seller Product CRUD
        Route::get('/seller/products', [SellerProductController::class, 'index'])->name('seller.products.index');
        Route::get('/seller/products/create', [SellerProductController::class, 'create'])->name('seller.products.create');
        Route::post('/seller/products', [SellerProductController::class, 'store'])->name('seller.products.store');
        Route::get('/seller/products/{id}/edit', [SellerProductController::class, 'edit'])->name('seller.products.edit');
        Route::put('/seller/products/{id}', [SellerProductController::class, 'update'])->name('seller.products.update');
        Route::delete('/seller/products/{id}', [SellerProductController::class, 'destroy'])->name('seller.products.destroy');
    });
});

// Informasi & Bantuan Pages
Route::get('/cara-jual', function () {
    return view('info-page', [
        'title' => 'Cara Jual di WhiMarket',
        'image' => '/assets/info/cara-jual.png',
        'description' => 'Panduan praktis dan langkah mudah untuk mulai menjual barang pre-loved dan merchandise eksklusif kamu langsung kepada para penggemar.',
        'sections' => [
            ['heading' => 'Daftarkan Akun Seller Terverifikasi', 'content' => 'Buat akun dan hubungkan profil media sosial aktifmu (Instagram atau TikTok) untuk proses verifikasi identitas resmi.'],
            ['heading' => 'Unggah Foto & Detail Produk', 'content' => 'Foto barang pre-loved dengan pencahayaan jelas, cantumkan kondisi barang (Seperti Baru, Sangat Baik, atau Baik), dan tetapkan harga jual.'],
            ['heading' => 'Pengiriman & Pencairan Saldo Otomatis', 'content' => 'Kirim barang setelah pesanan masuk. Dana penjualan aman diteruskan langsung ke rekeningmu melalui escrow WhiMarket.'],
        ],
    ]);
});

Route::get('/keuntungan', function () {
    return view('info-page', [
        'title' => 'Keuntungan Menjadi Seller',
        'image' => '/assets/info/keuntungan.png',
        'description' => 'Berbagai manfaat dan fasilitas eksklusif bagi figur publik, selebgram, dan kreator yang bergabung bersama platform WhiMarket.',
        'sections' => [
            ['heading' => 'Audiens Tertarget & Penggemar Setia', 'content' => 'Barang pre-loved dan koleksi milikmu langsung ditemukan oleh audiens yang menghargai nilai cerita dan keaslian produk.'],
            ['heading' => 'Sistem Rekening Bersama (Escrow)', 'content' => 'Bebas dari kekhawatiran penipuan. Dana pembeli diamankan terlebih dahulu oleh sistem sebelum paket kamu kirimkan.'],
            ['heading' => 'Dashboard Manajemen Praktis', 'content' => 'Pantau performa penjualan, kelola stok barang, cetak label resi ekspedisi, dan tarik dana ke bank secara instan.'],
        ],
    ]);
});

Route::get('/panduan-seller', function () {
    return view('info-page', [
        'title' => 'Panduan Seller',
        'image' => '/assets/info/panduan-seller.png',
        'description' => 'Standar kualitas, tata cara pengemasan terstandarisasi, dan etika berjualan untuk menjaga kepuasan pembeli.',
        'sections' => [
            ['heading' => 'Kejujuran Deskripsi Kondisi', 'content' => 'Sertakan foto sudut detail, minus pemakaian jika ada, dan deskripsi riwayat barang secara jujur dan terbuka.'],
            ['heading' => 'Standar Pengemasan Paket Aman', 'content' => 'Gunakan bubble wrap berlapis dan kardus tebal untuk menjamin barang sampai ke tangan pembeli tanpa cacat.'],
            ['heading' => 'Kecepatan Proses Pengiriman', 'content' => 'Kirimkan paket maksimal 2x24 jam hari kerja setelah pesanan terbayar untuk mempertahankan rating bintang 5 tokomu.'],
        ],
    ]);
});

Route::get('/gabung-seller', function () {
    return view('info-page', [
        'title' => 'Gabung sebagai Seller',
        'image' => '/assets/info/gabung-seller.png',
        'description' => 'Mulai perjalananmu membuka toko pre-loved dan merchandise resmi dengan verifikasi badge centang ungu di WhiMarket.',
        'sections' => [
            ['heading' => 'Kriteria Kurasi Pendaftaran', 'content' => 'Terbuka bagi public figure, content creator, kolektor resmi, dan brand merchandise berlisensi otentik.'],
            ['heading' => 'Verifikasi Cepat 1x24 Jam', 'content' => 'Tim kurasi WhiMarket akan mereview permohonan tokomu dengan cepat dan memastikan integrasi profil media sosial.'],
            ['heading' => 'Toko Resmi Siap Menerima Order', 'content' => 'Dapatkan halaman toko personal (`/seller/@username`) dengan link bio siap dibagikan ke followers di media sosial.'],
        ],
    ]);
});

Route::get('/faq', function () {
    return view('info-page', [
        'title' => 'Pertanyaan yang Sering Diajukan (FAQ)',
        'image' => '/assets/info/faq.png',
        'description' => 'Jawaban lengkap mengenai jaminan keaslian barang, mekanisme pembayaran rekening bersama, dan tata cara pengembalian.',
        'sections' => [
            ['heading' => 'Apakah barang di WhiMarket dijamin 100% original?', 'content' => 'Ya, setiap penjual melewati proses kurasi identitas ketat dan barang pre-loved diverifikasi otentisitasnya oleh tim kami.'],
            ['heading' => 'Bagaimana jika pesanan yang diterima rusak atau tidak sesuai?', 'content' => 'Ajukan klaim perlindungan pembeli dalam 2x24 jam dengan bukti video unboxing untuk garansi refund dana penuh.'],
            ['heading' => 'Opsi pembayaran apa saja yang tersedia?', 'content' => 'Tersedia QRIS instan, Virtual Account semua bank utama (BCA, Mandiri, BNI, BRI), Kartu Kredit, dan dompet digital.'],
        ],
    ]);
});
Route::get('/bantuan', function () {
    return redirect('/faq');
});

Route::get('/kebijakan-privasi', function () {
    return view('info-page', [
        'title' => 'Kebijakan Privasi',
        'image' => '/assets/info/kebijakan-privasi.png',
        'description' => 'Komitmen tertinggi kami dalam menjaga kerahasiaan data pribadi, riwayat transaksi, dan keamanan akun kamu di WhiMarket.',
        'sections' => [
            ['heading' => 'Enkripsi Data Standar Perbankan', 'content' => 'Seluruh transmisi data pribadi dan informasi pembayaran dilindungi dengan enkripsi SSL/TLS 256-bit kelas industri.'],
            ['heading' => 'Kerahasiaan Alamat & Kontak', 'content' => 'Nomor kontak dan alamat rumah kamu hanya diteruskan ke kurir logistik demi keperluan pengiriman barang pesanan.'],
            ['heading' => 'Hak Kendali Informasi Akun', 'content' => 'Kamu memiliki kebebasan penuh untuk meninjau, memperbarui preferensi notifikasi, atau meminta penonaktifan akun.'],
        ],
    ]);
});

Route::get('/syarat-ketentuan', function () {
    return view('info-page', [
        'title' => 'Syarat & Ketentuan',
        'image' => '/assets/info/syarat-ketentuan.png',
        'description' => 'Pedoman hukum dan syarat kesepakatan penggunaan layanan ekosistem marketplace WhiMarket untuk seluruh pihak.',
        'sections' => [
            ['heading' => 'Kewajiban & Hak Pembeli', 'content' => 'Pembeli berhak menerima barang sesuai deskripsi visual dan wajib menyelesaikan pembayaran pada tenggat yang ditentukan.'],
            ['heading' => 'Larangan Penjualan Produk KW/Palsu', 'content' => 'Pelanggaran terhadap keaslian barang berakibat pada pemblokiran akun permanen dan penyitaan dana jaminan toko.'],
            ['heading' => 'Pusat Mediasi Resolusi Transaksi', 'content' => 'Setiap perselisihan antara penjual dan pembeli ditengahi secara adil oleh mediator resmi tim sengketa WhiMarket.'],
        ],
    ]);
});

Route::get('/hubungi-kami', function () {
    return view('info-page', [
        'title' => 'Hubungi Kami',
        'image' => '/assets/info/hubungi-kami.png',
        'description' => 'Punya pertanyaan atau butuh bantuan terkait transaksimu? Tim Customer Happiness WhiMarket siap melayani kamu dengan sigap.',
        'sections' => [
            ['heading' => 'Email Customer Care', 'content' => 'Kirim pertanyaan, feedback, atau laporan kendala transaksi langsung ke email resmi halo@whimarket.com.'],
            ['heading' => 'WhatsApp Chat Live Support', 'content' => 'Layanan bantuan cepat interaktif setiap hari pukul 08.00 - 22.00 WIB via nomor resmi +62 811-2345-6789.'],
            ['heading' => 'Kantor Pusat WhiMarket', 'content' => 'WhiMarket Tower Lt. 15, Jl. Jend. Sudirman Kav. 52-53, Kawasan SCBD, Jakarta Selatan, DKI Jakarta 12190.'],
        ],
    ]);
});
