# SPEC-MVP-WHIMARKET.md
# Spesifikasi Teknis Implementasi Penuh MVP WhiMarket

Dokumen ini adalah instruksi kerja dan acuan teknis definitif untuk mengimplementasikan seluruh item MVP (No. 1–69) pada aplikasi **WhiMarket** berbasis Laravel 13, MariaDB, Tailwind CSS v4, dan Alpine.js.

---

## ⚠️ ATURAN MUTLAK: PRESERVASI DESAIN & UI (DILARANG MERUSAK / MENGUBAH TAMPILAN)
- **UI Invariant (Desain yang Sudah Ada Wajib 100% Tetap Sama)**:
  - Seluruh komponen Blade yang sudah ada di `resources/views/components/` dan views di `resources/views/` **TIDAK BOLEH DIUBAH DESAIN, WARNA, MAUPUN STRUKTUR VISUALNYA**.
  - **Card Seller (`components/seller-card.blade.php`)**: Desain, layout avatar, badge verified, tipografi, dan padding tetap persis 100%. Hanya ganti sumber data array statis menjadi data objek model Eloquent.
  - **Card Produk (`components/product-card.blade.php`)**: Card produk di beranda, katalog `/belanja`, dan profil toko `/seller/{username}` tetap persis sama (termasuk tag harga, rating, foto, badge, dan hover efek).
  - **Halaman Belanja (`views/shop.blade.php`)**: Banner ungu, filter drawer mobile, sidebar filter kategori, dan layout grid katalog produk tidak boleh diubah stylenya.
  - **Halaman Detail Produk (`views/product-detail.blade.php`)**: Layout galeri foto, panel penjual, tombol aksi, dan font handwriting (`Caveat`) dipertahankan 100%.
  - **Design System Tailwind v4**: Tetap menggunakan token warna brand (`--color-primary-purple`, `--color-brand-yellow`, dll) di `resources/css/app.css`. Dilarang mengganti library CSS atau merombak styling global.
- **Tugas Utama Agent**: Menghubungkan tampilan UI yang sudah matang ini ke database dinamis (Eloquent Model & Controller) dan melengkapi halaman fungsional baru (Checkout, Dashboard Seller, Admin Panel) dengan gaya visual/desain token yang senada.
- **KEWAJIBAN MUTLAK VISUAL INSPECTION (VISION) & KOMPARASI LANDING PAGE**:
  - **Dilarang keras berasumsi**: Agent DILARANG menganggap pekerjaan pembuatan/modifikasi halaman selesai hanya dari menulis sintaks kode Blade/CSS tanpa melakukan inspeksi visual nyata.
  - **Wajib Gunakan Vision**: Setiap halaman baru atau komponen baru yang dibuat WAJIB diinspeksi secara visual menggunakan kapabilitas Vision / Browser Tool (`browser.open` / screenshot / live rendering inspection).
  - **Wajib Compare dengan Landing Page (`resources/views/landing.blade.php` / `/`)**: Landing page adalah *Gold Standard* estetika WhiMarket. Desain halaman baru (tipografi, palet ungu `#4F26A6` & amber `#F59E0B`, card rounded-2xl, button CTA, spasi) wajib 100% konsisten dengan Landing Page.
  - **Wajib Responsif & Rapih Multi-Device**: Setiap halaman baru wajib diverifikasi di breakpoint Mobile (375px–430px), Tablet (768px), dan Desktop (1280px+), dengan toleransi nol horizontal overflow (`no horizontal scrollbar`), teks terlindungi anti-pecah, dan grid/flex tersusun rapih dan simetris.

### 0. Panduan Konsistensi UI/UX & Design Tokens (Wajib Ditaati untuk Seluruh Halaman Baru)
Agar halaman baru (Keranjang, Checkout, Dashboard Seller, Admin Panel, Modal Sengketa) tampil menyatu dan identik dengan desain yang sudah ada:
1. **Palette Warna Brand (Tailwind v4 CSS-First `@theme`)**:
   - **Warna Utama (Primary Purple)**: `#4F26A6` (class: `bg-[#4F26A6]`, `text-[#4F26A6]`, `hover:bg-[#3E1D85]`). Gunakan untuk tombol aksi utama (CTA), header aktif, dan badge prioritas.
   - **Warna Sekunder / Aksen (Brand Amber/Yellow)**: `#F59E0B` / `#F7AC1D` (class: `bg-[#F59E0B]`, `text-[#F59E0B]`). Gunakan untuk rating bintang, badge kategori tas/merch, dan label penting.
   - **Canvas / Background Halaman**: `#FAF9FC` (class: `bg-[#FAF9FC]`). Jangan gunakan warna putih mentah (`#FFFFFF`) untuk full-screen background body.
   - **Card / Surface Container**: `#FFFFFF` dengan shadow lembut (`shadow-xs` atau `shadow-sm`), border halus (`border border-gray-100` atau `border-gray-200`), dan sudut melengkung `rounded-2xl` atau `rounded-3xl`.
   - **Tipografi & Teks**:
     - Headings & Body: `'Plus Jakarta Sans', sans-serif` (font default).
     - Aksen Catatan / Highlight Kreator: `'Caveat', cursive` (class: `font-handwriting`).
     - Hirarki Warna Teks: Judul utama `text-gray-900` (`#111827`), sub-deskripsi `text-gray-500` / `text-gray-600`.
2. **Komponen UX Konsisten**:
   - **Tombol Utama (Primary Button)**: `bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold rounded-xl py-3 px-6 transition-all duration-200 shadow-sm active:scale-[0.98]`.
   - **Tombol Sekunder (Outline Button)**: `bg-transparent text-[#4F26A6] border-1.5 border-[#4F26A6]/30 hover:bg-[#4F26A6] hover:text-white rounded-xl py-2.5 px-5 font-semibold transition-all duration-200`.
   - **Form Input & Select**: `w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-[#4F26A6] focus:ring-2 focus:ring-[#4F26A6]/20 transition-all outline-none`.
   - **Status Badges**:
     - Pending/Menunggu: `bg-amber-50 text-amber-700 border border-amber-200/60 rounded-full px-3 py-1 text-xs font-semibold`
     - Sukses/Approved/Delivered: `bg-emerald-50 text-emerald-700 border border-emerald-200/60 rounded-full px-3 py-1 text-xs font-semibold`
     - Ditolak/Dispute/Batal: `bg-rose-50 text-rose-700 border border-rose-200/60 rounded-full px-3 py-1 text-xs font-semibold`
     - Diproses/Dikirim: `bg-purple-50 text-[#4F26A6] border border-purple-200/60 rounded-full px-3 py-1 text-xs font-semibold`
   - **Interaktivitas & Feedback (UX)**:
     - Seluruh interaksi toggle, modal popup, filter drawer, dan kalkulasi subtotal wajib reaktif menggunakan **Alpine.js** (`x-data`, `x-show`, `x-cloak`).
     - Sediakan loading skeleton atau state feedback saat submit bukti bayar, upload foto produk, atau ubah status pesanan.
3. **KEWAJIBAN MUTLAK: Standar Pembuatan & Penggunaan Aset Gambar Baru (WAJIB Generate Image AI)**:
   - **STATUS ATURAN: MANDATORY / WAJIB 100% (BUKAN OPSIONAL ATAU JIKA BUTUH SAJA)**:
     - **DILARANG KERAS**:
       - Menggunakan placeholder abu-abu/kosong (seperti `via.placeholder.com`, `placeholder.svg`, box wireframe kosong).
       - Menggunakan gambar dummy acak/sembarangan yang tidak estetik atau link eksternal yang tidak reliabel.
       - Mengabaikan elemen visual pada halaman baru (seperti banner hero, banner promo, empty state ilustratif, icon kategori visual, avatar dummy kreator, dan kartu panduan).
     - **WAJIB 100% Menggunakan Endpoint API Image Generation Resmi**:
       - Setiap kali membuat halaman baru atau komponen yang memerlukan elemen visual ilustrasi/banner/empty-state (contoh: ilustrasi keranjang kosong, ilustrasi belum ada order, banner onboarding seller, banner promo checkout, icon kategori khusus), **AI WAJIB langsung mengeksekusi request generate image** ke endpoint berikut:
       ```bash
       curl -X POST http://84.247.144.89:20128/v1/images/generations \
         -H "Content-Type: application/json" \
         -H "Authorization: Bearer sk-38fa7cc1d9099a51-xgwgf9-5dede6b6" \
         -d '{"model":"ag/gemini-3.1-flash-image","prompt":"[DESKRIPSI_DETAIL_GAMBAR_YANG_DIBUTUHKAN]","n":1,"size":"auto","quality":"auto","background":"auto","image_detail":"high","output_format":"png"}'
       ```
     - **Alur Kerja Eksekusi & Penyimpanan**:
       1. **Generate**: AI mengeksekusi curl API image generation dengan prompt Bahasa Inggris/Indonesia yang deskriptif dan estetik sesuai tema WhiMarket (Creator/Public Figure Preloved & Exclusive Merch, warna dominan ungu `#4F26A6` dan aksen amber `#F59E0B`).
       2. **Simpan**: Simpan output gambar langsung ke direktori `public/assets/` (atau subdirektori seperti `public/assets/banners/`, `public/assets/illustrations/`, `public/assets/empty-states/`) dengan format `.png` / `.webp` dan penamaan file yang deskriptif dan terstruktur.
       3. **Implementasikan**: Panggil langsung file aset lokal tersebut di view Blade terkait (`src="{{ asset('assets/illustrations/...') }}"`).
       4. **Verifikasi**: Pastikan gambar tampil sempurna di browser tanpa broken link atau placeholder kosong.
