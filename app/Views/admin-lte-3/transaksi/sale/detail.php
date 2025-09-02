<?php

/**
 * @description Admin transaction detail view - displays complete order information with management controls
 * @author CodeIgniter Development Team
 * @since 2025-01-15
 * @version 1.0.0
 */

echo $this->extend(theme_path('main')); ?>

<?= $this->section('content') ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Transaction Detail</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/transaksi/sale/orders') ?>">Transaksi</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- Order Header -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-file-invoice"></i> Invoice #<?= esc($order->invoice_no) ?>
                        </h3>
                        <div class="card-tools">
                            <a href="<?= base_url('admin/transaksi/sale/orders') ?>" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Orders
                            </a>
                            <a href="<?= base_url('admin/transaksi/sale/invoice/' . $order->id) ?>" class="btn btn-sm btn-success">
                                <i class="fas fa-file-pdf"></i> Invoice PDF
                            </a>
                            <a href="<?= base_url('admin/transaksi/sale/dot-matrix-invoice/' . $order->id) ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-print"></i> Dot Matrix
                            </a>
                            <a href="<?= base_url('admin/transaksi/sale/tickets/' . $order->id) ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-ticket-alt"></i> All Tickets
                            </a>
                            <button type="button" class="btn btn-sm btn-primary" onclick="window.print()">
                                <i class="fas fa-print"></i> Print
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Order Information</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Invoice No:</strong></td>
                                        <td><?= esc($order->invoice_no) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Invoice Date:</strong></td>
                                        <td><?= date('d/m/Y H:i', strtotime($order->invoice_date)) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Payment Method:</strong></td>
                                        <td><?= esc($order->payment_method) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Amount:</strong></td>
                                        <td><strong class="text-success">Rp <?= number_format($order->total_amount, 0, ',', '.') ?></strong></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Customer Information</h5>
                                <table class="table table-sm">
                                    <?php if ($user): ?>
                                        <tr>
                                            <td><strong>Customer:</strong></td>
                                            <td><?= esc($user->first_name . ' ' . $user->last_name) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Username:</strong></td>
                                            <td><?= esc($user->username) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td><?= esc($user->email) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>User ID:</strong></td>
                                            <td><?= $order->user_id ?></td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td><strong>Customer:</strong></td>
                                            <td><span class="text-muted">Guest User</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>User ID:</strong></td>
                                            <td><?= $order->user_id ?: 'N/A' ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Management -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Payment Status</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?= base_url('admin/transaksi/sale/update-status/' . $order->id) ?>">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label>Current Status:</label>
                                <?php
                                $statusColor = [
                                    'pending' => 'warning',
                                    'paid' => 'success',
                                    'failed' => 'danger',
                                    'cancelled' => 'secondary'
                                ];
                                $color = $statusColor[$order->payment_status] ?? 'info';
                                ?>
                                <p><span class="badge badge-<?= $color ?> badge-lg"><?= ucfirst($order->payment_status) ?></span></p>
                            </div>
                            <div class="form-group">
                                <label for="payment_status">Update Payment Status:</label>
                                <select class="form-control" name="payment_status" id="payment_status">
                                    <option value="">-- Select Status --</option>
                                    <option value="pending" <?= $order->payment_status === 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="paid" <?= $order->payment_status === 'paid' ? 'selected' : '' ?>>Paid</option>
                                    <option value="failed" <?= $order->payment_status === 'failed' ? 'selected' : '' ?>>Failed</option>
                                    <option value="cancelled" <?= $order->payment_status === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Order Status</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?= base_url('admin/transaksi/sale/update-status/' . $order->id) ?>">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label>Current Status:</label>
                                <?php
                                $orderStatusColor = [
                                    'active' => 'primary',
                                    'cancelled' => 'secondary',
                                    'completed' => 'success'
                                ];
                                $orderColor = $orderStatusColor[$order->status] ?? 'info';
                                ?>
                                <p><span class="badge badge-<?= $orderColor ?> badge-lg"><?= ucfirst($order->status) ?></span></p>
                            </div>
                            <div class="form-group">
                                <label for="order_status">Update Order Status:</label>
                                <select class="form-control" name="order_status" id="order_status">
                                    <option value="">-- Select Status --</option>
                                    <option value="active" <?= $order->status === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="completed" <?= $order->status === 'completed' ? 'selected' : '' ?>>Completed</option>
                                    <option value="cancelled" <?= $order->status === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Order Items</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Price Description</th>
                                    <th>Participant Info</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total Price</th>
                                    <th>Ticket</th>
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
                                                <small class="text-muted">Event ID: <?= $detail->event_id ?: 'N/A' ?></small>
                                            </td>
                                            <td>
                                                <?= esc($detail->price_description ?: 'N/A') ?><br>
                                                <small class="text-muted">Price ID: <?= $detail->price_id ?: 'N/A' ?></small>
                                            </td>
                                            <td>
                                                <?php if (isset($itemData['participant_name'])): ?>
                                                    <strong><?= esc($itemData['participant_name']) ?></strong><br>
                                                    <small class="text-muted">Participant #<?= $itemData['participant_number'] ?? 'N/A' ?></small>
                                                <?php else: ?>
                                                    <span class="text-muted">No participant info</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $detail->quantity ?: 1 ?></td>
                                            <td>Rp <?= number_format($detail->unit_price ?: 0, 0, ',', '.') ?></td>
                                            <td><strong>Rp <?= number_format($detail->total_price ?: 0, 0, ',', '.') ?></strong></td>
                                            <td>
                                                <a href="<?= base_url('admin/transaksi/sale/ticket/' . $detail->id) ?>" class="btn btn-sm btn-warning" title="Download Ticket">
                                                    <i class="fas fa-ticket-alt"></i>
                                                </a>
                                                <?php if (!empty($detail->qrcode)): ?>
                                                    <span class="badge badge-success">QR Ready</span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary">No QR</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="bg-light">
                                        <td colspan="6" class="text-right"><strong>Grand Total:</strong></td>
                                        <td><strong class="text-success">Rp <?= number_format($grandTotal, 0, ',', '.') ?></strong></td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            No order items found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <?php if (!empty($payment_platforms)): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Payment Details</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Platform</th>
                                    <th>Transaction ID</th>
                                    <th>Amount</th>
                                    <th>Notes</th>
                                    <th>Attachments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalPayments = 0;
                                foreach ($payment_platforms as $payment):
                                    $totalPayments += $payment->nominal;
                                ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i', strtotime($payment->created_at)) ?></td>
                                        <td><?= esc($payment->platform) ?></td>
                                        <td><?= esc($payment->no_nota ?: 'N/A') ?></td>
                                        <td><strong>Rp <?= number_format($payment->nominal, 0, ',', '.') ?></strong></td>
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
                                                            <img src="<?= $filePath ?>" style="width:40px;height:40px;object-fit:cover;" alt="<?= esc($file['original_name']) ?>">
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="<?= $filePath ?>" target="_blank">
                                                            <i class="fas fa-file-pdf text-danger"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="bg-light">
                                    <td colspan="4" class="text-right"><strong>Total Payments:</strong></td>
                                    <td><strong class="text-info">Rp <?= number_format($totalPayments, 0, ',', '.') ?></strong></td>
                                    <td></td>
                                </tr>
                                <?php if ($order->total_amount != $totalPayments): ?>
                                <tr class="bg-warning">
                                    <td colspan="4" class="text-right"><strong>Remaining Balance:</strong></td>
                                    <td><strong>Rp <?= number_format($order->total_amount - $totalPayments, 0, ',', '.') ?></strong></td>
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

<?= $this->endSection() ?>

<?= $this->section('css') ?>
<!-- Ekko Lightbox CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css">
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
<script>
$(document).ready(function() {
    // Initialize Ekko Lightbox
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox({
            alwaysShowClose: true,
            showArrows: true,
            onShown: function() {
                console.log('Lightbox shown');
            },
            onContentLoaded: function() {
                console.log('Lightbox content loaded');
            }
        });
    });
});

// Print function
window.addEventListener('beforeprint', function() {
    document.title = 'Invoice <?= esc($order->invoice_no) ?>';
});
</script>
<?= $this->endSection() ?>