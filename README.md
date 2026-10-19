# Simple POS - Kelompok 6 - SIB 3C

## Tim Pengembang 

| Nama Mahaisswa | NIM |
| :--- | :--- |
| Tersiqo Alfarezzel | 244107060089 |
| Fiza Rahmatus Sholikha | 244107060109 |
| Yanuar Alda Baran | 244107060016 |
| Zaskia Maulidina Mutiara Hati | 244107060056 |

## Skenario Uji Manual (Role-Based Access Control) Pertemuan 7

Berikut adalah skenario pengujian manual untuk membuktikan pembatasan hak akses masing-masing peran di aplikasi Simple POS:

### 1. Peran: Admin
* **Tujuan:**  Memastikan admin dapat mengakses seluruh halaman yang diizinkan untuk admin.
* **Langkah:** 
  1. Buka aplikasi dan arahkan ke halaman `/login`.
  2. Login menggunakan email `admin@pos.test` dan kata sandi `password`.
  3. Klik menu "Produk" di navigasi atau akses rute `/products` di URL untuk melihat daftar produk.
  4. Klik menu "Kategori" di navigasi atau akses rute `/categories` di URL untuk melihat daftar kategori.
  5. Klik menu "Transaksi" di navigasi atau akses rute `/transactions` di URL untuk melihat transaksi.
* **Hasil yang Diharapkan:** 
  - Admin berhasil login.
  - Halaman Produk dan Kategori dapat dibuka.
  - Halaman Transaksi dapat diakses.
  - Tidak muncul pesan 403 Forbidden pada halaman yang diizinkan.

### 2. Peran: Kasir
* **Tujuan:** Memastikan kasir dapat menjalankan aktivitas kasir, tetapi tidak dapat mengakses halaman khusus admin.
* **Langkah:** 
  1. Login menggunakan email `kasir@pos.test` dan kata sandi `password`.
  2. Pastikan halaman diarahkan ke `/pos` (Kasir).
  3. Coba paksa masuk ke halaman produk dengan mengetik `/products` langsung di *address bar* browser.
* **Hasil yang Diharapkan:** 
  - Kasir berhasil login
  - Menu Produk dan Kategori tidak muncul di navigasi. 
  - Saat memaksa akses rute `/products`, sistem menolak dan menampilka halaman error **403 Forbidden** dengan pesan "Halaman ini hanya untuk peran admin.". Akses ke `/pos` tetap berjalan normal.

### 3. Peran: Manager (Tugas Mandiri)
* **Tujuan:** Memastikan manager dapat mengakses halaman Transaksi, tetapi tidak dapat mengakses halaman Produk.
* **Langkah:** 
  1. Login menggunakan akun dengan role `manager`.
  2. Klik menu "Transaksi" (rute `/transactions`).
  3. Coba paksa akses rute `/products` di URL.
* **Hasil yang Diharapkan:** 
  - Manager berhasil login
  - Halaman riwayat transaksi berhasil dibuka. 
  - halaman produk ditolak, sistem menolak dengan pesan error 403 karena akses Produk hanya diberikan kepada admin.

### 4. Peran: Tamu / Belum Login (Guest)
* **Langkah:** 
  1. Pastikan belum login (atau klik tombol "Keluar" jika sedang login).
  2. Buka rute `/pos` atau `/products` langsung di *address bar*.
* **Hasil yang Diharapkan:** 
  Sistem mencegah akses dan otomatis mengarahkan (*redirect*) pengguna kembali ke halaman `/login`.

>**Catatan:** Pengujian manager dapat dilakukan setelah peran ketiga dan aturan aksesnya selesai ditambahkan ke aplikasi. Akun manager juga perlu dibuat terlebih dahulu karena akun demo bawaan hanya menyediakan admin dan kasir.