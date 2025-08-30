<?php
/**
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-08-29
 * Github : github.com/mikhaelfelian
 * description : Navigation bar template for Digital Agency theme
 * This file represents the navigation bar template for the Digital Agency theme.
 */
?>

<!--Navigation-->
<nav class="navbar navbar-default navbar-static-top style2">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main_nav"
                aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="<?= base_url() ?>" class="navbar-brand">
                <img src="<?= base_url('/public/assets/theme/da-theme/images/logo2.png') ?>" alt="<?= esc($Pengaturan->judul_app ?? $Pengaturan->judul ?? 'Digital Agency') ?>">
            </a>
        </div>
        <div class="collapse navbar-collapse" id="main_nav">
            <a href="<?= base_url('auth/login') ?>" class="btn btn-outline blue pull-right hidden-xs hidden-sm">Sign In</a>
            <ul class="nav navbar-nav navbar-right">
                <li class="<?= current_url() == base_url() ? 'active' : '' ?>">
                    <a href="<?= base_url() ?>">Home</a>
                </li>
                <li class="<?= strpos(current_url(), 'event') !== false ? 'active' : '' ?>">
                    <a href="<?= base_url('event') ?>">Event</a>
                </li>
                <li class="<?= strpos(current_url(), 'berita') !== false ? 'active' : '' ?>">
                    <a href="<?= base_url('berita') ?>">Berita</a>
                </li>
                <li class="<?= strpos(current_url(), 'about') !== false ? 'active' : '' ?>">
                    <a href="<?= base_url('about') ?>">About</a>
                </li>
                <li class="<?= strpos(current_url(), 'contact') !== false ? 'active' : '' ?>">
                    <a href="<?= base_url('contact') ?>">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
