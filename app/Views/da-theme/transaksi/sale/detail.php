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
                    <p><?= esc($payment_records->nama) ?></p>
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
                                                <?php $itemData = json_decode($detail->item_data, true); ?>
                                                <?php if (isset($itemData['participant_name'])): ?>
                                                    <br><small class="text-muted">Peserta:
                                                        <?= esc($itemData['participant_name']) ?></small>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 15px; border: 1px solid #ddd; text-align: right;">
                                            <strong>Rp <?= format_angka($detail->total_price, 2) ?></strong>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <!-- Credit Row -->
                                <tr>
                                    <td style="padding: 15px; border: 1px solid #ddd;">
                                        <strong>Kredit</strong>
                                    </td>
                                    <td style="padding: 15px; border: 1px solid #ddd; text-align: right;">
                                        <strong>Rp 0,00</strong>
                                    </td>
                                </tr>

                                <!-- Total Row -->
                                <tr style="background-color: #f8f9fa;">
                                    <td style="padding: 15px; border: 1px solid #ddd;">
                                        <strong>Total</strong>
                                    </td>
                                    <td style="padding: 15px; border: 1px solid #ddd; text-align: right;">
                                        <strong>Rp <?= format_angka($grandTotal, 2) ?></strong>
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
                                            <?= esc($payment->platform) ?>
                                        </td>
                                        <td style="padding: 15px; border: 1px solid #ddd;">
                                            <?= esc($payment->no_nota ?: '-') ?>
                                        </td>
                                        <td style="padding: 15px; border: 1px solid #ddd; text-align: right;">
                                            Rp <?= format_angka($payment->nominal, 2) ?>
                                        </td>
                                        <td style="padding: 15px; border: 1px solid #ddd;">
                                            <?php if ($order->payment_status == 'pending'): ?>
                                                <a href="<?= base_url('sale/' . $payment->platform . '/' . $order->id) ?>" class="btn btn-sm btn-success">
                                                    <i class="fa fa-credit-card"></i> Bayar
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= base_url('sale/order/' . $payment->id) ?>" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-link"></i> Lihat
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
                    <div class="col-md-6 text-right">
                        <a href="<?= base_url('my/invoice/' . $order->id) ?>" class="btn btn-success">
                            <i class="fa fa-file-pdf-o"></i> Invoice PDF
                        </a>
                        <a href="<?= base_url('sale/print-dotmatrix/' . $order->id) ?>" class="btn btn-warning">
                            <i class="fa fa-print"></i> Dot Matrix
                        </a>
                        <a href="<?= base_url('sale/print-ticket/' . $order->id) ?>" class="btn btn-info">
                            <i class="fa fa-ticket"></i> All Tickets
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php echo $this->endSection(); ?>

<?= $this->section('css') ?>
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