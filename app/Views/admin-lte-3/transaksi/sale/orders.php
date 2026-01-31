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
                <h1 class="m-0"></h1>
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
                        <p>Total Pesanan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <a href="<?= base_url('admin/transaksi/sale/orders/all') ?>" class="small-box-footer">
                        Info Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= $stats['pending'] ?></h3>
                        <p>Pesanan Menunggu</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <a href="<?= base_url('admin/transaksi/sale/orders/pending') ?>" class="small-box-footer">
                        Info Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $stats['paid'] ?></h3>
                        <p>Pesanan Lunas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="<?= base_url('admin/transaksi/sale/orders/paid') ?>" class="small-box-footer">
                        Info Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= $stats['failed'] + $stats['cancelled'] ?></h3>
                        <p>Gagal/Dibatalkan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <a href="<?= base_url('admin/transaksi/sale/orders/failed') ?>" class="small-box-footer">
                        Info Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Navigation -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Filter Pesanan</h3>
                    </div>
                    <div class="card-body">
                        <div class="btn-group">
                            <a href="<?= base_url('admin/transaksi/sale/orders/all') ?>" 
                               class="btn <?= $current_status === 'all' ? 'btn-primary' : 'btn-outline-primary' ?>">
                                Semua Pesanan (<?= $stats['all'] ?>)
                            </a>
                            <a href="<?= base_url('admin/transaksi/sale/orders/pending') ?>" 
                               class="btn <?= $current_status === 'pending' ? 'btn-warning' : 'btn-outline-warning' ?>">
                                Menunggu (<?= $stats['pending'] ?>)
                            </a>
                            <a href="<?= base_url('admin/transaksi/sale/orders/paid') ?>" 
                               class="btn <?= $current_status === 'paid' ? 'btn-success' : 'btn-outline-success' ?>">
                                Lunas (<?= $stats['paid'] ?>)
                            </a>
                            <a href="<?= base_url('admin/transaksi/sale/orders/failed') ?>" 
                               class="btn <?= $current_status === 'failed' ? 'btn-danger' : 'btn-outline-danger' ?>">
                                Gagal (<?= $stats['failed'] ?>)
                            </a>
                            <a href="<?= base_url('admin/transaksi/sale/orders/cancelled') ?>" 
                               class="btn <?= $current_status === 'cancelled' ? 'btn-secondary' : 'btn-outline-secondary' ?>">
                                Dibatalkan (<?= $stats['cancelled'] ?>)
                            </a>
                        </div>
                        
                        <div class="float-right">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#manualOrderModal">
                                <i class="fas fa-plus"></i> Pesanan Manual
                            </button>
                            <a href="<?= base_url('admin/transaksi/sale/reports') ?>" class="btn btn-info">
                                <i class="fas fa-chart-bar"></i> Laporan
                            </a>
                            <a href="<?= base_url('admin/transaksi/sale/export') ?>" class="btn btn-success">
                                <i class="fas fa-download"></i> Ekspor
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
                            <?= ucfirst($current_status) ?> Pesanan
                            <?php if ($current_status !== 'all'): ?>
                                <span class="badge badge-secondary"><?= count($orders) ?> hasil</span>
                            <?php endif; ?>
                        </h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>No Invoice</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Jumlah</th>
                                    <th>Status Pembayaran</th>
                                    <th>Status Pesanan</th>
                                    <th>Aksi</th>
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
                                                <?php
                                                $participants = $participantsByOrder[$order->id] ?? [];
                                                $fallbackPhone = $userPhonesByOrder[$order->id] ?? null;
                                                if (!empty($participants)):
                                                ?>
                                                    <small class="text-muted">
                                                        <strong>Peserta:</strong><br/>
                                                        <?php foreach ($participants as $p): ?>
                                                            <?php
                                                            $rawPhone = $p['phone'] ?? null;
                                                            if (empty($rawPhone)) {
                                                                $rawPhone = $fallbackPhone;
                                                            }

                                                            // Normalize WhatsApp number (Indonesian format 08.. -> 62..)
                                                            $waHref = null;
                                                            if (!empty($rawPhone)) {
                                                                $digits = preg_replace('/\D+/', '', $rawPhone);
                                                                if (strpos($digits, '0') === 0) {
                                                                    $digits = '62' . substr($digits, 1);
                                                                }
                                                                $waHref = 'https://wa.me/' . $digits;
                                                            }
                                                            ?>
                                                            - <?= esc(ucwords($p['name'] ?? '')) ?>
                                                            <?php if (!empty($waHref)): ?>
                                                                / <a href="<?= esc($waHref) ?>" target="_blank">
                                                                    <?= esc($rawPhone) ?>
                                                                </a>
                                                            <?php endif; ?> / 
                                                            <?= esc($p['category'] ?? '') ?>
                                                            <br/>
                                                        <?php endforeach; ?>
                                                    </small>
                                                <?php endif; ?>
                                            </td>
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
                                                $statusLabel = [
                                                    'pending' => 'Menunggu',
                                                    'paid' => 'Lunas',
                                                    'failed' => 'Gagal',
                                                    'cancelled' => 'Dibatalkan'
                                                ];
                                                ?>
                                                <span class="badge badge-<?= $color ?>"><?= $statusLabel[$order->payment_status] ?? ucfirst($order->payment_status) ?></span>
                                            </td>
                                            <td>
                                                <?php
                                                $orderStatusColor = [
                                                    'active' => 'primary',
                                                    'cancelled' => 'secondary',
                                                    'completed' => 'success'
                                                ];
                                                $orderLabel = [
                                                    'active' => 'Aktif',
                                                    'cancelled' => 'Dibatalkan',
                                                    'completed' => 'Selesai'
                                                ];
                                                $orderColor = $orderStatusColor[$order->status] ?? 'info';
                                                ?>
                                                <span class="badge badge-<?= $orderColor ?>"><?= $orderLabel[$order->status] ?? ucfirst($order->status) ?></span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?= base_url('admin/transaksi/sale/detail/' . $order->id) ?>" 
                                                       class="btn btn-sm btn-info" title="Lihat Detail">
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
                                                <h5>Tidak Ada Pesanan</h5>
                                                <p>Tidak ada pesanan yang sesuai dengan kriteria yang dipilih.</p>
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
                        <?= $pager->links('orders', 'adminlte_pagination') ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Manual Order Modal -->
