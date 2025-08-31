<?= $this->extend(theme_path('main')) ?>

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

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Kategori Berita</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/berita-category/create') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Kategori
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="categoryTable">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th width="80">Ikon</th>
                                    <th>Nama Kategori</th>
                                    <th>Slug</th>
                                    <th width="100">Urutan</th>
                                    <th width="100">Status</th>
                                    <th width="120">Tanggal</th>
                                    <th width="120">Aksi</th>
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
                                                    <div class="bg-secondary text-white text-center p-2" style="width: 40px; height: 40px; line-height: 36px;">
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
                                                <?php if ($category->is_active) : ?>
                                                    <span class="badge badge-success">Aktif</span>
                                                <?php else : ?>
                                                    <span class="badge badge-danger">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= date('d/m/Y H:i', strtotime($category->created_at)) ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?= base_url('admin/berita-category/edit/' . $category->id) ?>" 
                                                       class="btn btn-sm btn-info" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-warning" 
                                                            onclick="toggleStatus(<?= $category->id ?>)" title="Toggle Status">
                                                        <i class="fas fa-toggle-on"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            onclick="deleteCategory(<?= $category->id ?>)" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
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

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
$(document).ready(function() {
    $('#categoryTable').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#categoryTable_wrapper .col-md-6:eq(0)');
});

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
