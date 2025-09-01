<?php
/**
 * Sale Order Detail View
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-09-01
 * Github: github.com/mikhaelfelian
 * Description: View for displaying detailed information of a sales order
 * This file represents the Sale Order Detail View.
 */

echo $this->extend('admin-lte-3/layout/main'); 
echo $this->section('content');
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Order Details</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Home</a></li>
                    <li class="breadcrumb-item">Transaksi</li>
                    <li class="breadcrumb-item"><a href="<?= base_url('transaksi/sale/orders') ?>">Orders</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        
        <!-- Order Header Information -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-file-invoice"></i> Invoice Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Invoice Number:</strong><br>
                                <span class="text-primary h5"><?= esc($order->invoice_no) ?></span><br><br>
                                
                                <strong>Invoice Date:</strong><br>
                                <?= date('d F Y, H:i', strtotime($order->invoice_date)) ?><br><br>
                                
                                <strong>Customer:</strong><br>
                                <?php if ($order->user_id): ?>
                                    <i class="fas fa-user"></i> User ID: <?= $order->user_id ?>
                                <?php else: ?>
                                    <i class="fas fa-user-secret"></i> Guest User
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <strong>Total Amount:</strong><br>
                                <span class="h4 text-success">Rp <?= number_format($order->total_amount, 0, ',', '.') ?></span><br><br>
                                
                                <strong>Payment Method:</strong><br>
                                <?= esc($order->payment_method) ?><br><br>
                                
                                <strong>Payment Reference:</strong><br>
                                <?= $order->payment_reference ? esc($order->payment_reference) : '-' ?>
                            </div>
                        </div>
                        
                        <?php if ($order->notes): ?>
                        <hr>
                        <strong>Notes:</strong><br>
                        <p class="text-muted"><?= nl2br(esc($order->notes)) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle"></i> Status Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <strong>Payment Status:</strong><br>
                        <?php
                        $statusClass = [
                            'pending' => 'warning',
                            'paid' => 'success', 
                            'failed' => 'danger',
                            'cancelled' => 'secondary'
                        ];
                        $class = $statusClass[$order->payment_status] ?? 'info';
                        ?>
                        <span class="badge badge-<?= $class ?> p-2" style="font-size: 14px;">
                            <?= ucfirst($order->payment_status) ?>
                        </span><br><br>
                        
                        <strong>Order Status:</strong><br>
                        <?php
                        $orderStatusClass = [
                            'active' => 'primary',
                            'cancelled' => 'secondary',
                            'completed' => 'success'
                        ];
                        $orderClass = $orderStatusClass[$order->status] ?? 'info';
                        ?>
                        <span class="badge badge-<?= $orderClass ?> p-2" style="font-size: 14px;">
                            <?= ucfirst($order->status) ?>
                        </span><br><br>
                        
                        <strong>Created:</strong><br>
                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($order->created_at)) ?></small><br><br>
                        
                        <strong>Last Updated:</strong><br>
                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($order->updated_at)) ?></small>
                        
                        <?php if ($has_admin_access): ?>
                        <hr>
                        <button type="button" class="btn btn-warning btn-block update-status-btn" 
                                data-order-id="<?= $order->id ?>" 
                                data-current-status="<?= $order->payment_status ?>">
                            <i class="fas fa-edit"></i> Update Status
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-shopping-cart"></i> Order Items
                </h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Price Description</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-right">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($order_details)): ?>
                            <?php 
                            $grandTotal = 0;
                            foreach ($order_details as $detail): 
                                $grandTotal += $detail->total_price;
                            ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($detail->event_title) ?></strong>
                                        <?php if ($detail->item_data): ?>
                                            <?php $itemData = json_decode($detail->item_data, true); ?>
                                            <?php if (isset($itemData['event_date'])): ?>
                                                <br><small class="text-muted">
                                                    <i class="fas fa-calendar"></i> <?= esc($itemData['event_date']) ?>
                                                </small>
                                            <?php endif; ?>
                                            <?php if (isset($itemData['event_location'])): ?>
                                                <br><small class="text-muted">
                                                    <i class="fas fa-map-marker-alt"></i> <?= esc($itemData['event_location']) ?>
                                                </small>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($detail->price_description) ?></td>
                                    <td class="text-center">
                                        <span class="badge badge-info"><?= $detail->quantity ?></span>
                                    </td>
                                    <td class="text-right">Rp <?= number_format($detail->unit_price, 0, ',', '.') ?></td>
                                    <td class="text-right"><strong>Rp <?= number_format($detail->total_price, 0, ',', '.') ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="bg-light">
                                <td colspan="4" class="text-right"><strong>Grand Total:</strong></td>
                                <td class="text-right"><strong class="h5 text-success">Rp <?= number_format($grandTotal, 0, ',', '.') ?></strong></td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No order items found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Platforms -->
        <?php if (!empty($payment_platforms)): ?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-credit-card"></i> Payment Methods
                </h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Platform</th>
                            <th class="text-right">Amount</th>
                            <th>Notes</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalPayments = 0;
                        foreach ($payment_platforms as $payment): 
                            $totalPayments += $payment->nominal;
                        ?>
                            <tr>
                                <td>
                                    <strong><?= esc($payment->platform) ?></strong>
                                </td>
                                <td class="text-right">
                                    <strong>Rp <?= number_format($payment->nominal, 0, ',', '.') ?></strong>
                                </td>
                                <td>
                                    <small class="text-muted"><?= $payment->keterangan ? esc($payment->keterangan) : '-' ?></small>
                                </td>
                                <td>
                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($payment->created_at)) ?></small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="bg-light">
                            <td><strong>Total Payments:</strong></td>
                            <td class="text-right"><strong class="h6 text-primary">Rp <?= number_format($totalPayments, 0, ',', '.') ?></strong></td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-12">
                <a href="<?= base_url('transaksi/sale/orders') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Orders
                </a>
                <button type="button" class="btn btn-primary float-right" onclick="window.print()">
                    <i class="fas fa-print"></i> Print Invoice
                </button>
            </div>
        </div>
        
    </div>
