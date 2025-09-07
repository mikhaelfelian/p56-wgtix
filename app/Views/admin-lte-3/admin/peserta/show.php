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
                                <td>: <?= $peserta->kode ?></td>
                            </tr>
                            <tr>
                                <td><strong>Nama Lengkap</strong></td>
                                <td>: <?= $peserta->nama ?></td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Kelamin</strong></td>
                                <td>: 
                                    <span class="badge badge-<?= ($peserta->jns_klm == 'L') ? 'info' : 'warning' ?>">
                                        <?= ($peserta->jns_klm == 'L') ? 'Laki-laki' : 'Perempuan' ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tempat Lahir</strong></td>
                                <td>: <?= $peserta->tmp_lahir ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Lahir</strong></td>
                                <td>: <?= $peserta->tgl_lahir ? date('d/m/Y', strtotime($peserta->tgl_lahir)) : '-' ?></td>
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
                                <td><strong>Platform</strong></td>
                                <td>: <?= $peserta->id_platform ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Event</strong></td>
                                <td>: <?= $peserta->id_event ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Penjualan</strong></td>
                                <td>: <?= $peserta->id_penjualan ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td>: <?= $peserta->alamat ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>: 
                                    <span class="badge badge-<?= ($peserta->status == 1) ? 'success' : 'danger' ?>">
                                        <?= ($peserta->status == 1) ? 'Aktif' : 'Tidak Aktif' ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status Hadir</strong></td>
                                <td>: 
                                    <span class="badge badge-<?= ($peserta->status_hadir == '1') ? 'primary' : 'secondary' ?>">
                                        <?= ($peserta->status_hadir == '1') ? 'Hadir' : 'Tidak Hadir' ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Dibuat</strong></td>
                                <td>: <?= $peserta->created_at ? date('d/m/Y H:i', strtotime($peserta->created_at)) : '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Diupdate</strong></td>
                                <td>: <?= $peserta->updated_at ? date('d/m/Y H:i', strtotime($peserta->updated_at)) : '-' ?></td>
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
function downloadQR() {
    const qrContainer = document.getElementById('participant-qr');
    if (qrContainer) {
        const img = qrContainer.querySelector('img');
        if (img) {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = img.naturalWidth;
            canvas.height = img.naturalHeight;
            ctx.drawImage(img, 0, 0);
            canvas.toBlob(function(blob) {
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.download = 'qr-code-<?= $peserta->kode ?>.png';
                link.href = url;
                link.click();
                URL.revokeObjectURL(url);
            }, 'image/png');
        } else {
            alert('QR Code tidak tersedia untuk diunduh');
        }
    }
}
window.downloadQR = downloadQR;
</script> 