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
                                                                data-description="<?= esc($price->keterangan) ?>">
                                                                <i class="fa fa-shopping-cart"></i> <span class="btn-text">Beli</span>
                                                            </button>
                                                        <?php else: ?>
                                                            <a href="<?= base_url('auth/register?return_url=' . urlencode(current_url())) ?>" class="btn btn-warning btn-sm">
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
                    styles: [{
                        featureType: "poi",
                        elementType: "labels",
                        stylers: [{
                            visibility: "off"
                        }]
                    }]
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
                        0: {
                            items: 1
                        },
                        768: {
                            items: 1
                        },
                        1000: {
                            items: 1
                        }
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
                        0: {
                            items: 3
                        },
                        480: {
                            items: 4
                        },
                        768: {
                            items: 5
                        },
                        1000: {
                            items: 6
                        }
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

            // Participant Names Modal functionality
            let currentPurchaseData = {};

            // Handle buy button click - show modal
            $('.add-to-cart-btn').on('click', function(e) {
                e.preventDefault();

                const eventId = $(this).data('event-id');
                const priceId = $(this).data('price-id');
                const price = $(this).data('price');
                const description = $(this).data('description');
                const quantity = $(this).closest('tr').find('.quantity').val() || 1;

                // Store current purchase data
                currentPurchaseData = {
                    eventId: eventId,
                    priceId: priceId,
                    price: price,
                    description: description,
                    quantity: parseInt(quantity)
                };

                // Generate participant name fields
                generateParticipantFields(quantity);

                // Show modal
                $('#participantNamesModal').modal('show');
            });

            // Generate participant name fields based on quantity
            function generateParticipantFields(quantity) {
                const fieldsContainer = $('#participantFields');
                fieldsContainer.empty();

                for (let i = 1; i <= quantity; i++) {
                    // Prepare dropdown options for kategori and kelompok
                    let kategoriOptions = `<option value="">Pilih Kategori</option>`;
                    <?php if (!empty($kategori_list)): ?>
                        <?php foreach ($kategori_list as $kategori): ?>
                            kategoriOptions += `<option value="<?= esc($kategori->id) ?>"><?= esc($kategori->nama) ?></option>`;
                        <?php endforeach; ?>
                    <?php endif; ?>

                    let kelompokOptions = `<option value="">Pilih Kelompok</option>`;
                    <?php if (!empty($kelompok_list)): ?>
                        <?php foreach ($kelompok_list as $kelompok): ?>
                            kelompokOptions += `<option value="<?= esc($kelompok->id) ?>"><?= esc($kelompok->nama) ?></option>`;
                        <?php endforeach; ?>
                    <?php endif; ?>

                    let ukuranOptions = `<option value="">Pilih Ukuran</option>`;
                    <?php if (!empty($ukuranOptions)): ?>
                        <?php foreach ($ukuranOptions as $id => $label): ?>
                            ukuranOptions += `<option value="<?= esc($id) ?>"><?= esc($label) ?></option>`;
                        <?php endforeach; ?>
                    <?php endif; ?>


                    const fieldHtml = `
                    <div class="participant-row mb-3 p-3 border rounded" style="background-color: #f8f9fa;">
                        <h6 class="mb-3 text-primary">
                            <i class="fa fa-user"></i> Peserta ${i}
                        </h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-2">
                                    <label for="participant_${i}" class="form-label small">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="participant_${i}" name="participant_${i}" 
                                           placeholder="Nama lengkap peserta ${i}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-2">
                                    <label for="gender_${i}" class="form-label small">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm" id="gender_${i}" name="gender_${i}" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-2">
                                    <label for="birthdate_${i}" class="form-label small">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control form-control-sm" id="birthdate_${i}" name="birthdate_${i}" 
                                           required max="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-2">
                                    <label for="no_hp_${i}" class="form-label small">No. HP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="no_hp_${i}" name="no_hp_${i}" 
                                           placeholder="085741220427" maxlength="15" pattern="^08[0-9]{8,13}$" required
                                           title="Masukkan nomor HP yang valid, contoh: 085741220427">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-2">
                                    <label for="alamat_${i}" class="form-label small">Alamat <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="alamat_${i}" name="alamat_${i}" 
                                           placeholder="Alamat peserta ${i}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <div class="form-group mb-2">
                                    <label for="ukuran_${i}" class="form-label small">Ukuran Jersey <span class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm" id="ukuran_${i}" name="ukuran_${i}" required>
                                            ${ukuranOptions}
                                        </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-2">
                                    <label for="emg_${i}" class="form-label small">Kontak Darurat <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="emg_${i}" name="emg_${i}" 
                                           placeholder="08xxxxxxxxxx" maxlength="15" pattern="^08[0-9]{8,13}$" required
                                           title="Masukkan nomor darurat yang valid, contoh: 081234567890">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2" id="ktp_upload_row_${i}" style="display: none;">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="ktp_file_${i}" class="form-label small">Upload KTP <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control form-control-sm" id="ktp_file_${i}" name="ktp_file_${i}" 
                                           accept="image/*,.pdf" data-participant-index="${i}">
                                    <small class="text-muted">Format: JPG, PNG, PDF (Max 5MB). Wajib untuk peserta usia 40 tahun ke atas.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                    fieldsContainer.append(fieldHtml);
                    
                    // Add event listener for birthdate change to calculate age and show/hide KTP upload
                    $(document).on('change', `#birthdate_${i}`, function() {
                        const birthdate = $(this).val();
                        if (birthdate) {
                            const age = calculateAge(birthdate);
                            const ktpRow = $(`#ktp_upload_row_${i}`);
                            const ktpInput = $(`#ktp_file_${i}`);
                            
                            if (age >= 40) {
                                ktpRow.show();
                                ktpInput.prop('required', true);
                            } else {
                                ktpRow.hide();
                                ktpInput.prop('required', false);
                                ktpInput.val(''); // Clear file if age < 40
                            }
                        }
                    });
                }

                // Add event listener for single payment method change
                $('#payment_method').on('change', function() {
                    const selectedOption = $(this).find('option:selected');
                    const fee = selectedOption.data('fee') || 'Pilih metode pembayaran';
                    $('#payment_fee').text(fee);
                });
            }

            // Function to calculate age from birthdate
            function calculateAge(birthdate) {
                const today = new Date();
                const birth = new Date(birthdate);
                let age = today.getFullYear() - birth.getFullYear();
                const monthDiff = today.getMonth() - birth.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                    age--;
                }
                return age;
            }

            // Handle confirm purchase
            $('#confirmPurchase').on('click', function() {
                // Validate all fields
                let isValid = true;
                const participantData = [];

                // Get single payment method
                const paymentMethod = $('#payment_method').val();

                // Validate payment method
                if (!paymentMethod) {
                    isValid = false;
                    $('#payment_method').addClass('is-invalid');
                } else {
                    $('#payment_method').removeClass('is-invalid');
                }

                for (let i = 1; i <= currentPurchaseData.quantity; i++) {
                    const name = $(`#participant_${i}`).val().trim();
                    const gender = $(`#gender_${i}`).val();
                    const birthdate = $(`#birthdate_${i}`).val();
                    const phone = $(`#no_hp_${i}`).val().trim();
                    const address = $(`#alamat_${i}`).val().trim();
                    const jersey = $(`#ukuran_${i}`).val();
                    const emergency = $(`#emg_${i}`).val().trim();
                    const ktpFile = $(`#ktp_file_${i}`)[0].files[0];

                    // Validate name
                    if (!name) {
                        isValid = false;
                        $(`#participant_${i}`).addClass('is-invalid');
                    } else {
                        $(`#participant_${i}`).removeClass('is-invalid');
                    }

                    // Validate gender
                    if (!gender) {
                        isValid = false;
                        $(`#gender_${i}`).addClass('is-invalid');
                    } else {
                        $(`#gender_${i}`).removeClass('is-invalid');
                    }

                    // Validate birthdate
                    if (!birthdate) {
                        isValid = false;
                        $(`#birthdate_${i}`).addClass('is-invalid');
                    } else {
                        $(`#birthdate_${i}`).removeClass('is-invalid');
                        
                        // Check age and validate KTP if needed
                        const age = calculateAge(birthdate);
                        if (age >= 40) {
                            if (!ktpFile) {
                                isValid = false;
                                $(`#ktp_file_${i}`).addClass('is-invalid');
                                alert(`Peserta ${i} berusia ${age} tahun. Upload KTP wajib untuk peserta usia 40 tahun ke atas.`);
                            } else {
                                // Validate file type
                                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                                const fileType = ktpFile.type;
                                const fileName = ktpFile.name.toLowerCase();
                                const isValidType = allowedTypes.includes(fileType) || 
                                                    fileName.endsWith('.jpg') || 
                                                    fileName.endsWith('.jpeg') || 
                                                    fileName.endsWith('.png') || 
                                                    fileName.endsWith('.pdf');
                                
                                // Validate file size (5MB)
                                const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                                if (!isValidType) {
                                    isValid = false;
                                    $(`#ktp_file_${i}`).addClass('is-invalid');
                                    alert(`File KTP peserta ${i} tidak valid. Hanya format JPG, PNG, atau PDF yang diizinkan.`);
                                } else if (ktpFile.size > maxSize) {
                                    isValid = false;
                                    $(`#ktp_file_${i}`).addClass('is-invalid');
                                    alert(`File KTP peserta ${i} terlalu besar. Maksimal ukuran file adalah 5MB.`);
                                } else {
                                    $(`#ktp_file_${i}`).removeClass('is-invalid');
                                }
                            }
                        } else {
                            $(`#ktp_file_${i}`).removeClass('is-invalid');
                        }
                    }

                    // Validate phone
                    if (!phone || !phone.match(/^08[0-9]{8,13}$/)) {
                        isValid = false;
                        $(`#no_hp_${i}`).addClass('is-invalid');
                    } else {
                        $(`#no_hp_${i}`).removeClass('is-invalid');
                    }

                    // Validate address
                    if (!address) {
                        isValid = false;
                        $(`#alamat_${i}`).addClass('is-invalid');
                    } else {
                        $(`#alamat_${i}`).removeClass('is-invalid');
                    }

                    // Validate jersey size
                    if (!jersey) {
                        isValid = false;
                        $(`#ukuran_${i}`).addClass('is-invalid');
                    } else {
                        $(`#ukuran_${i}`).removeClass('is-invalid');
                    }

                    // Validate emergency contact
                    if (!emergency || !emergency.match(/^08[0-9]{8,13}$/)) {
                        isValid = false;
                        $(`#emg_${i}`).addClass('is-invalid');
                    } else {
                        $(`#emg_${i}`).removeClass('is-invalid');
                    }

                    // Collect participant data (without individual payment method)
                    participantData.push({
                        name: name,
                        gender: gender,
                        birthdate: birthdate,
                        phone: phone,
                        address: address,
                        jersey: jersey,
                        emergency: emergency,
                        ktpFile: ktpFile || null
                    });
                }

                if (!isValid) {
                    alert('Mohon lengkapi semua data peserta dengan benar!');
                    return;
                }

                // Bypass cart and go directly to checkout
                proceedToCheckout(participantData, paymentMethod);
            });

            // Proceed directly to checkout function
            function proceedToCheckout(participantData, paymentMethod) {
                // Show loading state
                $('#confirmPurchase').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

                // Generate invoice number
                const invoiceNo = 'INV-' + Date.now() + '-' + Math.random().toString(36).substr(2, 5).toUpperCase();

                // Calculate total
                const total = currentPurchaseData.price * currentPurchaseData.quantity;

                // Prepare order data in the format expected by Sale controller
                const orderData = {
                    no_nota: invoiceNo,
                    subtotal: total,
                    cart_data: JSON.stringify({
                        event_id: currentPurchaseData.eventId,
                        quantity: currentPurchaseData.quantity,
                        price: currentPurchaseData.price,
                        total: total
                    }),
                    participant: participantData.map((participant, index) => ({
                        participant_id: 0,
                        participant_name: participant.name,
                        // store gender in same format used elsewhere in system (male/female)
                        participant_gender: participant.gender === 'L' ? 'male' : (participant.gender === 'P' ? 'female' : null),
                        participant_birth_date: participant.birthdate,
                        participant_phone: participant.phone,
                        participant_address: participant.address,
                        participant_uk: participant.jersey,
                        participant_emg: participant.emergency,
                        event_id: currentPurchaseData.eventId,
                        price_id: currentPurchaseData.priceId, // Use the actual selected price ID
                        event_title: '<?= esc($event->event) ?>',
                        price_description: currentPurchaseData.description,
                        quantity: 1,
                        unit_price: currentPurchaseData.price,
                        total_price: currentPurchaseData.price,
                        kategori_id: 0, // Default category
                        platform_id: paymentMethod,
                        has_ktp: participant.ktpFile ? true : false
                    })),
                    cart_payments: [{
                        platform_id: paymentMethod,
                        amount: total,
                        note: 'Event Registration - ' + participantData.length + ' participants'
                    }]
                };

                // Create FormData for multipart/form-data submission
                const formData = new FormData();
                formData.append('order_data', JSON.stringify(orderData));
                
                // Add CSRF token
                const csrfToken = '<?= csrf_token() ?>';
                const csrfHash = window.csrf_hash || '<?= csrf_hash() ?>';
                formData.append(csrfToken, csrfHash);
                
                // Add KTP files for each participant
                participantData.forEach((participant, index) => {
                    if (participant.ktpFile) {
                        formData.append(`ktp_file_${index}`, participant.ktpFile);
                    }
                });

                // Submit directly to Sale controller store method
                $.ajax({
                    url: '<?= base_url('checkout') ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Redirect to checkout success page or order detail
                            window.location.href = response.redirect_url || '<?= base_url('sale/orders') ?>';
                        } else {
                            alert('Error: ' + (response.message || 'Failed to process checkout'));
                            $('#confirmPurchase').prop('disabled', false).html('<i class="fa fa-shopping-cart"></i> Beli');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Checkout error:', error);
                        console.error('Response:', xhr.responseText);
                        console.error('Status:', xhr.status);
                        alert('Error processing checkout. Status: ' + xhr.status + '. Please try again.');
                        $('#confirmPurchase').prop('disabled', false).html('<i class="fa fa-shopping-cart"></i> Beli');
                    }
                });
            }

            // Add to cart function with participant data
            function addToCartWithParticipants(participantData) {
                // Show loading state
                $('#confirmPurchase').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');

                // Prepare CSRF data
                var csrfData = {};
                var csrfToken = '<?= csrf_token() ?>';
                var csrfHash = window.csrf_hash || '<?= csrf_hash() ?>';
                csrfData[csrfToken] = csrfHash;

                // Make AJAX request to add to cart
                $.ajax({
                    url: '<?= base_url('cart/add') ?>',
                    type: 'POST',
                    data: $.extend({
                        event_id: currentPurchaseData.eventId,
                        price_id: currentPurchaseData.priceId,
                        quantity: currentPurchaseData.quantity,
                        price: currentPurchaseData.price,
                        participant_data: participantData,
                        event_title: '<?= esc($event->event ?? 'Event') ?>',
                        event_image: '<?= $event->foto ?? '' ?>',
                        price_description: currentPurchaseData.description,
                        event_date: '<?= $event->tgl_masuk ?? '' ?>',
                        event_location: '<?= $event->lokasi ?? 'TBA' ?>'
                    }, csrfData),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            if (typeof toastr !== 'undefined') {
                                toastr.success(response.message || 'Tiket berhasil ditambahkan ke keranjang!');
                            } else {
                                alert('Tiket berhasil ditambahkan ke keranjang!');
                            }

                            // Close modal
                            $('#participantNamesModal').modal('hide');

                            // Reset form
                            $('#participantNamesForm')[0].reset();

                            // Update cart counter
                            updateCartCounter();

                            // Update CSRF hash
                            if (response.csrf_hash) {
                                $('input[name="<?= csrf_token() ?>"]').val(response.csrf_hash);
                                window.csrf_hash = response.csrf_hash;
                            }
                        } else {
                            if (typeof toastr !== 'undefined') {
                                toastr.error(response.message || 'Gagal menambahkan ke keranjang');
                            } else {
                                alert('Error: ' + (response.message || 'Gagal menambahkan ke keranjang'));
                            }
                        }
                    },
                    error: function(xhr) {
                        var errorMsg = 'Terjadi kesalahan saat menambahkan ke keranjang';
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.message) errorMsg = response.message;
                        } catch (e) {
                            console.log('Could not parse error response');
                        }

                        if (typeof toastr !== 'undefined') {
                            toastr.error(errorMsg);
                        } else {
                            alert(errorMsg);
                        }
                    },
                    complete: function() {
                        // Reset button state
                        $('#confirmPurchase').prop('disabled', false).html('<i class="fa fa-shopping-cart"></i> Konfirmasi Pembelian');
                    }
                });
            }

            // Remove validation class on input
            $(document).on('input', '#participantFields input', function() {
                $(this).removeClass('is-invalid');
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
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
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
        box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
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
        background: rgba(0, 0, 0, 0.7);
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
        background: rgba(0, 0, 0, 0.5) !important;
        color: white !important;
        border: none !important;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .gallery-slider .owl-nav button:hover {
        background: rgba(0, 123, 255, 0.8) !important;
        transform: scale(1.1);
    }

    .gallery-slider .owl-nav .owl-prev {
        left: 10px;
    }

    .gallery-slider .owl-nav .owl-next {
        right: 10px;
    }

    /* Participant Form Styles */
    .participant-row {
        border: 1px solid #dee2e6;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }

    .participant-row:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-color: #007bff;
    }

    .participant-row h6 {
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid #007bff;
    }

    .form-label.small {
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .form-control-sm {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    /* Payment method styling */
    .payment-method-section {
        background-color: #f8f9fa;
        border-radius: 4px;
        padding: 10px;
        margin-top: 10px;
    }

    .payment-fee-display {
        background-color: #e9ecef !important;
        color: #495057;
        font-weight: 500;
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

        /* Stack fields vertically on mobile */
        .participant-row .row>div {
            margin-bottom: 10px;
        }

        /* Make gender dropdown full width on mobile */
        .participant-row .col-md-3 {
            margin-bottom: 10px;
        }
    }

    @media (max-width: 576px) {
        .participant-row {
            padding: 15px !important;
        }

        .participant-row h6 {
            font-size: 1rem;
            margin-bottom: 10px;
        }

        /* Stack all fields in single column on very small screens */
        .participant-row .col-md-3 {
            width: 100%;
            margin-bottom: 15px;
        }
    }
</style>

<!-- Participant Names Modal -->
<div class="modal fade" id="participantNamesModal" tabindex="-1" role="dialog" aria-labelledby="participantNamesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="participantNamesModalLabel">
                    <i class="fa fa-users"></i> Data Peserta
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="participantNamesForm">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i>
                        Masukkan nama lengkap untuk setiap peserta sesuai dengan jumlah tiket yang dibeli.
                    </div>
                    <div id="participantFields">
                        <!-- Participant name fields will be generated here -->
                    </div>

                    <!-- Single Payment Method Section -->
                    <div class="payment-method-section mt-4 p-3 border rounded" style="background-color: #f8f9fa;">
                        <h6 class="mb-3 text-primary">
                            <i class="fa fa-credit-card"></i> Metode Pembayaran
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="payment_method" class="form-label small">Pilih Metode Pembayaran <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm" id="payment_method" name="payment_method" required>
                                        <option value="">Pilih Metode Pembayaran</option>
                                        <?php if (!empty($platformOptions)): ?>
                                            <?php foreach ($platformOptions as $platform): ?>
                                                <option value="<?= esc($platform->id) ?>" data-fee="<?= esc($platform->nama) . ' ' . esc($platform->nama_rekening) . ' - ' . esc($platform->nomor_rekening) ?>"><?= esc($platform->nama) ?> - <?= esc($platform->nama_rekening) ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label class="form-label small">Biaya Admin</label>
                                    <div class="form-control form-control-sm payment-fee-display" id="payment_fee">
                                        Pilih metode pembayaran
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="background-color: #dc3545; border-color: #dc3545;">
                    <i class="fa fa-times"></i> Batal
                </button>
                <button type="button" class="btn btn-primary" id="confirmPurchase">
                    <i class="fa fa-shopping-cart"></i> Beli
                </button>
            </div>
        </div>
    </div>
</div>


<?php
echo $this->endSection();
?>