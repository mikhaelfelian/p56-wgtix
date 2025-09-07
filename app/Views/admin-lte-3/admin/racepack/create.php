<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">Form Tambah Racepack</h3>
            </div>
            <div class="card-body">
                <?= form_open_multipart('admin/racepack/store') ?>
                <div class="form-group">
                    <label for="kode_racepack">Kode Racepack <span class="text-danger">*</span></label>
                    <?= form_input([
                        'type' => 'text',
                        'name' => 'kode_racepack',
                        'id' => 'kode_racepack',
                        'class' => 'form-control',
                        'value' => old('kode_racepack') ?? '',
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
                        'value' => old('nama_racepack') ?? '',
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
                    ], $kategoriOptions, old('id_kategori') ?? '') ?>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <?= form_textarea([
                        'name' => 'deskripsi',
                        'id' => 'deskripsi',
                        'class' => 'form-control',
                        'rows' => '3',
                        'value' => old('deskripsi') ?? '',
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
                        'value' => old('harga') ?? '',
                        'placeholder' => 'Masukkan harga',
                        'required' => 'required'
                    ]) ?>
                </div>

                <div class="form-group">
                    <label for="gambar">Gambar</label>
                    <?= form_upload([
                        'name' => 'gambar',
                        'id' => 'gambar',
                        'class' => 'form-control-file',
                        'accept' => 'image/*'
                    ]) ?>
                    <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
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
                <?= form_close() ?>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('admin/racepack') ?>" class="btn btn-secondary rounded-0">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary rounded-0">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>