<?php
/**
 * Participant Registration Form
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Form for users to register participants for their orders
 */

echo $this->extend('da-theme/layout/main');
echo $this->section('content');
?>

<!--Register Form-->
<section class="row checkout-content">
    <div class="container">
        <div class="row">
            <!-- Order Info Section -->
            <div class="col-md-12">
                <div class="checkout-sidebar">
                    <h3 class="checkout-heading">Informasi Order</h3>
                    <div class="order-info">
                        <div class="info-item">
                            <strong>Invoice:</strong><br>
                            <span class="invoice-number"><?= esc($order->invoice_no) ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Tanggal:</strong><br>
                            <?= date('d M Y H:i', strtotime($order->invoice_date)) ?>
                        </div>
                        <div class="info-item">
                            <strong>Status:</strong><br>
                            <span class="badge badge-<?= $order->payment_status === 'paid' ? 'success' : 'warning' ?>">
                                <?= ucfirst($order->payment_status) ?>
                            </span>
                        </div>
                        <div class="info-item">
                            <strong>Total:</strong><br>
                            <span class="total-amount">Rp <?= number_format($order->total_amount, 0, ',', '.') ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Event:</strong><br>
                            <?php if (!empty($orderDetails)): ?>
                                <?= esc($orderDetails[0]->event_title ?? 'N/A') ?>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="<?= site_url('sale/orders') ?>" class="btn btn-success ml-2 mb-2">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Registration Form -->
            <div class="col-md-6">
                <?= form_open('sale/store-participant', ['class' => 'checkout-form', 'role' => 'form', 'id' => 'participantForm']) ?>
                <!-- Hidden fields -->
                <input type="hidden" name="order_id" value="<?= esc($order->id) ?>">
                <input type="hidden" name="event_id" value="<?= esc($eventId) ?>">

                <h3 class="checkout-heading">Data Peserta</h3>
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label for="nama">Nama Lengkap *</label>
                        <?= form_input([
                            'name' => 'nama',
                            'id' => 'nama',
                            'class' => 'form-control',
                            'required' => true,
                            'value' => old('nama')
                        ]) ?>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label for="email">Alamat Email *</label>
                        <?= form_input([
                            'name' => 'email',
                            'id' => 'email',
                            'type' => 'email',
                            'class' => 'form-control',
                            'required' => true,
                            'value' => old('email')
                        ]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label for="no_hp">Nomor Telepon *</label>
                        <?= form_input([
                            'name' => 'no_hp',
                            'id' => 'no_hp',
                            'class' => 'form-control',
                            'required' => true,
                            'placeholder' => '08xxxxxxxxxx',
                            'pattern' => '^08[0-9]{8,11}$',
                            'title' => 'Masukkan nomor telepon yang valid (contoh: 085741220427)',
                            'value' => old('no_hp')
                        ]) ?>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label for="jns_klm">Jenis Kelamin *</label>
                        <?= form_dropdown('jns_klm', [
                            '' => 'Pilih Jenis Kelamin',
                            'L' => 'Laki-laki',
                            'P' => 'Perempuan'
                        ], old('jns_klm'), [
                            'id' => 'jns_klm',
                            'class' => 'form-control',
                            'required' => true
                        ]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label for="tmp_lahir">Tempat Lahir</label>
                        <?= form_input([
                            'name' => 'tmp_lahir',
                            'id' => 'tmp_lahir',
                            'class' => 'form-control',
                            'placeholder' => 'Kota tempat lahir',
                            'value' => old('tmp_lahir')
                        ]) ?>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label for="tgl_lahir">Tanggal Lahir</label>
                        <?= form_input([
                            'name' => 'tgl_lahir',
                            'id' => 'tgl_lahir',
                            'type' => 'date',
                            'class' => 'form-control',
                            'value' => old('tgl_lahir')
                        ]) ?>
                    </div>
                </div>
                <h3 class="checkout-heading child2">Informasi Tambahan</h3>
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label for="id_kategori">Kategori</label>
                        <?php
                        $kategoriDropdownOptions = ['' => 'Pilih Kategori'];
                        if (isset($kategoriOptions) && !empty($kategoriOptions)) {
                            foreach ($kategoriOptions as $kategori) {
                                $kategoriDropdownOptions[$kategori->id] = $kategori->kategori;
                            }
                        }
                        ?>
                        <?= form_dropdown('id_kategori', $kategoriDropdownOptions, old('id_kategori'), [
                            'id' => 'id_kategori',
                            'class' => 'form-control'
                        ]) ?>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label for="id_kelompok">Kelompok</label>
                        <?php
                        $kelompokDropdownOptions = ['' => 'Pilih Kelompok'];
                        if (isset($kelompokOptions) && !empty($kelompokOptions)) {
                            foreach ($kelompokOptions as $kelompok) {
                                $kelompokDropdownOptions[$kelompok->id] = $kelompok->nama_kelompok;
                            }
                        }
                        ?>
                        <?= form_dropdown('id_kelompok', $kelompokDropdownOptions, old('id_kelompok'), [
                            'id' => 'id_kelompok',
                            'class' => 'form-control'
                        ]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <label for="alamat">Alamat</label>
                        <?= form_textarea([
                            'name' => 'alamat',
                            'id' => 'alamat',
                            'class' => 'form-control',
                            'rows' => '3',
                            'placeholder' => 'Alamat lengkap',
                            'value' => old('alamat')
                        ]) ?>
                    </div>
                </div>

                <?= form_submit([
                    'name' => 'btn_submit',
                    'id' => 'submitBtn',
                    'value' => 'Daftarkan Peserta',
                    'class' => 'btn btn-default place-order',
                ]) ?>
                <?= form_close() ?>
            </div>
            <div class="col-md-6">
                <div class="checkout-sidebar">
                    <h3 class="checkout-heading">Daftar Peserta Terdaftar</h3>
                    <div class="participants-list">
                        <?php if (isset($participants) && !empty($participants)): ?>
                            <div class="participant-count">
                                <small class="text-muted">Total: <?= count($participants) ?> peserta</small>
                            </div>
                            <div class="participants-container">
                                <?php foreach ($participants as $participant): ?>
                                    <div class="participant-item" onclick="fillParticipantForm(<?= htmlspecialchars(json_encode($participant), ENT_QUOTES, 'UTF-8') ?>)">
                                        <div class="participant-info">
                                            <div class="participant-name">
                                                <strong><?= esc($participant->nama) ?></strong>
                                                <span class="participant-code">#<?= esc($participant->kode) ?></span>
                                            </div>
                                            <div class="participant-details">
                                                <small class="text-muted">
                                                    <i class="fa fa-envelope"></i> <?= esc($participant->email) ?><br>
                                                    <i class="fa fa-phone"></i> <?= esc($participant->no_hp) ?><br>
                                                    <i class="fa fa-venus-mars"></i> <?= $participant->jns_klm == 'L' ? 'Laki-laki' : 'Perempuan' ?>
                                                    <?php if ($participant->tmp_lahir): ?>
                                                        <br><i class="fa fa-map-marker"></i> <?= esc($participant->tmp_lahir) ?>
                                                    <?php endif; ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="participant-status">
                                            <?php if ($participant->status_hadir == 1): ?>
                                                <span class="badge badge-success">Hadir</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">Belum Hadir</span>
                                            <?php endif; ?>
                                            <div class="participant-actions">
                                                <i class="fa fa-edit"></i>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="no-participants">
                                <div class="text-center text-muted">
                                    <i class="fa fa-users fa-3x mb-3"></i>
                                    <p>Belum ada peserta yang terdaftar</p>
                                    <small>Peserta akan muncul di sini setelah mendaftar</small>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Checkout Sidebar Styles */
.checkout-sidebar {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid #e9ecef;
}

.checkout-sidebar .checkout-heading {
    color: #333;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 15px;
    border-bottom: 2px solid #007bff;
    padding-bottom: 10px;
}

.order-info .info-item {
    margin-bottom: 15px;
    padding: 10px;
    background: white;
    border-radius: 5px;
    border-left: 3px solid #007bff;
}

.order-info .info-item strong {
    color: #333;
    font-size: 14px;
}

.invoice-number {
    color: #007bff;
    font-weight: 600;
    font-family: monospace;
}

.total-amount {
    color: #28a745;
    font-weight: 700;
    font-size: 16px;
}

.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.badge-success {
    background-color: #d4edda;
    color: #155724;
}

.badge-warning {
    background-color: #fff3cd;
    color: #856404;
}

/* Form Styles */
.checkout-form {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.checkout-heading {
    color: #333;
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 25px;
    border-bottom: 2px solid #007bff;
    padding-bottom: 10px;
}

.checkout-heading.child2 {
    margin-top: 30px;
    font-size: 20px;
    border-bottom-color: #28a745;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    color: #333;
    font-weight: 500;
    margin-bottom: 8px;
    display: block;
}

.form-control {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px 12px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: none;
}

.btn-default.place-order {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 12px 30px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 4px;
    transition: background-color 0.3s ease;
    width: 100%;
    margin-top: 20px;
}

.btn-default.place-order:hover {
    background-color: #0056b3;
    color: white;
}

/* Participant List Styles */
.participants-list {
    max-height: 500px;
    overflow-y: auto;
}

.participant-count {
    margin-bottom: 15px;
    padding: 8px 12px;
    background: #e9ecef;
    border-radius: 4px;
    text-align: center;
}

.participants-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.participant-item {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    cursor: pointer;
    position: relative;
}

.participant-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
    border-color: #007bff;
}

.participant-item:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.participant-info {
    flex: 1;
}

.participant-name {
    margin-bottom: 8px;
}

.participant-name strong {
    color: #333;
    font-size: 16px;
    display: block;
    margin-bottom: 4px;
}

.participant-code {
    background: #007bff;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    font-family: monospace;
}

.participant-details {
    margin-top: 8px;
}

.participant-details i {
    width: 14px;
    color: #6c757d;
    margin-right: 6px;
}

.participant-status {
    margin-left: 15px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.participant-actions {
    color: #007bff;
    font-size: 14px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.participant-item:hover .participant-actions {
    opacity: 1;
}

.no-participants {
    padding: 40px 20px;
    text-align: center;
}

.no-participants i {
    color: #dee2e6;
    margin-bottom: 15px;
}

.no-participants p {
    margin-bottom: 8px;
    font-size: 16px;
    color: #6c757d;
}

.no-participants small {
    color: #adb5bd;
}

/* Custom Scrollbar for Participants List */
.participants-list::-webkit-scrollbar {
    width: 6px;
}

.participants-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.participants-list::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.participants-list::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Responsive Design */
@media (max-width: 768px) {
    .checkout-sidebar {
        margin-bottom: 30px;
    }
    
    .checkout-form {
        padding: 20px;
    }
    
    .checkout-heading {
        font-size: 20px;
    }
    
    .participant-item {
        flex-direction: column;
        align-items: stretch;
    }
    
    .participant-status {
        margin-left: 0;
        margin-top: 10px;
        text-align: center;
    }
}
</style>

<script>
function fillParticipantForm(participant) {
    // Fill form fields with participant data
    document.getElementById('nama').value = participant.nama || '';
    document.getElementById('email').value = participant.email || '';
    document.getElementById('no_hp').value = participant.no_hp || '';
    document.getElementById('jns_klm').value = participant.jns_klm || '';
    document.getElementById('tmp_lahir').value = participant.tmp_lahir || '';
    document.getElementById('tgl_lahir').value = participant.tgl_lahir || '';
    document.getElementById('id_kategori').value = participant.id_kategori || '';
    document.getElementById('id_kelompok').value = participant.id_kelompok || '';
    document.getElementById('alamat').value = participant.alamat || '';
    
    // Add hidden field for participant ID if updating
    let participantIdField = document.getElementById('participant_id');
    if (!participantIdField) {
        participantIdField = document.createElement('input');
        participantIdField.type = 'hidden';
        participantIdField.name = 'participant_id';
        participantIdField.id = 'participant_id';
        document.getElementById('participantForm').appendChild(participantIdField);
    }
    participantIdField.value = participant.id || '';
    
    // Update form title and button
    document.querySelector('.checkout-heading').textContent = 'Update Data Peserta';
    document.getElementById('submitBtn').value = 'Update Peserta';
    
    // Scroll to form
    document.querySelector('.checkout-form').scrollIntoView({ 
        behavior: 'smooth', 
        block: 'start' 
    });
    
    // Show success message
    showNotification('Data peserta dimuat ke form. Anda dapat mengubah dan menyimpan data.', 'info');
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'info' ? 'info' : type} alert-dismissible fade show`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}

// Reset form when clicking "Daftarkan Peserta" button
document.getElementById('submitBtn').addEventListener('click', function() {
    if (this.value === 'Daftarkan Peserta') {
        // Clear participant ID if creating new
        const participantIdField = document.getElementById('participant_id');
        if (participantIdField) {
            participantIdField.remove();
        }
        
        // Reset form title
        document.querySelector('.checkout-heading').textContent = 'Data Peserta';
    }
});
</script>

<?= $this->endSection() ?>