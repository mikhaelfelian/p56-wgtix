<?= $this->extend('admin-lte-3/layout/main') ?>

<?= $this->section('content') ?>

<?php if (session('success')): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
        <?= session('success') ?>
        
        <?php if (session('generated_code')): ?>
            <div class="mt-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">Kode Peserta: <strong><?= session('generated_code') ?></strong></h6>
                            </div>
                            <div class="card-body text-center">
                                <?php 
                                if (isset($latestPeserta) && $latestPeserta && $latestPeserta->qr_code): 
                                ?>
                                    <div id="new-participant-qr">
                                        <img src="data:image/png;base64,<?= $latestPeserta->qr_code ?>" 
                                             alt="QR Code" 
                                             style="max-width: 150px; height: auto;">
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="downloadNewQR()">
                                        <i class="fas fa-download"></i> Download QR Code
                                    </button>
                                <?php else: ?>
                                    <div id="new-participant-qr">
                                        <i class="fas fa-qrcode fa-3x text-muted"></i>
                                        <br><small class="text-muted">QR Code tidak tersedia</small>
                                    </div>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>
    </div>
<?php endif ?>

<?php if (session('error')): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Error!</h5>
        <?= session('error') ?>
    </div>
<?php endif ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= $title ?></h3>
                <div class="card-tools">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="<?= base_url('admin/peserta/create') ?>" class="btn btn-sm btn-primary rounded-0">
                                <i class="fas fa-plus"></i> Tambah Data
                            </a>
                        </div>
                        <div class="col-md-6">
                            <?= form_open('', ['method' => 'get', 'class' => 'float-right']) ?>
                            <div class="input-group input-group-sm">
                                <?= form_input([
                                    'name' => 'search',
                                    'class' => 'form-control rounded-0',
                                    'value' => $keyword ?? '',
                                    'placeholder' => 'Cari peserta...'
                                ]) ?>
                                <div class="input-group-append">
                                    <button class="btn btn-sm btn-primary rounded-0" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Kode</th>
                            <th>QR Code</th>
                            <th>Nama Lengkap</th>
                            <th>Jenis Kelamin</th>
                            <th>No HP</th>
                            <th>Email</th>
                            <th>Kelompok</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($peserta as $key => $row): ?>
                            <tr>
                                <td><?= (($currentPage - 1) * $perPage) + $key + 1 ?></td>
                                <td><?= $row->kode ?></td>
                                <td>
                                    <?php if ($row->qr_code): ?>
                                        <div class="qr-code-container">
                                            <img src="data:image/png;base64,<?= $row->qr_code ?>" 
                                                 alt="QR Code" 
                                                 style="max-width: 50px; height: auto;">
                                        </div>
                                    <?php else: ?>
                                        <div class="qr-code-container">
                                            <i class="fas fa-qrcode text-muted"></i>
                                        </div>
                                    <?php endif ?>
                                </td>
                                <td><?= $row->nama ?></td>
                                <td>
                                    <span class="badge badge-<?= ($row->jns_klm == 'L') ? 'info' : 'warning' ?>">
                                        <?= ($row->jns_klm == 'L') ? 'Laki-laki' : 'Perempuan' ?>
                                    </span>
                                </td>
                                <td><?= $row->no_hp ?></td>
                                <td><?= $row->email ?></td>
                                <td><?= $row->nama_kelompok ?? '-' ?></td>
                                <td><?= $row->nama_kategori ?? '-' ?></td>
                                <td>
                                    <span class="badge badge-<?= ($row->status == 1) ? 'success' : 'danger' ?>">
                                        <?= ($row->status == 1) ? 'Aktif' : 'Tidak Aktif' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= base_url("admin/peserta/show/$row->id") ?>"
                                            class="btn btn-info btn-sm rounded-0">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url("admin/peserta/edit/$row->id") ?>"
                                            class="btn btn-warning btn-sm rounded-0">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url("admin/peserta/delete/$row->id") ?>"
                                            class="btn btn-danger btn-sm rounded-0"
                                            onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        <?php if (empty($peserta)): ?>
                            <tr>
                                <td colspan="11" class="text-center">Tidak ada data</td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <?php if ($pager): ?>
                <div class="card-footer clearfix">
                    <div class="float-right">
                        <?= $pager->links('peserta', 'adminlte_pagination') ?>
                    </div>
                </div>
            <?php endif ?>
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
<?= $this->endSection() ?>

<script>
// Define downloadNewQR function immediately
function downloadNewQR() {
    const newParticipantQR = document.getElementById('new-participant-qr');
    if (newParticipantQR) {
        const img = newParticipantQR.querySelector('img');
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
                link.download = 'qr-code-<?= session('generated_code') ?>.png';
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
window.downloadNewQR = downloadNewQR;
</script> 