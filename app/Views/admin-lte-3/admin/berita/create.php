<?= $this->extend('admin-lte-3/layout/main') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= $title ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/berita') ?>">Berita</a></li>
                        <li class="breadcrumb-item active"><?= $title ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Berita</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/berita') ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <form action="<?= base_url('admin/berita/store') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Basic Information -->
                                <div class="form-group">
                                    <label for="judul">Judul Berita <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="judul" name="judul" 
                                           value="<?= old('judul') ?>" required maxlength="240">
                                    <?php if (session()->getFlashdata('errors.judul')) : ?>
                                        <small class="text-danger"><?= session()->getFlashdata('errors.judul') ?></small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label for="slug">Slug <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="slug" name="slug" 
                                           value="<?= old('slug') ?>" required>
                                    <small class="form-text text-muted">URL-friendly version of the title (e.g., berita-terbaru-2024)</small>
                                    <?php if (session()->getFlashdata('errors.slug')) : ?>
                                        <small class="text-danger"><?= session()->getFlashdata('errors.slug') ?></small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label for="excerpt">Ringkasan</label>
                                    <textarea class="form-control" id="excerpt" name="excerpt" rows="3" 
                                              maxlength="500"><?= old('excerpt') ?></textarea>
                                    <small class="form-text text-muted">Ringkasan singkat berita (opsional)</small>
                                </div>

                                <div class="form-group">
                                    <label for="konten">Konten Berita <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="konten" name="konten" rows="15" required><?= old('konten') ?></textarea>
                                    <?php if (session()->getFlashdata('errors.konten')) : ?>
                                        <small class="text-danger"><?= session()->getFlashdata('errors.konten') ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Sidebar Settings -->
                                <div class="form-group">
                                    <label for="id_category">Kategori <span class="text-danger">*</span></label>
                                    <select class="form-control" id="id_category" name="id_category" required>
                                        <option value="">Pilih Kategori</option>
                                        <?php foreach ($categories as $category) : ?>
                                            <option value="<?= $category->id ?>" <?= old('id_category') == $category->id ? 'selected' : '' ?>>
                                                <?= esc($category->nama) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (session()->getFlashdata('errors.id_category')) : ?>
                                        <small class="text-danger"><?= session()->getFlashdata('errors.id_category') ?></small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="draft" <?= old('status') == 'draft' ? 'selected' : '' ?>>Draft</option>
                                        <option value="scheduled" <?= old('status') == 'scheduled' ? 'selected' : '' ?>>Terjadwal</option>
                                        <option value="published" <?= old('status') == 'published' ? 'selected' : '' ?>>Dipublikasi</option>
                                        <option value="archived" <?= old('status') == 'archived' ? 'selected' : '' ?>>Diarsipkan</option>
                                    </select>
                                    <?php if (session()->getFlashdata('errors.status')) : ?>
                                        <small class="text-danger"><?= session()->getFlashdata('errors.status') ?></small>
                                    <?php endif; ?>
                                </div>

                                <!-- Cover Image -->
                                <div class="form-group">
                                    <label for="cover_image">Cover Image <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="cover_image" name="cover_image" 
                                                   accept="image/*" required>
                                            <label class="custom-file-label" for="cover_image">Pilih file</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                                    <?php if (session()->getFlashdata('errors.cover_image')) : ?>
                                        <small class="text-danger"><?= session()->getFlashdata('errors.cover_image') ?></small>
                                    <?php endif; ?>
                                </div>

                                <!-- Gallery Images -->
                                <div class="form-group">
                                    <label for="gallery_images">Galeri Gambar</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="gallery_images" name="gallery_images[]" 
                                                   accept="image/*" multiple>
                                            <label class="custom-file-label" for="gallery_images">Pilih file</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB per file</small>
                                </div>

                                <!-- SEO Fields -->
                                <div class="form-group">
                                    <label for="meta_title">Meta Title</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                           value="<?= old('meta_title') ?>" maxlength="255">
                                    <small class="form-text text-muted">Judul untuk SEO (opsional)</small>
                                </div>

                                <div class="form-group">
                                    <label for="meta_description">Meta Description</label>
                                    <textarea class="form-control" id="meta_description" name="meta_description" 
                                              rows="3" maxlength="320"><?= old('meta_description') ?></textarea>
                                    <small class="form-text text-muted">Deskripsi untuk SEO (opsional)</small>
                                </div>

                                <div class="form-group">
                                    <label for="meta_keywords">Meta Keywords</label>
                                    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" 
                                           value="<?= old('meta_keywords') ?>" maxlength="320">
                                    <small class="form-text text-muted">Kata kunci untuk SEO, pisahkan dengan koma (opsional)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Berita
                        </button>
                        <a href="<?= base_url('admin/berita') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
$(document).ready(function() {
    // Auto-generate slug from title
    $('#judul').on('keyup', function() {
        var title = $(this).val();
        var slug = title.toLowerCase()
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
        $('#slug').val(slug);
    });

    // File input labels
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });

    // Initialize CKEditor for content
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('konten', {
            height: 400,
            filebrowserUploadUrl: '<?= base_url('admin/berita/upload-image') ?>',
            filebrowserUploadMethod: 'form'
        });
    }
});
</script>
<?= $this->endSection() ?>
