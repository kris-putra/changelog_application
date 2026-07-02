# Deployment ke Coolify + Cloudflare

## 1. Persiapan aplikasi

1. Pastikan repo telah dipush ke GitHub/GitLab.
2. Siapkan domain misalnya `changelog.example.com`.
3. Buat database MySQL di server/hosting.

## 2. Konfigurasi Coolify

1. Buka Coolify, buat aplikasi baru, pilih repo.
2. Pilih build pack PHP/Laravel.
3. Set build command:
   ```bash
   composer install --no-dev --prefer-dist
   npm ci
   npm run build
   ```
4. Set start command:
   ```bash
   php artisan serve --host=0.0.0.0 --port=80
   ```
5. Tambahkan environment variables:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://changelog.example.com`
   - `DB_CONNECTION=mysql`
   - `DB_HOST=...`
   - `DB_PORT=3306`
   - `DB_DATABASE=...`
   - `DB_USERNAME=...`
   - `DB_PASSWORD=...`
6. Jalankan deploy dan pastikan migrasi berjalan:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

## 3. Konfigurasi DNS Cloudflare

1. Tambah record DNS `A` atau `CNAME` untuk subdomain.
2. Arahkan ke IP server Coolify.
3. Aktifkan proxy (orange cloud) jika ingin memakai Cloudflare CDN.
4. Tunggu propagasi DNS.

## 4. Verifikasi

- Buka `https://changelog.example.com`.
- Pastikan halaman daftar fitur terbuka.
- Uji membuat permintaan fitur baru.
