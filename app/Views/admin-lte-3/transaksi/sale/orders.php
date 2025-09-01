<?php
/**
 * Sale Orders View
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-09-01
 * Github: github.com/mikhaelfelian
 * Description: View for displaying sales orders with payment status management
 * This file represents the Sale Orders View.
 */

echo $this->extend('admin-lte-3/layout/main'); 
echo $this->section('content');
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Order Management</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Home</a></li>
                    <li class="breadcrumb-item">Transaksi</li>
                    <li class="breadcrumb-item active">Orders</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-2 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= $stats['all'] ?></h3>
                        <p>Total Orders</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="<?= base_url('transaksi/sale/orders/all') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= $stats['pending'] ?></h3>
                        <p>Pending</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-clock"></i>
                    </div>
                    <a href="<?= base_url('transaksi/sale/orders/pending') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $stats['paid'] ?></h3>
                        <p>Paid</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-checkmark"></i>
                    </div>
                    <a href="<?= base_url('transaksi/sale/orders/paid') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= $stats['failed'] ?></h3>
                        <p>Failed</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-close"></i>
                    </div>
                    <a href="<?= base_url('transaksi/sale/orders/failed') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3><?= $stats['cancelled'] ?></h3>
                        <p>Cancelled</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-trash-b"></i>
                    </div>
                    <a href="<?= base_url('transaksi/sale/orders/cancelled') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <?php if ($has_admin_access): ?>
            <div class="col-lg-2 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3><i class="fas fa-download"></i></h3>
                        <p>Export</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-document"></i>
                    </div>
                    <a href="<?= base_url('transaksi/sale/export/' . $current_status) ?>" class="small-box-footer">Export Excel <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Orders Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list"></i> 
                    Orders 
                    <?php if ($current_status !== 'all'): ?>
                        - <?= ucfirst($current_status) ?>
                    <?php endif; ?>
                </h3>
                <div class="card-tools">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            Filter: <?= ucfirst($current_status) ?>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item <?= $current_status === 'all' ? 'active' : '' ?>" href="<?= base_url('transaksi/sale/orders/all') ?>">All Orders</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item <?= $current_status === 'pending' ? 'active' : '' ?>" href="<?= base_url('transaksi/sale/orders/pending') ?>">Pending</a>
                            <a class="dropdown-item <?= $current_status === 'paid' ? 'active' : '' ?>" href="<?= base_url('transaksi/sale/orders/paid') ?>">Paid</a>
                            <a class="dropdown-item <?= $current_status === 'failed' ? 'active' : '' ?>" href="<?= base_url('transaksi/sale/orders/failed') ?>">Failed</a>
                            <a class="dropdown-item <?= $current_status === 'cancelled' ? 'active' : '' ?>" href="<?= base_url('transaksi/sale/orders/cancelled') ?>">Cancelled</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Invoice No</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Payment Status</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orders)): ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('transaksi/sale/detail/' . $order->id) ?>" class="text-primary">
                                            <strong><?= esc($order->invoice_no) ?></strong>
                                        </a>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($order->invoice_date)) ?></td>
                                    <td>
                                        <?php if ($order->user_id): ?>
                                            <i class="fas fa-user"></i> User ID: <?= $order->user_id ?>
                                        <?php else: ?>
                                            <i class="fas fa-user-secret"></i> Guest
                                        <?php endif; ?>
                                    </td>
                                    <td><strong>Rp <?= number_format($order->total_amount, 0, ',', '.') ?></strong></td>
                                    <td>
                                        <?php
                                        $statusClass = [
                                            'pending' => 'warning',
                                            'paid' => 'success', 
                                            'failed' => 'danger',
                                            'cancelled' => 'secondary'
                                        ];
                                        $class = $statusClass[$order->payment_status] ?? 'info';
                                        ?>
                                        <span class="badge badge-<?= $class ?>"><?= ucfirst($order->payment_status) ?></span>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?= esc($order->payment_method) ?></small>
                                    </td>
                                    <td>
                                        <?php
                                        $orderStatusClass = [
                                            'active' => 'primary',
                                            'cancelled' => 'secondary',
                                            'completed' => 'success'
                                        ];
                                        $orderClass = $orderStatusClass[$order->status] ?? 'info';
                                        ?>
                                        <span class="badge badge-<?= $orderClass ?>"><?= ucfirst($order->status) ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= base_url('transaksi/sale/detail/' . $order->id) ?>" class="btn btn-info btn-sm" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($has_admin_access): ?>
                                                <button type="button" class="btn btn-warning btn-sm update-status-btn" 
                                                        data-order-id="<?= $order->id ?>" 
                                                        data-current-status="<?= $order->payment_status ?>"
                                                        title="Update Status">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <br>No orders found
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if ($pager->getPageCount() > 1): ?>
            <div class="card-footer clearfix">
                <?= $pager->links() ?>
            </div>
            <?php endif; ?>
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
