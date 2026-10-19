-- ============================================================
-- Sistem Inventori Barang
-- File    : database/inventori.sql
-- Desc    : Script pembuatan database, tabel, dan data contoh
-- Jalankan melalui phpMyAdmin atau terminal MySQL
-- ============================================================

-- Buat database (jika belum ada)
CREATE DATABASE IF NOT EXISTS `inventori_barang`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Gunakan database
USE `inventori_barang`;

-- ============================================================
-- Tabel: barang
-- ============================================================
CREATE TABLE IF NOT EXISTS `barang` (
    `id`            INT(11)        UNSIGNED NOT NULL AUTO_INCREMENT,
    `kode_barang`   VARCHAR(50)    NOT NULL COMMENT 'Kode unik barang',
    `nama_barang`   VARCHAR(150)   NOT NULL COMMENT 'Nama lengkap barang',
    `kategori`      VARCHAR(100)   NOT NULL COMMENT 'Kategori/jenis barang',
    `stok`          INT(11)        UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Jumlah stok saat ini',
    `satuan`        VARCHAR(30)    NOT NULL COMMENT 'Satuan barang (unit, pcs, kg, dsb)',
    `harga`         DECIMAL(15,2)  NOT NULL DEFAULT 0.00 COMMENT 'Harga per satuan',
    `tanggal_masuk` DATE           NOT NULL COMMENT 'Tanggal barang pertama masuk',
    `keterangan`    TEXT                    COMMENT 'Catatan tambahan',
    `created_at`    TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu data dibuat',
    `updated_at`    TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP
                                            ON UPDATE CURRENT_TIMESTAMP
                                            COMMENT 'Waktu data terakhir diperbarui',

    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_kode_barang` (`kode_barang`),
    INDEX `idx_nama_barang`   (`nama_barang`),
    INDEX `idx_kategori`      (`kategori`),
    INDEX `idx_stok`          (`stok`),
    INDEX `idx_tanggal_masuk` (`tanggal_masuk`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Tabel master data barang inventori';

-- ============================================================
-- Data Contoh (Sample Data)
-- Hapus bagian ini jika tidak diperlukan
-- ============================================================
INSERT INTO `barang`
    (`kode_barang`, `nama_barang`, `kategori`, `stok`, `satuan`, `harga`, `tanggal_masuk`, `keterangan`)
VALUES
    ('ELK-001', 'Laptop Lenovo ThinkPad E15',      'Elektronik',         15, 'Unit',  8500000.00,  '2026-01-10', 'Processor Intel Core i5 Gen 12, RAM 8GB, SSD 256GB'),
    ('ELK-002', 'Monitor Samsung 24 inch FHD',     'Elektronik',          8, 'Unit',  2750000.00,  '2026-01-15', 'Resolusi 1920x1080, Panel IPS, HDMI + VGA'),
    ('ELK-003', 'Mouse Wireless Logitech M185',    'Elektronik',         30, 'Pcs',   175000.00,   '2026-01-20', 'USB nano receiver, baterai tahan 12 bulan'),
    ('ELK-004', 'Keyboard Mechanical Rexus RX-MK5','Elektronik',          5, 'Pcs',   450000.00,   '2026-02-01', 'Switch Blue, backlight RGB, USB'),
    ('ATK-001', 'Kertas HVS A4 80gr Sinar Dunia',  'Alat Tulis Kantor',  50, 'Rim',   58000.00,    '2026-01-05', '1 Rim = 500 lembar'),
    ('ATK-002', 'Pulpen Pilot G2 0.5mm',           'Alat Tulis Kantor', 120, 'Pcs',    8500.00,    '2026-01-05', 'Tinta gel, warna hitam'),
    ('ATK-003', 'Spidol Whiteboard Snowman',       'Alat Tulis Kantor',   3, 'Box',   45000.00,    '2026-02-10', '1 Box isi 10 pcs, warna hitam'),
    ('ATK-004', 'Staples Max HD-10',               'Alat Tulis Kantor',  10, 'Pcs',   22000.00,    '2026-02-15', 'Staples untuk kertas tebal'),
    ('FRN-001', 'Kursi Kantor Ergonomis Type-A',   'Furnitur',            6, 'Unit', 1250000.00,   '2026-01-25', 'High back, adjustable height, lumbar support'),
    ('FRN-002', 'Meja Kerja L-Shape 160x120cm',    'Furnitur',            2, 'Unit', 2100000.00,   '2026-01-25', 'Material MDF, warna walnut'),
    ('PRH-001', 'Toner Printer HP LaserJet 85A',   'Peralatan Rumah Tangga', 7, 'Pcs', 385000.00, '2026-02-20', 'Compatible HP P1102/P1102w, yield ~1600 hal'),
    ('PRH-002', 'Sabun Cuci Tangan Dettol 250ml',  'Peralatan Rumah Tangga',20, 'Botol', 28000.00, '2026-03-01', 'Antibakteri, refill friendly'),
    ('MKN-001', 'Air Mineral Aqua 600ml',          'Makanan & Minuman',  48, 'Botol',  3500.00,    '2026-03-05', '1 dus isi 24 botol'),
    ('MKN-002', 'Gula Pasir Premium 1kg',          'Makanan & Minuman',   9, 'Kg',    17000.00,    '2026-03-05', 'Gula putih kristal'),
    ('ELK-005', 'UPS APC Back-UPS 650VA',          'Elektronik',          4, 'Unit', 1150000.00,   '2026-03-10', 'Proteksi lonjakan arus, battery backup 10 menit');

-- ============================================================
-- Selesai
-- ============================================================
