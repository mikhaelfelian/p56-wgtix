<?php

/**
 * @description Admin sales reports view - displays sales statistics and reporting functionality
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
                <h1 class="m-0">Sales Reports</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/transaksi/sale/orders') ?>">Transaksi</a></li>
                    <li class="breadcrumb-item active">Reports</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <!-- Date Filter -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Report Period</h3>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="<?= base_url('admin/transaksi/sale/reports') ?>">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="start_date">Start Date</label>
                                        <input type="date" class="form-control" name="start_date" id="start_date" 
                                               value="<?= $start_date ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="end_date">End Date</label>
                                        <input type="date" class="form-control" name="end_date" id="end_date" 
                                               value="<?= $end_date ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Generate Report
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br>
                                        <a href="<?= base_url('admin/transaksi/sale/export?start_date=' . $start_date . '&end_date=' . $end_date) ?>" 
                                           class="btn btn-success">
                                            <i class="fas fa-download"></i> Export CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= $stats['total_orders'] ?></h3>
                        <p>Total Orders</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $stats['paid_orders'] ?></h3>
                        <p>Paid Orders</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= $stats['pending_orders'] ?></h3>
                        <p>Pending Orders</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>Rp <?= number_format($stats['total_revenue'], 0, ',', '.') ?></h3>
                        <p>Total Revenue</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Breakdown -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Revenue Breakdown</h3>
                    </div>
                    <div class="card-body">
                        <div class="progress-group">
                            Paid Revenue
                            <span class="float-right"><b>Rp <?= number_format($stats['paid_revenue'], 0, ',', '.') ?></b>/Rp <?= number_format($stats['total_revenue'], 0, ',', '.') ?></span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-success" style="width: <?= $stats['total_revenue'] > 0 ? ($stats['paid_revenue'] / $stats['total_revenue'] * 100) : 0 ?>%"></div>
                            </div>
                        </div>
                        <div class="progress-group">
                            Pending Revenue
                            <span class="float-right"><b>Rp <?= number_format($stats['pending_revenue'], 0, ',', '.') ?></b>/Rp <?= number_format($stats['total_revenue'], 0, ',', '.') ?></span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-warning" style="width: <?= $stats['total_revenue'] > 0 ? ($stats['pending_revenue'] / $stats['total_revenue'] * 100) : 0 ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Order Status Distribution</h3>
                    </div>
                    <div class="card-body">
                        <div class="progress-group">
                            Paid Orders
                            <span class="float-right"><b><?= $stats['paid_orders'] ?></b>/<?= $stats['total_orders'] ?></span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-success" style="width: <?= $stats['total_orders'] > 0 ? ($stats['paid_orders'] / $stats['total_orders'] * 100) : 0 ?>%"></div>
                            </div>
                        </div>
                        <div class="progress-group">
                            Pending Orders
                            <span class="float-right"><b><?= $stats['pending_orders'] ?></b>/<?= $stats['total_orders'] ?></span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-warning" style="width: <?= $stats['total_orders'] > 0 ? ($stats['pending_orders'] / $stats['total_orders'] * 100) : 0 ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Summary -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Report Summary: <?= date('d/m/Y', strtotime($start_date)) ?> - <?= date('d/m/Y', strtotime($end_date)) ?>
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
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($orders)): ?>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td>
                                                <a href="<?= base_url('admin/transaksi/sale/detail/' . $order->id) ?>">
                                                    <?= esc($order->invoice_no) ?>
                                                </a>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($order->invoice_date)) ?></td>
                                            <td>
                                                <?php if ($order->user_id): ?>
                                                    User ID: <?= $order->user_id ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Guest</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= esc($order->payment_method) ?></td>
                                            <td>Rp <?= number_format($order->total_amount, 0, ',', '.') ?></td>
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
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            No orders found for the selected period
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
// Set default dates to current month if not set
$(document).ready(function() {
    if (!$('#start_date').val()) {
        var today = new Date();
        var firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        $('#start_date').val(firstDay.toISOString().substr(0, 10));
    }
    
    if (!$('#end_date').val()) {
        var today = new Date();
        var lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        $('#end_date').val(lastDay.toISOString().substr(0, 10));
    }
});
</script>
<?= $this->endSection() ?>
