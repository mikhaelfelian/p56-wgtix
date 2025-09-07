<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title"><?= $title ?></h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/berita/create') ?>" class="btn btn-primary btn-sm rounded-0">
                        <i class="fas fa-plus"></i> Tambah Berita
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Search and Filter Form -->
                <form method="GET" action="<?= base_url('admin/berita') ?>" class="mb-3">
                    <div class="form-row">
                        <div class="col-md-4 mb-2">
                            <input type="text" class="form-control" name="keyword" 
                                   placeholder="Cari berita..." value="<?= esc($keyword) ?>">
                        </div>
                        <div class="col-md-3 mb-2">
                            <select class="form-control" name="kategori">
                                <option value="">Semua Kategori</option>
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?= $category->id ?>" <?= (isset($kategori) && $kategori == $category->id) ? 'selected' : '' ?>>
                                        <?= esc($category->nama) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2 d-flex">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-search"></i> Cari
                            </button>
                            <a href="<?= base_url('admin/berita') ?>" class="btn btn-secondary">
                                <i class="fas fa-sync-alt"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Results Summary -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="12%">Cover</th>
                                <th>Judul</th>
                                <th width="15%">Kategori</th>
                                <th width="10%">Status</th>
                                <th width="15%">Tanggal</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($posts)) : ?>
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data berita</td>
                                </tr>
                            <?php else : ?>
                                <?php $no = ($currentPage - 1) * $perPage + 1; ?>
                                <?php foreach ($posts as $post) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <?php if ($post->cover_image) : ?>
                                                <img src="<?= base_url('/file/posts/'.$post->id.'/' . $post->cover_image) ?>" 
                                                     alt="Cover" class="img-thumbnail" style="max-width: 80px; max-height:60px;">
                                            <?php else : ?>
                                                <div class="bg-secondary text-white text-center d-flex align-items-center justify-content-center" style="width: 80px; height: 60px;">
                                                    <i class="fas fa-image fa-2x"></i>
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
                                            <a href="<?= base_url('admin/berita/edit/' . $post->id) ?>"
                                                class="btn btn-info btn-sm rounded-0" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('admin/berita/gallery/' . $post->id) ?>"
                                                class="btn btn-warning btn-sm rounded-0" title="Galeri">
                                                <i class="fas fa-images"></i>
                                            </a>
                                            <a href="<?= base_url('admin/berita/delete/' . $post->id) ?>"
                                                class="btn btn-danger btn-sm rounded-0"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <?= $pager->links('default', 'adminlte_pagination') ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
