# Cara Menjalankan Proyek OJS Publikasi

## Persyaratan

Pastikan sudah terinstall di sistem:

- **PHP** >= 8.0
- **Node.js** >= 16
- **npm** >= 8

Cek dengan perintah:

```bash
php -v
node -v
npm -v
```

---

## Langkah Pertama (Hanya Sekali)

Install dependensi Node.js (Tailwind CSS, dll):

```bash
cd "/home/vanzer/Documents/PROJECT CLIENT/ojs_publikasi"
npm install
```

---

## Menjalankan Proyek

### 1. Build CSS Tailwind

Sebelum membuka di browser, compile dulu CSS-nya:

```bash
npm run build
```

### 2. Jalankan PHP Dev Server

```bash
php -S localhost:8000
```

Buka browser dan akses:

```
http://localhost:8000
```

---

## Mode Development (Recommended)

Gunakan dua terminal sekaligus agar CSS otomatis ter-update saat ada perubahan:

**Terminal 1 — Tailwind watch:**

```bash
cd "/home/vanzer/Documents/PROJECT CLIENT/ojs_publikasi"
npm run watch
```

**Terminal 2 — PHP server:**

```bash
cd "/home/vanzer/Documents/PROJECT CLIENT/ojs_publikasi"
php -S localhost:8000
```

Setiap kali ada perubahan pada file `.php` atau `.js`, Tailwind akan otomatis rebuild CSS-nya.

---

## Struktur Folder

```
ojs_publikasi/
├── index.php              ← Halaman utama
├── includes/
│   ├── header.php         ← Navbar & head HTML
│   └── footer.php         ← Footer
├── pages/                 ← Halaman-halaman lainnya
├── assets/
│   ├── css/
│   │   ├── input.css      ← Source CSS (edit di sini)
│   │   └── output.css     ← CSS hasil build (jangan edit manual)
│   ├── js/
│   └── images/
├── tailwind.config.js     ← Konfigurasi Tailwind
├── package.json
└── docs/                  ← Dokumentasi proyek
```

---

## npm Scripts

| Perintah        | Fungsi                                      |
|-----------------|---------------------------------------------|
| `npm run build` | Compile CSS Tailwind sekali                 |
| `npm run watch` | Auto-compile CSS setiap ada perubahan file  |

---

## Port Berbeda

Jika port 8000 sudah dipakai, gunakan port lain:

```bash
php -S localhost:3000
```

Lalu akses `http://localhost:3000`.
