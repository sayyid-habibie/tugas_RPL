<?php

/**
 * Halaman Tambah Barang
 * 
 * Menampilkan form untuk menambahkan data barang baru.
 * Validasi dilakukan di sisi client (JS) dan server (barang_process.php).
 */

require_once __DIR__ . '/../includes/functions.php';

$pageTitle   = 'Tambah Barang';
$topbarTitle = 'Tambah Barang Baru';
$activeNav   = 'tambah';

require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <span aria-hidden="true">➕</span> Form Tambah Barang
        </h2>
        <a href="/tugas_RPL/pages/barang.php" class="btn btn-outline btn-sm">
            ← Kembali ke Daftar
        </a>
    </div>

    <div class="card-body">
        <form action="/tugas_RPL/process/barang_process.php"
              method="POST"
              class="form-needs-validation"
              novalidate
              aria-label="Form tambah barang">

            <input type="hidden" name="action" value="add">

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
                           placeholder="Contoh: BRG-001"
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
                           placeholder="Contoh: Laptop Lenovo ThinkPad"
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
                           placeholder="Contoh: Elektronik"
                           maxlength="100"
                           required
                           list="kategori-list"
                           aria-describedby="kategori_err">
                    <!-- Saran kategori (datalist) -->
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
                           placeholder="Contoh: Unit, Pcs, Kg, Lusin"
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
                           placeholder="0"
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
                               placeholder="0"
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
                           value="<?= date('Y-m-d') ?>"
                           required
                           aria-describedby="tanggal_err">
                    <span class="form-error" id="tanggal_err" role="alert"></span>
                </div>

                <!-- Spacer untuk grid alignment -->
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
                              aria-describedby="keterangan_hint"></textarea>
                    <span class="form-hint" id="keterangan_hint">Maksimal 500 karakter.</span>
                </div>

                <!-- Tombol Aksi -->
                <div class="form-actions">
                    <a href="/tugas_RPL/pages/barang.php" class="btn btn-outline">
                        Batal
                    </a>
                    <button type="reset" class="btn btn-outline">
                        🔄 Reset Form
                    </button>
                    <button type="submit" class="btn btn-primary">
                        💾 Simpan Barang
                    </button>
                </div>

            </div><!-- /.form-grid -->
        </form>
    </div><!-- /.card-body -->
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
