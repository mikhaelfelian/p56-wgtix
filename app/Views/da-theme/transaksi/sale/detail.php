<?php
/**
 * Frontend Sale Order Detail View
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-09-01
 * Github: github.com/mikhaelfelian
 * Description: Frontend view for displaying detailed information of a sales order
 * This file represents the Frontend Sale Order Detail View.
 */

echo $this->extend('da-theme/layout/main');
echo $this->section('content');
?>

<!-- Invoice Section -->
<section class="cart-section pt-60 pb-120">
    <div class="container">
        <div class="invoice-container">

            <!-- Invoice Header -->
            <div class="invoice-header">
                <div class="row">
                    <div class="col-md-6">
                        <div class="company-info">
                            <img src="<?= base_url($Pengaturan->logo_header) ?>"
                                alt="<?= esc($Pengaturan->judul_app) ?>" style="max-height: 60px;">
                            <p style="color: #6c7293; margin-top: 10px; font-size: 14px;">Event Management Solution</p>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <h1 class="invoice-title">INVOICE #<?= esc($order->invoice_no) ?></h1>
                        <div class="status-badge">
                            <?php
                            $statusText = strtoupper($order->payment_status);
                            $statusClass = 'unpaid';
                            if ($order->payment_status === 'paid') {
                                $statusClass = 'paid';
                            }
                            ?>
                            <span class="status <?= $statusClass ?>"><?= $statusText ?></span>
                        </div>
                        <p class="due-date">Jatuh Tempo:
                            <?= tgl_indo8(date('Y-m-d', strtotime($order->invoice_date . ' +7 days'))) ?></p>
                    </div>
                </div>
            </div>

            <hr class="invoice-divider">

            <!-- Invoice Details -->
            <div class="row invoice-details">
                <div class="col-md-6">
                    <h5>Penerima Invoice</h5>
                    <div class="recipient-info">
                        <strong><?= esc($Pengaturan->judul_app) ?></strong><br>
                        <?= isset($user) ? esc($user->first_name . ' ' . $user->last_name) : 'Guest User' ?><br>
                        User ID: <?= $order->user_id ? $order->user_id : 'Guest' ?><br>
                        <?= tgl_indo8($order->created_at) ?>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <h5>Dibayarkan Kepada</h5>
                    <div class="company-details">
                        <strong><?= esc($Pengaturan->nama_perusahaan ?? $Pengaturan->judul_app) ?></strong><br>
                        <?= esc($Pengaturan->alamat ?? 'Alamat tidak tersedia') ?><br>
                        <?= esc($Pengaturan->telepon ?? '') ?>
                    </div>
                </div>
            </div>

            <div class="row invoice-meta">
                <div class="col-md-6">
                    <p><strong>Tanggal Invoice</strong></p>
                    <p><?= tgl_indo8($order->invoice_date) ?></p>
                </div>
                <div class="col-md-6 text-right">
                    <p><strong>Metode Pembayaran</strong></p>
                    <p><?= $payment_records ? esc($payment_records->nama) : '-' ?></p>
                </div>
            </div>

            <!-- Item Invoice Section -->
            <div class="items-section">
                <h4 class="section-title">Item Invoice</h4>

                <?php if (!empty($order_details)): ?>
                    <div class="items-table">
                        <table class="table invoice-table">
                            <thead>
                                <tr style="background-color: #f8f9fa;">
                                    <th style="padding: 15px; border: 1px solid #ddd;">Deskripsi</th>
                                    <th style="padding: 15px; border: 1px solid #ddd; text-align: right;">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $grandTotal = 0;
                                foreach ($order_details as $detail):
                                    $grandTotal += $detail->total_price;
                                    ?>
                                    <tr>
                                        <td style="padding: 15px; border: 1px solid #ddd;">
                                            <strong><?= esc($detail->event_title ?: $detail->price_description) ?></strong>
                                            <?php if ($detail->item_data): ?>
                                                <?php $itemData = json_decode($detail->item_data, true) ?: []; ?>
                                                <?php if (isset($itemData['participant_name'])): ?>
                                                    <br>
                                                    <strong><?= ucwords($itemData['participant_name']) ?></strong><br>
                                                    <small class="text-muted" style="display:block;">Peserta #<?= esc($detail->sort_num ?? 'N/A') ?></small>
                                                    <?php if (!empty($itemData['participant_birth_date'])): ?>
                                                        <small class="text-muted" style="display:block;margin-top:4px;">Tanggal Lahir: <?= esc($itemData['participant_birth_date']) ?></small>
                                                    <?php endif; ?>
                                                    <?php if (!empty($itemData['participant_uk']) || !empty($itemData['participant_emg'])): ?>
                                                        <small class="text-muted" style="display:block;margin-top:4px;">
                                                            <?php if (!empty($itemData['participant_uk'])): ?>Ukuran Jersey: <?= esc(strtoupper($itemData['participant_uk'])) ?><br><?php endif; ?>
                                                            <?php if (!empty($itemData['participant_emg'])): ?>Kontak Darurat: <?= esc($itemData['participant_emg']) ?><?php endif; ?>
                                                        </small>
                                                    <?php endif; ?>
                                                    <?php if (!empty($itemData['participant_ktp_file'])): ?>
                                                        <?php
                                                        $ktpPath = $itemData['participant_ktp_file'];
                                                        $ktpUrl = base_url('public/' . $ktpPath);
                                                        $ktpExt = strtolower(pathinfo($ktpPath, PATHINFO_EXTENSION));
                                                        $isKtpImage = in_array($ktpExt, ['jpg', 'jpeg', 'png', 'gif']);
                                                        ?>
                                                        <div style="margin-top:8px;">
                                                            <small class="text-muted" style="display:block;margin-bottom:4px;"><i class="fa fa-id-card text-success"></i> KTP:</small>
                                                            <?php if ($isKtpImage): ?>
                                                                <a href="<?= $ktpUrl ?>" target="_blank" title="Lihat KTP"><img src="<?= $ktpUrl ?>" alt="KTP" style="width:60px;height:40px;object-fit:cover;border:1px solid #ddd;border-radius:4px;"></a>
                                                            <?php else: ?>
                                                                <a href="<?= $ktpUrl ?>" target="_blank" class="btn btn-sm btn-default" style="padding: 2px 8px; font-size: 12px;" title="Lihat KTP"><i class="fa fa-file-pdf-o"></i> KTP PDF</a>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php $isIncomplete = empty($itemData['participant_uk']) || empty($itemData['participant_emg']) || empty($itemData['participant_ktp_file']); ?>
                                                    <div style="margin-top:8px;">
                                                        <button type="button" class="btn btn-sm btn-default edit-participant-btn" data-detail-id="<?= (int) $detail->id ?>" data-order-id="<?= (int) $order->id ?>" data-participant-uk="<?= esc($itemData['participant_uk'] ?? '') ?>" data-participant-emg="<?= esc($itemData['participant_emg'] ?? '') ?>" data-participant-birth-date="<?= esc($itemData['participant_birth_date'] ?? '') ?>" title="<?= $isIncomplete ? 'Lengkapi info peserta' : 'Edit info peserta' ?>">
                                                            <i class="fa fa-edit"></i> <?= $isIncomplete ? 'Lengkapi' : 'Edit' ?>
                                                        </button>
                                                    </div>
                                                <?php else: ?>
                                                    <br><span class="text-muted">Tidak ada info peserta</span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 15px; border: 1px solid #ddd; text-align: right;">
                                            <strong>Rp <?= format_angka($detail->total_price, 0) ?></strong>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <!-- Total Row -->
                                <tr style="background-color: #f8f9fa;">
                                    <td style="padding: 15px; border: 1px solid #ddd;">
                                        <strong>Total</strong>
                                    </td>
                                    <td style="padding: 15px; border: 1px solid #ddd; text-align: right;">
                                        <strong>Rp <?= format_angka($grandTotal, 0) ?></strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <p class="tax-note">* Menandakan item yang dikenakan pajak.</p>

                <?php else: ?>
                    <div class="text-center py-4">
                        <p class="text-muted">No order items found</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Transaction Details -->
            <?php if (!empty($payment_platforms)): ?>
                <div class="transaction-section">
                    <h4 class="section-title">Transaksi Details</h4>

                    <div class="transaction-table">
                        <table class="table">
                            <thead>
                                <tr style="background-color: #f8f9fa;">
                                    <th style="padding: 15px; border: 1px solid #ddd;">#</th>
                                    <th style="padding: 15px; border: 1px solid #ddd;">Tanggal Transaksi</th>
                                    <th style="padding: 15px; border: 1px solid #ddd;">Metode Pembayaran</th>
                                    <th style="padding: 15px; border: 1px solid #ddd;">ID Transaksi</th>
                                    <th style="padding: 15px; border: 1px solid #ddd; text-align: right;">Jumlah</th>
                                    <th style="padding: 15px; border: 1px solid #ddd;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalPayments = 0;
                                $rowNum = 1;
                                foreach ($payment_platforms as $payment):
                                    $totalPayments += $payment->nominal;
                                    ?>
                                    <tr>
                                        <td style="padding: 15px; border: 1px solid #ddd;">
                                            <?= $rowNum++ ?>
                                        </td>
                                        <td style="padding: 15px; border: 1px solid #ddd;">
                                            <?= tgl_indo8($payment->created_at) ?>
                                        </td>
                                        <td style="padding: 15px; border: 1px solid #ddd;">
                                            <?= esc($payment->keterangan) ?>
                                        </td>
                                        <td style="padding: 15px; border: 1px solid #ddd;">
                                            <?= esc($payment->no_nota ?: '-') ?>
                                        </td>
                                        <td style="padding: 15px; border: 1px solid #ddd; text-align: right;">
                                            Rp <?= format_angka($payment->nominal, 0) ?>
                                        </td>
                                        <td style="padding: 15px; border: 1px solid #ddd;">
                                            <?php if ($order->payment_status == 'pending'): ?>
                                                <?php
                                                // Check if status_gateway property exists before accessing it
                                                $statusGateway = isset($payment->status_gateway) ? $payment->status_gateway : '0';
                                                // If status_gateway == 0, use 'confirm', else use jenis
                                                $platformRoute = ($statusGateway == '0')
                                                    ? 'confirm'
                                                    : (isset($payment->jenis) ? $payment->jenis : $payment->platform);
                                                ?>
                                                <a href="<?= base_url('sale/' . $platformRoute . '/' . $order->id) ?>" class="btn btn-sm btn-success">
                                                    <i class="fa fa-credit-card"></i> Bayar
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if (empty($payment_platforms)): ?>
                        <p style="text-align: center; color: #6c7293; padding: 20px;">
                            Tidak ada transaksi terkait yang ditemukan
                        </p>
                    <?php endif; ?>

                    <div class="payment-summary">
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <div class="summary-box">
                                    <p><strong>Sisa Tagihan</strong> <span class="float-right">Rp
                                            <?= format_angka($grandTotal - $totalPayments, 2) ?></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="transaction-section">
                    <h4 class="section-title">Transaksi Details</h4>
                    <p style="text-align: center; color: #6c7293; padding: 20px;">
                        Tidak ada transaksi terkait yang ditemukan
                    </p>

                    <div class="payment-summary">
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <div class="summary-box">
                                    <p><strong>Sisa Tagihan</strong> <span class="float-right">Rp
                                            <?= format_angka($grandTotal, 2) ?></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="invoice-actions">
                <div class="row">
                    <div class="col-md-6">
                        <a href="<?= base_url('sale/orders') ?>" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Semua Invoice
                        </a>
                    </div>
                    <div class="col-md-6">
                        <table style="width:100%; border:none; border-spacing:0.5px;">
                            <tr>
                                <td style="border:none; text-align:right;">
                                    <a href="<?= base_url('sale/print-dotmatrix/' . $order->id) ?>" class="btn btn-warning ml-2 mb-2">
                                        <i class="fa fa-print"></i> Invoice
                                    </a>
                                </td>
                                <td style="border:none; text-align:right;">
                                    <?php if ($order->payment_status == 'paid'): ?>
                                        <a href="<?= base_url('sale/print-ticket/' . $order->id) ?>" class="btn btn-info ml-2 mb-2">
                                            <i class="fa fa-ticket"></i> Cetak Tiket
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td style="border:none; text-align:right;">
                                    <?php if ($order->payment_status == 'paid'): ?>
                                        <a href="<?= base_url('sale/register-participant/' . $order->id) ?>" class="btn btn-success ml-2 mb-2">
                                            <i class="fa fa-user-plus"></i> Daftar Peserta
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Edit Participant Info -->
<div class="modal fade" id="editParticipantModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lengkapi Info Peserta</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="editParticipantForm" method="post" action="">
                <?= csrf_field() ?>
                <input type="hidden" name="ktp_file" id="edit_ktp_file" value="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_participant_birth_date">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="edit_participant_birth_date" name="participant_birth_date" max="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="form-group">
                        <label for="edit_participant_uk">Ukuran Jersey</label>
                        <select class="form-control" id="edit_participant_uk" name="participant_uk">
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
                        <input type="text" class="form-control" id="edit_participant_emg" name="participant_emg" placeholder="Nomor telepon darurat">
                    </div>
                    <div class="form-group">
                        <label>Unggah ulang KTP (opsional)</label>
                        <div id="edit-ktp-dropzone" class="dropzone" style="border: 2px dashed #28a745; border-radius: 8px; background: #f8f9fa; padding: 15px; text-align: center; min-height: 120px;">
                            <div class="dz-message" data-dz-message><i class="fa fa-id-card text-muted"></i><br>JPG, PNG, PDF. Maks. 5MB.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
