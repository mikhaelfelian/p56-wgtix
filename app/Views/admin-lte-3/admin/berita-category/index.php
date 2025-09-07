<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title"><?= $title ?></h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/berita-category/create') ?>" class="btn btn-primary btn-sm rounded-0">
                        <i class="fas fa-plus"></i> Tambah Kategori
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Flash Messages -->
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <small class="text-muted">
                        Menampilkan <?= count($categories) ?> kategori
                    </small>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="categoryTable">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Ikon</th>
                                <th>Nama Kategori</th>
                                <th>Slug</th>
                                <th width="8%">Urutan</th>
                                <th width="10%">Status</th>
                                <th width="15%">Tanggal</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($categories)) : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data kategori</td>
                                </tr>
                            <?php else : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($categories as $category) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <?php if ($category->ikon) : ?>
                                                <i class="<?= esc($category->ikon) ?>" style="font-size: 24px;"></i>
                                            <?php else : ?>
                                                <div class="bg-secondary text-white text-center d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-folder"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?= esc($category->nama) ?></strong>
                                            <?php if ($category->deskripsi) : ?>
                                                <br><small class="text-muted"><?= esc($category->deskripsi) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <code><?= esc($category->slug) ?></code>
                                        </td>
                                        <td>
                                            <span class="badge badge-info"><?= $category->urutan ?></span>
                                        </td>
                                        <td>
                                            <?php if ($category->is_active == 1) : ?>
                                                <span class="badge badge-success">Aktif</span>
                                            <?php else : ?>
                                                <span class="badge badge-danger">Nonaktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= $category->created_at ? date('d/m/Y H:i', strtotime($category->created_at)) : '-' ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('admin/berita-category/edit/' . $category->id) ?>"
                                               class="btn btn-info btn-sm rounded-0" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-warning btn-sm rounded-0"
                                                    onclick="toggleStatus(<?= $category->id ?>)" title="Toggle Status">
                                                <i class="fas fa-toggle-on"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm rounded-0"
                                                    onclick="deleteCategory(<?= $category->id ?>)" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
                <p>Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a href="#" id="confirmDelete" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
function deleteCategory(id) {
    $('#confirmDelete').attr('href', '<?= base_url('admin/berita-category/delete/') ?>' + id);
    $('#deleteModal').modal('show');
}

function toggleStatus(id) {
    $.get('<?= base_url('admin/berita-category/toggle-status/') ?>' + id)
        .done(function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        })
        .fail(function() {
            alert('Terjadi kesalahan saat mengubah status');
        });
}
</script>
<?= $this->endSection() ?>
