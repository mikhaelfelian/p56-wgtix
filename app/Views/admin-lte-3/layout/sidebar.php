<aside class="main-sidebar sidebar-light-primary elevation-0">
    <!-- Brand Logo -->
    <a href="<?= base_url() ?>" class="brand-link">
        <img src="<?= $Pengaturan->logo ? base_url($Pengaturan->logo) : base_url('public/assets/theme/admin-lte-3/dist/img/AdminLTELogo.png') ?>"
            alt="AdminLTE Logo" class="brand-image img-circle elevation-0" style="opacity: .8">
        <span class="brand-text font-weight-light"><?= $Pengaturan ? $Pengaturan->judul_app : env('app.name') ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="<?php echo base_url((!empty($Pengaturan->logo) ? $Pengaturan->logo_header : 'public/assets/theme/admin-lte-3/dist/img/AdminLTELogo.png')); ?>"
                        class="brand-image img-rounded-0 elevation-0"
                        style="width: 209px; height: 85px; background-color: transparent;" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block"></a>
                </div>
            </div>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>"
                        class="nav-link <?= isMenuActive('dashboard') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Master Data -->
                <li class="nav-header">MASTER DATA</li>
                <!-- Ukuran -->
                <li class="nav-item">
                    <a href="<?= base_url('admin/ukuran') ?>"
                        class="nav-link <?= isMenuActive('admin/ukuran') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-ruler-combined"></i>
                        <p>Ukuran</p>
                    </a>
                </li>

                <!-- Jadwal -->
                <li class="nav-item">
                    <a href="<?= base_url('admin/jadwal') ?>"
                        class="nav-link <?= isMenuActive('admin/jadwal') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Jadwal</p>
                    </a>
                </li>

                <!-- Racepack -->
                <li
                    class="nav-item has-treeview <?= isMenuActive(['admin/racepack', 'admin/kategori-racepack', 'admin/stock-racepack']) ? 'menu-open' : '' ?>">
                    <a href="#"
                        class="nav-link <?= isMenuActive(['admin/racepack', 'admin/kategori-racepack', 'admin/stock-racepack']) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tshirt"></i>
                        <p>
                            Racepack
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('admin/racepack') ?>"
                                class="nav-link <?= isMenuActive('admin/racepack') ? 'active' : '' ?>">
                                <?= nbs(3) ?>
                                <i class="fas fa-tshirt nav-icon"></i>
                                <p>Data Racepack</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/kategori-racepack') ?>"
                                class="nav-link <?= isMenuActive('admin/kategori-racepack') ? 'active' : '' ?>">
                                <?= nbs(3) ?>
                                <i class="fas fa-tags nav-icon"></i>
                                <p>Kategori Racepack</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/stock-racepack') ?>"
                                class="nav-link <?= isMenuActive('admin/stock-racepack') ? 'active' : '' ?>">
                                <?= nbs(3) ?>
                                <i class="fas fa-boxes nav-icon"></i>
                                <p>Stock Racepack</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">EVENTS</li>

                <!-- Events -->
                <li
                    class="nav-item has-treeview <?= isMenuActive(['admin/events', 'admin/event-pricing', 'admin/event-gallery', 'admin/kategori']) ? 'menu-open' : '' ?>">
                    <a href="#"
                        class="nav-link <?= isMenuActive(['admin/events', 'admin/event-pricing', 'admin/event-gallery', 'admin/kategori']) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>
                            Event
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- Kategori -->
                        <li class="nav-item">
                            <a href="<?= base_url('admin/kategori') ?>"
                                class="nav-link <?= isMenuActive('admin/kategori') ? 'active' : '' ?>">
                                <?= nbs(3) ?>
                                <i class="nav-icon fas fa-list"></i>
                                <p>Kategori</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/events') ?>"
                                class="nav-link <?= isMenuActive('admin/events') ? 'active' : '' ?>">
                                <?= nbs(3) ?>
                                <i class="fas fa-calendar-alt nav-icon"></i>
                                <p>Data Event</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/event-pricing') ?>"
                                class="nav-link <?= isMenuActive('admin/event-pricing') ? 'active' : '' ?>">
                                <?= nbs(3) ?>
                                <i class="fas fa-tags nav-icon"></i>
                                <p>Harga Event</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/event-gallery') ?>"
                                class="nav-link <?= isMenuActive('admin/event-gallery') ? 'active' : '' ?>">
                                <?= nbs(3) ?>
                                <i class="fas fa-images nav-icon"></i>
                                <p>Galeri Event</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">PESERTA</li>

                <!-- Peserta -->
                <li
                    class="nav-item has-treeview <?= isMenuActive(['admin/peserta/daftar', 'admin/peserta/kelompok', 'admin/peserta/pendaftaran']) ? 'menu-open' : '' ?>">
                    <a href="#"
                        class="nav-link <?= isMenuActive(['admin/peserta/daftar', 'admin/peserta/kelompok', 'admin/peserta/pendaftaran']) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-check"></i>
                        <p>
                            Peserta
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('admin/peserta/daftar') ?>"
                                class="nav-link <?= isMenuActive('admin/peserta/daftar') ? 'active' : '' ?>">
                                <?= nbs(3) ?>
                                <i class="fas fa-users nav-icon"></i>
                                <p>Data Peserta</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/peserta/kelompok') ?>"
                                class="nav-link <?= isMenuActive('admin/peserta/kelompok') ? 'active' : '' ?>">
                                <?= nbs(3) ?>
                                <i class="fas fa-layer-group nav-icon"></i>
                                <p>Kelompok Peserta</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/peserta/pendaftaran') ?>"
                                class="nav-link <?= isMenuActive('admin/peserta/pendaftaran') ? 'active' : '' ?>">
                                <?= nbs(3) ?>
                                <i class="fas fa-user-plus nav-icon"></i>
                                <p>Pendaftaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/peserta/rekap') ?>"
                                class="nav-link <?= isMenuActive('admin/peserta/rekap') ? 'active' : '' ?>">
                                <?= nbs(3) ?>
                                <i class="fas fa-file-alt nav-icon"></i>
                                <p>Rekap Peserta</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Payment -->
                <li
                    class="nav-item has-treeview <?= isMenuActive(['master/platform', 'master/bank']) ? 'menu-open' : '' ?>">
                    <a href="#"
                        class="nav-link <?= isMenuActive(['master/platform', 'master/bank']) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>
                            Pembayaran
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('admin/master/platform') ?>"
                                class="nav-link <?= isMenuActive('admin/master/platform') ? 'active' : '' ?>">
                                <?= nbs(3) ?>
                                <i class="fas fa-credit-card nav-icon"></i>
                                <p>Metode Bayar</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Settings -->
                <li class="nav-header">PENGATURAN</li>
                <li class="nav-item has-treeview <?= isMenuActive('pengaturan') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= isMenuActive('pengaturan') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Pengaturan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('pengaturan/app') ?>"
                                class="nav-link <?= isMenuActive('pengaturan/app') ? 'active' : '' ?>">
                                <?= nbs(3) ?>
                                <i class="fas fa-cogs nav-icon"></i>
                                <p>Aplikasi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('pengaturan/api-tokens') ?>"
                                class="nav-link <?= isMenuActive('pengaturan/api-tokens') ? 'active' : '' ?>">
                                <?= nbs(3) ?>
                                <i class="fas fa-key nav-icon"></i>
                                <p>API Tokens</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>