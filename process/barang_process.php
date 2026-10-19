<?php

/**
 * Handler Proses CRUD Barang
 *
 * Menerima POST request dari form tambah, edit, dan hapus barang.
 * Semua operasi database menggunakan prepared statements (via functions.php).
 * Setelah proses selesai, redirect ke halaman yang sesuai dengan flash message.
 *
 * Aksi yang didukung (field POST 'action'):
 *   - add    : Tambah barang baru
 *   - edit   : Perbarui data barang
 *   - delete : Hapus barang
 *
 * @author  Mahasiswa RPL
 * @version 1.0
 */

// Hanya izinkan akses via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit('Metode tidak diizinkan.');
}

require_once __DIR__ . '/../includes/functions.php';

// Ambil aksi dari POST, sanitasi untuk keamanan
$action = sanitizeInput($_POST['action'] ?? '');

switch ($action) {

    // ================================================================
    // TAMBAH BARANG
    // ================================================================
    case 'add':
        handleAdd();
        break;

    // ================================================================
    // EDIT BARANG
    // ================================================================
    case 'edit':
        handleEdit();
        break;

    // ================================================================
    // HAPUS BARANG
    // ================================================================
    case 'delete':
        handleDelete();
        break;

    default:
        setFlashMessage('error', 'Aksi tidak dikenali.');
        redirect('/tugas_RPL/pages/barang.php');
}

// ====================================================================
// FUNGSI HANDLER
// ====================================================================

/**
 * Menangani proses penambahan barang baru.
 *
 * @return void
 */
function handleAdd(): void
{
    // Kumpulkan dan bersihkan input
    $data = collectFormData();

    // Validasi server-side
    $errors = validateBarangInput($data);

    if (!empty($errors)) {
        // Gabungkan semua pesan error menjadi satu string
        setFlashMessage('error', implode(' ', $errors));
        redirect('/tugas_RPL/pages/tambah.php');
    }

    // Periksa duplikasi kode barang
    if (isKodeBarangExists($data['kode_barang'])) {
        setFlashMessage('error', "Kode barang '{$data['kode_barang']}' sudah digunakan. Gunakan kode lain.");
        redirect('/tugas_RPL/pages/tambah.php');
    }

    // Simpan ke database
    $success = addBarang($data);

    if ($success) {
        setFlashMessage('success', "Barang '{$data['nama_barang']}' berhasil ditambahkan.");
        redirect('/tugas_RPL/pages/barang.php');
    } else {
        setFlashMessage('error', 'Gagal menyimpan barang. Silakan coba lagi.');
        redirect('/tugas_RPL/pages/tambah.php');
    }
}

/**
 * Menangani proses pembaruan data barang.
 *
 * @return void
 */
function handleEdit(): void
{
    // Validasi ID
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if (!$id || $id <= 0) {
        setFlashMessage('error', 'ID barang tidak valid.');
        redirect('/tugas_RPL/pages/barang.php');
    }

    // Pastikan barang masih ada di database
    $barangLama = getBarangById($id);
    if ($barangLama === null) {
        setFlashMessage('error', 'Barang tidak ditemukan atau sudah dihapus.');
        redirect('/tugas_RPL/pages/barang.php');
    }

    // Kumpulkan dan bersihkan input
    $data = collectFormData();

    // Validasi server-side
    $errors = validateBarangInput($data);

    if (!empty($errors)) {
        setFlashMessage('error', implode(' ', $errors));
        redirect('/tugas_RPL/pages/edit.php?id=' . $id);
    }

    // Periksa duplikasi kode (kecualikan ID yang sedang diedit)
    if (isKodeBarangExists($data['kode_barang'], $id)) {
        setFlashMessage('error', "Kode barang '{$data['kode_barang']}' sudah digunakan barang lain.");
        redirect('/tugas_RPL/pages/edit.php?id=' . $id);
    }

    // Simpan perubahan ke database
    $success = updateBarang($id, $data);

    if ($success) {
        setFlashMessage('success', "Barang '{$data['nama_barang']}' berhasil diperbarui.");
        redirect('/tugas_RPL/pages/barang.php');
    } else {
        setFlashMessage('error', 'Gagal memperbarui barang. Silakan coba lagi.');
        redirect('/tugas_RPL/pages/edit.php?id=' . $id);
    }
}

/**
 * Menangani proses penghapusan barang.
 *
 * @return void
 */
function handleDelete(): void
{
    // Validasi ID
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if (!$id || $id <= 0) {
        setFlashMessage('error', 'ID barang tidak valid.');
        redirect('/tugas_RPL/pages/barang.php');
    }

    // Pastikan barang masih ada, simpan nama untuk pesan konfirmasi
    $barang = getBarangById($id);
    if ($barang === null) {
        setFlashMessage('error', 'Barang tidak ditemukan atau sudah dihapus.');
        redirect('/tugas_RPL/pages/barang.php');
    }

    $namaBarang = $barang['nama_barang'];

    // Hapus dari database
    $success = deleteBarang($id);

    if ($success) {
        setFlashMessage('success', "Barang '{$namaBarang}' berhasil dihapus.");
    } else {
        setFlashMessage('error', "Gagal menghapus barang '{$namaBarang}'. Silakan coba lagi.");
    }

    // Redirect ke halaman asal (referrer) atau daftar barang
    $redirectUrl = filter_input(INPUT_POST, 'redirect', FILTER_SANITIZE_URL)
        ?: '/tugas_RPL/pages/barang.php';

    // Pastikan redirect hanya ke domain yang sama (mencegah open redirect)
    $parsedUrl = parse_url($redirectUrl);
    if (isset($parsedUrl['host']) && $parsedUrl['host'] !== $_SERVER['HTTP_HOST']) {
        $redirectUrl = '/tugas_RPL/pages/barang.php';
    }

    redirect($redirectUrl);
}

/**
 * Mengumpulkan dan membersihkan data dari form POST.
 *
 * @return array Associative array field barang yang sudah di-sanitasi
 */
function collectFormData(): array
{
    return [
        'kode_barang'   => sanitizeInput($_POST['kode_barang']   ?? ''),
        'nama_barang'   => sanitizeInput($_POST['nama_barang']   ?? ''),
        'kategori'      => sanitizeInput($_POST['kategori']      ?? ''),
        'stok'          => trim($_POST['stok']   ?? ''),
        'satuan'        => sanitizeInput($_POST['satuan']        ?? ''),
        'harga'         => trim($_POST['harga']  ?? ''),
        'tanggal_masuk' => sanitizeInput($_POST['tanggal_masuk'] ?? ''),
        'keterangan'    => sanitizeInput($_POST['keterangan']    ?? ''),
    ];
}
