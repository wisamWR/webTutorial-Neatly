# Advanced Project — Tutorial System

Sistem manajemen dan presentasi materi tutorial real-time, dibangun di atas CodeIgniter 4 dengan penyimpanan berbasis flat-file JSON dan otentikasi JWT via webservice eksternal.

## Tech Stack

| Komponen | Teknologi |
|---|---|
| Backend Framework | CodeIgniter 4 (PHP) |
| Template UI | NiceAdmin + Bootstrap 5 |
| Penyimpanan Data | Flat-file JSON (`writable/data/`) |
| Syntax Highlighting | Prism.js |
| Autentikasi | JWT Webservice Eksternal |

## Fitur

- Otentikasi berlapis via JWT Webservice (`jwt-auth-eight-neon.vercel.app`)
- Session Auth Filter — proteksi seluruh route manajemen
- CRUD Tutorial (judul, deskripsi, status)
- CRUD Detail Langkah — tipe konten: **Text**, **Image**, **Code**, **URL**
- Re-order langkah via tombol Swap (naik/turun)
- Cascade Delete — hapus gambar yatim di `public/uploads/` saat langkah dihapus
- Live Presentation Mode — AJAX polling auto-refresh tanpa reload halaman
- Toggle visibilitas langkah (tampil/sembunyikan saat presentasi)

## Prasyarat

- PHP 8.1+
- Composer
- Extension PHP: `intl`, `mbstring`, `json`, `curl`

## Instalasi

1. **Clone repositori**
   ```bash
   git clone <repo-url>
   cd <folder-repo>
   ```

2. **Install dependensi**
   ```bash
   composer install
   ```

3. **Jalankan server lokal**
   ```bash
   php spark serve
   ```
   Aplikasi berjalan di `http://localhost:8080`.

> Tidak perlu konfigurasi `.env` atau database. Data disimpan otomatis ke `writable/data/` saat pertama kali digunakan.

## Login

Gunakan kredensial **akun Dosen** dari webservice JWT yang tertaut. Sistem mengirim email & password ke API eksternal, lalu menyimpan `refreshToken` di server session.

## Struktur Folder Penting

```
app/
├── Controllers/
│   ├── AuthController.php       # Login & logout via JWT
│   ├── TutorialController.php   # CRUD tutorial
│   ├── DetailController.php     # CRUD langkah, swap order, toggle
│   └── PresentationController.php # Mode presentasi publik + endpoint AJAX
├── Libraries/
│   ├── JsonStorage.php          # Mini ORM flat-file (read/write/insert/update/delete)
│   └── JwtService.php           # Wrapper cURL ke webservice JWT eksternal
├── Filters/
│   ├── AuthFilter.php           # Redirect ke /login jika belum login
│   └── GuestFilter.php          # Redirect ke / jika sudah login
└── Views/                       # Template NiceAdmin

writable/data/                   # File JSON runtime (tutorials.json, details.json)
public/uploads/                  # Gambar yang diunggah via form detail
```

## Alur Data

```
Browser → Controller → JsonStorage::method() → writable/data/*.json
```

Tidak ada ORM, tidak ada query SQL. `JsonStorage` membaca seluruh file JSON ke array PHP, memanipulasinya, lalu menulisnya kembali.

## Route Utama

| Method | URL | Deskripsi |
|---|---|---|
| GET | `/login` | Halaman login |
| GET | `/` | Dashboard (daftar mata kuliah) |
| GET | `/tutorial` | Daftar tutorial |
| GET | `/tutorial/detail/:id` | Manajemen langkah tutorial |
| GET | `/presentation/:id` | Mode presentasi publik |
| GET | `/presentation/:id/data` | Endpoint AJAX polling presentasi |

---

Dibuat oleh **Mohammad Wisam Wiraghina**
