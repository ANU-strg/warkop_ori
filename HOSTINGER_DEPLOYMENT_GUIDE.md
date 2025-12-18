# 🚀 PANDUAN DEPLOY LARAVEL KE HOSTINGER SHARED HOSTING

## 📋 Persiapan Sebelum Upload

### 1. File yang TIDAK perlu di-upload:
- `vendor/` (akan di-install via Composer di Hostinger)
- `node_modules/`
- `.git/`
- `halo/` (folder test yang tidak perlu)

### 2. Pastikan `.env` sudah dikonfigurasi untuk production:
```env
APP_NAME="Warung Mamcis"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://warungmamcis.shop

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=username_database
DB_PASSWORD=password_database

SESSION_DRIVER=file
CACHE_STORE=file

MIDTRANS_SERVER_KEY=Mid-server-xxxxx
MIDTRANS_CLIENT_KEY=Mid-client-xxxxx
MIDTRANS_IS_PRODUCTION=true
```

---

## 📁 STRUKTUR FOLDER DI HOSTINGER

```
/home/u123456789/
├── domains/
│   └── warungmamcis.shop/
│       └── public_html/          ← ISI FOLDER "public" LARAVEL
│           ├── index.php         ← EDIT PATH-NYA!
│           ├── .htaccess
│           ├── images/
│           │   ├── qrcodes/
│           │   └── menus/
│           ├── favicon.ico
│           └── robots.txt
│
└── laravel_app/                  ← SEMUA FILE LARAVEL (KECUALI public)
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── database/
    ├── resources/
    ├── routes/
    ├── storage/
    ├── .env
    ├── artisan
    ├── composer.json
    └── composer.lock
```

---

## 🔧 LANGKAH-LANGKAH DEPLOYMENT

### STEP 1: Buat Folder di Hostinger

1. Login ke **Hostinger hPanel**
2. Buka **File Manager**
3. Pergi ke `/home/u123456789/` (folder home Anda)
4. Buat folder baru: **`laravel_app`**

### STEP 2: Upload File Laravel (tanpa public)

1. Di komputer lokal, **ZIP semua file Laravel KECUALI folder `public`**:
   - app/
   - bootstrap/
   - config/
   - database/
   - resources/
   - routes/
   - storage/
   - .env
   - artisan
   - composer.json
   - composer.lock

2. Upload ZIP ke folder **`/home/u123456789/laravel_app/`**
3. Extract ZIP di Hostinger

### STEP 3: Upload Isi Folder Public

1. Di komputer lokal, **ZIP isi folder `public/`** (BUKAN folder public-nya, tapi ISI-nya)
2. Upload ke **`/home/u123456789/domains/warungmamcis.shop/public_html/`**
3. Extract ZIP

### STEP 4: Edit index.php di public_html

1. Buka **`/public_html/index.php`**
2. **GANTI SELURUH ISI** dengan:

```php
<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// ============================================
// HOSTINGER CONFIGURATION - EDIT PATH INI!
// ============================================
// Ganti "u123456789" dengan username Hostinger Anda
$laravelPath = '/home/u123456789/laravel_app';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $laravelPath.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $laravelPath.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once $laravelPath.'/bootstrap/app.php')
    ->handleRequest(Request::capture());
```

> ⚠️ **PENTING**: Ganti `u123456789` dengan username Hostinger Anda yang sebenarnya!
> Cek username di File Manager, lihat path di breadcrumb.

### STEP 5: Install Composer Dependencies

1. Buka **Hostinger hPanel** → **Advanced** → **SSH Access**
2. Enable SSH dan catat credentials
3. Buka Terminal/Command Prompt di komputer:

```bash
ssh u123456789@warungmamcis.shop
```

4. Masuk ke folder Laravel:
```bash
cd ~/laravel_app
```

5. Install dependencies:
```bash
composer install --optimize-autoloader --no-dev
```

> Jika tidak ada SSH, gunakan **Softaculous** atau upload folder `vendor/` secara manual.

### STEP 6: Setup Database

1. Di hPanel → **Databases** → **MySQL Databases**
2. Buat database baru (contoh: `u123456789_warkop`)
3. Buat user database dan assign ke database
4. Catat: database name, username, password

