# Panduan Penggunaan API di Postman

Dokumen ini menjelaskan langkah-langkah untuk melakukan pengujian API (terutama fitur tambah buku dan tambah jurnal beserta upload gambarnya) menggunakan **Postman**. 

File koleksi yang dibutuhkan sudah disiapkan di root folder proyek: `postman_collection.json`.

---

## 1. Import Koleksi ke Postman

Langkah pertama adalah mengimpor file `postman_collection.json` agar semua template request tersedia di aplikasi Postman Anda.

1. Buka aplikasi **Postman**.
2. Di bagian kiri atas, klik tombol **Import** (atau tekan `Ctrl`+`O` / `Cmd`+`O`).
3. Tarik (drag & drop) file `postman_collection.json` yang ada di root direktori proyek Anda ke dalam kotak import, atau klik **Choose Files** dan cari file tersebut.
4. Klik **Import**. 
5. Setelah berhasil, akan muncul folder koleksi baru bernama **"OJS Publikasi API"** di sidebar Postman Anda.

---

## 2. Struktur API & Pentingnya Login (Sesi)

API penambahan buku dan jurnal dilindungi oleh **Auth Middleware**. Artinya, Anda **tidak bisa** menembak langsung API penambahan buku/jurnal tanpa login terlebih dahulu.

**Bagaimana cara Postman "Login"?**
Postman secara otomatis dapat mengelola *Cookie* layaknya browser web. Saat Anda memukul (hit) endpoint login dan berhasil, server PHP akan mengembalikan *Session Cookie*. Postman akan menyimpan cookie ini dan menyisipkannya di request selanjutnya.

Oleh karena itu, **Anda wajib menjalankan request Login terlebih dahulu** sebelum mencoba API yang lain.

---

## 3. Langkah-Langkah Pengujian

### Langkah A: Melakukan Login (Wajib)
1. Buka folder koleksi **OJS Publikasi API**.
2. Klik request bernama **`1. Login Admin`**.
3. Pastikan method-nya adalah **POST**.
4. Buka tab **Body**. Di sana Anda akan melihat data sudah terisi (`username: fansya`, `password: Fansya123!`).
5. Klik tombol **Send** berwarna biru.
6. Periksa area **Response** di bagian bawah. Jika berhasil, Anda akan melihat JSON seperti ini:
   ```json
   {
       "status": "success",
       "message": "Login berhasil"
   }
   ```
   *Note: Setelah ini, Anda bebas mengeksekusi request tambah buku/jurnal karena sesi admin Anda sudah disimpan oleh Postman.*

### Langkah B: Menguji Tambah Buku & Upload Gambar
1. Buka request **`2. Tambah Buku (dengan Gambar)`**.
2. Buka tab **Body** (pastikan mode-nya `form-data`).
3. Anda akan melihat daftar field (*Key* dan *Value*) seperti `title`, `author`, dll. Anda bisa mengubah value-nya sesuai kebutuhan.
4. Perhatikan pada key **`image`**. 
   - Anda akan melihat tombol `Select Files` di kolom Value-nya.
   - Klik tombol **Select Files** dan pilih file gambar dari komputer Anda (Pastikan formatnya JPG, PNG, WebP, atau GIF dan **ukuran di bawah 2 MB**).
5. Klik tombol **Send**.
6. Jika berhasil, Anda akan mendapat response sukses beserta path gambar yang baru saja di-upload:
   ```json
   {
       "status": "success",
       "message": "Buku berhasil ditambahkan",
       "image": "/storage/books/book_650b23abcd.jpg"
   }
   ```

### Langkah C: Menguji Tambah Jurnal & Upload Gambar
Langkah ini persis sama dengan Langkah B.
1. Buka request **`3. Tambah Jurnal (dengan Gambar)`**.
2. Buka tab **Body** dan sesuaikan data yang ingin Anda isi.
3. Pada baris key **`image`**, klik `Select Files` dan pilih foto untuk jurnal tersebut.
4. Klik **Send** dan periksa response dari server. 

---

## 4. Tips & Troubleshooting

- **"Metode request tidak diizinkan"**: Pastikan Anda mengirim menggunakan metode `POST`.
- **Redirect ke halaman login HTML**: Jika response di tab "Preview" atau "Body" menunjukkan source code halaman HTML (login), itu berarti *Session Cookie* Anda belum di-set atau sudah kedaluwarsa. **Solusi:** Jalankan kembali request **`1. Login Admin`**.
- **"Ukuran foto maksimal 2 MB"**: File gambar yang Anda pilih pada parameter `image` terlalu besar. Gunakan file yang ukurannya lebih kecil.
- **Server Tidak Ditemukan**: Pastikan Anda sudah menyalakan server lokal PHP di terminal Anda (menggunakan `php -S localhost:8000`) sebelum melakukan pengujian API di Postman.
