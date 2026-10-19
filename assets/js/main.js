/**
 * Sistem Inventori Barang
 * File  : assets/js/main.js
 * Desc  : JavaScript utama — interaksi UI, konfirmasi hapus, pencarian, sidebar
 */

'use strict';

/* ============================================================
   1. Inisialisasi saat DOM siap
   ============================================================ */
document.addEventListener('DOMContentLoaded', function () {
    initSidebar();
    initSearchBox();
    initDeleteConfirmation();
    initAlertDismiss();
    initFormValidation();
    highlightActiveNav();
});

/* ============================================================
   2. Sidebar Toggle (Mobile)
   ============================================================ */
function initSidebar() {
    const sidebar        = document.getElementById('sidebar');
    const btnToggle      = document.getElementById('btnToggleSidebar');
    const overlay        = document.getElementById('sidebarOverlay');

    if (!sidebar || !btnToggle) return;

    /** Buka sidebar */
    function openSidebar() {
        sidebar.classList.add('open');
        if (overlay) overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    /** Tutup sidebar */
    function closeSidebar() {
        sidebar.classList.remove('open');
        if (overlay) overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    btnToggle.addEventListener('click', function () {
        sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });

    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    // Tutup sidebar saat ukuran layar membesar
    window.addEventListener('resize', function () {
        if (window.innerWidth > 900) {
            closeSidebar();
        }
    });
}

/* ============================================================
   3. Search Box — tombol clear & debounce live-search
   ============================================================ */
function initSearchBox() {
    const searchInput = document.getElementById('searchInput');
    const btnClear    = document.getElementById('btnClearSearch');
    const searchForm  = document.getElementById('searchForm');

    if (!searchInput) return;

    /** Tampilkan / sembunyikan tombol clear */
    function toggleClearButton() {
        if (!btnClear) return;
        btnClear.style.display = searchInput.value.length > 0 ? 'flex' : 'none';
    }

    searchInput.addEventListener('input', function () {
        toggleClearButton();
    });

    if (btnClear) {
        btnClear.addEventListener('click', function () {
            searchInput.value = '';
            toggleClearButton();
            searchInput.focus();
            // Submit form untuk reset hasil pencarian
            if (searchForm) searchForm.submit();
        });
    }

    // Inisialisasi saat load (jika ada nilai dari URL)
    toggleClearButton();
}

/* ============================================================
   4. Modal Konfirmasi Hapus
   ============================================================ */
function initDeleteConfirmation() {
    const modalOverlay = document.getElementById('modalHapus');
    const btnConfirm   = document.getElementById('btnConfirmHapus');
    const btnCancel    = document.querySelectorAll('.btn-cancel-modal');
    let   deleteForm   = null;

    if (!modalOverlay) return;

    /**
     * Buka modal konfirmasi hapus.
     * Dipanggil dari onclick attribute tombol hapus di tabel.
     *
     * @param {string} namaBarang  Nama barang yang akan dihapus
     * @param {string} formId      ID form hapus yang akan di-submit
     */
    window.confirmDelete = function (namaBarang, formId) {
        const nameEl = document.getElementById('deleteItemName');
        if (nameEl) {
            nameEl.textContent = namaBarang;
        }
        deleteForm = document.getElementById(formId);
        modalOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    // Tombol konfirmasi hapus
    if (btnConfirm) {
        btnConfirm.addEventListener('click', function () {
            if (deleteForm) {
                deleteForm.submit();
            }
        });
    }

    // Tombol batal / tutup modal
    btnCancel.forEach(function (btn) {
        btn.addEventListener('click', closeModal);
    });

    // Klik di luar modal untuk menutup
    modalOverlay.addEventListener('click', function (e) {
        if (e.target === modalOverlay) {
            closeModal();
        }
    });

    // Tekan Escape untuk menutup
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modalOverlay.classList.contains('hidden')) {
            closeModal();
        }
    });

    function closeModal() {
        modalOverlay.classList.add('hidden');
        document.body.style.overflow = '';
        deleteForm = null;
    }
}

/* ============================================================
   5. Dismiss Alert Otomatis & Manual
   ============================================================ */
