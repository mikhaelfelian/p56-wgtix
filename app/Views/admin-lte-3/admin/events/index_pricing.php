<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>

<!-- Statistics Cards -->
<?php if (isset($statistics) && $statistics): ?>
<div class="row mb-3">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $statistics['total_pricing'] ?? 0 ?></h3>
                <p>Total Harga</p>
            </div>
            <div class="icon">
                <i class="fas fa-tag"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $statistics['active_pricing'] ?? 0 ?></h3>
                <p>Harga Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $statistics['total_events'] ?? 0 ?></h3>
                <p>Total Event</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>Rp <?= number_format($statistics['average_price'] ?? 0, 0, ',', '.') ?></h3>
                <p>Rata-rata Harga</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>
</div>
<?php endif ?>

<div class="row">
    <div class="col-12">
        <div class="card rounded-0">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="card-title"><?= $title ?? 'Kelola Harga Event' ?></h3>
                    </div>
                    <div class="col-md-6">
                        <ol class="breadcrumb float-sm-right mb-0 bg-transparent p-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active"><?= $title ?? 'Harga Event' ?></li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <?= form_open('', ['method' => 'get', 'class' => 'float-left']) ?>
                        <div class="input-group input-group-sm">
                            <?= form_input([
                                'name' => 'keyword',
                                'class' => 'form-control rounded-0',
                                'value' => $keyword ?? '',
                                'placeholder' => 'Cari event...'
                            ]) ?>
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-primary rounded-0" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <?php if (!empty($keyword)): ?>
                                    <a href="<?= base_url('admin/event-pricing') ?>" class="btn btn-sm btn-secondary rounded-0" title="Hapus Pencarian">
                                        <i class="fas fa-times"></i>
                                    </a>
                                <?php endif ?>
                            </div>
                        </div>
                        <?= form_close() ?>
                    </div>
                    <div class="col-md-6 text-right">
                        <small class="text-muted">
                            <?php if (!empty($keyword)): ?>
                                untuk pencarian "<strong><?= esc($keyword) ?></strong>"
                            <?php endif ?>
                        </small>
                    </div>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Event</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pricing)): ?>
                            <?php foreach ($pricing as $key => $row): ?>
                                <tr>
                                    <td><?= ($currentPage - 1) * $perPage + $key + 1 ?></td>
                                    <td>
                                        <strong><?= esc($row->event ?? 'Event tidak ditemukan') ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?php if (isset($row->tgl_masuk) && isset($row->tgl_keluar)): ?>
                                                <?= tgl_indo5($row->tgl_masuk) ?> - 
                                                <?= tgl_indo5($row->tgl_keluar) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Tanggal tidak tersedia</span>
                                            <?php endif ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="text-muted">-</span>
                                    </td>
                                    <td>
                                        <strong class="text-success">
                                            Rp <?= number_format($row->harga ?? 0, 0, ',', '.') ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= ($row->status == '1') ? 'success' : 'danger' ?>">
                                            <?= ($row->status == '1') ? 'Aktif' : 'Tidak Aktif' ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-tag fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Belum ada data harga event</h5>
                                        <p class="text-muted">Silakan tambah data harga untuk event yang tersedia</p>
                                        <a href="<?= base_url('admin/events') ?>" class="btn btn-primary rounded-0">
                                            <i class="fas fa-calendar"></i> Lihat Event
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <?php if (isset($pager) && $pager->pageCount > 1): ?>
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">
                            Halaman <?= $currentPage ?> dari <?= $pager->pageCount ?>
                        </small>
                    </div>
                    <div class="col-md-6">
                        <?= $pager->getLinks() ?>
                    </div>
                </div>
                <?php endif ?>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

<?= $this->endSection() ?>
