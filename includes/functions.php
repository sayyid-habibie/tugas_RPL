<?php

/**
 * Fungsi-fungsi Utama Sistem Inventori Barang
 * 
 * Berisi semua fungsi CRUD dan utilitas yang digunakan
 * di seluruh aplikasi. Semua query menggunakan prepared statements
 * untuk mencegah SQL Injection.
 * 
 * @author  Mahasiswa RPL
 * @version 1.0
 */

require_once __DIR__ . '/../config/database.php';

// ---------------------------------------------------------------------------
// Konstanta Aplikasi
// ---------------------------------------------------------------------------

/** Batas stok yang dianggap "menipis" */
define('STOK_MINIMUM', 10);

/** Jumlah baris per halaman pada tabel daftar barang */
define('ROWS_PER_PAGE', 10);

// ---------------------------------------------------------------------------
// Fungsi Utilitas
// ---------------------------------------------------------------------------

/**
 * Membersihkan dan mengamankan string input dari pengguna.
 *
 * @param  string $input String mentah dari pengguna
 * @return string        String yang sudah di-trim dan di-escape
 */
function sanitizeInput(string $input): string
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Memformat angka menjadi format mata uang Rupiah.
 *
 * @param  float  $amount Nominal angka
 * @return string         String format Rupiah, contoh: Rp 10.000
 */
function formatRupiah(float $amount): string
{
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

/**
 * Memformat tanggal dari format database (Y-m-d) ke format tampilan (d/m/Y).
 *
 * @param  string $date Tanggal format Y-m-d
 * @return string       Tanggal format d/m/Y
 */
function formatTanggal(string $date): string
{
    if (empty($date)) {
        return '-';
    }
    $timestamp = strtotime($date);
    return date('d/m/Y', $timestamp);
}

/**
 * Mengalihkan pengguna ke URL lain dan menghentikan eksekusi.
 *
 * @param  string $url URL tujuan redirect
 * @return void
 */
function redirect(string $url): void
{
    header('Location: ' . $url);
    exit();
}

/**
 * Menyimpan pesan flash ke session untuk ditampilkan sekali.
 *
 * @param  string $type    Tipe pesan: 'success' | 'error' | 'warning'
 * @param  string $message Isi pesan
 * @return void
 */
function setFlashMessage(string $type, string $message): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['flash'] = [
        'type'    => $type,
        'message' => $message,
    ];
}

/**
 * Mengambil dan menghapus pesan flash dari session.
 *
 * @return array|null Array ['type', 'message'] atau null jika tidak ada
 */
function getFlashMessage(): ?array
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// ---------------------------------------------------------------------------
// Fungsi Dashboard
// ---------------------------------------------------------------------------

/**
 * Mengambil statistik ringkasan untuk halaman dashboard.
 *
 * @return array Associative array berisi:
 *               - total_barang   : jumlah jenis barang
 *               - total_stok     : total stok keseluruhan
 *               - stok_menipis   : jumlah barang dengan stok <= STOK_MINIMUM
 *               - barang_terbaru : 5 barang yang paling baru ditambahkan
 */
function getDashboardStats(): array
{
    $pdo = getConnection();

    // Total jenis barang
    $stmtJenis = $pdo->query('SELECT COUNT(*) AS total FROM barang');
    $totalBarang = (int) $stmtJenis->fetchColumn();

    // Total stok keseluruhan
    $stmtStok = $pdo->query('SELECT COALESCE(SUM(stok), 0) AS total FROM barang');
    $totalStok = (int) $stmtStok->fetchColumn();

    // Barang dengan stok menipis
    $stmtMenipis = $pdo->prepare(
        'SELECT COUNT(*) AS total FROM barang WHERE stok <= :minimum'
    );
    $stmtMenipis->execute([':minimum' => STOK_MINIMUM]);
    $stokMenipis = (int) $stmtMenipis->fetchColumn();

    // 5 barang terbaru
    $stmtTerbaru = $pdo->query(
        'SELECT * FROM barang ORDER BY created_at DESC LIMIT 5'
    );
    $barangTerbaru = $stmtTerbaru->fetchAll();

    return [
        'total_barang'   => $totalBarang,
        'total_stok'     => $totalStok,
        'stok_menipis'   => $stokMenipis,
        'barang_terbaru' => $barangTerbaru,
    ];
}

