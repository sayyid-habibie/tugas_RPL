<?php

/**
 * Halaman Edit Barang
 * 
 * Menampilkan form yang sudah terisi dengan data barang yang dipilih.
 * Memvalidasi keberadaan ID sebelum menampilkan form.
 */

require_once __DIR__ . '/../includes/functions.php';

// ------------------------------------------------------------
// Validasi parameter ID dari URL
// ------------------------------------------------------------
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    setFlashMessage('error', 'ID barang tidak valid.');
    redirect('/tugas_RPL/pages/barang.php');
}

// Ambil data barang dari database
$barang = getBarangById($id);

if ($barang === null) {
    setFlashMessage('error', 'Barang tidak ditemukan atau sudah dihapus.');
    redirect('/tugas_RPL/pages/barang.php');
}

// Variabel layout
$pageTitle   = 'Edit Barang — ' . htmlspecialchars($barang['nama_barang'], ENT_QUOTES, 'UTF-8');
$topbarTitle = 'Edit Barang';
$activeNav   = 'barang';

require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">
                <span aria-hidden="true">✏️</span> Edit Data Barang
            </h2>
            <p class="text-muted text-small" style="margin-top:4px;">
                Kode:
                <span class="kode-barang">
                    <?= htmlspecialchars($barang['kode_barang'], ENT_QUOTES, 'UTF-8') ?>
                </span>
            </p>
        </div>
        <a href="/tugas_RPL/pages/barang.php" class="btn btn-outline btn-sm">
            ← Kembali ke Daftar
        </a>
    </div>

    <div class="card-body">
        <form action="/tugas_RPL/process/barang_process.php"
              method="POST"
              class="form-needs-validation"
              novalidate
              aria-label="Form edit barang">

            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" value="<?= (int) $barang['id'] ?>">

            <div class="form-grid">

                <!-- Kode Barang -->
                <div class="form-group">
                    <label class="form-label" for="kode_barang">
                        Kode Barang <span class="required" aria-label="wajib diisi">*</span>
                    </label>
                    <input type="text"
                           id="kode_barang"
                           name="kode_barang"
                           class="form-control"
                           value="<?= htmlspecialchars($barang['kode_barang'], ENT_QUOTES, 'UTF-8') ?>"
                           maxlength="50"
                           required
                           autocomplete="off"
                           aria-describedby="kode_barang_hint kode_barang_err">
                    <span class="form-hint" id="kode_barang_hint">
                        Huruf, angka, tanda hubung (-), dan underscore (_).
                    </span>
                    <span class="form-error" id="kode_barang_err" role="alert"></span>
                </div>

                <!-- Nama Barang -->
                <div class="form-group">
                    <label class="form-label" for="nama_barang">
                        Nama Barang <span class="required" aria-label="wajib diisi">*</span>
                    </label>
                    <input type="text"
                           id="nama_barang"
                           name="nama_barang"
                           class="form-control"
                           value="<?= htmlspecialchars($barang['nama_barang'], ENT_QUOTES, 'UTF-8') ?>"
                           maxlength="150"
                           required
                           aria-describedby="nama_barang_err">
                    <span class="form-error" id="nama_barang_err" role="alert"></span>
                </div>

                <!-- Kategori -->
                <div class="form-group">
                    <label class="form-label" for="kategori">
                        Kategori <span class="required" aria-label="wajib diisi">*</span>
                    </label>
                    <input type="text"
                           id="kategori"
                           name="kategori"
                           class="form-control"
                           value="<?= htmlspecialchars($barang['kategori'], ENT_QUOTES, 'UTF-8') ?>"
                           maxlength="100"
                           required
                           list="kategori-list"
                           aria-describedby="kategori_err">
                    <datalist id="kategori-list">
                        <option value="Elektronik">
                        <option value="Alat Tulis Kantor">
                        <option value="Furnitur">
                        <option value="Peralatan Rumah Tangga">
                        <option value="Makanan & Minuman">
                        <option value="Pakaian">
                        <option value="Kendaraan & Aksesori">
                        <option value="Bahan Bangunan">
                        <option value="Peralatan Medis">
                        <option value="Lain-lain">
                    </datalist>
                    <span class="form-error" id="kategori_err" role="alert"></span>
                </div>

                <!-- Satuan -->
                <div class="form-group">
                    <label class="form-label" for="satuan">
                        Satuan <span class="required" aria-label="wajib diisi">*</span>
                    </label>
                    <input type="text"
                           id="satuan"
                           name="satuan"
                           class="form-control"
                           value="<?= htmlspecialchars($barang['satuan'], ENT_QUOTES, 'UTF-8') ?>"
                           maxlength="30"
                           required
                           list="satuan-list"
                           aria-describedby="satuan_err">
                    <datalist id="satuan-list">
                        <option value="Unit">
                        <option value="Pcs">
                        <option value="Kg">
                        <option value="Gram">
                        <option value="Liter">
                        <option value="Lusin">
                        <option value="Rim">
                        <option value="Box">
                        <option value="Karton">
                        <option value="Set">
                    </datalist>
                    <span class="form-error" id="satuan_err" role="alert"></span>
                </div>

                <!-- Jumlah Stok -->
                <div class="form-group">
                    <label class="form-label" for="stok">
                        Jumlah Stok <span class="required" aria-label="wajib diisi">*</span>
                    </label>
                    <input type="number"
                           id="stok"
                           name="stok"
                           class="form-control"
                           value="<?= (int) $barang['stok'] ?>"
                           min="0"
                           max="9999999"
                           required
                           aria-describedby="stok_err">
                    <span class="form-error" id="stok_err" role="alert"></span>
                </div>

                <!-- Harga Barang -->
                <div class="form-group">
                    <label class="form-label" for="hargaInput">
                        Harga Barang <span class="required" aria-label="wajib diisi">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-addon" aria-hidden="true">Rp</span>
                        <input type="number"
                               id="hargaInput"
                               name="harga"
                               class="form-control"
                               value="<?= (int) $barang['harga'] ?>"
                               min="0"
                               step="1"
                               required
                               aria-describedby="harga_err"
                               aria-label="Harga dalam Rupiah">
                    </div>
                    <span class="form-error" id="harga_err" role="alert"></span>
                </div>

                <!-- Tanggal Masuk -->
                <div class="form-group">
                    <label class="form-label" for="tanggal_masuk">
                        Tanggal Masuk <span class="required" aria-label="wajib diisi">*</span>
                    </label>
                    <input type="date"
                           id="tanggal_masuk"
                           name="tanggal_masuk"
                           class="form-control"
                           value="<?= htmlspecialchars($barang['tanggal_masuk'], ENT_QUOTES, 'UTF-8') ?>"
                           required
                           aria-describedby="tanggal_err">
                    <span class="form-error" id="tanggal_err" role="alert"></span>
                </div>

                <!-- Spacer -->
                <div class="form-group" aria-hidden="true"></div>

                <!-- Keterangan (full width) -->
                <div class="form-group full-width">
                    <label class="form-label" for="keterangan">
                        Keterangan
                        <span class="text-muted text-small">(opsional)</span>
                    </label>
                    <textarea id="keterangan"
                              name="keterangan"
                              class="form-control"
                              rows="3"
                              maxlength="500"
                              placeholder="Catatan tambahan tentang barang ini…"
                              aria-describedby="keterangan_hint"><?= htmlspecialchars($barang['keterangan'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    <span class="form-hint" id="keterangan_hint">Maksimal 500 karakter.</span>
                </div>

                <!-- Info terakhir diperbarui -->
                <?php if (!empty($barang['updated_at'])): ?>
                <div class="form-group full-width">
                    <p class="text-muted text-small">
                        🕐 Terakhir diperbarui:
                        <?= htmlspecialchars(
                            date('d/m/Y H:i', strtotime($barang['updated_at'])),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </p>
                </div>
                <?php endif; ?>

                <!-- Tombol Aksi -->
                <div class="form-actions">
                    <a href="/tugas_RPL/pages/barang.php" class="btn btn-outline">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        💾 Simpan Perubahan
                    </button>
                </div>

            </div><!-- /.form-grid -->
        </form>
    </div><!-- /.card-body -->
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
