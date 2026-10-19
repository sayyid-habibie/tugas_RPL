# Sistem Inventori Barang

Aplikasi manajemen inventori berbasis **PHP Native** dan **MySQL** yang dibangun sebagai tugas individu mata kuliah **Implementasi Coding Standard & Version Control**.

---

## Fitur Aplikasi

| Fitur | Keterangan |
|---|---|
| **Dashboard** | Statistik total barang, total stok, stok menipis, dan 5 barang terbaru |
| **Daftar Barang** | Tabel lengkap dengan pencarian, filter stok menipis, dan pagination |
| **Tambah Barang** | Form 8 field dengan validasi client-side dan server-side |
| **Edit Barang** | Form terisi data existing, update langsung ke database |
| **Hapus Barang** | Dengan konfirmasi modal sebelum data dihapus |

---

## Struktur Folder

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
├── index.php                  # Entry point -> redirect ke dashboard
└── README.md                  # Dokumentasi ini
```

---

## Persyaratan Sistem

- **XAMPP** versi 7.4+ (PHP 7.4+ dan MySQL 5.7+ / MariaDB 10.4+)
- Browser modern (Chrome, Firefox, Edge)
- Git (untuk version control)

---

## Cara Instalasi & Menjalankan

### Langkah 1 — Pastikan XAMPP berjalan

Buka **XAMPP Control Panel** dan jalankan:

- Apache
- MySQL

### Langkah 2 — Tempatkan project di folder htdocs

Pastikan folder project berada di:

```
C:\xampp\htdocs\tugas_RPL\
```

### Langkah 3 — Import database

**Cara A — Via phpMyAdmin (disarankan):**

1. Buka browser: `http://localhost/phpmyadmin`
2. Klik **New**, buat database bernama `inventori_barang`
3. Klik tab **Import**, pilih file `database/inventori.sql`
4. Klik tombol **Go / Import**

**Cara B — Via Terminal MySQL:**

```bash
mysql -u root -p < C:/xampp/htdocs/tugas_RPL/database/inventori.sql
```

### Langkah 4 — Sesuaikan konfigurasi database (jika perlu)

Buka file `config/database.php` dan sesuaikan nilainya:

```php
define('DB_HOST', 'localhost');        // Host MySQL
define('DB_PORT', '3306');             // Port MySQL
define('DB_NAME', 'inventori_barang'); // Nama database
define('DB_USER', 'root');             // Username MySQL
define('DB_PASS', '');                 // Password MySQL (kosong untuk XAMPP default)
```

### Langkah 5 — Buka aplikasi di browser

```
http://localhost/tugas_RPL
```

Aplikasi akan otomatis menampilkan halaman **Dashboard**.

---

## Menjalankan di VS Code + XAMPP

1. Buka VS Code, pilih **File > Open Folder**, arahkan ke `C:\xampp\htdocs\tugas_RPL`
2. Edit file sesuai kebutuhan
3. Pastikan Apache dan MySQL di XAMPP sudah aktif
4. Buka browser dan akses `http://localhost/tugas_RPL`
5. Setiap kali menyimpan file di VS Code, refresh browser untuk melihat perubahan

> **Tips:** Install ekstensi **PHP Intelephense** di VS Code untuk IntelliSense PHP yang lebih baik.

---

## Coding Standard yang Diterapkan

### Keamanan

- **Prepared Statements** — semua query database menggunakan PDO prepared statements untuk mencegah SQL Injection
- **`htmlspecialchars()`** — semua output user-input di-escape sebelum ditampilkan di HTML
- **`filter_input()`** — validasi dan sanitasi input dari GET/POST
- **Open Redirect Protection** — URL redirect divalidasi agar hanya menuju domain yang sama

### Struktur Kode

- **Pemisahan Concern** — config, fungsi, tampilan, dan proses CRUD dipisahkan ke file masing-masing
- **Naming Convention** — `snake_case` untuk variabel/fungsi PHP, `camelCase` untuk JavaScript
- **Docblock Komentar** — setiap fungsi dilengkapi PHPDoc (`@param`, `@return`)
- **Type Hints** — deklarasi tipe parameter dan return value pada semua fungsi
- **Validasi Dua Lapis** — client-side (JavaScript) dan server-side (PHP)

### Tampilan

- **CSS Custom Properties** — design token untuk warna, ukuran, dan shadow
- **Responsif** — layout menyesuaikan layar mobile, tablet, dan desktop
- **Aksesibilitas** — atribut `aria-*`, `role`, dan `aria-label` pada elemen interaktif

---

## Version Control (Git)

Repository project ini sudah diinisialisasi, commit pertama sudah dibuat, dan kode sudah di-push ke GitHub pada branch `main`.

**Repository:** [Repository GitHub](https://github.com/sayyid-habibie/tugas_RPL)

> **Catatan keamanan:** File `config/database.php` sudah dikecualikan melalui `.gitignore`
> karena berisi konfigurasi lokal (host, user, password). File ini tidak ikut ter-push
> ke repository publik. Siapa pun yang meng-clone repo ini perlu membuat file tersebut
> secara manual sesuai lingkungan lokal masing-masing (lihat Langkah 4 di atas).

### Melihat Status dan Riwayat Commit

```bash
# Lihat status file yang berubah
git status

# Lihat riwayat commit
git log --oneline

# Lihat detail perubahan yang belum di-stage
git diff
```

### Membuat Commit Setelah Ada Perubahan

Setelah melakukan perubahan pada file, stage file yang relevan lalu buat commit dengan pesan yang jelas:

```bash
# Stage file tertentu (disarankan, hindari git add -A)
git add nama-file.php

# Atau stage seluruh perubahan pada folder tertentu
git add pages/

# Buat commit
git commit -m "tipe: deskripsi singkat perubahan yang dilakukan"
```

Contoh format pesan commit yang baik:

| Tipe | Contoh pesan |
|---|---|
| `feat` | `feat: tambah fitur ekspor data ke CSV` |
| `fix` | `fix: perbaiki validasi stok saat edit barang` |
| `style` | `style: perbaiki tampilan tabel pada layar kecil` |
| `docs` | `docs: perbarui README dengan instruksi instalasi` |
| `refactor` | `refactor: pisahkan logika validasi ke fungsi tersendiri` |

### Mengirim Perubahan ke GitHub

```bash
# Push commit terbaru ke branch main
git push origin main
```

Jika ingin mengerjakan fitur baru tanpa mengganggu branch `main`:

```bash
# Buat dan pindah ke branch baru
git checkout -b nama-fitur

# Setelah selesai, push branch tersebut
git push origin nama-fitur
```

---

## Preview Halaman

| Halaman | URL |
|---|---|
| Dashboard | `http://localhost/tugas_RPL` |
| Daftar Barang | `http://localhost/tugas_RPL/pages/barang.php` |
| Tambah Barang | `http://localhost/tugas_RPL/pages/tambah.php` |
| Edit Barang | `http://localhost/tugas_RPL/pages/edit.php?id=1` |
| Stok Menipis | `http://localhost/tugas_RPL/pages/barang.php?filter=menipis` |

---

## Informasi

- **Mata Kuliah:** Implementasi Coding Standard & Version Control
- **Teknologi:** PHP Native, MySQL, HTML5, CSS3, JavaScript (Vanilla)
- **Server:** XAMPP (Apache + MySQL)
- **Database Client:** phpMyAdmin
