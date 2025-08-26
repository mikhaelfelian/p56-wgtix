<?= $this->extend('admin-lte-3/layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= $title ?></h3>
                <div class="card-tools">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="<?= base_url('admin/peserta/kelompok/create') ?>" class="btn btn-sm btn-primary rounded-0">
                                <i class="fas fa-plus"></i> Tambah Kelompok
                            </a>
                        </div>
                        <div class="col-md-6">
                            <?= form_open('', ['method' => 'get', 'class' => 'float-right']) ?>
                            <div class="input-group input-group-sm">
                                <?= form_input([
                                    'name' => 'search',
                                    'class' => 'form-control rounded-0',
                                    'value' => $keyword ?? '',
                                    'placeholder' => 'Cari kelompok...'
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
                            <th>Kode Kelompok</th>
                            <th>Nama Kelompok</th>
                            <th>Deskripsi</th>
                            <th>Kapasitas</th>
                            <th>Jumlah Anggota</th>
                            <th>Status</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kelompok as $key => $row): ?>
                            <tr>
                                <td><?= (($currentPage - 1) * $perPage) + $key + 1 ?></td>
                                <td><?= $row->kode_kelompok ?></td>
                                <td><?= $row->nama_kelompok ?></td>
                                <td><?= $row->deskripsi ?? '-' ?></td>
                                <td><?= $row->kapasitas ?? '0' ?></td>
                                <td>
                                    <span class="badge badge-info">
                                        <?= $row->jumlah_anggota ?? '0' ?> anggota
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?= ($row->status == '1') ? 'success' : 'danger' ?>">
                                        <?= ($row->status == '1') ? 'Aktif' : 'Tidak Aktif' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= base_url("admin/peserta/kelompok/edit/$row->id") ?>"
                                            class="btn btn-warning btn-sm rounded-0">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url("admin/peserta/kelompok/delete/$row->id") ?>"
                                            class="btn btn-danger btn-sm rounded-0"
                                            onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        <?php if (empty($kelompok)): ?>
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data</td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <?php if ($pager): ?>
                <div class="card-footer clearfix">
                    <div class="float-right">
                        <?= $pager->links('kelompok', 'adminlte_pagination') ?>
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