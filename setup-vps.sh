#!/bin/bash
# Setup VPS - Jalankan SEKALI untuk konfigurasi production
# Jalankan: bash setup-vps.sh yourdomain.com
# Contoh  : bash setup-vps.sh rekap.bu-rdtl.tl

set -e

DOMAIN="${1:-103.49.239.116}"
APP_DIR="/var/www/rekap-pendaftaran"
EMAIL="aguiarbaptista84@gmail.com"

echo "======================================"
echo "  SETUP VPS PRODUCTION"
echo "  Domain: $DOMAIN"
echo "======================================"

# ── Nginx config ─────────────────────────────────────────────────
echo ""
echo "[1/5] Konfigurasi Nginx..."
cat > /etc/nginx/sites-available/rekap-pendaftaran << NGINX
server {
    listen 80;
    server_name $DOMAIN www.$DOMAIN;
    root $APP_DIR/public;
    index index.php;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Referrer-Policy "strict-origin-when-cross-origin";

    # Gzip
    gzip on;
    gzip_types text/plain text/css application/json application/javascript;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 120;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    client_max_body_size 10M;
    access_log /var/log/nginx/rekap-access.log;
    error_log  /var/log/nginx/rekap-error.log;
}
NGINX

ln -sf /etc/nginx/sites-available/rekap-pendaftaran /etc/nginx/sites-enabled/
nginx -t && systemctl reload nginx
echo "  ✓ Nginx dikonfigurasi"

# ── SSL dengan Certbot ───────────────────────────────────────────
if [ "$DOMAIN" != "103.49.239.116" ]; then
    echo ""
    echo "[2/5] Install SSL (Let's Encrypt)..."
    apt-get install -y certbot python3-certbot-nginx 2>/dev/null || true
    certbot --nginx -d "$DOMAIN" -d "www.$DOMAIN" \
        --non-interactive --agree-tos --email "$EMAIL" \
        --redirect
    echo "  ✓ SSL aktif — HTTPS dikonfigurasi"

    # Auto-renew SSL
    (crontab -l 2>/dev/null; echo "0 3 * * * certbot renew --quiet && systemctl reload nginx") | sort -u | crontab -
    echo "  ✓ Auto-renew SSL ditambahkan ke crontab"
else
    echo ""
    echo "[2/5] SSL dilewati (akses via IP, bukan domain)"
    echo "  ℹ Daftarkan domain dulu lalu jalankan:"
    echo "    certbot --nginx -d yourdomain.com --email $EMAIL"
fi

# ── Crontab backup database ──────────────────────────────────────
echo ""
echo "[3/5] Setup cron backup database (setiap hari jam 02:00)..."
(crontab -l 2>/dev/null; echo "0 2 * * * bash $APP_DIR/backup-db.sh >> /var/log/rekap-backup.log 2>&1") | sort -u | crontab -
echo "  ✓ Cron backup database aktif"

# ── Firewall dasar ───────────────────────────────────────────────
echo ""
echo "[4/5] Konfigurasi firewall (UFW)..."
if command -v ufw &>/dev/null; then
    ufw allow 22/tcp    # SSH
    ufw allow 80/tcp    # HTTP
    ufw allow 443/tcp   # HTTPS
    ufw --force enable
    echo "  ✓ Firewall aktif (22, 80, 443)"
else
    echo "  ℹ UFW tidak terinstall, lewati"
fi

# ── Update .env production ───────────────────────────────────────
echo ""
echo "[5/5] Hardening .env production..."
cd $APP_DIR
sed -i 's/^APP_ENV=.*/APP_ENV=production/' .env
sed -i 's/^APP_DEBUG=.*/APP_DEBUG=false/'  .env
if [ "$DOMAIN" != "103.49.239.116" ]; then
    sed -i "s|^APP_URL=.*|APP_URL=https://$DOMAIN|" .env
fi
php artisan config:clear
php artisan config:cache
echo "  ✓ APP_ENV=production, APP_DEBUG=false"

echo ""
echo "======================================"
echo "  ✅ SETUP SELESAI!"
if [ "$DOMAIN" != "103.49.239.116" ]; then
    echo "  🌐 Akses: https://$DOMAIN"
else
    echo "  🌐 Akses: http://$DOMAIN"
fi
echo "======================================"
