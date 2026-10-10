# Toko Skincare

## Deskripsi Singkat Proyek

Toko Skincare adalah aplikasi **e-commerce penjualan produk skincare** berbasis web. Pelanggan dapat menjelajahi katalog, memfilter kategori, mengelola keranjang, melakukan checkout, serta membayar via **Midtrans** (VA/QRIS/e-Wallet/kartu) atau **transfer manual**. Admin dapat mengelola produk, kategori, data toko, pengguna, dan memverifikasi pembayaran lewat panel berbasis AdminLTE.

## Fitur Utama / Fungsi Program

- **Autentikasi & peran** — registrasi, login, logout; role `admin` dan `customer` (Spatie Permission); redirect berdasarkan role (admin → `/admin/dashboard`, customer → beranda); akun nonaktif diblokir.
- **Katalog produk** — tampilan produk aktif, filter kategori, halaman detail produk, dapat diakses tanpa login.

- **Keranjang belanja** — tambah, ubah jumlah, hapus, validasi stok, total otomatis, badge jumlah di navbar.

- **Checkout** — ringkasan pesanan, validasi field, pesanan tersimpan dengan status "Menunggu Pembayaran".
- **Pembayaran** — Snap popup Midtrans (sandbox/produksi) dengan redirect & update status otomatis; konfirmasi transfer manual; stok berkurang otomatis saat status jadi "dibayar".
- **Riwayat pesanan** — daftar pesanan customer + halaman pembayaran per pesanan.

- **Panel admin (AdminLTE)** — dashboard ringkasan (produk, pesanan, kategori, pengguna, pesanan menunggu); CRUD produk dengan upload foto yang diresize & dikompresi WebP (soft delete); kelola kategori + toggle aktif; kelola data toko (kontak, alamat, rekening bank) yang tampil dinamis di halaman bayar; kelola pengguna (ubah role, aktif/nonaktif); verifikasi/tolak pembayaran.

- **Keamanan** — CSRF, escape output Blade, mass-assignment aman, rate limit login, verifikasi `signature_key` webhook Midtrans.

## Struktur Folder / File Utama

```
app/
  Http/Controllers/Admin/          # Controller panel admin
  Http/Controllers/MidtransNotificationController.php   # Webhook notifikasi Midtrans
  Livewire/Forms/LoginForm.php     # Form login + blokir akun nonaktif
  Models/                            # User, Kategori, Produk, Pesanan, DetailPesanan, Pembayaran, DataToko
  Services/
    Keranjang.php                   # Layanan keranjang berbasis session
    MidtransService.php             # Layanan Snap Midtrans
config/
  adminlte.php                      # Konfigurasi AdminLTE (menu, dashboard_url, aset)
  services.php                      # Konfigurasi Midtrans (server/client key)
database/
  migrations/                       # Skema tabel
  seeders/DatabaseSeeder.php        # Role, user contoh, kategori, produk, data toko
resources/views/
  admin/                            # View panel admin (AdminLTE)
  layouts/                          # Layout toko (store) & auth (guest)
  livewire/pages/store/            # Volt pages: beranda, katalog, keranjang, checkout, pembayaran, pesanan
  livewire/pages/auth/             # Volt pages auth: login, register, dll.
  vendor/adminlte/                 # Override navbar AdminLTE (pintasan Beranda)
routes/
  web.php                           # Route publik, checkout, pesanan, admin, webhook
  auth.php                          # Route autentikasi (Breeze Livewire)
tests/                               # Pest: unit & feature (60+ test)
```

## Cara Instalasi / Menjalankan Program

```bash
# 1. Install dependency
composer install
npm install

# 2. Env & kunci aplikasi
cp .env.example .env
php artisan key:generate

# 3. Atur konfigurasi di .env
#    DB_CONNECTION / DB_DATABASE (MySQL atau SQLite), APP_URL,
#    MIDTRANS_SERVER_KEY & MIDTRANS_CLIENT_KEY

# 4. Migrasi + seed (membuat role, user contoh, produk, data toko)
php artisan migrate --seed

# 5. Symlink storage (untuk foto produk) & build aset
php artisan storage:link
npm run build          # produksi; gunakan npm run dev untuk pengembangan

# 6. Jalankan server
php artisan serve      # default: http://127.0.0.1:8000
```

**Akun contoh (seeder):**

| Role     | Email             | Password   |
| -------- | ----------------- | ---------- |
| Admin    | `admin@gmail.com` | `admin123` |
| Customer | `cust@gmail.com`  | `cust123`  |

## Contoh Penggunaan / Demo

- Buka `http://127.0.0.1:8000` → beranda & katalog toko.
- Tambah produk ke keranjang → buka `/keranjang` → `/checkout` (wajib login).
- Di halaman pembayaran pilih **Bayar via Midtrans** (popup Snap sandbox/adalah) atau **Transfer Bank** + kirim konfirmasi.
- Login admin (`/login` dengan email admin) → otomatis ke `/admin/dashboard` → kelola produk, kategori, toko, pengguna, dan verifikasi pesanan.
- Pengujian otomatis:

```bash
php artisan test --compact      # atau: composer test
vendor/bin/pint                 # format kode
```

_Catatan demo Midtrans sandbox: kartu uji `4811 1111 1111 1114`, expiry bebas di masa depan, CVV `123`, kode OTP `112233`. Webhook `/midtrans/notification` diuji manual via dashboard/simulator sandbox (tidak terjangkau dari localhost)._

## Teknologi yang Digunakan

- **Backend** — PHP 8.3, Laravel 13, Laravel Breeze (Livewire stack)
- **Frontend** — Livewire 3 + Volt, Alpine.js, Tailwind CSS 3 (toko); AdminLTE v4 / Bootstrap 5 (admin)
- **Database** — MySQL
- **Paket pendukung** — spatie/laravel-permission, midtrans/midtrans-php, intervention/image, jeroennoten/laravel-adminlte
- **Testing / Tools** — Pest / PHPUnit, Laravel Pint
