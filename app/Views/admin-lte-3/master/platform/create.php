<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8">
        <?= form_open('master/platform/store') ?>
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">Form Tambah Platform</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kategori <span class="text-danger">*</span></label>
                            <?= form_dropdown([
                                'name' => 'id_kategori',
                                'class' => 'form-control rounded-0',
                                'options' => ['' => 'Pilih Kategori'] + array_column($kategori, 'kategori', 'id'),
                                'selected' => old('id_kategori')
                            ]) ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Platform <span class="text-danger">*</span></label>
                            <?= form_input([
                                'type' => 'text',
                                'name' => 'nama',
                                'class' => 'form-control rounded-0',
                                'placeholder' => 'Nama Platform',
                                'value' => old('nama')
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Jenis <span class="text-danger">*</span></label>
                            <?= form_input([
                                'type' => 'text',
                                'name' => 'jenis',
                                'class' => 'form-control rounded-0',
                                'placeholder' => 'Jenis Platform',
                                'value' => old('jenis')
                            ]) ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kategori Platform <span class="text-danger">*</span></label>
                            <?= form_input([
                                'type' => 'text',
                                'name' => 'kategori',
                                'class' => 'form-control rounded-0',
                                'placeholder' => 'Kategori Platform',
                                'value' => old('kategori')
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Rekening <span class="text-danger">*</span></label>
                            <?= form_input([
                                'type' => 'text',
                                'name' => 'nama_rekening',
                                'class' => 'form-control rounded-0',
                                'placeholder' => 'Nama Rekening',
                                'value' => old('nama_rekening')
                            ]) ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nomor Rekening <span class="text-danger">*</span></label>
                            <?= form_input([
                                'type' => 'text',
                                'name' => 'nomor_rekening',
                                'class' => 'form-control rounded-0',
                                'placeholder' => 'Nomor Rekening',
                                'value' => old('nomor_rekening')
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Deskripsi <span class="text-danger">*</span></label>
                    <?= form_textarea([
                        'name' => 'deskripsi',
                        'class' => 'form-control rounded-0',
                        'placeholder' => 'Deskripsi Platform',
                        'rows' => 3,
                        'value' => old('deskripsi')
                    ]) ?>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Gateway Kode</label>
                            <?= form_input([
                                'type' => 'text',
                                'name' => 'gateway_kode',
                                'class' => 'form-control rounded-0',
                                'placeholder' => 'Gateway Kode',
                                'value' => old('gateway_kode')
                            ]) ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Gateway Instruksi</label>
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" name="gateway_instruksi" value="0" id="gatewayInstruksi0" <?= old('gateway_instruksi', '0') == '0' ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="gatewayInstruksi0">
                                    Tidak Aktif
                                </label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" name="gateway_instruksi" value="1" id="gatewayInstruksi1" <?= old('gateway_instruksi') == '1' ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="gatewayInstruksi1">
                                    Aktif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Logo <span class="text-danger">*</span></label>
                            <?= form_input([
                                'type' => 'text',
                                'name' => 'logo',
                                'class' => 'form-control rounded-0',
                                'placeholder' => 'Path Logo',
                                'value' => old('logo')
                            ]) ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Hasil <span class="text-danger">*</span></label>
                            <?= form_input([
                                'type' => 'text',
                                'name' => 'hasil',
                                'class' => 'form-control rounded-0',
                                'placeholder' => 'Hasil',
                                'value' => old('hasil')
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" name="status" value="1" id="statusAktif" <?= old('status', '1') == '1' ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="statusAktif">
                                    Aktif
                                </label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" name="status" value="0" id="statusNonaktif" <?= old('status') == '0' ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="statusNonaktif">
                                    Tidak Aktif
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status Gateway</label>
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" name="status_gateway" value="0" id="statusGateway0" <?= old('status_gateway', '0') == '0' ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="statusGateway0">
                                    Tidak Aktif
                                </label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" name="status_gateway" value="1" id="statusGateway1" <?= old('status_gateway') == '1' ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="statusGateway1">
                                    Aktif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-left">
                <a href="<?= base_url('master/platform') ?>" class="btn btn-default rounded-0">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <button type="submit" class="btn btn-primary rounded-0 float-right">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </div>
        <!-- /.card -->
        <?= form_close() ?>
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
<?= $this->endSection() ?>