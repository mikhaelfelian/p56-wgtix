<?= $this->extend('admin-lte-3/layout/default') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Stock Racepack</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/stock-racepack') ?>">Stock Racepack</a></li>
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
                    <h3 class="card-title">Form Edit Stock Racepack</h3>
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

                    <?= form_open('admin/stock-racepack/update/' . $stock->id) ?>
                    <div class="form-group">
                        <label for="id_racepack">Racepack <span class="text-danger">*</span></label>
                        <?= form_dropdown([
                            'name' => 'id_racepack',
                            'id' => 'id_racepack',
                            'class' => 'form-control',
                            'required' => 'required'
                        ], $racepackOptions, old('id_racepack') ?? $stock->id_racepack) ?>
                    </div>

                    <div class="form-group">
                        <label for="id_ukuran">Ukuran <span class="text-danger">*</span></label>
                        <?= form_dropdown([
                            'name' => 'id_ukuran',
                            'id' => 'id_ukuran',
                            'class' => 'form-control',
                            'required' => 'required'
                        ], $ukuranOptions, old('id_ukuran') ?? $stock->id_ukuran) ?>
                    </div>

                    <div class="form-group">
                        <label for="stok_masuk">Stok Masuk <span class="text-danger">*</span></label>
                        <?= form_input([
                            'type' => 'number',
                            'name' => 'stok_masuk',
                            'id' => 'stok_masuk',
                            'class' => 'form-control',
                            'value' => old('stok_masuk') ?? $stock->stok_masuk,
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
                            'value' => old('stok_keluar') ?? $stock->stok_keluar,
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
                            'value' => old('minimal_stok') ?? $stock->minimal_stok,
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
                        ], old('status') ?? $stock->status) ?>
                    </div>

                    <div class="form-group">
                        <a href="<?= base_url('admin/stock-racepack') ?>" class="btn btn-secondary">
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