5. Edit `.env` di folder `laravel_app`:
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u123456789_warkop
DB_USERNAME=u123456789_warkop
DB_PASSWORD=password_anda
```

### STEP 7: Jalankan Migrasi & Seeder

Via SSH:
```bash
cd ~/laravel_app
php artisan key:generate
php artisan migrate
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=DemoDataSeeder
```

Atau via browser (buat file temporary):

1. Buat file `setup.php` di `public_html/`:
```php
<?php
require '/home/u123456789/laravel_app/vendor/autoload.php';
$app = require_once '/home/u123456789/laravel_app/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<pre>";
echo "Running migrations...\n";
$kernel->call('migrate', ['--force' => true]);
echo "Migrations completed!\n\n";

echo "Seeding admin user...\n";
$kernel->call('db:seed', ['--class' => 'AdminUserSeeder', '--force' => true]);
echo "Admin user created!\n\n";

echo "Seeding demo data...\n";
$kernel->call('db:seed', ['--class' => 'DemoDataSeeder', '--force' => true]);
echo "Demo data created!\n\n";

echo "SETUP COMPLETE! Delete this file now.";
echo "</pre>";
```

2. Akses: `https://warungmamcis.shop/setup.php`
3. **HAPUS FILE `setup.php` SETELAH SELESAI!**

### STEP 8: Set Permissions

Via SSH:
```bash
cd ~/laravel_app
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

Via File Manager:
1. Klik kanan folder `storage` → Permissions → Set ke `775`
2. Klik kanan folder `bootstrap/cache` → Permissions → Set ke `775`

### STEP 9: Set Permissions untuk QR Codes

**PENTING untuk QR Code generation:**

1. Buka File Manager
2. Pergi ke `public_html/images/`
3. Buat folder `qrcodes` jika belum ada
4. Set permissions folder `qrcodes` ke **`775`** atau **`777`**

Via SSH:
```bash
cd ~/domains/warungmamcis.shop/public_html
mkdir -p images/qrcodes
chmod 775 images/qrcodes
```

### STEP 10: Clear Cache

Via SSH:
```bash
cd ~/laravel_app
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

Atau buat file `clear-cache.php` di public_html (HAPUS SETELAH PAKAI):
```php
<?php
require '/home/u123456789/laravel_app/vendor/autoload.php';
$app = require_once '/home/u123456789/laravel_app/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->call('config:clear');
$kernel->call('cache:clear');
$kernel->call('view:clear');
$kernel->call('route:clear');

echo "Cache cleared!";
```

---

## ✅ TESTING

1. **Buka website**: https://warungmamcis.shop
2. **Login admin**: https://warungmamcis.shop/login
   - Email: admin@warkop.com
   - Password: password
3. **Test tambah meja baru** dan pastikan QR code muncul
4. **Scan QR** dari HP untuk test customer flow

---

## 🔧 TROUBLESHOOTING

### Error 500 Internal Server Error
- Cek permissions storage/ dan bootstrap/cache/
- Cek path di index.php sudah benar
- Cek .env sudah dikonfigurasi

### QR Code tidak muncul
- Pastikan folder `public_html/images/qrcodes/` ada dan writable (775)
- Cek Hostinger tidak block `file_get_contents()` ke external URL
- Cek log error di `storage/logs/laravel.log`

### Database error
- Pastikan credentials di .env sudah benar
- Test koneksi database via phpMyAdmin

### 419 Page Expired
- Clear cache: `php artisan config:clear`
- Pastikan SESSION_DRIVER=file di .env

---

## 📝 CHECKLIST DEPLOYMENT

- [ ] Upload file Laravel ke `laravel_app/`
- [ ] Upload isi public/ ke `public_html/`
- [ ] Edit path di `public_html/index.php`
- [ ] Install composer dependencies
- [ ] Setup database dan edit .env
- [ ] Jalankan migrations dan seeders
- [ ] Set permissions storage/ (775)
- [ ] Set permissions images/qrcodes/ (775)
- [ ] Clear cache
- [ ] Test website
- [ ] Hapus file setup.php dan clear-cache.php

---

## 🔒 KEAMANAN PRODUCTION

1. **Set APP_DEBUG=false** di .env
2. **Hapus file test** (setup.php, clear-cache.php, dll)
3. **Ganti password admin default**
4. **Setup Midtrans production keys**
5. **Enable HTTPS** (Hostinger biasanya auto SSL)
