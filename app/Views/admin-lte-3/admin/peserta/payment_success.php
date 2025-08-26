<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-check-circle mr-2"></i>
                    Payment Success
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <h5><i class="fas fa-check mr-2"></i> Pembayaran Berhasil!</h5>
                    <p>Terima kasih, pembayaran Anda telah berhasil diproses.</p>
                    <p>Peserta telah terdaftar dengan kode: <strong><?= session('generated_code') ?? 'N/A' ?></strong></p>
                </div>
                
                <div class="text-center">
                    <a href="<?= base_url('admin/peserta/daftar') ?>" class="btn btn-primary">
                        <i class="fas fa-list mr-2"></i> Lihat Daftar Peserta
                    </a>
                    <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary">
                        <i class="fas fa-home mr-2"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 