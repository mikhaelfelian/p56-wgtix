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
<!--Page Cover-->
<section class="row page-cover"
    data-bgimage="<?php echo base_url('assets/theme/da-theme/images/page-cover/2.jpg') ?>">
    <div class="row m0">
        <div class="container">
            <h2 class="page-title"></h2>
        </div>
    </div>
</section>

<!--Shop Content-->
<section class="row">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-md-push-9 shop-sidebar">
                <?= $this->include('da-theme/event/sidebar') ?>
            </div>
            <div class="col-md-9 col-md-pull-3 shop-content">
                <div class="row m0">
                    <div class="media product product-details">
                        <div class="media-left">
                            <div class="img-holder">
                                <?php
                                if (!empty($event->foto)):
                                    $foto = base_url('file/events/' . $event->id . '/' . $event->foto);
                                else:
                                    $foto = base_url('assets/theme/da-theme/images/Shop/2.jpg');
                                endif;
                                ?>
                                <img src="<?= $foto ?>" width="360" height="339" alt="" alt="" class="product-img">
                                <?php if ($event->status == 1): ?>
                                    <div class="sale-new-tag">Tersedia</div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="media-body">
                            <h3 class="pro-title"><?= $event->event ?></h3>
                            <!-- <h3 class="price"><del>$80.00</del>$65.00</h3> -->
                            <p class="pro-about">
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
                                <tr>
                                    <th><small>Tanggal Mulai</small></th>
                                    <td style="width:5px; text-align:center;"><small>:</small></td>
                                    <td><small><?= isset($event->tgl_masuk) ? tgl_indo5($event->tgl_masuk . ' ' . $event->wkt_masuk) : '-' ?></small>
                                    </td>
                                </tr>
                                <tr>
                                    <th><small>Tanggal Selesai</small></th>
                                    <td style="width:5px; text-align:center;"><small>:</small></td>
                                    <td><small><?= isset($event->tgl_keluar) ? tgl_indo5($event->tgl_keluar) : '-' ?></small>
                                    </td>
                                </tr>
                            </table>
                            </p>
                            <h5 class="pro-cats">Kategori : <a href="#"><?= $event->kategori ?></a></h5>
                        </div>
                    </div>
                    <!-- Event Gallery Slider -->
                    <?php if (!empty($eventGallery) && is_array($eventGallery)): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="event-gallery-section">
                                    <h4><i class="fa fa-camera"></i> Event Gallery</h4>
                                    
                                    <!-- Main Gallery Slider -->
                                    <div class="gallery-slider-wrapper">
                                        <div class="owl-carousel owl-theme gallery-slider" id="eventGallerySlider">
                                            <?php foreach ($eventGallery as $gallery): ?>
                                                <div class="item">
                                                    <div class="gallery-item">
                                                        <img src="<?= base_url('public/file/events/' . $gallery['id_event'] . '/gallery/' . $gallery['file']) ?>" 
                                                             alt="<?= esc($gallery['deskripsi'] ?? 'Event Gallery') ?>" 
                                                             class="img-responsive gallery-image"
                                                             style="width: 100%; height: 300px; object-fit: cover; border-radius: 8px;">
                                                        <?php if (!empty($gallery['deskripsi'])): ?>
                                                            <div class="gallery-caption">
                                                                <p><?= esc($gallery['deskripsi']) ?></p>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if ($gallery['is_cover'] == 1): ?>
                                                            <div class="gallery-badge">
                                                                <span class="badge badge-primary">Cover</span>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        
                                        <!-- Gallery Thumbnails -->
                                        <div class="gallery-thumbnails mt-3">
                                            <div class="owl-carousel owl-theme gallery-thumbs" id="eventGalleryThumbs">
                                                <?php foreach ($eventGallery as $index => $gallery): ?>
                                                    <div class="item">
                                                        <div class="thumb-item <?= $index === 0 ? 'active' : '' ?>" data-slide="<?= $index ?>">
                                                            <img src="<?= base_url('public/file/events/' . $gallery['id_event'] . '/gallery/' . $gallery['file']) ?>" 
                                                                 alt="Thumbnail <?= $index + 1 ?>" 
                                                                 class="img-responsive thumb-image"
                                                                 style="width: 80px; height: 60px; object-fit: cover; border-radius: 4px; cursor: pointer;">
                                                            <?php if ($gallery['is_cover'] == 1): ?>
                                                                <div class="thumb-badge">
                                                                    <i class="fa fa-star text-warning"></i>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-lg-12">
                        <div class="row m0 quantity-cart">
                            <div class="col-md-12">
                                <table class="table table-responsive mb-0">
                                    <tbody>
                                        <?php if (!empty($event_price) && is_array($event_price)): ?>
                                            <?php foreach ($event_price as $price): ?>
                                                <tr>
                                                    <td style="vertical-align: middle;"><?= esc($price->keterangan) ?></td>
                                                    <td style="text-align:right; font-weight: bold; vertical-align: middle;">
                                                        Rp <?= format_angka($price->harga) ?>
                                                    </td>
                                                    <td style="text-align: center; vertical-align: middle;">
                                                        <?php if (isset($user_level) && $user_level && $user_level->name == 'user'): ?>
                                                            <input type="number" class="form-control quantity text-center" name="quantity_<?= $price->id ?>" value="1" min="1" style="width:80px; margin: 0 auto;">
                                                        <?php endif; ?>
                                                    </td>
                                                    <td style="text-align: center; vertical-align: middle;">
                                                        <?php if (isset($user_level) && $user_level && $user_level->name == 'user'): ?>
                                                            <button class="btn btn-primary btn-sm add-to-cart-btn"
                                                                data-event-id="<?= $price->id_event ?>"
                                                                data-price-id="<?= $price->id ?>"
                                                                data-price="<?= $price->harga ?>"
                                                                data-description="<?= esc($price->keterangan) ?>"
                                                            >
                                                                <i class="fa fa-shopping-cart"></i> <span class="btn-text">Beli</span>
                                                            </button>
                                                        <?php else: ?>
                                                            <a href="<?= base_url('auth/login?return_url=' . urlencode(current_url())) ?>" class="btn btn-warning btn-sm">
                                                                <i class="fa fa-sign-in"></i> Login untuk membeli
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" style="text-align: center; color: #666; vertical-align: middle;">
                                                    <i class="fa fa-info-circle"></i> Harga tidak tersedia
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m0 shop-tabs">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#description" aria-controls="description"
                                role="tab" data-toggle="tab">Deskripsi</a></li>
                        <li role="presentation"><a href="#reviews" aria-controls="reviews" role="tab"
                                data-toggle="tab">Review</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="description">
                            <?= $event->keterangan ?>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="reviews">...</div>
                    </div>
                </div>
                <div class="row m0">
                    <div class="col-md-12">
                        <!-- Google Map Section -->
                        <?php if (!empty($event->latitude) && !empty($event->longitude)): ?>
                            <div class="event-map mt-4">
                                <h4><i class="fa fa-map-marker"></i> Event Location</h4>
                                <div id="eventMap" style="height: 400px; width: 100%; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);"></div>
                                <div class="map-info mt-2">
                                    <p class="text-muted">
                                        <i class="fa fa-map-marker text-danger"></i> 
                                        <?= !empty($event->lokasi) ? esc($event->lokasi) : 'Event Location' ?>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
