<?php

/**
 * @description Tampilan detail transaksi admin - menampilkan informasi pesanan lengkap beserta kontrol manajemen
 * @author Tim Pengembang CodeIgniter
 * @since 2025-01-15
 * @version 1.0.0
 */

echo $this->extend(theme_path('main')); ?>

<?= $this->section('content') ?>

<!-- Konten Utama -->
<section class="content rounded-0">
    <div class="container-fluid rounded-0">
        <!-- Header Pesanan -->
        <div class="row">
            <div class="col-12">
                <div class="card rounded-0">
                    <div class="card-header rounded-0">
                        <h3 class="card-title">
                            <i class="fas fa-file-invoice"></i> Invoice #<?= esc($order->invoice_no) ?>
                        </h3>
                        <div class="card-tools">
                            <a href="<?= base_url('admin/transaksi/sale/orders') ?>" class="btn btn-sm btn-secondary rounded-0">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <a href="<?= base_url('admin/transaksi/sale/invoice/' . $order->id) ?>" class="btn btn-sm btn-success rounded-0">
                                <i class="fas fa-file-pdf"></i> Invoice PDF
                            </a>
                            <a href="<?= base_url('admin/transaksi/sale/tickets/' . $order->id) ?>" class="btn btn-sm btn-info rounded-0">
                                <i class="fas fa-ticket-alt"></i> Semua Tiket
                            </a>
                        </div>
                    </div>
                    <div class="card-body rounded-0">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Informasi Pesanan</h5>
                                <table class="table table-sm rounded-0">
                                    <tr>
                                        <td><strong>No. Invoice:</strong></td>
                                        <td><?= esc($order->invoice_no) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Invoice:</strong></td>
                                        <td><?= tgl_indo8($order->invoice_date) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Metode Pembayaran:</strong></td>
                                        <td><?= esc($order->payment_method) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total:</strong></td>
                                        <td><strong class="text-success">Rp <?= format_angka($order->total_amount) ?></strong></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Informasi Pelanggan</h5>
                                <table class="table table-sm rounded-0">
                                    <?php if ($user): ?>
                                        <tr>
                                            <td><strong>Nama:</strong></td>
                                            <td><?= esc(trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''))) ?: '-' ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Username:</strong></td>
                                            <td><?= strtolower($user->username ?? '-') ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td><?= esc($user->email ?? '-') ?></td>
                                        </tr>
                                        <?php if (!empty($user->phone)): ?>
                                        <tr>
                                            <td><strong>Telepon:</strong></td>
                                            <td>
                                                <?php if (!empty($user->phone) && $user->phone !== '-' && $user->phone !== '0'): ?>
                                                    <?php 
                                                        $cleanPhone = preg_replace('/[^0-9]/', '', $user->phone);
                                                        if (strpos($cleanPhone, '0') === 0) {
                                                            $waPhone = '62' . substr($cleanPhone, 1);
                                                        } elseif (strpos($cleanPhone, '62') === 0) {
                                                            $waPhone = $cleanPhone;
                                                        } else {
                                                            $waPhone = $cleanPhone;
                                                        }
                                                        $waUrl = "https://wa.me/{$waPhone}";
                                                    ?>
                                                    <a href="<?= esc($waUrl) ?>" target="_blank" class="text-success" title="Chat via WhatsApp">
                                                        <i class="fab fa-whatsapp"></i> <?= esc($user->phone) ?>
                                                    </a>
                                                <?php else: ?>
                                                    <?= esc($user->phone) ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td><strong>Pelanggan:</strong></td>
                                            <td><span class="text-muted">Pengguna Tamu</span></td>
                                        </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Manajemen Status -->
        <div class="row">
            <div class="col-md-4">
                <div class="card rounded-0">
                    <div class="card-header rounded-0">
                        <h3 class="card-title">Status Pembayaran</h3>
                    </div>
                    <div class="card-body rounded-0">
                        <form method="POST" action="<?= base_url('admin/transaksi/sale/update-status/' . $order->id) ?>">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label>Status Saat Ini:</label>
                                <?php
                                $statusColor = [
                                    'pending' => 'warning',
                                    'paid' => 'success',
                                    'failed' => 'danger',
                                    'cancelled' => 'secondary'
                                ];
                                $color = $statusColor[$order->payment_status] ?? 'info';
                                ?>
                                <p><span class="badge badge-<?= $color ?> badge-lg rounded-0"><?= ucfirst($order->payment_status) ?></span></p>
                            </div>
                            <div class="form-group">
                                <label for="payment_status">Ubah Status Pembayaran:</label>
                                <select class="form-control rounded-0" name="payment_status" id="payment_status">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="pending" <?= $order->payment_status === 'pending' ? 'selected' : '' ?>>Menunggu</option>
                                    <option value="paid" <?= $order->payment_status === 'paid' ? 'selected' : '' ?>>Lunas</option>
                                    <option value="failed" <?= $order->payment_status === 'failed' ? 'selected' : '' ?>>Gagal</option>
                                    <option value="cancelled" <?= $order->payment_status === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary rounded-0">
                                <i class="fas fa-save"></i> Update
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card rounded-0">
                    <div class="card-header rounded-0">
                        <h3 class="card-title">Status Pesanan</h3>
                    </div>
                    <div class="card-body rounded-0">
                        <form method="POST" action="<?= base_url('admin/transaksi/sale/update-status/' . $order->id) ?>">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label>Status Saat Ini:</label>
                                <?php
                                $orderStatusColor = [
                                    'active' => 'primary',
                                    'cancelled' => 'secondary',
                                    'completed' => 'success'
                                ];
                                $orderColor = $orderStatusColor[$order->status] ?? 'info';
                                ?>
                                <p><span class="badge badge-<?= $orderColor ?> badge-lg rounded-0"><?= ucfirst($order->status) ?></span></p>
                            </div>
                            <div class="form-group">
                                <label for="order_status">Ubah Status Pesanan:</label>
                                <select class="form-control rounded-0" name="order_status" id="order_status">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="active" <?= $order->status === 'active' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="completed" <?= $order->status === 'completed' ? 'selected' : '' ?>>Selesai</option>
                                    <option value="cancelled" <?= $order->status === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary rounded-0">
                                <i class="fas fa-save"></i> Update
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card rounded-0">
                    <div class="card-header rounded-0">
                        <h3 class="card-title">Catatan</h3>
                    </div>
                    <div class="card-body rounded-0">
                        <div class="form-group">
                            <p><?= nl2br(esc($order->notes ?? '-')) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item Pesanan -->
        <div class="row">
            <div class="col-12">
                <div class="card rounded-0">
                    <div class="card-header rounded-0">
                        <h3 class="card-title">Item Pesanan</h3>
                    </div>
                    <div class="card-body table-responsive p-0 rounded-0">
                        <table class="table table-hover rounded-0">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Deskripsi</th>
                                    <th>Info Peserta</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Total Harga</th>
                                    <th>Tiket</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($order_details)): ?>
                                    <?php
                                    $grandTotal = 0;
                                    foreach ($order_details as $detail):
                                        $grandTotal += $detail->total_price;
                                        $itemData = json_decode($detail->item_data, true) ?: [];
                                    ?>
                                        <tr>
                                            <td>
                                                <strong><?= esc($detail->event_title ?: 'N/A') ?></strong><br>
                                                <small class="text-muted">ID Event: <?= $detail->event_id ?: 'N/A' ?></small>
                                            </td>
                                            <td>
                                                <?= esc($detail->price_description ?: 'N/A') ?><br>
                                                <small class="text-muted">ID Harga: <?= $detail->price_id ?: 'N/A' ?></small>
                                            </td>
                                            <td>
                                                <?php if (isset($itemData['participant_name'])): ?>
                                                    <strong><?= ucwords($itemData['participant_name']) ?></strong><br>
                                                    <small class="text-muted d-block">
                                                        Peserta #<?= esc($detail->sort_num ?? 'N/A') ?>
                                                    </small>
                                                    <?php if (!empty($itemData['participant_birth_date'])): ?>
                                                        <small class="text-muted d-block mt-1">
                                                            Tanggal Lahir: <?= esc($itemData['participant_birth_date']) ?><br>
                                                        </small>
                                                    <?php endif; ?>
                                                    <?php if (!empty($itemData['participant_uk']) || !empty($itemData['participant_emg'])): ?>
                                                        <small class="text-muted d-block mt-1">
                                                            <?php if (!empty($itemData['participant_uk'])): ?>
                                                                Ukuran Jersey: <?= esc(strtoupper($itemData['participant_uk'])) ?><br>
                                                            <?php endif; ?>
                                                            <?php if (!empty($itemData['participant_emg'])): ?>
                                                                Kontak Darurat: <?= esc($itemData['participant_emg']) ?><br>
                                                            <?php endif; ?>
                                                        </small>
                                                    <?php endif; ?>
                                                    <?php if (!empty($itemData['participant_ktp_file'])): ?>
                                                        <?php
                                                        $ktpPath = $itemData['participant_ktp_file'];
                                                        // KTP path format: file/sale/ktp/{orderId}/{filename}
                                                        $ktpUrl = base_url('public/' . $ktpPath);
                                                        $ktpExt = strtolower(pathinfo($ktpPath, PATHINFO_EXTENSION));
                                                        $isKtpImage = in_array($ktpExt, ['jpg', 'jpeg', 'png', 'gif']);
                                                        ?>
                                                        <div class="mt-2">
                                                            <small class="text-muted d-block mb-1">
                                                                <i class="fas fa-id-card text-success"></i> KTP:
                                                            </small>
                                                            <?php if ($isKtpImage): ?>
                                                                <a href="<?= $ktpUrl ?>" target="_blank" data-toggle="lightbox" data-gallery="ktp-gallery" data-title="KTP - <?= esc($itemData['participant_name']) ?>">
                                                                    <img src="<?= $ktpUrl ?>" alt="KTP" style="width:60px;height:40px;object-fit:cover;border:1px solid #ddd;" class="rounded">
                                                                </a>
                                                            <?php else: ?>
                                                                <a href="<?= $ktpUrl ?>" target="_blank" class="btn btn-sm btn-outline-danger rounded-0" title="Lihat KTP">
                                                                    <i class="fas fa-file-pdf"></i> KTP PDF
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php
                                                    $isIncomplete = empty($itemData['participant_uk']) || empty($itemData['participant_emg']) || empty($itemData['participant_ktp_file']);
                                                    ?>
                                                        <div class="mt-2">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-0 edit-participant-btn" data-detail-id="<?= (int) $detail->id ?>" data-order-id="<?= (int) $order->id ?>" data-participant-uk="<?= esc($itemData['participant_uk'] ?? '') ?>" data-participant-emg="<?= esc($itemData['participant_emg'] ?? '') ?>" data-participant-birth-date="<?= esc($itemData['participant_birth_date'] ?? '') ?>" title="<?= $isIncomplete ? 'Lengkapi info peserta' : 'Edit info peserta' ?>">
                                                                <i class="fas fa-edit"></i> <?= $isIncomplete ? 'Lengkapi' : 'Edit' ?>
                                                            </button>
                                                        </div>
                                                <?php else: ?>
                                                    <span class="text-muted">Tidak ada info peserta</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $detail->quantity ?: 1 ?></td>
                                            <td>Rp <?= format_angka($detail->unit_price ?: 0) ?></td>
                                            <td><strong>Rp <?= format_angka($detail->total_price ?: 0) ?></strong></td>
                                            <td>
                                                <a href="<?= base_url('admin/transaksi/sale/ticket/' . $detail->id) ?>" class="btn btn-sm btn-warning rounded-0" title="Unduh Tiket">
                                                    <i class="fas fa-ticket-alt"></i>
                                                </a>
                                                <?php if (!empty($detail->qrcode)): ?>
                                                    <span class="badge badge-success rounded-0">QR Siap</span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary rounded-0">Tidak Ada QR</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="bg-light">
                                        <td colspan="6" class="text-right"><strong>Total Keseluruhan:</strong></td>
                                        <td><strong class="text-success">Rp <?= format_angka($grandTotal) ?></strong></td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            Tidak ada item pesanan
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Pembayaran -->
        <?php if (!empty($payment_platforms)): ?>
        <div class="row">
            <div class="col-12">
                <div class="card rounded-0">
                    <div class="card-header rounded-0">
                        <h3 class="card-title">Detail Pembayaran</h3>
                    </div>
                    <div class="card-body table-responsive p-0 rounded-0">
                        <table class="table table-hover rounded-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Platform</th>
                                    <th>ID Transaksi</th>
                                    <th>Nominal</th>
                                    <th>Catatan</th>
                                    <th>Lampiran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalPayments = 0;
                                foreach ($payment_platforms as $payment):
                                    $totalPayments += $payment->nominal;
                                ?>
                                    <tr>
                                        <td><?= tgl_indo8($payment->created_at) ?></td>
                                        <td><?= esc($payment->platform) ?></td>
                                        <td><?= esc($payment->no_nota ?: 'N/A') ?></td>
                                        <td><strong>Rp <?= format_angka($payment->nominal) ?></strong></td>
                                        <td><?= esc($payment->keterangan ?: '-') ?></td>
                                        <td>
                                            <?php
                                            $files = [];
                                            if (!empty($payment->foto)) {
                                                $decoded = json_decode($payment->foto, true);
                                                if (is_array($decoded)) {
                                                    $files = $decoded;
                                                }
                                            }
                                            ?>
                                            <?php if (!empty($files)): ?>
                                                <?php foreach ($files as $file): ?>
                                                    <?php
                                                    $filePath = base_url('public/file/sale/' . $order->id . '/' . $file['filename']);
                                                    $ext = strtolower($file['extension'] ?? pathinfo($file['filename'], PATHINFO_EXTENSION));
                                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif']);
                                                    ?>
                                                    <?php if ($isImage): ?>
                                                        <a href="<?= $filePath ?>" target="_blank">
                                                            <img src="<?= $filePath ?>" style="width:40px;height:40px;object-fit:cover;" alt="<?= esc($file['original_name']) ?>" class="rounded-0">
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="<?= $filePath ?>" target="_blank">
                                                            <i class="fas fa-file-pdf text-danger"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-0 upload-payment-proof-btn" data-order-id="<?= (int) $order->id ?>" data-payment-id="<?= (int) $payment->id ?>" title="Upload bukti pembayaran">
                                                    <i class="fas fa-upload"></i> Upload bukti
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="bg-light">
                                    <td colspan="4" class="text-right"><strong>Total Pembayaran:</strong></td>
                                    <td><strong class="text-info">Rp <?= format_angka($totalPayments) ?></strong></td>
                                    <td></td>
                                </tr>
                                <?php if ($order->total_amount != $totalPayments): ?>
                                <tr class="bg-warning">
                                    <td colspan="4" class="text-right"><strong>Sisa Tagihan:</strong></td>
                                    <td><strong>Rp <?= format_angka($order->total_amount - $totalPayments) ?></strong></td>
                                    <td></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</section>

