<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">Tambah Data Event</h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/events') ?>" class="btn btn-sm btn-secondary rounded-0">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <?php
                    // Determine if this is edit or create
                    $isEdit = isset($event) && isset($event->id);
                ?>
                <?= form_open('admin/events/store', [
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data'
                ]) ?>
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= esc($event->id) ?>">
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode">Kode</label>
                                <input type="text" 
                                       name="kode" 
                                       id="kode" 
                                       class="form-control rounded-0" 
                                       value="<?= old('kode', $isEdit ? $event->kode : '') ?>" 
                                       placeholder="Masukkan kode event (opsional)">
                                <?php if (session('errors.kode')): ?>
                                    <small class="text-danger"><?= session('errors.kode') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="event">Nama Event <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="event" 
                                       id="event" 
                                       class="form-control rounded-0" 
                                       value="<?= old('event', $isEdit ? $event->event : '') ?>" 
                                       placeholder="Masukkan nama event" 
                                       required>
                                <?php if (session('errors.event')): ?>
                                    <small class="text-danger"><?= session('errors.event') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="foto">Foto Utama</label>
                                <div class="custom-file-upload" id="photoUploadArea">
                                    <div class="upload-placeholder" <?= ($isEdit && !empty($event->foto)) ? 'style="display:none;"' : '' ?>>
                                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                        <h5>Drag & Drop foto utama di sini</h5>
                                        <p class="text-muted">atau klik untuk memilih file</p>
                                        <p class="text-muted small">Format: JPG, PNG, GIF (Max: 2MB)</p>
                                    </div>
                                    <div class="file-preview" id="filePreview" style="<?= ($isEdit && !empty($event->foto)) ? '' : 'display: none;' ?>">
                                        <?php if ($isEdit && !empty($event->foto)): ?>
                                            <img id="previewImage" src="<?= base_url('public/file/events/' . $event->id . '/' . $event->foto) ?>" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                        <?php else: ?>
                                            <img id="previewImage" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-danger mt-2" id="removePhoto">
                                            <i class="fas fa-trash"></i> Hapus Foto
                                        </button>
                                    </div>
                                </div>
                                <input type="file" name="foto" id="foto" accept="image/*" style="display: none;">
                                <?php if ($isEdit && !empty($event->foto)): ?>
                                    <input type="hidden" name="foto_lama" value="<?= esc($event->foto) ?>">
                                <?php endif; ?>
                                <?php if (session('errors.foto')): ?>
                                    <small class="text-danger"><?= session('errors.foto') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_kategori">Kategori <span class="text-danger">*</span></label>
                                <select name="id_kategori" id="id_kategori" class="form-control rounded-0" required>
                                    <option value="">Pilih Kategori</option>
                                    <?php if (isset($kategoriOptions) && is_array($kategoriOptions)): ?>
                                        <?php foreach ($kategoriOptions as $kategori): ?>
                                            <option value="<?= $kategori->id ?>" 
                                                <?= old('id_kategori', $isEdit ? $event->id_kategori : '') == $kategori->id ? 'selected' : '' ?>>
                                                <?= $kategori->kategori ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php if (session('errors.id_kategori')): ?>
                                    <small class="text-danger"><?= session('errors.id_kategori') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control rounded-0" required>
                                    <option value="">Pilih Status</option>
                                    <option value="1" <?= old('status', $isEdit ? $event->status : '1') == '1' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="0" <?= old('status', $isEdit ? $event->status : '') == '0' ? 'selected' : '' ?>>Tidak Aktif</option>
                                </select>
                                <?php if (session('errors.status')): ?>
                                    <small class="text-danger"><?= session('errors.status') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Empty column for spacing -->
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tgl_masuk">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" 
                                       name="tgl_masuk" 
                                       id="tgl_masuk" 
                                       class="form-control rounded-0" 
                                       value="<?= old('tgl_masuk', $isEdit ? $event->tgl_masuk : '') ?>" 
                                       required>
                                <?php if (session('errors.tgl_masuk')): ?>
                                    <small class="text-danger"><?= session('errors.tgl_masuk') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tgl_keluar">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" 
                                       name="tgl_keluar" 
                                       id="tgl_keluar" 
                                       class="form-control rounded-0" 
                                       value="<?= old('tgl_keluar', $isEdit ? $event->tgl_keluar : '') ?>" 
                                       required>
                                <?php if (session('errors.tgl_keluar')): ?>
                                    <small class="text-danger"><?= session('errors.tgl_keluar') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="wkt_masuk">Waktu Mulai <span class="text-danger">*</span></label>
                                <input type="time" 
                                       name="wkt_masuk" 
                                       id="wkt_masuk" 
                                       class="form-control rounded-0" 
                                       value="<?= old('wkt_masuk', $isEdit ? $event->wkt_masuk : '') ?>" 
                                       required>
                                <?php if (session('errors.wkt_masuk')): ?>
                                    <small class="text-danger"><?= session('errors.wkt_masuk') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="wkt_keluar">Waktu Selesai <span class="text-danger">*</span></label>
                                <input type="time" 
                                       name="wkt_keluar" 
                                       id="wkt_keluar" 
                                       class="form-control rounded-0" 
                                       value="<?= old('wkt_keluar', $isEdit ? $event->wkt_keluar : '') ?>" 
                                       required>
                                <?php if (session('errors.wkt_keluar')): ?>
                                    <small class="text-danger"><?= session('errors.wkt_keluar') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lokasi">Lokasi</label>
                                <input type="text" 
                                       name="lokasi" 
                                       id="lokasi" 
                                       class="form-control rounded-0" 
                                       value="<?= old('lokasi', $isEdit ? $event->lokasi : '') ?>" 
                                       placeholder="Masukkan lokasi event">
                                <?php if (session('errors.lokasi')): ?>
                                    <small class="text-danger"><?= session('errors.lokasi') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jml">Kapasitas</label>
                                <input type="number" 
                                       name="jml" 
                                       id="jml" 
                                       class="form-control rounded-0" 
                                       value="<?= old('jml', $isEdit ? $event->jml : '') ?>" 
                                       placeholder="Masukkan kapasitas maksimal" 
                                       min="0">
                                <?php if (session('errors.jml')): ?>
                                    <small class="text-danger"><?= session('errors.jml') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" 
                                  id="keterangan" 
                                  class="form-control rounded-0" 
                                  rows="3" 
                                  placeholder="Masukkan keterangan tambahan"><?= old('keterangan', $isEdit ? $event->keterangan : '') ?></textarea>
                        <?php if (session('errors.keterangan')): ?>
                            <small class="text-danger"><?= session('errors.keterangan') ?></small>
                        <?php endif ?>
                    </div>
                    
                    <!-- Google Maps Integration -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-map-marker-alt"></i> Lokasi Google Maps
                            </h5>
                            <small class="text-muted">Klik pada peta untuk menentukan koordinat lokasi event</small>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lat">Latitude <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="lat" 
                                               id="lat" 
                                               class="form-control rounded-0 coordinate-input" 
                                               value="<?= old('lat', $isEdit ? $event->latitude : '') ?>" 
                                               placeholder="Contoh: -6.2088" 
                                               required>
                                        <?php if (session('errors.lat')): ?>
                                            <small class="text-danger"><?= session('errors.lat') ?></small>
                                        <?php endif ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="long">Longitude <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="long" 
                                               id="long" 
                                               class="form-control rounded-0 coordinate-input" 
                                               value="<?= old('long', $isEdit ? $event->longitude : '') ?>" 
                                               placeholder="Contoh: 106.8456" 
                                               required>
                                        <?php if (session('errors.long')): ?>
                                            <small class="text-danger"><?= session('errors.long') ?></small>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Map Container -->
                            <div class="form-group">
                                <label>Peta Lokasi</label>
                                <div id="map" style="height: 400px; width: 100%; border: 1px solid #ddd; border-radius: 4px;"></div>
                                <small class="text-muted">Klik pada peta untuk mengatur koordinat lokasi event</small>
                            </div>
                            
                            <!-- Search Location -->
                            <div class="form-group search-location">
                                <label for="searchLocation">Cari Lokasi</label>
                                <div class="input-group">
                                    <input type="text" 
                                           id="searchLocation" 
                                           class="form-control rounded-0" 
                                           placeholder="Masukkan nama tempat atau alamat"
                                           spellcheck="false"
                                           autocomplete="off">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary rounded-0" id="searchBtn">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                    </div>
                                </div>
                                <!-- Search Suggestions Dropdown -->
                                <div id="searchSuggestions" class="search-suggestions" style="display: none;">
                                    <div class="suggestions-list"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary rounded-0">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('admin/events') ?>" class="btn btn-secondary rounded-0">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                <?= form_close() ?>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

