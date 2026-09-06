<!-- Modal Detail Pelanggan -->
<div class="modal fade cm-modal" id="ModalPelanggan" tabindex="-1" aria-labelledby="PelangganModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content cm-content">

            <div class="modal-header cm-header">
                <div class="cm-header-left">
                    <div class="cm-avatar" id="detail-avatar">--</div>
                    <div>
                        <h1 class="modal-title cm-title" id="PelangganModalLabel">Detail Pelanggan</h1>
                        <span class="cm-subtitle">Informasi lengkap akun customer</span>
                    </div>
                </div>
                <button type="button" class="btn-close cm-btn-close-x" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body cm-body">
                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-user"></i> Nama</span>
                    <span class="cm-field-value" id="detail-name">-</span>
                </div>
                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-envelope"></i> Email</span>
                    <span class="cm-field-value" id="detail-email">-</span>
                </div>
                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-location-dot"></i> Alamat</span>
                    <span class="cm-field-value" id="detail-alamat">-</span>
                </div>
                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-id-badge"></i> Role</span>
                    <span class="cm-field-value" id="detail-role">-</span>
                </div>
                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-circle-check"></i> Status</span>
                    <span class="cm-field-value">
                        <span class="status-badge" id="detail-status-badge">
                            <span id="detail-status">-</span>
                        </span>
                    </span>
                </div>
                <div class="cm-field cm-field-last">
                    <span class="cm-field-label"><i class="fa-solid fa-address-card"></i> Foto KTP</span>
                    <span class="cm-field-value">
                        <button type="button" class="ktp-thumb-btn" id="detail-ktp-btn" data-bs-toggle="modal"
                            data-bs-target="#ModalKtpViewer">
                            <img src="" alt="Foto KTP" id="detail-ktp-thumb">
                            <span class="ktp-thumb-empty" id="detail-ktp-empty">
                                <i class="fa-solid fa-image"></i>
                            </span>
                            <span class="ktp-thumb-zoom-icon"><i class="fa-solid fa-magnifying-glass-plus"></i></span>
                        </button>
                    </span>
                </div>
            </div>

            <div class="modal-footer cm-footer">
                <button type="button" class="btn btn-secondary cm-btn-close" data-bs-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade cm-modal" id="ModalKtpViewer" tabindex="-1" aria-labelledby="KtpViewerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content cm-content ktp-viewer-content">

            <div class="modal-header cm-header">
                <div class="cm-header-left">
                    <div class="cm-avatar"><i class="fa-solid fa-address-card"></i></div>
                    <div>
                        <h1 class="modal-title cm-title" id="KtpViewerModalLabel">Foto KTP</h1>
                        <span class="cm-subtitle">Geser untuk menggeser, gunakan tombol untuk zoom</span>
                    </div>
                </div>
                <button type="button" class="btn-close cm-btn-close-x" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body ktp-viewer-body">
                <div class="ktp-viewer-stage" id="ktp-viewer-stage">
                    <img src="" alt="Foto KTP" id="ktp-viewer-img" draggable="false">
                </div>

                <div class="ktp-viewer-controls">
                    <button type="button" class="ktp-zoom-btn" id="ktp-zoom-out" title="Zoom out">
                        <i class="fa-solid fa-minus"></i>
                    </button>
                    <span class="ktp-zoom-level" id="ktp-zoom-level">100%</span>
                    <button type="button" class="ktp-zoom-btn" id="ktp-zoom-in" title="Zoom in">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                    <span class="ktp-zoom-divider"></span>
                    <button type="button" class="ktp-zoom-btn" id="ktp-zoom-reset" title="Reset zoom">
                        <i class="fa-solid fa-arrows-rotate"></i>
                    </button>
                </div>
            </div>

            <div class="modal-footer cm-footer">
                <button type="button" class="btn btn-secondary cm-btn-close" data-bs-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>

