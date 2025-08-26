<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">Tambah Data Jadwal</h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/jadwal') ?>" class="btn btn-sm btn-secondary rounded-0">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <?= form_open_multipart('admin/jadwal/store', ['class' => 'form-horizontal']) ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kode">Kode</label>
                            <?= form_input([
                                'name' => 'kode',
                                'id' => 'kode',
                                'class' => 'form-control rounded-0',
                                'value' => old('kode'),
                                'placeholder' => 'Masukkan kode jadwal (opsional)'
                            ]) ?>
                            <?php if (session('errors.kode')): ?>
                                <small class="text-danger"><?= session('errors.kode') ?></small>
                            <?php endif ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_jadwal">Nama Jadwal <span class="text-danger">*</span></label>
                            <?= form_input([
                                'name' => 'nama_jadwal',
                                'id' => 'nama_jadwal',
                                'class' => 'form-control rounded-0',
                                'value' => old('nama_jadwal'),
                                'placeholder' => 'Masukkan nama jadwal'
                            ]) ?>
                            <?php if (session('errors.nama_jadwal')): ?>
                                <small class="text-danger"><?= session('errors.nama_jadwal') ?></small>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal_mulai">Tanggal Mulai <span class="text-danger">*</span></label>
                            <?= form_input([
                                'name' => 'tanggal_mulai',
                                'id' => 'tanggal_mulai',
                                'type' => 'date',
                                'class' => 'form-control rounded-0',
                                'value' => old('tanggal_mulai')
                            ]) ?>
                            <?php if (session('errors.tanggal_mulai')): ?>
                                <small class="text-danger"><?= session('errors.tanggal_mulai') ?></small>
                            <?php endif ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal_selesai">Tanggal Selesai <span class="text-danger">*</span></label>
                            <?= form_input([
                                'name' => 'tanggal_selesai',
                                'id' => 'tanggal_selesai',
                                'type' => 'date',
                                'class' => 'form-control rounded-0',
                                'value' => old('tanggal_selesai')
                            ]) ?>
                            <?php if (session('errors.tanggal_selesai')): ?>
                                <small class="text-danger"><?= session('errors.tanggal_selesai') ?></small>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="waktu_mulai">Waktu Mulai <span class="text-danger">*</span></label>
                            <?= form_input([
                                'name' => 'waktu_mulai',
                                'id' => 'waktu_mulai',
                                'type' => 'time',
                                'class' => 'form-control rounded-0',
                                'value' => old('waktu_mulai')
                            ]) ?>
                            <?php if (session('errors.waktu_mulai')): ?>
                                <small class="text-danger"><?= session('errors.waktu_mulai') ?></small>
                            <?php endif ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="waktu_selesai">Waktu Selesai <span class="text-danger">*</span></label>
                            <?= form_input([
                                'name' => 'waktu_selesai',
                                'id' => 'waktu_selesai',
                                'type' => 'time',
                                'class' => 'form-control rounded-0',
                                'value' => old('waktu_selesai')
                            ]) ?>
                            <?php if (session('errors.waktu_selesai')): ?>
                                <small class="text-danger"><?= session('errors.waktu_selesai') ?></small>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lokasi">Lokasi</label>
                            <?= form_input([
                                'name' => 'lokasi',
                                'id' => 'lokasi',
                                'class' => 'form-control rounded-0',
                                'value' => old('lokasi'),
                                'placeholder' => 'Masukkan lokasi kegiatan'
                            ]) ?>
                            <?php if (session('errors.lokasi')): ?>
                                <small class="text-danger"><?= session('errors.lokasi') ?></small>
                            <?php endif ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kapasitas">Kapasitas</label>
                            <?= form_input([
                                'name' => 'kapasitas',
                                'id' => 'kapasitas',
                                'type' => 'number',
                                'class' => 'form-control rounded-0',
                                'value' => old('kapasitas'),
                                'placeholder' => 'Masukkan kapasitas maksimal'
                            ]) ?>
                            <?php if (session('errors.kapasitas')): ?>
                                <small class="text-danger"><?= session('errors.kapasitas') ?></small>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <div class="row">
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
                            ], old('status', '1')) ?>
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
                        'value' => old('keterangan'),
                        'placeholder' => 'Masukkan keterangan tambahan'
                    ]) ?>
                    <?php if (session('errors.keterangan')): ?>
                        <small class="text-danger"><?= session('errors.keterangan') ?></small>
                    <?php endif ?>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary rounded-0">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="<?= base_url('admin/jadwal') ?>" class="btn btn-secondary rounded-0">
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