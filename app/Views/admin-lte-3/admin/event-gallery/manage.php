<?= $this->extend(theme_path('main')) ?>

<?= $this->section('css') ?>
<!-- Dropzone CSS -->
<link rel="stylesheet" href="<?= base_url('public/assets/theme/admin-lte-3/plugins/dropzone/min/dropzone.min.css') ?>" type="text/css" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card rounded-0">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="card-title"><?= $title ?></h3>
                    </div>
                    <div class="col-md-6">
                        <ol class="breadcrumb float-sm-right mb-0 bg-transparent p-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('admin/events') ?>">Events</a></li>
                            <li class="breadcrumb-item active">Galeri Event</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- Event Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Informasi Event</h5>
                        <table class="table table-striped">
                            <tr>
                                <td width="120" class="text-left"><strong>Nama Event</strong></td>
                                <td class="text-center" style="width: 1px;">:</td>
                                <td class="text-left"><?= esc($event->event) ?></td>
                            </tr>
                            <tr>
                                <td class="text-left"><strong>Tanggal</strong></td>
                                <td class="text-center" style="width: 1px;">:</td>
                                <td class="text-left"><?= tgl_indo5($event->tgl_masuk) ?> - <?= tgl_indo4($event->tgl_keluar) ?></td>
                            </tr>
                            <tr>
                                <td class="text-left"><strong>Lokasi</strong></td>
                                <td class="text-center" style="width: 1px;">:</td>
                                <td class="text-left"><?= esc($event->lokasi) ?: '-' ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Upload Galeri</h5>
                                                 <div class="dropzone" id="galleryDropzone">
                             <div class="dz-message">
                                 <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                 <h5>Drag & Drop gambar galeri di sini</h5>
                                 <p class="text-muted">atau klik untuk memilih file</p>
                                 <p class="text-muted small">Format: JPG, PNG, GIF (Max: 2MB per file)</p>
                             </div>
                         </div>
                         
                         <!-- Upload button -->
                         <div class="mt-2">
                             <button type="button" class="btn btn-success rounded-0" onclick="uploadFiles()">
                                 <i class="fas fa-upload"></i> Unggah
                             </button>
                         </div>
                    </div>
                </div>

                <!-- Gallery Grid -->
                <div class="row">
                    <div class="col-12">
                        <h5>Galeri Event</h5>
                        <div class="row" id="galleryGrid">
                            <?php if (!empty($galleries)): ?>
                                <?php foreach ($galleries as $gallery): ?>
                                    <div class="col-md-3 col-sm-6 mb-3" id="gallery-item-<?= $gallery->id ?>">
                                        <div class="card gallery-item">
                                            <div class="card-img-top position-relative">
                                                <img src="<?= base_url('public/file/events/' . $event->id . '/gallery/' . $gallery->file) ?>" 
                                                     class="img-fluid" 
                                                     alt="Gallery Image"
                                                     style="height: 200px; object-fit: cover; width: 100%;">
                                            </div>
                                            <div class="card-body p-2">
                                                <textarea class="form-control form-control-sm description-input" 
                                                          data-id="<?= $gallery->id ?>" 
                                                          placeholder="Tambah deskripsi..."
                                                          rows="2"><?= esc($gallery->deskripsi) ?></textarea>
                                                <small class="text-muted">Klik di luar untuk simpan deskripsi</small>
                                                <!-- Action Buttons moved below description -->
                                                <div class="mt-2 d-flex justify-content-end">
                                                    <div class="btn-group">
                                                        <?php if ($gallery->is_cover != 1): ?>
                                                            <a href="<?= base_url("admin/event-gallery/set-cover/$gallery->id") ?>" 
                                                               class="btn btn-sm btn-success" 
                                                               title="Set as Cover"
                                                               onclick="return confirm('Set sebagai cover image?')">
                                                                <i class="fas fa-star"></i>
                                                            </a>
                                                        <?php endif ?>
                                                        <a href="<?= base_url("admin/event-gallery/delete/$gallery->id") ?>" 
                                                           class="btn btn-sm btn-danger" 
                                                           title="Hapus"
                                                           onclick="return confirm('Apakah anda yakin ingin menghapus gambar ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <div class="alert alert-info text-center">
                                        <i class="fas fa-info-circle"></i>
                                        Belum ada gambar galeri yang diupload
                                    </div>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>

                <!-- Back Button -->
                <div class="mt-4">
                    <a href="<?= base_url('admin/events') ?>" class="btn btn-primary rounded-0">
                        &laquo; Kembali
                    </a>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->



<style>
.gallery-item {
    transition: all 0.3s ease;
}

