<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<div class="hero-section mb-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="hero-card">
                    <div class="hero-content">
                        <div class="hero-text">
                            <h1 class="hero-title"><?= $event->event ?></h1>
                            <p class="hero-subtitle"><?= $event->kode ?: 'Tidak ada kode' ?></p>
                            <div class="hero-meta">
                                <span class="meta-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <?= date('d M Y', strtotime($event->tgl_masuk)) ?> - <?= date('d M Y', strtotime($event->tgl_keluar)) ?>
                                </span>
                                <span class="meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= $event->lokasi ?: 'Lokasi belum ditentukan' ?>
                                </span>
                                <span class="meta-item">
                                    <i class="fas fa-users"></i>
                                    <?= $total_participants ?> peserta terdaftar
                                </span>
                            </div>
                        </div>
                        <div class="hero-actions">
                            <?php if ($event->status == 1): ?>
                                <span class="status-badge status-active">
                                    <i class="fas fa-check-circle"></i> Aktif
                                </span>
                            <?php else: ?>
                                <span class="status-badge status-inactive">
                                    <i class="fas fa-pause-circle"></i> Tidak Aktif
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions mb-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="action-card">
                    <div class="action-buttons">
                        <a href="<?= base_url('admin/events') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="<?= base_url('admin/events/edit/' . $event->id) ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Event
                        </a>
                        <a href="<?= base_url('admin/events/absen/' . $event->id) ?>" class="btn btn-success" target="_blank">
                            <i class="fas fa-qrcode"></i> Absensi QR
                        </a>
                        <a href="<?= base_url('admin/events/print/' . $event->id) ?>" class="btn btn-info" target="_blank">
                            <i class="fas fa-print"></i> Cetak Peserta
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <div class="col-lg-8">
        <!-- Event Details Card -->
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-info-circle"></i> Informasi Event
                </h5>
            </div>
            <div class="card-body">

                <!-- Event Information Grid -->
                <div class="info-grid">
                    <div class="info-section">
                        <h6 class="section-title">Informasi Dasar</h6>
                        <div class="info-items">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-tag"></i> Kode Event
                                </div>
                                <div class="info-value"><?= $event->kode ?: '-' ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-folder"></i> Kategori
                                </div>
                                <div class="info-value">
                                    <?= $kategori ? $kategori->kategori : 'Tidak ada kategori' ?>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-map-marker-alt"></i> Lokasi
                                </div>
                                <div class="info-value"><?= $event->lokasi ?: 'Belum ditentukan' ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="info-section">
                        <h6 class="section-title">Jadwal Event</h6>
                        <div class="info-items">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-calendar-day"></i> Tanggal Mulai
                                </div>
                                <div class="info-value"><?= date('d M Y', strtotime($event->tgl_masuk)) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-calendar-check"></i> Tanggal Selesai
                                </div>
                                <div class="info-value"><?= date('d M Y', strtotime($event->tgl_keluar)) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-clock"></i> Waktu
                                </div>
                                <div class="info-value"><?= $event->wkt_masuk ?> - <?= $event->wkt_keluar ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="info-section">
                        <h6 class="section-title">Kapasitas & Peserta</h6>
                        <div class="info-items">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-users"></i> Kapasitas
                                </div>
                                <div class="info-value">
                                    <?php if ($event->jml > 0): ?>
                                        <?= $event->jml ?> peserta
                                    <?php else: ?>
                                        Tidak terbatas
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-user-check"></i> Terdaftar
                                </div>
                                <div class="info-value"><?= $total_participants ?> peserta</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-user-plus"></i> Sisa Kuota
                                </div>
                                <div class="info-value">
                                    <?php if ($available_capacity === 'Unlimited'): ?>
                                        Tidak terbatas
                                    <?php else: ?>
                                        <?= $available_capacity ?> peserta
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-section">
                        <h6 class="section-title">Informasi Sistem</h6>
                        <div class="info-items">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-plus-circle"></i> Dibuat
                                </div>
                                <div class="info-value"><?= date('d M Y H:i', strtotime($event->created_at)) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-edit"></i> Diupdate
                                </div>
                                <div class="info-value"><?= date('d M Y H:i', strtotime($event->updated_at)) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-qrcode"></i> Absensi
                                </div>
                                <div class="info-value">
                                    <a href="<?= base_url('admin/events/absen/' . $event->id) ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="fas fa-external-link-alt"></i> Buka Absensi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Participants Section -->
                <div class="participants-section">
                    <div class="section-header">
                        <h6 class="section-title">
                            <i class="fas fa-users"></i> Daftar Peserta Terbaru
                        </h6>
                        <a href="<?= base_url('admin/peserta/daftar?event_id=' . $event->id) ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-list"></i> Lihat Semua
                        </a>
                    </div>
                    
                    <?php if (isset($participants) && $participants): ?>
                        <div class="participants-list">
                            <?php foreach ($participants as $participant): ?>
                                <div class="participant-card">
                                    <div class="participant-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="participant-info">
                                        <div class="participant-name">
                                            <?= $participant->nama ?>
                                            <?php if ($participant->status_hadir == '1'): ?>
                                                <span class="attendance-badge">
                                                    <i class="fas fa-check"></i> Hadir
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="participant-details">
                                            <span class="detail-item">
                                                <i class="fas fa-envelope"></i> <?= $participant->email ?>
                                            </span>
                                            <span class="detail-item">
                                                <i class="fas fa-phone"></i> <?= $participant->no_hp ?: 'Tidak ada' ?>
                                            </span>
                                        </div>
                                        <div class="participant-date">
                                            <i class="fas fa-calendar"></i> Daftar: <?= date('d M Y H:i', strtotime($participant->created_at)) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h5>Belum ada peserta</h5>
                            <p>Event ini belum memiliki peserta yang terdaftar.</p>
                            <a href="<?= base_url('admin/peserta/daftar?event_id=' . $event->id) ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Lihat Peserta
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Statistics Card -->
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-chart-pie"></i> Statistik Event
                </h5>
            </div>
            <div class="card-body">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $total_participants ?></div>
                            <div class="stat-label">Total Peserta</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $attended_participants ?></div>
                            <div class="stat-label">Sudah Hadir</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-percentage"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $attendance_rate ?>%</div>
                            <div class="stat-label">Tingkat Kehadiran</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">
                                <?php if ($available_capacity === 'Unlimited'): ?>
                                    ∞
                                <?php else: ?>
                                    <?= $available_capacity ?>
                                <?php endif; ?>
                            </div>
                            <div class="stat-label">Sisa Kuota</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-bolt"></i> Aksi Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="action-list">
                    <a href="<?= base_url('admin/events/edit/' . $event->id) ?>" class="action-item">
                        <div class="action-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="action-content">
                            <div class="action-title">Edit Event</div>
                            <div class="action-desc">Ubah informasi event</div>
                        </div>
                    </a>
                    <a href="<?= base_url('admin/event-gallery/manage/' . $event->id) ?>" class="action-item">
                        <div class="action-icon">
                            <i class="fas fa-images"></i>
                        </div>
                        <div class="action-content">
                            <div class="action-title">Kelola Galeri</div>
                            <div class="action-desc">Tambah/edit foto event</div>
                        </div>
                    </a>
                    <a href="<?= base_url('admin/events/pricing/' . $event->id) ?>" class="action-item">
                        <div class="action-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div class="action-content">
                            <div class="action-title">Kelola Harga</div>
                            <div class="action-desc">Atur harga tiket</div>
                        </div>
                    </a>
                    <a href="<?= base_url('admin/peserta/daftar?event_id=' . $event->id) ?>" class="action-item">
                        <div class="action-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="action-content">
                            <div class="action-title">Lihat Peserta</div>
                            <div class="action-desc">Kelola daftar peserta</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
