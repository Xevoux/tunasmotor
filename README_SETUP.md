# Tunas Motor - Website Jual Beli Sparepart Motor

Website e-commerce untuk penjualan sparepart motor dengan tampilan modern dan responsif.

## Fitur

- 🔐 Sistem login dan registrasi
- 🏠 Halaman home one-page dengan berbagai section
- 🛒 Keranjang belanja
- 📦 Manajemen produk
- ⭐ Rating dan review produk
- 🎨 UI/UX modern dan responsif
- 🔍 Pencarian produk
- 💳 Sistem diskon dan kupon

## Teknologi yang Digunakan

- **Backend**: Laravel 11
- **Frontend**: Blade Templates, CSS3, JavaScript (Vanilla)
- **Database**: MySQL
- **Package Manager**: Composer, NPM

## Persyaratan Sistem

- PHP >= 8.2
- MySQL >= 8.0
- Composer
- Node.js & NPM

## Cara Instalasi

### 1. Clone atau Extract Project

```bash
cd D:\EmirFile\Telkom University\Tugas Semester 7\PTI\tunasmotor
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies (opsional)
npm install
```

### 3. Konfigurasi Database

#### A. Buat Database MySQL

Buka MySQL dan buat database baru:

```sql
CREATE DATABASE tunasmotor_db;
```

#### B. Konfigurasi File .env

1. Jika belum ada file `.env`, copy dari `.env.example`:
   ```bash
   copy .env.example .env
   ```

2. Buka file `.env` dan sesuaikan konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=tunasmotor_db
   DB_USERNAME=root
   DB_PASSWORD=your_mysql_password
   ```

   **Catatan**: Ganti `your_mysql_password` dengan password MySQL Anda. Jika tidak ada password, kosongkan saja.

#### C. Generate Application Key

```bash
php artisan key:generate
```

### 4. Jalankan Migration dan Seeder

```bash
php artisan migrate:fresh --seed
```

Perintah ini akan:
- Membuat tabel-tabel database
- Mengisi data kategori dan produk contoh
- Membuat user untuk testing

**User untuk Login:**
- Email: `admin@tunasmotor.com` | Password: `password`
- Email: `user@test.com` | Password: `password`

### 5. Buat Storage Link (untuk upload gambar)

```bash
php artisan storage:link
```

### 6. Jalankan Server

```bash
php artisan serve
```

Aplikasi akan berjalan di: `http://localhost:8000`

## Struktur Database

### Tabel Utama:

1. **users** - Data pengguna
2. **categories** - Kategori produk
3. **products** - Data produk sparepart
4. **carts** - Keranjang belanja
5. **orders** - Data pesanan
6. **order_items** - Detail item pesanan

## Halaman yang Tersedia

### 1. Login Page (`/login`)
- Halaman login dengan social login (Google, Facebook, Apple)
- Form login email dan password
- Link ke halaman register

### 2. Register Page (`/register`)
- Form registrasi user baru
- Validasi password dan konfirmasi password

### 3. Home Page (`/home`)
One-page website dengan section:
- **Header**: Logo, navigasi, search, cart, user menu
- **Hero Section**: Banner dengan countdown timer deal
- **Features**: Original Products, Affordable Rates, Wide Variety
- **New Arrivals**: Grid produk terbaru
- **Discount Banner**: Banner diskon dengan kode kupon
- **Products**: Grid semua produk dengan filter dan search
- **Footer**: Form subscribe, contact info, links

### 4. Cart Page (`/cart`)
- Daftar produk di keranjang
- Update quantity produk
- Hapus produk dari keranjang
- Summary total harga
- Input kupon diskon
- Tombol checkout

## Cara Menggunakan

### Login
1. Buka browser dan akses `http://localhost:8000`
2. Anda akan diarahkan ke halaman login
3. Login dengan kredensial:
   - Email: `admin@tunasmotor.com`
   - Password: `password`
4. Setelah login, Anda akan masuk ke halaman home

### Browse Produk
1. Di halaman home, scroll untuk melihat berbagai section
2. Gunakan search bar untuk mencari produk spesifik
3. Klik tombol "Tambah ke Keranjang" untuk menambahkan produk

### Keranjang Belanja
1. Klik icon keranjang di header
2. Lihat produk yang sudah ditambahkan
3. Update quantity atau hapus produk
4. Klik "Proceed to checkout" untuk melanjutkan

## File Penting

### Controllers
- `HomeController.php` - Menampilkan halaman home dengan produk
- `AuthController.php` - Handle login, register, logout
- `CartController.php` - Manage keranjang belanja
- `ProductController.php` - Manage produk (untuk admin)

### Views
- `auth/login.blade.php` - Halaman login
- `auth/register.blade.php` - Halaman register
- `home.blade.php` - Halaman home one-page
- `cart.blade.php` - Halaman keranjang belanja
- `layouts/app.blade.php` - Layout template utama

### Assets
- `public/css/style.css` - Styling CSS utama
- `public/js/app.js` - JavaScript untuk interaksi

### Database
- `database/migrations/` - File migration untuk struktur database
- `database/seeders/` - File seeder untuk data contoh

## Troubleshooting

### Error: SQLSTATE[HY000] [1045] Access denied

**Solusi:**
1. Pastikan MySQL sudah berjalan
2. Cek kredensial database di file `.env`
3. Pastikan database `tunasmotor_db` sudah dibuat
4. Cek username dan password MySQL

### Error: Class not found

**Solusi:**
```bash
composer dump-autoload
php artisan clear-compiled
php artisan cache:clear
php artisan config:clear
```

### Gambar produk tidak muncul

**Solusi:**
1. Jalankan: `php artisan storage:link`
2. Pastikan folder `storage/app/public` ada dan writable
3. Upload gambar produk ke `storage/app/public/products/`

### CSS/JS tidak load

**Solusi:**
1. Clear browser cache
2. Pastikan file `public/css/style.css` dan `public/js/app.js` ada
3. Jalankan: `php artisan view:clear`

## Fitur Tambahan (Opsional)

### Menambah Produk Baru

Buat seeder baru atau edit `ProductSeeder.php` dan tambahkan data produk:

```php
[
    'category_id' => 1,
    'nama' => 'Nama Produk',
    'deskripsi' => 'Deskripsi produk',
    'harga' => 100000,
    'harga_diskon' => 80000,
    'stok' => 50,
    'terjual' => 0,
    'gambar' => null,
    'rating' => 4.5,
    'jumlah_rating' => 10,
    'is_new' => true,
    'diskon_persen' => 20,
],
```

Kemudian jalankan:
```bash
php artisan db:seed --class=ProductSeeder
```

### Upload Gambar Produk

1. Siapkan gambar produk
2. Upload ke folder `storage/app/public/products/`
3. Update database dengan nama file gambar
4. Atau gunakan form upload (fitur yang bisa dikembangkan)

## Development

### Running Tests
```bash
php artisan test
```

### Build Assets (jika menggunakan Vite)
```bash
npm run dev     # Development
npm run build   # Production
```

## Lisensi

Project ini dibuat untuk keperluan tugas kuliah Telkom University.

## Kontak

Untuk pertanyaan atau masalah, silakan hubungi:
- Email: admin@tunasmotor.com
- GitHub: [Repository Link]

---

**Selamat mencoba! 🚀**

