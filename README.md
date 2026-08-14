# 🛒 e-Djaya Point of Sales (POS)

**e-Djaya** adalah aplikasi kasir (Point of Sales) modern berbasis web yang dibangun menggunakan **Laravel 11**. Aplikasi ini dirancang untuk memudahkan proses transaksi kasir, manajemen produk, manajemen kategori, pengelolaan pengguna (users), serta pelaporan keuangan dan penjualan.

## ✨ Fitur Utama

- **📦 Manajemen Barang (Produk)**: Tambah, edit, hapus, dan lihat daftar barang (termasuk harga beli, harga jual, dan pembuatan SKU otomatis).
- **📂 Manajemen Kategori**: Pengelompokan barang berdasarkan kategori (contoh: Makanan, Minuman) untuk memudahkan pencarian.
- **🛒 Point of Sales (Kasir)**: Antarmuka kasir yang responsif untuk proses transaksi, perhitungan total belanja, kembalian, dan checkout.
- **🧾 Cetak Struk**: Fitur cetak struk otomatis (format rapi tanpa margin) setelah transaksi berhasil dilakukan.
- **📊 Laporan Penjualan**: 
  - **Laporan Keuangan**: Memantau pemasukan dan pengeluaran.
  - **Laporan Transaksi / Riwayat Transaksi**: Melihat detail dari transaksi-transaksi sebelumnya.
  - **Laporan Produk Terjual**: Mengetahui produk apa saja yang paling laku.
- **👥 Manajemen Pengguna (Users)**: Kelola data staf/kasir yang memiliki akses ke dalam sistem.
- **🔒 Autentikasi & Keamanan**: Login, Register, dan manajemen profil dengan aman (menggunakan Laravel Breeze).

## 💻 Teknologi yang Digunakan

- **Framework PHP**: [Laravel 11.x](https://laravel.com/)
- **Database**: MySQL / MariaDB
- **Frontend**: Blade Templating, Tailwind CSS / Vanilla CSS, JavaScript
- **Icons**: Iconify

## 🚀 Cara Instalasi (Instalasi Lokal)

Ikuti langkah-langkah di bawah ini untuk menjalankan project e-Djaya di komputer lokal Anda:

### 1. Kebutuhan Sistem
Pastikan komputer Anda sudah terinstal:
- PHP (Minimal versi 8.2)
- Composer
- Node.js & NPM
- XAMPP / Laragon (Atau database MySQL lainnya)
- Git

### 2. Langkah-langkah Instalasi

1. **Clone Repository**
   Buka terminal/Command Prompt dan jalankan:
   ```bash
   git clone https://github.com/aziashary/e-djaya.git
   cd e-djaya
   ```

2. **Install Dependensi PHP & Node.js**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   Duplikat file `.env.example` menjadi `.env`.
   ```bash
   copy .env.example .env  # untuk Windows
   # atau
   cp .env.example .env    # untuk Linux/Mac
   ```

4. **Konfigurasi Database**
   Buka file `.env` dan sesuaikan nama koneksi database Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=djaya_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   *(Pastikan Anda telah membuat database kosong bernama `djaya_db` di MySQL/phpMyAdmin Anda).*

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Migrasi Database & Seeding (Data Awal)**
   Jalankan perintah ini untuk membuat struktur tabel di database sekaligus mengisi data bawaan (seperti kategori produk awal, contoh barang, dan role user):
   ```bash
   php artisan migrate --seed
   ```
   *(Pilihan Lain: Anda juga bisa mengimpor database manual dari file `.sql` backup jika ada).*

7. **Compile Aset Frontend**
   Agar tampilan CSS dan JS bisa dimuat dengan benar:
   ```bash
   npm run build
   # atau jika Anda sedang mengembangkan (development): npm run dev
   ```

8. **Jalankan Aplikasi**
   Terakhir, hidupkan server lokal Laravel:
   ```bash
   php artisan serve
   ```
   Buka browser favorit Anda dan akses aplikasi melalui: **[http://localhost:8000](http://localhost:8000)**

## 📸 Tampilan Layar (Screenshots)

*(Tambahkan gambar screenshot aplikasi Anda di sini untuk memberikan gambaran UI/UX kepada calon pengguna di GitHub)*

- **Halaman Dashboard:** `![Dashboard](link-gambar)`
- **Halaman Kasir (POS):** `![POS](link-gambar)`
- **Halaman Cetak Struk:** `![Struk](link-gambar)`
- **Laporan Transaksi:** `![Laporan](link-gambar)`

## 👨‍💻 Kontributor

- **Azi Ashary** - *Main Developer*

---
*Dibuat dengan ❤️ untuk kemudahan operasional kasir dan toko.*
