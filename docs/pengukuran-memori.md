# Pengukuran Memori: get() vs lazyById()

**Dataset:** 52.500 row pada tabel `transactions`.

| Cara baca data | Peak memory (MB) |
|---|---:|
| `Transaction::lazyById()` | 39,4 MB |
| `Transaction::get()` | 97,7 MB |

**Kesimpulan kelompok:**

Berdasarkan hasil pengukuran terhadap 52.500 data transaksi, metode `lazyById()` menggunakan memori puncak sebesar 39,4 MB, sedangkan metode `get()` menggunakan 97,7 MB. Hal ini menunjukkan bahwa `lazyById()` lebih hemat memori pada pengujian ini karena data diproses secara bertahap, sedangkan `get()` mengambil seluruh data sekaligus ke dalam memori. Dengan demikian, `lazyById()` lebih sesuai digunakan ketika aplikasi perlu memproses data dalam jumlah besar agar penggunaan memori lebih efisien.