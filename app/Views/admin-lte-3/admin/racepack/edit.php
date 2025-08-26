<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Racepack</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/racepack') ?>">Data Racepack</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Racepack</h3>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('errors')) : ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-ban"></i> Error!</h5>
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?= form_open_multipart('admin/racepack/update/' . $racepack->id) ?>
                    <div class="form-group">
                        <label for="kode_racepack">Kode Racepack <span class="text-danger">*</span></label>
                        <?= form_input([
                            'type' => 'text',
                            'name' => 'kode_racepack',
                            'id' => 'kode_racepack',
                            'class' => 'form-control',
                            'value' => old('kode_racepack') ?? $racepack->kode_racepack,
                            'placeholder' => 'Masukkan kode racepack',
                            'required' => 'required'
                        ]) ?>
                    </div>

                    <div class="form-group">
                        <label for="nama_racepack">Nama Racepack <span class="text-danger">*</span></label>
                        <?= form_input([
                            'type' => 'text',
                            'name' => 'nama_racepack',
                            'id' => 'nama_racepack',
                            'class' => 'form-control',
                            'value' => old('nama_racepack') ?? $racepack->nama_racepack,
                            'placeholder' => 'Masukkan nama racepack',
                            'required' => 'required'
                        ]) ?>
                    </div>

                    <div class="form-group">
                        <label for="id_kategori">Kategori <span class="text-danger">*</span></label>
                        <?= form_dropdown([
                            'name' => 'id_kategori',
                            'id' => 'id_kategori',
                            'class' => 'form-control',
                            'required' => 'required'
                        ], $kategoriOptions, old('id_kategori') ?? $racepack->id_kategori) ?>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <?= form_textarea([
                            'name' => 'deskripsi',
                            'id' => 'deskripsi',
                            'class' => 'form-control',
                            'rows' => '3',
                            'value' => old('deskripsi') ?? $racepack->deskripsi,
                            'placeholder' => 'Masukkan deskripsi racepack'
                        ]) ?>
                    </div>

                    <div class="form-group">
                        <label for="harga">Harga <span class="text-danger">*</span></label>
                        <?= form_input([
                            'type' => 'number',
                            'name' => 'harga',
                            'id' => 'harga',
                            'class' => 'form-control',
                            'value' => old('harga') ?? $racepack->harga,
                            'placeholder' => 'Masukkan harga',
                            'required' => 'required'
                        ]) ?>
                    </div>

                    <div class="form-group">
                        <label for="gambar">Gambar</label>
                        <?php if ($racepack->gambar) : ?>
                            <div class="mb-2">
                                <img src="<?= base_url('uploads/racepack/' . $racepack->gambar) ?>" 
                                     alt="Gambar Racepack" 
                                     style="max-width: 200px; height: auto;" class="img-thumbnail">
                            </div>
                        <?php endif; ?>
                        <?= form_upload([
                            'name' => 'gambar',
                            'id' => 'gambar',
                            'class' => 'form-control-file',
                            'accept' => 'image/*'
                        ]) ?>
                        <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</small>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <?= form_dropdown([
                            'name' => 'status',
                            'id' => 'status',
                            'class' => 'form-control'
                        ], [
                            '1' => 'Aktif',
                            '0' => 'Nonaktif'
                        ], old('status') ?? $racepack->status) ?>
                    </div>

                    <div class="form-group">
                        <a href="<?= base_url('admin/racepack') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                    </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 