<!-- Modal Upload Bukti Pembayaran -->
<div class="modal fade rounded-0" id="uploadPaymentProofModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-0">
            <div class="modal-header rounded-0">
                <h5 class="modal-title">Upload bukti pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body rounded-0">
                <form id="uploadPaymentProofForm" action="">
                    <div id="payment-proof-dropzone" class="dropzone" style="border: 2px dashed #667eea; border-radius: 8px; background: #f8f9fa; padding: 20px; text-align: center; min-height: 180px;">
                        <div class="dz-message" data-dz-message><i class="fas fa-cloud-upload-alt fa-2x text-muted"></i><br>Seret file ke sini atau klik untuk memilih. JPG, PNG, PDF. Maks. 5MB.</div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Participant Info -->
<div class="modal fade rounded-0" id="editParticipantModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-0">
            <div class="modal-header rounded-0">
                <h5 class="modal-title">Lengkapi Info Peserta</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="editParticipantForm" method="post" action="">
                <?= csrf_field() ?>
                <input type="hidden" name="ktp_file" id="edit_ktp_file" value="">
                <div class="modal-body rounded-0">
                    <div class="form-group">
                        <label for="edit_participant_birth_date">Tanggal Lahir</label>
                        <input type="date" class="form-control rounded-0" id="edit_participant_birth_date" name="participant_birth_date" max="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="form-group">
                        <label for="edit_participant_uk">Ukuran Jersey</label>
                        <select class="form-control rounded-0" id="edit_participant_uk" name="participant_uk">
                            <option value="">Pilih Ukuran</option>
                            <?php if (!empty($ukuranOptions)): ?>
                                <?php foreach ($ukuranOptions as $kode => $label): ?>
                                    <option value="<?= esc($kode) ?>"><?= esc($label) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_participant_emg">Kontak Darurat</label>
                        <input type="text" class="form-control rounded-0" id="edit_participant_emg" name="participant_emg" placeholder="Nomor telepon darurat">
                    </div>
                    <div class="form-group">
                        <label>Unggah ulang KTP (opsional)</label>
                        <div id="edit-ktp-dropzone" class="dropzone" style="border: 2px dashed #28a745; border-radius: 8px; background: #f8f9fa; padding: 15px; text-align: center; min-height: 120px;">
                            <div class="dz-message" data-dz-message><i class="fas fa-id-card text-muted"></i><br>JPG, PNG, PDF. Maks. 5MB.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer rounded-0">
                    <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-0">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('css') ?>