(function() {
    // Google Map Section
    <?php if (!empty($event->latitude) && !empty($event->longitude)): ?>
    // Expose initEventMap globally to fix "initEventMap is not a function" error
    window.initEventMap = function() {
        var eventLocation = {
        lat: parseFloat(<?= $event->latitude ?>),
        lng: parseFloat(<?= $event->longitude ?>)
    };

        var map = new google.maps.Map(document.getElementById("eventMap"), {
        zoom: 15,
        center: eventLocation,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: [
            {
                featureType: "poi",
                elementType: "labels",
                stylers: [{ visibility: "off" }]
            }
        ]
    });

        var marker = new google.maps.Marker({
        position: eventLocation,
        map: map,
        title: "<?= esc($event->event ?? 'Event Location') ?>",
        icon: {
            url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
            scaledSize: new google.maps.Size(40, 40)
        }
    });

        var infoWindowContent = `
            <div style="padding: 10px; max-width: 250px;">
                <h6 style="margin: 0 0 5px 0; color: #333;">
                    <i class="fa fa-calendar" style="color: #007bff;"></i> 
                    <?= esc($event->event ?? 'Event') ?>
                </h6>
                <?php if (!empty($event->lokasi)): ?>
                    <p style="margin: 0; color: #666; font-size: 13px;">
                        <i class="fa fa-map-marker" style="color: #dc3545;"></i> 
                        <?= esc($event->lokasi) ?>
                    </p>
                <?php endif; ?>
                <?php if (!empty($event->tgl_masuk)): ?>
                    <p style="margin: 5px 0 0 0; color: #666; font-size: 13px;">
                        <i class="fa fa-clock-o" style="color: #28a745;"></i> 
                        <?= date('d M Y', strtotime($event->tgl_masuk)) ?>
                        <?php if (!empty($event->wkt_masuk)): ?>
                            at <?= date('H:i', strtotime($event->wkt_masuk)) ?>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            </div>
        `;

        var infoWindow = new google.maps.InfoWindow({
            content: infoWindowContent
    });

        marker.addListener("click", function() {
        infoWindow.open(map, marker);
    });

    infoWindow.open(map, marker);
    };

    // Use best-practice async loading for Google Maps JS API
function loadGoogleMaps() {
        // Only load if not already loaded
        if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
            // Remove any previous script with same src to avoid duplicates
            var existingScript = document.querySelector('script[data-gmaps="event"]');
            if (existingScript) existingScript.remove();

            var script = document.createElement('script');
            script.src = 'https://maps.googleapis.com/maps/api/js?key=<?= getenv('GOOGLE_MAPS_API') ?>&callback=initEventMap&loading=async';
        script.async = true;
        script.defer = true;
            script.setAttribute('data-gmaps', 'event');
        document.head.appendChild(script);
    } else {
            // If already loaded, just call the function
            window.initEventMap();
    }
}
    <?php endif; ?>

document.addEventListener('DOMContentLoaded', function() {
        // Google Map
        <?php if (!empty($event->latitude) && !empty($event->longitude)): ?>
    if (document.getElementById('eventMap')) {
        loadGoogleMaps();
    }
        <?php endif; ?>
    
        // Gallery Slider
    <?php if (!empty($eventGallery) && is_array($eventGallery)): ?>
        // Main gallery slider
    $('#eventGallerySlider').owlCarousel({
        items: 1,
        loop: true,
        margin: 10,
        nav: true,
        dots: false,
        autoplay: true,
        autoplayTimeout: 5000,
        navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>'],
        responsive: {
            0: { items: 1 },
            768: { items: 1 },
            1000: { items: 1 }
        }
    });

        // Thumbnail slider
    $('#eventGalleryThumbs').owlCarousel({
        items: 6,
        loop: false,
        margin: 10,
        nav: false,
        dots: false,
        responsive: {
            0: { items: 3 },
            480: { items: 4 },
            768: { items: 5 },
            1000: { items: 6 }
        }
    });

        // Thumbnail click
    $('.thumb-item').on('click', function() {
        var slideIndex = $(this).data('slide');
        $('#eventGallerySlider').trigger('to.owl.carousel', [slideIndex, 300]);
        $('.thumb-item').removeClass('active');
        $(this).addClass('active');
    });

    // Sync main slider with thumbnails
    $('#eventGallerySlider').on('changed.owl.carousel', function(event) {
        var current = event.item.index;
        $('.thumb-item').removeClass('active');
        $('.thumb-item').eq(current).addClass('active');
    });
    <?php endif; ?>

        // CSRF hash
        window.csrf_hash = '<?= csrf_hash() ?>';

        // Add to cart
        $('.add-to-cart-btn').on('click', function() {
            var btn = $(this);
            var eventId = btn.data('event-id');
            var priceId = btn.data('price-id');
            var price = btn.data('price');
            var description = btn.data('description');
            var quantity = $('input[name="quantity_' + priceId + '"]').val() || 1;

            btn.prop('disabled', true);
            btn.find('.btn-text').text('Adding...');
            btn.find('i').removeClass('fa-shopping-cart').addClass('fa-spinner fa-spin');

            var csrfData = {};
            var csrfToken = '<?= csrf_token() ?>';
            var csrfHash = window.csrf_hash || '<?= csrf_hash() ?>';
            csrfData[csrfToken] = csrfHash;

            $.ajax({
                url: '<?= base_url('cart/add') ?>',
                type: 'POST',
                dataType: 'json',
                data: $.extend({
                    event_id: eventId,
                    price_id: priceId,
                    quantity: quantity,
                    price: price,
                    event_title: '<?= esc($event->event ?? 'Event') ?>',
                    event_image: '<?= $event->foto ?? '' ?>',
                    price_description: description,
                    event_date: '<?= $event->tgl_masuk ?? '' ?>',
                    event_location: '<?= $event->lokasi ?? 'TBA' ?>'
                }, csrfData),
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        updateCartCounter();
                        if (response.csrf_hash) {
                            $('input[name="<?= csrf_token() ?>"]').val(response.csrf_hash);
                            window.csrf_hash = response.csrf_hash;
                        }
                        btn.find('.btn-text').text('Added!');
                        btn.removeClass('btn-primary').addClass('btn-success');
                        setTimeout(function() {
                            btn.find('.btn-text').text('Beli');
                            btn.removeClass('btn-success').addClass('btn-primary');
                            btn.prop('disabled', false);
                            btn.find('i').removeClass('fa-spinner fa-spin').addClass('fa-shopping-cart');
                        }, 2000);
                    } else {
                        toastr.error(response.message || 'Failed to add item to cart');
                        resetButton();
                    }
                },
                error: function(xhr) {
                    var errorMsg = 'An error occurred while adding item to cart';
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.message) errorMsg = response.message;
                        if (response.debug) console.log('Debug info:', response.debug);
                    } catch(e) {
                        console.log('Could not parse error response');
                    }
                    toastr.error(errorMsg);
                    resetButton();
                }
            });

            function resetButton() {
                btn.prop('disabled', false);
                btn.find('.btn-text').text('Beli');
                btn.find('i').removeClass('fa-spinner fa-spin').addClass('fa-shopping-cart');
            }
        });

        // Update cart counter
        function updateCartCounter() {
            $.ajax({
                url: '<?= base_url('cart/getCount') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('.cart-counter').text(response.count);
                        if (response.count > 0) {
                            $('.cart-counter').show();
                        } else {
                            $('.cart-counter').hide();
                        }
                    }
                }
            });
        }

        updateCartCounter();
    });
})();
</script>