function initAlertDismiss() {
    const alerts = document.querySelectorAll('.alert');

    alerts.forEach(function (alert) {
        // Tombol tutup manual
        const closeBtn = alert.querySelector('.alert-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                dismissAlert(alert);
            });
        }

        // Auto-dismiss setelah 5 detik
        setTimeout(function () {
            dismissAlert(alert);
        }, 5000);
    });
}

/**
 * Animasikan dan hapus elemen alert dari DOM.
 *
 * @param {HTMLElement} alertEl Elemen alert yang akan dihapus
 */
function dismissAlert(alertEl) {
    alertEl.style.transition = 'opacity .4s ease, transform .4s ease';
    alertEl.style.opacity    = '0';
    alertEl.style.transform  = 'translateY(-6px)';
    setTimeout(function () {
        if (alertEl.parentNode) {
            alertEl.parentNode.removeChild(alertEl);
        }
    }, 400);
}

/* ============================================================
   6. Validasi Form Client-Side (Tambah / Edit Barang)
   ============================================================ */
function initFormValidation() {
    const forms = document.querySelectorAll('.form-needs-validation');

    forms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            let isValid = true;

            // Reset error state
            form.querySelectorAll('.form-control').forEach(function (input) {
                input.classList.remove('is-invalid');
                const errEl = input.parentNode.querySelector('.form-error');
                if (errEl) errEl.textContent = '';
            });

            // Validasi setiap field required
            form.querySelectorAll('[required]').forEach(function (input) {
                if (!input.value.trim()) {
                    markInvalid(input, 'Field ini wajib diisi.');
                    isValid = false;
                }
            });

            // Validasi stok harus angka positif
            const stokInput = form.querySelector('[name="stok"]');
            if (stokInput && stokInput.value.trim() !== '') {
                const stokVal = parseInt(stokInput.value, 10);
                if (isNaN(stokVal) || stokVal < 0) {
                    markInvalid(stokInput, 'Stok harus berupa angka positif.');
                    isValid = false;
                }
            }

            // Validasi harga harus angka positif
            const hargaInput = form.querySelector('[name="harga"]');
            if (hargaInput && hargaInput.value.trim() !== '') {
                const hargaVal = parseFloat(hargaInput.value);
                if (isNaN(hargaVal) || hargaVal < 0) {
                    markInvalid(hargaInput, 'Harga harus berupa angka positif.');
                    isValid = false;
                }
            }

            // Validasi format kode barang
            const kodeInput = form.querySelector('[name="kode_barang"]');
            if (kodeInput && kodeInput.value.trim() !== '') {
                const kodePattern = /^[A-Za-z0-9\-_]+$/;
                if (!kodePattern.test(kodeInput.value.trim())) {
                    markInvalid(
                        kodeInput,
                        'Kode hanya boleh berisi huruf, angka, tanda hubung, dan underscore.'
                    );
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault();
                // Scroll ke error pertama
                const firstError = form.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }
        });
    });
}

/**
 * Tandai input sebagai invalid dan tampilkan pesan error.
 *
 * @param {HTMLElement} inputEl  Elemen input
 * @param {string}      message  Pesan error
 */
function markInvalid(inputEl, message) {
    inputEl.classList.add('is-invalid');
    const errEl = inputEl.parentNode.querySelector('.form-error');
    if (errEl) {
        errEl.textContent = message;
    }
}

/* ============================================================
   7. Tandai link navigasi yang aktif
   ============================================================ */
function highlightActiveNav() {
    const currentPath = window.location.pathname;
    const navLinks    = document.querySelectorAll('.sidebar-nav a');

    navLinks.forEach(function (link) {
        const linkPath = new URL(link.href, window.location.origin).pathname;
        if (currentPath === linkPath) {
            link.classList.add('active');
        }
    });
}

/* ============================================================
   8. Format input harga — tampilkan separator ribuan
   ============================================================ */
(function () {
    const hargaInput = document.getElementById('hargaInput');
    if (!hargaInput) return;

    hargaInput.addEventListener('input', function () {
        // Biarkan hanya angka
        this.value = this.value.replace(/[^0-9]/g, '');
    });
})();
