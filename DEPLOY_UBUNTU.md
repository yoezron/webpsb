# 🚀 Manual Deploy ke Server Ubuntu 22.04 LTS

Panduan memasang aplikasi **PSB (CodeIgniter 4)** di server Linux Ubuntu 22.04 dengan
**Nginx + PHP-FPM 8.3 + MySQL 8**, mulai dari server kosong sampai HTTPS, backup, dan cara update.

> Ganti semua contoh berikut sesuai server Anda:
>
> | Contoh di panduan | Ganti dengan |
> |---|---|
> | `psb.domain-anda.sch.id` | domain/subdomain aplikasi |
> | `/var/www/webpsb` | folder aplikasi di server |
> | `psb_persis31` | nama database |
> | `psb_user` / `PasswordKuatAnda!` | user & password database |
> | `deploy` | user Linux untuk mengelola aplikasi |

---

## Daftar Isi

1. [Kebutuhan](#1-kebutuhan)
2. [Persiapan server](#2-persiapan-server)
3. [Install Nginx, PHP 8.3, Composer, MySQL](#3-install-nginx-php-83-composer-mysql)
4. [Konfigurasi PHP](#4-konfigurasi-php)
5. [Konfigurasi MySQL & buat database](#5-konfigurasi-mysql--buat-database)
6. [Ambil kode aplikasi](#6-ambil-kode-aplikasi)
7. [File `.env` production](#7-file-env-production)
8. [Hak akses folder](#8-hak-akses-folder)
9. [Migrasi database & akun awal](#9-migrasi-database--akun-awal)
10. [Konfigurasi Nginx](#10-konfigurasi-nginx)
11. [HTTPS (SSL gratis Let's Encrypt)](#11-https-ssl-gratis-lets-encrypt)
12. [Cek setelah deploy](#12-cek-setelah-deploy)
13. [Update aplikasi (deploy versi baru)](#13-update-aplikasi-deploy-versi-baru)
14. [Pindah dari server/database lama](#14-pindah-dari-serverdatabase-lama)
15. [Backup otomatis](#15-backup-otomatis)
16. [Troubleshooting](#16-troubleshooting)

---

## 1. Kebutuhan

| Komponen | Versi | Catatan |
|---|---|---|
| Ubuntu | 22.04 LTS | |
| PHP | **8.2 atau 8.3** (disarankan 8.3) | ⚠️ PHP 8.1 bawaan Ubuntu 22.04 **tidak cukup**: paket `laminas-escaper` di `composer.lock` butuh PHP ≥ 8.2 |
| Ekstensi PHP | intl, mbstring, mysql, gd, zip, xml, dom, curl, fileinfo, iconv, zlib | intl & mbstring wajib untuk CodeIgniter; gd/zip/xml untuk Excel; dom/gd untuk PDF; zip untuk unduh ZIP Kartu Keluarga |
| Web server | Nginx | Document root **harus** folder `public/` |
| Database | MySQL 8.0 | MariaDB 10.6+ juga bisa |
| Lainnya | Composer 2, Git, Certbot | |

Spesifikasi minimum server: 1 vCPU, 1 GB RAM (disarankan 2 GB), disk 20 GB (file Kartu Keluarga ±1 MB per pendaftar).

---

## 2. Persiapan server

Login sebagai `root` (atau user dengan sudo), lalu:

```bash
# Update sistem
sudo apt update && sudo apt upgrade -y

# Zona waktu server = WIB (aplikasi memakai Asia/Jakarta)
sudo timedatectl set-timezone Asia/Jakarta

# Paket dasar
sudo apt install -y software-properties-common curl unzip git ufw

# Buat user khusus untuk mengelola aplikasi (bukan root)
sudo adduser deploy
sudo usermod -aG sudo deploy
sudo usermod -aG www-data deploy

# Firewall: buka SSH, HTTP, HTTPS
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full' 2>/dev/null || { sudo ufw allow 80; sudo ufw allow 443; }
sudo ufw enable
```

Setelah ini, lanjutkan sebagai user `deploy` (`su - deploy`).

---

## 3. Install Nginx, PHP 8.3, Composer, MySQL

### 3.1 Nginx

```bash
sudo apt install -y nginx
sudo systemctl enable --now nginx
```

### 3.2 PHP 8.3 + ekstensi (dari PPA ondrej)

```bash
sudo add-apt-repository -y ppa:ondrej/php
sudo apt update
sudo apt install -y php8.3-fpm php8.3-cli php8.3-common \
    php8.3-mysql php8.3-intl php8.3-mbstring php8.3-gd php8.3-zip \
    php8.3-xml php8.3-curl php8.3-bcmath php8.3-opcache

sudo systemctl enable --now php8.3-fpm

# Cek: harus muncul intl, mbstring, gd, zip, dom, mysqli, fileinfo
php -v
php -m | grep -E 'intl|mbstring|gd|zip|dom|mysqli|fileinfo|iconv|zlib'
```

### 3.3 Composer

```bash
cd ~
curl -sS https://getcomposer.org/installer -o composer-setup.php
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php
composer --version
```

### 3.4 MySQL 8

```bash
sudo apt install -y mysql-server
sudo systemctl enable --now mysql
sudo mysql_secure_installation   # jawab: Y untuk semua pengamanan, buat password root yang kuat
```

---

## 4. Konfigurasi PHP

Edit **dua** file: `/etc/php/8.3/fpm/php.ini` (web) dan `/etc/php/8.3/cli/php.ini` (perintah `spark`).

```bash
sudo nano /etc/php/8.3/fpm/php.ini
```

Cari dan ubah nilai berikut:

```ini
date.timezone = Asia/Jakarta
upload_max_filesize = 8M      ; KK maks 1 MB, logo maks 2 MB, gambar pengumuman
post_max_size = 12M
memory_limit = 256M           ; ekspor Excel & PDF butuh memori lebih
max_execution_time = 120      ; ekspor Excel/ZIP data banyak
expose_php = Off

; OPcache (mempercepat aplikasi)
opcache.enable = 1
opcache.memory_consumption = 128
opcache.max_accelerated_files = 10000
opcache.validate_timestamps = 1
```

Di `/etc/php/8.3/cli/php.ini` cukup ubah `date.timezone = Asia/Jakarta` dan `memory_limit = 512M`.

```bash
sudo systemctl restart php8.3-fpm
```

---

## 5. Konfigurasi MySQL & buat database

### 5.1 Zona waktu MySQL = WIB

```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

Tambahkan di bawah `[mysqld]`:

```ini
default-time-zone = '+07:00'
character-set-server = utf8mb4
collation-server = utf8mb4_general_ci
```

```bash
sudo systemctl restart mysql
sudo mysql -e "SELECT @@global.time_zone, NOW();"   # harus +07:00 dan jam WIB
```

> Zona waktu PHP dan MySQL **harus sama (WIB)**. Jika berbeda, tanggal daftar, jadwal buka/tutup periode, dan waktu cetak PDF bisa selisih 7 jam.

### 5.2 Buat database & user

```bash
sudo mysql
```

```sql
CREATE DATABASE psb_persis31 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
CREATE USER 'psb_user'@'localhost' IDENTIFIED BY 'PasswordKuatAnda!';
GRANT ALL PRIVILEGES ON psb_persis31.* TO 'psb_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## 6. Ambil kode aplikasi

```bash
sudo mkdir -p /var/www/webpsb
sudo chown deploy:www-data /var/www/webpsb
cd /var/www

# Repo privat: gunakan deploy key SSH atau Personal Access Token GitHub
git clone https://github.com/yoezron/webpsb.git webpsb
cd webpsb
git checkout main

# Install dependency (tanpa paket development)
composer install --no-dev --optimize-autoloader --no-interaction
```

> **Deploy key (disarankan untuk repo privat):** `ssh-keygen -t ed25519 -C "deploy-psb"`, lalu salin isi
> `~/.ssh/id_ed25519.pub` ke GitHub → repo → *Settings → Deploy keys* (read-only), dan clone dengan
> `git clone git@github.com:yoezron/webpsb.git webpsb`.

---

## 7. File `.env` production

File `.env` **tidak ada di repo** (sudah di-`.gitignore`). Buat baru:

```bash
cd /var/www/webpsb
nano .env
```

Isi:

```ini
#--------------------------------------------------------------------
# ENVIRONMENT
#--------------------------------------------------------------------
CI_ENVIRONMENT = production

#--------------------------------------------------------------------
# APP
#--------------------------------------------------------------------
app.baseURL = 'https://psb.domain-anda.sch.id/'
app.indexPage = ''
app.appTimezone = 'Asia/Jakarta'
; aktifkan setelah HTTPS berjalan (langkah 11)
app.forceGlobalSecureRequests = false

#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------
database.default.hostname = localhost
database.default.database = psb_persis31
database.default.username = psb_user
database.default.password = PasswordKuatAnda!
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
database.default.charset = utf8mb4
database.default.DBCollat = utf8mb4_general_ci

#--------------------------------------------------------------------
# COOKIE (ubah ke true setelah HTTPS berjalan)
#--------------------------------------------------------------------
cookie.secure = false
```

Amankan file `.env`:

```bash
chmod 640 .env
sudo chown deploy:www-data .env
```

> ⚠️ Jangan pernah memakai `CI_ENVIRONMENT = development` di server publik: halaman error akan menampilkan
> potongan kode dan konfigurasi.

---

## 8. Hak akses folder

PHP (user `www-data`) perlu bisa menulis ke:

- `writable/`: cache, log, session, **file Kartu Keluarga** (`writable/uploads/kartu_keluarga`)
- `public/uploads/`: gambar pengumuman & logo unggahan superadmin

```bash
cd /var/www/webpsb
sudo chown -R deploy:www-data .
sudo find . -type d -exec chmod 755 {} \;
sudo find . -type f -exec chmod 644 {} \;

mkdir -p writable/{cache,logs,session,uploads} public/uploads/{pengumuman,logo}
sudo chown -R www-data:www-data writable public/uploads
sudo chmod -R 775 writable public/uploads

chmod 640 .env
chmod +x spark
```

---

## 9. Migrasi database & akun awal

### 9.1 Database baru (server kosong)

```bash
cd /var/www/webpsb
php spark migrate
php spark migrate:status   # semua migrasi harus berstatus sudah dijalankan
```

Buat akun panitia awal. **Hanya jalankan `AdminSeeder`.** Jangan `php spark db:seed DatabaseSeeder`,
karena itu juga mengisi data pendaftar contoh.

```bash
php spark db:seed AdminSeeder
```

Akun awal yang dibuat:

| Username | Password | Role |
|---|---|---|
| `admin` | `admin123` | Superadmin |
| `panitia_tsn` | `panitia123` | Panitia Tsanawiyyah |
| `panitia_mua` | `panitia123` | Panitia Mu'allimin |

> 🔐 **Segera setelah login pertama**, buka **Pengaturan → User Panitia** dan ganti password ketiga akun
> tersebut (atau hapus yang tidak dipakai lalu buat akun baru).

### 9.2 Database sudah ada (pindahan dari server lama)

Lihat [langkah 14](#14-pindah-dari-serverdatabase-lama).

---

## 10. Konfigurasi Nginx

```bash
sudo nano /etc/nginx/sites-available/webpsb
```

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name psb.domain-anda.sch.id;

    # WAJIB: arahkan ke folder public, bukan root aplikasi
    root /var/www/webpsb/public;
    index index.php;

    charset utf-8;
    client_max_body_size 12M;   # samakan dengan post_max_size PHP

    access_log /var/log/nginx/webpsb.access.log;
    error_log  /var/log/nginx/webpsb.error.log;

    # Header keamanan dasar
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_read_timeout 120;
    }

    # Cegah eksekusi PHP di folder upload
    location ~* ^/uploads/.*\.(php|phtml|phar)$ {
        deny all;
    }

    # Tolak file tersembunyi (.env, .git, .htaccess, dll)
    location ~ /\.(?!well-known) {
        deny all;
    }

    # Cache file statis
    location ~* \.(?:css|js|png|jpe?g|gif|ico|svg|webp|woff2?|ttf|eot)$ {
        expires 30d;
        access_log off;
        try_files $uri =404;
    }
}
```

Aktifkan:

```bash
sudo ln -s /etc/nginx/sites-available/webpsb /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
```

Pastikan DNS domain (A record) sudah mengarah ke IP server, lalu buka `http://psb.domain-anda.sch.id`.

---

## 11. HTTPS (SSL gratis Let's Encrypt)

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d psb.domain-anda.sch.id --redirect -m email-admin@domain-anda.sch.id --agree-tos -n

# Uji perpanjangan otomatis
sudo certbot renew --dry-run
```

Setelah HTTPS jalan, ubah `.env`:

```ini
app.baseURL = 'https://psb.domain-anda.sch.id/'
app.forceGlobalSecureRequests = true
cookie.secure = true
```

```bash
php spark cache:clear
sudo systemctl reload php8.3-fpm
```

---

## 12. Cek setelah deploy

Checklist yang perlu dicoba setelah deploy:

- [ ] `https://psb.domain-anda.sch.id/` tampil normal (CSS & logo muncul)
- [ ] `https://psb.domain-anda.sch.id/.env` → **403/404** (tidak boleh bisa diunduh)
- [ ] Login superadmin di `/login`, lalu **ganti password akun bawaan**
- [ ] **Pengaturan → Periode & Nomor**: periode & tahun ajaran sudah benar, pendaftaran "Dibuka"
- [ ] **Pengaturan → Halaman Utama**: kontak, penandatangan kartu PDF
- [ ] **Pengaturan → Logo & Warna**: unggah logo bila perlu
- [ ] Coba daftar 1 santri uji (Tsanawiyyah) sampai selesai, termasuk upload KK
- [ ] Unduh **Kartu PDF** dan **PDF Data Lengkap** dari halaman sukses
- [ ] Di dashboard: detail, unduh KK, **Export Excel**, **Unduh ZIP KK**
- [ ] Hapus pendaftar uji tersebut dari dashboard
- [ ] Jam di Kartu PDF sesuai WIB

Cek log bila ada masalah:

```bash
tail -n 50 /var/www/webpsb/writable/logs/log-$(date +%F).log
sudo tail -n 50 /var/log/nginx/webpsb.error.log
sudo tail -n 50 /var/log/php8.3-fpm.log
```

---

## 13. Update aplikasi (deploy versi baru)

Simpan script berikut sebagai `/home/deploy/deploy-psb.sh`:

```bash
#!/usr/bin/env bash
# Deploy versi terbaru aplikasi PSB
set -euo pipefail

APP=/var/www/webpsb
BRANCH=main
BACKUP=/home/deploy/backup-psb

echo "==> Backup database sebelum update"
mkdir -p "$BACKUP"
mysqldump --single-transaction --no-tablespaces --routines psb_persis31 | gzip > "$BACKUP/db-sebelum-deploy-$(date +%F_%H%M).sql.gz"

cd "$APP"
echo "==> Ambil kode terbaru ($BRANCH)"
git fetch origin
git checkout "$BRANCH"
git pull --ff-only origin "$BRANCH"

echo "==> Install dependency"
composer install --no-dev --optimize-autoloader --no-interaction

echo "==> Migrasi database"
php spark migrate

echo "==> Bersihkan cache & reload PHP"
php spark cache:clear
sudo chown -R www-data:www-data writable public/uploads
sudo systemctl reload php8.3-fpm

echo "==> Selesai. Versi aktif: $(git log --oneline -1)"
```

Agar `mysqldump` tidak menanyakan password, buat `/home/deploy/.my.cnf`:

```ini
[client]
user=psb_user
password=PasswordKuatAnda!
```

```bash
chmod 600 ~/.my.cnf
chmod +x ~/deploy-psb.sh

# Menjalankan update
~/deploy-psb.sh
```

**Rollback** bila versi baru bermasalah:

```bash
cd /var/www/webpsb
git log --oneline -5                 # cari commit sebelumnya
git checkout <hash-commit-sebelumnya>
composer install --no-dev --optimize-autoloader
# Bila migrasi baru perlu dibatalkan:
php spark migrate:rollback
# Bila data rusak, pulihkan dari backup:
gunzip < ~/backup-psb/db-sebelum-deploy-XXXX.sql.gz | mysql psb_persis31
php spark cache:clear && sudo systemctl reload php8.3-fpm
```

---

## 14. Pindah dari server/database lama

Jika sebelumnya aplikasi sudah berjalan (di hosting lain/Laragon) dan datanya ingin dipindah:

### 14.1 Di server lama

```bash
mysqldump -u root -p --single-transaction --routines psb_persis31 > psb_persis31.sql
```

Salin juga folder file unggahan:

- `writable/uploads/kartu_keluarga/` (file Kartu Keluarga)
- `public/uploads/pengumuman/` dan `public/uploads/logo/`

### 14.2 Di server baru

```bash
# Upload file dari komputer Anda
scp psb_persis31.sql deploy@IP_SERVER:/home/deploy/
scp -r kartu_keluarga deploy@IP_SERVER:/home/deploy/

# Import database (database & user sudah dibuat di langkah 5)
mysql psb_persis31 < /home/deploy/psb_persis31.sql

# Kembalikan file unggahan
sudo mkdir -p /var/www/webpsb/writable/uploads/kartu_keluarga
sudo cp -r /home/deploy/kartu_keluarga/. /var/www/webpsb/writable/uploads/kartu_keluarga/
sudo chown -R www-data:www-data /var/www/webpsb/writable /var/www/webpsb/public/uploads

# Jalankan migrasi yang belum ada (aman dijalankan berulang)
cd /var/www/webpsb
php spark migrate
php spark migrate:status
```

> **Catatan migrasi dari versi lama:**
> - Migrasi `CreatePengaturanDanPeriode` memasukkan semua pendaftar lama ke periode **2026/2027**.
> - Migrasi `AddKolomEmisDanFileKk` mengubah nama santri/ayah/ibu/wali menjadi **HURUF BESAR**.
> - Migrasi `SesuaikanPilihanEmis` mengonversi pilihan jarak/transportasi lama ke versi EMIS.
> - Data yang tersimpan saat aplikasi masih memakai zona waktu UTC tercatat 7 jam lebih awal.
> - Tidak bisa menjalankan `spark`? Impor manual berurutan lewat phpMyAdmin:
>   `migration_pengaturan_periode.sql` → `migration_kolom_emis_file_kk.sql` → `migration_pilihan_emis.sql`.

---

## 15. Backup otomatis

Data penting yang **wajib** dibackup:

1. Database `psb_persis31`
2. `writable/uploads/kartu_keluarga/`: file KK **tidak ada di database**; jika hilang, tidak bisa dipulihkan
3. `public/uploads/`: gambar pengumuman & logo
4. File `.env`

Simpan sebagai `/home/deploy/backup-psb.sh`:

```bash
#!/usr/bin/env bash
# Backup harian database + file unggahan PSB, simpan 14 hari terakhir
set -euo pipefail

APP=/var/www/webpsb
DEST=/home/deploy/backup-psb
STAMP=$(date +%F_%H%M)
KEEP_DAYS=14

mkdir -p "$DEST"

mysqldump --single-transaction --no-tablespaces --routines psb_persis31 | gzip > "$DEST/db-$STAMP.sql.gz"
tar -czf "$DEST/uploads-$STAMP.tar.gz" -C "$APP" writable/uploads public/uploads .env

find "$DEST" -type f -mtime +$KEEP_DAYS -delete
echo "Backup selesai: $STAMP"
```

```bash
chmod +x ~/backup-psb.sh
~/backup-psb.sh            # uji sekali

crontab -e
# tambahkan baris berikut (backup setiap hari jam 01.30 WIB):
30 1 * * * /home/deploy/backup-psb.sh >> /home/deploy/backup-psb/backup.log 2>&1
```

> Simpan salinan backup di **luar server** juga (Google Drive, komputer kantor, atau object storage).
> Contoh mengambil backup ke komputer:
> `scp deploy@IP_SERVER:/home/deploy/backup-psb/db-*.sql.gz .`

---

## 16. Troubleshooting

| Gejala | Penyebab umum | Solusi |
|---|---|---|
| `Unable to connect to the database` | MySQL mati / kredensial `.env` salah | `sudo systemctl status mysql`; cek `database.default.*` di `.env`; uji `mysql -u psb_user -p psb_persis31` |
| Halaman putih / error 500 tanpa pesan | Folder `writable` tidak bisa ditulis | Ulangi [langkah 8](#8-hak-akses-folder); lihat `writable/logs/` |
| `404 Not Found` untuk semua halaman kecuali `/` | `try_files` Nginx salah / root bukan `public` | Cek blok `location /` dan `root .../public` di [langkah 10](#10-konfigurasi-nginx) |
| CSS/gambar tidak muncul, link mengarah ke `localhost` | `app.baseURL` salah | Perbaiki `app.baseURL` di `.env`, lalu `php spark cache:clear` |
| URL mengandung `/index.php/` | `app.indexPage` belum dikosongkan | Set `app.indexPage = ''` di `.env` |
| `413 Request Entity Too Large` saat upload | Batas upload Nginx | Naikkan `client_max_body_size` di Nginx & `post_max_size` di PHP |
| Upload KK/logo gagal "file tidak valid" | Ekstensi `fileinfo`/`gd` tidak ada | `sudo apt install php8.3-gd`, cek `php -m` |
| `Class "Locale" not found` / `intl` error | Ekstensi intl belum terpasang | `sudo apt install php8.3-intl && sudo systemctl restart php8.3-fpm` |
| Export Excel / ZIP KK gagal | Ekstensi `zip`/`xml` tidak ada, atau memori habis | `sudo apt install php8.3-zip php8.3-xml`; naikkan `memory_limit` |
| PDF kosong / gagal dibuat | Ekstensi `dom`/`mbstring`/`gd` | Pasang ekstensi tersebut, restart PHP-FPM |
| `composer install` error "requires php >=8.2" | PHP versi 8.1 | Pasang PHP 8.3 dari PPA ([langkah 3.2](#32-php-83--ekstensi-dari-ppa-ondrej)) dan pastikan `php -v` = 8.3 |
| Jam di PDF/dashboard selisih 7 jam | Zona waktu PHP/MySQL berbeda | Cek `date.timezone` PHP, `default-time-zone` MySQL, dan `timedatectl` |
| Selalu kembali ke halaman login | Cookie `secure` aktif tapi situs masih HTTP | Set `cookie.secure = false` sampai HTTPS aktif |
| `The action you requested is not allowed` | Token CSRF kedaluwarsa (halaman dibuka terlalu lama) | Muat ulang halaman lalu kirim ulang formulir |

Perintah berguna:

```bash
php spark routes                   # daftar rute
php spark migrate:status           # status migrasi
php spark cache:clear              # bersihkan cache (mis. setelah ubah .env)
sudo systemctl restart php8.3-fpm nginx mysql
```
