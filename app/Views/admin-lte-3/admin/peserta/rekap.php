<?= $this->extend('admin-lte-3/layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <!-- Peserta Statistics -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $pesertaStats->total ?></h3>
                <p>Total Peserta</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="<?= base_url('admin/peserta/daftar') ?>" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $pesertaStats->active ?></h3>
                <p>Peserta Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
            <a href="<?= base_url('admin/peserta/daftar') ?>" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $pesertaStats->male ?></h3>
                <p>Laki-laki</p>
            </div>
            <div class="icon">
                <i class="fas fa-male"></i>
            </div>
            <a href="<?= base_url('admin/peserta/daftar') ?>" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $pesertaStats->female ?></h3>
                <p>Perempuan</p>
            </div>
            <div class="icon">
                <i class="fas fa-female"></i>
            </div>
            <a href="<?= base_url('admin/peserta/daftar') ?>" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Kelompok Statistics -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3><?= $kelompokStats->total ?></h3>
                <p>Total Kelompok</p>
            </div>
            <div class="icon">
                <i class="fas fa-layer-group"></i>
            </div>
            <a href="<?= base_url('admin/peserta/kelompok') ?>" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $kelompokStats->active ?></h3>
                <p>Kelompok Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="<?= base_url('admin/peserta/kelompok') ?>" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= count($recentPendaftaran) ?></h3>
                <p>Pendaftaran Terbaru</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <a href="<?= base_url('admin/peserta/pendaftaran') ?>" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= date('d/m/Y') ?></h3>
                <p>Tanggal Hari Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar"></i>
            </div>
            <a href="#" class="small-box-footer">
                Update <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Registrations -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clock mr-1"></i>
                    Pendaftaran Terbaru
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th>Peserta</th>
                                <th>Jadwal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentPendaftaran as $row): ?>
                                <tr>
                                    <td><?= $row->nama_peserta ?></td>
                                    <td><?= $row->nama_jadwal ?></td>
                                    <td>
                                        <?php
                                        $statusClass = 'secondary';
                                        $statusText = 'Unknown';
                                        switch ($row->status_pendaftaran) {
                                            case 'pending':
                                                $statusClass = 'warning';
                                                $statusText = 'Pending';
                                                break;
                                            case 'approved':
                                                $statusClass = 'success';
                                                $statusText = 'Disetujui';
                                                break;
                                            case 'rejected':
                                                $statusClass = 'danger';
                                                $statusText = 'Ditolak';
                                                break;
                                            case 'cancelled':
                                                $statusClass = 'secondary';
                                                $statusText = 'Dibatalkan';
                                                break;
                                        }
                                        ?>
                                        <span class="badge badge-<?= $statusClass ?>">
                                            <?= $statusText ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            <?php if (empty($recentPendaftaran)): ?>
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data</td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Gender Distribution -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-1"></i>
                    Distribusi Jenis Kelamin
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-male"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Laki-laki</span>
                                <span class="info-box-number"><?= $pesertaStats->male ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-female"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Perempuan</span>
                                <span class="info-box-number"><?= $pesertaStats->female ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Registration Status -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-1"></i>
                    Status Pendaftaran
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pending</span>
                                <span class="info-box-number">
                                    <?php 
                                    $pendingCount = 0;
                                    foreach ($pendaftaranByStatus as $status) {
                                        if ($status->status_pendaftaran == 'pending') {
                                            $pendingCount = $status->jumlah;
                                            break;
                                        }
                                    }
                                    echo $pendingCount;
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Disetujui</span>
                                <span class="info-box-number">
                                    <?php 
                                    $approvedCount = 0;
                                    foreach ($pendaftaranByStatus as $status) {
                                        if ($status->status_pendaftaran == 'approved') {
                                            $approvedCount = $status->jumlah;
                                            break;
                                        }
                                    }
                                    echo $approvedCount;
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-times"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Ditolak</span>
                                <span class="info-box-number">
                                    <?php 
                                    $rejectedCount = 0;
                                    foreach ($pendaftaranByStatus as $status) {
                                        if ($status->status_pendaftaran == 'rejected') {
                                            $rejectedCount = $status->jumlah;
                                            break;
                                        }
                                    }
                                    echo $rejectedCount;
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-secondary"><i class="fas fa-ban"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Dibatalkan</span>
                                <span class="info-box-number">
                                    <?php 
                                    $cancelledCount = 0;
                                    foreach ($pendaftaranByStatus as $status) {
                                        if ($status->status_pendaftaran == 'cancelled') {
                                            $cancelledCount = $status->jumlah;
                                            break;
                                        }
                                    }
                                    echo $cancelledCount;
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 