4. **KEWAJIBAN MUTLAK: Vision Inspection, Komparasi dengan Landing Page, & Jaminan Responsif / Kerapian Layout (Zero-Deviation UI/UX Standard)**:
   - **STATUS ATURAN: MANDATORY / WAJIB 100% PADA SETIAP PEMBUATAN ATAU MODIFIKASI HALAMAN BARU**:
   - **A. Wajib Gunakan Kapabilitas Vision (Inspeksi Visual & Render Browser Nyata)**:
     - Setiap kali Agent membuat atau memodifikasi halaman antarmuka pengguna baru (contoh: Keranjang `/keranjang`, Checkout `/checkout`, Instruksi Pembayaran `/pembayaran/{id}`, Order Detail `/pesanan/{order_number}`, Seller Dashboard `/seller/dashboard`, Form Produk `/seller/products/create`, Halaman Komplain `/dispute/{id}`, Modal Alamat, dsb.):
       - **AI WAJIB mengeksekusi inspeksi visual menggunakan kapabilitas Vision / Browser**: buka URL halaman tersebut di browser lokal, ambil screenshot tampilan, dan amati langsung komposisi visualnya.
       - **DILARANG KERAS** mengandalkan asumsi kode Blade/HTML semata tanpa melihat wujud visual nyatanya di browser (Zero Visual Blindspot).
       - Jika hasil inspeksi visual memperlihatkan elemen yang miring, padding timpang, font tidak seragam, tombol gepeng, atau kontras yang buruk, perbaiki seketika itu juga sebelum pekerjaan dianggap selesai.
   - **B. Wajib Bandingkan (Side-by-Side Comparison) dengan Landing Page (`resources/views/landing.blade.php` / `/`)**:
     - **Landing Page adalah *Single Source of Truth / The Gold Standard* Desain WhiMarket**: Karakter visual WhiMarket (eksklusif, playful namun terpercaya, modern, dan bernuansa kreator publik figur) telah terpatri sempurna pada Landing Page.
     - Setiap halaman baru **WAJIB dikomparasikan berdampingan (side-by-side comparison)** dengan Landing Page terhadap 6 elemen kunci:
       1. **Pembungkus Layout**: Halaman baru wajib menggunakan `<x-layouts.app :title="..." :activeTab="...">` agar Navbar (`layouts/navbar.blade.php`) dan Footer (`layouts/footer.blade.php`) tetap presisi, seragam, dan utuh.
       2. **Max-Width & Container Rhythm**: Gunakan pembatas lebar kontainer utama yang seragam dengan landing page (`max-w-[1536px]` atau `max-w-7xl mx-auto px-4 sm:px-6 md:px-8 lg:px-12 xl:px-16 py-6 sm:py-8 lg:py-10`).
       3. **Tipografi & Hirarki Teks**:
          - Font default sans: `'Plus Jakarta Sans', sans-serif`.
          - Judul Utama Halaman: `text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-900 tracking-tight`.
          - Subjudul / Deskripsi: `text-sm sm:text-base text-gray-600 leading-relaxed`.
          - Aksen Catatan Personal / Kreator: Wajib gunakan font handwriting `'Caveat', cursive` (`font-handwriting text-gray-900` atau `text-[#4F26A6]`), identik dengan aksen sticky paper note di hero landing page.
          - Category Badge / Pill: `inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#F3EEFF] text-[#4F26A6] text-xs sm:text-sm font-bold`.
       4. **Card Styling & Elevation**:
          - Gunakan standar card persis seperti Card Produk & Card Seller di Landing Page: `bg-white rounded-2xl border border-gray-100/90 shadow-[0_4px_16px_rgba(0,0,0,0.04)] hover:shadow-[0_10px_26px_rgba(0,0,0,0.08)] transition-all`.
          - Dilarang keras membuat card bersudut siku tajam (`rounded-none` / `rounded-sm`), bergaris tepi abu-abu gelap tebal, atau dengan drop-shadow hitam pekat yang merusak estetika brand.
       5. **Tombol CTA & Micro-Interactions**:
          - Tombol CTA Utama: `bg-[#4F26A6] hover:bg-[#3E1D85] text-white font-bold rounded-xl py-3 px-6 shadow-md shadow-[#4F26A6]/20 transition-all active:scale-[0.98] inline-flex items-center justify-center gap-2.5`.
          - Tombol Sekunder / Outline: `border-2 border-[#4F26A6] text-[#4F26A6] hover:bg-[#4F26A6]/5 font-bold rounded-xl py-2.5 px-5 transition-all inline-flex items-center justify-center gap-2`.
       6. **Badge Verified Rosette**: Gunakan komponen resmi `<x-verified-badge size="sm|md" />` persis seperti di landing page untuk setiap status seller/toko terverifikasi.
   - **C. Jaminan Responsivitas Penuh Multi-Device (Mobile, Tablet, Desktop)**:
     - WhiMarket adalah marketplace yang berfokus pada pengalaman pengguna mobile-first. Setiap halaman baru **WAJIB diuji pada 3 spektrum ukuran layar**:
       1. **Mobile Viewport (375px – 430px, e.g. iPhone / Android Standard)**:
          - **TOLERANSI NOL HORIZONTAL OVERFLOW**: Dilarang keras ada konten yang memicu horizontal scrollbar pada window (`overflow-x` liar yang membuat halaman bergoyang ke samping). Seluruh kontainer wajib fluid `w-full` dengan padding sisi aman `px-4`.
          - Tombol aksi mobile wajib memiliki touch target ramah jempol (tinggi minimal `h-11` atau `h-12`, `min-h-[44px]`).
          - Tabel data (seperti daftar pesanan seller atau mutasi saldo) wajib bertransformasi menjadi daftar kartu (card list) mobile yang mudah dibaca, ATAU dibungkus kontainer scroll horizontal terisolasi yang rapi (`overflow-x-auto rounded-xl border border-gray-200`).
          - Sticky action bar di mobile (misal: subtotal & tombol "Bayar Sekarang" di checkout) harus fixed rapi di bawah dengan safe padding tanpa menutupi konten di belakangnya.
       2. **Tablet Viewport (768px – 1023px, e.g. iPad / Tablet)**:
          - Grid layout beradaptasi secara mulus: 1 kolom di mobile bertransformasi menjadi 2 atau 3 kolom (`grid-cols-1 sm:grid-cols-2 lg:grid-cols-3`).
          - Sidebar filter, panel profil toko, atau drawer tidak boleh bertumpuk atau terpotong.
       3. **Desktop Viewport (1024px – 1536px+)**:
          - Tata letak multi-kolom yang seimbang (contoh: kolom daftar barang kiri 60% dan sticky summary pembayaran kanan 40%).
          - Komponen card tidak boleh meregang berlebihan (stretching ekstrem) tanpa batasan `max-w`.
   - **D. Standar Kerapian & Polishing Visual (Pixel-Tidy & Aesthetic Discipline)**:
     - **Ritme Spacing Konsisten**: Selalu gunakan kelipatan Tailwind terstandar (`gap-3 sm:gap-4`, `p-4 sm:p-6`, `py-8 sm:py-12`). Jangan mencampur padding acak yang menyebabkan tata letak tidak rapi.
     - **Alignment Simetris**: Ikon SVG dan label teks selalu sejajar di tengah secara vertikal (`inline-flex items-center gap-2.5`), dengan class `shrink-0` pada SVG agar tidak mengecil saat teks panjang.
     - **Defensive UI untuk Teks Panjang**: Seluruh teks dinamis (nama produk, varian, alamat, nama buyer/seller) wajib diproteksi dari kebocoran layout (`truncate`, `line-clamp-1`, `line-clamp-2`, atau `break-words`). Dilarang ada teks yang menembus batas card.
     - **Empty State Estetik**: Saat keranjang kosong, belum ada order, atau riwayat transaksi kosong, wajib menyajikan ilustrasi AI resmi yang digenerate langsung ke `public/assets/` (sesuai aturan Item 3) lengkap dengan copywriting ramah dan tombol CTA terarah.
     - **Interaktivitas Tanpa Glitch**: Seluruh interaksi Alpine.js (`x-data`, `x-show`, `x-cloak`, dropdown, modal popup) harus memiliki transisi halus dan bebas dari visual flicker/layout shift.
   - **E. Checklist Verifikasi UI/UX Sebelum Halaman Dinyatakan Selesai ("Done")**:
     - [ ] Halaman baru telah dirender di browser dan diinspeksi secara visual menggunakan Vision / Screenshot.
     - [ ] Tampilan telah dikomparasikan berdampingan dengan Landing Page (`/`): palet warna ungu `#4F26A6` & amber `#F59E0B`, card rounded-2xl, font Plus Jakarta Sans + Caveat 100% serasi.
     - [ ] Diuji pada breakpoint Mobile (375px): responsif sempurna, TIDAK ADA horizontal overflow / scrollbar liar, tombol sentuh mudah dijangkau.
     - [ ] Diuji pada breakpoint Tablet (768px) & Desktop (1280px+): grid tersusun simetris, sticky container berfungsi rapi, tidak ada elemen melar ekstrem.
     - [ ] Seluruh aset visual / ilustrasi empty state terpasang dari file lokal resmi (hasil generate image AI), nol gambar rusak (broken image) atau placeholder abu-abu.
     - [ ] Seluruh interaksi reaktif (Alpine.js) berjalan mulus tanpa kedip visual atau pergeseran layout mendadak.
---
## 1. Alur Kerja Utama 3 Aktor (End-to-End User Journeys)

### 1.1 Alur Buyer

| No | User Flow Buyer | Alur Singkat |
| -: | :--- | :--- |
| 1 | **Login / Register** | Register Akun WhiMarket (Email & Password) atau Google SSO → Popup Onboarding Data Diri & Alamat (Bisa Diisi / Dilewati) → Masuk Beranda |
| 2 | **Mencari Produk** | Browse → Search/Filter → Product Detail |
| 3 | **Wishlist** | Lihat produk → Tambah/Hapus Wishlist |
| 4 | **Keranjang & Checkout** | Add to Cart → Keranjang → Pilih Alamat (Auto jika sudah diisi / Isi Baru 1x) → Buat Order |
| 5 | **Pembayaran** | Transfer manual → Upload bukti → Menunggu verifikasi Admin |
| 6 | **Penerimaan Pesanan** | Pesanan dikirim → Lihat resi → Barang sampai → Konfirmasi diterima |
| 7 | **Komplain / Dispute** | Laporkan masalah → Upload bukti → Seller tanggapi → Admin putuskan |

