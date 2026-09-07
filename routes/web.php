<?php

use Illuminate\Support\Facades\Route;

use App\Support\MarketData;

Route::get('/', function () {
    return view('landing', [
        'categories' => MarketData::categories(),
        'products' => MarketData::landingProducts(),
        'sellers' => MarketData::sellers(),
        'steps' => MarketData::steps(),
    ]);
});
Route::get('/belanja', function () {
    return view('shop', [
        'products' => MarketData::shopProducts(),
        'categories' => MarketData::categories(),
    ]);
});
Route::get('/seller/{username}', function (string $username) {
    // Strictly require username format starting with @
    if (!str_starts_with($username, '@')) {
        abort(404);
    }

    $handle = strtolower($username);
    // Currently only @rachel_venya (and @rachelvennya) exists
    $validHandles = ['@rachel_venya', '@rachelvennya'];
    
    if (!in_array($handle, $validHandles, true)) {
        abort(404);
    }

    return view('seller-profile', [
        'username' => '@rachel_venya',
    ]);
})->where('username', '@[A-Za-z0-9_.-]+');

// Informasi & Bantuan Pages
Route::get('/cara-jual', function () {
    return view('info-page', [
        'title' => 'Cara Jual di WhiMarket',
        'description' => 'Panduan praktis dan langkah mudah untuk mulai menjual barang pre-loved dan merchandise eksklusif kamu.',
        'sections' => [
            ['heading' => '1. Daftarkan Akun Seller Terverifikasi', 'content' => 'Buat akun dan hubungkan profil media sosial aktifmu (Instagram atau TikTok) untuk proses kurasi identitas.'],
            ['heading' => '2. Unggah Foto & Detail Produk', 'content' => 'Foto barang pre-loved kamu dengan jelas, cantumkan kondisi barang (Seperti Baru, Sangat Baik, atau Baik), dan tentukan harga terbaik.'],
            ['heading' => '3. Pengiriman & Pembayaran Aman', 'content' => 'Kirim barang setelah pesanan masuk. Dana penjualan akan langsung diteruskan ke saldo rekeningmu secara aman melalui sistem escrow WhiMarket.']
        ]
    ]);
});

Route::get('/keuntungan', function () {
    return view('info-page', [
        'title' => 'Keuntungan Menjadi Seller',
        'description' => 'Berbagai manfaat dan kemudahan eksklusif bagi figur publik dan kreator yang bergabung bersama WhiMarket.',
        'sections' => [
            ['heading' => 'Jangkauan Audiens Terfokus', 'content' => 'Barang pre-loved dan merchandise milikmu langsung ditemukan oleh para penggemar setia yang mencari produk otentik.'],
            ['heading' => 'Sistem Rekening Bersama (Escrow)', 'content' => 'Transaksi dijamin aman 100%. Tidak perlu khawatir penipuan dari pembeli karena dana sudah diamankan di awal.'],
            ['heading' => 'Pengelolaan Toko Simpel', 'content' => 'Dashboard intuitif untuk memantau stok, pesanan masuk, resi pengiriman, hingga penarikan saldo instan.']
        ]
    ]);
});

Route::get('/panduan-seller', function () {
    return view('info-page', [
        'title' => 'Panduan Seller',
        'description' => 'Standar kualitas, aturan pengemasan, dan panduan etika berjualan untuk menjaga kepuasan pembeli.',
        'sections' => [
            ['heading' => 'Standar Kejujuran Kondisi Barang', 'content' => 'Sertakan foto detail sudut produk, cacat minor jika ada, dan deskripsi penggunaan sebelumnya secara transparan.'],
            ['heading' => 'Protokol Pengemasan Rapi & Aman', 'content' => 'Gunakan bubble wrap dan kardus pelindung agar barang tidak rusak selama proses ekspedisi.'],
            ['heading' => 'Batas Waktu Pengiriman', 'content' => 'Kirim paket maksimal dalam kurun waktu 2x24 jam setelah pembayaran pembeli terkonfirmasi oleh sistem.']
        ]
    ]);
});

