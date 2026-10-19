# 📦 Sistem Inventori Barang

Aplikasi manajemen inventori berbasis **PHP Native** dan **MySQL** yang dibangun sebagai tugas individu mata kuliah **Implementasi Coding Standard & Version Control**.

---

## 🎯 Fitur Aplikasi

| Fitur | Keterangan |
|---|---|
| **Dashboard** | Statistik total barang, total stok, stok menipis, dan 5 barang terbaru |
| **Daftar Barang** | Tabel lengkap dengan pencarian, filter stok menipis, dan pagination |
| **Tambah Barang** | Form 8 field dengan validasi client-side dan server-side |
| **Edit Barang** | Form terisi data existing, update langsung ke database |
| **Hapus Barang** | Dengan konfirmasi modal sebelum data dihapus |

---

## 🗂️ Struktur Folder

```
tugas_RPL/
├── assets/
│   ├── css/
│   │   └── style.css          # Stylesheet utama (CSS Variables, responsif)
│   └── js/
│       └── main.js            # JavaScript (sidebar, modal hapus, validasi)
├── config/
│   └── database.php           # Konfigurasi koneksi PDO ke MySQL
├── database/
│   └── inventori.sql          # Script SQL: buat tabel + data contoh
├── includes/
│   ├── functions.php          # Semua fungsi CRUD, utilitas, flash message
│   ├── header.php             # Layout header: HTML head, sidebar, topbar
│   └── footer.php             # Layout footer: penutup HTML, modal hapus global
├── pages/
│   ├── dashboard.php          # Halaman dashboard statistik
│   ├── barang.php             # Halaman daftar barang
│   ├── tambah.php             # Halaman form tambah barang
│   └── edit.php               # Halaman form edit barang
├── process/
│   └── barang_process.php     # Handler POST: proses add, edit, delete
├── .gitignore                 # File yang dikecualikan dari Git
├── index.php                  # Entry point → redirect ke dashboard
└── README.md                  # Dokumentasi ini
```

---

## ⚙️ Persyaratan Sistem

- **XAMPP** versi 7.4+ (PHP 7.4+ dan MySQL 5.7+ / MariaDB 10.4+)
- Browser modern (Chrome, Firefox, Edge)
- Git (untuk version control)

---

## 🚀 Cara Instalasi & Menjalankan

### Langkah 1 — Pastikan XAMPP berjalan

Buka **XAMPP Control Panel** dan jalankan:
- ✅ **Apache**
- ✅ **MySQL**

### Langkah 2 — Tempatkan project di folder htdocs

Pastikan folder project berada di:
```
C:\xampp\htdocs\tugas_RPL\
```

### Langkah 3 — Import database

Ada dua cara:

**Cara A — Via phpMyAdmin (disarankan):**
1. Buka browser → `http://localhost/phpmyadmin`
2. Klik **New** → buat database bernama `inventori_barang`
3. Klik tab **Import** → pilih file `database/inventori.sql`
4. Klik tombol **Go / Import**

**Cara B — Via Terminal MySQL:**
```bash
mysql -u root -p < C:/xampp/htdocs/tugas_RPL/database/inventori.sql
```

### Langkah 4 — Sesuaikan konfigurasi database (jika perlu)

Buka file `config/database.php` dan sesuaikan nilainya:

```php
define('DB_HOST', 'localhost');   // Host MySQL
define('DB_PORT', '3306');        // Port MySQL
define('DB_NAME', 'inventori_barang'); // Nama database
define('DB_USER', 'root');        // Username MySQL
define('DB_PASS', '');            // Password MySQL (kosong untuk XAMPP default)
```

### Langkah 5 — Buka aplikasi di browser

```
http://localhost/tugas_RPL
```

Aplikasi akan otomatis menampilkan halaman **Dashboard**.

---

## 🖥️ Menjalankan di VS Code + XAMPP

1. Buka VS Code → **File → Open Folder** → pilih `C:\xampp\htdocs\tugas_RPL`
2. Edit file sesuai kebutuhan
3. Pastikan Apache dan MySQL di XAMPP sudah aktif
4. Buka browser dan akses `http://localhost/tugas_RPL`
5. Setiap kali menyimpan file di VS Code, refresh browser untuk melihat perubahan

> **Tips:** Install ekstensi **PHP Intelephense** di VS Code untuk IntelliSense PHP yang lebih baik.

---

## 🔒 Coding Standard yang Diterapkan

### Keamanan
- ✅ **Prepared Statements** — semua query database menggunakan PDO prepared statements untuk mencegah SQL Injection
- ✅ **`htmlspecialchars()`** — semua output user-input di-escape sebelum ditampilkan di HTML
- ✅ **`filter_input()`** — validasi dan sanitasi input dari GET/POST
- ✅ **Open Redirect Protection** — URL redirect divalidasi agar hanya menuju domain yang sama

### Struktur Kode
- ✅ **Pemisahan Concern** — config, fungsi, tampilan, dan proses CRUD dipisahkan ke file masing-masing
- ✅ **Naming Convention** — `snake_case` untuk variabel/fungsi PHP, `camelCase` untuk JavaScript
- ✅ **Docblock Komentar** — setiap fungsi dilengkapi PHPDoc (`@param`, `@return`)
- ✅ **Type Hints** — deklarasi tipe parameter dan return value pada semua fungsi
- ✅ **Validasi Dua Lapis** — client-side (JavaScript) dan server-side (PHP)

### Tampilan
- ✅ **CSS Custom Properties** — design token untuk warna, ukuran, dan shadow
- ✅ **Responsif** — layout menyesuaikan layar mobile, tablet, dan desktop
- ✅ **Aksesibilitas** — atribut `aria-*`, `role`, dan `aria-label` pada elemen interaktif

---

## 🌿 Panduan Version Control (Git)

Project ini disiapkan untuk dikelola dengan Git. Berikut alur commit yang disarankan:

### Inisialisasi Repository

```bash
cd C:\xampp\htdocs\tugas_RPL
git init
git remote add origin https://github.com/username/tugas-inventori-rpl.git
```

### Commit 1 — Struktur project dan konfigurasi database

```bash
git add config/database.php database/inventori.sql .gitignore README.md
git commit -m "feat: inisialisasi project dan konfigurasi database"
```

### Commit 2 — Fitur CRUD barang

```bash
git add includes/ pages/ process/ index.php
git commit -m "feat: tambah fitur CRUD barang (tambah, edit, hapus, daftar)"
```

### Commit 3 — Validasi, pencarian, dan perapian tampilan

```bash
git add assets/css/style.css assets/js/main.js
git commit -m "feat: tambah validasi form, pencarian, dan perbaikan tampilan responsif"
```

### Push ke GitHub

```bash
git push -u origin main
```

---

## 📷 Preview Halaman

| Halaman | URL |
|---|---|
| Dashboard | `http://localhost/tugas_RPL` |
| Daftar Barang | `http://localhost/tugas_RPL/pages/barang.php` |
| Tambah Barang | `http://localhost/tugas_RPL/pages/tambah.php` |
| Edit Barang | `http://localhost/tugas_RPL/pages/edit.php?id=1` |
| Stok Menipis | `http://localhost/tugas_RPL/pages/barang.php?filter=menipis` |

---

## 👤 Informasi

- **Mata Kuliah:** Implementasi Coding Standard & Version Control
- **Teknologi:** PHP Native, MySQL, HTML5, CSS3, JavaScript (Vanilla)
- **Server:** XAMPP (Apache + MySQL)
- **Database Client:** phpMyAdmin
