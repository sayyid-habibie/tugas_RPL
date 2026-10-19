<?php

/**
 * Layout Header — Sidebar & Topbar
 * 
 * Di-include di setiap halaman. Menerima variabel:
 *   $pageTitle    (string) — judul tab browser
 *   $topbarTitle  (string) — judul di topbar
 *   $activeNav    (string) — slug halaman aktif untuk highlight nav
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nilai default agar tidak error jika variabel belum di-set
$pageTitle   = $pageTitle   ?? 'Sistem Inventori';
$topbarTitle = $topbarTitle ?? 'Dashboard';
$activeNav   = $activeNav   ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> — Inventori Barang</title>
    <meta name="description" content="Sistem Manajemen Inventori Barang berbasis PHP Native dan MySQL">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Stylesheet utama -->
    <link rel="stylesheet" href="/tugas_RPL/assets/css/style.css">
</head>
<body>

<!-- ============================================================
     SIDEBAR OVERLAY (mobile)
     ============================================================ -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ============================================================
     SIDEBAR
     ============================================================ -->
<aside class="sidebar" id="sidebar" role="navigation" aria-label="Navigasi utama">

    <!-- Brand -->
    <a href="/tugas_RPL/index.php" class="sidebar-brand">
        <div class="sidebar-brand-icon" aria-hidden="true">📦</div>
        <div class="sidebar-brand-text">
            <span class="sidebar-brand-title">Inventori Barang</span>
            <span class="sidebar-brand-subtitle">Sistem Manajemen Stok</span>
        </div>
    </a>

    <!-- Navigasi -->
    <nav class="sidebar-nav">
        <p class="sidebar-label">Menu Utama</p>

        <a href="/tugas_RPL/index.php"
           class="<?= $activeNav === 'dashboard' ? 'active' : '' ?>"
           aria-current="<?= $activeNav === 'dashboard' ? 'page' : 'false' ?>">
            <span class="nav-icon" aria-hidden="true">🏠</span>
            Dashboard
        </a>

        <a href="/tugas_RPL/pages/barang.php"
           class="<?= $activeNav === 'barang' ? 'active' : '' ?>"
           aria-current="<?= $activeNav === 'barang' ? 'page' : 'false' ?>">
            <span class="nav-icon" aria-hidden="true">📋</span>
            Daftar Barang
        </a>

        <a href="/tugas_RPL/pages/tambah.php"
           class="<?= $activeNav === 'tambah' ? 'active' : '' ?>"
           aria-current="<?= $activeNav === 'tambah' ? 'page' : 'false' ?>">
            <span class="nav-icon" aria-hidden="true">➕</span>
            Tambah Barang
        </a>

        <p class="sidebar-label" style="margin-top: 12px;">Informasi</p>

        <a href="/tugas_RPL/pages/barang.php?filter=menipis"
           class="<?= $activeNav === 'menipis' ? 'active' : '' ?>">
            <span class="nav-icon" aria-hidden="true">⚠️</span>
            Stok Menipis
        </a>
    </nav>

    <!-- Footer sidebar -->
    <div class="sidebar-footer">
        <p>Tugas RPL &copy; <?= date('Y') ?></p>
        <p style="margin-top:2px;">PHP Native &amp; MySQL</p>
    </div>
</aside>

<!-- ============================================================
     MAIN WRAPPER
     ============================================================ -->
<div class="main-wrapper">

    <!-- TOPBAR -->
    <header class="topbar" role="banner">
        <div class="topbar-left">
            <!-- Tombol toggle sidebar (mobile) -->
            <button class="btn-toggle-sidebar"
                    id="btnToggleSidebar"
                    aria-label="Buka atau tutup menu navigasi"
                    aria-expanded="false"
                    aria-controls="sidebar">
                ☰
            </button>
            <div>
                <h1 class="topbar-title"><?= htmlspecialchars($topbarTitle, ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="topbar-subtitle"><?= date('l, d F Y') ?></p>
            </div>
        </div>
        <div class="topbar-right">
            <a href="/tugas_RPL/pages/tambah.php" class="btn btn-primary btn-sm">
                <span aria-hidden="true">＋</span> Tambah Barang
            </a>
        </div>
    </header>

    <!-- MAIN CONTENT dimulai di sini -->
    <main class="main-content" id="mainContent" role="main">

<?php
// Tampilkan flash message jika ada
$flash = getFlashMessage();
if ($flash):
    $iconMap = [
        'success' => '✅',
        'error'   => '❌',
        'warning' => '⚠️',
    ];
    $icon = $iconMap[$flash['type']] ?? 'ℹ️';
?>
<div class="alert alert-<?= htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8') ?>"
     role="alert"
     aria-live="polite">
    <span class="alert-icon" aria-hidden="true"><?= $icon ?></span>
    <div class="alert-body">
        <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <button class="alert-close" aria-label="Tutup notifikasi" type="button">✕</button>
</div>
<?php endif; ?>