<style>
    /* Invoice Container */
    .invoice-container {
        background: white;
        padding: 40px;
        margin: 20px 0;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        max-width: 1000px;
        margin: 20px auto;
    }

    /* Invoice Header */
    .invoice-header {
        margin-bottom: 30px;
    }

    .invoice-title {
        font-size: 28px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }

    .status {
        display: inline-block;
        padding: 8px 20px;
        border-radius: 25px;
        font-weight: bold;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .status.paid {
        background-color: #d4edda;
        color: #155724;
    }

    .status.unpaid {
        background-color: #f8d7da;
        color: #721c24;
    }

    .due-date {
        color: #6c7293;
        font-size: 14px;
        margin-top: 10px;
    }

    .invoice-divider {
        border: none;
        height: 2px;
        background: linear-gradient(to right, #007bff, #0056b3);
        margin: 30px 0;
    }

    /* Invoice Details */
    .invoice-details {
        margin-bottom: 30px;
    }

    .invoice-details h5 {
        color: #6c7293;
        font-size: 16px;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .recipient-info,
    .company-details {
        line-height: 1.8;
        color: #333;
    }

    .invoice-meta {
        margin-bottom: 40px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .invoice-meta p {
        margin-bottom: 5px;
        color: #6c7293;
    }

    /* Sections */
    .items-section,
    .transaction-section {
        margin-bottom: 40px;
    }

    .section-title {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f8f9fa;
    }

    /* Tables */
    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .invoice-table th,
    .invoice-table td {
        padding: 15px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .invoice-table thead tr {
        background-color: #f8f9fa;
    }

    .invoice-table tbody tr:nth-child(even) {
        background-color: #fafafa;
    }

    .tax-note {
        font-style: italic;
        color: #6c7293;
        font-size: 14px;
        margin-top: 10px;
    }

    /* Payment Summary */
    .payment-summary {
        margin-top: 30px;
    }

    .summary-box {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    .summary-box p {
        margin: 0;
        font-size: 16px;
    }

    /* Action Buttons */
    .invoice-actions {
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid #f8f9fa;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
        border: none;
    }

    .btn-secondary:hover {
        background-color: #545b62;
    }

    /* Print Styles */
    @media print {
        body * {
            visibility: hidden;
        }

        .invoice-container,
        .invoice-container * {
            visibility: visible;
        }

        .invoice-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            box-shadow: none;
            margin: 0;
            padding: 20px;
        }

        .invoice-actions {
            display: none !important;
        }

        .status.unpaid {
            background-color: #ffebee !important;
            color: #c62828 !important;
        }

        .status.paid {
            background-color: #e8f5e8 !important;
            color: #2e7d32 !important;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .invoice-container {
            padding: 20px;
            margin: 10px;
        }

        .invoice-title {
            font-size: 24px;
        }

        .invoice-table {
            font-size: 14px;
        }

        .invoice-table th,
        .invoice-table td {
            padding: 10px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script>Dropzone.autoDiscover = false;</script>
<script>
$(document).ready(function() {
    var editKtpDropzone = null;
    var uploadTempUrl = "<?= site_url('sale/upload-temp') ?>";
    var updateParticipantBaseUrl = "<?= base_url('sale/update-participant-info/') ?>";

    $(document).on('click', '.edit-participant-btn', function() {
        var detailId = $(this).data('detail-id');
        var participantUk = $(this).data('participant-uk') || '';
        var participantEmg = $(this).data('participant-emg') || '';
        var participantBirthDate = $(this).data('participant-birth-date') || '';
        var updateUrl = updateParticipantBaseUrl + detailId;
        $('#editParticipantForm').attr('action', updateUrl);
        $('#edit_participant_uk').val(participantUk);
        $('#edit_participant_emg').val(participantEmg);
        $('#edit_participant_birth_date').val(participantBirthDate);
        $('#edit_ktp_file').val('');

        if (editKtpDropzone) {
            editKtpDropzone.removeAllFiles(true);
        }

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
                    this.on("error", function(file, errorMessage) {
                        var msg = typeof errorMessage === 'string' ? errorMessage : (errorMessage && errorMessage.message) ? errorMessage.message : 'Upload gagal. Silakan coba lagi.';
                        alert('Upload Error: ' + msg);
                    });
                }
            });
        }

        $('#editParticipantModal').modal('show');
    });

    $('#editParticipantModal').on('hidden.bs.modal', function() {
        $('#edit_ktp_file').val('');
        if (editKtpDropzone) {
            editKtpDropzone.removeAllFiles(true);
        }
    });
});
</script>
<?= $this->endSection() ?>