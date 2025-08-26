<?= $this->extend('admin-lte-3/layout/main_front') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-gradient-primary">
            <div class="card-body text-center text-white">
                <h1 class="display-4 mb-3">
                    <i class="fas fa-running mr-3"></i>
                    Event Registration 2025
                </h1>
                <p class="lead mb-0">Daftar sekarang dan bergabunglah dalam event terbesar tahun ini!</p>
            </div>
        </div>
    </div>
</div>

<!-- Registration Form -->
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-outline card-primary">
            <div class="card-header bg-primary">
                <h3 class="card-title text-white">
                    <i class="fas fa-user-plus mr-2"></i>
                    Formulir Pendaftaran Peserta
                </h3>
            </div>
            <div class="card-body">
                <form id="registrationForm" method="POST" action="<?= base_url('frontend/register') ?>">
                    <?= csrf_field() ?>
                    
                    <!-- Personal Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap" class="font-weight-bold">
                                    <i class="fas fa-user mr-1"></i>Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-lg" id="nama_lengkap" name="nama_lengkap" required placeholder="Masukkan nama lengkap">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jenis_kelamin" class="font-weight-bold">
                                    <i class="fas fa-venus-mars mr-1"></i>Jenis Kelamin <span class="text-danger">*</span>
                                </label>
                                <select class="form-control form-control-lg" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tempat_lahir" class="font-weight-bold">
                                    <i class="fas fa-map-marker-alt mr-1"></i>Tempat Lahir
                                </label>
                                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Tempat lahir">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_lahir" class="font-weight-bold">
                                    <i class="fas fa-calendar mr-1"></i>Tanggal Lahir
                                </label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_hp" class="font-weight-bold">
                                    <i class="fas fa-phone mr-1"></i>Nomor HP
                                </label>
                                <input type="tel" class="form-control" id="no_hp" name="no_hp" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="font-weight-bold">
                                    <i class="fas fa-envelope mr-1"></i>Email
                                </label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="email@example.com">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alamat" class="font-weight-bold">
                            <i class="fas fa-home mr-1"></i>Alamat
                        </label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Alamat lengkap"></textarea>
                    </div>

                    <!-- Event Information -->
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="text-primary mb-3">
                                <i class="fas fa-calendar-alt mr-2"></i>Informasi Event
                            </h4>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_kategori" class="font-weight-bold">
                                    <i class="fas fa-running mr-1"></i>Kategori Event
                                </label>
                                <select class="form-control select2" id="id_kategori" name="id_kategori">
                                    <option value="">Pilih Kategori</option>
                                    <?php foreach ($kategoriOptions as $kategori): ?>
                                        <option value="<?= $kategori->id ?>"><?= $kategori->kategori ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_kelompok" class="font-weight-bold">
                                    <i class="fas fa-users mr-1"></i>Kelompok (Opsional)
                                </label>
                                <select class="form-control select2" id="id_kelompok" name="id_kelompok">
                                    <option value="">Pilih Kelompok</option>
                                    <?php foreach ($kelompokOptions as $id => $nama): ?>
                                        <option value="<?= $id ?>"><?= $nama ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="text-success mb-3">
                                <i class="fas fa-credit-card mr-2"></i>Metode Pembayaran
                            </h4>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="id_platform" class="font-weight-bold">
                            <i class="fas fa-wallet mr-1"></i>Platform Pembayaran
                        </label>
                        <select class="form-control select2" id="id_platform" name="id_platform">
                            <option value="">Pilih Platform Pembayaran</option>
                            <?php foreach ($platformOptions as $platform): ?>
                                <option value="<?= $platform->id ?>"><?= $platform->nama ?> - <?= $platform->jenis ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="agree_terms" required>
                            <label class="custom-control-label" for="agree_terms">
                                Saya setuju dengan <a href="#" data-toggle="modal" data-target="#termsModal" class="text-primary">syarat dan ketentuan</a> yang berlaku
                            </label>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-5" id="submitBtn">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Daftar Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Event Highlights -->
