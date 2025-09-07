<?php
// // Ensure required variables are set
// if (!isset($user)) {
//     throw new \RuntimeException('User data not passed to view');
// }
if (!isset($Pengaturan)) {
    throw new \RuntimeException('Settings data not passed to view');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Dashboard' ?> | <?= $Pengaturan->judul_app ?? env('app.name') ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url($Pengaturan->favicon) ?>">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/theme/admin-lte-3/plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('assets/theme/admin-lte-3/dist/css/adminlte.min.css') ?>">
    
    <?= $this->renderSection('css') ?>

    <!-- Core Scripts -->
    <script src="<?= base_url('assets/theme/admin-lte-3/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/theme/admin-lte-3/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/theme/admin-lte-3/plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
    <script src="<?= base_url('assets/theme/admin-lte-3/plugins/moment/moment.min.js') ?>"></script>
    <script src="<?= base_url('assets/theme/admin-lte-3/dist/js/adminlte.min.js') ?>"></script>
    <link rel="stylesheet" href="<?= base_url('assets/theme/admin-lte-3/plugins/jquery-ui/jquery-ui.min.css') ?>">

    <!-- Datepicker -->
    <script src="<?= base_url('assets/theme/admin-lte-3/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') ?>"></script>
    <script src="<?= base_url('assets/theme/admin-lte-3/plugins/bootstrap-datepicker/bootstrap-datepicker.id.min.js') ?>"></script>
    <link rel="stylesheet" href="<?= base_url('assets/theme/admin-lte-3/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') ?>">

    <!-- Select2 -->
    <script src="<?= base_url('assets/theme/admin-lte-3/plugins/select2/js/select2.full.min.js') ?>"></script>
    <link rel="stylesheet" href="<?= base_url('assets/theme/admin-lte-3/plugins/select2/css/select2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/theme/admin-lte-3/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">

    <!-- AutoNumeric -->
    <script src="<?= base_url('assets/theme/admin-lte-3/plugins/JAutoNumber/autonumeric.js') ?>"></script>
    <?= csrf_meta() ?>

    <!-- Toastr -->
    <script src="<?= base_url('assets/theme/admin-lte-3/plugins/toastr/toastr.min.js') ?>"></script>
    <link rel="stylesheet" href="<?= base_url('assets/theme/admin-lte-3/plugins/toastr/toastr.min.css') ?>">

    <!-- iCheck Bootstrap -->
    <link rel="stylesheet" href="<?= base_url('assets/theme/admin-lte-3/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">

    <!-- jQuery UI -->
    <link rel="stylesheet" href="<?= base_url('assets/theme/admin-lte-3/plugins/jquery-ui/jquery-ui.min.css') ?>">

    <script src="<?= base_url('assets/theme/admin-lte-3/plugins/JAutonumber/autonumeric.js') ?>"></script>
    <script src="<?= base_url('assets/theme/admin-lte-3/plugins/jquery-ui/jquery-ui.js') ?>"></script>
    <script src="<?= base_url('assets/theme/admin-lte-3/plugins/moment/moment.min.js') ?>"></script>
    <link href="<?= base_url('assets/theme/admin-lte-3/plugins/jquery-ui/jquery-ui.min.css') ?>" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <?= $this->include('admin-lte-3/layout/navbar') ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?= $this->include('admin-lte-3/layout/sidebar') ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><?= $title ?? 'Dashboard' ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <?= $breadcrumbs ?? '<li class="breadcrumb-item active">Dashboard</li>' ?>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <?= $this->include('admin-lte-3/layout/footer') ?>
    </div>
    <!-- ./wrapper -->
    <?= $this->renderSection('js') ?>

    <!-- Global Toastr Configuration -->
    <script>
        // Global toastr configuration - available anywhere
        window.toastrConfig = {
            closeButton: true,
            debug: false,
            newestOnTop: true,
            progressBar: true,
            positionClass: "toast-top-right",
            preventDuplicates: false,
            onclick: null,
            showDuration: "300",
            hideDuration: "1000",
            timeOut: "5000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut"
        };

        // Global toastr helper functions
        window.showToast = {
            success: function(message, title = null) {
                toastr.options = window.toastrConfig;
                toastr.success(message, title);
            },
            error: function(message, title = null) {
                toastr.options = window.toastrConfig;
                toastr.error(message, title);
            },
            warning: function(message, title = null) {
                toastr.options = window.toastrConfig;
                toastr.warning(message, title);
            },
            info: function(message, title = null) {
                toastr.options = window.toastrConfig;
                toastr.info(message, title);
            },
            // Custom method for AJAX responses
            fromResponse: function(response) {
                if (response.success) {
                    this.success(response.message || 'Operation completed successfully');
                } else {
                    this.error(response.message || 'Operation failed');
                }
            },
            // Method for handling validation errors
            validationErrors: function(errors) {
                if (typeof errors === 'object') {
                    Object.keys(errors).forEach(function(field) {
                        if (Array.isArray(errors[field])) {
                            errors[field].forEach(function(error) {
                                showToast.error(error, 'Validation Error');
                            });
                        } else {
                            showToast.error(errors[field], 'Validation Error');
                        }
                    });
                } else if (typeof errors === 'string') {
                    this.error(errors, 'Validation Error');
                }
            }
        };

        $(document).ready(function () {
            // Apply global toastr configuration
            toastr.options = window.toastrConfig;

            // Handle flash messages from CodeIgniter session
            <?php if (session()->getFlashdata('success')): ?>
                showToast.success('<?= addslashes(session()->getFlashdata('success')) ?>');
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                showToast.error('<?= addslashes(session()->getFlashdata('error')) ?>');
            <?php endif; ?>

            <?php if (session()->getFlashdata('warning')): ?>
                showToast.warning('<?= addslashes(session()->getFlashdata('warning')) ?>');
            <?php endif; ?>

            <?php if (session()->getFlashdata('info')): ?>
                showToast.info('<?= addslashes(session()->getFlashdata('info')) ?>');
            <?php endif; ?>

            // Global AJAX error handler
            $(document).ajaxError(function(event, xhr, settings) {
                if (xhr.status === 403) {
                    showToast.error('Access forbidden. Please check your permissions.', 'Error 403');
                } else if (xhr.status === 404) {
                    showToast.error('Resource not found.', 'Error 404');
                } else if (xhr.status === 500) {
                    showToast.error('Internal server error. Please try again.', 'Error 500');
                } else if (xhr.status !== 200 && xhr.status !== 0) {
                    showToast.error('An error occurred: ' + xhr.statusText, 'Error ' + xhr.status);
                }
            });

            // Global CSRF token refresh for AJAX
            window.refreshCSRFToken = function() {
                $.get('<?= base_url("csrf-token") ?>', function(data) {
                    if (data.token) {
                        $('meta[name="X-CSRF-TOKEN"]').attr('content', data.token);
                        window.csrf_hash = data.token;
                    }
                });
            };
        });
    </script>

    <!-- jQuery UI -->
    <script src="<?= base_url('assets/theme/admin-lte-3/plugins/jquery-ui/jquery-ui.min.js') ?>"></script>

    <!-- Select2 -->
    <script src="<?= base_url('assets/theme/admin-lte-3/plugins/select2/js/select2.full.min.js') ?>"></script>

    <!-- Sidebar toggle initialization -->
    <script>
        $(document).ready(function() {
            // Ensure AdminLTE sidebar functionality is properly initialized
            if (typeof $.AdminLTE !== 'undefined') {
                $.AdminLTE.init();
            }
            
            // Remember sidebar state on page load
            if (localStorage.getItem('sidebar-collapsed') === 'true') {
                $('body').addClass('sidebar-collapse');
            }
            
            // Save sidebar state when toggled (using AdminLTE's built-in events)
            $(document).on('collapsed.lte.pushmenu', function() {
                localStorage.setItem('sidebar-collapsed', 'true');
            });
            
            $(document).on('shown.lte.pushmenu', function() {
                localStorage.setItem('sidebar-collapsed', 'false');
            });
        });
    </script>
</body>
</html>