<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">Tambah Data Event</h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/events') ?>" class="btn btn-sm btn-secondary rounded-0">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <?= form_open('admin/events/store', [
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data'
                ]) ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode">Kode</label>
                                <input type="text" 
                                       name="kode" 
                                       id="kode" 
                                       class="form-control rounded-0" 
                                       value="<?= old('kode') ?>" 
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
                                       value="<?= old('event') ?>" 
                                       placeholder="Masukkan nama event" 
                                       required>
                                <?php if (session('events.event')): ?>
                                    <small class="text-danger"><?= session('errors.event') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="foto">Foto Utama</label>
                                <div class="custom-file-upload" id="photoUploadArea">
                                    <div class="upload-placeholder">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                        <h5>Drag & Drop foto utama di sini</h5>
                                        <p class="text-muted">atau klik untuk memilih file</p>
                                        <p class="text-muted small">Format: JPG, PNG, GIF (Max: 2MB)</p>
                                    </div>
                                    <div class="file-preview" id="filePreview" style="display: none;">
                                        <img id="previewImage" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                        <button type="button" class="btn btn-sm btn-danger mt-2" id="removePhoto">
                                            <i class="fas fa-trash"></i> Hapus Foto
                                        </button>
                                    </div>
                                </div>
                                <input type="file" name="foto" id="foto" accept="image/*" style="display: none;">
                                <?php if (session('errors.foto')): ?>
                                    <small class="text-danger"><?= session('errors.foto') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_kategori">Kategori <span class="text-danger">*</span></label>
                                <select name="id_kategori" id="id_kategori" class="form-control rounded-0" required>
                                    <option value="">Pilih Kategori</option>
                                    <?php if (isset($kategoriOptions) && is_array($kategoriOptions)): ?>
                                        <?php foreach ($kategoriOptions as $kategori): ?>
                                            <option value="<?= $kategori->id ?>" <?= old('id_kategori') == $kategori->id ? 'selected' : '' ?>>
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
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control rounded-0" required>
                                    <option value="">Pilih Status</option>
                                    <option value="1" <?= old('status', '1') == '1' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="0" <?= old('status') == '0' ? 'selected' : '' ?>>Tidak Aktif</option>
                                </select>
                                <?php if (session('errors.status')): ?>
                                    <small class="text-danger"><?= session('errors.status') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Empty column for spacing -->
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
                                       value="<?= old('tgl_masuk') ?>" 
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
                                       value="<?= old('tgl_keluar') ?>" 
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
                                       value="<?= old('wkt_masuk') ?>" 
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
                                       value="<?= old('wkt_keluar') ?>" 
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
                                       value="<?= old('lokasi') ?>" 
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
                                       value="<?= old('jml') ?>" 
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
                                  placeholder="Masukkan keterangan tambahan"><?= old('keterangan') ?></textarea>
                        <?php if (session('errors.keterangan')): ?>
                            <small class="text-danger"><?= session('errors.keterangan') ?></small>
                        <?php endif ?>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary rounded-0">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('admin/events') ?>" class="btn btn-secondary rounded-0">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                <?= form_close() ?>
                
                <!-- Gallery Upload Section -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-images"></i> Galeri Event
                        </h3>
                        <div class="card-tools">
                            <small class="text-muted">Upload galeri setelah event disimpan</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Info:</strong> Galeri dapat diupload setelah event berhasil disimpan. 
                            Setelah event tersimpan, Anda akan diarahkan ke halaman detail event untuk mengelola galeri.
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

<style>
.custom-file-upload {
    border: 2px dashed #ddd;
    border-radius: 8px;
    padding: 30px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.custom-file-upload:hover {
    border-color: #007bff;
    background: #e3f2fd;
}

.upload-placeholder {
    color: #6c757d;
}

.file-preview {
    text-align: center;
}

.file-preview img {
    border: 2px solid #dee2e6;
    border-radius: 8px;
}

#removePhoto {
    border-radius: 20px;
}
</style>

<script>
$(document).ready(function() {
    var photoUploadArea = $('#photoUploadArea');
    var fileInput = $('#foto');
    var filePreview = $('#filePreview');
    var previewImage = $('#previewImage');
    var uploadPlaceholder = $('.upload-placeholder');
    
    // Click to select file
    photoUploadArea.on('click', function() {
        fileInput.click();
    });
    
    // Handle file selection
    fileInput.on('change', function() {
        var file = this.files[0];
        if (file) {
            // Validate file type
            if (!file.type.match('image.*')) {
                alert('Hanya file gambar yang diperbolehkan!');
                return;
            }
            
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB!');
                return;
            }
            
            // Show preview
            var reader = new FileReader();
            reader.onload = function(e) {
                previewImage.attr('src', e.target.result);
                uploadPlaceholder.hide();
                filePreview.show();
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Remove photo
    $('#removePhoto').on('click', function() {
        fileInput.val('');
        filePreview.hide();
        uploadPlaceholder.show();
    });
    
    // Drag and drop functionality
    photoUploadArea.on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('dragover');
    });
    
    photoUploadArea.on('dragleave', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
    });
    
    photoUploadArea.on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
        
        var files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            fileInput[0].files = files;
            fileInput.trigger('change');
        }
    });
    
    // Add dragover visual feedback
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .custom-file-upload.dragover {
                border-color: #007bff !important;
                background: #e3f2fd !important;
                transform: scale(1.02);
            }
        `)
        .appendTo('head');
});
</script>
<?= $this->endSection() ?>
