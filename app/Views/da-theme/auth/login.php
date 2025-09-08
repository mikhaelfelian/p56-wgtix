<?php
/**
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-08-29
 * Github : github.com/mikhaelfelian
 * description : Login page template for Digital Agency theme
 * This file represents the login page template for the Digital Agency theme.
 */

echo $this->extend('da-theme/layout/main');
echo $this->section('content');
?>
<!--Login Form-->
<section class="row login-content">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <!-- Flash Messages -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <i class="fa fa-exclamation-triangle"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <i class="fa fa-check-circle"></i>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?= form_open('auth/cek_login', ['class' => 'login-form', 'role' => 'form', 'id' => 'loginForm']) ?>
                <?php echo form_hidden('return_url', $_GET['return_url'] ?? ''); ?>
                <h3 class="login-heading">Login Pengguna</h3>

                <div class="form-group">
                    <label for="user">Username</label>
                    <?= form_input([
                        'name' => 'user',
                        'id' => 'user',
                        'class' => 'form-control rounded-0',
                        'placeholder' => 'Masukkan username',
                        'autocomplete' => 'username',
                        'required' => true,
                        'value' => old('user')
                    ]) ?>
                    <?php if (session()->getFlashdata('errors.user')): ?>
                        <span class="error-message"><?= session()->getFlashdata('errors.user') ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="pass">Password</label>
                    <div class="input-group">
                        <?= form_password([
                            'name' => 'pass',
                            'id' => 'pass',
                            'class' => 'form-control rounded-0',
                            'placeholder' => 'Masukkan password',
                            'autocomplete' => 'current-password',
                            'required' => true
                        ]) ?>
                        <span class="input-group-addon password-toggle" onclick="togglePassword()">
                            <i class="fa fa-eye" id="passwordIcon"></i>
                        </span>
                    </div>
                    <?php if (session()->getFlashdata('errors.pass')): ?>
                        <span class="error-message"><?= session()->getFlashdata('errors.pass') ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <?= form_checkbox('ingat', '1', old('ingat') ? true : false, ['id' => 'ingat']) ?> Ingat
                            saya
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <?= form_submit('submit', 'Login', ['class' => 'btn btn-primary btn-block rounded-0', 'id' => 'submitBtn']) ?>
                </div>

                <div class="text-center">
                    <a href="<?= base_url('auth/forgot-password') ?>" class="forgot-link">
                        Lupa password?
                    </a>
                </div>

                <!-- Hidden reCAPTCHA token field -->
                <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

                <?= form_close() ?>
                <!-- reCAPTCHA info -->
                <div class="text-center mt-3">
                    <small class="text-muted">
                        This site is protected by reCAPTCHA and the Google
                        <a href="https://policies.google.com/privacy">Privacy Policy</a> and
                        <a href="https://policies.google.com/terms">Terms of Service</a> apply.
                    </small>
                </div>

                <hr class="divider">

                <div class="text-center">
                    <p class="mb-0">Belum punya akun?
                        <a href="<?= base_url('auth/register') ?>" class="signup-link">
                            Daftar disini
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
echo $this->endSection();
?>