#### Diagram ASCII User Flow Buyer
```text
       [ 1. Register Akun WhiMarket / Google SSO ]
                      │
                      ▼
        [ Akun Berhasil Masuk / Dibuat ]
                      │
                      ▼
       [ Prompt: Lengkapi Kontak & Alamat? ]
              │                       │
        (Isi Sekarang)          (Lewati / Nanti)
              │                       │
              ▼                       │
     [ Simpan Nomor HP &              │
       Alamat Utama Default ]         │
              │                       │
              └───────────┬───────────┘
                          ▼
             [ 2. Browse / Search / Filter ] ◄────────┐
                          │                           │
                          ├─────────► [ 3. Wishlist ] ┘
                          ▼
             [ 4. Product Detail & Varian ]
                          │
                          ▼
        [ Add to Cart ] ──► [ Keranjang Belanja ]
                                      │
                                      ▼
                            [ Masuk ke Checkout ]
                                      │
                       ┌──────────────┴──────────────┐
               (Alamat Sudah Ada)             (Alamat Belum Ada)
                       │                             │
                       ▼                             ▼
            [ Auto-Select Alamat ]         [ Isi Form Alamat 1x ]
                       │                   (Auto Save ke Default)
                       │                             │
                       └──────────────┬──────────────┘
                                      ▼
                      [ 5. Buat Order & Transfer Manual ]
                                      │
                                      ▼
                            [ Upload Bukti Bayar ]
                                      │
                                      ▼
                            [ Verifikasi Admin ]
                                      │
                       ┌──────────────┴──────────────┐
                [ Bukti Ditolak ]             [ Bukti Disetujui ]
                       │                             │
                       ▼                             ▼
              [ Upload Ulang Bukti ]         [ Seller Kirim + Input Resi ]
                                                     │
                                                     ▼
                                          [ 6. Lacak Resi Pengiriman ]
                                                     │
                                                     ▼
                                          [ Status: Paket Sampai ]
                                                     │
                               ┌─────────────────────┴─────────────────────┐
                               │                                           │
                        (Jalur A: Cepat)                            (Jalur B: Failsafe)
                               │                                           │
                               ▼                                           ▼
                     [ Buyer Klik Diterima ]                      [ Seller Cek Kurir: ]
                               │                                  [ Status DELIVERED ]
                               │                                           │
                               │                                           ▼
                               │                                  [ Seller Ajukan Klaim: ]
                               │                                  [ "Paket Sampai" ]
                               │                                           │
                               │                                           ▼
                               │                                  [ Admin Cek Web Resi ]
                               │                                           │
                               │                                           ▼
                               │                                  [ Admin Verifikasi ]
                               │                                  [ Status: VALID ]
                               │                                           │
                               └─────────────────────┬─────────────────────┘
                                                     ▼
                                      [ Status: PAKET DITERIMA ]
                                                     │
                                                     ▼
                                        [ Masa Pemeriksaan 48 Jam ]
                                                     │
                         ┌───────────────────────────┼───────────────────────────┐
                         │                           │                           │
                  [ Buyer Terima ]           [ Buyer Komplain ]            [ Buyer Diam / ]
                         │                           │                     [ Cuek 48 Jam ]
                         ▼                           ▼                           │
                [ Pesanan Selesai ]           [ 7. Sengketa ]                    ▼
                         │                     (Dispute Status)         [ Auto-Complete 48 Jam ]
                         ▼                           │                           │
               [ Dana Cair ke Seller ]               ▼                           ▼
                                           [ Upload Bukti & Unboxing ]  [ Pesanan Selesai ]
                                                     │                           │
                                                     ▼                           ▼
                                           [ Seller Tanggapi Bukti ]   [ Dana Cair ke Seller ]
                                                     │
                                                     ▼
                                           [ Admin Cek & Putuskan ]
                                                     │
                                      ┌──────────────┴──────────────┐
                                      ▼                             ▼
                              [ Dispute Ditolak ]           [ Dispute Disetujui ]
                                      │                             │
                                      ▼                             ▼
                             [ Pesanan Selesai ]           [ Refund Manual Buyer ]
                             (Dana Cair ke Seller)
```

### 1.2 Alur Seller / Public Figure

| No | User Flow Seller / Public Figure | Alur Singkat |
| -: | :--- | :--- |
| 1 | **Gabung & Verifikasi Seller (Kode Akses)** | Register Akun WhiMarket atau Login Google → Chat Admin via TikTok/IG → Admin Berikan Kode Akses → Masukkan Kode di WhiMarket → Toko Langsung Aktif |
| 2 | **Kelola Toko & Produk** | Masuk Dashboard → Kelola profil toko → Tambah/Edit/Hapus produk → Upload foto → Atur kategori, varian & stok |
| 3 | **Kelola Pesanan** | Pesanan masuk → Cek detail order → Siapkan barang → Upload foto barang → Packing & kirim |
| 4 | **Pengiriman** | Input nomor resi → Upload foto/scan resi → Update status menjadi Dikirim → Buyer dapat melihat resi |
| 5 | **Komplain & Pencairan Dana** | Buyer komplain → Seller lihat dispute → Berikan tanggapan/bukti → Order selesai → Dana masuk pending payout → Menunggu Admin mencairkan |

#### 1.2.1 Detail Alur & Tabel Gabung Seller (Onboarding Kode Akses)

##### Alur Calon Seller (Flow Seller)
| Step | Flow Seller                           | Detail                                                                               |
| ---: | ------------------------------------- | ------------------------------------------------------------------------------------ |
|    1 | **Login / Register di WhiMarket**     | Seller login atau daftar menggunakan akun WhiMarket (email & password) atau Google SSO |
|    2 | **Chat Admin via TikTok / Instagram** | Seller menghubungi admin WhiMarket melalui DM TikTok atau Instagram                  |
|    3 | **Konfirmasi Email yang Terdaftar**   | Admin menanyakan email yang digunakan seller untuk login/register di WhiMarket        |
|    4 | **Admin Berikan Kode Akses**          | Admin memverifikasi email lalu membuat kode akses khusus seller                      |
|    5 | **Masukkan Kode di WhiMarket**        | Seller login ke WhiMarket, kemudian memasukkan kode akses yang diberikan admin       |
|    6 | **Toko Aktif**                        | Setelah kode valid, akun/toko seller langsung aktif dan seller dapat mulai berjualan |

##### Alur Admin (Flow Admin)
| Step | Flow Admin                            | Detail                                                                      |
| ---: | ------------------------------------- | --------------------------------------------------------------------------- |
|    1 | **Terima DM**                         | Admin menerima pesan dari calon seller melalui TikTok/Instagram             |
|    2 | **Tanyakan / Terima Email Terdaftar** | Admin meminta email yang digunakan untuk login/register di WhiMarket                |
|    3 | **Generate Kode Akses**               | Admin membuat kode akses unik untuk seller tersebut                         |
|    4 | **Berikan Kode ke Seller**            | Admin mengirim kode melalui DM TikTok/Instagram                             |
|    5 | **Seller Aktif**                      | Setelah seller memasukkan kode yang benar di WhiMarket, toko langsung aktif |

#### Diagram ASCII Interaksi / Sequence Flow Gabung Seller
```text
┌────────────────┐            ┌────────────────────────┐            ┌─────────────────┐            ┌──────────────────┐
│  CALON SELLER  │            │  DM TIKTOK / INSTAGRAM │            │ ADMIN WHIMARKET │            │ WHIMARKET SYSTEM │
└───────┬────────┘            └───────────┬────────────┘            └────────┬────────┘            └────────┬─────────┘
        │                                 │                                  │                              │
        │ 1. Register / Login di WhiMarket  │                                  │                              │
        ├──────────────────────────────────────────────────────────────────────────────────────────────────►│
        │    (Email & Password atau Google)  │                                  │                      [ Akun Terdaftar ]
        │                                 │                                  │                      (Role: Buyer)
        │                                 │                                  │                              │
        │ 2. Chat Admin via DM            │                                  │                              │
        ├────────────────────────────────►│                                  │                              │
        │    "Halo Min, mau gabung Seller"│ 1. Terima DM dari Calon Seller   │                              │
        │                                 ├─────────────────────────────────►│                              │
        │                                 │                                  │                              │
        │                                 │ 2. Tanyakan Email Terdaftar      │                              │
        │                                 │◄─────────────────────────────────┤                              │
        │ 3. Konfirmasi Email Terdaftar   │                                  │                              │
        ├────────────────────────────────►│                                  │                              │
        │    (e.g. seller@gmail.com)      │    Terima Email Terdaftar        │                              │
        │                                 ├─────────────────────────────────►│                              │
        │                                 │                                  │                              │
        │                                 │                                  │ 3. Cari User & Generate      │
        │                                 │                                  │    Kode Akses Seller Unik    │
        │                                 │                                  ├─────────────────────────────►│
        │                                 │                                  │◄─────────────────────────────┤
        │                                 │                                  │    (Kode Akses Dibuat)       │
        │                                 │ 4. Berikan Kode via DM           │                              │
        │                                 │◄─────────────────────────────────┤                              │
        │ 4. Terima Kode Akses            │                                  │                              │
        │◄────────────────────────────────┤                                  │                              │
        │                                 │                                  │                              │
        │ 5. Masukkan Kode di WhiMarket   │                                  │                              │
        ├──────────────────────────────────────────────────────────────────────────────────────────────────►│
        │    (Form Input Kode Akses)      │                                  │                              │
        │                                 │                                  │                      [ Validasi Kode ]
        │                                 │                                  │                              │
        │ 6. Toko Langsung Aktif          │                                  │                      - Role: SELLER
        │◄──────────────────────────────────────────────────────────────────────────────────────────┤
        │    (Masuk ke Seller Dashboard   │                                  │                      - Status: VERIFIED
        │     & Mulai Berjualan)          │                                  │                      - Toko Siap Digunakan
```

#### Diagram ASCII User Flowchart Gabung Seller
```text
                  [ 1. Calon Seller: Register / Login di WhiMarket ]
                                        │
                                        ▼
                           [ Akun Terdaftar di Sistem ]
                                (Role Awal: Buyer)
                                        │
                                        ▼
                    [ 2. Chat Admin via TikTok / Instagram ]
                       ("Halo Min, mau daftar jadi Seller")
                                        │
                                        ▼
                    [ 3. Admin Terima DM & Tanyakan Email ]
                                        │
                                        ▼
                 [ Seller Konfirmasi Email yang Terdaftar ]
                                        │
                                        ▼
                    [ 4. Admin Generate Kode Akses Khusus ]
                        (Melalui Admin Panel WhiMarket)
                                        │
                                        ▼
                   [ Admin Berikan Kode Akses ke Seller ]
                          (Via DM TikTok / Instagram)
                                        │
                                        ▼
                  [ 5. Seller Masukkan Kode di WhiMarket ]
                                 (Form Kode Akses)
                                        │
                         ┌──────────────┴──────────────┐
                         ▼                             ▼
                 [ Kode Tidak Valid ]            [ Kode Valid ]
                         │                             │
                (Tolak / Coba Lagi)                    ▼
                                               [ 6. Toko Aktif ]
                                         - Role Upgrade ke SELLER
                                         - Status Toko: VERIFIED
                                         - Seller Lengkapi Profil Toko
                                                       │
                                                       ▼
                                          [ Masuk Seller Dashboard ]
                                          [ Mulai Tambah Produk ]
```

