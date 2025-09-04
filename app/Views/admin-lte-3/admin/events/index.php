<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>

<!-- Statistics Cards -->
<?php if (isset($statistics) && $statistics): ?>
<div class="row mb-3">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
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
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $statistics['active_events'] ?? 0 ?></h3>
                <p>Event Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $statistics['upcoming_events'] ?? 0 ?></h3>
                <p>Event Mendatang</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3><?= $statistics['past_events'] ?? 0 ?></h3>
                <p>Event Berlalu</p>
            </div>
            <div class="icon">
                <i class="fas fa-history"></i>
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
                        <a href="<?= base_url('admin/events/create') ?>" class="btn btn-sm btn-primary rounded-0">
                            <i class="fas fa-plus"></i> Tambah Data
                        </a>
                    </div>
                    <div class="col-md-6">
                        <ol class="breadcrumb float-sm-right mb-0 bg-transparent p-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active"><?= $title ?? 'Events' ?></li>
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
                                'placeholder' => 'Cari...'
                            ]) ?>
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-primary rounded-0" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <?php if (!empty($keyword)): ?>
                                    <a href="<?= base_url('admin/events') ?>" class="btn btn-sm btn-secondary rounded-0" title="Hapus Pencarian">
                                        <i class="fas fa-times"></i>
                                    </a>
                                <?php endif ?>
                            </div>
                        </div>
                        <?= form_close() ?>
                    </div>
                    <div class="col-md-6 text-right">
                        <small class="text-muted">
                            Menampilkan <?= count($events) ?> dari <?= $total ?? 0 ?> data
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
                            <th>Kode</th>
                            <th>Event</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th width="160">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $key => $row): ?>
                            <tr>
                                <td><?= (($currentPage - 1) * $perPage) + $key + 1 ?></td>
                                <td><?= esc($row->kode) ?></td>
                                <td><?= esc($row->event) ?></td>
                                <td><?= date('d-m-Y', strtotime($row->tgl_masuk)) ?></td>
                                <td><?= date('d-m-Y', strtotime($row->tgl_keluar)) ?></td>
                                <td><?= esc($row->lokasi) ?></td>
                                <td>
                                    <span class="badge badge-<?= ($row->status == '1') ? 'success' : 'danger' ?>">
                                        <?= ($row->status == '1') ? 'Aktif' : 'Tidak Aktif' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= base_url("admin/events/view/$row->id") ?>"
                                            class="btn btn-primary btn-sm rounded-0" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url("admin/events/edit/$row->id") ?>"
                                            class="btn btn-warning btn-sm rounded-0" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url("admin/events/delete/$row->id") ?>"
                                            class="btn btn-danger btn-sm rounded-0"
                                            onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <a href="<?= base_url("admin/event-gallery/manage/$row->id") ?>"
                                            class="btn btn-info btn-sm rounded-0" title="Galeri">
                                            <i class="fas fa-images"></i>
                                        </a>
                                        <a href="<?= base_url("admin/events/pricing/$row->id") ?>"
                                            class="btn btn-success btn-sm rounded-0" title="Harga">
                                            <i class="fas fa-tag"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        <?php if (empty($events)): ?>
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data</td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <?php if (isset($pager) && $pager): ?>
                <div class="card-footer clearfix">
                    <div class="float-right">
                        <?= $pager->links('events', 'adminlte_pagination') ?>
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
