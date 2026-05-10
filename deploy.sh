#!/bin/bash
# Script Deploy - Rekap Pendaftaran (Production Hardened)
# Jalankan di VPS: bash deploy.sh

set -e

APP_DIR="/var/www/rekap-pendaftaran"
PHP="php"
COMPOSER="composer"
BACKUP_DIR="/var/backups/rekap-pendaftaran"
DATE=$(date +%Y%m%d_%H%M%S)

echo "======================================"
echo "  DEPLOY: Rekap Pendaftaran"
echo "  $(date '+%d/%m/%Y %H:%M:%S')"
echo "======================================"

cd $APP_DIR

# ── [0] Backup database sebelum deploy ──────────────────────────
echo ""
echo "[0/8] Backup database..."
mkdir -p $BACKUP_DIR
if [ -f "$APP_DIR/database/database.sqlite" ]; then
    cp "$APP_DIR/database/database.sqlite" "$BACKUP_DIR/database_$DATE.sqlite"
    # Simpan hanya 30 backup terakhir
    ls -t $BACKUP_DIR/*.sqlite 2>/dev/null | tail -n +31 | xargs rm -f 2>/dev/null || true
    echo "  ✓ Backup disimpan: database_$DATE.sqlite"
else
    echo "  ℹ Tidak ada file SQLite, lewati backup"
fi

# ── [1] Maintenance mode ON ──────────────────────────────────────
echo ""
echo "[1/8] Masuk maintenance mode..."
$PHP artisan down --refresh=15 --retry=10 2>/dev/null || true

# ── [2] Git pull ─────────────────────────────────────────────────
echo ""
echo "[2/8] Git pull dari GitHub..."
git pull origin main

# ── [3] Composer ─────────────────────────────────────────────────
echo ""
echo "[3/8] Install/update Composer dependencies..."
$COMPOSER install --no-dev --optimize-autoloader --no-interaction

# ── [4] Set environment production ──────────────────────────────
echo ""
echo "[4/8] Set environment production..."
# Paksa nilai production yang aman di .env
sed -i 's/^APP_ENV=.*/APP_ENV=production/'   .env
sed -i 's/^APP_DEBUG=.*/APP_DEBUG=false/'    .env
# Update APP_URL jika domain sudah dikonfigurasi
if [ -n "$APP_DOMAIN" ]; then
    sed -i "s|^APP_URL=.*|APP_URL=https://$APP_DOMAIN|" .env
fi
echo "  ✓ APP_ENV=production, APP_DEBUG=false"

# ── [5] Migration ────────────────────────────────────────────────
echo ""
echo "[5/8] Jalankan migration database..."
$PHP artisan migrate --force

# ── [6] Clear & rebuild cache ────────────────────────────────────
echo ""
echo "[6/8] Clear & rebuild cache..."
$PHP artisan config:clear
$PHP artisan cache:clear
$PHP artisan route:clear
$PHP artisan view:clear
$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan view:cache

# ── [7] Permissions ──────────────────────────────────────────────
echo ""
echo "[7/8] Set permissions..."
$PHP artisan storage:link --force 2>/dev/null || true
chown -R www-data:www-data $APP_DIR/storage $APP_DIR/bootstrap/cache 2>/dev/null || \
chown -R apache:apache     $APP_DIR/storage $APP_DIR/bootstrap/cache 2>/dev/null || true
chmod -R 775 $APP_DIR/storage $APP_DIR/bootstrap/cache
chmod 664 $APP_DIR/database/database.sqlite 2>/dev/null || true
chown www-data:www-data $APP_DIR/database/database.sqlite 2>/dev/null || true

# ── [8] Restart PHP-FPM & keluar maintenance ─────────────────────
echo ""
echo "[8/8] Restart service & maintenance mode OFF..."
systemctl restart php8.4-fpm 2>/dev/null || \
systemctl restart php8.2-fpm 2>/dev/null || \
systemctl restart php-fpm    2>/dev/null || true

$PHP artisan up

echo ""
echo "======================================"
echo "  ✅ DEPLOY SELESAI!"
echo "  $(date '+%d/%m/%Y %H:%M:%S')"
echo "======================================"
