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
                        <li class="breadcrumb-item active"><?= $title ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Galeri Gambar Berita</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-danger btn-sm" onclick="bulkDelete()" id="bulkDeleteBtn" style="display: none;">
                                    <i class="fas fa-trash"></i> Hapus Terpilih
                                </button>
                                <a href="<?= base_url('admin/berita-gallery/upload') ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-upload"></i> Upload Gambar
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (empty($gallery)) : ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-images fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada gambar galeri</p>
                                    <a href="<?= base_url('admin/berita-gallery/upload') ?>" class="btn btn-primary">
                                        <i class="fas fa-upload"></i> Upload Gambar Pertama
                                    </a>
                                </div>
                            <?php else : ?>
                                <div class="row" id="galleryContainer">
                                    <?php foreach ($gallery as $image) : ?>
                                        <div class="col-md-4 col-sm-6 mb-3">
                                            <div class="card gallery-item" data-id="<?= $image->id ?>">
                                                <div class="card-img-top position-relative">
                                                    <img src="<?= base_url('public/uploads/berita/gallery/' . $image->path) ?>" 
                                                         class="card-img-top" alt="<?= esc($image->alt_text ?: $image->caption) ?>"
                                                         style="height: 200px; object-fit: cover;">
                                                    
                                                    <!-- Primary Image Badge -->
                                                    <?php if ($image->is_primary) : ?>
                                                        <div class="position-absolute top-0 left-0 m-2">
                                                            <span class="badge badge-success">
                                                                <i class="fas fa-star"></i> Utama
                                                            </span>
                                                        </div>
                                                    <?php endif; ?>

                                                    <!-- Checkbox for bulk selection -->
                                                    <div class="position-absolute top-0 right-0 m-2">
                                                        <input type="checkbox" class="gallery-checkbox" value="<?= $image->id ?>">
                                                    </div>

                                                    <!-- Order badge -->
                                                    <div class="position-absolute bottom-0 left-0 m-2">
                                                        <span class="badge badge-info">#<?= $image->urutan ?></span>
                                                    </div>
                                                </div>
                                                <div class="card-body p-2">
                                                    <h6 class="card-title mb-1"><?= esc($image->post_title) ?></h6>
                                                    <?php if ($image->caption) : ?>
                                                        <p class="card-text small text-muted mb-1"><?= esc($image->caption) ?></p>
                                                    <?php endif; ?>
                                                    <div class="btn-group btn-group-sm w-100">
                                                        <a href="<?= base_url('admin/berita-gallery/edit/' . $image->id) ?>" 
                                                           class="btn btn-info" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <?php if (!$image->is_primary) : ?>
                                                            <button type="button" class="btn btn-warning" 
                                                                    onclick="setPrimaryImage(<?= $image->id ?>)" title="Set as Primary">
                                                                <i class="fas fa-star"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                        <button type="button" class="btn btn-danger" 
                                                                onclick="deleteImage(<?= $image->id ?>)" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Statistik Galeri</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-images"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Gambar</span>
                                    <span class="info-box-number"><?= count($gallery) ?></span>
                                </div>
                            </div>

                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-newspaper"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Berita</span>
                                    <span class="info-box-number"><?= count($posts) ?></span>
                                </div>
                            </div>

                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-star"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Gambar Utama</span>
                                    <span class="info-box-number">
                                        <?= count(array_filter($gallery, function($img) { return $img->is_primary; })) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Berita Terbaru</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <?php foreach (array_slice($posts, 0, 5) as $post) : ?>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?= esc($post->judul) ?></h6>
                                            <small class="text-muted"><?= date('d/m/Y', strtotime($post->created_at)) ?></small>
                                        </div>
                                        <?php if ($post->cover_image) : ?>
                                            <img src="<?= base_url('public/uploads/berita/cover/' . $post->cover_image) ?>" 
                                                 alt="Cover" class="img-thumbnail mt-2" style="max-width: 100%; height: 60px; object-fit: cover;">
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus gambar ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a href="#" id="confirmDelete" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
$(document).ready(function() {
    // Handle checkbox selection
    $('.gallery-checkbox').on('change', function() {
        var checkedCount = $('.gallery-checkbox:checked').length;
        if (checkedCount > 0) {
            $('#bulkDeleteBtn').show();
        } else {
            $('#bulkDeleteBtn').hide();
        }
    });
});

function deleteImage(id) {
    $('#confirmDelete').attr('href', '<?= base_url('admin/berita-gallery/delete/') ?>' + id);
    $('#deleteModal').modal('show');
}

function setPrimaryImage(id) {
    $.get('<?= base_url('admin/berita-gallery/set-primary-image/') ?>' + id)
        .done(function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        })
        .fail(function() {
            alert('Terjadi kesalahan saat mengatur gambar utama');
        });
}

function bulkDelete() {
    var checkedIds = [];
    $('.gallery-checkbox:checked').each(function() {
        checkedIds.push($(this).val());
    });

    if (checkedIds.length === 0) {
        alert('Pilih gambar yang akan dihapus');
        return;
    }

    if (confirm('Apakah Anda yakin ingin menghapus ' + checkedIds.length + ' gambar yang dipilih?')) {
        $.post('<?= base_url('admin/berita-gallery/bulk-delete') ?>', {ids: checkedIds})
            .done(function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            })
            .fail(function() {
                alert('Terjadi kesalahan saat menghapus gambar');
            });
    }
}
</script>
<?= $this->endSection() ?>
