<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">Edit Data Ukuran</h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/ukuran') ?>" class="btn btn-sm btn-secondary rounded-0">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <?= form_open("admin/ukuran/update/$ukuran->id", ['class' => 'form-horizontal']) ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kode">Kode</label>
                            <?= form_input([
                                'name' => 'kode',
                                'id' => 'kode',
                                'class' => 'form-control rounded-0',
                                'value' => old('kode', $ukuran->kode),
                                'placeholder' => 'Masukkan kode ukuran (opsional)'
                            ]) ?>
                            <?php if (session('errors.kode')): ?>
                                <small class="text-danger"><?= session('errors.kode') ?></small>
                            <?php endif ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ukuran">Ukuran <span class="text-danger">*</span></label>
                            <?= form_input([
                                'name' => 'ukuran',
                                'id' => 'ukuran',
                                'class' => 'form-control rounded-0',
                                'value' => old('ukuran', $ukuran->ukuran),
                                'placeholder' => 'Masukkan nama ukuran'
                            ]) ?>
                            <?php if (session('errors.ukuran')): ?>
                                <small class="text-danger"><?= session('errors.ukuran') ?></small>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <?= form_input([
                                'name' => 'deskripsi',
                                'id' => 'deskripsi',
                                'class' => 'form-control rounded-0',
                                'value' => old('deskripsi', $ukuran->deskripsi),
                                'placeholder' => 'Masukkan deskripsi ukuran'
                            ]) ?>
                            <?php if (session('errors.deskripsi')): ?>
                                <small class="text-danger"><?= session('errors.deskripsi') ?></small>
                            <?php endif ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <?= form_input([
                                'name' => 'harga',
                                'id' => 'harga',
                                'class' => 'form-control rounded-0',
                                'value' => old('harga', $ukuran->harga),
                                'placeholder' => 'Masukkan harga tambahan'
                            ]) ?>
                            <?php if (session('errors.harga')): ?>
                                <small class="text-danger"><?= session('errors.harga') ?></small>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stok">Stok</label>
                            <?= form_input([
                                'name' => 'stok',
                                'id' => 'stok',
                                'class' => 'form-control rounded-0',
                                'value' => old('stok', $ukuran->stok),
                                'placeholder' => 'Masukkan stok tersedia'
                            ]) ?>
                            <?php if (session('errors.stok')): ?>
                                <small class="text-danger"><?= session('errors.stok') ?></small>
                            <?php endif ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <?= form_dropdown([
                                'name' => 'status',
                                'id' => 'status',
                                'class' => 'form-control rounded-0'
                            ], [
                                '1' => 'Aktif',
                                '0' => 'Tidak Aktif'
                            ], old('status', $ukuran->status)) ?>
                            <?php if (session('errors.status')): ?>
                                <small class="text-danger"><?= session('errors.status') ?></small>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <?= form_textarea([
                        'name' => 'keterangan',
                        'id' => 'keterangan',
                        'class' => 'form-control rounded-0',
                        'rows' => 3,
                        'value' => old('keterangan', $ukuran->keterangan),
                        'placeholder' => 'Masukkan keterangan tambahan'
                    ]) ?>
                    <?php if (session('errors.keterangan')): ?>
                        <small class="text-danger"><?= session('errors.keterangan') ?></small>
                    <?php endif ?>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary rounded-0">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="<?= base_url('admin/ukuran') ?>" class="btn btn-secondary rounded-0">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
                <?= form_close() ?>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
<?= $this->endSection() ?> 