/* Professional Event Show Page Styles */
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    margin: -20px -20px 20px -20px;
    padding: 30px;
    color: white;
}

.hero-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    padding: 30px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.hero-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.hero-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0 0 10px 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.hero-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0 0 20px 0;
}

.hero-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.95rem;
    opacity: 0.9;
}

.meta-item i {
    font-size: 1rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-active {
    background: rgba(40, 167, 69, 0.2);
    border: 2px solid rgba(40, 167, 69, 0.3);
    color: #28a745;
}

.status-inactive {
    background: rgba(108, 117, 125, 0.2);
    border: 2px solid rgba(108, 117, 125, 0.3);
    color: #6c757d;
}

.quick-actions {
    margin-bottom: 30px;
}

.action-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
}

.action-buttons {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.action-buttons .btn {
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.action-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.modern-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    margin-bottom: 20px;
    overflow: hidden;
}

.modern-card .card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    padding: 20px 25px;
}

.modern-card .card-title {
    margin: 0;
    font-weight: 600;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modern-card .card-body {
    padding: 25px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
    margin-top: 20px;
}

.info-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    border-left: 4px solid #007bff;
}

.section-title {
    font-size: 1rem;
    font-weight: 600;
    color: #495057;
    margin: 0 0 15px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-items {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #e9ecef;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    color: #6c757d;
    font-size: 0.9rem;
}

.info-value {
    font-weight: 600;
    color: #495057;
    text-align: right;
}

.participants-section {
    margin-top: 30px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}

.participants-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.participant-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.participant-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.participant-avatar {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.participant-info {
    flex: 1;
}

.participant-name {
    font-weight: 600;
    font-size: 1.1rem;
    color: #495057;
    margin-bottom: 5px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.attendance-badge {
    background: #d4edda;
    color: #155724;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 4px;
}

.participant-details {
    display: flex;
    flex-direction: column;
    gap: 5px;
    margin-bottom: 8px;
}

.detail-item {
    font-size: 0.9rem;
    color: #6c757d;
    display: flex;
    align-items: center;
    gap: 8px;
}

.participant-date {
    font-size: 0.85rem;
    color: #adb5bd;
    display: flex;
    align-items: center;
    gap: 8px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 2px dashed #dee2e6;
}

.empty-icon {
    font-size: 4rem;
    color: #adb5bd;
    margin-bottom: 20px;
}

.empty-state h5 {
    color: #6c757d;
    margin-bottom: 10px;
}

.empty-state p {
    color: #adb5bd;
    margin-bottom: 25px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.stat-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.stat-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 1.8rem;
    font-weight: 700;
    color: #495057;
    line-height: 1;
}

.stat-label {
    font-size: 0.85rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 5px;
}

.action-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.action-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.action-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
    text-decoration: none;
    color: inherit;
    border-color: #dee2e6;
}

.action-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.action-content {
    flex: 1;
}

.action-title {
    font-weight: 600;
    color: #495057;
    margin-bottom: 2px;
}

.action-desc {
    font-size: 0.85rem;
    color: #6c757d;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-content {
        flex-direction: column;
        text-align: center;
    }
    
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-meta {
        justify-content: center;
    }
    
    .action-buttons {
        justify-content: center;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .participant-card {
        flex-direction: column;
        text-align: center;
    }
    
    .participant-details {
        align-items: center;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modern-card {
    animation: fadeInUp 0.6s ease-out;
}

.participant-card {
    animation: fadeInUp 0.4s ease-out;
}

.stat-item {
    animation: fadeInUp 0.5s ease-out;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
$(document).ready(function() {
    // Add hover effects to gallery images
    $('.card-img-top').hover(
        function () {
            $(this).css('transform', 'scale(1.05)');
            $(this).css('transition', 'transform 0.3s ease');
        },
        function () {
            $(this).css('transform', 'scale(1)');
        }
    );

    // Add click to copy functionality for event code
    $('[data-copy]').click(function () {
        var text = $(this).text();
        navigator.clipboard.writeText(text).then(function () {
            toastr.success('Kode event berhasil disalin!');
        });
    });
});
</script>
<?= $this->endSection() ?>