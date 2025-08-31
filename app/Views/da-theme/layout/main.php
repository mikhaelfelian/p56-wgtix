<?php
/**
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-08-29
 * Github : github.com/mikhaelfelian
 * description : Main layout template for Digital Agency theme
 * This file represents the main layout template for the Digital Agency theme.
 */

// Ensure required variables are set
if (!isset($Pengaturan)) {
    throw new \RuntimeException('Settings data not passed to view');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $title ?? 'Digital Agency' ?> | <?= esc($Pengaturan->judul_app ?? $Pengaturan->judul ?? 'Digital Agency') ?></title>

    <!--Fonts-->
    <link
        href="https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic"
        rel="stylesheet" type="text/css">

    <!--Bootstrap-->
    <link rel="stylesheet" href="<?= base_url('/public/assets/theme/da-theme/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/public/assets/theme/da-theme/css/bootstrap-theme.min.css') ?>">
    <!--jQuery UI-->
    <link rel="stylesheet" href="<?= base_url('/public/assets/theme/da-theme/vendors/jquery-ui/jquery-ui.min.css') ?>">
    <!--Font Awesome-->
    <link rel="stylesheet" href="<?= base_url('/public/assets/theme/da-theme/css/font-awesome.min.css') ?>">
    <!--Owl Carousel-->
    <link rel="stylesheet"
        href="<?= base_url('/public/assets/theme/da-theme/vendors/owl.carousel/owl.carousel.css') ?>">
    <!--Magnific Popup-->
    <link rel="stylesheet"
        href="<?= base_url('/public/assets/theme/da-theme/vendors/magnific-popup/magnific-popup.css') ?>">
    <!-- RS5.0 Main Stylesheet -->
    <link rel="stylesheet" type="text/css"
        href="<?= base_url('/public/assets/theme/da-theme/vendors/revolution/css/settings.css') ?>">

    <!-- RS5.0 Layers and Navigation Styles -->
    <link rel="stylesheet" type="text/css"
        href="<?= base_url('/public/assets/theme/da-theme/vendors/revolution/css/layers.css') ?>">
    <link rel="stylesheet" type="text/css"
        href="<?= base_url('/public/assets/theme/da-theme/vendors/revolution/css/navigation.css') ?>">

    <!--Theme Styles-->
    <link rel="stylesheet" href="<?= base_url('/public/assets/theme/da-theme/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/public/assets/theme/da-theme/css/theme.css') ?>">

    <!--[if lt IE 9]>
        <script src="<?= base_url('/public/assets/theme/da-theme/js/html5shiv.min.js') ?>"></script>
        <script src="<?= base_url('/public/assets/theme/da-theme/js/respond.min.js') ?>"></script>
    <![endif]-->

    <!-- Custom CSS Section -->
    <?= $this->renderSection('css') ?>
</head>

<body>
    <!--Header-->
    <?= $this->include('da-theme/layout/header') ?>

    <!--Navigation-->
    <?= $this->include('da-theme/layout/navbar') ?>

    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!--Footer-->
    <?= $this->include('da-theme/layout/footer') ?>

    <!--Theme Scripts-->
    <script src="<?= base_url('/public/assets/theme/da-theme/js/jquery-2.1.4.min.js') ?>"></script>
    <script src="<?= base_url('/public/assets/theme/da-theme/vendors/jquery-ui/jquery-ui.min.js') ?>"></script>
    <script src="<?= base_url('/public/assets/theme/da-theme/js/bootstrap.min.js') ?>"></script>

    <!--Magnific Popup-->
    <script
        src="<?= base_url('/public/assets/theme/da-theme/vendors/magnific-popup/jquery.magnific-popup.min.js') ?>"></script>
    <!--Owl Carousel-->
    <script src="<?= base_url('/public/assets/theme/da-theme/vendors/owl.carousel/owl.carousel.min.js') ?>"></script>
    <!--CounterUp-->
    <script src="<?= base_url('/public/assets/theme/da-theme/vendors/couterup/jquery.counterup.min.js') ?>"></script>
    <!--WayPoints-->
    <script src="<?= base_url('/public/assets/theme/da-theme/vendors/waypoint/waypoints.min.js') ?>"></script>
    <!-- RS5.0 Core JS Files -->
    <script
        src="<?= base_url('/public/assets/theme/da-theme/vendors/revolution/js/jquery.themepunch.tools.min.js?rev=5.0') ?>"></script>
    <script
        src="<?= base_url('/public/assets/theme/da-theme/vendors/revolution/js/jquery.themepunch.revolution.min.js?rev=5.0') ?>"></script>
    <!--RS5.0 Extensions-->
    <script
        src="<?= base_url('/public/assets/theme/da-theme/vendors/revolution/js/extensions/revolution.extension.video.min.js') ?>"></script>
    <script
        src="<?= base_url('/public/assets/theme/da-theme/vendors/revolution/js/extensions/revolution.extension.slideanims.min.js') ?>"></script>
    <script
        src="<?= base_url('/public/assets/theme/da-theme/vendors/revolution/js/extensions/revolution.extension.layeranimation.min.js') ?>"></script>
    <script
        src="<?= base_url('/public/assets/theme/da-theme/vendors/revolution/js/extensions/revolution.extension.navigation.min.js') ?>"></script>

    <!--Theme Script-->
    <script src="<?= base_url('/public/assets/theme/da-theme/js/theme.js') ?>"></script>

    <!-- Custom JavaScript Section -->
    <?= $this->renderSection('js') ?>
</body>

</html>