<style>
/* Event Gallery Styles */
.event-gallery-section {
    margin-bottom: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.event-gallery-section h4 {
    color: #333;
    margin-bottom: 20px;
    font-weight: 600;
    border-bottom: 2px solid #007bff;
    padding-bottom: 10px;
}
.gallery-slider-wrapper {
    position: relative;
}
.gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.gallery-image {
    transition: transform 0.3s ease;
}
.gallery-item:hover .gallery-image {
    transform: scale(1.05);
}
.gallery-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    color: white;
    padding: 20px 15px 15px;
    transform: translateY(100%);
    transition: transform 0.3s ease;
}
.gallery-item:hover .gallery-caption {
    transform: translateY(0);
}
.gallery-caption p {
    margin: 0;
    font-size: 14px;
    line-height: 1.4;
}
.gallery-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 10;
}
.gallery-badge .badge {
    background: #007bff;
    color: white;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 11px;
}
.gallery-thumbnails {
    margin-top: 15px;
}
.thumb-item {
    position: relative;
    border: 2px solid transparent;
    border-radius: 6px;
    overflow: hidden;
    transition: all 0.3s ease;
}
.thumb-item:hover,
.thumb-item.active {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0,123,255,0.3);
}
.thumb-image {
    transition: opacity 0.3s ease;
}
.thumb-item:hover .thumb-image {
    opacity: 0.8;
}
.thumb-badge {
    position: absolute;
    top: 2px;
    right: 2px;
    background: rgba(0,0,0,0.7);
    border-radius: 50%;
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.thumb-badge i {
    font-size: 8px;
}
/* Owl Carousel Custom Navigation */
.gallery-slider .owl-nav {
    position: absolute;
    top: 50%;
    width: 100%;
    transform: translateY(-50%);
}
.gallery-slider .owl-nav button {
    position: absolute;
    background: rgba(0,0,0,0.5) !important;
    color: white !important;
    border: none !important;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    font-size: 16px;
    transition: all 0.3s ease;
}
.gallery-slider .owl-nav button:hover {
    background: rgba(0,123,255,0.8) !important;
    transform: scale(1.1);
}
.gallery-slider .owl-nav .owl-prev {
    left: 10px;
}
.gallery-slider .owl-nav .owl-next {
    right: 10px;
}
/* Responsive adjustments */
@media (max-width: 768px) {
    .event-gallery-section {
        padding: 15px;
    }
    .gallery-image {
        height: 200px !important;
    }
    .thumb-image {
        width: 60px !important;
        height: 45px !important;
    }
}
</style>

<?php
echo $this->endSection();
?>