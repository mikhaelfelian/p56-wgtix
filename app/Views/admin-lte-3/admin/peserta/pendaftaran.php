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
                            <a href="<?= base_url('admin/peserta/create') ?>" class="btn btn-sm btn-primary rounded-0">
                                <i class="fas fa-plus"></i> Tambah Pendaftaran
                            </a>
                        </div>
                        <div class="col-md-6">
                            <?= form_open('', ['method' => 'get', 'class' => 'float-right']) ?>
                            <div class="input-group input-group-sm">
                                <?= form_input([
                                    'name' => 'search',
                                    'class' => 'form-control rounded-0',
                                    'value' => $keyword ?? '',
                                    'placeholder' => 'Cari pendaftaran...'
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
                            <th>Kode Pendaftaran</th>
                            <th>Nama Peserta</th>
                            <th>Jadwal</th>
                            <th>Tanggal Pendaftaran</th>
                            <th>Status</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendaftaran as $key => $row): ?>
                            <tr>
                                <td><?= (($currentPage - 1) * $perPage) + $key + 1 ?></td>
                                <td><?= $row->kode_pendaftaran ?></td>
                                <td><?= $row->nama_peserta ?></td>
                                <td>
                                    <?= $row->nama_jadwal ?><br>
                                    <small class="text-muted">
                                        <?= date('d/m/Y', strtotime($row->tanggal_mulai)) ?>
                                        <?php if ($row->tanggal_mulai != $row->tanggal_selesai): ?>
                                            - <?= date('d/m/Y', strtotime($row->tanggal_selesai)) ?>
                                        <?php endif ?>
                                    </small>
                                </td>
                                <td><?= date('d/m/Y', strtotime($row->tanggal_pendaftaran)) ?></td>
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
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= base_url("admin/peserta/show/$row->id") ?>"
                                            class="btn btn-info btn-sm rounded-0">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url("admin/peserta/edit/$row->id") ?>"
                                            class="btn btn-warning btn-sm rounded-0">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url("admin/peserta/delete/$row->id") ?>"
                                            class="btn btn-danger btn-sm rounded-0"
                                            onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        <?php if (empty($pendaftaran)): ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data</td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
<?= $this->endSection() ?> 