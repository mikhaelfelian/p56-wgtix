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
                <img src="<?= base_url($Pengaturan->logo_header) ?>" alt="<?= esc($Pengaturan->judul_app ?? $Pengaturan->judul ?? 'Digital Agency') ?>">
            </a>
        </div>
        <div class="collapse navbar-collapse" id="main_nav">
            <?php if (isset($user_level) && $user_level && isset($user_level->name) && $user_level->name == 'user'): ?>
                <!-- User dropdown when logged in -->
                <ul class="nav navbar-nav navbar-right">
                    <li class="<?= current_url() == base_url() ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>">Home</a>
                    </li>
                    <li class="<?= strpos(current_url(), 'event') !== false ? 'active' : '' ?>">
                        <a href="<?= base_url('events') ?>">Event</a>
                    </li>
                    <li class="<?= strpos(current_url(), 'cart') !== false ? 'active' : '' ?>">
                        <a href="<?= base_url('cart') ?>" class="cart-link" style="position: relative;">
                            <i class="fa fa-shopping-cart"></i> Cart
                            <span class="cart-counter badge" style="position: absolute; top: -8px; right: -8px; background: #ff6b6b; color: white; border-radius: 50%; min-width: 18px; height: 18px; font-size: 11px; line-height: 18px; text-align: center; display: none;">0</span>
                        </a>
                    </li>
                    <li class="<?= strpos(current_url(), 'sale/order') !== false ? 'active' : '' ?>">
                        <a href="<?= base_url('sale/orders') ?>">
                            <i class="fa fa-list"></i> My Orders
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user"></i> <?= esc($user->username) ?> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-header">
                                <strong><?= esc($user->first_name . ' ' . $user->last_name) ?></strong>
                                <br>
                                <small>Member since <?= date('d-m-Y', $user->created_on) ?></small>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li><a href="<?= base_url('sale/orders') ?>"><i class="fa fa-list"></i> My Orders</a></li>
                            <li><a href="<?= base_url('cart') ?>"><i class="fa fa-shopping-cart"></i> Cart</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="<?= base_url('auth/logout') ?>"><i class="fa fa-sign-out"></i> Sign out</a></li>
                        </ul>
                    </li>
                </ul>
            <?php else: ?>
                <!-- Login button when not logged in -->
                <a href="<?= base_url('auth/login') ?>" class="btn btn-outline blue pull-right hidden-xs hidden-sm">Masuk</a>
                <ul class="nav navbar-nav navbar-right">
                    <li class="<?= current_url() == base_url() ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>">Home</a>
                    </li>
                    <li class="<?= strpos(current_url(), 'event') !== false ? 'active' : '' ?>">
                        <a href="<?= base_url('events') ?>">Event</a>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>