<!-- Ekko Lightbox CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css">
<!-- Dropzone CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
<style>
.payment-attachments .card {
    transition: transform 0.2s;
}
.payment-attachments .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.payment-attachments img {
    cursor: pointer;
    transition: opacity 0.2s;
}
.payment-attachments img:hover {
    opacity: 0.8;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- Ekko Lightbox JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>
<!-- Dropzone JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script>Dropzone.autoDiscover = false;</script>
<script>
var paymentProofDropzone = null;
var uploadPaymentProofBaseUrl = "<?= base_url('admin/transaksi/sale/upload-payment-proof/') ?>";

$(document).ready(function() {
    // Inisialisasi Ekko Lightbox
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox({
            alwaysShowClose: true,
            showArrows: true,
            onShown: function() {
                console.log('Lightbox ditampilkan');
            },
            onContentLoaded: function() {
                console.log('Konten lightbox dimuat');
            }
        });
    });

    // Upload bukti: open modal, set URL, init Dropzone on first open
    $(document).on('click', '.upload-payment-proof-btn', function() {
        var orderId = $(this).data('order-id');
        var paymentId = $(this).data('payment-id');
        var url = uploadPaymentProofBaseUrl + orderId + '/' + paymentId;
        $('#uploadPaymentProofForm').attr('action', url);

        if (paymentProofDropzone === null && typeof Dropzone !== 'undefined') {
            paymentProofDropzone = new Dropzone("#payment-proof-dropzone", {
                url: url,
                paramName: "file",
                maxFilesize: 5,
                acceptedFiles: ".jpg,.jpeg,.png,.pdf",
                addRemoveLinks: true,
                maxFiles: 5,
                dictDefaultMessage: "",
                init: function() {
                    var dropzone = this;
                    this.on("sending", function(file, xhr, formData) {
                        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
                    });
                    this.on("success", function(file, response) {
                        var res = typeof response === 'string' ? (function() { try { return JSON.parse(response); } catch(e) { return {}; } })() : (response || {});
                        if (res.success) {
                            $('#uploadPaymentProofModal').modal('hide');
                            dropzone.removeAllFiles(true);
                            location.reload();
                        }
                    });
                }
            });
        } else if (paymentProofDropzone) {
            paymentProofDropzone.options.url = url;
        }

        $('#uploadPaymentProofModal').modal('show');
    });

    // Clear Dropzone files when modal is hidden
    $('#uploadPaymentProofModal').on('hidden.bs.modal', function() {
        if (paymentProofDropzone) {
            paymentProofDropzone.removeAllFiles(true);
        }
    });

    // Edit participant info: open modal, pre-fill, set form action
    var editKtpDropzone = null;
    var uploadTempUrl = "<?= base_url('admin/transaksi/sale/upload-temp') ?>";
    $(document).on('click', '.edit-participant-btn', function() {
        var detailId = $(this).data('detail-id');
        var participantUk = $(this).data('participant-uk') || '';
        var participantEmg = $(this).data('participant-emg') || '';
        var participantBirthDate = $(this).data('participant-birth-date') || '';
        var updateUrl = "<?= base_url('admin/transaksi/sale/update-participant-info/') ?>" + detailId;
        $('#editParticipantForm').attr('action', updateUrl);
        $('#edit_participant_uk').val(participantUk);
        $('#edit_participant_emg').val(participantEmg);
        $('#edit_participant_birth_date').val(participantBirthDate);
        $('#edit_ktp_file').val('');
        if (editKtpDropzone) editKtpDropzone.removeAllFiles(true);

        if (editKtpDropzone === null && typeof Dropzone !== 'undefined') {
            editKtpDropzone = new Dropzone("#edit-ktp-dropzone", {
                url: uploadTempUrl,
                paramName: "file",
                maxFilesize: 5,
                acceptedFiles: ".jpg,.jpeg,.png,.pdf",
                addRemoveLinks: true,
                maxFiles: 1,
                dictDefaultMessage: "",
                init: function() {
                    this.on("sending", function(file, xhr, formData) {
                        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
                    });
                    this.on("success", function(file, response) {
                        var res = typeof response === 'string' ? (function() { try { return JSON.parse(response); } catch(e) { return {}; } })() : (response || {});
                        if (res.success) {
                            $('#edit_ktp_file').val(JSON.stringify({filename: res.filename, original_name: res.original_name || file.name, size: res.size, type: res.type}));
                        }
                    });
                    this.on("removedfile", function() {
                        $('#edit_ktp_file').val('');
                    });
                }
            });
        }

        $('#editParticipantModal').modal('show');
    });

    $('#editParticipantModal').on('hidden.bs.modal', function() {
        $('#edit_ktp_file').val('');
        if (editKtpDropzone) editKtpDropzone.removeAllFiles(true);
    });
});

// Fungsi cetak
window.addEventListener('beforeprint', function() {
    document.title = 'Invoice <?= esc($order->invoice_no) ?>';
});
</script>
<?= $this->endSection() ?>