<script>
    (function() {
        // ---------- Isi data ke modal detail (sesuaikan pemanggilnya) ----------
        // Contoh pemanggilan dari tombol "lihat" di tabel:
        // openDetailPelanggan({ name, email, alamat, role, status, avatar, foto_ktp })
        window.openDetailPelanggan = function(data) {
            document.getElementById('detail-name').textContent = data.name ?? '-';
            document.getElementById('detail-email').textContent = data.email ?? '-';
            document.getElementById('detail-alamat').textContent = data.alamat ?? '-';
            document.getElementById('detail-role').textContent = data.role ?? '-';
            document.getElementById('detail-avatar').textContent = (data.name ?? 'U').charAt(0).toUpperCase();

            const statusBadge = document.getElementById('detail-status-badge');
            const statusText = document.getElementById('detail-status');
            statusText.textContent = data.status ?? '-';
            statusBadge.className = 'status-badge status-' + (data.statusClass ?? 'pending');

            const thumb = document.getElementById('detail-ktp-thumb');
            const empty = document.getElementById('detail-ktp-empty');
            const btn = document.getElementById('detail-ktp-btn');

            if (data.foto_ktp) {
                thumb.src = data.foto_ktp;
                thumb.classList.remove('d-none');
                empty.classList.add('d-none');
                btn.dataset.ktpSrc = data.foto_ktp;
                btn.disabled = false;
            } else {
                thumb.src = '';
                thumb.classList.add('d-none');
                empty.classList.remove('d-none');
                btn.removeAttribute('data-ktp-src');
                btn.disabled = true;
            }
        };

        // ---------- Lempar src foto ke viewer saat modal viewer dibuka ----------
        const modalKtpViewer = document.getElementById('ModalKtpViewer');
        const viewerImg = document.getElementById('ktp-viewer-img');

        modalKtpViewer.addEventListener('show.bs.modal', function() {
            const src = document.getElementById('detail-ktp-btn').dataset.ktpSrc || '';
            viewerImg.src = src;
            resetZoom();
        });

        // ---------- Zoom logic ----------
        const stage = document.getElementById('ktp-viewer-stage');
        const zoomLevelEl = document.getElementById('ktp-zoom-level');
        const btnZoomIn = document.getElementById('ktp-zoom-in');
        const btnZoomOut = document.getElementById('ktp-zoom-out');
        const btnZoomReset = document.getElementById('ktp-zoom-reset');

        const MIN_SCALE = 1;
        const MAX_SCALE = 4;
        const STEP = 0.5;

        let scale = 1;
        let posX = 0;
        let posY = 0;
        let isDragging = false;
        let startX = 0;
        let startY = 0;

        function applyTransform() {
            viewerImg.style.transform = `translate(${posX}px, ${posY}px) scale(${scale})`;
            zoomLevelEl.textContent = Math.round(scale * 100) + '%';
            stage.classList.toggle('is-zoomed', scale > 1);
            btnZoomOut.disabled = scale <= MIN_SCALE;
            btnZoomIn.disabled = scale >= MAX_SCALE;
        }

        function clampPosition() {
            if (scale <= 1) {
                posX = 0;
                posY = 0;
                return;
            }
            const maxOffsetX = (stage.clientWidth * (scale - 1)) / 2;
            const maxOffsetY = (stage.clientHeight * (scale - 1)) / 2;
            posX = Math.min(maxOffsetX, Math.max(-maxOffsetX, posX));
            posY = Math.min(maxOffsetY, Math.max(-maxOffsetY, posY));
        }

        function setScale(newScale) {
            scale = Math.min(MAX_SCALE, Math.max(MIN_SCALE, newScale));
            clampPosition();
            applyTransform();
        }

        function resetZoom() {
            scale = 1;
            posX = 0;
            posY = 0;
            applyTransform();
        }

        btnZoomIn.addEventListener('click', () => setScale(scale + STEP));
        btnZoomOut.addEventListener('click', () => setScale(scale - STEP));
        btnZoomReset.addEventListener('click', resetZoom);

        // Scroll wheel zoom
        stage.addEventListener('wheel', function(e) {
            e.preventDefault();
            setScale(scale + (e.deltaY < 0 ? STEP : -STEP));
        }, {
            passive: false
        });

        // Drag to pan (mouse)
        stage.addEventListener('mousedown', function(e) {
            if (scale <= 1) return;
            isDragging = true;
            startX = e.clientX - posX;
            startY = e.clientY - posY;
            stage.classList.add('is-dragging');
        });

        window.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            posX = e.clientX - startX;
            posY = e.clientY - startY;
            clampPosition();
            applyTransform();
        });

        window.addEventListener('mouseup', function() {
            isDragging = false;
            stage.classList.remove('is-dragging');
        });

        // Drag to pan (touch)
        stage.addEventListener('touchstart', function(e) {
            if (scale <= 1 || e.touches.length !== 1) return;
            isDragging = true;
            startX = e.touches[0].clientX - posX;
            startY = e.touches[0].clientY - posY;
        }, {
            passive: true
        });

        stage.addEventListener('touchmove', function(e) {
            if (!isDragging || e.touches.length !== 1) return;
            posX = e.touches[0].clientX - startX;
            posY = e.touches[0].clientY - startY;
            clampPosition();
            applyTransform();
        }, {
            passive: true
        });

        stage.addEventListener('touchend', function() {
            isDragging = false;
        });

        // Double click / double tap untuk toggle zoom cepat
        stage.addEventListener('dblclick', function() {
            scale > 1 ? resetZoom() : setScale(2.5);
        });

        modalKtpViewer.addEventListener('hidden.bs.modal', resetZoom);
    })();
</script>