<?= $this->section('css') ?>
<style>
    .login-content {
        padding: 60px 0;
        background: #f8f9fa;
    }

    .login-form {
        background: #fff;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
    }

    .login-heading {
        text-align: center;
        color: #333;
        font-weight: 700;
        font-size: 24px;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #667eea;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        border: 2px solid #e1e5e9;
        border-radius: 5px;
        padding: 12px 15px;
        font-size: 14px;
        transition: all 0.3s ease;
        width: 100%;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .input-group {
        position: relative;
        display: flex;
    }

    .input-group .form-control {
        border-right: none;
        border-radius: 5px 0 0 5px;
    }

    .input-group-addon {
        background: #f8f9fa;
        border: 2px solid #e1e5e9;
        border-left: none;
        color: #6c757d;
        padding: 12px 15px;
        display: flex;
        align-items: center;
        border-radius: 0 5px 5px 0;
    }

    .password-toggle {
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .password-toggle:hover {
        color: #667eea;
    }

    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }

    .checkbox {
        display: flex;
        align-items: center;
    }

    .checkbox input[type="checkbox"] {
        margin-right: 8px;
    }

    .checkbox label {
        margin-bottom: 0;
        font-weight: normal;
        color: #666;
    }

    .btn-primary {
        background: #667eea;
        border-color: #667eea;
        font-weight: 600;
        font-size: 16px;
        padding: 12px 30px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: #5a6fd8;
        border-color: #5a6fd8;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .forgot-link {
        color: #667eea;
        text-decoration: none;
        font-size: 14px;
    }

    .forgot-link:hover {
        text-decoration: underline;
    }

    .divider {
        margin: 30px 0;
        border-color: #e9ecef;
    }

    .signup-link {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
    }

    .signup-link:hover {
        text-decoration: underline;
    }

    .alert {
        padding: 12px 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        border: none;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
    }

    .alert i {
        margin-right: 8px;
    }

    /* reCAPTCHA v3 Styling - Invisible */
    .g-recaptcha {
        display: none;
        /* Hide the widget since v3 is invisible */
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .login-form {
            padding: 30px 20px;
            margin: 20px;
        }

        .login-heading {
            font-size: 20px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>

<!-- Add reCAPTCHA v3 -->
<script src="https://www.google.com/recaptcha/api.js?render=<?= config('Recaptcha')->siteKey ?>"></script>
<style>
    /* Style for loading indicator */
    .loading {
        position: relative;
        pointer-events: none;
    }

    .loading:after {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 2;
    }

    /* Style for reCAPTCHA badge */
    .grecaptcha-badge {
        bottom: 60px !important;
    }
</style>

<script>
    // Auto-hide alerts after 5 seconds (vanilla JS)
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function () {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function (alert) {
                alert.style.transition = "opacity 0.5s";
                alert.style.opacity = 0;
                setTimeout(function () {
                    alert.style.display = "none";
                }, 500);
            });
        }, 5000);
    });

    // Password toggle functionality
    function togglePassword() {
        const passwordField = document.getElementById('pass');
        const passwordIcon = document.getElementById('passwordIcon');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            passwordIcon.className = 'fa fa-eye-slash';
        } else {
            passwordField.type = 'password';
            passwordIcon.className = 'fa fa-eye';
        }
    }

    // Form validation
    function validateForm() {
        const user = document.getElementById('user').value.trim();
        const pass = document.getElementById('pass').value.trim();

        if (!user || !pass) {
            alert('Mohon isi semua field yang diperlukan.');
            return false;
        }

        return true;
    }

    // Recaptcha v3
    grecaptcha.ready(function () {
        const form = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');

        if (!form) {
            console.error('Form with id="loginForm" not found.');
            return;
        }

        // Prevent double-binding
        if (form._recaptchaBound) return;
        form._recaptchaBound = true;

        form.addEventListener('submit', function (e) {
            // Prevent default submit only if reCAPTCHA token is not set
            if (!form._recaptchaTokenSet) {
                e.preventDefault();

                // Show loading state
                if (submitBtn) {
                    submitBtn.disabled = true;
                    form.classList.add('loading');
                }

                // Execute reCAPTCHA
                grecaptcha.execute('<?= config('Recaptcha')->siteKey ?>', { action: 'login' })
                    .then(function (token) {
                        // Add token to form
                        document.getElementById('recaptchaResponse').value = token;
                        // Mark that token is set to avoid infinite loop
                        form._recaptchaTokenSet = true;
                        // Submit the form using the native HTMLFormElement submit method
                        HTMLFormElement.prototype.submit.call(form);
                    })
                    .catch(function (error) {
                        // Handle error
                        console.error('reCAPTCHA error:', error);
                        if (typeof toastr !== 'undefined') {
                            toastr.error('Error verifying reCAPTCHA. Please try again.');
                        } else {
                            alert('Error verifying reCAPTCHA. Please try again.');
                        }

                        // Reset loading state
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            form.classList.remove('loading');
                        }
                    });
            }
        });
    });
</script>
<?= $this->endSection() ?>