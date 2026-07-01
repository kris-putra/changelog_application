# Changelog Application (skeleton)

Aplikasi Laravel sederhana untuk mencatat permintaan fitur, perubahan, atau bug.

## Setup Lokal

1. Pastikan PHP 8.4 dan Composer tersedia.
2. Copy `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
3. Sesuaikan konfigurasi database MySQL di `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=changelog
   DB_USERNAME=root
   DB_PASSWORD=root
   ```
4. Install dependency:
   ```bash
   composer install
   npm install
   npm run build
   ```
5. Generate `APP_KEY`:
   ```bash
   php artisan key:generate
   ```
6. Jalankan migrasi dan seed data:
   ```bash
   php artisan migrate --seed
   ```
7. Jalankan server:
   ```bash
   php artisan serve --host=127.0.0.1 --port=8000
   ```

## Menggunakan MAMP

1. Buka MAMP dan pastikan MySQL berjalan.
2. Buat database `changelog` di phpMyAdmin atau MySQL client.
3. Atur `.env` dengan konfigurasi MySQL di atas.
4. Jika menggunakan port MySQL MAMP `8889`, ubah `DB_PORT=8889`.
5. Arahkan _Document Root_ MAMP ke folder `public` dalam project ini.
6. Akses aplikasi melalui `http://localhost:8888` atau port web MAMP yang Anda gunakan.

## Struktur Utama

- `app/Models/FeatureRequest.php`: model permintaan fitur.
- `app/Http/Controllers/FeatureRequestController.php`: controller CRUD.
- `app/Http/Requests/StoreFeatureRequest.php`: validasi form.
- `database/migrations/2026_06_30_000000_create_feature_requests_table.php`: migration tabel.
- `resources/views/feature_requests`: view untuk daftar, buat, detail, edit.
- `routes/web.php`: route aplikasi.
- `Dockerfile`: contoh build container untuk deployment.

## Fitur yang tersedia

- Daftar permintaan fitur.
- Form input permintaan fitur baru.
- Halaman detail permintaan.
- Halaman edit permintaan.

## Deploy ke Coolify

1. Push repo ke GitHub.
2. Buat aplikasi baru di Coolify dan sambungkan ke repo.
3. Set build command:
   ```bash
   composer install --no-dev --prefer-dist
   npm ci
   npm run build
   ```
4. Set start command atau gunakan Dockerfile.
5. Tambahkan environment variables di Coolify sesuai `.env`.
6. Jalankan `php artisan migrate --force` melalui command deploy.