#### 1.2.2 Diagram ASCII User Flow Seller / Public Figure (End-to-End)
```text
     [ 1. Register / Login WhiMarket & Aktivasi Kode Akses ]
                           │
                           ├─► Chat Admin via DM TikTok / Instagram
                           ├─► Admin Berikan Kode Akses Khusus
                           └─► Masukkan Kode Akses di WhiMarket
                           │
                           ▼
              [ Role Berubah Jadi Seller ]
                 (Toko Langsung Aktif)
                           │
                           ▼
             [ 2. Akses Seller Dashboard ]
                           │
             ┌─────────────┴─────────────┐
             ▼                           ▼
   [ Kelola Profil Toko ]      [ Manajemen Produk (CRUD) ]
                                             │
                                             ├─► [ Upload Multi-Foto ]
                                             ├─► [ Pilih Kategori ]
                                             └─► [ Setup Varian & Stok ]
                                                     │
                                                     ▼
                                          [ Produk Tayang di Toko ]
                                                     │
                                                     ▼
                                      [ 3. Notifikasi Order Masuk ]
                                        (Status: Paid / Siap Kirim)
                                                     │
                                                     ▼
                                          [ Cek Rincian Order ]
                                                     │
                                                     ▼
                                          [ Siapkan Barang & Packing ]
                                                     │
                                                     ▼
                                       [ Upload Foto Kondisi Barang ]
                                            (Bukti Sebelum Kirim)
                                                     │
                                                     ▼
                                       [ 4. Input Kurir & No. Resi ]
                                       [ Upload Foto / Scan Resi ]
                                                     │
                                                     ▼
                                       [ Status Berubah: Shipped ]
                                       (Buyer Dapat Pantau Resi)
                                                     │
                                                     ▼
                                        [ Paket Tiba di Buyer ]
                                                     │
                     ┌───────────────────────────────┴───────────────────────────────┐
                     │                                                               │
              (Jalur Normal)                                                  (Jalur Komplain)
                     │                                                               │
                     ▼                                                               ▼
     [ Buyer Konfirmasi / Auto 48 Jam ]                             [ 5. Notifikasi Komplain Masuk ]
                     │                                                               │
                     ▼                                                               ▼
           [ Transaksi Selesai ]                                          [ Buka Detail Dispute ]
                     │                                                               │
                     ▼                                                               ▼
       [ Dana Masuk Pending Payout ]                                      [ Berikan Tanggapan & ]
                     │                                                    [ Upload Bukti Packing ]
                     ▼                                                               │
         [ Admin Transfer Saldo ]                                                    ▼
         (Upload Bukti Transfer)                                           [ Keputusan Admin ]
                     │                                                               │
                     ▼                                                ┌──────────────┴──────────────┐
         [ Saldo Diterima Seller ]                                    ▼                             ▼
                                                             [ Dispute Ditolak ]           [ Dispute Diterima ]
                                                             (Order Selesai & Payout)      (Refund ke Buyer)
```

### 1.3 Alur Admin

| No | User Flow Admin | Alur Singkat |
| -: | :--- | :--- |
| 1 | **Login & Dashboard** | Login → Admin Dashboard → Lihat ringkasan seller, produk, pesanan, pembayaran, dispute & payout |
| 2 | **Onboarding Seller (Kode Akses) & Moderasi Produk** | Terima DM TikTok/IG → Tanyakan email terdaftar → Generate kode akses unik → Kirim ke seller via DM → Seller aktif → Moderasi produk |
| 3 | **Verifikasi Pembayaran & Monitoring Pesanan** | Bukti transfer masuk → Cek pembayaran → Valid/Reject → Monitor proses order sampai pengiriman/selesai |
| 4 | **Penanganan Dispute & Klaim Pengiriman** | Buyer komplain **atau** Seller klaim paket sudah sampai → Cek bukti buyer/seller & tracking → Admin memutuskan → Lanjutkan transaksi atau refund/retur |
| 5 | **Pencairan Dana Seller** | Order selesai → Dana masuk pending payout → Admin cek → Transfer ke seller → Upload bukti transfer |

#### Diagram ASCII User Flow Admin
```text
                [ 1. Login Akun Admin ]
                           │
                           ▼
                [ Masuk Admin Dashboard ]
    (Ringkasan: Seller, Produk, Order, Escrow, Dispute, Payout)
                           │
       ┌───────────────────┼───────────────────┬───────────────────┐
       │                   │                   │                   │
       ▼                   ▼                   ▼                   ▼
[ 2. Seller & Produk ]  [ 3. Pembayaran ]  [ 4. Sengketa/Klaim ]  [ 5. Payout Seller ]
       │                   │                   │                   │
 ┌─────┴─────┐       ┌─────┴─────┐       ┌─────┴─────┐             │
 │ DM Calon  │       │ Bukti Bayar│      │ A. Seller │             │
 │  Seller   │       │   Masuk   │       │   Klaim   │             │
 └─────┬─────┘       └─────┬─────┘       │  Sampai   │             │
       ▼                   ▼             └─────┬─────┘             │
 [ Tanyakan & Cek ]  [ Cek Rekening ]          ▼                   │
 [ Email Terdaftar]  [ & Nominal ]       [ Buka Link ]             │
       │                   │             [ Web Resi  ]             │
 ┌─────┴─────┐       ┌─────┴─────┐             │                   │
 ▼           ▼       ▼           ▼       ┌─────┴─────┐             │
[Belum    [Sudah  [Tolak]    [Valid]    ▼           ▼             │
 Login]    Login]    │           │     [Tolak]    [Valid]          │
   │         │       ▼           ▼       │           │             │
   ▼         ▼    (Upload     (Order    ▼           ▼             │
 (Minta   [Generate Ulang)     Status: (Tunggu   (Status:          │
  Login   Kode Akses]           Paid)   Buyer)   Delivered)        │
  Dulu)      │                   │                   │             │
             ▼                   ▼                   ▼             │
        [Kirim Kode       [ Monitor Order ]    [ Timer 48 Jam ]     │
         ke Seller]       (Packing/Kirim)             │             │
             │                                       ▼             │
             ▼                               ┌───────────────┐     │
        [Seller Input                        │ B. Dispute    │     │
         Kode di Web]                        │    Buyer      │     │
             │                               └───────┬───────┘     │
             ▼                                       ▼             │
        [Toko Aktif/                         [ Cek Bukti Foto ]    │
         Role SELLER]                        [ & Unboxing ]        │
             │                                       │             │
             ▼                               ┌───────┴───────┐     │
       ┌───────────┐                         ▼               ▼     │
       │ Moderasi  │                   [Tolak Dispute] [Approve]   │
       │  Produk   │                         │           (Refund)  │
       └─────┬─────┘                         ▼               │     │
             ▼                              [ Order Selesai ]│     │
       [ Cek Konten ]                        │               ▼     │
       [ & Foto ]                            │          [ Transfer]│
             │                               │          [ Refund  ]│
       ┌─────┴─────┐                         │          (Ke Buyer) │
       ▼           ▼                         │                     │
    [Tolak]    [Tayang]                      └──────────┬──────────┘
   (Nonaktif)                                           │
                                             [ Order Selesai & ]
                                             [ Masuk Pending Payout ]
                                                        │
                                                        ▼
                                             [ Cek Rekening Seller ]
                                                        │
                                                        ▼
                                             [ Transfer Manual Bank ]
                                                        │
                                                        ▼
                                             [ Upload Bukti Transfer ]
                                                        │
                                                        ▼
                                             [ Status Payout: PAID ]
                                             (Seller Terima Saldo)
```
---

## 2. Prinsip & Arsitektur Sistem

### 2.1 Arsitektur: Modular Monolith
- **Monolith Wajib**: Seluruh modul (Auth, Buyer, Seller, Order, Payment, Shipping, Dispute, Payout, Admin) berada dalam satu repositori Laravel tanpa arsitektur microservices terdistribusi.
- **Pemisahan Domain**: Struktur kode dipisah secara logis dalam folder domain/service:
  - `app/Services/{Auth,Order,Payment,Shipping,Dispute,Payout}Service.php`
  - `app/Http/Controllers/{Buyer,Seller,Admin}/`
  - `app/Http/Requests/{Buyer,Seller,Admin}/`
  - `app/Policies/` dan `app/Enums/`
- **Dependency Injection**: Seluruh business logic di-inject melalui constructor, tidak menginstansiasi concrete service secara manual (`new Service()`) di dalam controller.

### 2.2 Arsitektur Ekosistem & Integrasi Laravel Plugins Resmi (Wajib & Terstandar)
Untuk menjaga stabilitas, optimasi, keamanan, dan mencegah pembuatan logic native manual (*re-inventing the wheel*), proyek **WAJIB** menggunakan daftar plugin standar berikut sesuai fungsinya masing-masing:

| Domain Use Case | Plugin / Package Wajib | Aturan Penggunaan & Batasan |
| :--- | :--- | :--- |
| **Admin Panel (/admin)** | `filament/filament:^5.0` | **Khusus Admin Panel di rute `/admin`**. Dilarang keras menggunakan Filament untuk mengganti frontend WhiMarket (Blade + Tailwind v4) yang sudah ada. Digunakan eksklusif untuk: Admin Dashboard, Seller Management, Product Moderation, Payment Verification, Order Management, Delivery Claim Verification, Dispute Management, dan Payout Management. |
| **Role & Permissions (RBAC)** | `spatie/laravel-permission:^8.0` | Mengelola role: `buyer`, `seller`, `admin`. Model `User` menggunakan trait `HasRoles`, middleware `role:admin`, `role:seller`, `role:buyer`, direct permissions, serta integrasi Gate & Policy. |
| **Authentication (Native & Google SSO)** | `laravel/socialite` | Autentikasi dual-method: (1) Register/login native WhiMarket menggunakan email & password, (2) Google SSO via Socialite untuk flow redirect & callback. **Kredensial wajib bersumber hanya dari file `.env`**, dilarang keras melakukan hardcode kredensial ke dalam file source code PHP/Blade. |
| **State Machine Transaksi** | `spatie/laravel-model-states:^2.14` | Digunakan untuk: (1) State Machine Order, (2) State Machine Dispute. Seluruh transisi status wajib deterministik dan **tidak boleh ada bypass transition rules** langsung ke database. |
| **SEO & Clean Slugging** | `spatie/laravel-sluggable:^4.0` | Auto-generate slug unik produk (`/produk/{slug}`) dan slug username toko seller (`/seller/@{username}`). |
| **Media / File Upload Management** | `spatie/laravel-medialibrary:^11.0` | Mengelola: Product images, Payment proof, Pre-shipment photo, Shipment receipt photo, Buyer dispute evidence, Video unboxing, Payout transfer proof. **Wajib gunakan private storage untuk bukti sensitif** (bukti bayar, sengketa, payout). Dilarang mengekspos internal storage path secara langsung ke publik. |
| **Kalkulasi Mata Uang / Finansial** | `brick/math` (terpasang v0.18) | Mencegah bug desimal uang/floating point pada perhitungan subtotal, diskon, dan payout. |

### 2.3 Software Design & Patterns
- **SOLID**:
  - *Single Responsibility*: Controller hanya menangani HTTP Request & Response; kalkulasi harga, pemotongan stok, dan mutasi status dipindahkan ke Service/Action.
  - *Open/Closed & Liskov*: State Machine order menggunakan `spatie/laravel-model-states` dengan class state terpisah per status.
  - *Interface Segregation*: Kontrak terpisah untuk PaymentVerifier, ShippingTracker, NotificationDispatcher.
  - *Dependency Inversion*: Controllers bergantung pada interface/service layer, bukan direct model mutators.
