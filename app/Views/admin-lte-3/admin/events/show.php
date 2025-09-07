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
                     <a href="<?= base_url('admin/events/print/' . $event->id) ?>" class="btn btn-sm btn-success rounded-0" target="_blank">
                         <i class="fas fa-print"></i> Cetak
                     </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- Event Header -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1"><?= $event->event ?></h4>
                                <p class="text-muted mb-0"><?= $event->kode ?: 'Tidak ada kode' ?></p>
                            </div>
                            <div>
                                <?php if ($event->status == 1): ?>
                                    <span class="badge badge-success badge-lg">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary badge-lg">Tidak Aktif</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

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
                                    <?= $kategori ? $kategori->kategori : 'Tidak ada kategori' ?>
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
                                    <?= $total_participants ?> peserta
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Sisa Kapasitas:</strong></td>
                                <td>
                                    <?php if ($available_capacity === 'Unlimited'): ?>
                                        Tidak terbatas
                                    <?php else: ?>
                                        <?= $available_capacity ?> peserta
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

                <?php if (isset($participants) && $participants): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5><i class="fas fa-users"></i> Daftar Peserta</h5>
                            <a href="<?= base_url('admin/peserta/daftar?event_id=' . $event->id) ?>" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-list"></i> Lihat Semua
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Telepon</th>
                                        <th>Tanggal Daftar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($participants as $participant): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <strong><?= $participant->nama ?></strong>
                                            <?php if ($participant->status_hadir == '1'): ?>
                                                <span class="badge badge-success badge-sm ml-1">Hadir</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $participant->email ?></td>
                                        <td><?= $participant->no_hp ?: '-' ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($participant->created_at)) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada peserta</h5>
                            <p class="text-muted">Event ini belum memiliki peserta yang terdaftar.</p>
                            <a href="<?= base_url('admin/peserta/daftar?event_id=' . $event->id) ?>" 
                               class="btn btn-primary">
                                <i class="fas fa-plus"></i> Lihat Peserta
                            </a>
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

<!-- Action Buttons Card -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="<?= base_url('admin/events/edit/' . $event->id) ?>" 
                           class="btn btn-warning btn-block">
                            <i class="fas fa-edit"></i> Edit Event
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="<?= base_url('admin/event-gallery/manage/' . $event->id) ?>" 
                           class="btn btn-info btn-block">
                            <i class="fas fa-images"></i> Kelola Galeri
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="<?= base_url('admin/events/pricing/' . $event->id) ?>" 
                           class="btn btn-success btn-block">
                            <i class="fas fa-tags"></i> Kelola Harga
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="<?= base_url('admin/peserta/daftar?event_id=' . $event->id) ?>" 
                           class="btn btn-primary btn-block">
                            <i class="fas fa-users"></i> Lihat Peserta
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
function openImageModal(imageSrc, description) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('modalDescription').textContent = description;
    $('#imageModal').modal('show');
}

// Add some interactive features
$(document).ready(function() {
    // Add hover effects to gallery images
    $('.card-img-top').hover(
        function() {
            $(this).css('transform', 'scale(1.05)');
            $(this).css('transition', 'transform 0.3s ease');
        },
        function() {
            $(this).css('transform', 'scale(1)');
        }
    );
    
    // Add click to copy functionality for event code
    $('[data-copy]').click(function() {
        var text = $(this).text();
        navigator.clipboard.writeText(text).then(function() {
            toastr.success('Kode event berhasil disalin!');
        });
    });
});
</script>
<?= $this->endSection() ?>
