<?php

/**
 * @description Admin transaction orders list view - displays all orders with filtering and management options
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
                <h1 class="m-0">Transaction Management</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                    <li class="breadcrumb-item active">Penjualan</li>
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

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= $stats['all'] ?></h3>
                        <p>Total Orders</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <a href="<?= base_url('admin/transaksi/sale/orders/all') ?>" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= $stats['pending'] ?></h3>
                        <p>Pending Orders</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <a href="<?= base_url('admin/transaksi/sale/orders/pending') ?>" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $stats['paid'] ?></h3>
                        <p>Paid Orders</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="<?= base_url('admin/transaksi/sale/orders/paid') ?>" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= $stats['failed'] + $stats['cancelled'] ?></h3>
                        <p>Failed/Cancelled</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <a href="<?= base_url('admin/transaksi/sale/orders/failed') ?>" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Navigation -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Order Filters</h3>
                    </div>
                    <div class="card-body">
                        <div class="btn-group">
                            <a href="<?= base_url('admin/transaksi/sale/orders/all') ?>" 
                               class="btn <?= $current_status === 'all' ? 'btn-primary' : 'btn-outline-primary' ?>">
                                All Orders (<?= $stats['all'] ?>)
                            </a>
                            <a href="<?= base_url('admin/transaksi/sale/orders/pending') ?>" 
                               class="btn <?= $current_status === 'pending' ? 'btn-warning' : 'btn-outline-warning' ?>">
                                Pending (<?= $stats['pending'] ?>)
                            </a>
                            <a href="<?= base_url('admin/transaksi/sale/orders/paid') ?>" 
                               class="btn <?= $current_status === 'paid' ? 'btn-success' : 'btn-outline-success' ?>">
                                Paid (<?= $stats['paid'] ?>)
                            </a>
                            <a href="<?= base_url('admin/transaksi/sale/orders/failed') ?>" 
                               class="btn <?= $current_status === 'failed' ? 'btn-danger' : 'btn-outline-danger' ?>">
                                Failed (<?= $stats['failed'] ?>)
                            </a>
                            <a href="<?= base_url('admin/transaksi/sale/orders/cancelled') ?>" 
                               class="btn <?= $current_status === 'cancelled' ? 'btn-secondary' : 'btn-outline-secondary' ?>">
                                Cancelled (<?= $stats['cancelled'] ?>)
                            </a>
                        </div>
                        
                        <div class="float-right">
                            <a href="<?= base_url('admin/transaksi/sale/reports') ?>" class="btn btn-info">
                                <i class="fas fa-chart-bar"></i> Reports
                            </a>
                            <a href="<?= base_url('admin/transaksi/sale/export') ?>" class="btn btn-success">
                                <i class="fas fa-download"></i> Export
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <?= ucfirst($current_status) ?> Orders
                            <?php if ($current_status !== 'all'): ?>
                                <span class="badge badge-secondary"><?= count($orders) ?> results</span>
                            <?php endif; ?>
                        </h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Invoice No</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Payment Method</th>
                                    <th>Amount</th>
                                    <th>Payment Status</th>
                                    <th>Order Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($orders)): ?>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td>
                                                <strong><?= esc($order->invoice_no) ?></strong><br>
                                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($order->invoice_date)) ?></small>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($order->invoice_date)) ?></td>
                                            <td>
                                                <?php if ($order->user_id): ?>
                                                    <strong>User ID: <?= $order->user_id ?></strong><br>
                                                    <small class="text-muted">Registered User</small>
                                                <?php else: ?>
                                                    <span class="text-muted">Guest User</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= esc($order->payment_method) ?></td>
                                            <td><strong>Rp <?= number_format($order->total_amount, 0, ',', '.') ?></strong></td>
                                            <td>
                                                <?php
                                                $statusColor = [
                                                    'pending' => 'warning',
                                                    'paid' => 'success',
                                                    'failed' => 'danger',
                                                    'cancelled' => 'secondary'
                                                ];
                                                $color = $statusColor[$order->payment_status] ?? 'info';
                                                ?>
                                                <span class="badge badge-<?= $color ?>"><?= ucfirst($order->payment_status) ?></span>
                                            </td>
                                            <td>
                                                <?php
                                                $orderStatusColor = [
                                                    'active' => 'primary',
                                                    'cancelled' => 'secondary',
                                                    'completed' => 'success'
                                                ];
                                                $orderColor = $orderStatusColor[$order->status] ?? 'info';
                                                ?>
                                                <span class="badge badge-<?= $orderColor ?>"><?= ucfirst($order->status) ?></span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?= base_url('admin/transaksi/sale/detail/' . $order->id) ?>" 
                                                       class="btn btn-sm btn-info" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if ($order->payment_status === 'pending'): ?>
                                                        <button type="button" class="btn btn-sm btn-warning" 
                                                                title="Update Status" 
                                                                onclick="updateStatus(<?= $order->id ?>, 'paid')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="text-muted py-4">
                                                <i class="fas fa-inbox fa-3x"></i><br><br>
                                                <h5>No Orders Found</h5>
                                                <p>No orders match the selected criteria.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($pager->getPageCount() > 1): ?>
                    <div class="card-footer clearfix">
                        <?= $pager->links() ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
function updateStatus(orderId, newStatus) {
    if (confirm('Are you sure you want to update this order status?')) {
        // Create a form and submit it
        var form = $('<form method="POST" action="<?= base_url('admin/transaksi/sale/update-status/') ?>' + orderId + '">');
        form.append('<input type="hidden" name="payment_status" value="' + newStatus + '">');
        form.append('<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">');
        form.appendTo('body').submit();
    }
}
</script>
<?= $this->endSection() ?>