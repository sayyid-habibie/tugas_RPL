<?php

/**
 * Halaman Daftar Barang
 * 
 * Menampilkan semua data barang dalam tabel dengan fitur:
 * - Pencarian berdasarkan kode, nama, atau kategori
 * - Filter stok menipis
 * - Pagination
 * - Aksi edit dan hapus per baris
 */

require_once __DIR__ . '/../includes/functions.php';

// ------------------------------------------------------------
// Ambil parameter dari URL
// ------------------------------------------------------------
$keyword    = sanitizeInput($_GET['q']      ?? '');
$filterMenipis = isset($_GET['filter']) && $_GET['filter'] === 'menipis';
$currentPage   = max(1, (int) ($_GET['page'] ?? 1));
$offset        = ($currentPage - 1) * ROWS_PER_PAGE;

// Jika filter stok menipis, ubah query pencarian
// (implementasi via getAllBarang dengan kondisi tambahan)
// Karena fungsi getAllBarang sudah support keyword, kita
// tambahkan logika filter di sisi PHP setelah fetch.
$result = getAllBarang($keyword, $offset, ROWS_PER_PAGE);
$barangList = $result['data'];
$totalRows  = $result['total'];
$totalPages = (int) ceil($totalRows / ROWS_PER_PAGE);

// Terapkan filter stok menipis di sisi PHP
// (hanya berlaku pada dataset yang sudah di-fetch untuk halaman ini)
// Untuk keperluan tugas ini, kita fetch semua dulu saat filter aktif
if ($filterMenipis) {
    $allResult     = getAllBarang($keyword, 0, 99999);
    $barangList    = array_filter(
        $allResult['data'],
        fn($b) => (int) $b['stok'] <= STOK_MINIMUM
    );
    $barangList    = array_slice(array_values($barangList), $offset, ROWS_PER_PAGE);
    $filteredTotal = count(array_filter(
        $allResult['data'],
        fn($b) => (int) $b['stok'] <= STOK_MINIMUM
    ));
    $totalPages    = (int) ceil($filteredTotal / ROWS_PER_PAGE);
    $totalRows     = $filteredTotal;
}

// Variabel untuk layout
$pageTitle   = 'Daftar Barang';
$topbarTitle = $filterMenipis ? 'Stok Menipis' : 'Daftar Barang';
$activeNav   = $filterMenipis ? 'menipis' : 'barang';

