<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">Absensi Event - <?= $event->event ?></h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/events/view/' . $event->id) ?>" class="btn btn-sm btn-secondary rounded-0">
                        <i class="fas fa-arrow-left"></i> Kembali ke Event
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Peserta</span>
                                <span class="info-box-number" id="total-participants"><?= $total_participants ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Sudah Hadir</span>
                                <span class="info-box-number" id="attended-participants"><?= $attended_participants ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-percentage"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Tingkat Kehadiran</span>
                                <span class="info-box-number" id="attendance-rate"><?= $attendance_rate ?>%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Scanner Section -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-qrcode"></i> Scanner QR Code
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                <div id="qr-reader" style="width: 100%; max-width: 400px; margin: 0 auto;"></div>
                                <div id="qr-reader-results"></div>
                                
                                <!-- Manual QR Input -->
                                <div class="mt-3">
                                    <label for="manual-qr">Atau masukkan QR Code secara manual:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="manual-qr" placeholder="Masukkan QR Code">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" id="manual-scan-btn">
                                                <i class="fas fa-search"></i> Scan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-history"></i> Riwayat Absensi
                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="attendance-log" style="max-height: 400px; overflow-y: auto;">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-qrcode fa-2x mb-2"></i>
                                        <p>Mulai scan QR code untuk melihat riwayat absensi</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center">
                                <button class="btn btn-warning mr-2" id="reset-attendance">
                                    <i class="fas fa-undo"></i> Reset Absensi
                                </button>
                                <button class="btn btn-info mr-2" id="refresh-stats">
                                    <i class="fas fa-sync"></i> Refresh Statistik
                                </button>
                                <a href="<?= base_url('admin/events/print/' . $event->id) ?>" class="btn btn-success" target="_blank">
                                    <i class="fas fa-print"></i> Cetak Daftar Peserta
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">
                    <i class="fas fa-check-circle"></i> Absensi Berhasil
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5 id="success-participant-name"></h5>
                    <p id="success-message"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="errorModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Error
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h5 id="error-title"></h5>
                    <p id="error-message"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
.info-box {
    display: block;
    min-height: 90px;
    background: #fff;
    width: 100%;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    border-radius: 2px;
    margin-bottom: 15px;
}

.info-box-icon {
    border-top-left-radius: 2px;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 2px;
    display: block;
    float: left;
    height: 90px;
    width: 90px;
    text-align: center;
    font-size: 45px;
    line-height: 90px;
    background: rgba(0,0,0,0.2);
}

.info-box-content {
    padding: 5px 10px;
    margin-left: 90px;
}

.info-box-text {
    text-transform: uppercase;
    font-weight: bold;
    font-size: 14px;
}

.info-box-number {
    display: block;
    font-weight: bold;
    font-size: 18px;
}

.attendance-log-item {
    padding: 10px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.attendance-log-item:last-child {
    border-bottom: none;
}

.attendance-log-item.success {
    background-color: #d4edda;
    border-left: 4px solid #28a745;
}

.attendance-log-item.error {
    background-color: #f8d7da;
    border-left: 4px solid #dc3545;
}

.attendance-log-item.warning {
    background-color: #fff3cd;
    border-left: 4px solid #ffc107;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- QR Code Scanner Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
let html5QrcodeScanner;
let attendanceLog = [];

$(document).ready(function() {
    // Initialize QR Scanner
    initQRScanner();
    
    // Manual QR input
    $('#manual-scan-btn').click(function() {
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
        if (e.which === 13) {
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
            fps: 10, 
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        },
        false
    );
    
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
}

function onScanSuccess(decodedText, decodedResult) {
    processQRCode(decodedText);
}

function onScanFailure(error) {
    // Handle scan failure silently
}

function processQRCode(qrCode) {
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
            <div>
                <strong>${participant.nama}</strong><br>
                <small class="text-muted">${participant.email}</small>
            </div>
            <div class="text-right">
                <small>${timestamp}</small><br>
                <span class="badge badge-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'danger'}">
                    ${type === 'success' ? 'Berhasil' : type === 'warning' ? 'Sudah Hadir' : 'Error'}
                </span>
            </div>
        </div>
    `;
    
    // Add to top of log
    if ($('#attendance-log .text-center').length > 0) {
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
                    <div class="text-center text-muted">
                        <i class="fas fa-qrcode fa-2x mb-2"></i>
                        <p>Absensi telah direset. Mulai scan QR code untuk melihat riwayat absensi</p>
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
