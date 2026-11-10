# Perpustakaan Modular — Laravel 13

Proyek ini adalah hasil pengembangan studi kasus tugas MP-BED-06 (Modular Monolith + Domain-Driven Design).

**Mulai cepat:**

1. Pastikan PHP 8.3+ dan ekstensi `pdo_sqlite`, `dom`, `mbstring`, `xml`, `openssl`, `tokenizer`, `ctype`, serta `fileinfo` aktif.
2. Salin `.env.example` menjadi `.env` (`Copy-Item .env.example .env` di PowerShell).
3. Jalankan `php artisan key:generate`.
4. Jalankan `php artisan migrate:fresh --seed`.
5. Jalankan `php artisan serve`, lalu buka `http://127.0.0.1:8000`.

Folder `vendor/` sudah disertakan agar paket dapat dicoba tanpa mengunduh dependensi lagi. Jika folder tersebut tidak ada pada hasil ekstraksi, jalankan `composer install` terlebih dahulu. Gunakan hanya database praktikum saat menjalankan `migrate:fresh` karena perintah ini menghapus tabel lama.

Dokumentasi lengkap endpoint, seeder, pengujian, dan Tinker tersedia di [README-PRAKTIKUM.md](README-PRAKTIKUM.md). Laporan dan daftar screenshot BAB III–IV ada pada dokumen Word terpisah.