// ---------------------------------------------------------------------------
// Fungsi CRUD Barang
// ---------------------------------------------------------------------------

/**
 * Mengambil semua data barang, dengan opsi pencarian dan pagination.
 *
 * @param  string $keyword  Kata kunci pencarian (kode, nama, atau kategori)
 * @param  int    $offset   Offset baris untuk pagination
 * @param  int    $limit    Jumlah baris yang diambil
 * @return array            Array berisi 'data' (rows) dan 'total' (total rows)
 */
function getAllBarang(string $keyword = '', int $offset = 0, int $limit = ROWS_PER_PAGE): array
{
    $pdo = getConnection();

    $searchTerm = '%' . $keyword . '%';
    $whereClause = 'WHERE kode_barang LIKE :kw1
                       OR nama_barang  LIKE :kw2
                       OR kategori     LIKE :kw3';

    // Hitung total baris (untuk pagination)
    $stmtCount = $pdo->prepare(
        "SELECT COUNT(*) FROM barang $whereClause"
    );
    $stmtCount->execute([
        ':kw1' => $searchTerm,
        ':kw2' => $searchTerm,
        ':kw3' => $searchTerm,
    ]);
    $total = (int) $stmtCount->fetchColumn();

    // Ambil data dengan limit dan offset
    $stmtData = $pdo->prepare(
        "SELECT * FROM barang $whereClause
         ORDER BY created_at DESC
         LIMIT :limit OFFSET :offset"
    );
    $stmtData->bindValue(':kw1', $searchTerm, PDO::PARAM_STR);
    $stmtData->bindValue(':kw2', $searchTerm, PDO::PARAM_STR);
    $stmtData->bindValue(':kw3', $searchTerm, PDO::PARAM_STR);
    $stmtData->bindValue(':limit',  $limit,  PDO::PARAM_INT);
    $stmtData->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmtData->execute();
    $data = $stmtData->fetchAll();

    return [
        'data'  => $data,
        'total' => $total,
    ];
}

/**
 * Mengambil satu data barang berdasarkan ID.
 *
 * @param  int        $id ID barang
 * @return array|null     Row barang atau null jika tidak ditemukan
 */
