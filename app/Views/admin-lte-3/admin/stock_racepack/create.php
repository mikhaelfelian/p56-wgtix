<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Tambah Stock Racepack</h3>
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

                <?= form_open('admin/stock-racepack/store') ?>
                <div class="form-group">
                    <label for="id_racepack">Racepack <span class="text-danger">*</span></label>
                    <?= form_dropdown([
                        'name' => 'id_racepack',
                        'id' => 'id_racepack',
                        'class' => 'form-control',
                        'required' => 'required'
                    ], $racepackOptions, old('id_racepack') ?? '') ?>
                </div>

                <div class="form-group">
                    <label for="id_ukuran">Ukuran <span class="text-danger">*</span></label>
                    <?= form_dropdown([
                        'name' => 'id_ukuran',
                        'id' => 'id_ukuran',
                        'class' => 'form-control',
                        'required' => 'required'
                    ], $ukuranOptions, old('id_ukuran') ?? '') ?>
                </div>

                <div class="form-group">
                    <label for="stok_masuk">Stok Masuk <span class="text-danger">*</span></label>
                    <?= form_input([
                        'type' => 'number',
                        'name' => 'stok_masuk',
                        'id' => 'stok_masuk',
                        'class' => 'form-control',
                        'value' => old('stok_masuk') ?? '0',
                        'placeholder' => 'Masukkan stok masuk',
                        'required' => 'required',
                        'min' => '0'
                    ]) ?>
                </div>

                <div class="form-group">
                    <label for="stok_keluar">Stok Keluar</label>
                    <?= form_input([
                        'type' => 'number',
                        'name' => 'stok_keluar',
                        'id' => 'stok_keluar',
                        'class' => 'form-control',
                        'value' => old('stok_keluar') ?? '0',
                        'placeholder' => 'Masukkan stok keluar',
                        'min' => '0'
                    ]) ?>
                </div>

                <div class="form-group">
                    <label for="minimal_stok">Minimal Stok <span class="text-danger">*</span></label>
                    <?= form_input([
                        'type' => 'number',
                        'name' => 'minimal_stok',
                        'id' => 'minimal_stok',
                        'class' => 'form-control',
                        'value' => old('minimal_stok') ?? '10',
                        'placeholder' => 'Masukkan minimal stok',
                        'required' => 'required',
                        'min' => '0'
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
                    <a href="<?= base_url('admin/stock-racepack') ?>" class="btn btn-secondary">
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