# Simple POS - Kelompok 6 - SIB 3C

## Tim Pengembang 

| Nama Mahaisswa | NIM |
| :--- | :--- |
| Tersiqo Alfarezzel | 244107060089 |
| Fiza Rahmatus Sholikha | 244107060109 |
| Yanuar Alda Baran | 244107060016 |
| Zaskia Maulidina Mutiara Hati | 244107060056 |

## Ringkasan Pengembangan: Increment 6 (Validasi dan Keamanan Input)

Pada *Increment 6*, pengembangan aplikasi Simple POS berfokus pada penerapan validasi input dan peningkatan keamanan aplikasi untuk mencegah manipulasi data transaksi. Pengembangan ini mencakup beberapa hal berikut:

1. **Perlindungan *Mass Assignment*:** Penggunaan properti `$fillable` pada model untuk membatasi atribut yang dapat diisi secara massal.
2. **Validasi Berbasis *Form Request*:** Penggunaan kelas `StoreProductRequest` dan `StoreTransactionRequest` untuk memisahkan proses validasi input dari *controller*.
3. **Validasi Stok dan Kuantitas Produk:** Pemeriksaan jumlah stok yang tersedia serta penanganan kuantitas produk pada keranjang kasir agar sesuai dengan jumlah pembelian.
4. **Perhitungan Total Transaksi di *Server-Side Calculation*:** Perhitungan subtotal dan total transaksi menggunakan harga produk dari basis data agar hasil transaksi tidak bergantung pada nilai yang dikirim dari formulir pengguna.

---

## Tantangan Mandiri: Validasi dan Keamanan Input

### Analisis Validasi `numeric` pada Atribut `total`

Validasi menggunakan aturan `numeric` saja tidak cukup untuk mencegah manipulasi total transaksi. Aturan tersebut hanya memastikan bahwa nilai yang diterima berupa angka, tetapi tidak memastikan bahwa nilai tersebut sesuai dengan hasil perhitungan harga produk dan jumlah barang yang dibeli.

Pengguna masih dapat mengubah nilai `total` yang dikirim melalui formulir, baik menggunakan *Browser Developer Tools* maupun dengan mengirimkan *request* secara manual. Sebagai contoh, total transaksi yang seharusnya sebesar Rp72.955 dapat diubah menjadi Rp1. Nilai tersebut tetap dapat lolos validasi `numeric` karena masih berupa angka.

Oleh karena itu, data dari *client-side* tidak boleh langsung dipercaya, terutama untuk perhitungan transaksi. Nilai total sebaiknya dihitung ulang di *server-side* berdasarkan harga produk yang tersimpan di basis data dan jumlah barang yang dibeli.

Pada aplikasi Simple POS, perhitungan total transaksi dilakukan di dalam `TransactionController::store()`. Server mengambil data produk berdasarkan `product_id`, menggunakan harga produk dari basis data, kemudian menghitung subtotal dengan mengalikan harga produk dan jumlah pembelian (`qty`). Seluruh subtotal dijumlahkan untuk memperoleh total transaksi.

Dengan demikian, validasi `numeric` saja tidak cukup untuk mencegah manipulasi total transaksi. Total transaksi harus dihitung di sisi server berdasarkan harga produk dari basis data dan jumlah pembelian yang telah divalidasi, bukan menggunakan nilai total yang dikirim oleh pengguna.
