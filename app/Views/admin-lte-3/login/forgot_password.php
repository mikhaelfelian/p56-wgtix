<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?> | <?= $Pengaturan->judul_app ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url($Pengaturan->favicon) ?>">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('public/assets/theme/admin-lte-3/plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('public/assets/theme/admin-lte-3/dist/css/adminlte.min.css') ?>">
    <!-- Toastr -->
    <link rel="stylesheet" href="<?= base_url('public/assets/theme/admin-lte-3/plugins/toastr/toastr.min.css') ?>">

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
        background: rgba(255,255,255,0.8) url('<?= base_url('public/assets/img/loading.gif') ?>') center no-repeat;
        z-index: 2;
    }
    /* Style for reCAPTCHA badge */
    .grecaptcha-badge {
        bottom: 60px !important;
    }
    </style>
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-success">
            <div class="card-header text-center">
                <img src="<?= !empty($Pengaturan->logo_header) ? base_url($Pengaturan->logo_header) : base_url('public/assets/theme/admin-lte-3/dist/img/AdminLTELogo.png') ?>" alt="Logo" class="img-fluid"
                    style="width: 209px; height: 94px; background-color: #fff;">
            </div>
            <div class="card-body">
                <p class="login-box-msg">Lupa Kata Sandi</p>
                <p class="text-muted text-center mb-3">Masukkan email Anda untuk mendapatkan link reset password</p>

                <?= form_open('auth/forgot-password', ['id' => 'forgot_password_form']) ?>
                
                <!-- Display validation errors -->
                <?php 
                $validation = \Config\Services::validation();
                if ($validation->getErrors()): 
                ?>
                    <div class="alert alert-danger alert-dismissible fade show rounded-0" role="alert">
                        <?php foreach ($validation->getErrors() as $error): ?>
                            <div><?= $error ?></div>
                        <?php endforeach; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <div class="input-group mb-3">
                    <?= form_input([
                        'type'          => 'email',
                        'name'          => 'identity', 
                        'class'         => 'form-control rounded-0' . ($validation->hasError('identity') ? ' is-invalid' : ''),
                        'placeholder'   => 'Email ...',
                        'required'      => true,
                        'value'         => old('identity', '')
                    ]) ?>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    <?php if ($validation->hasError('identity')): ?>
                        <div class="invalid-feedback d-block">
                            <?= $validation->getError('identity') ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block rounded-0" id="submitBtn">
                            <span>Kirim Link Reset Password</span>
                            <i class="fas fa-spinner fa-spin d-none"></i>
                        </button>
                    </div>
                </div>

                <!-- Hidden reCAPTCHA token field -->
                <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

                <?= form_close() ?>

                <p class="mt-3 mb-1 text-center">
                    <a href="<?= base_url('auth/login') ?>" class="text-center">
                        <i class="fas fa-arrow-left"></i> Kembali ke Login
                    </a>
                </p>

                <!-- reCAPTCHA info -->
                <div class="text-center mt-3">
                    <small class="text-muted">
                        This site is protected by reCAPTCHA and the Google
                        <a href="https://policies.google.com/privacy">Privacy Policy</a> and
                        <a href="https://policies.google.com/terms">Terms of Service</a> apply.
                    </small>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="<?= base_url('public/assets/theme/admin-lte-3/plugins/jquery/jquery.min.js') ?>"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url('public/assets/theme/admin-lte-3/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url('public/assets/theme/admin-lte-3/dist/js/adminlte.min.js') ?>"></script>
    <!-- Toastr -->
    <script src="<?= base_url('public/assets/theme/admin-lte-3/plugins/toastr/toastr.min.js') ?>"></script>

    <?php if (session()->has('toastr')): ?>
        <?php 
        $toastr = session('toastr');
        echo toast_show($toastr['message'], $toastr['type'], 'Lupa Kata Sandi');
        ?>
    <?php endif; ?>

    <!-- Add reCAPTCHA script -->
    <script>
    grecaptcha.ready(function() {
        const form = document.getElementById('forgot_password_form');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn.querySelector('span');
        const btnLoader = submitBtn.querySelector('i');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            submitBtn.disabled = true;
            btnText.classList.add('d-none');
            btnLoader.classList.remove('d-none');
            form.classList.add('loading');

            // Execute reCAPTCHA
            grecaptcha.execute('<?= config('Recaptcha')->siteKey ?>', {action: 'forgot_password'})
                .then(function(token) {
                    // Add token to form
                    document.getElementById('recaptchaResponse').value = token;
                    // Submit form
                    form.submit();
                })
                .catch(function(error) {
                    // Handle error
                    console.error('reCAPTCHA error:', error);
                    toastr.error('Error verifying reCAPTCHA. Please try again.');
                    
                    // Reset loading state
                    submitBtn.disabled = false;
                    btnText.classList.remove('d-none');
                    btnLoader.classList.add('d-none');
                    form.classList.remove('loading');
                });
        });
    });
    </script>
</body>
</html>
