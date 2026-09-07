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
        'image' => '/assets/info/cara-jual.png',
        'description' => 'Panduan praktis dan langkah mudah untuk mulai menjual barang pre-loved dan merchandise eksklusif kamu langsung kepada para penggemar.',
        'sections' => [
            ['heading' => 'Daftarkan Akun Seller Terverifikasi', 'content' => 'Buat akun dan hubungkan profil media sosial aktifmu (Instagram atau TikTok) untuk proses verifikasi identitas resmi.'],
            ['heading' => 'Unggah Foto & Detail Produk', 'content' => 'Foto barang pre-loved dengan pencahayaan jelas, cantumkan kondisi barang (Seperti Baru, Sangat Baik, atau Baik), dan tetapkan harga jual.'],
            ['heading' => 'Pengiriman & Pencairan Saldo Otomatis', 'content' => 'Kirim barang setelah pesanan masuk. Dana penjualan aman diteruskan langsung ke rekeningmu melalui escrow WhiMarket.']
        ]
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
            ['heading' => 'Dashboard Manajemen Praktis', 'content' => 'Pantau performa penjualan, kelola stok barang, cetak label resi ekspedisi, dan tarik dana ke bank secara instan.']
        ]
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
            ['heading' => 'Kecepatan Proses Pengiriman', 'content' => 'Kirimkan paket maksimal 2x24 jam hari kerja setelah pesanan terbayar untuk mempertahankan rating bintang 5 tokomu.']
        ]
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
            ['heading' => 'Toko Resmi Siap Menerima Order', 'content' => 'Dapatkan halaman toko personal (`/seller/@username`) dengan link bio siap dibagikan ke followers di media sosial.']
        ]
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
            ['heading' => 'Opsi pembayaran apa saja yang tersedia?', 'content' => 'Tersedia QRIS instan, Virtual Account semua bank utama (BCA, Mandiri, BNI, BRI), Kartu Kredit, dan dompet digital.']
        ]
    ]);
});

Route::get('/kebijakan-privasi', function () {
    return view('info-page', [
        'title' => 'Kebijakan Privasi',
        'image' => '/assets/info/kebijakan-privasi.png',
        'description' => 'Komitmen tertinggi kami dalam menjaga kerahasiaan data pribadi, riwayat transaksi, dan keamanan akun kamu di WhiMarket.',
        'sections' => [
            ['heading' => 'Enkripsi Data Standar Perbankan', 'content' => 'Seluruh transmisi data pribadi dan informasi pembayaran dilindungi dengan enkripsi SSL/TLS 256-bit kelas industri.'],
            ['heading' => 'Kerahasiaan Alamat & Kontak', 'content' => 'Nomor kontak dan alamat rumah kamu hanya diteruskan ke kurir logistik demi keperluan pengiriman barang pesanan.'],
            ['heading' => 'Hak Kendali Informasi Akun', 'content' => 'Kamu memiliki kebebasan penuh untuk meninjau, memperbarui preferensi notifikasi, atau meminta penonaktifan akun.']
        ]
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
            ['heading' => 'Pusat Mediasi Resolusi Transaksi', 'content' => 'Setiap perselisihan antara penjual dan pembeli ditengahi secara adil oleh mediator resmi tim sengketa WhiMarket.']
        ]
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
            ['heading' => 'Kantor Pusat WhiMarket', 'content' => 'WhiMarket Tower Lt. 15, Jl. Jend. Sudirman Kav. 52-53, Kawasan SCBD, Jakarta Selatan, DKI Jakarta 12190.']
        ]
    ]);
});
