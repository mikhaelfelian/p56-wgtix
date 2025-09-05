<?php
/**
 * Frontend Sale Orders View
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-09-01
 * Github: github.com/mikhaelfelian
 * Description: Frontend view for displaying user's sales orders with payment status
 * This file represents the Frontend Sale Orders View.
 */

echo $this->extend('da-theme/layout/main');
echo $this->section('content');
?>
<!--Page Cover-->
<section class="row page-cover" data-bgimage="<?= base_url('assets/theme/da-theme/images/page-cover/5.jpg') ?>">
    <div class="row m0">
        <div class="container">
            <h2 class="page-title">Pesanan Saya</h2>
        </div>
    </div>
</section>

<!--Orders-->
<section class="row shopping-cart">
    <div class="container">
        
        <!-- Status Filter Navigation -->
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-md-12">
                <div class="text-center">
                    <a href="<?= base_url('sale/orders/all') ?>" class="btn <?= $current_status === 'all' ? 'btn-primary' : 'btn-default' ?>" style="margin: 5px;">Semua (<?= $stats['all'] ?>)</a>
                    <a href="<?= base_url('sale/orders/pending') ?>" class="btn <?= $current_status === 'pending' ? 'btn-warning' : 'btn-default' ?>" style="margin: 5px;">Menunggu (<?= $stats['pending'] ?>)</a>
                    <a href="<?= base_url('sale/orders/paid') ?>" class="btn <?= $current_status === 'paid' ? 'btn-success' : 'btn-default' ?>" style="margin: 5px;">Lunas (<?= $stats['paid'] ?>)</a>
                    <a href="<?= base_url('sale/orders/failed') ?>" class="btn <?= $current_status === 'failed' ? 'btn-danger' : 'btn-default' ?>" style="margin: 5px;">Gagal (<?= $stats['failed'] ?>)</a>
                    <a href="<?= base_url('sale/orders/cancelled') ?>" class="btn <?= $current_status === 'cancelled' ? 'btn-default' : 'btn-default' ?>" style="margin: 5px;">Dibatalkan (<?= $stats['cancelled'] ?>)</a>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table cart-table">
                <thead>
                    <tr>
                        <th class="col-xs-3">No. Invoice</th>
                        <th class="col-xs-2">Tanggal</th>
                        <th class="col-xs-2">Jumlah</th>
                        <th class="col-xs-2">Status Pembayaran</th>
                        <th class="col-xs-2">Status Pesanan</th>
                        <th class="col-xs-1">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr class="alert fade in" role="alert">
                                <td>
                                    <div class="media">
                                        <div class="media-body text-left">
                                            <strong><?= esc($order->invoice_no) ?></strong><br>
                                            <small class="text-muted"><?= esc($order->payment_method) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($order->invoice_date)) ?></td>
                                <td><strong>Rp <?= number_format($order->total_amount, 0, ',', '.') ?></strong></td>
                                <td>
                                    <?php
                                    $statusColor = [
                                        'pending' => 'warning',
                                        'paid' => 'success', 
                                        'failed' => 'danger',
                                        'cancelled' => 'default'
                                    ];
                                    $color = $statusColor[$order->payment_status] ?? 'info';
                                    // label payment status in Indonesian
                                    $labelPaymentStatus = [
                                        'pending' => 'Menunggu',
                                        'paid' => 'Lunas',
                                        'failed' => 'Gagal',
                                        'cancelled' => 'Dibatalkan'
                                    ];
                                    $labelStatus = $labelPaymentStatus[$order->payment_status] ?? ucfirst($order->payment_status);
                                    ?>
                                    <span class="label label-<?= $color ?>"><?= $labelStatus ?></span>
                                </td>
                                <td>
                                    <?php
                                    $orderStatusColor = [
                                        'active' => 'primary',
                                        'cancelled' => 'default',
                                        'completed' => 'success'
                                    ];
                                    $orderColor = $orderStatusColor[$order->status] ?? 'info';
                                    // label order status in Indonesian
                                    $labelOrderStatus = [
                                        'active' => 'Aktif',
                                        'cancelled' => 'Dibatalkan',
                                        'completed' => 'Selesai'
                                    ];
                                    $labelOrder = $labelOrderStatus[$order->status] ?? ucfirst($order->status);
                                    ?>
                                    <span class="label label-<?= $orderColor ?>"><?= $labelOrder ?></span>
                                </td>
                                <td>
                                    <a href="<?= base_url('sale/order/' . $order->id) ?>" class="btn btn-sm btn-primary">
                                        <i class="fa fa-eye"></i> Lihat
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center" style="padding: 50px;">
                                <i class="fa fa-inbox fa-3x text-muted"></i><br><br>
                                <h4 class="text-muted">Pesanan Tidak Ditemukan</h4>
                                <p class="text-muted">Anda belum melakukan pemesanan apapun.</p>
                                <a href="<?= base_url('events') ?>" class="btn btn-primary">
                                    <i class="fa fa-shopping-cart"></i> Lihat Event
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($pager->getPageCount() > 1): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <?= $pager->links('default', 'datheme_pagination') ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
    </div>
</section>
<?php echo $this->endSection(); ?>