<?php

/**
 * Halaman Dashboard
 * 
 * Menampilkan statistik ringkasan inventori:
 * total jenis barang, total stok, stok menipis,
 * dan tabel 5 barang terbaru.
 */

require_once __DIR__ . '/../includes/functions.php';

// Ambil data statistik dashboard
$stats = getDashboardStats();

// Variabel untuk layout header
$pageTitle   = 'Dashboard';
$topbarTitle = 'Dashboard';
$activeNav   = 'dashboard';

require_once __DIR__ . '/../includes/header.php';
?>

<!-- ============================================================
     STAT CARDS
     ============================================================ -->
<div class="stats-grid">

    <!-- Total Jenis Barang -->
    <div class="stat-card">
        <div class="stat-icon bg-primary" aria-hidden="true">📦</div>
        <div class="stat-info">
            <div class="stat-value"><?= number_format($stats['total_barang']) ?></div>
            <div class="stat-label">Total Jenis Barang</div>
        </div>
    </div>

    <!-- Total Stok -->
    <div class="stat-card">
        <div class="stat-icon bg-success" aria-hidden="true">📊</div>
        <div class="stat-info">
            <div class="stat-value"><?= number_format($stats['total_stok']) ?></div>
            <div class="stat-label">Total Stok Keseluruhan</div>
        </div>
    </div>

    <!-- Stok Menipis -->
    <div class="stat-card">
        <div class="stat-icon bg-warning" aria-hidden="true">⚠️</div>
        <div class="stat-info">
            <div class="stat-value <?= $stats['stok_menipis'] > 0 ? 'text-warning' : '' ?>">
                <?= number_format($stats['stok_menipis']) ?>
            </div>
            <div class="stat-label">Barang Stok Menipis (≤ <?= STOK_MINIMUM ?>)</div>
        </div>
    </div>

    <!-- Shortcut Tambah Barang -->
    <div class="stat-card" style="cursor: pointer;"
         onclick="window.location.href='/tugas_RPL/pages/tambah.php'">
        <div class="stat-icon bg-primary" aria-hidden="true">➕</div>
        <div class="stat-info">
            <div class="stat-value" style="font-size: 1rem; color: var(--color-primary);">
                Tambah Barang
            </div>
            <div class="stat-label">Klik untuk menambah data baru</div>
        </div>
    </div>

</div>

<!-- ============================================================
     TABEL BARANG TERBARU
     ============================================================ -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <span aria-hidden="true">🕐</span> Barang Terbaru Ditambahkan
        </h2>
        <a href="/tugas_RPL/pages/barang.php" class="btn btn-outline btn-sm">
            Lihat Semua →
        </a>
    </div>

    <?php if (empty($stats['barang_terbaru'])): ?>
        <div class="empty-state">
            <div class="empty-state-icon" aria-hidden="true">📭</div>
            <h3>Belum ada data barang</h3>
            <p>Mulai dengan menambahkan barang pertama Anda.</p>
            <a href="/tugas_RPL/pages/tambah.php"
               class="btn btn-primary"
               style="margin-top: 16px;">
                ➕ Tambah Barang
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table" aria-label="Daftar 5 barang terbaru">
                <thead>
                    <tr>
                        <th scope="col">Kode</th>
                        <th scope="col">Nama Barang</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Stok</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Tgl Masuk</th>
                        <th scope="col" class="col-action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats['barang_terbaru'] as $barang): ?>
                        <?php
                        // Tentukan kelas CSS berdasarkan level stok
                        $stok     = (int) $barang['stok'];
                        $stokClass = 'stok-aman';
                        $stokBadge = 'badge-success';
                        if ($stok === 0) {
                            $stokClass = 'stok-habis';
                            $stokBadge = 'badge-danger';
                        } elseif ($stok <= STOK_MINIMUM) {
                            $stokClass = 'stok-menipis';
                            $stokBadge = 'badge-warning';
                        }
                        ?>
                        <tr>
                            <td>
                                <span class="kode-barang">
                                    <?= htmlspecialchars($barang['kode_barang'], ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </td>
                            <td class="text-bold">
                                <?= htmlspecialchars($barang['nama_barang'], ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td>
                                <span class="badge badge-gray">
                                    <?= htmlspecialchars($barang['kategori'], ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?= $stokBadge ?>">
                                    <span class="stok-value">
                                        <?= htmlspecialchars((string) $stok, ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                    <?= htmlspecialchars($barang['satuan'], ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </td>
                            <td class="text-bold">
                                <?= formatRupiah((float) $barang['harga']) ?>
                            </td>
                            <td class="text-muted">
                                <?= formatTanggal($barang['tanggal_masuk']) ?>
                            </td>
                            <td class="col-action">
                                <div class="d-flex align-center gap-8" style="justify-content:center">
                                    <a href="/tugas_RPL/pages/edit.php?id=<?= (int) $barang['id'] ?>"
                                       class="btn btn-warning btn-sm btn-icon"
                                       aria-label="Edit <?= htmlspecialchars($barang['nama_barang'], ENT_QUOTES, 'UTF-8') ?>"
                                       title="Edit">
                                        ✏️
                                    </a>

                                    <!-- Form hapus tersembunyi -->
                                    <form id="form-hapus-<?= (int) $barang['id'] ?>"
                                          action="/tugas_RPL/process/barang_process.php"
                                          method="POST"
                                          style="display:none;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id"
                                               value="<?= (int) $barang['id'] ?>">
                                    </form>

                                    <button type="button"
                                            class="btn btn-danger btn-sm btn-icon"
                                            onclick="confirmDelete(
                                                '<?= htmlspecialchars(addslashes($barang['nama_barang']), ENT_QUOTES, 'UTF-8') ?>',
                                                'form-hapus-<?= (int) $barang['id'] ?>'
                                            )"
                                            aria-label="Hapus <?= htmlspecialchars($barang['nama_barang'], ENT_QUOTES, 'UTF-8') ?>"
                                            title="Hapus">
                                        🗑️
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- ============================================================
     SECTION STOK MENIPIS (tampilkan hanya jika ada)
     ============================================================ -->
<?php if ($stats['stok_menipis'] > 0): ?>
<div class="card" style="margin-top: 24px; border-left: 4px solid var(--color-warning);">
    <div class="card-header">
        <h2 class="card-title text-warning">
            <span aria-hidden="true">⚠️</span>
            Peringatan Stok Menipis
        </h2>
        <a href="/tugas_RPL/pages/barang.php?filter=menipis" class="btn btn-warning btn-sm">
            Lihat Detail
        </a>
    </div>
    <div class="card-body" style="padding-top: 12px; padding-bottom: 14px;">
        <p class="text-muted">
            Terdapat <strong class="text-warning"><?= $stats['stok_menipis'] ?> barang</strong>
            dengan stok di bawah atau sama dengan
            <strong><?= STOK_MINIMUM ?></strong> unit.
            Segera lakukan pengadaan untuk menghindari kekosongan stok.
        </p>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