<style>
.custom-file-upload {
    border: 2px dashed #ddd;
    border-radius: 8px;
    padding: 30px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.custom-file-upload:hover {
    border-color: #007bff;
    background: #e3f2fd;
}

.upload-placeholder {
    color: #6c757d;
}

.file-preview {
    text-align: center;
}

.file-preview img {
    border: 2px solid #dee2e6;
    border-radius: 8px;
}

#removePhoto {
    border-radius: 20px;
}

.search-location {
    position: relative;
}

.search-location input[type="text"] {
    /* Remove spell check warnings completely */
    -webkit-spellcheck: false !important;
    -moz-spellcheck: false !important;
    spellcheck: false !important;
    /* Remove red wavy underline */
    outline: none !important;
    border: 1px solid #ced4da !important;
    /* Additional spell check removal */
    -webkit-text-security: none !important;
    text-security: none !important;
    /* Remove any browser styling */
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
}

.search-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-top: none;
    border-radius: 0 0 4px 4px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    z-index: 1000;
    max-height: 300px;
    overflow-y: auto;
}

.suggestions-list {
    padding: 0;
    margin: 0;
}

.suggestion-item {
    padding: 12px 15px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background-color 0.2s ease;
    display: flex;
    align-items: center;
}

.suggestion-item:hover {
    background-color: #f8f9fa;
}

