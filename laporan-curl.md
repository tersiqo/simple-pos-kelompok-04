## Laporan Pengujian Validasi Form Kasir via cURL (Tugas Mandiri)
**Oleh: Yanuar Alda Baran**

Berdasarkan instruksi pembagian tugas kelompok, berikut adalah hasil pengujian *request* `POST` ke *endpoint* `/pos` menggunakan `curl` untuk mengamati pesan *error* validasi dari sistem Laravel. Pengujian dilakukan dengan menambahkan header `Accept: application/json` serta mengecualikan *route* `/pos` dari *middleware* CSRF sementara waktu agar *request* tidak diblokir.

### Kasus 1: Mengirim Kuantitas (qty) Bernilai 0
**Perintah yang dijalankan:**
`curl.exe -X POST http://127.0.0.1:8000/pos -H "Accept: application/json" -d "items[0][product_id]=1" -d "items[0][qty]=0"`

**Pesan Error yang Dikembalikan Laravel:**
`{"message":"The items.0.qty field must be at least 1.","errors":{"items.0.qty":["The items.0.qty field must be at least 1."]}}`

**Analisis:**
Laravel mengembalikan *error* di atas karena pada file `StoreTransactionRequest`, input `qty` dilindungi oleh aturan validasi `min:1`. Karena *request* mengirimkan nilai `0`, sistem secara otomatis menolaknya karena tidak memenuhi syarat batas minimal.

---

### Kasus 2: Mengirim ID Produk (product_id) yang Tidak Terdaftar
**Perintah yang dijalankan:**
`curl.exe -X POST http://127.0.0.1:8000/pos -H "Accept: application/json" -d "items[0][product_id]=99999" -d "items[0][qty]=1"`

**Pesan Error yang Dikembalikan Laravel:**
`{"message":"The selected items.0.product_id is invalid.","errors":{"items.0.product_id":["The selected items.0.product_id is invalid."]}}`

**Analisis:**
Laravel menolak *request* ini karena pada file `StoreTransactionRequest`, input `product_id` memiliki aturan validasi `exists:products,id`. Sistem mengecek langsung ke dalam tabel `products` di basis data dan tidak menemukan produk dengan ID `99999`, sehingga input dianggap tidak valid.