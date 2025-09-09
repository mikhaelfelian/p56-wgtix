<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<div class="hero-section mb-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="hero-card">
                    <div class="hero-content">
                        <div class="hero-text">
                            <h1 class="hero-title">
                                <i class="fas fa-qrcode"></i> Absensi Event
                            </h1>
                            <p class="hero-subtitle"><?= $event->event ?></p>
                            <div class="hero-meta">
                                <span class="meta-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <?= date('d M Y', strtotime($event->tgl_masuk)) ?> - <?= date('d M Y', strtotime($event->tgl_keluar)) ?>
                                </span>
                                <span class="meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= $event->lokasi ?: 'Lokasi belum ditentukan' ?>
                                </span>
                                <span class="meta-item">
                                    <i class="fas fa-users"></i>
                                    <?= $total_participants ?> peserta terdaftar
                                </span>
                            </div>
                        </div>
                        <div class="hero-actions">
                            <a href="<?= base_url('admin/events/view/' . $event->id) ?>" class="btn btn-outline-light">
                                <i class="fas fa-arrow-left"></i> Kembali ke Event
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-section mb-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number" id="total-participants"><?= $total_participants ?></div>
                            <div class="stat-label">Total Peserta</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number" id="attended-participants"><?= $attended_participants ?></div>
                            <div class="stat-label">Sudah Hadir</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-percentage"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number" id="attendance-rate"><?= $attendance_rate ?>%</div>
                            <div class="stat-label">Tingkat Kehadiran</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <div class="col-lg-8">
        <!-- QR Scanner Card -->
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-qrcode"></i> Scanner QR Code
                </h5>
            </div>
            <div class="card-body">
                <!-- QR Scanner -->
                <div class="scanner-container">
                    <div class="scanner-wrapper">
                        <div id="qr-reader"></div>
                        <div id="qr-reader-results"></div>
                    </div>
                    
                    <!-- Manual QR Input -->
                    <div class="manual-input-section">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-keyboard"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="manual-qr" placeholder="Masukkan QR Code secara manual">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="manual-scan-btn">
                                    <i class="fas fa-search"></i> Scan
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> Gunakan kamera untuk scan QR code atau masukkan secara manual
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Attendance Log Card -->
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-history"></i> Riwayat Absensi
                </h5>
            </div>
            <div class="card-body">
                <div id="attendance-log" class="attendance-log-container">
                    <div class="empty-log">
                        <div class="empty-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <h6>Belum ada riwayat</h6>
                        <p>Mulai scan QR code untuk melihat riwayat absensi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-bolt"></i> Aksi Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="action-list">
                    <button class="action-item" id="refresh-stats">
                        <div class="action-icon">
                            <i class="fas fa-sync"></i>
                        </div>
                        <div class="action-content">
                            <div class="action-title">Refresh Statistik</div>
                            <div class="action-desc">Perbarui data kehadiran</div>
                        </div>
                    </button>
                    <button class="action-item" id="reset-attendance">
                        <div class="action-icon">
                            <i class="fas fa-undo"></i>
                        </div>
                        <div class="action-content">
                            <div class="action-title">Reset Absensi</div>
                            <div class="action-desc">Reset semua kehadiran</div>
                        </div>
                    </button>
                    <a href="<?= base_url('admin/events/print/' . $event->id) ?>" class="action-item" target="_blank">
                        <div class="action-icon">
                            <i class="fas fa-print"></i>
                        </div>
                        <div class="action-content">
                            <div class="action-title">Cetak Daftar</div>
                            <div class="action-desc">Print daftar peserta</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modern-modal">
            <div class="modal-header success-header">
                <h5 class="modal-title" id="successModalLabel">
                    <i class="fas fa-check-circle"></i> Absensi Berhasil
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h5 id="success-participant-name" class="mt-3"></h5>
                <p id="success-message" class="text-muted"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-lg" data-dismiss="modal">
                    <i class="fas fa-check"></i> OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modern-modal">
            <div class="modal-header error-header">
                <h5 class="modal-title" id="errorModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Error
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="error-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h5 id="error-title" class="mt-3"></h5>
                <p id="error-message" class="text-muted"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">
                    <i class="fas fa-times"></i> OK
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
/* Professional Attendance Page Styles */
.hero-section {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-radius: 12px;
    margin: -20px -20px 20px -20px;
    padding: 30px;
    color: white;
}

.hero-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    padding: 30px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.hero-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.hero-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0 0 10px 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 15px;
}

.hero-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0 0 20px 0;
}

.hero-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.95rem;
    opacity: 0.9;
}

.meta-item i {
    font-size: 1rem;
}

.hero-actions .btn {
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.hero-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.stats-section {
    margin-bottom: 30px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 2.2rem;
    font-weight: 700;
    color: #495057;
    line-height: 1;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.9rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
}

.modern-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    margin-bottom: 20px;
    overflow: hidden;
}