function getBarangById(int $id): ?array
{
    $pdo  = getConnection();
    $stmt = $pdo->prepare('SELECT * FROM barang WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

/**
 * Memeriksa apakah kode barang sudah digunakan (untuk validasi unik).
 *
 * @param  string   $kodeBarang  Kode yang akan diperiksa
 * @param  int|null $excludeId   ID barang yang dikecualikan (saat edit)
 * @return bool                  true jika kode sudah ada
 */
function isKodeBarangExists(string $kodeBarang, ?int $excludeId = null): bool
{
    $pdo = getConnection();

    if ($excludeId !== null) {
        $stmt = $pdo->prepare(
            'SELECT COUNT(*) FROM barang WHERE kode_barang = :kode AND id != :id'
        );
        $stmt->execute([':kode' => $kodeBarang, ':id' => $excludeId]);
    } else {
        $stmt = $pdo->prepare(
            'SELECT COUNT(*) FROM barang WHERE kode_barang = :kode'
        );
        $stmt->execute([':kode' => $kodeBarang]);
    }

    return (int) $stmt->fetchColumn() > 0;
}

/**
 * Menyimpan data barang baru ke database.
 *
 * @param  array $data Associative array berisi field barang
 * @return bool        true jika berhasil
 */
function addBarang(array $data): bool
{
    $pdo  = getConnection();
    $stmt = $pdo->prepare(
        'INSERT INTO barang
            (kode_barang, nama_barang, kategori, stok, satuan, harga, tanggal_masuk, keterangan)
         VALUES
            (:kode_barang, :nama_barang, :kategori, :stok, :satuan, :harga, :tanggal_masuk, :keterangan)'
    );

    return $stmt->execute([
        ':kode_barang'   => $data['kode_barang'],
        ':nama_barang'   => $data['nama_barang'],
        ':kategori'      => $data['kategori'],
        ':stok'          => (int) $data['stok'],
        ':satuan'        => $data['satuan'],
        ':harga'         => (float) $data['harga'],
        ':tanggal_masuk' => $data['tanggal_masuk'],
        ':keterangan'    => $data['keterangan'] ?? '',
    ]);
}

/**
 * Memperbarui data barang yang sudah ada.
 *
 * @param  int   $id   ID barang yang akan diperbarui
 * @param  array $data Associative array berisi field yang diperbarui
 * @return bool        true jika berhasil
 */
function updateBarang(int $id, array $data): bool
{
    $pdo  = getConnection();
    $stmt = $pdo->prepare(
        'UPDATE barang SET
            kode_barang   = :kode_barang,
            nama_barang   = :nama_barang,
            kategori      = :kategori,
            stok          = :stok,
            satuan        = :satuan,
            harga         = :harga,
            tanggal_masuk = :tanggal_masuk,
            keterangan    = :keterangan,
            updated_at    = NOW()
         WHERE id = :id'
    );

    return $stmt->execute([
        ':kode_barang'   => $data['kode_barang'],
        ':nama_barang'   => $data['nama_barang'],
        ':kategori'      => $data['kategori'],
        ':stok'          => (int) $data['stok'],
        ':satuan'        => $data['satuan'],
        ':harga'         => (float) $data['harga'],
        ':tanggal_masuk' => $data['tanggal_masuk'],
        ':keterangan'    => $data['keterangan'] ?? '',
        ':id'            => $id,
    ]);
}

/**
 * Menghapus satu data barang berdasarkan ID.
 *
 * @param  int  $id ID barang yang akan dihapus
 * @return bool     true jika berhasil
 */
function deleteBarang(int $id): bool
{
    $pdo  = getConnection();
    $stmt = $pdo->prepare('DELETE FROM barang WHERE id = :id');
    return $stmt->execute([':id' => $id]);
}

/**
 * Memvalidasi data barang dari form input.
 *
 * @param  array $data Data POST dari form
 * @return array       Array of error messages (kosong jika valid)
 */
function validateBarangInput(array $data): array
{
    $errors = [];

    if (empty(trim($data['kode_barang'] ?? ''))) {
        $errors[] = 'Kode barang tidak boleh kosong.';
    } elseif (!preg_match('/^[A-Za-z0-9\-_]+$/', $data['kode_barang'])) {
        $errors[] = 'Kode barang hanya boleh berisi huruf, angka, tanda hubung, dan underscore.';
    }

    if (empty(trim($data['nama_barang'] ?? ''))) {
        $errors[] = 'Nama barang tidak boleh kosong.';
    }

    if (empty(trim($data['kategori'] ?? ''))) {
        $errors[] = 'Kategori tidak boleh kosong.';
    }

    $stokTrimmed = trim((string) ($data['stok'] ?? ''));
    if ($stokTrimmed === '') {
        $errors[] = 'Jumlah stok tidak boleh kosong.';
    } elseif (!ctype_digit($stokTrimmed)) {
        // ctype_digit() hanya loloskan digit 0-9 tanpa tanda, titik, atau eksponen.
        // Menolak: "2.5", "-1", "2e0", "abc", " 5", dan string lain yang bukan
        // barisan digit murni. Nilai "0" dan "100" lolos.
        $errors[] = 'Jumlah stok harus berupa bilangan bulat tidak negatif (contoh: 0, 1, 50).';
    }

    if (empty(trim($data['satuan'] ?? ''))) {
        $errors[] = 'Satuan tidak boleh kosong.';
    }

    if (!isset($data['harga']) || $data['harga'] === '') {
        $errors[] = 'Harga tidak boleh kosong.';
    } elseif (!is_numeric($data['harga']) || (float) $data['harga'] < 0) {
        $errors[] = 'Harga harus berupa angka positif.';
    }

    if (empty(trim($data['tanggal_masuk'] ?? ''))) {
        $errors[] = 'Tanggal masuk tidak boleh kosong.';
    } elseif (!strtotime($data['tanggal_masuk'])) {
        $errors[] = 'Format tanggal masuk tidak valid.';
    }

    return $errors;
}
