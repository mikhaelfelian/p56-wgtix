<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<section class="content rounded-0">
    <div class="container-fluid rounded-0">
        <div class="row">
            <div class="col-12">
                <div class="card rounded-0">
                    <div class="card-header rounded-0">
                        <h3 class="card-title">
                            <i class="fas fa-edit"></i> Edit Racepack
                        </h3>
                        <div class="card-tools">
                            <a href="<?= base_url('admin/racepack') ?>" class="btn btn-sm btn-secondary rounded-0">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body rounded-0">
                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-danger alert-dismissible rounded-0">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-ban"></i> Error!</h5>
                                <ul class="mb-0">
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
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
                                'class' => 'form-control rounded-0',
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
                                'class' => 'form-control rounded-0',
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
                                'class' => 'form-control rounded-0',
                                'required' => 'required'
                            ], $kategoriOptions, old('id_kategori') ?? $racepack->id_kategori) ?>
                        </div>

                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <?= form_textarea([
                                'name' => 'deskripsi',
                                'id' => 'deskripsi',
                                'class' => 'form-control rounded-0',
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
                                'class' => 'form-control rounded-0',
                                'value' => old('harga') ?? $racepack->harga,
                                'placeholder' => 'Masukkan harga',
                                'required' => 'required'
                            ]) ?>
                        </div>

                        <div class="form-group">
                            <label for="gambar">Gambar</label>
                            <?php if ($racepack->gambar): ?>
                                <div class="mb-2">
                                    <img src="<?= base_url('uploads/racepack/' . $racepack->gambar) ?>"
                                         alt="Gambar Racepack"
                                         style="max-width: 200px; height: auto;" class="img-thumbnail rounded-0">
                                </div>
                            <?php endif; ?>
                            <?= form_upload([
                                'name' => 'gambar',
                                'id' => 'gambar',
                                'class' => 'form-control-file rounded-0',
                                'accept' => 'image/*'
                            ]) ?>
                            <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</small>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <?= form_dropdown([
                                'name' => 'status',
                                'id' => 'status',
                                'class' => 'form-control rounded-0'
                            ], [
                                '1' => 'Aktif',
                                '0' => 'Nonaktif'
                            ], old('status') ?? $racepack->status) ?>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary rounded-0">
                                <i class="fas fa-save"></i> Update
                            </button>
                            <a href="<?= base_url('admin/racepack') ?>" class="btn btn-secondary rounded-0">
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
<?= $this->endSection() ?> 