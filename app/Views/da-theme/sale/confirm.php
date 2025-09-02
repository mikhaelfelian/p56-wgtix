<?php

/**
 * @description Payment confirmation page for manual payment verification
 * @author CodeIgniter Development Team
 * @since 2025-01-15
 * @version 1.0.0
 */

echo $this->extend('da-theme/layout/index'); ?>

<?= $this->section('content') ?>

<!--Page Cover-->
<section class="row page-cover" data-bgimage="<?= base_url('public/assets/theme/da-theme/images/page-cover/5.jpg') ?>">
    <div class="row m0">
        <div class="container">
            <h2 class="page-title">Payment Confirmation</h2>
        </div>
    </div>
</section>

<!--Payment Confirmation-->
<section class="row shopping-cart">
    <div class="container">

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <!-- Order Summary -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Order Summary - Invoice #<?= esc($order->invoice_no) ?></h4>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table">
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
                                <tr>
                                    <td><strong>Payment Status:</strong></td>
                                    <td>
                                        <?php
                                        $statusColor = [
                                            'pending' => 'warning',
                                            'paid' => 'success',
                                            'failed' => 'danger',
                                            'cancelled' => 'default'
                                        ];
                                        $color = $statusColor[$order->payment_status] ?? 'info';
                                        ?>
                                        <span class="label label-<?= $color ?>"><?= ucfirst($order->payment_status) ?></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Order Items</h4>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($order_details)): ?>
                                        <?php foreach ($order_details as $detail): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= esc($detail->event_title ?: $detail->price_description) ?></strong>
                                                    <?php if ($detail->item_data): ?>
                                                        <?php $itemData = json_decode($detail->item_data, true); ?>
                                                        <?php if (isset($itemData['participant_name'])): ?>
                                                            <br><small class="text-muted">Participant: <?= esc($itemData['participant_name']) ?></small>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= $detail->quantity ?: 1 ?></td>
                                                <td>Rp <?= number_format($detail->unit_price ?: 0, 0, ',', '.') ?></td>
                                                <td><strong>Rp <?= number_format($detail->total_price ?: 0, 0, ',', '.') ?></strong></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No items found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Payment Instructions -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Payment Instructions</h4>
                    </div>
                    <div class="panel-body">
                        <?php if (!empty($platform_details)): ?>
                            <?php foreach ($platform_details as $platform): ?>
                                <div class="payment-platform" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 4px;">
                                    <h5><strong><?= esc($platform->nama) ?></strong></h5>
                                    <?php if (!empty($platform->jenis)): ?>
                                        <p class="text-muted"><?= esc($platform->jenis) ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($platform->nama_rekening) && !empty($platform->nomor_rekening)): ?>
                                        <div style="background: #f5f5f5; padding: 10px; border-radius: 4px; margin: 10px 0;">
                                            <strong>Account Details:</strong><br>
                                            <strong>Name:</strong> <?= esc($platform->nama_rekening) ?><br>
                                            <strong>Number:</strong> <?= esc($platform->nomor_rekening) ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($platform->deskripsi)): ?>
                                        <div class="payment-description">
                                            <strong>Instructions:</strong><br>
                                            <?= nl2br(esc($platform->deskripsi)) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">No payment instructions available.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Payment Confirmation Form -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Confirm Payment</h4>
                    </div>
                    <div class="panel-body">
                        <?= form_open('sale/process-confirmation', ['enctype' => 'multipart/form-data']) ?>
                        <?= form_hidden('order_id', $order->id) ?>
                        
                        <div class="form-group">
                            <label>Payment Proof / Transaction Reference</label>
                            <textarea name="payment_proof" class="form-control" rows="3" placeholder="Enter transaction ID, reference number, or payment details..." required></textarea>
                            <small class="text-muted">Please provide transaction reference number or upload receipt details</small>
                        </div>

                        <div class="form-group">
                            <label>Additional Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Any additional information..."></textarea>
                        </div>

                        <div class="form-group">
                            <div class="alert alert-info">
                                <strong>Important:</strong> After submitting this confirmation, your payment will be reviewed by our team. You will be notified once the payment is verified.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fa fa-check"></i> Submit Payment Confirmation
                        </button>
                        
                        <a href="<?= base_url('my/orders') ?>" class="btn btn-default btn-block" style="margin-top: 10px;">
                            <i class="fa fa-arrow-left"></i> Back to Orders
                        </a>
                        
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
$(document).ready(function() {
    // Form validation
    $('form').on('submit', function(e) {
        var paymentProof = $('textarea[name="payment_proof"]').val().trim();
        
        if (!paymentProof) {
            e.preventDefault();
            alert('Please provide payment proof or transaction reference');
            $('textarea[name="payment_proof"]').focus();
            return false;
        }
        
        if (!confirm('Are you sure you want to submit this payment confirmation? Please make sure all information is correct.')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
<?= $this->endSection() ?>