- **State Machine Pattern**: Siklus hidup pesanan dikelola secara deterministik via `spatie/laravel-model-states`.

### 2.4 Concurrency, Locking & Consistency
- **Race Condition pada Pengurangan Stok**:
  - Saat `CreateOrder` atau `Checkout`: Wajib menggunakan `DB::transaction()` dengan `Pessimistic Locking` (`ProductVariant::where('id', $id)->lockForUpdate()->first()`).
  - Validasi stok: jika `variant->stock < requested_qty`, throw `InsufficientStockException` dan rollback transaksi secara otomatis.
- **Idempotency**:
  - Endpoint pembayaran, checkout, dan aksi approve admin wajib menggunakan idempotency key atau atomic DB locks (`Cache::lock('order_checkout_' . $userId, 10)`).
- **Deadlock Avoidance**:
  - Urutkan penguncian row database (misal selalu urutkan `variant_id` secara ascending sebelum melakukan `lockForUpdate()`).

### 2.5 Performance, Bottleneck Mitigation, Redis & Scalability
- **Arsitektur Caching, Queue & Session (Redis vs Local Development)**:
  - **Environment Local Development (Laptop / Windows)**:
    - Tidak mewajibkan daemon Redis lokal agar setup instan.
    - `CACHE_STORE=file` atau `database`
    - `QUEUE_CONNECTION=sync` atau `database`
    - `SESSION_DRIVER=file` atau `database`
    - `Cache::lock()` otomatis memakai atomic lock berbasis cache store aktif.
  - **Environment Production (VPS / Staging Server - WAJIB REDIS)**:
    - **Wajib menggunakan Redis Server** (`predis/predis` atau extension `phpredis`) untuk performa tinggi dan nol I/O disk bottleneck:
      ```env
      # Konfigurasi Production VPS
      CACHE_STORE=redis
      QUEUE_CONNECTION=redis
      SESSION_DRIVER=redis
      REDIS_CLIENT=phpredis
      REDIS_HOST=127.0.0.1
      REDIS_PASSWORD=null
      REDIS_PORT=6379
      ```
    - **Atomic Mutex Locking**: `Cache::lock('checkout_user_' . $userId, 10)` di-handle langsung di memori Redis dengan kompleksitas $O(1)$ untuk mencegah race condition checkout.
    - **Queue Worker di VPS**: Dikelola oleh supervisor daemon (`php artisan queue:work redis --tries=3 --timeout=90`).
- **Mitigasi Bottleneck Database & Query**:
  - **Anti-N+1 Query**: Wajib eager loading (`with(['images', 'variants', 'category', 'seller.user'])`) di semua layer controller & API. Gunakan `Model::preventLazyLoading(!app()->isProduction())` di `AppServiceProvider` untuk menangkap N+1 sejak masa testing.
  - **Composite & Selective Indexing**:
    - `products (category_id, status, created_at)`
    - `orders (buyer_id, status)` dan `orders (seller_id, status)`
    - `product_variants (product_id, stock)`
    - `disputes (order_id, status)`
  - **Pagination Contract**: Menggunakan `simplePaginate(12)` atau `paginate(12)` berbatas jelas, dilarang pemanggilan `->get()` tanpa limit pada katalog produk dan riwayat transaksi.
- **Mitigasi Bottleneck Concurrency & Locking**:
  - **Deadlock Prevention**: Penguncian varian produk wajib diurutkan (`ProductVariant::whereIn('id', $ids)->orderBy('id', 'asc')->lockForUpdate()`) agar tidak terjadi siklus circular lock antar transaksi bersamaan.
  - **Idempotency Lock**: Menggunakan atomic lock (`Cache::lock('checkout_user_' . $userId, 10)->get()`) untuk mencegah double submission dari tombol checkout yang ditekan berulang kali oleh buyer.
- **Mitigasi Bottleneck I/O & File Upload**:
  - **Async Image Compression**: Upload foto produk dan bukti transfer diproses ke background queue (`ShouldQueue`), dilarang memblokir lifecycle synchronous HTTP request.
  - **Storage Stream Optimization**: File bukti pembayaran dan sengketa disimpan di disk lokal privat dengan nama unik (`Str::uuid()`), disajikan ke Admin via controller response download/inline stream tanpa ekspos path internal.
- **Scheduled Automation**:
  - `orders:auto-complete`: Menghindari pemindaian seluruh tabel dengan query terseleksi: `Order::where('status', 'delivered')->whereNotNull('inspection_deadline_at')->where('inspection_deadline_at', '<=', now())->chunkById(100, ...)`.

### 2.6 Security
- **Authentication (Dual-Method)**:
  - Register/login native WhiMarket menggunakan email & password (`/login`, `/register`).
  - Laravel Socialite untuk Google SSO (`users.google_id`) sebagai metode login alternatif.
- **Authorization & RBAC**:
  - Laravel Policies & Gates untuk role: `Buyer`, `Seller`, `Admin`.
  - Buyer hanya boleh mengakses order milik sendiri; Seller hanya boleh mengelola produk dan order tokonya sendiri.
- **CSRF & Rate Limiting**:
  - `@csrf` token pada seluruh formulir POST/PUT/DELETE.
  - Rate limiting `throttle:6,1` pada login, registrasi, dan upload bukti pembayaran.
- **File Upload Protection**:
  - Validasi ketat MIME-type (`mimes:jpg,jpeg,png,webp,pdf,mp4`), sanitasi nama file (`Str::uuid()`), pembatasan ukuran (`max:10240`), penyimpanan di disk privat/terkontrol (`storage/app/private` untuk bukti transfer & sengketa).

---

## 3. Skema & Spesifikasi Database (MariaDB)
### 3.1 Enum Backed (PHP 8.4)
- `UserRole`: `BUYER = 'buyer'`, `SELLER = 'seller'`, `ADMIN = 'admin'`
- `SellerStatus`: `PENDING = 'pending'`, `VERIFIED = 'verified'`, `REJECTED = 'rejected'`, `SUSPENDED = 'suspended'`
- `OrderStatus`: 
  - `PENDING_PAYMENT = 'pending_payment'`
  - `PAYMENT_VERIFICATION = 'payment_verification'`
  - `PAID = 'paid'`
  - `PROCESSING = 'processing'`
  - `SHIPPED = 'shipped'`
  - `DELIVERED = 'delivered'`
  - `COMPLETED = 'completed'`
  - `DISPUTED = 'disputed'`
  - `CANCELLED = 'cancelled'`
- `PaymentStatus`: `UNPAID = 'unpaid'`, `PENDING_REVIEW = 'pending_review'`, `VERIFIED = 'verified'`, `REJECTED = 'rejected'`
- `DisputeStatus`: `OPEN = 'open'`, `SELLER_RESPONDED = 'seller_responded'`, `UNDER_ADMIN_REVIEW = 'under_admin_review'`, `RESOLVED_REFUND = 'resolved_refund'`, `RESOLVED_REJECTED = 'resolved_rejected'`
- `PayoutStatus`: `PENDING = 'pending'`, `PROCESSING = 'processing'`, `PAID = 'paid'`, `FAILED = 'failed'`

### 3.2 Tabel dan Relasi Relasional
1. **`users`**:
   - `id`, `name`, `email`, `password` (hashed dengan `Hash::make()` / `bcrypt`, nullable jika registrasi murni via Google SSO), `google_id` (nullable, indexed), `role` (enum: buyer, seller, admin, default: buyer), `phone`, `avatar`, `timestamps`.
2. **`sellers`**:
   - `id`, `user_id` (foreignId, unique), `store_name`, `username` (unique, slug format), `bio`, `bank_name`, `bank_account_number`, `bank_account_name`, `status` (enum, default: pending), `verified_at` (nullable), `rejection_reason` (nullable), `timestamps`.
3. **`addresses`**:
   - `id`, `user_id` (foreignId), `recipient_name`, `phone`, `full_address`, `province`, `city`, `district`, `postal_code`, `is_default` (boolean), `timestamps`.
4. **`categories`**:
   - `id`, `name`, `slug` (unique), `image`, `bg_color`, `is_active` (boolean), `timestamps`.
5. **`products`**:
   - `id`, `seller_id` (foreignId), `category_id` (foreignId), `name`, `slug` (unique), `description`, `price` (decimal 12,2), `condition` (enum: brand_new, like_new, gently_used), `status` (enum: active, inactive, rejected), `timestamps`.
6. **`product_images`**:
   - `id`, `product_id` (foreignId), `image_path`, `sort_order` (integer), `is_primary` (boolean), `timestamps`.
7. **`product_variants`**:
   - `id`, `product_id` (foreignId), `name` (e.g. "Hitam - L"), `sku` (nullable), `price` (decimal 12,2), `stock` (integer, unsigned), `timestamps`.
8. **`wishlists`**:
   - `id`, `user_id` (foreignId), `product_id` (foreignId), `timestamps` (unique: `user_id`, `product_id`).
9. **`carts` & `cart_items`**:
   - `carts`: `id`, `user_id` (foreignId, unique), `timestamps`.
   - `cart_items`: `id`, `cart_id` (foreignId), `product_variant_id` (foreignId), `quantity` (integer), `is_selected` (boolean), `timestamps`.
10. **`orders`**:
    - `id`, `order_number` (unique, e.g. `WHI-YYYYMMDD-XXXX`), `buyer_id` (foreignId -> users), `seller_id` (foreignId -> sellers), `address_snapshot` (json), `total_amount` (decimal 12,2), `shipping_cost` (decimal 12,2), `grand_total` (decimal 12,2), `status` (string, dikelola via `spatie/laravel-model-states`), `inspection_deadline_at` (timestamp, nullable), `completed_at` (timestamp, nullable), `timestamps`.
11. **`order_items`**:
    - `id`, `order_id` (foreignId), `product_variant_id` (foreignId, nullable on delete), `product_name_snapshot` (string), `variant_name_snapshot` (string), `price_snapshot` (decimal 12,2), `quantity` (integer), `subtotal` (decimal 12,2), `timestamps`.
12. **`payments`**:
    - `id`, `order_id` (foreignId, unique), `bank_destination` (string), `sender_bank_name` (string), `sender_account_name` (string), `proof_path` (string), `amount` (decimal 12,2), `status` (enum PaymentStatus), `verified_by` (foreignId -> users, nullable), `verified_at` (timestamp, nullable), `rejection_reason` (text, nullable), `timestamps`.
13. **`shipments`**:
    - `id`, `order_id` (foreignId, unique), `courier_name` (string), `tracking_number` (string), `pre_shipment_photo_path` (string), `receipt_photo_path` (string), `shipped_at` (timestamp, nullable), `delivered_at` (timestamp, nullable), `timestamps`.
