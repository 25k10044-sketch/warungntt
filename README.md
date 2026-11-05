# Warung NTT

Aplikasi warung online untuk mengenal dan memesan makanan khas Nusa Tenggara Timur, seperti se'i sapi, jagung bose, ikan kuah asam, kolo, dan jagung titi.

## Link proyek

- **Aplikasi deploy:** Belum dideploy. Setelah deploy, ganti dengan `https://<nama-layanan>.onrender.com`.
- **Repository GitHub:** Ganti dengan `https://github.com/<username>/<repository>` setelah repository dipublikasikan.
- **Panduan deploy:** [DEPLOYMENT.md](DEPLOYMENT.md) (Render + Aiven MySQL) atau [DEPLOYMENT_INFINITYFREE.md](DEPLOYMENT_INFINITYFREE.md) (InfinityFree + FileZilla).

## Fitur

- Katalog menu dengan pencarian, filter kategori, detail cerita kuliner, dan menu terlaris.
- Registrasi/login email dan kata sandi, serta login Google OAuth.
- Keranjang, checkout, catatan pesanan, dan simulasi pembayaran COD/transfer/QRIS.
- Riwayat dan status pesanan, favorit, serta ulasan dan rating untuk menu yang sudah dipesan dan selesai.
- Dashboard admin, CRUD menu/kategori, upload foto, pengelolaan pesanan/pengguna, moderasi ulasan, dan laporan penjualan.
- Tampilan responsif bertema kain tenun NTT.

## Teknologi

- PHP 8.2+, Laravel 12, Laravel Breeze (Blade), Laravel Socialite.
- MySQL (Aiven) atau SQLite untuk pengembangan lokal.
- Blade, Tailwind CSS, Alpine.js, Vite, dan Chart.js.
- Docker untuk deployment; HTTPS production dan koneksi MySQL TLS/CA disiapkan.

## Persyaratan lokal

- PHP 8.2+ dengan ekstensi PDO SQLite (`pdo_sqlite`) untuk konfigurasi lokal bawaan.
- Composer 2.
- Node.js dan npm.

## Instalasi lokal (Windows PowerShell)

Clone repository, lalu jalankan perintah berikut dari folder proyek:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
if (-not (Test-Path database\database.sqlite)) { New-Item -ItemType File database\database.sqlite | Out-Null }
php artisan migrate --seed
npm ci
npm run build
php artisan storage:link
php artisan serve
```

Buka `http://localhost:8000` dan gunakan hostname itu secara konsisten (jangan berganti ke `127.0.0.1`). Jika ingin hot reload aset saat mengembangkan UI, jalankan `npm run dev` di terminal kedua.

Seeder membuat kategori, 14 menu, akun demo, beberapa pesanan, ulasan, dan favorit. Jalankan ulang `php artisan migrate:fresh --seed` hanya pada database lokal yang boleh dihapus.
Jika database lokal yang sudah ada belum memiliki kategori/menu atau akun demo, jalankan `php artisan db:seed` untuk mengisi semuanya. Perintah ini aman dijalankan ulang untuk katalog demo lokal.

## Akun demo lokal

| Role | Email | Kata sandi |
|---|---|---|
| Admin | `admin@warungntt.test` | `Admin12345` |
| Pelanggan | `pelanggan@warungntt.test` | `Pelanggan12345` |

Kedua akun demo di atas dibuat otomatis oleh `php artisan migrate --seed` atau `php artisan db:seed`; tidak perlu mendaftar lagi. Keluar dari akun Google terlebih dahulu, lalu masuk memakai salah satu email dan kata sandi demo. Akun admin membuka modul admin, sedangkan akun pelanggan membuka modul belanja. Satu akun tidak bisa memiliki dua tampilan sekaligus. Login Google membuat/menghubungkan akun ber-role pelanggan, bukan akun admin.

Kredensial ini bersifat publik untuk keperluan tugas/demo. Jangan gunakan pada layanan produksi publik atau mengaktifkan seeder demo di produksi tanpa memahami risikonya.

## Google OAuth

1. Buat OAuth Client ID tipe **Web application** di Google Cloud Console.
2. Tambahkan authorized redirect URI lokal: `http://localhost:8000/auth/google/callback`.
3. Isi `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, dan `GOOGLE_REDIRECT_URI` pada `.env` lokal (template variabel sudah tersedia):

```dotenv
GOOGLE_CLIENT_ID=client-id-dari-google
GOOGLE_CLIENT_SECRET=client-secret-dari-google
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

Tambahkan redirect URI HTTPS untuk domain deployment di Google Cloud Console dan gunakan URL yang sama pada `GOOGLE_REDIRECT_URI` di Render. Pengguna yang mendaftar melalui Google mendapat role pelanggan.

## Database MySQL/Aiven dan deployment

`.env.example` memakai SQLite agar instalasi lokal mudah. Untuk MySQL/Aiven, ubah `DB_CONNECTION=mysql` dan isi host, port, nama database, username, dan password. Pada production, konfigurasi CA certificate dan verifikasi TLS wajib diaktifkan.

Lihat [DEPLOYMENT.md](DEPLOYMENT.md) untuk langkah menyiapkan Aiven, Secret File CA di Render, environment variables, health check, HTTPS, batasan storage ephemeral, dan menjalankan Docker.

Foto menu disimpan di storage lokal. File upload pada free instance Render dapat hilang saat deploy/restart; gunakan object storage untuk penyimpanan persisten.

## Pengujian

```powershell
php artisan test
vendor\bin\pint --test
npm run build
```

## Lisensi

Proyek ini menggunakan lisensi MIT.
