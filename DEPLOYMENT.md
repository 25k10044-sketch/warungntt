# Deploy Warung NTT ke Render + Aiven

Panduan ini memakai Dockerfile pada repository, Render Web Service untuk aplikasi, dan Aiven MySQL sebagai database. Render akan menyediakan HTTPS untuk domain `onrender.com`; jangan menaruh password database, `APP_KEY`, atau kredensial OAuth di Git.

## 1. Siapkan database Aiven

1. Buat layanan MySQL di Aiven, lalu buat database dan user aplikasi.
2. Dari halaman koneksi layanan, catat hostname, port, nama database, dan username.
3. Unduh CA certificate Aiven. Simpan file tersebut di komputer dengan nama `ca.pem`; jangan commit ke repository.
4. Pastikan akses jaringan layanan Aiven mengizinkan koneksi dari aplikasi Render. Gunakan pengaturan jaringan Aiven yang tersedia untuk akun/plan Anda.

## 2. Buat layanan Render

1. Push repository ke GitHub, lalu pilih **New > Web Service** di Render dan hubungkan repository.
2. Pilih **Docker** sebagai runtime. Tidak perlu mengisi build/start command khusus; `Dockerfile` dan `docker/entrypoint.sh` menanganinya.
3. Atur health check path ke `/up`.
4. Pilih instance sesuai ketersediaan plan. Free web service dapat idle/sleep, dan disk lokalnya tidak persisten.
5. Tambahkan environment variables berikut di pengaturan layanan Render:

| Key | Nilai |
|---|---|
| `APP_NAME` | `Warung NTT` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | Hasil `php artisan key:generate --show` dari komputer lokal |
| `APP_URL` | `https://<nama-layanan>.onrender.com` |
| `LOG_CHANNEL` | `stderr` |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | Hostname dari Aiven |
| `DB_PORT` | Port dari Aiven |
| `DB_DATABASE` | Nama database Aiven |
| `DB_USERNAME` | Username Aiven |
| `DB_PASSWORD` | Password user Aiven |
| `MYSQL_ATTR_SSL_CA` | `/etc/secrets/ca.pem` |
| `MYSQL_ATTR_SSL_VERIFY_SERVER_CERT` | `true` |
| `SESSION_DRIVER` | `database` |
| `SESSION_SECURE_COOKIE` | `true` |
| `CACHE_STORE` | `database` |
| `QUEUE_CONNECTION` | `database` |
| `FILESYSTEM_DISK` | `public` |
| `RUN_MIGRATIONS` | `true` |
| `SEED_DEMO_DATA` | `false` |

6. Di bagian **Secret Files** Render, unggah CA certificate dengan filename `ca.pem`. Path yang dipakai aplikasi adalah `/etc/secrets/ca.pem`. Jangan simpan isi certificate sebagai environment variable biasa.
7. Deploy layanan. Entrypoint akan membuat symbolic link storage, menjalankan `php artisan migrate --force`, lalu menjalankan Apache pada port yang disediakan Render.

Jika menghubungkan domain sendiri, atur `APP_URL` ke alamat HTTPS domain tersebut dan tambahkan domain pada pengaturan Render. Render mengelola sertifikat TLS dan redirect HTTPS pada edge; aplikasi juga memaksa URL HTTPS di environment production dan mempercayai forwarded headers dari reverse proxy Render.

### Isi katalog awal

Saat migration diaktifkan, entrypoint akan mengisi kategori dan 14 menu contoh **hanya bila katalog belum memiliki menu**. Ini tidak membuat akun demo, dan tidak menimpa katalog yang sudah ada. Jika deployment lama sudah berjalan sebelum pemeriksaan katalog otomatis ini ditambahkan, jalankan sekali melalui Render Shell:

```sh
php artisan db:seed --class='Database\Seeders\CategorySeeder'
php artisan db:seed --class='Database\Seeders\MenuSeeder'
```

Jangan jalankan `DatabaseSeeder` pada production publik jika tidak menginginkan akun demo dengan kata sandi yang diketahui.

## 3. Login Google (opsional)

Di Google Cloud Console, buat OAuth Client ID tipe Web. Tambahkan URL callback berikut sebagai **Authorized redirect URI**:

```text
https://<nama-layanan>.onrender.com/auth/google/callback
```

Isi `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, dan `GOOGLE_REDIRECT_URI` pada environment Render. Untuk domain sendiri, gunakan callback domain tersebut dan daftarkan URI yang sama di Google.

## 4. Data demo dan akun

Seeder membuat akun demo `admin@warungntt.test` / `Admin12345` dan `pelanggan@warungntt.test` / `Pelanggan12345`. Jangan jalankan seeder demo pada layanan produksi publik dengan kredensial bawaan. Untuk tugas/demo yang memang membutuhkan akun tersebut, set `SEED_DEMO_DATA=true` pada first deploy sampai seeding berhasil, lalu segera set kembali ke `false` dan redeploy. Seeder dapat membuat ulang password demo setiap container mulai selama flag tetap aktif.

Untuk pemakaian selain demonstrasi, buat admin dengan kredensial pribadi melalui proses provisioning yang aman dan jangan bagikan akun demo.

## 5. Foto menu dan batasan free tier

Foto disimpan pada disk `public` dan dapat diakses melalui `/storage`. Jalankan `php artisan storage:link` secara lokal; container menjalankan perintah itu saat startup. Penyimpanan lokal Render Free bersifat ephemeral, jadi foto dapat hilang saat deploy/restart. Untuk data foto yang persisten, migrasikan disk ke object storage (misalnya Cloudinary) sebelum penggunaan produksi.

Aiven dan Render dapat mengubah ketersediaan, batas resource, atau syarat free tier. Periksa pilihan plan dan kuota terkini pada dashboard masing-masing sebelum deployment.

## 6. Menjalankan container lokal

```powershell
docker build -t warung-ntt .
docker run --rm -p 10000:10000 --env-file .env warung-ntt
```

Untuk menjalankan image lokal, isi `.env` dengan `APP_KEY`, database MySQL yang dapat dijangkau container, dan konfigurasi sertifikat yang sesuai. Jangan menggunakan `.env` production atau memasukkan rahasia ke build arguments.