14. **`disputes`**:
    - `id`, `order_id` (foreignId, unique), `buyer_id` (foreignId -> users), `reason` (string), `description` (text), `buyer_evidence_paths` (json), `video_unboxing_path` (string, nullable), `seller_response` (text, nullable), `seller_evidence_paths` (json, nullable), `status` (string, via model-states), `resolution_notes` (text, nullable), `resolved_by` (foreignId -> users, nullable), `resolved_at` (timestamp, nullable), `timestamps`.
15. **`escrow_balances` & `payouts`**:
    - `escrow_balances`: `id`, `order_id` (foreignId, unique), `seller_id` (foreignId -> sellers), `amount` (decimal 12,2), `is_released` (boolean), `released_at` (nullable), `timestamps`.
    - `payouts`: `id`, `seller_id` (foreignId -> sellers), `order_id` (foreignId, unique), `amount` (decimal 12,2), `bank_details_snapshot` (json), `transfer_proof_path` (string, nullable), `status` (enum PayoutStatus), `processed_by` (foreignId -> users, nullable), `processed_at` (timestamp, nullable), `timestamps`.
16. **`seller_access_codes`**:
    - `id`, `code` (string, unique, e.g. `WHI-VIP-XXXX`), `email` (string, nullable, email calon seller), `user_id` (foreignId -> users, nullable, user yang me-redeem), `is_used` (boolean, default: false), `used_at` (timestamp, nullable), `created_by` (foreignId -> users, admin pembuat), `timestamps`.
---

## 4. Rincian Implementasi 69 Poin MVP

### Modul 1: Authentication & User Management (Item 1 - 3, 53)
- **Item 1 (Autentikasi Dual-Method: Akun WhiMarket & Google SSO)**:
  - **A. Akun WhiMarket Native (Email & Password)**:
    - **Endpoint & Routing**:
      - `GET /login`: Halaman form masuk Akun WhiMarket (email, password, remember me, switch role toggle) dengan alternatif tombol "Masuk dengan Google".
      - `POST /login`: Validasi input (`email`, `password`), autentikasi via `Auth::attempt()`, regenerasi session ID, dan redirect ke tujuan (atau dashboard sesuai role).
      - `GET /register`: Halaman registrasi Akun WhiMarket baru (nama lengkap, email, nomor HP/WhatsApp, password, konfirmasi password).
      - `POST /register`: Validasi input (`name`, `email` unique, `phone`, `password` min 8 karakter), create user baru dengan hashing `Hash::make()`, otomatis assign role `buyer` via Spatie Permission, dan login sesi otomatis.
  - **B. Google SSO (OAuth 2.0)**:
    - **Wajib gunakan `laravel/socialite`**.
    - **Kredensial Resmi Google OAuth 2.0 Client**:
      - Client ID: `14694636579-6grupovr35n8snetth2qcia0ajch27q7.apps.googleusercontent.com`
      - Client Secret: `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`
      - Redirect URI Local: `http://localhost:8000/auth/google/callback`
      - Redirect URI Production: `https://whimarket.com/auth/google/callback`
    - **Konfigurasi Environment & Services (`config/services.php`)**:
      ```env
      GOOGLE_CLIENT_ID=14694636579-6grupovr35n8snetth2qcia0ajch27q7.apps.googleusercontent.com
      GOOGLE_CLIENT_SECRET=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
      GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
      ```
      ```php
      // config/services.php
      'google' => [
          'client_id' => env('GOOGLE_CLIENT_ID'),
          'client_secret' => env('GOOGLE_CLIENT_SECRET'),
          'redirect' => env('GOOGLE_REDIRECT_URI'),
      ],
      ```
    - Endpoint: `GET /auth/google/redirect`, `GET /auth/google/callback`.
    - Alur: Ambil data dari Google User. Cek apakah email sudah ada di `users`. Jika belum, buat record user baru (`google_id`, `name`, `email`, `avatar`) dan otomatis assign role `buyer` via Spatie Permission. Jika email sudah ada, sinkronkan `google_id` dan login langsung.
  - **C. Post-Login / Post-Register Onboarding (Opsional)**:
    - Setelah registrasi atau login pertama kali (baik via Akun WhiMarket maupun Google SSO), jika buyer belum memiliki alamat tersimpan, tampilkan modal interaktif: *"Lengkapi Alamat Pengiriman Sekarang (Opsional)"*.
    - *Opsi 1 (Isi Sekarang)*: Buyer mengisi No. Handphone/WhatsApp dan Alamat Lengkap. Data otomatis tersimpan di tabel `addresses` sebagai alamat utama (`is_default = true`). Saat checkout nanti, alamat ini langsung terpilih otomatis (Zero Pain Point).
    - *Opsi 2 (Lewati / Nanti Saja)*: Buyer langsung diarahkan ke halaman beranda/belanja. Alamat cukup diisi 1 kali saat buyer pertama kali melakukan transaksi di checkout.
- **Item 2 (Role User)**:
  - **Gunakan plugin `spatie/laravel-permission`** (^8.3).
  - Definisikan role standar: `buyer`, `seller`, `admin`.
  - Model `User` menggunakan trait `HasRoles`. Proteksi rute menggunakan middleware `role:admin`, `role:seller`, `role:buyer` atau permission checks `@role('seller')` di Blade.
- **Item 3 (Logout & Session)**:
  - `POST /logout` dengan invalidasi session token dan regenerate CSRF.
- **Item 53 (Database Users)**:
  - Update tabel `users` untuk support `google_id`, `phone`, dan `avatar`. Relasi role tersimpan rapi di tabel bawaan Spatie (`roles`, `model_has_roles`).

### Modul 2: Buyer Browsing & Discovery (Item 4 - 8, 55 - 58)
- **Item 4 (Browse Produk Dinamis)**:
  - Model `Product` menggunakan plugin `spatie/laravel-sluggable` untuk auto-generate `slug` unik dari `name`.
  - Query builder teroptimasi dengan eager loading `with(['category', 'images', 'seller.user', 'variants'])`.
- **Item 5 (Search & Filter Produk)**:
  - Query builder dengan filter: category slug, min/max price, condition (`brand_new`, `like_new`, `gently_used`), dan sort (`terbaru`, `termurah`, `termahal`).
- **Item 6 (Seller Directory)**:
  - Halaman `/seller` mengambil data `Seller::with('user')->where('status', 'verified')`.
  - Model `Seller` juga menggunakan `spatie/laravel-sluggable` untuk generate username slug toko.
- **Item 7 (Seller Profile)**:
  - Halaman `/seller/{username}` memuat statistik toko, katalog produk aktif, dan ulasan transaksi selesai.
- **Item 8 (Product Detail)**:
  - Halaman `/produk/{slug}` menampilkan detail produk, galeri gambar (`product_images`), opsi pemilihan varian (`product_variants`), jumlah stok dinamis, dan info reputasi seller.

### Modul 3: Cart & Wishlist (Item 9 - 10, 59 - 60)
- **Item 9 (Wishlist Database)**:
  - Fitur toggle wishlist (`POST /wishlist/toggle/{productId}`) dengan auth check.
  - Halaman daftar wishlist `/wishlist`.
- **Item 10 (Keranjang Belanja)**:
  - Endpoint:
    - `GET /keranjang`: Tampilkan item keranjang grouped by seller, kalkulasi subtotal.
    - `POST /keranjang/add`: Tambah item dengan payload `product_variant_id` & `quantity`.
    - `PATCH /keranjang/item/{id}`: Update quantity atau toggle `is_selected`.
    - `DELETE /keranjang/item/{id}`: Hapus item dari keranjang.
  - Subtotal terhitung otomatis secara dinamis via Alpine.js & verifikasi server-side.
  - **Standar UI/UX Konsisten dengan Landing Page & Vision Check**:
    - Tampilan list item dikelompokkan per seller menggunakan card seller mini yang rapi (`bg-white rounded-2xl border border-gray-100/90 shadow-sm p-4 sm:p-5`).
    - Quantity stepper, checkbox seleksi, dan tombol hapus menggunakan micro-interactions Tailwind yang rapi dan responsif.
    - Summary pesanan (subtotal, estimasi) menggunakan card sticky di desktop dan bottom bar tetap di mobile (`sticky bottom-0 bg-white/95 backdrop-blur-md border-t border-gray-100 p-4`).
    - Empty state wajib menggunakan ilustrasi hasil AI generation resmi (`public/assets/illustrations/empty-cart.png`), bukan teks polos atau wireframe abu-abu.
    - **Wajib Vision Inspection**: Buka di browser, ambil screenshot, bandingkan keselarasan tipografi/warna dengan Landing Page, dan pastikan nol horizontal overflow di mobile (375px).

### Modul 4: Seller Onboarding & Product Management (Item 11 - 17, 54, 57, 58)
- **Item 11 (Seller Registration & Aktivasi Kode Akses)**:
  - Calon seller register/login menggunakan Akun WhiMarket (email & password) atau Google SSO di WhiMarket.
  - Calon seller menghubungi Admin melalui DM TikTok / Instagram WhiMarket.
  - Admin menanyakan & memvalidasi email terdaftar, lalu men-generate Kode Akses khusus (VIP Access Code) di Admin Panel.
  - Admin mengirimkan kode akses ke calon seller melalui DM.
  - Seller memasukkan kode akses di form aktivasi `/seller/register` (atau `/gabung-seller`).
  - Setelah kode terverifikasi valid, akun seller langsung aktif (`status = verified`), role di-upgrade ke `seller`, dan seller dapat melengkapi profil toko (`store_name`, `username`, `bio`, info rekening bank) serta langsung mulai berjualan.
- **Item 12 (Seller Access Code Management di Admin Panel)**:
  - Resource Filament di `/admin/seller-access-codes` untuk membuat kode akses unik, menautkan email calon seller, dan memantau status kode (`unused` / `redeemed`).
  - Admin juga dapat mengelola status toko seller (verified, suspended, rejected).
- **Item 13 (Seller Dashboard)**:
  - Endpoint `/seller/dashboard`: Ringkasan pesanan masuk, saldo pending payout, produk aktif, dan status pengiriman.
- **Item 14 (Product CRUD)**:
  - CRUD lengkap di `/seller/products` (Create, Read, Update, Delete/Soft Delete).
- **Item 15 (Upload Foto Produk)**:
  - Upload multiple gambar (`images[]`), optimasi via background/storage, simpan ke `product_images` dengan flag `is_primary` untuk gambar utama.
- **Item 16 (Kategori Produk)**:
  - Dropdown dinamis dari tabel `categories`.
