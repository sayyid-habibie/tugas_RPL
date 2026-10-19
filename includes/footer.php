    </main><!-- /.main-content -->

    <!-- ============================================================
         FOOTER
         ============================================================ -->
    <footer style="
        padding: 16px var(--content-padding);
        border-top: 1px solid var(--color-gray-200);
        background: var(--color-white);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
        font-size: .78rem;
        color: var(--color-gray-400);
    " role="contentinfo">
        <span>© <?= date('Y') ?> Sistem Inventori Barang — Tugas Mata Kuliah RPL</span>
        <span>PHP <?= PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION ?> &nbsp;·&nbsp; MySQL &nbsp;·&nbsp; XAMPP</span>
    </footer>

</div><!-- /.main-wrapper -->

<!-- ============================================================
     MODAL KONFIRMASI HAPUS (global, dipakai di semua halaman)
     ============================================================ -->
<div class="modal-overlay hidden"
     id="modalHapus"
     role="dialog"
     aria-modal="true"
     aria-labelledby="modalHapusTitle">

    <div class="modal">
        <div class="modal-header">
            <div class="modal-icon" aria-hidden="true">🗑️</div>
            <div>
                <h2 class="modal-title" id="modalHapusTitle">Konfirmasi Hapus</h2>
            </div>
        </div>
        <div class="modal-body">
            Apakah Anda yakin ingin menghapus barang
            <strong id="deleteItemName"></strong>?
            <br>
            <span class="text-danger text-small">Tindakan ini tidak dapat dibatalkan.</span>
        </div>
        <div class="modal-footer">
            <button type="button"
                    class="btn btn-outline btn-cancel-modal"
                    aria-label="Batal, jangan hapus">
                Batal
            </button>
            <button type="button"
                    class="btn btn-danger"
                    id="btnConfirmHapus">
                🗑️ Ya, Hapus
            </button>
        </div>
    </div>
</div>

<!-- Script utama -->
<script src="/tugas_RPL/assets/js/main.js"></script>
</body>
</html>
