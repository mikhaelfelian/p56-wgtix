<?php
/**
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-08-29
 * Github : github.com/mikhaelfelian
 * description : Footer section template for Digital Agency theme
 * This file represents the footer section template for the Digital Agency theme.
 */
?>

<!--Footer-->
<footer class="row site-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-md-push-5 half-side">
                <div class="footer-contact row footer-widget right-box">
                    <h3 class="footer-title">Kontak Kami</h3>
                    <ul class="nav">
                        <?php if (!empty($Pengaturan->telp)): ?>
                            <li class="tel">
                                <a href="tel:<?= esc($Pengaturan->telp) ?>">
                                    <i class="fa fa-phone"></i><?= esc($Pengaturan->telp) ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($Pengaturan->url)): ?>
                            <li class="email">
                                <a href="mailto:<?= esc($Pengaturan->url) ?>">
                                    <i class="fa fa-envelope-o"></i><?= esc($Pengaturan->url) ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a href="#" target="_blank">
                                <i class="fa fa-facebook"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" target="_blank">
                                <i class="fa fa-twitter"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" target="_blank">
                                <i class="fa fa-linkedin"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" target="_blank">
                                <i class="fa fa-youtube-play"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-5 col-md-pull-7 half-side">
                <div class="footer-about left-box footer-widget row">
                    <h3 class="footer-title">About Us</h3>
                    <p><?= !empty($Pengaturan->deskripsi) ? esc($Pengaturan->deskripsi) : 'Digital Agency adalah perusahaan yang bergerak di bidang layanan digital marketing, web development, dan solusi teknologi untuk membantu bisnis Anda berkembang di era digital.' ?></p>
                </div>
                <div class="copyright-row left-box footer-widget row">
                    &copy; Copyright <?= date('Y') ?> - <?= esc($Pengaturan->judul_app ?? $Pengaturan->judul ?? 'Digital Agency') ?>. All Rights Reserved.
                </div>
            </div>
        </div>
    </div>
</footer>
