<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title"><?= isset($category) && $category ? 'Form Edit Kategori Berita' : 'Form Tambah Kategori Berita' ?></h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/berita-category') ?>" class="btn btn-secondary btn-sm rounded-0">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Flash Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?= form_open(isset($category) && $category ? 'admin/berita-category/update/' . $category->id : 'admin/berita-category/store') ?>
                <?php if (isset($category) && $category): ?>
                    <?= form_hidden('id', $category->id) ?>
                <?php endif; ?>
                <div class="form-group">
                    <label for="nama">Nama Kategori <span class="text-danger">*</span></label>
                    <?= form_input([
                        'type' => 'text',
                        'name' => 'nama',
                        'id' => 'nama',
                        'class' => 'form-control',
                        'value' => old('nama') ?? (isset($category) ? $category->nama : ''),
                        'placeholder' => 'Masukkan nama kategori',
                        'maxlength' => 160,
                        'required' => 'required'
                    ]) ?>
                    <?php if (session()->getFlashdata('errors.nama')) : ?>
                        <small class="text-danger"><?= session()->getFlashdata('errors.nama') ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="slug">Slug <span class="text-danger">*</span></label>
                    <?= form_input([
                        'type' => 'text',
                        'name' => 'slug',
                        'id' => 'slug',
                        'class' => 'form-control',
                        'value' => old('slug') ?? (isset($category) ? $category->slug : ''),
                        'placeholder' => 'Masukkan slug kategori',
                        'maxlength' => 180,
                        'required' => 'required'
                    ]) ?>
                    <small class="form-text text-muted">URL-friendly version of the name (e.g., teknologi-terbaru)</small>
                    <?php if (session()->getFlashdata('errors.slug')) : ?>
                        <small class="text-danger"><?= session()->getFlashdata('errors.slug') ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <?= form_textarea([
                        'name' => 'deskripsi',
                        'id' => 'deskripsi',
                        'class' => 'form-control',
                        'rows' => '3',
                        'value' => old('deskripsi') ?? (isset($category) ? $category->deskripsi : ''),
                        'placeholder' => 'Masukkan deskripsi kategori (opsional)'
                    ]) ?>
                    <small class="form-text text-muted">Deskripsi singkat tentang kategori (opsional)</small>
                </div>

                <div class="form-group">
                    <label for="ikon">Ikon</label>
                    <?= form_input([
                        'type' => 'text',
                        'name' => 'ikon',
                        'id' => 'ikon',
                        'class' => 'form-control',
                        'value' => old('ikon') ?? (isset($category) ? $category->ikon : ''),
                        'placeholder' => 'Masukkan class ikon FontAwesome (e.g., fas fa-newspaper)',
                        'maxlength' => 120
                    ]) ?>
                    <small class="form-text text-muted">Class ikon FontAwesome (e.g., fas fa-newspaper, fas fa-star)</small>
                    <?php if (session()->getFlashdata('errors.ikon')) : ?>
                        <small class="text-danger"><?= session()->getFlashdata('errors.ikon') ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="urutan">Urutan <span class="text-danger">*</span></label>
                    <?= form_input([
                        'type' => 'number',
                        'name' => 'urutan',
                        'id' => 'urutan',
                        'class' => 'form-control',
                        'value' => old('urutan') ?? (isset($category) ? $category->urutan : '1'),
                        'min' => '1',
                        'max' => '999',
                        'required' => 'required'
                    ]) ?>
                    <small class="form-text text-muted">Urutan tampil kategori (1 = paling atas)</small>
                    <?php if (session()->getFlashdata('errors.urutan')) : ?>
                        <small class="text-danger"><?= session()->getFlashdata('errors.urutan') ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="is_active">Status <span class="text-danger">*</span></label>
                    <?= form_dropdown([
                        'name' => 'is_active',
                        'id' => 'is_active',
                        'class' => 'form-control',
                        'required' => 'required'
                    ], [
                        '1' => 'Aktif',
                        '0' => 'Tidak Aktif'
                    ], old('is_active') ?? (isset($category) ? $category->is_active : '1')) ?>
                    <?php if (session()->getFlashdata('errors.is_active')) : ?>
                        <small class="text-danger"><?= session()->getFlashdata('errors.is_active') ?></small>
                    <?php endif; ?>
                </div>
                <?= form_close() ?>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('admin/berita-category') ?>" class="btn btn-secondary rounded-0">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" form="categoryForm" class="btn btn-primary rounded-0">
                        <i class="fas fa-save"></i> <?= isset($category) && $category ? 'Update Kategori' : 'Simpan Kategori' ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
$(document).ready(function() {
    // Auto-generate slug from name
    $('#nama').on('keyup', function() {
        var name = $(this).val();
        var slug = name.toLowerCase()
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
        $('#slug').val(slug);
    });

    // Add form ID to the form
    $('form').attr('id', 'categoryForm');
});
</script>
<?= $this->endSection() ?>