- **Item 17 (Varian Produk)**:
  - Form penambahan varian: kombinasi warna/ukuran, harga, dan stok spesifik per varian (`product_variants`).
  - **Standar UI/UX Konsisten dengan Landing Page & Vision Check**:
    - Form registrasi toko & aktivasi kode akses mengadopsi styling card bersih dengan aksen ungu `#4F26A6` dan badge verified rosette resmi.
    - Dashboard seller `/seller/dashboard` menyajikan card statistik metrik dengan elevation lembut (`shadow-[0_4px_16px_rgba(0,0,0,0.04)]`) dan tipografi `Plus Jakarta Sans`.
    - Tabel produk di desktop wajib bertransformasi menjadi card-list responsif di mobile agar informasi harga dan stok tidak gepeng atau terpotong.
    - Form produk `/seller/products/create` menyediakan dropzone upload gambar dengan live preview thumbnail yang simetris dan rapi.
    - **Wajib Vision Inspection**: Periksa render visual di browser pada viewport mobile (375px) dan desktop (1280px+), pastikan layout rapih tanpa cacat alignment.

### Modul 5: Checkout & Order Creation (Item 18 - 20, 61)
- **Item 18 (Checkout Review)**:
  - Endpoint `GET /checkout`: Memvalidasi item yang di-check (`is_selected = true`) dari keranjang. Menampilkan review barang, ongkir flat/estimasi, dan total pembayaran.
- **Item 19 (Alamat Pengiriman)**:
  - CRUD modal/form alamat pengiriman buyer (`addresses`), pemilihan alamat utama default untuk order.
- **Item 20 (Create Order)**:
  - `POST /checkout/process`:
    - Jalankan dalam `DB::transaction()`.
    - Kunci row varian produk (`lockForUpdate()`).
    - Validasi stok; jika cukup, potong stok `variant->decrement('stock', $qty)`.
    - Generate unique `order_number` (format `WHI-YYYYMMDD-RANDOM`).
    - Buat row `orders`, duplikasi data harga & nama ke `order_items` (snapshot).
    - Hapus item terpilih dari `cart_items`.
    - Buat status pembayaran `payments` dengan status `unpaid`.
    - Buat entry `escrow_balances` untuk menahan dana transaksi.
  - **Standar UI/UX Konsisten dengan Landing Page & Vision Check**:
    - Layout 2 kolom di desktop (kiri 60%: review barang & alamat, kanan 40%: sticky order summary). Di mobile (375px) mengalir 1 kolom teratur dengan sticky action bar di bawah.
    - Modal popup pemilihan dan formulir alamat baru menggunakan backdrop blur halus (`bg-black/40 backdrop-blur-xs`), card `rounded-2xl`, dan animasi fade yang rapi.
    - **Wajib Vision Inspection**: Bandingkan tombol CTA "Bayar Sekarang" dengan tombol "Belanja Sekarang" di hero Landing Page; pastikan ukuran, shadow ungu, dan padding 100% konsisten.

### Modul 6: Payment & Manual Transfer (Item 21 - 24, 62)
- **Item 21 (Transfer Manual)**:
  - Halaman instruksi pembayaran menampilkan rekening resmi WhiMarket (Bank BCA xxxxx a/n PT WhiMarket Indonesia), nominal tagihan, dan batas waktu 24 jam.
- **Item 22 (Upload Bukti Transfer)**:
  - Form upload bukti transfer: nama bank pengirim, nama pemilik rekening, file foto bukti (`proof_path`).
  - Update status `payments` -> `pending_review` dan status order -> `payment_verification`.
- **Item 23 (Verifikasi Pembayaran Admin)**:
  - Admin approve: status `payments` -> `verified`, status `orders` -> `paid`.
  - Admin reject: masukkan alasan penolakan, status `payments` -> `rejected`.
- **Item 24 (Upload Ulang Bukti)**:
  - Buyer dapat mengunggah ulang bukti transfer baru jika bukti sebelumnya ditolak.
  - **Standar UI/UX Konsisten dengan Landing Page & Vision Check**:
    - Card informasi rekening bank WhiMarket dilengkapi tombol salin nomor rekening reaktif Alpine.js dengan tooltip feedback interaktif.
    - Dropzone upload bukti transfer menggunakan area bergaris putus-putus ungu lembut (`border-2 border-dashed border-[#4F26A6]/30 bg-[#F3EEFF]/30 rounded-2xl`) dan preview gambar sebelum submit.
    - **Wajib Vision Inspection**: Periksa di mobile 375px agar card nominal transfer dan countdown batas bayar 24 jam tetap rapi dan tidak tumpang tindih.

### Modul 7: Order Fulfillment & Shipping (Item 25 - 31, 63)
- **Item 25 (State Machine Status Pesanan)**:
  - **Wajib gunakan `spatie/laravel-model-states`** untuk memodelkan transisi status order (`OrderStatusState`):
    `PendingPayment` -> `PaymentVerification` -> `Paid` -> `Processing` -> `Shipped` -> `Delivered` -> `Completed` (atau `Disputed` / `Cancelled`).
  - Setiap transisi memicu event domain terpisah dan memblokir lompatan status yang tidak sah secara otomatis.
- **Item 26 (Order Detail Buyer)**:
  - Halaman `/pesanan/{order_number}`: Visual timeline progress pesanan, item snapshot, kurir dan no resi, serta foto bukti packing dari seller.
- **Item 27 (Order Management Seller)**:
  - Halaman `/seller/orders`: Filter tab berdasarkan state (`processing`, `shipped`, `delivered`, `disputed`). Action tombol dinamis sesuai state order saat ini.
- **Item 28 (Foto Barang Sebelum Dikirim)**:
  - Form fulfillment seller: Wajib upload foto kondisi barang saat dipacking (`pre_shipment_photo_path`).
- **Item 29 & 30 (Resi Pengiriman & Foto Resi)**:
  - Seller memilih kurir, memasukkan nomor resi (`tracking_number`), dan mengunggah foto/scan resi (`receipt_photo_path`).
  - Eksekusi transisi status ke `Shipped`.
- **Item 31 (Cek Resi Eksternal)**:
  - Tombol direct link ke aggregator cek resi (CekResi.com / Berdu) dengan parameter resi kurir.
  - **Standar UI/UX Konsisten dengan Landing Page & Vision Check**:
    - Halaman `/pesanan/{order_number}` dan `/seller/orders` menampilkan timeline status pesanan horizontal di desktop dan vertikal di mobile, menggunakan palet status badges resmi.
    - Galeri foto kondisi barang sebelum kirim dan foto resi dilengkapi modal zoom lightbox Alpine.js.
    - **Wajib Vision Inspection**: Uji visual di layar mobile dan tablet, pastikan timeline langkah fulfillment responsif dan tidak terpotong.

### Modul 8: Receiving & Inspection Period (Item 32 - 34)
- **Item 32 (Konfirmasi Barang Diterima - 2 Jalur Validasi)**:
  - **Jalur A (Fast Path - Buyer Langsung)**:
    - Buyer menerima paket fisik, lalu menekan tombol `Barang Sudah Diterima` di halaman order detail.
    - Status pesanan langsung bertransisi menjadi `delivered`.
  - **Jalur B (Failsafe Zero-Budget - Seller Klaim + Admin Verifikasi Web Resi)**:
    - Digunakan jika buyer pasif / tidak kunjung mengonfirmasi setelah barang tiba.
    - Seller memantau nomor resi di situs kurir eksternal (J&T / JNE / SiCepat / CekResi.com).
    - Begitu status kurir terlihat `DELIVERED`, Seller menekan tombol `Ajukan Klaim Paket Sampai` di Seller Dashboard.
    - Pengajuan masuk ke antrean Admin `/admin/orders/delivery-claims`.
    - Admin membuka link tracking publik gratis kurir untuk memverifikasi kebenaran fisik serah terima.
    - Jika data resi kurir terbukti `DELIVERED`, Admin menekan `Verifikasi Valid` $\rightarrow$ Status pesanan berubah menjadi `delivered`.
- **Item 33 (Masa Pemeriksaan 48 Jam)**:
  - Saat status order resmi menjadi `delivered` (baik via Jalur A maupun Jalur B):
    - Sistem menyetel timestamp `inspection_deadline_at = now()->addHours(48)`.
    - Banner & countdown timer 48 jam tampil di akun Buyer.
    - Opsi Buyer selama 48 jam:
      1. Klik `Terima / Selesaikan Sekarang` $\rightarrow$ Order langsung `completed`, dana dicairkan ke seller.
      2. Klik `Ajukan Komplain (Dispute)` $\rightarrow$ Countdown timer dibekukan, order masuk status `disputed`, dana tetap ditahan di escrow.
      3. Buyer pasif/diam $\rightarrow$ Menunggu batas waktu 48 jam berakhir.
- **Item 34 (Auto Complete Cron/Scheduler)**:
  - Laravel Console Command `php artisan orders:auto-complete` dijadwalkan di `routes/console.php` (`everyThirtyMinutes()`).
  - Logika: Ambil semua order dengan status `delivered`, di mana `inspection_deadline_at <= now()`, dan tidak memiliki dispute terbuka (`status != disputed`).
  - Mutasi otomatis status order menjadi `completed`, melepaskan dana escrow ke tabel `payouts` seller.

### Modul 9: Dispute & Resolution (Item 35 - 41, 64)
- **Item 35 (Laporkan Masalah / Ajukan Dispute)**:
  - Tombol `Komplain / Masalah` aktif selama status `delivered` sebelum order selesai.
  - Status order bertransisi menjadi `disputed`.
- **Item 36 & 37 (Upload Bukti & Video Unboxing)**:
  - Form dispute buyer: Alasan komplain (barang rusak, tidak sesuai deskripsi, hilang), deskripsi detail, multiple foto bukti kendala, dan upload link/file video unboxing.
- **Item 38 (Seller Response)**:
  - Seller melihat detail komplain di `/seller/disputes/{id}` dan memiliki batas waktu 48 jam untuk memberikan tanggapan beserta foto/bukti tandingan packing.
- **Item 39 (Admin Review)**:
  - Admin melihat dashboard sengketa di `/admin/disputes/{id}`: membandingkan foto sebelum kirim seller, bukti resi kurir, bukti komplain buyer, dan video unboxing.
- **Item 40 (Approve / Reject Dispute)**:
  - Admin menetapkan keputusan final:
    - `Approve Dispute (Refund Buyer)`: Status dispute `resolved_refund`.
    - `Reject Dispute (Release Dana ke Seller)`: Status dispute `resolved_rejected`. Order diselesaikan.