<div class="modal fade" id="manualOrderModal" tabindex="-1" role="dialog" aria-labelledby="manualOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manualOrderModalLabel">Buat Pesanan Manual</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/transaksi/sale/create-manual-order', ['id' => 'manualOrderForm']) ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="event_id">Event <span class="text-danger">*</span></label>
                            <select class="form-control" id="event_id" name="event_id" required>
                                <option value="">Pilih Event</option>
                                <?php foreach ($events as $event): ?>
                                    <option value="<?= $event->id ?>" data-event-name="<?= esc($event->event) ?>">
                                        <?= esc($event->event) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="price_id">Kategori/Harga <span class="text-danger">*</span></label>
                            <select class="form-control" id="price_id" name="price_id" required>
                                <option value="">Pilih Event Terlebih Dahulu</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr>
                <h6>Informasi Peserta</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="participant_name">Nama Peserta <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="participant_name" name="participant_name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="participant_phone">No. Telepon</label>
                            <input type="text" class="form-control" id="participant_phone" name="participant_phone" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="participant_gender">Jenis Kelamin</label>
                            <select class="form-control" id="participant_gender" name="participant_gender">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="M">Laki-laki</option>
                                <option value="F">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="participant_uk">Ukuran Jersey</label>
                            <select class="form-control" id="participant_uk" name="participant_uk">
                                <option value="">Pilih Ukuran</option>
                                <option value="XS">XS</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="participant_emg">Kontak Darurat</label>
                            <input type="text" class="form-control" id="participant_emg" name="participant_emg" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="participant_address">Alamat</label>
                    <textarea class="form-control" id="participant_address" name="participant_address" rows="2"></textarea>
                </div>

                <hr>
                <h6>Informasi Pembayaran</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="platform_id">Platform Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-control" id="platform_id" name="platform_id" required>
                                <option value="">Pilih Platform</option>
                                <?php foreach ($platforms as $platform): ?>
                                    <option value="<?= $platform->id ?>"><?= esc($platform->nama) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="total_amount">Total Bayar <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="total_amount" name="total_amount" step="0.01" min="0" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <input type="hidden" name="payment_status" id="payment_status" value="pending">
                    <!-- <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_status">Status Pembayaran</label>
                            <select class="form-control" id="payment_status" name="payment_status">
                                <option value="pending">Menunggu</option>
                                <option value="paid">Lunas</option>
                            </select>
                        </div>
                    </div> -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="notes">Catatan</label>
                            <input type="text" class="form-control" id="notes" name="notes" placeholder="Catatan opsional">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Buat Pesanan</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
function updateStatus(orderId, newStatus) {
    if (confirm('Apakah Anda yakin ingin memperbarui status pesanan ini?')) {
        // Create a form and submit it
        var form = $('<form method="POST" action="<?= base_url('admin/transaksi/sale/update-status/') ?>' + orderId + '">');
        form.append('<input type="hidden" name="payment_status" value="' + newStatus + '">');
        form.append('<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">');
        form.appendTo('body').submit();
    }
}

// Load prices when event is selected
$('#event_id').on('change', function() {
    var eventId = $(this).val();
    var priceSelect = $('#price_id');
    
    priceSelect.html('<option value="">Memuat...</option>');
    
    if (eventId) {
        $.ajax({
            url: '<?= base_url('admin/transaksi/sale/get-event-prices/') ?>' + eventId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                priceSelect.html('<option value="">Pilih Harga</option>');
                if (response.success && response.prices) {
                    response.prices.forEach(function(price) {
                        priceSelect.append('<option value="' + price.id + '" data-price="' + price.harga + '">' + 
                            price.keterangan + ' - Rp ' + parseFloat(price.harga).toLocaleString('id-ID') + '</option>');
                    });
                }
            },
            error: function() {
                priceSelect.html('<option value="">Gagal memuat harga</option>');
            }
        });
    } else {
        priceSelect.html('<option value="">Pilih Event Terlebih Dahulu</option>');
    }
});

// Auto-fill total amount when price is selected
$('#price_id').on('change', function() {
    var selectedOption = $(this).find('option:selected');
    var price = selectedOption.data('price');
    if (price) {
        $('#total_amount').val(price);
    }
});
</script>
<?= $this->endSection() ?>