<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Stock Racepack</h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/stock-racepack/low-stock') ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-exclamation-triangle"></i> Stok Menipis
                    </a>
                    <a href="<?= base_url('admin/stock-racepack/create') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Stock
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Racepack</th>
                                <th>Ukuran</th>
                                <th>Stok Masuk</th>
                                <th>Stok Keluar</th>
                                <th>Stok Tersedia</th>
                                <th>Minimal Stok</th>
                                <th>Status</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($stock)): ?>
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; ?>
                                <?php foreach ($stock as $row): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($row->nama_racepack) ?></td>
                                        <td><?= esc($row->ukuran) ?></td>
                                        <td><?= number_format($row->stok_masuk) ?></td>
                                        <td><?= number_format($row->stok_keluar) ?></td>
                                        <td>
                                            <span
                                                class="badge badge-<?= $row->stok_tersedia <= $row->minimal_stok ? 'danger' : 'success' ?>">
                                                <?= number_format($row->stok_tersedia) ?>
                                            </span>
                                        </td>
                                        <td><?= number_format($row->minimal_stok) ?></td>
                                        <td>
                                            <?php if ($row->status == 1): ?>
                                                <span class="badge badge-success">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Nonaktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('admin/stock-racepack/edit/' . $row->id) ?>"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('admin/stock-racepack/delete/' . $row->id) ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                <i class="fas fa-trash"></i>
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
<?= $this->endSection() ?>