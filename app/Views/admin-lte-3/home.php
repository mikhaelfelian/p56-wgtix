<?= $this->extend(theme_path('main_front')) ?>

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
                    Selamat datang di sistem POS & Inventory Management. Dashboard ini siap untuk dikustomisasi sesuai kebutuhan Anda.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Info boxes with static data -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Penjualan Lunas</span>
                <span class="info-box-number">--</span>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-money-bill-wave"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Pendapatan</span>
                <span class="info-box-number">Rp --</span>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-shopping-bag"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pembelian Lunas</span>
                <span class="info-box-number">--</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-chart-line"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Laba</span>
                <span class="info-box-number">Rp --</span>
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
                        <a href="<?= base_url('transaksi/jual/cashier') ?>" class="btn btn-success btn-block mb-2">
                            <i class="fas fa-cash-register mr-2"></i> Kasir Baru
                        </a>
                        <a href="<?= base_url('master/item') ?>" class="btn btn-info btn-block">
                            <i class="fas fa-box mr-2"></i> Kelola Produk
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= base_url('transaksi/beli/create') ?>" class="btn btn-danger btn-block mb-2">
                            <i class="fas fa-shopping-bag mr-2"></i> Pembelian Baru
                        </a>
                        <a href="<?= base_url('master/pelanggan') ?>" class="btn btn-warning btn-block">
                            <i class="fas fa-users mr-2"></i> Kelola Pelanggan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-2"></i>
                    Informasi Sistem
                </h3>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><strong>Versi CodeIgniter:</strong> 4.x</li>
                    <li><strong>Framework:</strong> AdminLTE 3</li>
                    <li><strong>Status:</strong> <span class="badge badge-success">Aktif</span></li>
                    <li><strong>User:</strong> <?= $user->username ?? 'Admin' ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>