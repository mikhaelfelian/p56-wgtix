<?= $this->extend('admin-lte-3/layout/main_front') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success">
                <h3 class="card-title text-white">
                    <i class="fas fa-check-circle mr-2"></i>
                    Pembayaran Berhasil
                </h3>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                
                <h4 class="text-success mb-3">Pembayaran Anda Berhasil Diproses!</h4>
                
                <div class="alert alert-info">
                    <h6><i class="fas fa-user mr-2"></i>Informasi Peserta</h6>
                    <p><strong>Nama:</strong> <?= $peserta->nama_lengkap ?></p>
                    <p><strong>Kode Peserta:</strong> <?= $peserta->kode_peserta ?></p>
                    <p><strong>Status:</strong> <span class="badge badge-success">Terdaftar</span></p>
                </div>

                <?php if ($peserta->qr_code): ?>
                <div class="mb-4">
                    <h6><i class="fas fa-qrcode mr-2"></i>QR Code Peserta</h6>
                    <img src="data:image/png;base64,<?= $peserta->qr_code ?>" alt="QR Code" style="max-width: 200px;" class="img-fluid">
                    <p class="text-muted mt-2">Simpan QR Code ini untuk keperluan check-in</p>
                </div>
                <?php endif; ?>

                <div class="alert alert-warning">
                    <h6><i class="fas fa-info-circle mr-2"></i>Informasi Penting</h6>
                    <ul class="text-left mb-0">
                        <li>Simpan kode peserta dan QR Code dengan baik</li>
                        <li>Bawa identitas asli pada hari event</li>
                        <li>Datang minimal 30 menit sebelum event dimulai</li>
                        <li>Ikuti instruksi panitia untuk check-in</li>
                    </ul>
                </div>

                <div class="mt-4">
                    <a href="<?= base_url('frontend') ?>" class="btn btn-primary">
                        <i class="fas fa-home mr-2"></i>
                        Kembali ke Beranda
                    </a>
                    <button type="button" class="btn btn-outline-secondary ml-2" onclick="window.print()">
                        <i class="fas fa-print mr-2"></i>
                        Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
