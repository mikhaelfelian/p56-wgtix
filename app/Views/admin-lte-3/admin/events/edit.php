<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">Edit Data Event</h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/events') ?>" class="btn btn-sm btn-secondary rounded-0">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="<?= base_url('admin/events/update/' . $event->id) ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode">Kode</label>
                                <input type="text" 
                                       name="kode" 
                                       id="kode" 
                                       class="form-control rounded-0" 
                                       value="<?= old('kode', $event->kode) ?>" 
                                       placeholder="Masukkan kode event (opsional)">
                                <?php if (session('errors.kode')): ?>
                                    <small class="text-danger"><?= session('errors.kode') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="event">Nama Event <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="event" 
                                       id="event" 
                                       class="form-control rounded-0" 
                                       value="<?= old('event', $event->event) ?>" 
                                       placeholder="Masukkan nama event" 
                                       required>
                                <?php if (session('errors.event')): ?>
                                    <small class="text-danger"><?= session('errors.event') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_kategori">Kategori <span class="text-danger">*</span></label>
                                <select name="id_kategori" id="id_kategori" class="form-control rounded-0" required>
                                    <option value="">Pilih Kategori</option>
                                    <?php if (isset($kategoriOptions) && is_array($kategoriOptions)): ?>
                                        <?php foreach ($kategoriOptions as $kategori): ?>
                                            <option value="<?= $kategori->id ?>" <?= old('id_kategori', $event->id_kategori) == $kategori->id ? 'selected' : '' ?>>
                                                <?= $kategori->kategori ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php if (session('errors.id_kategori')): ?>
                                    <small class="text-danger"><?= session('errors.id_kategori') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control rounded-0" required>
                                    <option value="">Pilih Status</option>
                                    <option value="1" <?= old('status', $event->status) == '1' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="0" <?= old('status', $event->status) == '0' ? 'selected' : '' ?>>Tidak Aktif</option>
                                </select>
                                <?php if (session('errors.status')): ?>
                                    <small class="text-danger"><?= session('errors.status') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tgl_masuk">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" 
                                       name="tgl_masuk" 
                                       id="tgl_masuk" 
                                       class="form-control rounded-0" 
                                       value="<?= old('tgl_masuk', $event->tgl_masuk) ?>" 
                                       required>
                                <?php if (session('errors.tgl_masuk')): ?>
                                    <small class="text-danger"><?= session('errors.tgl_masuk') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tgl_keluar">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" 
                                       name="tgl_keluar" 
                                       id="tgl_keluar" 
                                       class="form-control rounded-0" 
                                       value="<?= old('tgl_keluar', $event->tgl_keluar) ?>" 
                                       required>
                                <?php if (session('errors.tgl_keluar')): ?>
                                    <small class="text-danger"><?= session('errors.tgl_keluar') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="wkt_masuk">Waktu Mulai <span class="text-danger">*</span></label>
                                <input type="time" 
                                       name="wkt_masuk" 
                                       id="wkt_masuk" 
                                       class="form-control rounded-0" 
                                       value="<?= old('wkt_masuk', $event->wkt_masuk) ?>" 
                                       required>
                                <?php if (session('errors.wkt_masuk')): ?>
                                    <small class="text-danger"><?= session('errors.wkt_masuk') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="wkt_keluar">Waktu Selesai <span class="text-danger">*</span></label>
                                <input type="time" 
                                       name="wkt_keluar" 
                                       id="wkt_keluar" 
                                       class="form-control rounded-0" 
                                       value="<?= old('wkt_keluar', $event->wkt_keluar) ?>" 
                                       required>
                                <?php if (session('errors.wkt_keluar')): ?>
                                    <small class="text-danger"><?= session('errors.wkt_keluar') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lokasi">Lokasi</label>
                                <input type="text" 
                                       name="lokasi" 
                                       id="lokasi" 
                                       class="form-control rounded-0" 
                                       value="<?= old('lokasi', $event->lokasi) ?>" 
                                       placeholder="Masukkan lokasi event">
                                <?php if (session('errors.lokasi')): ?>
                                    <small class="text-danger"><?= session('errors.lokasi') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jml">Kapasitas</label>
                                <input type="number" 
                                       name="jml" 
                                       id="jml" 
                                       class="form-control rounded-0" 
                                       value="<?= old('jml', $event->jml) ?>" 
                                       placeholder="Masukkan kapasitas maksimal" 
                                       min="0">
                                <?php if (session('errors.jml')): ?>
                                    <small class="text-danger"><?= session('errors.jml') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" 
                                  id="keterangan" 
                                  class="form-control rounded-0" 
                                  rows="3" 
                                  placeholder="Masukkan keterangan tambahan"><?= old('keterangan', $event->keterangan) ?></textarea>
                        <?php if (session('errors.keterangan')): ?>
                            <small class="text-danger"><?= session('errors.keterangan') ?></small>
                        <?php endif ?>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary rounded-0">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="<?= base_url('admin/events') ?>" class="btn btn-secondary rounded-0">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
<?= $this->endSection() ?>
