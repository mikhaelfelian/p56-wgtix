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
                            <h3 class="card-title">Form Edit Kategori</h3>
                        </div>
                        <div class="card-body">
                            <?php if (session('error')) : ?>
                                <div class="alert alert-danger">
                                    <?= session('error') ?>
                                </div>
                            <?php endif; ?>

                            <?php if (session('errors')) : ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach (session('errors') as $error) : ?>
                                            <li><?= $error ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <?= form_open('admin/kategori/update/' . $kategori->id) ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kategori">Nama Kategori <span class="text-danger">*</span></label>
                                        <input type="text" name="kategori" id="kategori" class="form-control <?= session('errors.kategori') ? 'is-invalid' : '' ?>" value="<?= old('kategori', $kategori->kategori) ?>" placeholder="Masukkan nama kategori" required>
                                        <?php if (session('errors.kategori')) : ?>
                                            <div class="invalid-feedback">
                                                <?= session('errors.kategori') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kode">Kode Kategori</label>
                                        <input type="text" name="kode" id="kode" class="form-control <?= session('errors.kode') ? 'is-invalid' : '' ?>" value="<?= old('kode', $kategori->kode) ?>" placeholder="Masukkan kode kategori (opsional)">
                                        <?php if (session('errors.kode')) : ?>
                                            <div class="invalid-feedback">
                                                <?= session('errors.kode') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea name="keterangan" id="keterangan" class="form-control <?= session('errors.keterangan') ? 'is-invalid' : '' ?>" rows="3" placeholder="Masukkan keterangan kategori (opsional)"><?= old('keterangan', $kategori->keterangan) ?></textarea>
                                        <?php if (session('errors.keterangan')) : ?>
                                            <div class="invalid-feedback">
                                                <?= session('errors.keterangan') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control <?= session('errors.status') ? 'is-invalid' : '' ?>" required>
                                            <option value="">Pilih Status</option>
                                            <option value="1" <?= (old('status', $kategori->status) == '1') ? 'selected' : '' ?>>Aktif</option>
                                            <option value="0" <?= (old('status', $kategori->status) == '0') ? 'selected' : '' ?>>Tidak Aktif</option>
                                        </select>
                                        <?php if (session('errors.status')) : ?>
                                            <div class="invalid-feedback">
                                                <?= session('errors.status') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update
                                </button>
                                <a href="<?= base_url('admin/kategori') ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?> 