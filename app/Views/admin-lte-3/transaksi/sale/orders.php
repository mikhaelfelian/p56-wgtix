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
                            <?php
                            $searchParam = !empty($search) ? '?search=' . urlencode($search) : '';
                            ?>
                            <a href="<?= base_url('admin/transaksi/sale/orders/all' . $searchParam) ?>" 
                               class="btn <?= $current_status === 'all' ? 'btn-primary' : 'btn-outline-primary' ?>">
                                Semua (<?= $stats['all'] ?>)
                            </a>
                            <a href="<?= base_url('admin/transaksi/sale/orders/pending' . $searchParam) ?>" 
                               class="btn <?= $current_status === 'pending' ? 'btn-warning' : 'btn-outline-warning' ?>">
                                Menunggu (<?= $stats['pending'] ?>)
                            </a>
                            <a href="<?= base_url('admin/transaksi/sale/orders/paid' . $searchParam) ?>" 
                               class="btn <?= $current_status === 'paid' ? 'btn-success' : 'btn-outline-success' ?>">
                                Lunas (<?= $stats['paid'] ?>)
                            </a>
                            <a href="<?= base_url('admin/transaksi/sale/orders/failed' . $searchParam) ?>" 
                               class="btn <?= $current_status === 'failed' ? 'btn-danger' : 'btn-outline-danger' ?>">
                                Gagal (<?= $stats['failed'] ?>)
                            </a>
                            <a href="<?= base_url('admin/transaksi/sale/orders/cancelled' . $searchParam) ?>" 
                               class="btn <?= $current_status === 'cancelled' ? 'btn-secondary' : 'btn-outline-secondary' ?>">
                                Dibatalkan (<?= $stats['cancelled'] ?>)
                            </a>
                        </div>
                        
                        <div class="mt-3">
                            <form method="GET" action="<?= base_url('admin/transaksi/sale/orders/' . $current_status) ?>" class="form-inline">
                                <div class="input-group" style="width: 300px;">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Cari nama peserta..." 
                                           value="<?= esc($search ?? '') ?>"
                                           autocomplete="off">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                        <?php if (!empty($search)): ?>
                                            <a href="<?= base_url('admin/transaksi/sale/orders/' . $current_status) ?>" class="btn btn-secondary">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="float-right">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#manualOrderModal">
                                <i class="fas fa-plus"></i> Pesanan Manual
                            </button>
                            <a href="<?= base_url('admin/transaksi/sale/reports') ?>" class="btn btn-info">
                                <i class="fas fa-chart-bar"></i> Laporan
                            </a>
                            <?php
                            $exportParams = [];
                            if ($current_status !== 'all') {
                                $exportParams['status'] = $current_status;
                            }
                            if (!empty($search)) {
                                $exportParams['search'] = $search;
                            }
                            $exportUrl = base_url('admin/transaksi/sale/export');
                            if (!empty($exportParams)) {
                                $exportUrl .= '?' . http_build_query($exportParams);
                            }
                            ?>
                            <a href="<?= $exportUrl ?>" class="btn btn-success">
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
                            <?= ucfirst($current_status === 'all' ? 'Semua' : $current_status) ?> Pesanan
                            <?php if (!empty($search)): ?>
                                <span class="badge badge-info">Pencarian: "<?= esc($search) ?>"</span>
                            <?php endif; ?>
                            <span class="badge badge-secondary"><?= count($orders) ?> hasil</span>
                        </h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>No Invoice</th>
                                    <th>Partisipan</th>
                                    <th>Nominal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Setup number for current page data, use pager if available
                                $startNumber = 1;
                                if (isset($pager) && method_exists($pager, 'getCurrentPage') && method_exists($pager, 'getPerPage')) {
                                    $currentPage = $pager->getCurrentPage('orders');
                                    $perPage = $pager->getPerPage('orders');
                                    $startNumber = $perPage * ($currentPage - 1) + 1;
                                }
                                ?>
                                <?php if (!empty($orders)): ?>
                                    <?php $number = $startNumber; ?>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td><?= $number++ ?></td>
                                            <td>
                                                <strong><?= esc($order->invoice_no) ?></strong><br>
                                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($order->invoice_date)) ?></small>
                                                
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
                                                <br/>
                                                <span class="badge badge-<?= $orderColor ?>"><?= $orderLabel[$order->status] ?? ucfirst($order->status) ?></span>
                                            </td>
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
                                            <td>
                                                <strong>Rp <?= number_format($order->total_amount, 0, ',', '.') ?></strong>
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
                                                <br/>
                                                <span class="badge badge-<?= $color ?>"><?= $statusLabel[$order->payment_status] ?? ucfirst($order->payment_status) ?></span>
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
                    <?php if ($pager->getPageCount('orders') > 1): ?>
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
            <?= form_open('admin/transaksi/sale/create-manual-order', ['id' => 'manualOrderForm', 'enctype' => 'multipart/form-data']) ?>
            <input type="hidden" name="uploaded_files" id="uploaded_files" value="" />
            <input type="hidden" name="ktp_file" id="ktp_file" value="" />
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
                            <label for="participant_birthdate">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="participant_birthdate" name="participant_birthdate" 
                                   required max="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="participant_uk">Ukuran Jersey</label>
                            <select class="form-control" id="participant_uk" name="participant_uk">
                                <option value="">Pilih Ukuran</option>
                                <?php if (!empty($ukuranOptions)): ?>
                                    <?php foreach ($ukuranOptions as $id => $label): ?>
                                        <option value="<?= esc($id) ?>"><?= esc($label) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
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

                <div class="form-group" id="ktp_upload_section" style="display: none;">
                    <label style="font-weight: 600; color: #333; margin-bottom: 10px;">
                        <i class="fa fa-id-card" style="margin-right: 8px; color: #28a745;"></i>
                        Upload KTP <span class="text-danger">*</span>
                    </label>
                    <div id="ktp-dropzone" class="dropzone" style="border: 2px dashed #28a745; border-radius: 10px; background: white; padding: 20px; text-align: center; cursor: pointer; transition: all 0.3s ease;">
                        <div class="dz-message" style="margin: 20px 0;">
                            <div style="font-size: 48px; color: #28a745; margin-bottom: 15px;">
                                <i class="fa fa-cloud-upload"></i>
                            </div>
                            <h4 style="color: #28a745; font-weight: 600; margin-bottom: 10px; font-size: 16px;">Drop KTP file here or click to upload</h4>
                            <p style="color: #999; margin: 0; font-size: 14px;">
                                Upload KTP (Kartu Tanda Penduduk) peserta<br>
                                <small>Supported formats: JPG, PNG, PDF (Max size: 5MB)</small>
                            </p>
                        </div>
                    </div>
                    <small class="text-muted" style="display: block; margin-top: 8px;">
                        <i class="fa fa-info-circle"></i> <span id="ktp_requirement_text">Wajib untuk peserta usia 40 tahun ke atas</span>
                    </small>
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label style="font-weight: 600; color: #333; margin-bottom: 10px;">
                                <i class="fa fa-upload" style="margin-right: 8px; color: #667eea;"></i>
                                Upload Attachment
                            </label>
                            <div id="manual-order-dropzone" class="dropzone" style="border: 2px dashed #667eea; border-radius: 10px; background: white; padding: 20px; text-align: center; cursor: pointer; transition: all 0.3s ease;">
                                <div class="dz-message" style="margin: 20px 0;">
                                    <div style="font-size: 48px; color: #667eea; margin-bottom: 15px;">
                                        <i class="fa fa-cloud-upload"></i>
                                    </div>
                                    <h4 style="color: #667eea; font-weight: 600; margin-bottom: 10px; font-size: 16px;">Drop files here or click to upload</h4>
                                    <p style="color: #999; margin: 0; font-size: 14px;">
                                        Upload payment receipt, bank transfer proof, or documents<br>
                                        <small>Supported formats: JPG, PNG, PDF (Max size: 5MB)</small>
                                    </p>
                                </div>
                            </div>
                            <small class="text-muted" style="display: block; margin-top: 8px;">
                                <i class="fa fa-info-circle"></i> Optional: Upload payment proof or related documents
                            </small>
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

