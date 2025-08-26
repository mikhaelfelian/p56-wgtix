<?= $this->extend('admin-lte-3/layout/main') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= $title ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/kategori') ?>">Kategori</a></li>
                        <li class="breadcrumb-item active"><?= $title ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detail Kategori</h3>
                            <div class="card-tools">
                                <a href="<?= base_url('admin/kategori/edit/' . $kategori->id) ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="<?= base_url('admin/kategori') ?>" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="30%"><strong>ID</strong></td>
                                            <td width="5%">:</td>
                                            <td><?= $kategori->id ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kode Kategori</strong></td>
                                            <td>:</td>
                                            <td><?= $kategori->kode ?: '-' ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nama Kategori</strong></td>
                                            <td>:</td>
                                            <td><?= $kategori->kategori ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Keterangan</strong></td>
                                            <td>:</td>
                                            <td><?= $kategori->keterangan ?: '-' ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status</strong></td>
                                            <td>:</td>
                                            <td>
                                                <?php if ($kategori->status == '1') : ?>
                                                    <span class="badge badge-success">Aktif</span>
                                                <?php else : ?>
                                                    <span class="badge badge-danger">Tidak Aktif</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Dibuat Pada</strong></td>
                                            <td>:</td>
                                            <td><?= $kategori->created_at ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Diperbarui Pada</strong></td>
                                            <td>:</td>
                                            <td><?= $kategori->updated_at ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?> 