.modern-card .card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    padding: 20px 25px;
}

.modern-card .card-title {
    margin: 0;
    font-weight: 600;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modern-card .card-body {
    padding: 25px;
}

.scanner-container {
    text-align: center;
}

.scanner-wrapper {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 25px;
    border: 2px dashed #dee2e6;
}

.manual-input-section {
    margin-top: 20px;
}

.manual-input-section .input-group {
    max-width: 500px;
    margin: 0 auto 10px auto;
}

.manual-input-section .input-group-text {
    background: #e9ecef;
    border-color: #ced4da;
    color: #6c757d;
}

.manual-input-section .form-control {
    border-radius: 0;
    border-color: #ced4da;
    padding: 12px 15px;
    font-size: 1rem;
}

.manual-input-section .form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.manual-input-section .btn {
    border-radius: 0 8px 8px 0;
    padding: 12px 20px;
    font-weight: 500;
}

.attendance-log-container {
    max-height: 400px;
    overflow-y: auto;
    padding-right: 10px;
}

.attendance-log-container::-webkit-scrollbar {
    width: 6px;
}

.attendance-log-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.attendance-log-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.attendance-log-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.empty-log {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.5;
}

.empty-log h6 {
    margin-bottom: 10px;
    font-weight: 600;
}

.empty-log p {
    margin: 0;
    font-size: 0.9rem;
}

.attendance-log-item {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.attendance-log-item:hover {
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.attendance-log-item.success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border-left-color: #28a745;
}

.attendance-log-item.error {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    border-left-color: #dc3545;
}

.attendance-log-item.warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border-left-color: #ffc107;
}

.attendance-log-item .participant-info {
    flex: 1;
}

.attendance-log-item .participant-name {
    font-weight: 600;
    color: #495057;
    margin-bottom: 3px;
}

.attendance-log-item .participant-email {
    font-size: 0.85rem;
    color: #6c757d;
}

.attendance-log-item .log-meta {
    text-align: right;
}

.attendance-log-item .log-time {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 5px;
}

.attendance-log-item .log-status {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.action-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.action-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    border: 1px solid transparent;
    cursor: pointer;
    width: 100%;
    border: none;
}

.action-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
    text-decoration: none;
    color: inherit;
    border-color: #dee2e6;
}

.action-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.action-content {
    flex: 1;
}

.action-title {
    font-weight: 600;
    color: #495057;
    margin-bottom: 2px;
}

.action-desc {
    font-size: 0.85rem;
    color: #6c757d;
}