<?= $this->section('css') ?>
<!-- Dropzone CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
<style>
/* Custom Dropzone Styling */
.dropzone {
    border: 2px dashed #667eea !important;
    border-radius: 10px !important;
    background: white !important;
    transition: all 0.3s ease !important;
}

.dropzone:hover {
    border-color: #764ba2 !important;
    background: #f8f9ff !important;
}

.dropzone.dz-drag-hover {
    border-color: #56ab2f !important;
    background: #f0fff4 !important;
}

.dropzone .dz-preview {
    margin: 10px;
    border-radius: 8px;
    border: 1px solid #e0e6ed;
    background: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.dropzone .dz-preview .dz-image {
    border-radius: 8px 8px 0 0;
}

.dropzone .dz-preview .dz-details {
    padding: 10px;
}

.dropzone .dz-preview .dz-progress {
    background: #e0e6ed;
    border-radius: 4px;
    overflow: hidden;
}

.dropzone .dz-preview .dz-progress .dz-upload {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.dropzone .dz-preview.dz-success .dz-success-mark {
    color: #56ab2f;
}

.dropzone .dz-preview.dz-error .dz-error-mark {
    color: #e74c3c;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- Dropzone JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script>
// Disable Dropzone auto-discovery
Dropzone.autoDiscover = false;

// Track uploaded files for manual order
var uploadedFiles = [];
var uploadedKtpFile = null;

// Initialize Dropzone for manual order form
var manualOrderDropzone = null;
var ktpDropzone = null;

$(document).ready(function() {
    // Initialize Dropzone when modal is shown
    $('#manualOrderModal').on('shown.bs.modal', function() {
        if (!manualOrderDropzone) {
            manualOrderDropzone = new Dropzone("#manual-order-dropzone", {
                url: "<?= base_url('admin/transaksi/sale/upload-temp') ?>",
                paramName: "file",
                maxFilesize: 5, // MB
                acceptedFiles: ".jpg,.jpeg,.png,.pdf",
                addRemoveLinks: true,
                dictDefaultMessage: '',
                maxFiles: 5,
                parallelUploads: 1,
                uploadMultiple: false,
                timeout: 10000, // 10 seconds timeout
                retries: 0,
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                },
                
                init: function() {
                    var dropzone = this;
                    
                    this.on("sending", function(file, xhr, formData) {
                        // Add CSRF token to form data
                        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
                    });
                    
                    this.on("success", function(file, response) {
                        // Handle both string and object responses
                        var parsedResponse;
                        if (typeof response === 'string') {
                            try {
                                parsedResponse = JSON.parse(response);
                            } catch (e) {
                                console.error('Failed to parse response:', e);
                                this.emit("error", file, 'Invalid server response');
                                return;
                            }
                        } else if (typeof response === 'object' && response !== null) {
                            parsedResponse = response;
                        } else {
                            console.error('Invalid response type:', typeof response);
                            this.emit("error", file, 'Invalid server response type');
                            return;
                        }
                        
                        if (parsedResponse && parsedResponse.success === true) {
                            // Add file to uploaded files array
                            uploadedFiles.push({
                                filename: parsedResponse.filename,
                                original_name: file.name,
                                size: file.size,
                                type: file.type,
                                temp_path: parsedResponse.temp_path || ''
                            });
                            
                            // Update hidden field
                            $('#uploaded_files').val(JSON.stringify(uploadedFiles));
                            
                            // Add success styling
                            $(file.previewElement).addClass('dz-success');
                            
                            console.log('File uploaded successfully:', parsedResponse);
                        } else {
                            console.error('Upload failed - server returned:', parsedResponse);
                            var errorMessage = 'Upload failed';
                            if (parsedResponse && parsedResponse.message) {
                                errorMessage = parsedResponse.message;
                            }
                            this.emit("error", file, errorMessage);
                        }
                    });
                    
                    this.on("error", function(file, errorMessage) {
                        console.error('Upload error:', errorMessage);
                        
                        // Show error styling
                        $(file.previewElement).addClass('dz-error');
                        
                        // Show error message
                        var message = 'Upload failed. Please try again.';
                        
                        if (typeof errorMessage === 'string' && errorMessage.trim() !== '') {
                            message = errorMessage;
                        } else if (errorMessage && typeof errorMessage === 'object' && errorMessage.message) {
                            message = errorMessage.message;
                        } else if (errorMessage && typeof errorMessage === 'object') {
                            message = JSON.stringify(errorMessage);
                        }
                        
                        console.log('Showing error message:', message);
                        alert('Upload Error: ' + message);
                    });
                    
                    this.on("removedfile", function(file) {
                        // Remove file from uploaded files array
                        uploadedFiles = uploadedFiles.filter(function(uploadedFile) {
                            return uploadedFile.original_name !== file.name;
                        });
                        
                        // Update hidden field
                        $('#uploaded_files').val(JSON.stringify(uploadedFiles));
                        
                        console.log('File removed:', file.name);
                    });
                    
                    this.on("maxfilesexceeded", function(file) {
                        alert("Maximum 5 files allowed");
                        this.removeFile(file);
                    });
                },
                
                // Custom preview template
                previewTemplate: `
                    <div class="dz-preview dz-file-preview">
                        <div class="dz-image">
                            <img data-dz-thumbnail />
                        </div>
                        <div class="dz-details">
                            <div class="dz-size"><span data-dz-size></span></div>
                            <div class="dz-filename"><span data-dz-name></span></div>
                        </div>
                        <div class="dz-progress">
                            <span class="dz-upload" data-dz-uploadprogress></span>
                        </div>
                        <div class="dz-error-message"><span data-dz-errormessage></span></div>
                        <div class="dz-success-mark">
                            <i class="fa fa-check-circle" style="color: #56ab2f; font-size: 20px;"></i>
                        </div>
                        <div class="dz-error-mark">
                            <i class="fa fa-times-circle" style="color: #e74c3c; font-size: 20px;"></i>
                        </div>
                        <div class="dz-remove" data-dz-remove style="cursor: pointer; color: #e74c3c; font-size: 12px; text-align: center; padding: 5px;">
                            <i class="fa fa-trash"></i> Remove
                        </div>
                    </div>
                `
            });
        }
        
        // Initialize KTP Dropzone
        if (!ktpDropzone) {
            ktpDropzone = new Dropzone("#ktp-dropzone", {
                url: "<?= base_url('admin/transaksi/sale/upload-temp') ?>",
                paramName: "file",
                maxFilesize: 5, // MB
                acceptedFiles: ".jpg,.jpeg,.png,.pdf",
                addRemoveLinks: true,
                dictDefaultMessage: '',
                maxFiles: 1, // Only one KTP file allowed
                parallelUploads: 1,
                uploadMultiple: false,
                timeout: 10000, // 10 seconds timeout
                retries: 0,
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                },
                
                init: function() {
                    var dropzone = this;
                    
                    this.on("sending", function(file, xhr, formData) {
                        // Add CSRF token to form data
                        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
                    });
                    
                    this.on("success", function(file, response) {
                        // Handle both string and object responses
                        var parsedResponse;
                        if (typeof response === 'string') {
                            try {
                                parsedResponse = JSON.parse(response);
                            } catch (e) {
                                console.error('Failed to parse response:', e);
                                this.emit("error", file, 'Invalid server response');
                                return;
                            }
                        } else if (typeof response === 'object' && response !== null) {
                            parsedResponse = response;
                        } else {
                            console.error('Invalid response type:', typeof response);
                            this.emit("error", file, 'Invalid server response type');
                            return;
                        }
                        
                        if (parsedResponse && parsedResponse.success === true) {
                            // Store KTP file info
                            uploadedKtpFile = {
                                filename: parsedResponse.filename,
                                original_name: file.name,
                                size: file.size,
                                type: file.type,
                                temp_path: parsedResponse.temp_path || ''
                            };
                            
                            // Update hidden field
                            $('#ktp_file').val(JSON.stringify(uploadedKtpFile));
                            
                            // Add success styling
                            $(file.previewElement).addClass('dz-success');
                            
                            console.log('KTP file uploaded successfully:', parsedResponse);
                        } else {
                            console.error('KTP upload failed - server returned:', parsedResponse);
                            var errorMessage = 'Upload failed';
                            if (parsedResponse && parsedResponse.message) {
                                errorMessage = parsedResponse.message;
                            }
                            this.emit("error", file, errorMessage);
                        }
                    });
                    
                    this.on("error", function(file, errorMessage) {
                        console.error('KTP upload error:', errorMessage);
                        
                        // Show error styling
                        $(file.previewElement).addClass('dz-error');
                        
                        // Show error message
                        var message = 'Upload failed. Please try again.';
                        
                        if (typeof errorMessage === 'string' && errorMessage.trim() !== '') {
                            message = errorMessage;
                        } else if (errorMessage && typeof errorMessage === 'object' && errorMessage.message) {
                            message = errorMessage.message;
                        } else if (errorMessage && typeof errorMessage === 'object') {
                            message = JSON.stringify(errorMessage);
                        }
                        
                        console.log('Showing error message:', message);
                        alert('KTP Upload Error: ' + message);
                    });
                    
                    this.on("removedfile", function(file) {
                        // Clear KTP file info
                        uploadedKtpFile = null;
                        
                        // Update hidden field
                        $('#ktp_file').val('');
                        
                        console.log('KTP file removed:', file.name);
                    });
                    
                    this.on("maxfilesexceeded", function(file) {
                        alert("Only one KTP file allowed. Please remove the existing file first.");
                        this.removeFile(file);
                    });
                },
                
                // Custom preview template
                previewTemplate: `
                    <div class="dz-preview dz-file-preview">
                        <div class="dz-image">
                            <img data-dz-thumbnail />
                        </div>
                        <div class="dz-details">
                            <div class="dz-size"><span data-dz-size></span></div>
                            <div class="dz-filename"><span data-dz-name></span></div>
                        </div>
                        <div class="dz-progress">
                            <span class="dz-upload" data-dz-uploadprogress></span>
                        </div>
                        <div class="dz-error-message"><span data-dz-errormessage></span></div>
                        <div class="dz-success-mark">
                            <i class="fa fa-check-circle" style="color: #56ab2f; font-size: 20px;"></i>
                        </div>
                        <div class="dz-error-mark">
                            <i class="fa fa-times-circle" style="color: #e74c3c; font-size: 20px;"></i>
                        </div>
                        <div class="dz-remove" data-dz-remove style="cursor: pointer; color: #e74c3c; font-size: 12px; text-align: center; padding: 5px;">
                            <i class="fa fa-trash"></i> Remove
                        </div>
                    </div>
                `
            });
        }
    });
    
    // Reset Dropzone when modal is hidden
    $('#manualOrderModal').on('hidden.bs.modal', function() {
        if (manualOrderDropzone) {
            manualOrderDropzone.removeAllFiles(true);
            uploadedFiles = [];
            $('#uploaded_files').val('');
        }
        if (ktpDropzone) {
            ktpDropzone.removeAllFiles(true);
            uploadedKtpFile = null;
            $('#ktp_file').val('');
        }
    });
    
    // Function to calculate age from birthdate
    function calculateAge(birthdate) {
        const today = new Date();
        const birth = new Date(birthdate);
        let age = today.getFullYear() - birth.getFullYear();
        const monthDiff = today.getMonth() - birth.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        return age;
    }
    
    // Handle birthdate change to calculate age and show/hide KTP upload
    $('#participant_birthdate').on('change', function() {
        const birthdate = $(this).val();
        const ktpSection = $('#ktp_upload_section');
        const ktpRequirementText = $('#ktp_requirement_text');
        
        if (birthdate) {
            const age = calculateAge(birthdate);
            
            if (age >= 40) {
                ktpSection.show();
                ktpRequirementText.text('Wajib untuk peserta usia 40 tahun ke atas (Usia: ' + age + ' tahun)');
                // Make KTP required by adding a data attribute or validation
                ktpSection.find('#ktp-dropzone').attr('data-required', 'true');
            } else {
                ktpSection.hide();
                // Clear KTP file if age < 40
                if (ktpDropzone) {
                    ktpDropzone.removeAllFiles(true);
                    uploadedKtpFile = null;
                    $('#ktp_file').val('');
                }
                ktpSection.find('#ktp-dropzone').removeAttr('data-required');
            }
        } else {
            ktpSection.hide();
            ktpSection.find('#ktp-dropzone').removeAttr('data-required');
        }
    });
    
    // Add form validation before submit
    $('#manualOrderForm').on('submit', function(e) {
        const birthdate = $('#participant_birthdate').val();
        const ktpSection = $('#ktp_upload_section');
        const isKtpRequired = ktpSection.is(':visible') && ktpSection.find('#ktp-dropzone').attr('data-required') === 'true';
        
        // Validate birthdate
        if (!birthdate) {
            e.preventDefault();
            alert('Tanggal lahir wajib diisi!');
            $('#participant_birthdate').focus();
            return false;
        }
        
        // Validate KTP if required (age >= 40)
        if (isKtpRequired && (!uploadedKtpFile || !$('#ktp_file').val())) {
            e.preventDefault();
            const age = calculateAge(birthdate);
            alert('Upload KTP wajib untuk peserta usia 40 tahun ke atas. Usia peserta: ' + age + ' tahun.');
            ktpSection.find('#ktp-dropzone').focus();
            return false;
        }
        
        return true;
    });
});

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