.suggestion-item:last-child {
    border-bottom: none;
}

.suggestion-icon {
    margin-right: 12px;
    color: #6c757d;
    font-size: 16px;
    width: 20px;
    text-align: center;
}

.suggestion-text {
    flex: 1;
}

.suggestion-primary {
    font-weight: 500;
    color: #333;
    margin-bottom: 2px;
}

.suggestion-secondary {
    font-size: 12px;
    color: #6c757d;
}
</style>

<script>
$(document).ready(function() {
    var photoUploadArea = $('#photoUploadArea');
    var fileInput = $('#foto');
    var filePreview = $('#filePreview');
    var previewImage = $('#previewImage');
    var uploadPlaceholder = $('.upload-placeholder');
    
    // Click to select file
    photoUploadArea.on('click', function() {
        fileInput.click();
    });
    
    // Handle file selection
    fileInput.on('change', function() {
        var file = this.files[0];
        if (file) {
            // Validate file type
            if (!file.type.match('image.*')) {
                alert('Hanya file gambar yang diperbolehkan!');
                return;
            }
            
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB!');
                return;
            }
            
            // Show preview
            var reader = new FileReader();
            reader.onload = function(e) {
                previewImage.attr('src', e.target.result);
                uploadPlaceholder.hide();
                filePreview.show();
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Remove photo
    $('#removePhoto').on('click', function() {
        fileInput.val('');
        filePreview.hide();
        uploadPlaceholder.show();
    });
    
    // Drag and drop functionality
    photoUploadArea.on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('dragover');
    });
    
    photoUploadArea.on('dragleave', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
    });
    
    photoUploadArea.on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
        
        var files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            fileInput[0].files = files;
            fileInput.trigger('change');
        }
    });
    
    // Add dragover visual feedback
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .custom-file-upload.dragover {
                border-color: #007bff !important;
                background: #e3f2fd !important;
                transform: scale(1.02);
            }
        `)
        .appendTo('head');
});
</script>

<!-- Google Maps Integration Script -->
<script>
// Initialize Google Maps
let map, marker, geocoder;
let defaultLat = <?= $isEdit && !empty($event->latitude) ? $event->latitude : '-6.2088' ?>;
let defaultLng = <?= $isEdit && !empty($event->longitude) ? $event->longitude : '106.8456' ?>;

function initMap() {
    // Create map centered on default location (Jakarta)
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: { lat: parseFloat(defaultLat), lng: parseFloat(defaultLng) },
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    // Create marker
    marker = new google.maps.Marker({
        position: { lat: parseFloat(defaultLat), lng: parseFloat(defaultLng) },
        map: map,
        draggable: true,
        title: 'Lokasi Event'
    });

    // Initialize geocoder
    geocoder = new google.maps.Geocoder();

    // Add click event to map
    map.addListener('click', function(event) {
        placeMarker(event.latLng);
        updateCoordinates(event.latLng.lat(), event.latLng.lng());
    });

    // Add drag event to marker
    marker.addListener('dragend', function(event) {
        updateCoordinates(event.latLng.lat(), event.latLng.lng());
    });

    // If editing, place marker at existing coordinates
    if (<?= $isEdit ? 'true' : 'false' ?> && defaultLat && defaultLng) {
        const position = { lat: parseFloat(defaultLat), lng: parseFloat(defaultLng) };
        placeMarker(position);
        updateCoordinates(defaultLat, defaultLng);
    }
}

function placeMarker(latLng) {
    marker.setPosition(latLng);
    map.setCenter(latLng);
}

function updateCoordinates(lat, lng) {
    document.getElementById('lat').value = lat.toFixed(6);
    document.getElementById('long').value = lng.toFixed(6);
}

// Debug: Check if search suggestions container exists
document.addEventListener('DOMContentLoaded', function() {
    const suggestionsContainer = document.getElementById('searchSuggestions');
    if (suggestionsContainer) {
        console.log('Search suggestions container found:', suggestionsContainer);
    } else {
        console.error('Search suggestions container NOT found');
    }
});

// Search location functionality
document.getElementById('searchBtn').addEventListener('click', function() {
    const address = document.getElementById('searchLocation').value;
    if (address) {
        geocoder.geocode({ address: address }, function(results, status) {
            if (status === 'OK') {
                const location = results[0].geometry.location;
                placeMarker(location);
                updateCoordinates(location.lat(), location.lng());
                
                // Update location field
                document.getElementById('lokasi').value = results[0].formatted_address;
            } else {
                alert('Lokasi tidak ditemukan: ' + status);
            }
        });
    }
});

// Enter key support for search
document.getElementById('searchLocation').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        document.getElementById('searchBtn').click();
    }
});

// Search suggestions functionality
let searchTimeout;
let currentSuggestions = [];

document.getElementById('searchLocation').addEventListener('input', function(e) {
    const query = e.target.value.trim();
    
    // Clear previous timeout
    clearTimeout(searchTimeout);
    
    // Hide suggestions if query is empty
    if (query.length < 2) {
        hideSuggestions();
        return;
    }
    
    // Debounce search requests
    searchTimeout = setTimeout(() => {
        searchSuggestions(query);
    }, 300);
});

// Search for location suggestions
function searchSuggestions(query) {
    // Clean the query
    const cleanQuery = query.trim();
    
    if (cleanQuery.length < 2) {
        hideSuggestions();
        return;
    }
    
    // Create more specific search queries for better results
    const searchQueries = [
        cleanQuery + ' Jakarta Indonesia',
        cleanQuery + ' Surabaya Indonesia', 
        cleanQuery + ' Bandung Indonesia',
        cleanQuery + ' Medan Indonesia',
        cleanQuery + ' Semarang Indonesia'
    ];
    
    let searchAttempt = 0;
    let foundResults = false;
    
    function attemptSearch() {
        if (searchAttempt >= searchQueries.length || foundResults) {
            if (!foundResults) {
                // If no results found, show a generic suggestion
                showGenericSuggestions(cleanQuery);
            }
            return;
        }
        
        const currentQuery = searchQueries[searchAttempt];
        
        geocoder.geocode({ 
            address: currentQuery,
            componentRestrictions: { country: 'id' }
        }, function(results, status) {
            if (status === 'OK' && results && results.length > 0) {
                foundResults = true;
                
                // Convert geocoding results to suggestion format
                const suggestions = results.slice(0, 5).map(result => {
                    const fullAddress = result.formatted_address;
                    const parts = fullAddress.split(',');
                    const placeName = parts[0].trim();
                    const address = parts.slice(1).join(',').trim();
                    
                    return {
                        description: fullAddress,
                        place_id: result.place_id || Math.random().toString(36),
                        structured_formatting: {
                            main_text: placeName,
                            secondary_text: address
                        },
                        types: result.types || ['geocode']
                    };
                });
                
                showSuggestions(suggestions);
            } else {
                // Try next search query
                searchAttempt++;
                attemptSearch();
            }
        });
    }
    
    attemptSearch();
}

// Show generic suggestions when specific search fails
function showGenericSuggestions(query) {
    const suggestions = [
        {
            description: query + ' Jakarta',
            place_id: 'jakarta_' + Date.now(),
            structured_formatting: {
                main_text: query + ' Jakarta',
                secondary_text: 'DKI Jakarta, Indonesia'
            },
            types: ['locality']
        },
        {
            description: query + ' Surabaya',
            place_id: 'surabaya_' + Date.now(),
            structured_formatting: {
                main_text: query + ' Surabaya',
                secondary_text: 'Jawa Timur, Indonesia'
            },
            types: ['locality']
        },
        {
            description: query + ' Bandung',
            place_id: 'bandung_' + Date.now(),
            structured_formatting: {
                main_text: query + ' Bandung',
                secondary_text: 'Jawa Barat, Indonesia'
            },
            types: ['locality']
        }
    ];
    
    showSuggestions(suggestions);
}

// Show search suggestions
function showSuggestions(predictions) {
    const suggestionsContainer = document.getElementById('searchSuggestions');
    
    // Check if container exists
    if (!suggestionsContainer) {
        console.error('Search suggestions container not found');
        return;
    }
    
    const suggestionsList = suggestionsContainer.querySelector('.suggestions-list');
    
    // Check if suggestions list exists
    if (!suggestionsList) {
        console.error('Suggestions list not found');
        return;
    }
    
    // Clear previous suggestions
    suggestionsList.innerHTML = '';
    
    // Add new suggestions
    predictions.forEach((prediction, index) => {
        const suggestionItem = document.createElement('div');
        suggestionItem.className = 'suggestion-item';
        suggestionItem.dataset.index = index;
        
        // Determine icon based on type
        let icon = 'fas fa-map-marker-alt';
        if (prediction.types && prediction.types.includes('establishment')) {
            icon = 'fas fa-building';
        } else if (prediction.types && prediction.types.includes('route')) {
            icon = 'fas fa-road';
        } else if (prediction.types && prediction.types.includes('locality')) {
            icon = 'fas fa-city';
        }
        
        suggestionItem.innerHTML = `
            <div class="suggestion-icon">
                <i class="${icon}"></i>
            </div>
            <div class="suggestion-text">
                <div class="suggestion-primary">${prediction.structured_formatting.main_text}</div>
                <div class="suggestion-secondary">${prediction.structured_formatting.secondary_text}</div>
            </div>
        `;
        
        // Add click event
        suggestionItem.addEventListener('click', function() {
            selectSuggestion(prediction);
        });
        
        // Add hover effects
        suggestionItem.addEventListener('mouseenter', function() {
            this.classList.add('active');
        });
        
        suggestionItem.addEventListener('mouseleave', function() {
            this.classList.remove('active');
        });
        
        suggestionsList.appendChild(suggestionItem);
    });
    
    // Show suggestions container
    suggestionsContainer.style.display = 'block';
    currentSuggestions = predictions;
}

// Hide search suggestions
function hideSuggestions() {
    const suggestionsContainer = document.getElementById('searchSuggestions');
    if (suggestionsContainer) {
        suggestionsContainer.style.display = 'none';
        currentSuggestions = [];
    }
}

// Select a suggestion
function selectSuggestion(prediction) {
    // Hide suggestions
    hideSuggestions();
    
    // Update search input
    document.getElementById('searchLocation').value = prediction.description;
    
    // Get place details and update map
    if (prediction.place_id && prediction.place_id.length > 10 && google.maps && google.maps.places) {
        // Try to use Places Service if we have a valid place_id
        try {
            const service = new google.maps.places.PlacesService(map);
            service.getDetails({
                placeId: prediction.place_id
            }, function(place, status) {
                if (status === google.maps.places.PlacesServiceStatus.OK && place.geometry) {
                    const location = place.geometry.location;
                    
                    // Place marker and update coordinates
                    placeMarker(location);
                    updateCoordinates(location.lat(), location.lng());
                    
                    // Update location field with place name (not full address)
                    if (place.name) {
                        document.getElementById('lokasi').value = place.name;
                    } else if (prediction.structured_formatting.main_text) {
                        document.getElementById('lokasi').value = prediction.structured_formatting.main_text;
                    }
                    
                    // Center map on selected location
                    map.setCenter(location);
                    map.setZoom(16);
                } else {
                    // Fallback to geocoding
                    updateMapWithGeocoding(prediction.description);
                }
            });
        } catch (error) {
            console.warn('Places Service failed, using geocoding fallback:', error);
            updateMapWithGeocoding(prediction.description);
        }
    } else {
        // Use geocoding directly
        updateMapWithGeocoding(prediction.description);
    }
}

// Helper function to update map using geocoding
function updateMapWithGeocoding(address) {
    geocoder.geocode({ address: address }, function(results, status) {
        if (status === 'OK' && results[0]) {
            const location = results[0].geometry.location;
            
            // Place marker and update coordinates
            placeMarker(location);
            updateCoordinates(location.lat(), location.lng());
            
            // Update location field
            document.getElementById('lokasi').value = results[0].formatted_address;
            
            // Center map on selected location
            map.setCenter(location);
            map.setZoom(16);
        } else {
            console.error('Geocoding failed for address:', address);
        }
    });
}

// Click outside to hide suggestions
document.addEventListener('click', function(e) {
    const searchLocation = document.getElementById('searchLocation');
    const suggestionsContainer = document.getElementById('searchSuggestions');
    
    if (!searchLocation.contains(e.target) && !suggestionsContainer.contains(e.target)) {
        hideSuggestions();
    }
});

// Form validation for coordinates
document.querySelector('form').addEventListener('submit', function(e) {
    const lat = document.getElementById('lat').value;
    const lng = document.getElementById('long').value;
    
    if (!lat || !lng) {
        e.preventDefault();
        alert('Mohon tentukan koordinat lokasi event dengan mengklik pada peta atau menggunakan fitur pencarian lokasi.');
        return false;
    }
    
    // Validate coordinate format
    const latNum = parseFloat(lat);
    const lngNum = parseFloat(lng);
    
    if (isNaN(latNum) || isNaN(lngNum)) {
        e.preventDefault();
        alert('Format koordinat tidak valid. Mohon gunakan angka desimal.');
        return false;
    }
    
    if (latNum < -90 || latNum > 90) {
        e.preventDefault();
        alert('Latitude harus berada antara -90 dan 90.');
        return false;
    }
    
    if (lngNum < -180 || lngNum > 180) {
        e.preventDefault();
        alert('Longitude harus berada antara -180 dan 180.');
        return false;
    }
});
</script>

<!-- Google Maps API Script -->
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=<?= getenv('GOOGLE_MAPS_API') ?>&libraries=places&callback=initMap">
</script>

<!-- TinyMCE CDN -->
<script src="https://cdn.tiny.cloud/1/<?= getenv('TINYMCE_API') ?>/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#keterangan',
        output_format: 'html',
        plugins: 'lists link image table wordcount',
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | table',
        menubar: false,
        statusbar: false,
        height: 200,
        language: 'id',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 14px; }',
        setup: function(editor) {
            editor.on('change', function() {
                editor.save();
            });
        }
    });
</script>
<?= $this->endSection() ?>
