<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">Detail Event</h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/events') ?>" class="btn btn-sm btn-secondary rounded-0">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <a href="<?= base_url('admin/events/edit/' . $event->id) ?>" class="btn btn-sm btn-primary rounded-0">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Kode Event:</strong></td>
                                <td><?= $event->kode ?: '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Nama Event:</strong></td>
                                <td><?= $event->event ?></td>
                            </tr>
                            <tr>
                                <td><strong>Kategori:</strong></td>
                                <td>
                                    <?php 
                                    $kategori = $this->kategoriModel->find($event->id_kategori);
                                    echo $kategori ? $kategori->kategori : 'Tidak ada kategori';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <?php if ($event->status == 1): ?>
                                        <span class="badge badge-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Tidak Aktif</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Tanggal Mulai:</strong></td>
                                <td><?= date('d/m/Y', strtotime($event->tgl_masuk)) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Selesai:</strong></td>
                                <td><?= date('d/m/Y', strtotime($event->tgl_keluar)) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Waktu Mulai:</strong></td>
                                <td><?= $event->wkt_masuk ?></td>
                            </tr>
                            <tr>
                                <td><strong>Waktu Selesai:</strong></td>
                                <td><?= $event->wkt_keluar ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Lokasi:</strong></td>
                                <td><?= $event->lokasi ?: '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Kapasitas:</strong></td>
                                <td>
                                    <?php if ($event->jml > 0): ?>
                                        <?= $event->jml ?> peserta
                                    <?php else: ?>
                                        Tidak terbatas
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Peserta Terdaftar:</strong></td>
                                <td>
                                    <?php if (isset($capacity_info)): ?>
                                        <?= $capacity_info['registered_count'] ?> peserta
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Sisa Kapasitas:</strong></td>
                                <td>
                                    <?php if (isset($capacity_info)): ?>
                                        <?php if ($capacity_info['available_capacity'] === 'Unlimited'): ?>
                                            Tidak terbatas
                                        <?php else: ?>
                                            <?= $capacity_info['available_capacity'] ?> peserta
                                        <?php endif; ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Dibuat:</strong></td>
                                <td><?= date('d/m/Y H:i', strtotime($event->created_at)) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Diupdate:</strong></td>
                                <td><?= date('d/m/Y H:i', strtotime($event->updated_at)) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Keterangan:</strong></td>
                                <td><?= $event->keterangan ?: '-' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php if (isset($pricing) && $pricing): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <h5><i class="fas fa-tags"></i> Informasi Harga</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Harga</th>
                                        <th>Status</th>
                                        <th>Dibuat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pricing as $price): ?>
                                    <tr>
                                        <td>Rp <?= number_format($price->harga, 0, ',', '.') ?></td>
                                        <td>
                                            <?php if ($price->status == '1'): ?>
                                                <span class="badge badge-success">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Tidak Aktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($price->created_at)) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (isset($gallery) && $gallery): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <h5><i class="fas fa-images"></i> Galeri Event</h5>
                        <div class="row">
                            <?php foreach ($gallery as $item): ?>
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <img src="<?= base_url('uploads/events/' . $item->file) ?>" 
                                         class="card-img-top" 
                                         alt="<?= $item->deskripsi ?: 'Event Image' ?>"
                                         style="height: 150px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <p class="card-text small mb-1"><?= $item->deskripsi ?: 'Tidak ada deskripsi' ?></p>
                                        <?php if ($item->is_cover): ?>
                                            <span class="badge badge-primary">Cover</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
<?= $this->endSection() ?>
