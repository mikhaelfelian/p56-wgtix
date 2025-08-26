<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card rounded-0">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="card-title"><?= $title ?? 'Kelola Galeri Event' ?></h3>
                    </div>
                    <div class="col-md-6">
                        <ol class="breadcrumb float-sm-right mb-0 bg-transparent p-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active"><?= $title ?? 'Galeri Event' ?></li>
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
                                'placeholder' => 'Cari event atau judul galeri...'
                            ]) ?>
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-primary rounded-0" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <?php if (!empty($keyword)): ?>
                                    <a href="<?= base_url('admin/event-gallery') ?>" class="btn btn-sm btn-secondary rounded-0" title="Hapus Pencarian">
                                        <i class="fas fa-times"></i>
                                    </a>
                                <?php endif ?>
                            </div>
                        </div>
                        <?= form_close() ?>
                    </div>
                    <div class="col-md-6 text-right">
                    </div>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Event</th>
                            <th>Judul Galeri</th>
                            <th>Gambar</th>
                            <th>Status</th>
                            <th width="160">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($galleries)): ?>
                            <?php foreach ($galleries as $key => $row): ?>
                                <tr>
                                    <td><?= ($currentPage - 1) * $perPage + $key + 1 ?></td>
                                    <td>
                                        <strong><?= esc($row->event_name) ?></strong>
                                    </td>
                                    <td>
                                        <?= esc($row->deskripsi) ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row->file) && file_exists(FCPATH . 'file/events/' . $row->id_event . '/gallery/' . $row->file)): ?>
                                            <img src="<?= base_url('public/file/events/' . $row->id_event . '/gallery/' . $row->file) ?>" alt="<?= esc($row->deskripsi) ?>" class="rounded-0" style="width:40px; height:40px; object-fit:cover; display:block;">
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= ($row->status == '1') ? 'success' : 'secondary' ?>">
                                            <?= ($row->status == '1') ? 'Aktif' : 'Tidak Aktif' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= base_url("admin/event-gallery/edit/$row->id") ?>"
                                                class="btn btn-warning btn-sm rounded-0" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url("admin/event-gallery/delete/$row->id") ?>"
                                                class="btn btn-danger btn-sm rounded-0"
                                                onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-images fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Belum ada data galeri event</h5>
                                        <p class="text-muted">Silakan tambah galeri untuk event yang tersedia</p>
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
            <?php if (isset($pager) && $pager): ?>
                <div class="card-footer clearfix">
                    <div class="float-right">
                        <?= $pager->links('galleries', 'adminlte_pagination') ?>
                    </div>
                </div>
            <?php endif; ?>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

<?= $this->endSection() ?>
