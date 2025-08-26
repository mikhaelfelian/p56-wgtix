<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card rounded-0">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <a href="<?= base_url('admin/master/platform/create') ?>" class="btn btn-sm btn-primary rounded-0">
                            <i class="fas fa-plus"></i> Tambah Data
                        </a>
                        <?php if ($trashCount > 0): ?>
                            <a href="<?= base_url('admin/master/platform/trash') ?>" class="btn btn-sm btn-danger rounded-0">
                                <i class="fas fa-trash"></i> Arsip (<?= $trashCount ?>)
                            </a>
                        <?php endif ?>
                    </div>
                    <div class="col-md-6">
                        <?= form_open('', ['method' => 'get', 'class' => 'float-right']) ?>
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
                            </div>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Platform</th>
                            <th>Jenis</th>
                            <th>Kategori</th>
                            <th>Nama Rekening</th>
                            <th>Nomor Rekening</th>
                            <th>Status</th>
                            <th>Gateway</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($platform as $key => $row): ?>
                            <tr>
                                <td><?= (($currentPage - 1) * $perPage) + $key + 1 ?></td>
                                <td><?= esc($row->nama) ?></td>
                                <td><?= esc($row->jenis) ?></td>
                                <td><?= esc($row->kategori_name ?? $row->kategori) ?></td>
                                <td><?= esc($row->nama_rekening) ?></td>
                                <td><?= esc($row->nomor_rekening) ?></td>
                                <td>
                                    <?php if ($row->status == 1): ?>
                                        <span class="badge badge-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Tidak Aktif</span>
                                    <?php endif ?>
                                </td>
                                <td>
                                    <?php if ($row->status_gateway == '1'): ?>
                                        <span class="badge badge-info">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Tidak Aktif</span>
                                    <?php endif ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= base_url("admin/master/platform/show/$row->id") ?>"
                                            class="btn btn-info btn-sm rounded-0">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url("admin/master/platform/edit/$row->id") ?>"
                                            class="btn btn-warning btn-sm rounded-0">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url("admin/master/platform/delete/$row->id") ?>"
                                            class="btn btn-danger btn-sm rounded-0"
                                            onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        <?php if (empty($platform)): ?>
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data</td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <?php if ($pager): ?>
                <div class="card-footer clearfix">
                    <div class="float-right">
                        <?= $pager->links('platform', 'adminlte_pagination') ?>
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