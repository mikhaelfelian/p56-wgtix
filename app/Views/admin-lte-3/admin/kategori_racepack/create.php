<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Tambah Kategori Racepack</h3>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?= form_open('admin/kategori-racepack/store') ?>
                <div class="form-group">
                    <label for="nama_kategori">Nama Kategori <span class="text-danger">*</span></label>
                    <?= form_input([
                        'type' => 'text',
                        'name' => 'nama_kategori',
                        'id' => 'nama_kategori',
                        'class' => 'form-control',
                        'value' => old('nama_kategori') ?? '',
                        'placeholder' => 'Masukkan nama kategori',
                        'required' => 'required'
                    ]) ?>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <?= form_textarea([
                        'name' => 'deskripsi',
                        'id' => 'deskripsi',
                        'class' => 'form-control',
                        'rows' => '3',
                        'value' => old('deskripsi') ?? '',
                        'placeholder' => 'Masukkan deskripsi kategori'
                    ]) ?>
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
                    ], old('status') ?? '1') ?>
                </div>

                <div class="form-group">
                    <a href="<?= base_url('admin/kategori-racepack') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>