<?= $this->extend('admin-lte-3/layout/main_front') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-danger">
                <h3 class="card-title text-white">
                    <i class="fas fa-times-circle mr-2"></i>
                    Pembayaran Gagal
                </h3>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <i class="fas fa-times-circle text-danger" style="font-size: 4rem;"></i>
                </div>
                
                <h4 class="text-danger mb-3">Pembayaran Anda Gagal Diproses!</h4>
                
                <div class="alert alert-danger">
                    <h6><i class="fas fa-exclamation-triangle mr-2"></i>Informasi Peserta</h6>
                    <p><strong>Nama:</strong> <?= $peserta->nama_lengkap ?></p>
                    <p><strong>Kode Peserta:</strong> <?= $peserta->kode_peserta ?></p>
                    <p><strong>Status:</strong> <span class="badge badge-warning">Menunggu Pembayaran</span></p>
                </div>

                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle mr-2"></i>Langkah Selanjutnya</h6>
                    <ul class="text-left mb-0">
                        <li>Silakan coba lagi dengan metode pembayaran yang berbeda</li>
                        <li>Pastikan saldo atau limit kartu Anda mencukupi</li>
                        <li>Periksa kembali data pembayaran yang dimasukkan</li>
                        <li>Hubungi customer service jika masalah berlanjut</li>
                    </ul>
                </div>

                <div class="mt-4">
                    <a href="<?= base_url('frontend') ?>" class="btn btn-primary">
                        <i class="fas fa-redo mr-2"></i>
                        Coba Lagi
                    </a>
                    <a href="<?= base_url('admin') ?>" class="btn btn-outline-secondary ml-2">
                        <i class="fas fa-headset mr-2"></i>
                        Hubungi Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
