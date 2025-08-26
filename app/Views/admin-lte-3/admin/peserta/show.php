<?= $this->extend('admin-lte-3/layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= $title ?></h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/peserta/daftar') ?>" class="btn btn-sm btn-secondary rounded-0">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <a href="<?= base_url("admin/peserta/edit/$peserta->id") ?>" class="btn btn-sm btn-warning rounded-0">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Kode Peserta</strong></td>
                                <td>: <?= $peserta->kode_peserta ?></td>
                            </tr>
                            <tr>
                                <td><strong>Nama Lengkap</strong></td>
                                <td>: <?= $peserta->nama_lengkap ?></td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Kelamin</strong></td>
                                <td>: 
                                    <span class="badge badge-<?= ($peserta->jenis_kelamin == 'L') ? 'info' : 'warning' ?>">
                                        <?= ($peserta->jenis_kelamin == 'L') ? 'Laki-laki' : 'Perempuan' ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tempat Lahir</strong></td>
                                <td>: <?= $peserta->tempat_lahir ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Lahir</strong></td>
                                <td>: <?= $peserta->tanggal_lahir ? date('d/m/Y', strtotime($peserta->tanggal_lahir)) : '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>No. HP</strong></td>
                                <td>: <?= $peserta->no_hp ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>: <?= $peserta->email ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Kelompok</strong></td>
                                <td>: <?= $peserta->nama_kelompok ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Kategori</strong></td>
                                <td>: <?= $peserta->nama_kategori ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td>: <?= $peserta->alamat ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>: 
                                    <span class="badge badge-<?= ($peserta->status == '1') ? 'success' : 'danger' ?>">
                                        <?= ($peserta->status == '1') ? 'Aktif' : 'Tidak Aktif' ?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">QR Code</h6>
                            </div>
                            <div class="card-body text-center">
                                <?php if ($peserta->qr_code): ?>
                                    <div id="participant-qr">
                                        <img src="data:image/png;base64,<?= $peserta->qr_code ?>" 
                                             alt="QR Code" 
                                             style="max-width: 200px; height: auto;">
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="downloadQR()">
                                        <i class="fas fa-download"></i> Download QR Code
                                    </button>
                                <?php else: ?>
                                    <div id="participant-qr">
                                        <i class="fas fa-qrcode fa-3x text-muted"></i>
                                        <br><small class="text-muted">QR Code tidak tersedia</small>
                                    </div>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<script>
// Define downloadQR function immediately
function downloadQR() {
    const qrContainer = document.getElementById('participant-qr');
    if (qrContainer) {
        const img = qrContainer.querySelector('img');
        if (img) {
            // Create a canvas to draw the image
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = img.naturalWidth;
            canvas.height = img.naturalHeight;
            
            // Draw the image on canvas
            ctx.drawImage(img, 0, 0);
            
            // Convert to blob and download
            canvas.toBlob(function(blob) {
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.download = 'qr-code-<?= $peserta->kode_peserta ?>.png';
                link.href = url;
                link.click();
                URL.revokeObjectURL(url);
            }, 'image/png');
        } else {
            alert('QR Code tidak tersedia untuk diunduh');
        }
    }
}

// Also make it available globally
window.downloadQR = downloadQR;
</script> 