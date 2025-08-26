<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8">
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">Detail Platform</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="200"><strong>Nama Platform</strong></td>
                        <td>: <?= esc($platform->nama) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Jenis</strong></td>
                        <td>: <?= esc($platform->jenis) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Kategori</strong></td>
                        <td>: <?= esc($platform->kategori_name ?? $platform->kategori) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nama Rekening</strong></td>
                        <td>: <?= esc($platform->nama_rekening) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nomor Rekening</strong></td>
                        <td>: <?= esc($platform->nomor_rekening) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Deskripsi</strong></td>
                        <td>: <?= esc($platform->deskripsi) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Gateway Kode</strong></td>
                        <td>: <?= esc($platform->gateway_kode) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Gateway Instruksi</strong></td>
                        <td>: 
                            <?php if ($platform->gateway_instruksi == '1'): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Tidak Aktif</span>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Logo</strong></td>
                        <td>: <?= esc($platform->logo) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Hasil</strong></td>
                        <td>: <?= esc($platform->hasil) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>: 
                            <?php if ($platform->status == 1): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Tidak Aktif</span>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Status Gateway</strong></td>
                        <td>: 
                            <?php if ($platform->status_gateway == '1'): ?>
                                <span class="badge badge-info">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Tidak Aktif</span>
                            <?php endif ?>
                        </td>
                    </tr>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-left">
                <a href="<?= base_url('master/platform') ?>" class="btn btn-default rounded-0">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <a href="<?= base_url('master/platform/edit/' . $platform->id) ?>" class="btn btn-warning rounded-0 float-right">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
            </div>
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
<?= $this->endSection() ?>