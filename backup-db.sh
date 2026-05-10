#!/bin/bash
# Backup otomatis database SQLite
# Tambahkan ke crontab: 0 2 * * * bash /var/www/rekap-pendaftaran/backup-db.sh >> /var/log/rekap-backup.log 2>&1

APP_DIR="/var/www/rekap-pendaftaran"
BACKUP_DIR="/var/backups/rekap-pendaftaran"
DB_FILE="$APP_DIR/database/database.sqlite"
DATE=$(date +%Y%m%d_%H%M%S)
KEEP_DAYS=30

mkdir -p $BACKUP_DIR

if [ ! -f "$DB_FILE" ]; then
    echo "[$(date)] ERROR: Database tidak ditemukan di $DB_FILE"
    exit 1
fi

# Copy database
BACKUP_FILE="$BACKUP_DIR/database_$DATE.sqlite"
cp "$DB_FILE" "$BACKUP_FILE"
echo "[$(date)] ✓ Backup: $BACKUP_FILE ($(du -h $BACKUP_FILE | cut -f1))"

# Hapus backup lebih dari 30 hari
find $BACKUP_DIR -name "*.sqlite" -mtime +$KEEP_DAYS -delete
echo "[$(date)] ✓ Backup lama (>$KEEP_DAYS hari) dibersihkan"

# Tampilkan jumlah backup saat ini
COUNT=$(ls $BACKUP_DIR/*.sqlite 2>/dev/null | wc -l)
echo "[$(date)] ℹ Total backup tersedia: $COUNT file"
