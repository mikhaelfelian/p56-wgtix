<?= $this->extend('admin-lte-3/layout/default') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Stok Menipis</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/stock-racepack') ?>">Stock Racepack</a></li>
                        <li class="breadcrumb-item active">Stok Menipis</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Stok Menipis</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/stock-racepack') ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Peringatan!</h5>
                        Berikut adalah daftar racepack dengan stok yang sudah menipis atau di bawah minimal stok.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Racepack</th>
                                    <th>Ukuran</th>
                                    <th>Stok Tersedia</th>
                                    <th>Minimal Stok</th>
                                    <th>Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($lowStock)) : ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data stok menipis</td>
                                    </tr>
                                <?php else : ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($lowStock as $row) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= esc($row->nama_racepack) ?></td>
                                            <td><?= esc($row->ukuran) ?></td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    <?= number_format($row->stok_tersedia) ?>
                                                </span>
                                            </td>
                                            <td><?= number_format($row->minimal_stok) ?></td>
                                            <td>
                                                <?php if ($row->status == 1) : ?>
                                                    <span class="badge badge-success">Aktif</span>
                                                <?php else : ?>
                                                    <span class="badge badge-danger">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('admin/stock-racepack/edit/' . $row->id) ?>" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i> Update Stok
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 