.gallery-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.description-input {
    border: 1px solid #ddd;
    transition: border-color 0.3s ease;
}

.description-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

.dropzone {
    border: 2px dashed #ddd;
    border-radius: 8px;
    padding: 30px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.dropzone:hover {
    border-color: #007bff;
    background: #e3f2fd;
}

.dropzone.dz-drag-hover {
    border-color: #007bff;
    background: #e3f2fd;
    transform: scale(1.02);
}

/* Fix Dropzone error display issues */
.dropzone .dz-error-message {
    color: #721c24;
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    border-radius: 4px;
    padding: 8px;
    margin: 10px 0;
    font-size: 14px;
}

.dropzone .dz-error .dz-error-message {
    display: block !important;
}

.dropzone .dz-preview .dz-error {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
}

.dropzone .dz-preview .dz-error .dz-error-message {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Ensure proper button text display */
.dropzone .dz-preview .dz-remove {
    color: #dc3545 !important;
    text-decoration: none;
    font-weight: bold;
}

.dropzone .dz-preview .dz-remove:hover {
    color: #c82333 !important;
    text-decoration: underline;
}
</style>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- Dropzone JS -->
<script src="<?= base_url('public/assets/theme/admin-lte-3/plugins/dropzone/min/dropzone.min.js') ?>"></script>

<script>
// Disable auto-discovery BEFORE DOM is ready
Dropzone.autoDiscover = false;

$(document).ready(function() {
    var uploadUrl = "<?= base_url('admin/event-gallery/upload') ?>";
    var eventId = "<?= $event->id ?>";

    // Validate URL and eventId
    if (!uploadUrl || uploadUrl === "" || !eventId || eventId === "") {
        return;
    }

    function initDropzone() {
        if ($('#galleryDropzone').length === 0) {
            return;
        }

        if (typeof Dropzone !== 'undefined' && uploadUrl && uploadUrl !== "" && eventId && eventId !== "") {
            // Destroy any existing instances first
            if (Dropzone.instances.length > 0) {
                Dropzone.instances.forEach(function(instance) {
                    if (instance.element.id === 'galleryDropzone') {
                        instance.destroy();
                    }
                });
            }

            try {
                var galleryDropzone = new Dropzone("#galleryDropzone", {
                    url: uploadUrl,
                    paramName: "gallery",
                    maxFilesize: 2, // 2MB
                    acceptedFiles: "image/*",
                    addRemoveLinks: true,
                    dictDefaultMessage: "Drag & Drop gambar galeri di sini",
                    dictFileTooBig: "File terlalu besar ({{filesize}}MB). Maksimal: {{maxFilesize}}MB.",
                    dictInvalidFileType: "Tidak dapat upload file jenis ini.",
                    dictRemoveFile: "Hapus",
                    dictCancelUpload: "Batal",
                    dictResponseError: "Upload gagal",
                    dictMaxFilesExceeded: "Tidak dapat upload lebih banyak file",
                    dictUploadCanceled: "Upload dibatalkan",
                    dictFallbackMessage: "Browser Anda tidak mendukung drag & drop file uploads.",
                    dictFileSizeUnits: { tb: "TB", gb: "GB", mb: "MB", kb: "KB", b: "b" },
                    maxFiles: 10,
                    retryChunks: false,
                    chunking: false,
                    autoProcessQueue: false,
                    error: function(file, errorMessage, xhr) {
                        var displayMessage = "Upload gagal";
                        if (typeof errorMessage === 'string') {
                            displayMessage = errorMessage;
                        } else if (errorMessage && errorMessage.message) {
                            displayMessage = errorMessage.message;
                        } else if (errorMessage && typeof errorMessage === 'object') {
                            displayMessage = "Upload gagal - " + JSON.stringify(errorMessage);
                        }
                        alert(displayMessage);
                    },
                    init: function() {
                        this.on("sending", function(file, xhr, formData) {
                            // Add event_id only (CSRF is disabled for this endpoint)
                            formData.append("event_id", eventId);
                        });

                        this.on("success", function(file, response) {
                            if (response.success) {
                                location.reload();
                            } else {
                                var errorMsg = "Upload gagal";
                                if (response.error && typeof response.error === 'string') {
                                    errorMsg = response.error;
                                } else if (response.error && response.error.message) {
                                    errorMsg = response.error.message;
                                }
                                alert(errorMsg);
                            }
                        });

                        this.on("error", function(file, errorMessage, xhr) {
                            var displayMessage = "Upload gagal";
                            if (typeof errorMessage === 'string') {
                                displayMessage = errorMessage;
                            } else if (errorMessage && errorMessage.message) {
                                displayMessage = errorMessage.message;
                            } else if (errorMessage && typeof errorMessage === 'object') {
                                displayMessage = "Upload gagal - " + JSON.stringify(errorMessage);
                            }
                            alert(displayMessage);
                        });

                        this.on("addedfile", function(file) {
                            if (!file.type.match(/image.*/)) {
                                this.removeFile(file);
                                alert('File ' + file.name + ' bukan file gambar yang valid');
                                return;
                            }
                            if (file.size > 2 * 1024 * 1024) {
                                this.removeFile(file);
                                alert('File ' + file.name + ' terlalu besar (maksimal 2MB)');
                                return;
                            }
                        });

                        this.on("removedfile", function(file) {});
                        this.on("processing", function(file) {});
                        this.on("uploadprogress", function(file, progress, bytesSent) {});
                        this.on("queuecomplete", function() {});
                        this.on("rejectedfile", function(file, errorMessage) {
                            alert('File ' + file.name + ' ditolak: ' + errorMessage);
                        });
                        this.on("complete", function(file) {});
                    }
                });

                window.galleryDropzone = galleryDropzone;
            } catch (error) {
                // No console log
            }
        } else {
            setTimeout(initDropzone, 100);
        }
    }

    setTimeout(function() {
        initDropzone();
    }, 200);

    // Upload function
    window.uploadFiles = function() {
        if (window.galleryDropzone) {
            if (window.galleryDropzone.files.length > 0) {
                window.galleryDropzone.processQueue();
            } else {
                alert('Tidak ada file yang dipilih untuk diupload');
            }
        } else {
            alert('Dropzone belum diinisialisasi');
        }
    };

    // Test upload configuration
    window.testUploadConfig = function() {
        $.ajax({
            url: '<?= base_url('admin/event-gallery/test-upload') ?>',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var phpSettings = response.php_settings;
                    var requestData = response.request_data;

                    // Check for common issues
                    var issues = [];
                    if (parseInt(phpSettings.upload_max_filesize) < 2) {
                        issues.push('upload_max_filesize too low (need at least 2M)');
                    }
                    if (parseInt(phpSettings.post_max_size) < 8) {
                        issues.push('post_max_size too low (need at least 8M)');
                    }
                    if (phpSettings.file_uploads !== '1') {
                        issues.push('file_uploads disabled');
                    }

                    if (issues.length > 0) {
                        alert('PHP Configuration Issues Found:\n' + issues.join('\n'));
                    } else {
                        alert('PHP configuration looks good!');
                    }
                }
            },
            error: function(xhr, status, error) {
                alert('Error testing upload configuration');
            }
        });
    };

    // Test simple file upload (bypass Dropzone)
    window.testSimpleUpload = function() {
        // Create a file input
        var fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.accept = 'image/*';
        fileInput.style.display = 'none';

        fileInput.onchange = function() {
            var file = this.files[0];
            if (file) {
                var formData = new FormData();
                formData.append('gallery', file);
                formData.append('event_id', eventId);

                // Test with the simple upload endpoint first
                var testUrl = '<?= base_url('admin/event-gallery/test-simple-upload') ?>';

                // Use XMLHttpRequest instead of jQuery AJAX for better file upload handling
                var xhr = new XMLHttpRequest();
                xhr.open('POST', testUrl, true);

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            alert('Simple upload test response: ' + JSON.stringify(response));
                        } catch (e) {
                            alert('Response parsing error: ' + xhr.responseText);
                        }
                    } else {
                        alert('Upload test failed with status: ' + xhr.status);
                    }
                };

                xhr.onerror = function() {
                    alert('Network error during upload test');
                };

                xhr.send(formData);
            }
        };

        document.body.appendChild(fileInput);
        fileInput.click();
        document.body.removeChild(fileInput);
    };

    // Handle description updates
    $('.description-input').on('blur', function() {
        var id = $(this).data('id');
        var description = $(this).val();

        $.ajax({
            url: '<?= base_url('admin/event-gallery/update-description') ?>',
            type: 'POST',
                         data: {
                 id: id,
                 description: description
             },
            success: function(response) {
                if (response.success) {
                    var textarea = $('[data-id="' + id + '"]');
                    textarea.addClass('border-success');
                    setTimeout(function() {
                        textarea.removeClass('border-success');
                    }, 2000);
                } else {
                    alert('Gagal menyimpan deskripsi');
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat menyimpan deskripsi');
            }
        });
    });
});
</script>

<?= $this->endSection() ?>
