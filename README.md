# WhiMarket (Laravel 11 + Tailwind CSS v4 + Alpine.js)

WhiMarket adalah platform marketplace pre-loved & merchandise personal terkurasi dari kreator, artis, selebgram, dan streamer Indonesia.

---

## 🛠 Tech Stack

- **Backend**: Laravel 11 (PHP 8.4)
- **Frontend / Templating**: Blade, Alpine.js v3, Tailwind CSS v4
- **Asset Bundler & Compression**: Vite 8, Sharp (`scripts/compress-images.js`), `vite-plugin-image-optimizer`
- **Web Server / Hosting**: Plesk AlmaLinux, Apache / Nginx Reverse Proxy, PHP 8.4 FastCGI
- **Database**: MySQL / MariaDB (production), SQLite (local dev)

---

## 🚀 Fitur yang Sudah Live

- **Landing Page (`/`)**: Hero section, kategori populer (responsive slider), produk terbaru, seller populer, alur belanja, banner kreator, newsletter.
- **Shop Catalog (`/belanja`)**: Filter kategori interaktif, dynamic sort dropdown, filter drawer responsive (mobile bottom sheet), reactive counter.
- **Browse Seller (`/seller`)**: Daftar seller terverifikasi, statistik followers & rating, filter kategori kreator.
- **Seller Profile (`/seller/@username`)**: Profil kreator, tab ulasan terverifikasi, barang dijual, tentang seller.
- **Info Pages (`/info/*`)**: Halaman panduan belanja, cara jual, privasi, dan syarat ketentuan dengan aset 3D claymorphic teroptimasi.

---

## 💻 Panduan Instalasi Lokal

```bash
# 1. Clone repository
git clone https://github.com/Jackm4ss/whimarket.git
cd whimarket

# 2. Install dependencies
composer install
bun install

# 3. Konfigurasi environment
cp .env.example .env
php artisan key:generate

# 4. Build assets
bun run build

# 5. Jalankan server
php artisan serve
```