/* Modal Styles */
.modern-modal {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.modern-modal .modal-header {
    border-bottom: none;
    padding: 25px 30px 20px 30px;
}

.success-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.error-header {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

.modern-modal .modal-title {
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modern-modal .modal-body {
    padding: 30px;
}

.success-icon, .error-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-size: 2.5rem;
}

.success-icon {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #28a745;
}

.error-icon {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #dc3545;
}

.modern-modal .modal-footer {
    border-top: none;
    padding: 20px 30px 30px 30px;
    text-align: center;
}

.modern-modal .btn {
    border-radius: 8px;
    padding: 12px 30px;
    font-weight: 500;
    min-width: 120px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-content {
        flex-direction: column;
        text-align: center;
    }
    
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-meta {
        justify-content: center;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-card {
        flex-direction: column;
        text-align: center;
    }
    
    .scanner-wrapper {
        padding: 20px;
    }
    
    .manual-input-section .input-group {
        max-width: 100%;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modern-card {
    animation: fadeInUp 0.6s ease-out;
}

.stat-card {
    animation: fadeInUp 0.5s ease-out;
}

.attendance-log-item {
    animation: fadeInUp 0.4s ease-out;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- QR Code Scanner Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
let html5QrcodeScanner;
let attendanceLog = [];
let isProcessing = false; // Flag to prevent double submission
let lastScanTime = 0; // Track last scan time for debouncing

$(document).ready(function() {
    // Initialize QR Scanner
    initQRScanner();
    
    // Manual QR input
    $('#manual-scan-btn').click(function() {
        if (isProcessing) return; // Prevent double submission
        
        const qrCode = $('#manual-qr').val().trim();
        if (qrCode) {
            processQRCode(qrCode);
            $('#manual-qr').val('');
        } else {
            showError('Error', 'Masukkan QR Code terlebih dahulu');
        }
    });
    
    // Enter key for manual input
    $('#manual-qr').keypress(function(e) {
        if (e.which === 13 && !isProcessing) {
            $('#manual-scan-btn').click();
        }
    });
    
    // Reset attendance
    $('#reset-attendance').click(function() {
        if (confirm('Apakah Anda yakin ingin mereset semua absensi untuk event ini?')) {
            resetAttendance();
        }
    });
    
    // Refresh statistics
    $('#refresh-stats').click(function() {
        refreshStats();
    });
});

function initQRScanner() {
    html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader",
        { 
            fps: 5, // Reduced FPS to prevent multiple scans
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0,
            rememberLastUsedCamera: true
        },
        false
    );
    
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
}

function onScanSuccess(decodedText, decodedResult) {
    const currentTime = Date.now();
    const timeSinceLastScan = currentTime - lastScanTime;
    
    // Only process if not already processing and at least 2 seconds since last scan
    if (!isProcessing && timeSinceLastScan > 2000) {
        lastScanTime = currentTime;
        processQRCode(decodedText);
    } else if (isProcessing) {
        console.log('Already processing QR code, ignoring duplicate scan');
    } else {
        console.log('Scan too soon, ignoring (debounced)');
    }
}

function onScanFailure(error) {
    // Handle scan failure silently
}

function processQRCode(qrCode) {
    // Prevent double submission
    if (isProcessing) {
        console.log('Already processing QR code, ignoring duplicate scan');
        return;
    }
    
    isProcessing = true;
    
    // Disable manual input while processing
    $('#manual-qr').prop('disabled', true);
    $('#manual-scan-btn').prop('disabled', true).text('Processing...');
    
    $.ajax({
        url: '<?= base_url('admin/events/absen/scan') ?>',
        type: 'POST',
        data: {
            qr_code: qrCode,
            event_id: '<?= $event->id ?>',
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showSuccess(response.participant.nama, response.message);
                addToAttendanceLog(response.participant, 'success');
                refreshStats();
            } else {
                showError('Error', response.message);
                if (response.participant) {
                    addToAttendanceLog(response.participant, 'warning');
                } else {
                    addToAttendanceLog({nama: 'Unknown', email: qrCode}, 'error');
                }
            }
        },
        error: function(xhr, status, error) {
            showError('Error', 'Terjadi kesalahan saat memproses QR Code');
            addToAttendanceLog({nama: 'Unknown', email: qrCode}, 'error');
        },
        complete: function() {
            // Re-enable input after processing
            isProcessing = false;
            $('#manual-qr').prop('disabled', false);
            $('#manual-scan-btn').prop('disabled', false).text('Scan');
            
            // Clear manual input
            $('#manual-qr').val('');
        }
    });
}

function showSuccess(participantName, message) {
    $('#success-participant-name').text(participantName);
    $('#success-message').text(message);
    $('#successModal').modal('show');
}

function showError(title, message) {
    $('#error-title').text(title);
    $('#error-message').text(message);
    $('#errorModal').modal('show');
}

function addToAttendanceLog(participant, type) {
    const timestamp = new Date().toLocaleString('id-ID');
    const logItem = `
        <div class="attendance-log-item ${type}">
            <div class="participant-info">
                <div class="participant-name">${participant.nama}</div>
                <div class="participant-email">${participant.email}</div>
            </div>
            <div class="log-meta">
                <div class="log-time">${timestamp}</div>
                <div class="log-status ${type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'error'}">
                    ${type === 'success' ? 'Berhasil' : type === 'warning' ? 'Sudah Hadir' : 'Error'}
                </div>
            </div>
        </div>
    `;
    
    // Add to top of log
    if ($('#attendance-log .empty-log').length > 0) {
        $('#attendance-log').html(logItem);
    } else {
        $('#attendance-log').prepend(logItem);
    }
    
    // Keep only last 20 items
    const items = $('#attendance-log .attendance-log-item');
    if (items.length > 20) {
        items.slice(20).remove();
    }
}

function refreshStats() {
    $.ajax({
        url: '<?= base_url('admin/events/absen/get-stats/' . $event->id) ?>',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#total-participants').text(response.total_participants);
                $('#attended-participants').text(response.attended_participants);
                $('#attendance-rate').text(response.attendance_rate + '%');
            }
        },
        error: function() {
            console.error('Failed to refresh statistics');
        }
    });
}

function resetAttendance() {
    $.ajax({
        url: '<?= base_url('admin/events/absen/reset/' . $event->id) ?>',
        type: 'POST',
        data: {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showSuccess('Reset Berhasil', response.message);
                refreshStats();
                $('#attendance-log').html(`
                    <div class="empty-log">
                        <div class="empty-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <h6>Absensi telah direset</h6>
                        <p>Mulai scan QR code untuk melihat riwayat absensi</p>
                    </div>
                `);
            } else {
                showError('Error', response.message);
            }
        },
        error: function() {
            showError('Error', 'Terjadi kesalahan saat mereset absensi');
        }
    });
}
</script>
<?= $this->endSection() ?>
