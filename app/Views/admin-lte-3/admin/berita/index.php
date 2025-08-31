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
                    <h3 class="card-title">Daftar Berita</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/berita/create') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Berita
                        </a>
                    </div>
                </div>
                
                <!-- Search and Filter Form -->
                <div class="card-body">
                    <form method="GET" action="<?= base_url('admin/berita') ?>" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="keyword" 
                                           placeholder="Cari berita..." value="<?= esc($keyword) ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-control" name="kategori">
                                        <option value="">Semua Kategori</option>
                                        <?php foreach ($categories as $category) : ?>
                                            <option value="<?= $category->id ?>" <?= $kategori == $category->id ? 'selected' : '' ?>>
                                                <?= esc($category->nama) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <a href="<?= base_url('admin/berita') ?>" class="btn btn-secondary">
                                    <i class="fas fa-refresh"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Results Summary -->
                    <div class="mb-3">
                        <small class="text-muted">
                            Menampilkan <?= ($currentPage - 1) * $perPage + 1 ?> - 
                            <?= min($currentPage * $perPage, $total) ?> 
                            dari <?= $total ?> berita
                        </small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="beritaTable">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th width="100">Cover</th>
                                    <th>Judul</th>
                                    <th width="120">Kategori</th>
                                    <th width="100">Status</th>
                                    <th width="120">Tanggal</th>
                                    <th width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($posts)) : ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data berita</td>
                                    </tr>
                                <?php else : ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($posts as $post) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td>
                                                <?php if ($post->cover_image) : ?>
                                                    <img src="<?= base_url('public/uploads/berita/cover/' . $post->cover_image) ?>" 
                                                         alt="Cover" class="img-thumbnail" style="max-width: 80px;">
                                                <?php else : ?>
                                                    <div class="bg-secondary text-white text-center p-2" style="width: 80px; height: 60px; line-height: 56px;">
                                                        <i class="fas fa-image"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?= esc($post->judul) ?></strong><br>
                                                <small class="text-muted">
                                                    <?= esc($post->excerpt ?: substr(strip_tags($post->konten), 0, 100) . '...') ?>
                                                </small>
                                            </td>
                                            <td>
                                                <?php 
                                                $category = array_filter($categories, function($cat) use ($post) {
                                                    return $cat->id == $post->id_category;
                                                });
                                                $category = reset($category);
                                                echo $category ? esc($category->nama) : '<span class="text-muted">-</span>';
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = [
                                                    'draft' => 'badge badge-secondary',
                                                    'scheduled' => 'badge badge-warning',
                                                    'published' => 'badge badge-success',
                                                    'archived' => 'badge badge-danger'
                                                ];
                                                $statusText = [
                                                    'draft' => 'Draft',
                                                    'scheduled' => 'Terjadwal',
                                                    'published' => 'Dipublikasi',
                                                    'archived' => 'Diarsipkan'
                                                ];
                                                ?>
                                                <span class="<?= $statusClass[$post->status] ?? 'badge badge-secondary' ?>">
                                                    <?= $statusText[$post->status] ?? ucfirst($post->status) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= date('d/m/Y H:i', strtotime($post->created_at)) ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?= base_url('admin/berita/edit/' . $post->id) ?>" 
                                                       class="btn btn-sm btn-info" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="<?= base_url('admin/berita/gallery/' . $post->id) ?>" 
                                                       class="btn btn-sm btn-warning" title="Galeri">
                                                        <i class="fas fa-images"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            onclick="deleteBerita(<?= $post->id ?>)" title="Hapus">
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
                    
                    <!-- Pagination -->
                    <?php if (isset($pager) && $pager): ?>
                        <div class="card-footer clearfix">
                            <div class="float-right">
                                <?= $pager->links('default', 'adminlte_pagination') ?>
                            </div>
                        </div>
                    <?php endif; ?>
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
                <p>Apakah Anda yakin ingin menghapus berita ini? Tindakan ini tidak dapat dibatalkan.</p>
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
    $('#beritaTable').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#beritaTable_wrapper .col-md-6:eq(0)');
});

function deleteBerita(id) {
    $('#confirmDelete').attr('href', '<?= base_url('admin/berita/delete/') ?>' + id);
    $('#deleteModal').modal('show');
}
</script>
<?= $this->endSection() ?>
