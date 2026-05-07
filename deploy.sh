#!/bin/bash
# Script Deploy - Rekap Pendaftaran
# Jalankan di VPS: bash deploy.sh

set -e

APP_DIR="/var/www/rekap-pendaftaran"
PHP="php"
COMPOSER="composer"

echo "======================================"
echo "  DEPLOY: Rekap Pendaftaran"
echo "======================================"

cd $APP_DIR

echo ""
echo "[1/7] Git pull dari GitHub..."
git pull origin main

echo ""
echo "[2/7] Install/update Composer dependencies..."
$COMPOSER install --no-dev --optimize-autoloader --no-interaction

echo ""
echo "[3/7] Jalankan migration database..."
$PHP artisan migrate --force

echo ""
echo "[4/7] Clear & rebuild cache..."
$PHP artisan config:clear
$PHP artisan cache:clear
$PHP artisan route:clear
$PHP artisan view:clear
$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan view:cache

echo ""
echo "[5/7] Storage link..."
$PHP artisan storage:link --force 2>/dev/null || true

echo ""
echo "[6/7] Set permissions..."
chown -R www-data:www-data $APP_DIR/storage $APP_DIR/bootstrap/cache 2>/dev/null || \
chown -R apache:apache $APP_DIR/storage $APP_DIR/bootstrap/cache 2>/dev/null || true
chmod -R 775 $APP_DIR/storage $APP_DIR/bootstrap/cache

echo ""
echo "[7/7] Restart PHP-FPM (jika ada)..."
systemctl restart php8.4-fpm 2>/dev/null || \
systemctl restart php8.2-fpm 2>/dev/null || \
systemctl restart php-fpm 2>/dev/null || true

echo ""
echo "======================================"
echo "  DEPLOY SELESAI!"
echo "======================================"