require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">

    <!-- --------------------------------------------------------
         TOOLBAR: pencarian + tombol tambah
         -------------------------------------------------------- -->
    <div class="table-toolbar">
        <!-- Form Pencarian -->
        <form id="searchForm"
              method="GET"
              action="/tugas_RPL/pages/barang.php"
              role="search"
              style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">

            <?php if ($filterMenipis): ?>
                <input type="hidden" name="filter" value="menipis">
            <?php endif; ?>

            <div class="search-box">
                <span class="search-icon" aria-hidden="true">🔍</span>
                <input type="search"
                       id="searchInput"
                       name="q"
                       value="<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>"
                       placeholder="Cari kode, nama, atau kategori…"
                       aria-label="Cari barang"
                       autocomplete="off">
                <button type="button"
                        id="btnClearSearch"
                        class="btn-clear-search"
                        aria-label="Hapus pencarian">
                    ✕
                </button>
            </div>

            <button type="submit" class="btn btn-primary btn-sm">Cari</button>

            <?php if (!empty($keyword) || $filterMenipis): ?>
                <a href="/tugas_RPL/pages/barang.php" class="btn btn-outline btn-sm">
                    Reset
                </a>
            <?php endif; ?>
        </form>

        <div class="d-flex align-center gap-8">
            <?php if ($filterMenipis): ?>
                <span class="badge badge-warning">⚠️ Filter: Stok Menipis</span>
            <?php endif; ?>
            <a href="/tugas_RPL/pages/tambah.php" class="btn btn-success btn-sm">
                ➕ Tambah Barang
            </a>
        </div>
    </div>

    <!-- --------------------------------------------------------
         TABEL DATA BARANG
         -------------------------------------------------------- -->
    <?php if (empty($barangList)): ?>
        <div class="empty-state">
            <div class="empty-state-icon" aria-hidden="true">
                <?= empty($keyword) && !$filterMenipis ? '📭' : '🔍' ?>
            </div>
            <h3>
                <?php if (!empty($keyword)): ?>
                    Tidak ada barang dengan kata kunci "<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>"
                <?php elseif ($filterMenipis): ?>
                    Tidak ada barang dengan stok menipis
                <?php else: ?>
                    Belum ada data barang
                <?php endif; ?>
            </h3>
            <p class="text-muted">
                <?= !empty($keyword) || $filterMenipis
                    ? 'Coba ubah kata kunci pencarian atau hapus filter.'
                    : 'Mulai dengan menambahkan barang pertama Anda.' ?>
            </p>
            <?php if (empty($keyword) && !$filterMenipis): ?>
                <a href="/tugas_RPL/pages/tambah.php"
                   class="btn btn-primary"
                   style="margin-top:16px;">
                    ➕ Tambah Barang
                </a>
            <?php endif; ?>
        </div>

    <?php else: ?>
        <div class="table-responsive">
            <table class="table" aria-label="Daftar barang">
                <thead>
                    <tr>
                        <th scope="col" class="col-no">#</th>
                        <th scope="col">Kode</th>
                        <th scope="col">Nama Barang</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Stok</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Tgl Masuk</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col" class="col-action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($barangList as $index => $barang): ?>
                        <?php
                        $nomor = $offset + $index + 1;
                        $stok  = (int) $barang['stok'];

                        // Tentukan status stok
                        if ($stok === 0) {
                            $stokBadge = 'badge-danger';
                            $stokLabel = 'Habis';
                        } elseif ($stok <= STOK_MINIMUM) {
                            $stokBadge = 'badge-warning';
                            $stokLabel = 'Menipis';
                        } else {
                            $stokBadge = 'badge-success';
                            $stokLabel = 'Aman';
                        }
                        ?>
                        <tr>
                            <td class="col-no text-muted"><?= $nomor ?></td>

                            <td>
                                <span class="kode-barang">
                                    <?= htmlspecialchars($barang['kode_barang'], ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </td>

                            <td>
                                <span class="text-bold">
                                    <?= htmlspecialchars($barang['nama_barang'], ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </td>

                            <td>
                                <span class="badge badge-gray">
                                    <?= htmlspecialchars($barang['kategori'], ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </td>

                            <td>
                                <span class="badge <?= $stokBadge ?>">
                                    <?= $stok ?>
                                    <?= htmlspecialchars($barang['satuan'], ENT_QUOTES, 'UTF-8') ?>
                                </span>
                                <div class="text-small text-muted" style="margin-top:3px;">
                                    <?= $stokLabel ?>
                                </div>
                            </td>

                            <td class="text-bold">
                                <?= formatRupiah((float) $barang['harga']) ?>
                            </td>

                            <td class="text-muted text-small">
                                <?= formatTanggal($barang['tanggal_masuk']) ?>
                            </td>

                            <td class="text-small text-muted" style="max-width:160px;">
                                <?php
                                $ket = $barang['keterangan'] ?? '';
                                echo htmlspecialchars(
                                    mb_strlen($ket) > 60 ? mb_substr($ket, 0, 57) . '…' : $ket,
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>

                            <td class="col-action">
                                <div class="d-flex align-center gap-8" style="justify-content:center;">
                                    <!-- Tombol Edit -->
                                    <a href="/tugas_RPL/pages/edit.php?id=<?= (int) $barang['id'] ?>"
                                       class="btn btn-warning btn-sm btn-icon"
                                       title="Edit barang"
                                       aria-label="Edit <?= htmlspecialchars($barang['nama_barang'], ENT_QUOTES, 'UTF-8') ?>">
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
                                        <!-- Token CSRF sederhana -->
                                        <input type="hidden" name="redirect"
                                               value="<?= htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8') ?>">
                                    </form>

                                    <!-- Tombol Hapus -->
                                    <button type="button"
                                            class="btn btn-danger btn-sm btn-icon"
                                            title="Hapus barang"
                                            aria-label="Hapus <?= htmlspecialchars($barang['nama_barang'], ENT_QUOTES, 'UTF-8') ?>"
                                            onclick="confirmDelete(
                                                '<?= htmlspecialchars(addslashes($barang['nama_barang']), ENT_QUOTES, 'UTF-8') ?>',
                                                'form-hapus-<?= (int) $barang['id'] ?>'
                                            )">
                                        🗑️
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- --------------------------------------------------------
             PAGINATION
             -------------------------------------------------------- -->
        <?php if ($totalPages > 1): ?>
        <div class="pagination-wrapper">
            <p class="pagination-info">
                Menampilkan
                <strong><?= $offset + 1 ?></strong>–<strong><?= min($offset + ROWS_PER_PAGE, $totalRows) ?></strong>
                dari <strong><?= $totalRows ?></strong> barang
            </p>

            <nav class="pagination" aria-label="Navigasi halaman">
                <?php
                // Bangun query string untuk pagination
                $queryParams = array_filter([
                    'q'      => $keyword,
                    'filter' => $filterMenipis ? 'menipis' : null,
                ]);

                // Tombol Sebelumnya
                $prevPage = $currentPage - 1;
                $prevQuery = http_build_query(array_merge($queryParams, ['page' => $prevPage]));
                ?>
                <a href="?<?= $prevQuery ?>"
                   class="page-link <?= $currentPage <= 1 ? 'disabled' : '' ?>"
                   aria-label="Halaman sebelumnya"
                   <?= $currentPage <= 1 ? 'aria-disabled="true" tabindex="-1"' : '' ?>>
                    ‹
                </a>

                <?php
                // Tombol nomor halaman (maksimal 5 nomor)
                $startPage = max(1, $currentPage - 2);
                $endPage   = min($totalPages, $startPage + 4);
                $startPage = max(1, $endPage - 4);

                for ($p = $startPage; $p <= $endPage; $p++):
                    $pQuery = http_build_query(array_merge($queryParams, ['page' => $p]));
                ?>
                    <a href="?<?= $pQuery ?>"
                       class="page-link <?= $p === $currentPage ? 'active' : '' ?>"
                       aria-label="Halaman <?= $p ?>"
                       <?= $p === $currentPage ? 'aria-current="page"' : '' ?>>
                        <?= $p ?>
                    </a>
                <?php endfor; ?>

                <?php
                // Tombol Berikutnya
                $nextPage = $currentPage + 1;
                $nextQuery = http_build_query(array_merge($queryParams, ['page' => $nextPage]));
                ?>
                <a href="?<?= $nextQuery ?>"
                   class="page-link <?= $currentPage >= $totalPages ? 'disabled' : '' ?>"
                   aria-label="Halaman berikutnya"
                   <?= $currentPage >= $totalPages ? 'aria-disabled="true" tabindex="-1"' : '' ?>>
                    ›
                </a>
            </nav>
        </div>
        <?php else: ?>
            <div class="pagination-wrapper">
                <p class="pagination-info">
                    Menampilkan <strong><?= $totalRows ?></strong> barang
                </p>
            </div>
        <?php endif; ?>

    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
