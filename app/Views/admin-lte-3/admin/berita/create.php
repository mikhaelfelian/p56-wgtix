<?= $this->extend(theme_path('main')) ?>

<?= $this->section('js') ?>
<!-- TinyMCE CDN -->
<script src="https://cdn.tiny.cloud/1/<?= env('TINYMCE_API', 'no-api-key') ?>/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= form_open_multipart(base_url('admin/berita/store')) ?>
<div class="row">
    <div class="col-lg-8">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Validation Errors!</h5>
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $field => $error): ?>
                        <li><strong><?= ucfirst($field) ?>:</strong> <?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if (isset($post) && $post): ?>
            <?= form_hidden('id', $post->id) ?>
        <?php endif; ?>
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title"><?= isset($post) && $post ? 'Form Edit Berita' : 'Form Tambah Berita' ?></h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="judul">Judul Berita <span class="text-danger">*</span></label>
                    <?= form_input([
                        'type' => 'text',
                        'name' => 'judul',
                        'id' => 'judul',
                        'class' => 'form-control',
                        'value' => old('judul') ?? (isset($post) ? $post->judul : ''),
                        'placeholder' => 'Masukkan judul berita',
                        'maxlength' => 240,
                        'required' => 'required'
                    ]) ?>
                    <?php if (session()->getFlashdata('errors.judul')) : ?>
                        <small class="text-danger"><?= session()->getFlashdata('errors.judul') ?></small>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="konten">Konten Berita <span class="text-danger">*</span></label>
                    <?= form_textarea([
                        'name' => 'konten',
                        'id' => 'konten',
                        'class' => 'form-control',
                        'rows' => '15',
                        'value' => old('konten') ?? (isset($post) ? $post->konten : ''),
                        'placeholder' => 'Masukkan konten berita'
                    ]) ?>
                    <?php if (session()->getFlashdata('errors.konten')) : ?>
                        <small class="text-danger"><?= session()->getFlashdata('errors.konten') ?></small>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="excerpt">Ringkasan</label>
                    <?= form_textarea([
                        'name' => 'excerpt',
                        'id' => 'excerpt',
                        'class' => 'form-control',
                        'rows' => '3',
                        'maxlength' => 500,
                        'value' => old('excerpt') ?? (isset($post) ? $post->excerpt : ''),
                        'placeholder' => 'Masukkan ringkasan singkat berita (opsional)'
                    ]) ?>
                    <small class="form-text text-muted">Ringkasan singkat berita (opsional)</small>
                </div>
            </div>
        </div>
        <!-- Publish box for mobile -->
        <div class="d-block d-lg-none mt-3">
            <div class="card rounded-0">
                <div class="card-header">
                    <h3 class="card-title">Terbitkan</h3>
                </div>
                <div class="card-body">
                    <div class="form-group mb-2">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <?= form_dropdown([
                            'name' => 'status',
                            'id' => 'status',
                            'class' => 'form-control',
                            'required' => 'required'
                        ], [
                            'draft' => 'Draft',
                            'scheduled' => 'Terjadwal',
                            'published' => 'Dipublikasi',
                            'archived' => 'Diarsipkan'
                        ], old('status') ?? (isset($post) ? $post->status : 'draft')) ?>
                        <?php if (session()->getFlashdata('errors.status')) : ?>
                            <small class="text-danger"><?= session()->getFlashdata('errors.status') ?></small>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block rounded-0">
                        <i class="fas fa-save"></i> <?= isset($post) && $post ? 'Update Berita' : 'Simpan Berita' ?>
                    </button>
                    <a href="<?= base_url('admin/berita') ?>" class="btn btn-secondary btn-block rounded-0 mt-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card rounded-0 mb-3">
            <div class="card-header">
                <h3 class="card-title">Terbitkan</h3>
            </div>
            <div class="card-body">
                <div class="form-group mb-2">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <?= form_dropdown([
                        'name' => 'status',
                        'id' => 'status',
                        'class' => 'form-control',
                        'required' => 'required'
                    ], [
                        'draft' => 'Draft',
                        'scheduled' => 'Terjadwal',
                        'published' => 'Dipublikasi',
                        'archived' => 'Diarsipkan'
                    ], old('status') ?? (isset($post) ? $post->status : 'draft')) ?>
                    <?php if (session()->getFlashdata('errors.status')) : ?>
                        <small class="text-danger"><?= session()->getFlashdata('errors.status') ?></small>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary btn-block rounded-0">
                    <i class="fas fa-save"></i> <?= isset($post) && $post ? 'Update Berita' : 'Simpan Berita' ?>
                </button>
                <a href="<?= base_url('admin/berita') ?>" class="btn btn-secondary btn-block rounded-0 mt-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card rounded-0 mb-3">
            <div class="card-header">
                <h3 class="card-title">Kategori</h3>
            </div>
            <div class="card-body">
                <div class="form-group mb-0">
                    <?= form_dropdown([
                        'name' => 'id_category',
                        'id' => 'id_category',
                        'class' => 'form-control',
                        'required' => 'required',
                        'form' => 'form0'
                    ], $categoryOptions, old('id_category') ?? (isset($post) ? $post->id_category : '')) ?>
                    <?php if (session()->getFlashdata('errors.id_category')) : ?>
                        <small class="text-danger"><?= session()->getFlashdata('errors.id_category') ?></small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="card rounded-0 mb-3">
            <div class="card-header">
                <h3 class="card-title">Gambar Sampul</h3>
            </div>
            <div class="card-body">
                <?php if (isset($post) && $post->cover_image): ?>
                    <div class="mb-2">
                        <img src="<?= base_url('public/file/posts/' . $post->id . '/' . $post->cover_image) ?>" 
                             alt="Current Cover" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                        <p class="text-muted small">Current cover image</p>
                    </div>
                <?php endif; ?>
                <?= form_upload([
                    'name' => 'cover_image',
                    'id' => 'cover_image',
                    'class' => 'form-control-file',
                    'accept' => 'image/*',
                    'form' => 'form0'
                ]) ?>
                <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB. <?= isset($post) ? 'Leave empty to keep current image.' : '' ?></small>
                <?php if (session()->getFlashdata('errors.cover_image')) : ?>
                    <small class="text-danger"><?= session()->getFlashdata('errors.cover_image') ?></small>
                <?php endif; ?>
            </div>
        </div>
        <div class="card rounded-0 mb-3">
            <div class="card-header">
                <h3 class="card-title">Slug</h3>
            </div>
            <div class="card-body">
                <div class="form-group mb-0">
                    <?= form_input([
                        'type' => 'text',
                        'name' => 'slug',
                        'id' => 'slug',
                        'class' => 'form-control',
                        'value' => old('slug') ?? (isset($post) ? $post->slug : ''),
                        'placeholder' => 'Masukkan slug berita',
                        'required' => 'required',
                        'form' => 'form0'
                    ]) ?>
                    <small class="form-text text-muted">URL-friendly version of the title (e.g., berita-terbaru-2024)</small>
                    <?php if (session()->getFlashdata('errors.slug')) : ?>
                        <small class="text-danger"><?= session()->getFlashdata('errors.slug') ?></small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="card rounded-0 mb-3">
            <div class="card-header">
                <h3 class="card-title">SEO</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="meta_title">Meta Title</label>
                    <?= form_input([
                        'type' => 'text',
                        'name' => 'meta_title',
                        'id' => 'meta_title',
                        'class' => 'form-control',
                        'value' => old('meta_title') ?? (isset($post) ? $post->meta_title : ''),
                        'maxlength' => 255,
                        'placeholder' => 'Judul untuk SEO (opsional)',
                        'form' => 'form0'
                    ]) ?>
                    <small class="form-text text-muted">Judul untuk SEO (opsional)</small>
                </div>
                <div class="form-group">
                    <label for="meta_description">Meta Description</label>
                    <?= form_textarea([
                        'name' => 'meta_description',
                        'id' => 'meta_description',
                        'class' => 'form-control',
                        'rows' => '3',
                        'maxlength' => 320,
                        'value' => old('meta_description') ?? (isset($post) ? $post->meta_description : ''),
                        'placeholder' => 'Deskripsi untuk SEO (opsional)',
                        'form' => 'form0'
                    ]) ?>
                    <small class="form-text text-muted">Deskripsi untuk SEO (opsional)</small>
                </div>
                <div class="form-group">
                    <label for="meta_keywords">Meta Keywords</label>
                    <?= form_input([
                        'type' => 'text',
                        'name' => 'meta_keywords',
                        'id' => 'meta_keywords',
                        'class' => 'form-control',
                        'value' => old('meta_keywords') ?? (isset($post) ? $post->meta_keywords : ''),
                        'maxlength' => 320,
                        'placeholder' => 'Kata kunci untuk SEO, pisahkan dengan koma (opsional)',
                        'form' => 'form0'
                    ]) ?>
                    <small class="form-text text-muted">Kata kunci untuk SEO, pisahkan dengan koma (opsional)</small>
                </div>
            </div>
        </div>
    </div>
</div>
<?= form_close() ?>
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
    $('.form-control-file').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label, .form-control-file-label').html(fileName);
    });

    // Initialize TinyMCE for content
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#konten',
            height: 400,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
            images_upload_url: '<?= base_url('admin/berita/upload-image') ?>',
            images_upload_handler: function (blobInfo, success, failure) {
                var xhr, formData;
                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', '<?= base_url('admin/berita/upload-image') ?>');
                xhr.onload = function() {
                    var json;
                    if (xhr.status != 200) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }
                    json = JSON.parse(xhr.responseText);
                    if (!json || typeof json.location != 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    success(json.location);
                };
                formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            },
            setup: function (editor) {
                editor.on('init', function () {
                    // Remove required attribute from original textarea to prevent validation issues
                    var textarea = document.getElementById('konten');
                    if (textarea) {
                        textarea.removeAttribute('required');
                        textarea.removeAttribute('aria-hidden');
                        textarea.style.display = 'none';
                    }
                });
                
                // Sync content back to textarea before form submission
                editor.on('change', function () {
                    editor.save();
                });
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