Route::get('/gabung-seller', function () {
    return view('info-page', [
        'title' => 'Gabung sebagai Seller',
        'description' => 'Mulai perjalananmu sebagai penjual pre-loved dan merchandise resmi di WhiMarket.',
        'sections' => [
            ['heading' => 'Kriteria Pendaftaran', 'content' => 'Terbuka untuk figur publik, kreator konten, kurator fashion, dan penjual barang koleksi otentik.'],
            ['heading' => 'Proses Kurasi Tim WhiMarket', 'content' => 'Tim kami akan memverifikasi keaslian profil dan kualitas barang dalam kurun waktu 1x24 jam kerja.'],
            ['heading' => 'Siap Buka Toko', 'content' => 'Setelah disetujui, toko resmi langsung aktif dengan badge verifikasi ungu dan siap menerima pesanan pertama.']
        ]
    ]);
});

Route::get('/faq', function () {
    return view('info-page', [
        'title' => 'Pertanyaan yang Sering Diajukan (FAQ)',
        'description' => 'Temukan jawaban cepat seputar transaksi, keaslian barang, pengiriman, dan cara berbelanja.',
        'sections' => [
            ['heading' => 'Apakah semua barang di WhiMarket dijamin asli?', 'content' => 'Ya, setiap seller melalui proses kurasi dan verifikasi identitas ketat oleh tim kurator WhiMarket.'],
            ['heading' => 'Bagaimana jika barang yang diterima tidak sesuai?', 'content' => 'Kamu dapat mengajukan komplain dalam waktu 2x24 jam setelah barang sampai dengan menyertakan video unboxing untuk pengembalian dana penuh.'],
            ['heading' => 'Metode pembayaran apa saja yang didukung?', 'content' => 'Kami mendukung QRIS, Transfer Bank Virtual Account (BCA, Mandiri, BNI, BRI), kartu kredit, dan dompet digital populer.']
        ]
    ]);
});

Route::get('/kebijakan-privasi', function () {
    return view('info-page', [
        'title' => 'Kebijakan Privasi',
        'description' => 'Komitmen WhiMarket dalam melindungi data pribadi, keamanan akun, dan kerahasiaan informasi pengguna.',
        'sections' => [
            ['heading' => 'Pengumpulan Data', 'content' => 'Kami hanya mengumpulkan data yang diperlukan untuk verifikasi identitas, pemrosesan transaksi, dan alamat pengiriman barang.'],
            ['heading' => 'Keamanan Informasi', 'content' => 'Data pengguna dienkripsi dengan standar industri SSL/TLS dan tidak pernah diperjualbelikan kepada pihak ketiga manapun.'],
            ['heading' => 'Hak Pengguna', 'content' => 'Pengguna memiliki hak penuh untuk memperbarui profil, meminta salinan data, atau mengajukan penghapusan akun kapan saja.']
        ]
    ]);
});

Route::get('/syarat-ketentuan', function () {
    return view('info-page', [
        'title' => 'Syarat & Ketentuan',
        'description' => 'Aturan dan ketentuan penggunaan platform marketplace WhiMarket bagi pembeli dan penjual.',
        'sections' => [
            ['heading' => 'Ketentuan Pembeli', 'content' => 'Pembeli wajib menyelesaikan pembayaran sesuai nominal dan memverifikasi kondisi barang begitu paket diterima.'],
            ['heading' => 'Ketentuan Penjual', 'content' => 'Penjual dilarang keras menjual barang tiruan (palsu/KW), barang ilegal, atau menyalahgunakan informasi pembeli.'],
            ['heading' => 'Penyelesaian Sengketa', 'content' => 'Segala sengketa transaksi diselesaikan melalui mediasi pusat resolusi WhiMarket dengan bukti yang sah dan objektif.']
        ]
    ]);
});

Route::get('/hubungi-kami', function () {
    return view('info-page', [
        'title' => 'Hubungi Kami',
        'description' => 'Tim layanan pelanggan WhiMarket siap membantu kamu setiap hari mulai pukul 08.00 - 21.00 WIB.',
        'sections' => [
            ['heading' => 'Email Bantuan Resmi', 'content' => 'Kirim pertanyaan atau laporan kendala transaksi ke support@whimarket.com.'],
            ['heading' => 'WhatsApp Customer Care', 'content' => 'Layanan chat cepat via WhatsApp di nomor resmi +62 812-3456-7890.'],
            ['heading' => 'Kantor Operasional', 'content' => 'Gedung WhiMarket HQ, Jl. Senopati No. 88, Kebayoran Baru, Jakarta Selatan 12190.']
        ]
    ]);
});