</section>

<!-- Status Update Modal -->
<?php if ($has_admin_access): ?>
<div class="modal fade" id="statusUpdateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Order Status</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="statusUpdateForm">
                    <input type="hidden" id="orderId" name="order_id">
                    <div class="form-group">
                        <label>Payment Status</label>
                        <select class="form-control" id="paymentStatus" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="failed">Failed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Notes (Optional)</label>
                        <textarea class="form-control" id="statusNotes" name="notes" rows="3" placeholder="Add notes about status change..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmStatusUpdate">Update Status</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php echo $this->endSection(); ?>

<?= $this->section('js') ?>
<?php if ($has_admin_access): ?>
<script>
$(document).ready(function() {
    // Handle status update button click
    $('.update-status-btn').on('click', function() {
        var orderId = $(this).data('order-id');
        var currentStatus = $(this).data('current-status');
        
        $('#orderId').val(orderId);
        $('#paymentStatus').val(currentStatus);
        $('#statusNotes').val('');
        
        $('#statusUpdateModal').modal('show');
    });
    
    // Handle status update confirmation
    $('#confirmStatusUpdate').on('click', function() {
        var formData = $('#statusUpdateForm').serialize();
        
        $.ajax({
            url: '<?= base_url('transaksi/sale/updateStatus') ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                $('#confirmStatusUpdate').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#statusUpdateModal').modal('hide');
                    location.reload(); // Refresh the page to show updated status
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('An error occurred while updating status');
            },
            complete: function() {
                $('#confirmStatusUpdate').prop('disabled', false).html('Update Status');
            }
        });
    });
});
</script>
<?php endif; ?>
<?= $this->endSection() ?>
