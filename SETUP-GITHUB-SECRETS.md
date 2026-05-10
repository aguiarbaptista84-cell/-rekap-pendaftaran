# Setup GitHub Secrets untuk CI/CD

Agar GitHub Actions bisa deploy otomatis ke VPS, tambahkan 3 secrets berikut:

## Cara Menambahkan Secrets

1. Buka https://github.com/aguiarbaptista84-cell/-rekap-pendaftaran
2. Klik **Settings** → **Secrets and variables** → **Actions**
3. Klik **New repository secret** untuk setiap secret berikut:

| Secret Name    | Value                |
|----------------|----------------------|
| `VPS_HOST`     | `103.49.239.116`     |
| `VPS_USER`     | `baptista`           |
| `VPS_PASSWORD` | `Jordy1984`          |

## Cara Kerja

Setelah secrets dikonfigurasi:
- Setiap `git push` ke branch `main` → GitHub Actions otomatis SSH ke VPS → jalankan `deploy.sh`
- Deploy meliputi: backup DB, git pull, composer install, migrate, clear cache, set production env

## Setup VPS (jalankan SEKALI di VPS)

```bash
ssh baptista@103.49.239.116
cd /var/www/rekap-pendaftaran
git pull origin main
bash setup-vps.sh
```

Jika sudah punya domain, jalankan:
```bash
bash setup-vps.sh namadomain.com
```

## Cek Status Deploy

- Buka tab **Actions** di GitHub untuk melihat log deploy
- Endpoint health check: http://103.49.239.116/health
