<?= $this->extend('admin-lte-3/layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= $title ?></h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/peserta/daftar') ?>" class="btn btn-sm btn-secondary rounded-0">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?= form_open('admin/peserta/update/' . $peserta->id) ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                            <?= form_input([
                                'name' => 'nama_lengkap',
                                'id' => 'nama_lengkap',
                                'class' => 'form-control rounded-0',
                                'value' => old('nama_lengkap', $peserta->nama_lengkap),
                                'placeholder' => 'Masukkan nama lengkap'
                            ]) ?>
                            <?php if (session('errors.nama_lengkap')): ?>
                                <small class="text-danger"><?= session('errors.nama_lengkap') ?></small>
                            <?php endif ?>
                        </div>

                        <div class="form-group">
                            <label for="kode_peserta">Kode Peserta <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <?= form_input([
                                    'name' => 'kode_peserta',
                                    'id' => 'kode_peserta',
                                    'class' => 'form-control rounded-0',
                                    'value' => old('kode_peserta', $peserta->kode_peserta),
                                    'readonly' => 'readonly'
                                ]) ?>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                            </div>
                            <small class="text-muted">Kode peserta tidak dapat diubah</small>
                            <?php if (session('errors.kode_peserta')): ?>
                                <small class="text-danger"><?= session('errors.kode_peserta') ?></small>
                            <?php endif ?>
                        </div>

                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                            <?= form_dropdown([
                                'name' => 'jenis_kelamin',
                                'id' => 'jenis_kelamin',
                                'class' => 'form-control rounded-0'
                            ], [
                                '' => 'Pilih Jenis Kelamin',
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan'
                            ], old('jenis_kelamin', $peserta->jenis_kelamin)) ?>
                            <?php if (session('errors.jenis_kelamin')): ?>
                                <small class="text-danger"><?= session('errors.jenis_kelamin') ?></small>
                            <?php endif ?>
                        </div>

                        <div class="form-group">
                            <label for="tempat_lahir">Tempat Lahir</label>
                            <?= form_input([
                                'name' => 'tempat_lahir',
                                'id' => 'tempat_lahir',
                                'class' => 'form-control rounded-0',
                                'value' => old('tempat_lahir', $peserta->tempat_lahir),
                                'placeholder' => 'Masukkan tempat lahir'
                            ]) ?>
                            <?php if (session('errors.tempat_lahir')): ?>
                                <small class="text-danger"><?= session('errors.tempat_lahir') ?></small>
                            <?php endif ?>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <?= form_input([
                                'name' => 'tanggal_lahir',
                                'id' => 'tanggal_lahir',
                                'type' => 'date',
                                'class' => 'form-control rounded-0',
                                'value' => old('tanggal_lahir', $peserta->tanggal_lahir)
                            ]) ?>
                            <?php if (session('errors.tanggal_lahir')): ?>
                                <small class="text-danger"><?= session('errors.tanggal_lahir') ?></small>
                            <?php endif ?>
                        </div>

                        <div class="form-group">
                            <label for="no_hp">No. HP</label>
                            <?= form_input([
                                'name' => 'no_hp',
                                'id' => 'no_hp',
                                'class' => 'form-control rounded-0',
                                'value' => old('no_hp', $peserta->no_hp),
                                'placeholder' => 'Contoh: 08123456789'
                            ]) ?>
                            <?php if (session('errors.no_hp')): ?>
                                <small class="text-danger"><?= session('errors.no_hp') ?></small>
                            <?php endif ?>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <?= form_input([
                                'name' => 'email',
                                'id' => 'email',
                                'type' => 'email',
                                'class' => 'form-control rounded-0',
                                'value' => old('email', $peserta->email),
                                'placeholder' => 'Contoh: email@example.com'
                            ]) ?>
                            <?php if (session('errors.email')): ?>
                                <small class="text-danger"><?= session('errors.email') ?></small>
                            <?php endif ?>
                        </div>

                        <div class="form-group">
                            <label for="id_kelompok">Kelompok</label>
                            <?= form_dropdown([
                                'name' => 'id_kelompok',
                                'id' => 'id_kelompok',
                                'class' => 'form-control rounded-0'
                            ], ['' => 'Pilih Kelompok'] + $kelompokOptions, old('id_kelompok', $peserta->id_kelompok)) ?>
                            <?php if (session('errors.id_kelompok')): ?>
                                <small class="text-danger"><?= session('errors.id_kelompok') ?></small>
                            <?php endif ?>
                        </div>

                        <div class="form-group">
                            <label for="id_kategori">Kategori</label>
                            <?php 
                            $kategoriOptionsArray = ['' => 'Pilih Kategori'];
                            foreach ($kategoriOptions as $kategori) {
                                $kategoriOptionsArray[$kategori->id] = $kategori->kategori;
                            }
                            ?>
                            <?= form_dropdown([
                                'name' => 'id_kategori',
                                'id' => 'id_kategori',
                                'class' => 'form-control rounded-0'
                            ], $kategoriOptionsArray, old('id_kategori', $peserta->id_kategori)) ?>
                            <?php if (session('errors.id_kategori')): ?>
                                <small class="text-danger"><?= session('errors.id_kategori') ?></small>
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
                            ], old('status', $peserta->status)) ?>
                            <?php if (session('errors.status')): ?>
                                <small class="text-danger"><?= session('errors.status') ?></small>
                            <?php endif ?>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <?= form_textarea([
                                'name' => 'alamat',
                                'id' => 'alamat',
                                'class' => 'form-control rounded-0',
                                'rows' => '3',
                                'placeholder' => 'Masukkan alamat lengkap'
                            ], old('alamat', $peserta->alamat) ?? '') ?>
                            <?php if (session('errors.alamat')): ?>
                                <small class="text-danger"><?= session('errors.alamat') ?></small>
                            <?php endif ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary rounded-0">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="<?= base_url('admin/peserta/daftar') ?>" class="btn btn-secondary rounded-0">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 