<?php
/**
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-08-29
 * Github : github.com/mikhaelfelian
 * description : Registration page template for Digital Agency theme
 * This file represents the registration page template for the Digital Agency theme.
 */

echo $this->extend('da-theme/layout/main');
echo $this->section('content');
?>
<!--Register Form-->
<section class="row checkout-content">
    <div class="container">
        <div class="row">
            <?= form_open('auth/register_store', ['class' => 'col-md-9 checkout-form', 'role' => 'form', 'id' => 'registerForm']) ?>
            <h3 class="checkout-heading">Informasi Pendaftaran</h3>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label for="first_name">Nama Depan</label>
                    <?= form_input([
                        'name' => 'first_name',
                        'id' => 'first_name',
                        'class' => 'form-control',
                        'required' => true,
                        'value' => old('first_name')
                    ]) ?>
                </div>
                <div class="col-sm-6 form-group">
                    <label for="last_name">Nama Belakang</label>
                    <?= form_input([
                        'name' => 'last_name',
                        'id' => 'last_name',
                        'class' => 'form-control',
                        'required' => true,
                        'value' => old('last_name')
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label for="username">Nama Pengguna <i>* Username</i></label>
                    <?= form_input([
                        'name' => 'username',
                        'id' => 'username',
                        'class' => 'form-control',
                        'required' => true,
                        'value' => old('username')
                    ]) ?>
                </div>
                <div class="col-sm-6 form-group">
                    <label for="email">Alamat Email</label>
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
                    <label for="password">Kata Sandi</label>
                    <?= form_password([
                        'name' => 'password',
                        'id' => 'password',
                        'class' => 'form-control',
                        'required' => true
                    ]) ?>
                    <div class="password-strength-meter" style="margin-top: 5px;">
                        <div class="strength-bar"
                            style="height: 5px; background: #eee; border-radius: 3px; overflow: hidden;">
                            <div class="strength-fill" style="height: 100%; width: 0%; transition: all 0.3s ease;">
                            </div>
                        </div>
                        <small class="strength-text" style="color: #666; font-size: 12px;">Kekuatan kata sandi: <span
                                class="strength-label">Masukkan kata sandi</span></small>
                    </div>
                </div>
                <div class="col-sm-6 form-group">
                    <label for="password_confirm">Konfirmasi Kata Sandi</label>
                    <?= form_password([
                        'name' => 'password_confirm',
                        'id' => 'password_confirm',
                        'class' => 'form-control',
                        'required' => true
                    ]) ?>
                    <div class="password-match-indicator" style="margin-top: 5px;">
                        <i class="fa fa-circle-o" style="color: #666; margin-right: 5px;"></i>
                        <span class="match-text" style="color: #666; font-size: 12px;">
                            <span class="match-label">Masukkan konfirmasi kata sandi</span>
                        </span>
                    </div>
                </div>
            </div>
            <h3 class="checkout-heading child2">Informasi Tambahan</h3>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label for="company">Instansi</label>
                    <?= form_input([
                        'name' => 'company',
                        'id' => 'company',
                        'class' => 'form-control',
                        'placeholder' => 'masukkan instansi cth : PERCASI, PERBASI, PSMTI, dll',
                        'value' => old('company')
                    ]) ?>
                </div>
                <div class="col-sm-6 form-group">
                    <label for="phone">Nomor Telepon *</label>
                        <?= form_input([
                            'name' => 'phone',
                            'id' => 'phone',
                            'class' => 'form-control',
                            'required' => true,
                            'placeholder' => 'masukkan nomor telepon 08xxxxxxxxx',
                            'pattern' => '^08[0-9]{8,11}$',
                            'title' => 'Masukkan nomor telepon Indonesia yang valid (contoh: 085741220427)',
                            'value' => old('phone'),
                            'maxlength' => '15'
                        ]) ?>   
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <label for="profile">Profil/Biografi</label>
                    <?= form_textarea([
                        'name' => 'profile',
                        'id' => 'profile',
                        'class' => 'form-control',
                        'rows' => '3',
                        'placeholder' => 'Ceritakan tentang diri Anda (maksimal 160 karakter)',
                        'value' => old('profile')
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 form-group">
                </div>
                <div class="col-sm-6 form-group">
                    <div class="checkbox" style="margin-top: 30px;">
                        <?= form_checkbox([
                            'name' => 'terms',
                            'id' => 'terms',
                            'value' => '1',
                            'required' => true,
                            'checked' => old('terms') ? true : false
                        ]) ?>
                        <label for="terms">Saya setuju dengan Syarat & Ketentuan *</label>
                    </div>
                </div>
            </div>

            <!-- reCAPTCHA v3 Widget -->
            <?= form_submit([
                'name' => 'btn_submit',
                'id' => 'submitBtn',
                'value' => 'Buat Akun',
                'class' => 'btn btn-default place-order',
            ]) ?>
            <?= form_close() ?>
        </div>
    </div>
</section>
<?php
echo $this->endSection();
?>

<?= $this->section('js') ?>
<!-- Google reCAPTCHA v3 Script -->
<script src="https://www.google.com/recaptcha/api.js?render=<?= env('recaptcha.sitekey') ?>"></script>

<script>
    $(document).ready(function () {
        // Password strength validation
        $('#password').on('input', function () {
            const password = $(this).val();
            const strength = checkPasswordStrength(password);
            updatePasswordStrengthMeter(strength);

            // Also check password confirmation when password changes
            const confirmPassword = $('#password_confirm').val();
            if (confirmPassword !== '') {
                updatePasswordMatchIndicator(password, confirmPassword);
            }
        });

        // Password confirmation match validation
        $('#password_confirm').on('input', function () {
            const password = $('#password').val();
            const confirmPassword = $(this).val();
            updatePasswordMatchIndicator(password, confirmPassword);
        });

        // Real-time phone number validation
        $('#phone').on('input', function () {
            const phone = $(this).val().trim();
            const phonePattern = /^08[0-9]{8,11}$/;
            const phoneGroup = $(this).closest('.form-group');
            
            // Remove existing validation classes and messages
            phoneGroup.removeClass('has-error has-success');
            phoneGroup.find('.help-block').remove();
            
            if (phone === '') {
                // Empty field - no validation message
                return;
            }
            
            if (phone.length < 10 || phone.length > 15) {
                phoneGroup.addClass('has-error');
                phoneGroup.append('<span class="help-block text-danger">Nomor telepon harus 10-15 digit.</span>');
                return;
            }
            
            if (!phonePattern.test(phone)) {
                phoneGroup.addClass('has-error');
                phoneGroup.append('<span class="help-block text-danger">Format nomor telepon tidak valid. Gunakan format Indonesia: 085741220427</span>');
                return;
            }
            
            // Valid phone number
            phoneGroup.addClass('has-success');
            phoneGroup.append('<span class="help-block text-success">✓ Format nomor telepon valid</span>');
        });

        // Format phone number as user types (auto-format)
        $('#phone').on('keypress', function (e) {
            // Only allow numbers
            if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                e.preventDefault();
                return;
            }
            
            // Auto-format: ensure it starts with 08
            const currentValue = $(this).val();
            if (currentValue.length === 0 && e.key !== '0') {
                e.preventDefault();
                $(this).val('0');
            } else if (currentValue.length === 1 && currentValue === '0' && e.key !== '8') {
                e.preventDefault();
                $(this).val('08');
            }
        });

        // Form submission with reCAPTCHA v3
        let isSubmitting = false;

        $('#registerForm').on('submit', function (e) {
            e.preventDefault();

            if (isSubmitting) return;     // GUARD: cegah double-submit

            // Check password strength before proceeding
            const password = $('#password').val();
            const passwordStrength = checkPasswordStrength(password);

            if (passwordStrength.score < 3) {
                alert('Kata sandi terlalu lemah. Silakan gunakan kata sandi yang lebih kuat dengan minimal 8 karakter, termasuk huruf besar, huruf kecil, angka, dan karakter khusus.');
                $('#password').focus();
                return;
            }

            // Check if passwords match
            if ($('#password').val() !== $('#password_confirm').val()) {
                alert('Kata sandi tidak cocok. Silakan konfirmasi kata sandi Anda.');
                $('#password_confirm').focus();
                return;
            }

            // Check phone number format (Indonesian format only)
            const phone = $('#phone').val().trim();
            const phonePattern = /^08[0-9]{8,11}$/;
            
            if (!phone) {
                alert('Nomor telepon wajib diisi.');
                $('#phone').focus();
                return;
            }
            
            if (phone.length < 10 || phone.length > 15) {
                alert('Nomor telepon harus 10-15 digit.');
                $('#phone').focus();
                return;
            }
            
            if (!phonePattern.test(phone)) {
                alert('Format nomor telepon tidak valid. Gunakan format Indonesia: 085741220427');
                $('#phone').focus();
                return;
            }

            const formEl = document.getElementById('registerForm');
            const submitBtn = $('#submitBtn');
            const originalText = submitBtn.val();

            submitBtn.val('Memverifikasi...').prop('disabled', true);
            isSubmitting = true;

            executeRecaptcha()
                .then(function (token) {
                    // sisipkan token sudah dilakukan di executeRecaptcha()
                    submitBtn.val('Memproses...');
                    console.log('Submitting form...');

                    // Lepas handler agar tidak intercept lagi,
                    // lalu submit native "kebal-bentrok"
                    $('#registerForm').off('submit');
                    if (formEl.requestSubmit) {
                        formEl.requestSubmit();
                    } else {
                        HTMLFormElement.prototype.submit.call(formEl);
                    }
                })
                .catch(function (err) {
                    console.error('reCAPTCHA error:', err);
                    alert('Verifikasi keamanan gagal. Silakan coba lagi.');
                    isSubmitting = false;
                    submitBtn.val(originalText).prop('disabled', false);
                });

            // fallback guard reset opsional
            setTimeout(function () {
                if (isSubmitting) {
                    isSubmitting = false;
                    submitBtn.val(originalText).prop('disabled', false);
                }
            }, 10000);
        });

        // Password strength checker
        function checkPasswordStrength(password) {
            let score = 0;
            let feedback = [];

            if (password.length >= 8) {
                score += 1;
            } else {
                feedback.push('At least 8 characters');
            }

            if (/[a-z]/.test(password)) {
                score += 1;
            } else {
                feedback.push('Lowercase letter');
            }

            if (/[A-Z]/.test(password)) {
                score += 1;
            } else {
                feedback.push('Uppercase letter');
            }

            if (/[0-9]/.test(password)) {
                score += 1;
            } else {
                feedback.push('Number');
            }

            if (/[^A-Za-z0-9]/.test(password)) {
                score += 1;
            } else {
                feedback.push('Special character');
            }

            let strength = '';
            let color = '';
            let width = '';

            if (score === 0) {
                strength = 'Sangat Lemah';
                color = '#ff4444';
                width = '20%';
            } else if (score === 1) {
                strength = 'Lemah';
                color = '#ff8800';
                width = '40%';
            } else if (score === 2) {
                strength = 'Cukup';
                color = '#ffaa00';
                width = '60%';
            } else if (score === 3) {
                strength = 'Baik';
                color = '#00aa00';
                width = '80%';
            } else if (score === 4) {
                strength = 'Kuat';
                color = '#008800';
                width = '90%';
            } else {
                strength = 'Sangat Kuat';
                color = '#006600';
                width = '100%';
            }

            return {
                score: score,
                strength: strength,
                color: color,
                width: width,
                feedback: feedback
            };
        }

        // Update password strength meter
        function updatePasswordStrengthMeter(strength) {
            $('.strength-fill').css({
                'width': strength.width,
                'background-color': strength.color
            });

            $('.strength-label').text(strength.strength);

            if (strength.score < 3) {
                $('.strength-text').css('color', '#ff4444');
            } else {
                $('.strength-text').css('color', '#666');
            }
        }

        // Update password match indicator
        function updatePasswordMatchIndicator(password, confirmPassword) {
            const matchIndicator = $('.password-match-indicator .match-text');
            const matchLabel = $('.password-match-indicator .match-label');
            const matchIcon = $('.password-match-indicator .fa');

            if (confirmPassword === '') {
                matchIndicator.css('color', '#666');
                matchIcon.removeClass('fa-check-circle fa-times-circle').addClass('fa-circle-o');
                matchLabel.text('Masukkan konfirmasi kata sandi');
            } else if (password === confirmPassword) {
                matchIndicator.css('color', '#28a745');
                matchIcon.removeClass('fa-circle-o fa-times-circle').addClass('fa-check-circle');
                matchLabel.text('Kata sandi cocok ✓');
            } else {
                matchIndicator.css('color', '#dc3545');
                matchIcon.removeClass('fa-circle-o fa-check-circle').addClass('fa-times-circle');
                matchLabel.text('Kata sandi tidak cocok ✗');
            }
        }
    });

    // Execute reCAPTCHA v3 and get score
    function executeRecaptcha() {
        return new Promise((resolve, reject) => {
            console.log('Executing reCAPTCHA...');

            // Check if reCAPTCHA is loaded
            if (typeof grecaptcha === 'undefined') {
                console.error('reCAPTCHA not loaded');
                reject('reCAPTCHA not loaded');
                return;
            }

            // Check if site key is available
            const siteKey = '<?= env('recaptcha.sitekey') ?>';
            if (!siteKey || siteKey === '') {
                console.error('reCAPTCHA site key not configured');
                reject('reCAPTCHA site key not configured');
                return;
            }

            console.log('reCAPTCHA site key:', siteKey);

            try {
                grecaptcha.ready(function () {
                    console.log('reCAPTCHA ready, executing...');
                    grecaptcha.execute(siteKey, { action: 'register' })
                        .then(function (token) {
                            console.log('reCAPTCHA token received:', token ? 'YES' : 'NO');
                            console.log('Token length:', token ? token.length : 0);

                            // Validate token
                            if (!token || token === '') {
                                console.error('Empty token received');
                                reject('Empty reCAPTCHA token');
                                return;
                            }

                            // Add the token to a hidden input field
                            if (!$('#g-recaptcha-response').length) {
                                $('<input>').attr({
                                    type: 'hidden',
                                    id: 'g-recaptcha-response',
                                    name: 'g-recaptcha-response'
                                }).appendTo('#registerForm');
                            }
                            $('#g-recaptcha-response').val(token);
                            console.log('Token added to form, resolving...');
                            resolve(token);
                        })
                        .catch(function (error) {
                            console.error('reCAPTCHA execution error:', error);
                            reject(error);
                        });
                });
            } catch (error) {
                console.error('Error in grecaptcha.ready:', error);
                reject(error);
            }
        });
    }
</script>
<?= $this->endSection() ?>