- **Item 41 (Refund / Retur Manual)**:
  - Jika dispute dimenangkan buyer: Admin memproses pengembalian dana manual ke rekening buyer, mengunggah bukti refund, dan menandai order `cancelled`.
  - **Standar UI/UX Konsisten dengan Landing Page & Vision Check**:
    - Form pengajuan dispute dan respon seller menggunakan card terstruktur dengan banner alert amber yang estetik (`bg-amber-50 border border-amber-200/60 rounded-xl`).
    - Upload multi-foto dan video unboxing disajikan dalam grid thumbnail rapi dengan tombol remove yang responsif.
    - **Wajib Vision Inspection**: Inspeksi visual di browser, pastikan form komplain dan detail sengketa memiliki estetika yang serasi dengan Landing Page.

### Modul 10: Dana & Payout Escrow (Item 42 - 45, 65)
- **Item 42 (Dana Ditahan / Rekening Bersama Escrow)**:
  - Dana buyer masuk ke rekening WhiMarket dan dicatat di tabel `escrow_balances` dengan status `is_released = false`. Seller belum dapat mencairkan dana selama transaksi aktif.
- **Item 43 (Pending Payout)**:
  - Begitu order mencapai status `completed` (baik konfirmasi manual maupun auto-complete), sistem membuat baris baru di tabel `payouts` dengan status `pending` dan amount sebesar harga produk (dikurangi komisi jika ada).
- **Item 44 (Admin Transfer ke Seller)**:
  - Admin membuka menu `/admin/payouts`, melihat daftar pencairan tertunda beserta nomor rekening bank seller.
  - Admin melakukan transfer manual via internet banking ke rekening seller.
- **Item 45 (Upload Bukti Transfer Seller)**:
  - Admin mengunggah foto bukti transfer bank (`transfer_proof_path`), mengubah status payout menjadi `paid`. Notifikasi dikirimkan ke seller.

### Modul 11: Admin Control Panel via Filament v5 (Item 46 - 52)
**Arsitektur Khusus Admin**: Seluruh fungsi panel admin di rute `/admin` dibangun menggunakan **`filament/filament:^5.0`**. **Dilarang keras mengganti atau merusak tampilan frontend WhiMarket yang sudah ada**.
- **Item 46 (Admin Dashboard Overview)**:
  - Dashboard Filament Widget: Total transaksi aktif, volume dana ditahan (escrow), pending approval seller, pending verifikasi pembayaran, dispute terbuka, dan pending payout.
- **Item 47 (Seller Management - Filament Resource)**:
  - `SellerResource`: Table daftar pengajuan seller, Action Modal untuk review rekening/data toko, tombol Approve dan Reject (dengan input catatan alasan penolakan).
- **Item 48 (Product Moderation - Filament Resource)**:
  - `ProductResource`: Monitoring katalog produk seller, status toggle, filter kategori, dan Action menonaktifkan/reject produk yang melanggar.
- **Item 49 (Payment Management - Filament Resource)**:
  - `PaymentResource`: Verifikasi bukti transfer buyer (infolist image preview dengan zoom, input alasan reject, Action Approve mengubah status order menjadi `paid`).
- **Item 50 (Order Management - Filament Resource)**:
  - `OrderResource`: Monitoring seluruh lifecycle transaksi sistem dari state `pending_payment` hingga `completed`.
  - **Delivery Claim Verification**: Tab/filter khusus untuk memeriksa klaim paket sampai dari seller, direct link ke web tracking publik kurir, dan Action verifikasi valid untuk memicu status `delivered` & countdown timer 48 jam.
- **Item 51 (Dispute Management - Filament Resource)**:
  - `DisputeResource`: Menangani komplain buyer vs seller, membandingkan bukti foto kondisi sebelum kirim, bukti resi kurir, foto kendala buyer, dan video unboxing. Action: Putuskan Refund Buyer atau Selesaikan Pesanan ke Seller.
- **Item 52 (Payout Management - Filament Resource)**:
  - `PayoutResource`: Menampilkan daftar dana seller siap cair (order selesai), detail rekening bank seller, form input transfer manual, dan upload bukti transfer pencairan (`transfer_proof_path`).
### Modul 12: Fondasi Sistem, Keamanan & Pengujian (Item 66 - 69)
- **Item 66 (RBAC & Authorization Guard)**:
  - Implementasi Policy: `OrderPolicy`, `ProductPolicy`, `SellerPolicy`, `DisputePolicy`.
- **Item 67 (Form Request Validation)**:
  - 100% input divalidasi menggunakan dedicated `FormRequest`:
    - `StoreProductRequest`, `UpdateProductRequest`
    - `CheckoutOrderRequest`, `UploadPaymentProofRequest`
    - `FulfillOrderRequest`, `CreateDisputeRequest`, `SellerResponseDisputeRequest`
- **Item 68 (Automated Testing Suite)**:
  - Menggunakan PHPUnit:
    - **Unit Tests**: Kalkulasi harga order, validasi State Machine transition logic.
    - **Feature Tests**:
      - `AuthenticationTest`: Registrasi akun WhiMarket (email & password), login native, Google SSO mock flow, login/logout, session expiry.
      - `ProductBrowseTest`: Filter kategori, pencarian keyword, sort.
      - `CheckoutConcurrencyTest`: Simulasi 2 buyer checkout varian dengan sisa stok 1 secara bersamaan (pembuktian zero overselling).
      - `OrderLifecycleTest`: Alur utuh dari order -> transfer bukti -> admin verify -> seller ship resi -> buyer receive -> complete -> payout.
      - `DisputeResolutionTest`: Flow komplain buyer -> tanggapan seller -> keputusan admin.
- **Item 69 (Production Deployment Readiness & Redis di VPS)**:
  - Database migration dan seeder siap pakai (`php artisan migrate --force`, `php artisan db:seed`).
  - **Aturan Redis Environment**:
    - **Local Development**: Driver fallback fleksibel (`CACHE_STORE=file/database`, `QUEUE_CONNECTION=sync/database`, `SESSION_DRIVER=file`) sehingga agent/developer di laptop Windows tidak terblokir kebutuhan daemon redis.
    - **Production VPS**: **WAJIB REDIS** (`CACHE_STORE=redis`, `QUEUE_CONNECTION=redis`, `SESSION_DRIVER=redis`). Supervisor worker menjalankan antrean background via `php artisan queue:work redis`.
  - Konfigurasi caching production (`php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`).
  - Storage symlink (`php artisan storage:link`).
---

## 5. Rencana Tahapan Eksekusi (Roadmap Agent)

```
[Fase 1: Instalasi Plugin Teruji & Fondasi Database]
  ├── Instalasi Plugin Wajib:
  │     composer require filament/filament:^5.0 spatie/laravel-permission:^8.0 spatie/laravel-model-states:^2.14 spatie/laravel-sluggable:^4.0 spatie/laravel-medialibrary:^11.0 laravel/socialite -W
  ├── Publish Config & Migrasi Plugin (Filament, Spatie Permission, MediaLibrary, Model States)
  ├── Setup Panel Filament Admin di rute `/admin`
  ├── Migrasi 15 Tabel Relasional Domain WhiMarket
  ├── Definisi Model States (OrderStatusState, DisputeStatusState) & Spatie Roles (buyer, seller, admin)
  ├── Model Eloquent, Relasi, Casts, Media Collections (Private vs Public), & Model Factories
  └── Seeder Data Awal (Kategori Realistis, Akun Demo Admin, Seller, Buyer)

[Fase 2: Autentikasi & Otorisasi]
  ├── Autentikasi Native WhiMarket (Form Registrasi & Login Email/Password)
  ├── Integrasi Laravel Socialite (Google SSO) via Environment Only
  ├── Modal Post-Register / Post-SSO Onboarding Alamat (Opsional)
  ├── Middleware Spatie Role (`role:admin`, `role:seller`, `role:buyer`)
  └── Policy Otorisasi Sumber Daya (Order, Product, Seller, Dispute)
[Fase 3: Migrasi Katalog Dinamis & Keranjang]
  ├── Hubungkan View Landing & Shop ke Database Eloquent (Tanpa Mengubah UI)
  ├── Implementasi Fitur Wishlist & Alpine.js Toggle
  ├── Modul Keranjang Belanja (Add, Update Qty, Delete, Select Item, Subtotal)
  └── Vision Inspection & Komparasi Visual Halaman Keranjang dengan Landing Page (Responsive & Neat)

[Fase 4: Seller Portal & Product Management]
  ├── Form Registrasi Toko & Pengajuan Seller
  ├── Portal Seller: Dashboard & Kelola Produk (Blade + Tailwind v4 senada)
  ├── Upload Multi-Foto via MediaLibrary & Setup Varian/Stok
  └── Vision Inspection & Komparasi Visual Dashboard & Form Produk Seller dengan Landing Page

[Fase 5: Checkout, Transaksi & Escrow]
  ├── Order Processing dengan Pessimistic Locking (lockForUpdate)
  ├── Modul Transfer Manual & Upload Bukti Pembayaran (Private Storage)
  ├── Verifikasi Pembayaran Buyer di Panel Admin Filament
  └── Vision Inspection & Komparasi Visual Halaman Checkout & Pembayaran dengan Landing Page

[Fase 6: Fulfillment, Shipping & Verifikasi Paket Sampai]
  ├── Seller Upload Foto Barang Sebelum Kirim + Resi Kurir
  ├── Halaman Tracking & Order Detail Buyer
  ├── 2 Jalur Verifikasi Paket: Jalur Cepat Buyer vs Jalur Failsafe (Seller Klaim + Admin Cek Resi)
  ├── Countdown Timer 48 Jam & Scheduler Auto-Complete (`orders:auto-complete`)
  └── Vision Inspection & Komparasi Visual Halaman Tracking & Order Detail dengan Landing Page

[Fase 7: Sengketa (Dispute), Mediasi & Payout Seller]
  ├── Form Komplain Buyer (Bukti Foto & Video Unboxing di Private Storage)
  ├── Tanggapan Seller & Bukti Tandingan
  ├── Filament Dispute Resource: Mediasi & Putusan Admin
  ├── Filament Payout Resource: Transfer Manual Bank ke Seller & Upload Bukti Payout
  └── Vision Inspection & Komparasi Visual Halaman Dispute dengan Landing Page

[Fase 8: Verifikasi, Testing & Hardening]
  ├── Feature Test PHPUnit Menyeluruh (Auth, Browse, Concurrency Checkout, Order, Dispute)
  ├── Uji Concurrency Zero Overselling
  ├── Audit Visual & Responsivitas Menyeluruh (Vision Check pada Breakpoint Mobile 375px, Tablet 768px, Desktop 1280px+)
  ├── Pembersihan Pint Formatter (`vendor/bin/pint --format agent`)
  └── Panduan Deployment Production (Redis Queue & Cache di VPS)
```

Dokumen ini menjadi pedoman absolut tanpa deviasi fitur agar agent dapat menyelesaikan implementasi MVP WhiMarket secara komprehensif dan tuntas.
