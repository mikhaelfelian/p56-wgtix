<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Racepack</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/racepack') ?>">Data Racepack</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Racepack</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/racepack/edit/' . $racepack->id) ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="<?= base_url('admin/racepack') ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%"><strong>Kode Racepack</strong></td>
                                    <td width="5%">:</td>
                                    <td><?= esc($racepack->kode_racepack) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Racepack</strong></td>
                                    <td>:</td>
                                    <td><?= esc($racepack->nama_racepack) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Kategori</strong></td>
                                    <td>:</td>
                                    <td><?= esc($racepack->nama_kategori) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Deskripsi</strong></td>
                                    <td>:</td>
                                    <td><?= esc($racepack->deskripsi) ?: '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Harga</strong></td>
                                    <td>:</td>
                                    <td>Rp <?= number_format($racepack->harga, 0, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>:</td>
                                    <td>
                                        <?php if ($racepack->status == 1) : ?>
                                            <span class="badge badge-success">Aktif</span>
                                        <?php else : ?>
                                            <span class="badge badge-danger">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat Pada</strong></td>
                                    <td>:</td>
                                    <td><?= date('d/m/Y H:i', strtotime($racepack->created_at)) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Terakhir Diupdate</strong></td>
                                    <td>:</td>
                                    <td><?= date('d/m/Y H:i', strtotime($racepack->updated_at)) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <?php if ($racepack->gambar) : ?>
                                <div class="text-center">
                                    <h6><strong>Gambar Racepack</strong></h6>
                                    <img src="<?= base_url('uploads/racepack/' . $racepack->gambar) ?>" 
                                         alt="Gambar Racepack" 
                                         class="img-fluid img-thumbnail" 
                                         style="max-width: 300px;">
                                </div>
                            <?php else : ?>
                                <div class="text-center">
                                    <h6><strong>Gambar Racepack</strong></h6>
                                    <div class="bg-light p-4">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                        <br><small class="text-muted">Tidak ada gambar</small>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 