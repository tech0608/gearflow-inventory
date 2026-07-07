#!/usr/bin/env bash
# ─────────────────────────────────────────────────────────────────────────────
# Script Deployment Otomatis - Inventaris Bengkel (Laravel 11 + PHP 8.3)
# ─────────────────────────────────────────────────────────────────────────────
# Cara penggunaan di server (SSH):
# chmod +x deploy.sh && ./deploy.sh

set -e

echo "🚀 [1/7] Memulai proses deployment Inventaris Bengkel..."

# 1. Pastikan kita berada di direktori proyek
cd "$(dirname "$0")"

# 2. Cek apakah PHP 8.3 tersedia
if command -v php8.3 >/dev/null 2>&1; then
    PHP_BIN="php8.3"
else
    PHP_BIN="php"
fi
echo "⚡ Menggunakan binary PHP: $($PHP_BIN -v | head -n 1)"

# 3. Mode maintenance aktif sementara (agar user tidak error saat proses)
echo "🔒 [2/7] Mengaktifkan mode maintenance..."
$PHP_BIN artisan down --message="Sistem sedang diperbarui. Silakan kembali dalam beberapa saat." --refresh=15 || true

# 4. Update dependensi Composer (Tanpa dev package untuk produksi)
echo "📦 [3/7] Menginstall dependensi Composer (Production)..."
export COMPOSER_ALLOW_SUPERUSER=1
composer install --no-dev --optimize-autoloader --no-interaction

# 5. Migrasi Database & Seeding (jika tabel belum ada)
echo "🗄️ [4/7] Menjalankan migrasi database..."
$PHP_BIN artisan migrate --force

# 6. Optimasi dan Caching Konfigurasi
echo "⚡ [5/7] Membangun cache konfigurasi, rute, dan tampilan..."
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache
$PHP_BIN artisan event:cache

# 7. Pengaturan Hak Akses File (Permissions & Least Privilege)
echo "🔐 [6/7] Mengatur hak akses direktori storage dan cache..."
chmod -R 755 storage bootstrap/cache || true
chmod -R 644 .env || true

# 8. Matikan mode maintenance
echo "🔓 [7/7] Menonaktifkan mode maintenance..."
$PHP_BIN artisan up

echo "─────────────────────────────────────────────────────────────────────────────"
echo "✅ DEPLOYMENT BERHASIL! Aplikasi Inventaris Bengkel siap digunakan online."
echo "─────────────────────────────────────────────────────────────────────────────"
