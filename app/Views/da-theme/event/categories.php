<?php
/**
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-08-29
 * Github : github.com/mikhaelfelian
 * description : Home page template for Digital Agency theme
 * This file represents the home page template for the Digital Agency theme.
 */

echo $this->extend('da-theme/layout/main');
echo $this->section('content');
?>
<!-- Events Categories Content -->
<section class="row">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-md-push-9 shop-sidebar">
                <?= $this->include('da-theme/event/sidebar') ?>
            </div>
            <div class="col-md-9 col-md-pull-3 shop-content">
                <div class="row">
                    <?php if (isset($events) && is_array($events)): ?>
                        <?php foreach ($events as $event): ?>
                            <div class="col-sm-4 product">
                                <div class="img-holder row">
                                    <?php
                                    if (!empty($event->foto)):
                                        $foto = base_url('/public/file/events/' . $event->id . '/' . $event->foto);
                                    else:
                                        $foto = base_url('/public/assets/theme/da-theme/images/Shop/2.jpg');
                                    endif;
                                    ?>
                                    <img src="<?= $foto ?>" width="263" height="299" alt="" class="product-img">
                                    <?php if ($event->status == 1): ?>
                                        <div class="sale-new-tag">Tersedia</div>
                                    <?php endif; ?>
                                    <div class="hover-box">
                                        <div class="btn-holder">
                                            <div class="row m0"><a
                                                    href="<?= base_url('events/' . $event->id . '-' . generateSlug($event->event)) ?>"
                                                    class="btn btn-outline blue">Detail</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="<?= base_url('events/' . $event->id . '-' . generateSlug($event->event)) ?>">
                                    <h4 class="pro-title"><?= $event->event ?></h4>
                                </a>
                                <p class="pro-about">
                                    <?= shortDescription($event->keterangan, 40) ?>
                                </p>
                                <table class="table table-sm mb-2">
                                    <tr>
                                        <th><small>Kategori</small></th>
                                        <td style="width:5px; text-align:center;"><small>:</small></td>
                                        <td><small><?= isset($event->kategori) ? esc($event->kategori) : '-' ?></small></td>
                                    </tr>
                                    <tr>
                                        <th><small>Kapasitas</small></th>
                                        <td style="width:5px; text-align:center;"><small>:</small></td>
                                        <td><small><?= isset($event->jml) ? format_angka($event->jml) : '-' ?></small></td>
                                    </tr>
                                    <tr>
                                        <th><small>Lokasi</small></th>
                                        <td style="width:5px; text-align:center;"><small>:</small></td>
                                        <td><small><?= isset($event->lokasi) ? esc($event->lokasi) : '-' ?></small></td>
                                    </tr>
                                </table>
                                <button class="btn btn-secondary btn-sm mt-2"
                                    onclick="window.location.href='<?= base_url('events/' . $event->id . '-' . generateSlug($event->event)) ?>'">Selanjutnya</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <?php if (isset($pager) && $pager): ?>
                    <div class="pagination-wrapper">
                        <?= $pager->links('events', 'datheme_pagination') ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php
echo $this->endSection();
?>