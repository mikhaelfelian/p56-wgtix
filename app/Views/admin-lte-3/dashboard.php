<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<!-- Welcome Section -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tachometer-alt mr-2"></i>
                    Dashboard
                </h3>
            </div>
            <div class="card-body">
                <div class="callout callout-info">
                    <h5><i class="fas fa-info mr-2"></i> Selamat Datang!</h5>
                    Selamat datang di sistem Event Management. Dashboard ini menampilkan ringkasan data dari semua modul sistem.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Info Boxes -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Peserta</span>
                <span class="info-box-number"><?= number_format($stats['total_peserta']) ?></span>
                <small class="text-muted">Aktif: <?= number_format($stats['active_peserta']) ?></small>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-tshirt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Racepack</span>
                <span class="info-box-number"><?= number_format($stats['total_racepack']) ?></span>
                <small class="text-muted">Aktif: <?= number_format($stats['active_racepack']) ?></small>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-exclamation-triangle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Stok Menipis</span>
                <span class="info-box-number"><?= count($stats['low_stock_items']) ?></span>
                <small class="text-muted">Perlu restock</small>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-calendar-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Jadwal</span>
                <span class="info-box-number"><?= number_format($stats['total_jadwal']) ?></span>
                <small class="text-muted">Event terdaftar</small>
            </div>
        </div>
    </div>
</div>

<!-- Additional Statistics Row -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-tags"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Kategori</span>
                <span class="info-box-number"><?= number_format($stats['total_kategori']) ?></span>
                <small class="text-muted">Master data</small>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-dark elevation-1"><i class="fas fa-layer-group"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Kelompok</span>
                <span class="info-box-number"><?= number_format($stats['total_kelompok']) ?></span>
                <small class="text-muted">Grup peserta</small>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-credit-card"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Platform</span>
                <span class="info-box-number"><?= number_format($stats['total_platform']) ?></span>
                <small class="text-muted">Metode pembayaran</small>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-light elevation-1"><i class="fas fa-ruler"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Ukuran</span>
                <span class="info-box-number"><?= number_format($stats['total_ukuran']) ?></span>
                <small class="text-muted">Master data</small>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-rocket mr-2"></i>
                    Aksi Cepat
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <a href="<?= base_url('admin/peserta/daftar') ?>" class="btn btn-success btn-block mb-2">
                            <i class="fas fa-eye mr-2"></i> Daftar Peserta
                        </a>
                        <a href="<?= base_url('admin/racepack/create') ?>" class="btn btn-info btn-block mb-2">
                            <i class="fas fa-tshirt mr-2"></i> Tambah Racepack
                        </a>
                        <a href="<?= base_url('admin/stock-racepack/create') ?>" class="btn btn-warning btn-block">
                            <i class="fas fa-boxes mr-2"></i> Kelola Stok
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= base_url('admin/kategori/create') ?>" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-tags mr-2"></i> Tambah Kategori
                        </a>
                        <a href="<?= base_url('admin/peserta/kelompok/create') ?>" class="btn btn-secondary btn-block mb-2">
                            <i class="fas fa-layer-group mr-2"></i> Tambah Kelompok
                        </a>
                        <a href="<?= base_url('admin/stock-racepack/low-stock') ?>" class="btn btn-danger btn-block">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Stok Menipis
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
    </div>
</div>
<?= $this->endSection() ?>