<div class="row mt-5">
    <div class="col-md-4">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <i class="fas fa-medal text-warning" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-3">Hadiah Menarik</h5>
                <p class="card-text">Hadiah total jutaan rupiah untuk pemenang</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <i class="fas fa-tshirt text-info" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-3">Race Pack</h5>
                <p class="card-text">Dapatkan race pack eksklusif untuk semua peserta</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <i class="fas fa-certificate text-success" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-3">Sertifikat</h5>
                <p class="card-text">Sertifikat resmi untuk semua peserta</p>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-file-contract mr-2"></i>Syarat dan Ketentuan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-clipboard-check mr-2"></i>1. Pendaftaran</h6>
                        <p>Peserta harus mengisi formulir pendaftaran dengan data yang benar dan lengkap.</p>
                        
                        <h6><i class="fas fa-credit-card mr-2"></i>2. Pembayaran</h6>
                        <p>Pembayaran harus dilakukan sesuai dengan metode yang dipilih dalam waktu yang ditentukan.</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-clock mr-2"></i>3. Kehadiran</h6>
                        <p>Peserta wajib hadir pada waktu dan tempat yang telah ditentukan.</p>
                        
                        <h6><i class="fas fa-ban mr-2"></i>4. Pembatalan</h6>
                        <p>Pembatalan pendaftaran dapat dilakukan sesuai dengan kebijakan yang berlaku.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-white">
                    <i class="fas fa-check-circle mr-2"></i>
                    Pendaftaran Berhasil!
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <h4 class="text-success mb-3">Selamat! Anda telah berhasil mendaftar</h4>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-body">
                                <h6><i class="fas fa-qrcode mr-2"></i>QR Code Peserta</h6>
                                <div id="qrCodeDisplay" class="mb-3"></div>
                                <p class="text-muted">Simpan QR Code ini untuk keperluan check-in</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-info">
                            <div class="card-body">
                                <h6><i class="fas fa-id-card mr-2"></i>Informasi Peserta</h6>
                                <p><strong>Kode Peserta:</strong><br><span id="kodePeserta" class="badge badge-info badge-lg"></span></p>
                                <p class="text-muted">Simpan kode peserta Anda dengan baik</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3">
                    <h6><i class="fas fa-info-circle mr-2"></i>Langkah Selanjutnya</h6>
                    <p class="mb-0">Anda akan otomatis diarahkan ke halaman pembayaran dalam 3 detik. Silakan lakukan pembayaran sesuai metode yang dipilih untuk menyelesaikan pendaftaran.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="proceedToPayment">
                    <i class="fas fa-credit-card mr-2"></i>Lanjutkan ke Pembayaran
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: 'Pilih opsi...'
    });

    // Form submission
    $('#registrationForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $('#submitBtn');
        const originalText = submitBtn.html();
        
        // Disable button and show loading
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...');
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show QR code if available
                    if (response.data.qr_code) {
                        $('#qrCodeDisplay').html('<img src="data:image/png;base64,' + response.data.qr_code + '" alt="QR Code" style="max-width: 150px;" class="img-fluid">');
                    }
                    
                    // Set kode peserta
                    $('#kodePeserta').text(response.data.kode_peserta);
                    
                    // Show success modal
                    $('#successModal').modal('show');
                    
                    // Handle payment URL if available
                    if (response.data.payment_url && response.data.redirect_to_payment) {
                        $('#proceedToPayment').show().off('click').on('click', function() {
                            window.location.href = response.data.payment_url;
                        });
                        
                        // Auto redirect to payment gateway after 3 seconds
                        setTimeout(function() {
                            window.location.href = response.data.payment_url;
                        }, 3000);
                    } else {
                        $('#proceedToPayment').hide();
                    }
                    
                    // Reset form
                    $('#registrationForm')[0].reset();
                    $('.select2').val('').trigger('change');
                    
                } else {
                    toastr.error(response.message);
                    if (response.errors) {
                        Object.keys(response.errors).forEach(function(key) {
                            toastr.error(response.errors[key]);
                        });
                    }
                }
            },
            error: function(xhr, status, error) {
                toastr.error('Terjadi kesalahan. Silakan coba lagi.');
                console.error(xhr.responseText);
            },
            complete: function() {
                // Re-enable button
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Phone number formatting
    $('#no_hp').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 0 && value.charAt(0) !== '0') {
            value = '0' + value;
        }
        $(this).val(value);
    });

    // Email validation
    $('#email').on('blur', function() {
        const email = $(this).val();
        if (email && !isValidEmail(email)) {
            toastr.warning('Format email tidak valid');
        }
    });

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Add some animation to the form
    $('.form-control').on('focus', function() {
        $(this).parent().addClass('border-primary');
    }).on('blur', function() {
        $(this).parent().removeClass('border-primary');
    });
});
</script>
<?= $this->endSection() ?>
