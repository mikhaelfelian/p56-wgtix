<?= $this->extend('admin-lte-3/layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= $title ?></h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/peserta/kelompok') ?>" class="btn btn-sm btn-secondary rounded-0">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?= form_open('admin/peserta/kelompok/update/' . $kelompok->id) ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kode_kelompok">Kode Kelompok <span class="text-danger">*</span></label>
                            <?= form_input([
                                'name' => 'kode_kelompok',
                                'id' => 'kode_kelompok',
                                'class' => 'form-control rounded-0',
                                'value' => old('kode_kelompok', $kelompok->kode_kelompok),
                                'placeholder' => 'Contoh: KEL001, KEL002'
                            ]) ?>
                            <?php if (session('errors.kode_kelompok')): ?>
                                <small class="text-danger"><?= session('errors.kode_kelompok') ?></small>
                            <?php endif ?>
                        </div>

                        <div class="form-group">
                            <label for="nama_kelompok">Nama Kelompok <span class="text-danger">*</span></label>
                            <?= form_input([
                                'name' => 'nama_kelompok',
                                'id' => 'nama_kelompok',
                                'class' => 'form-control rounded-0',
                                'value' => old('nama_kelompok', $kelompok->nama_kelompok),
                                'placeholder' => 'Masukkan nama kelompok'
                            ]) ?>
                            <?php if (session('errors.nama_kelompok')): ?>
                                <small class="text-danger"><?= session('errors.nama_kelompok') ?></small>
                            <?php endif ?>
                        </div>

                        <div class="form-group">
                            <label for="kapasitas">Kapasitas</label>
                            <?= form_input([
                                'name' => 'kapasitas',
                                'id' => 'kapasitas',
                                'type' => 'number',
                                'class' => 'form-control rounded-0',
                                'value' => old('kapasitas', $kelompok->kapasitas),
                                'placeholder' => 'Masukkan kapasitas kelompok'
                            ]) ?>
                            <?php if (session('errors.kapasitas')): ?>
                                <small class="text-danger"><?= session('errors.kapasitas') ?></small>
                            <?php endif ?>
                        </div>

                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <?= form_dropdown([
                                'name' => 'status',
                                'id' => 'status',
                                'class' => 'form-control rounded-0'
                            ], [
                                '' => 'Pilih Status',
                                '1' => 'Aktif',
                                '0' => 'Tidak Aktif'
                            ], old('status', $kelompok->status)) ?>
                            <?php if (session('errors.status')): ?>
                                <small class="text-danger"><?= session('errors.status') ?></small>
                            <?php endif ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <?= form_textarea([
                                'name' => 'deskripsi',
                                'id' => 'deskripsi',
                                'class' => 'form-control rounded-0',
                                'rows' => '8',
                                'placeholder' => 'Masukkan deskripsi kelompok'
                            ], old('deskripsi', $kelompok->deskripsi) ?? '') ?>
                            <?php if (session('errors.deskripsi')): ?>
                                <small class="text-danger"><?= session('errors.deskripsi') ?></small>
                            <?php endif ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary rounded-0">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="<?= base_url('admin/peserta/kelompok') ?>" class="btn btn-secondary rounded-0">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 