<?php

/**
 * @description Payment confirmation page for manual payment verification
 * @author CodeIgniter Development Team
 * @since 2025-01-15
 * @version 1.0.0
 */

echo $this->extend('da-theme/layout/main'); ?>

<?= $this->section('content') ?>

<!--Page Cover-->
<section class="row page-cover" data-bgimage="<?= base_url('assets/theme/da-theme/images/page-cover/5.jpg') ?>">
    <div class="row m0">
        <div class="container">
            <h2 class="page-title"></h2>
        </div>
    </div>
</section>

<!--Payment Confirmation-->
<section class="row shopping-cart">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <!-- Order Summary -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Order Summary - Invoice #<?= esc($order->invoice_no) ?></h4>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <td><strong>Invoice Date:</strong></td>
                                    <td><?= date('d/m/Y H:i', strtotime($order->invoice_date)) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Method:</strong></td>
                                    <td><?= esc($order->payment_method) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td><strong class="text-success">Rp <?= number_format($order->total_amount, 0, ',', '.') ?></strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Status:</strong></td>
                                    <td>
                                        <?php
                                        $statusColor = [
                                            'pending' => 'warning',
                                            'paid' => 'success',
                                            'failed' => 'danger',
                                            'cancelled' => 'default'
                                        ];
                                        $color = $statusColor[$order->payment_status] ?? 'info';
                                        ?>
                                        <span class="label label-<?= $color ?>"><?= ucfirst($order->payment_status) ?></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Order Items</h4>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($order_details)): ?>
                                        <?php foreach ($order_details as $detail): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= esc($detail->event_title ?: $detail->price_description) ?></strong>
                                                    <?php if ($detail->item_data): ?>
                                                        <?php $itemData = json_decode($detail->item_data, true); ?>
                                                        <?php if (isset($itemData['participant_name'])): ?>
                                                            <br><small class="text-muted">Participant: <?= esc($itemData['participant_name']) ?></small>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= $detail->quantity ?: 1 ?></td>
                                                <td>Rp <?= number_format($detail->unit_price ?: 0, 0, ',', '.') ?></td>
                                                <td><strong>Rp <?= number_format($detail->total_price ?: 0, 0, ',', '.') ?></strong></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No items found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Payment Instructions -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Payment Instructions</h4>
                    </div>
                    <div class="panel-body">
                        <?php if (!empty($platform_details)): ?>
                            <?php foreach ($platform_details as $platform): ?>
                                <div class="payment-platform" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 4px;">
                                    <h5><strong><?= esc($platform->nama) ?></strong></h5>
                                    <?php if (!empty($platform->jenis)): ?>
                                        <p class="text-muted"><?= esc($platform->jenis) ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($platform->nama_rekening) && !empty($platform->nomor_rekening)): ?>
                                        <div style="background: #f5f5f5; padding: 10px; border-radius: 4px; margin: 10px 0;">
                                            <strong>Account Details:</strong><br>
                                            <strong>Name:</strong> <?= esc($platform->nama_rekening) ?><br>
                                            <strong>Number:</strong> <?= esc($platform->nomor_rekening) ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($platform->deskripsi)): ?>
                                        <div class="payment-description">
                                            <strong>Instructions:</strong><br>
                                            <?= nl2br(esc($platform->deskripsi)) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">No payment instructions available.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Payment Confirmation Form -->
                <div class="panel panel-primary" style="border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <div class="panel-heading" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 20px;">
                        <h4 class="panel-title" style="color: white; font-weight: 600; margin: 0;">
                            <i class="fa fa-credit-card" style="margin-right: 10px;"></i>
                            Confirm Your Payment
                        </h4>
                    </div>
                    <div class="panel-body" style="padding: 30px; background: #fafafa;">
                        <?= form_open('sale/process-confirmation', ['id' => 'payment-confirmation-form', 'enctype' => 'multipart/form-data']) ?>
                        <?= form_hidden('order_id', $order->id) ?>
                        <input type="hidden" name="uploaded_files" id="uploaded_files" value="" />
                        
                        <!-- Payment Proof Upload -->
                        <div class="form-group">
                            <label style="font-weight: 600; color: #333; margin-bottom: 10px;">
                                <i class="fa fa-upload" style="margin-right: 8px; color: #667eea;"></i>
                                Upload Payment Proof
                            </label>
                            <div id="payment-dropzone" class="dropzone" style="border: 2px dashed #667eea; border-radius: 10px; background: white; padding: 20px; text-align: center; cursor: pointer; transition: all 0.3s ease;">
                                <div class="dz-message" style="margin: 20px 0;">
                                    <div style="font-size: 48px; color: #667eea; margin-bottom: 15px;">
                                        <i class="fa fa-cloud-upload"></i>
                                    </div>
                                    <h4 style="color: #667eea; font-weight: 600; margin-bottom: 10px;">Drop files here or click to upload</h4>
                                    <p style="color: #999; margin: 0; font-size: 14px;">
                                        Upload your payment receipt, bank transfer proof, or screenshot<br>
                                        <small>Supported formats: JPG, PNG, PDF (Max size: 5MB)</small>
                                    </p>
                                </div>
                            </div>
                            <small class="text-muted" style="display: block; margin-top: 8px;">
                                <i class="fa fa-info-circle"></i> Please upload clear images of your payment receipt or transaction proof
                            </small>
                        </div>

                        <!-- Transaction Reference -->
                        <div class="form-group" style="margin-top: 25px;">
                            <label style="font-weight: 600; color: #333; margin-bottom: 10px;">
                                <i class="fa fa-hashtag" style="margin-right: 8px; color: #667eea;"></i>
                                Transaction Reference / ID
                            </label>
                            <textarea name="payment_proof" class="form-control" rows="3" 
                                      placeholder="Enter transaction ID, reference number, or payment details..." 
                                      required
                                      style="border: 2px solid #e0e6ed; border-radius: 8px; padding: 12px; font-size: 14px; transition: border-color 0.3s ease;"
                                      onfocus="this.style.borderColor='#667eea'"
                                      onblur="this.style.borderColor='#e0e6ed'"></textarea>
                            <small class="text-muted" style="display: block; margin-top: 8px;">
                                <i class="fa fa-lightbulb-o"></i> Include transaction ID, reference number, or any relevant payment details
                            </small>
                        </div>

                        <!-- Additional Notes -->
                        <div class="form-group" style="margin-top: 25px;">
                            <label style="font-weight: 600; color: #333; margin-bottom: 10px;">
                                <i class="fa fa-comment" style="margin-right: 8px; color: #667eea;"></i>
                                Additional Notes <span style="color: #999; font-weight: normal;">(Optional)</span>
                            </label>
                            <textarea name="notes" class="form-control" rows="2" 
                                      placeholder="Any additional information about your payment..."
                                      style="border: 2px solid #e0e6ed; border-radius: 8px; padding: 12px; font-size: 14px; transition: border-color 0.3s ease;"
                                      onfocus="this.style.borderColor='#667eea'"
                                      onblur="this.style.borderColor='#e0e6ed'"></textarea>
                        </div>

                        <!-- Important Notice -->
                        <div class="alert" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 10px; color: white; margin: 25px 0; padding: 20px;">
                            <div style="display: flex; align-items: flex-start;">
                                <div style="margin-right: 15px; font-size: 24px;">
                                    <i class="fa fa-info-circle"></i>
                                </div>
                                <div>
                                    <h5 style="color: white; font-weight: 600; margin: 0 0 8px 0;">Important Information</h5>
                                    <p style="margin: 0; line-height: 1.5; opacity: 0.9;">
                                        After submitting this confirmation, your payment will be reviewed by our team. 
                                        You will be notified via email once the payment is verified and approved.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div style="margin-top: 30px;">
                            <button type="submit" class="btn btn-success btn-block" id="submit-confirmation"
                                    style="background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%); 
                                           border: none; padding: 15px; font-size: 16px; font-weight: 600; 
                                           border-radius: 8px; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.5px;">
                                <i class="fa fa-check" style="margin-right: 10px;"></i>
                                Submit Payment Confirmation
                            </button>
                            
                            <a href="<?= base_url('sale/orders') ?>" class="btn btn-default btn-block" 
                               style="margin-top: 15px; padding: 12px; border: 2px solid #e0e6ed; 
                                      border-radius: 8px; font-weight: 600; transition: all 0.3s ease;"
                               onmouseover="this.style.borderColor='#667eea'; this.style.color='#667eea'"
                               onmouseout="this.style.borderColor='#e0e6ed'; this.style.color='#333'">
                                <i class="fa fa-arrow-left" style="margin-right: 10px;"></i>
                                Back to My Orders
                            </a>
                        </div>
                        
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('css') ?>
<!-- Dropzone CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
<style>
/* Custom Dropzone Styling */
.dropzone {
    border: 2px dashed #667eea !important;
    border-radius: 10px !important;
    background: white !important;
    transition: all 0.3s ease !important;
}

.dropzone:hover {
    border-color: #764ba2 !important;
    background: #f8f9ff !important;
}

.dropzone.dz-drag-hover {
    border-color: #56ab2f !important;
    background: #f0fff4 !important;
}

.dropzone .dz-preview {
    margin: 10px;
    border-radius: 8px;
    border: 1px solid #e0e6ed;
    background: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.dropzone .dz-preview .dz-image {
    border-radius: 8px 8px 0 0;
}

.dropzone .dz-preview .dz-details {
    padding: 10px;
}

.dropzone .dz-preview .dz-progress {
    background: #e0e6ed;
    border-radius: 4px;
    overflow: hidden;
}

.dropzone .dz-preview .dz-progress .dz-upload {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.dropzone .dz-preview.dz-success .dz-success-mark {
    color: #56ab2f;
}

.dropzone .dz-preview.dz-error .dz-error-mark {
    color: #e74c3c;
}

/* Button Hover Effects */
.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2) !important;
}

/* Form Focus Effects */
.form-control:focus {
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- Dropzone JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script>
$(document).ready(function() {
    // Disable Dropzone auto-discovery
    Dropzone.autoDiscover = false;
    
    // Track uploaded files
    var uploadedFiles = [];
    
    // Initialize Dropzone
    var paymentDropzone = new Dropzone("#payment-dropzone", {
        url: "<?= base_url('sale/upload-payment-proof/' . $order->id) ?>",
        paramName: "file",
        maxFilesize: 5, // MB
        acceptedFiles: ".jpg,.jpeg,.png,.pdf",
        addRemoveLinks: true,
        dictDefaultMessage: '',
        maxFiles: 5,
        parallelUploads: 1,
        uploadMultiple: false,
        timeout: 10000, // 10 seconds timeout
        retries: 0,
        headers: {
            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        },
        
        init: function() {
            var dropzone = this;
            
            this.on("sending", function(file, xhr, formData) {
                // Add CSRF token to form data
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
                
                // Show loading state
                $('#submit-confirmation').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Uploading...');
            });
            
            this.on("success", function(file, response) {
                console.log('Raw response received:', response);
                console.log('Response type:', typeof response);
                
                // Skip if response is undefined (duplicate callback)
                if (typeof response === 'undefined') {
                    console.log('Skipping undefined response (duplicate callback)');
                    return;
                }
                
                // Handle both string and object responses
                var parsedResponse;
                if (typeof response === 'string') {
                    try {
                        parsedResponse = JSON.parse(response);
                    } catch (e) {
                        console.error('Failed to parse response:', e);
                        this.emit("error", file, 'Invalid server response');
                        return;
                    }
                } else if (typeof response === 'object' && response !== null) {
                    parsedResponse = response;
                } else {
                    console.error('Invalid response type:', typeof response);
                    this.emit("error", file, 'Invalid server response type');
                    return;
                }
                
                console.log('Parsed response:', parsedResponse);
                
                if (parsedResponse && parsedResponse.success === true) {
                    // Add file to uploaded files array
                    uploadedFiles.push({
                        filename: parsedResponse.filename,
                        original_name: file.name,
                        size: file.size,
                        type: file.type
                    });
                    
                    // Update hidden field
                    $('#uploaded_files').val(JSON.stringify(uploadedFiles));
                    
                    // Add success styling
                    $(file.previewElement).addClass('dz-success');
                    
                    console.log('File uploaded successfully:', parsedResponse);
                    
                    // Re-enable submit button
                    $('#submit-confirmation').prop('disabled', false).html('<i class="fa fa-check"></i> Submit Payment Confirmation');
                } else {
                    console.error('Upload failed - server returned:', parsedResponse);
                    var errorMessage = 'Upload failed';
                    if (parsedResponse && parsedResponse.message) {
                        errorMessage = parsedResponse.message;
                    }
                    this.emit("error", file, errorMessage);
                }
            });
            
            this.on("error", function(file, errorMessage) {
                console.error('Upload error:', errorMessage);
                
                // Show error styling
                $(file.previewElement).addClass('dz-error');
                
                // Re-enable submit button
                $('#submit-confirmation').prop('disabled', false).html('<i class="fa fa-check"></i> Submit Payment Confirmation');
                
                // Show error message with better handling
                var message = 'Upload failed. Please try again.';
                
                if (typeof errorMessage === 'string' && errorMessage.trim() !== '') {
                    message = errorMessage;
                } else if (errorMessage && typeof errorMessage === 'object' && errorMessage.message) {
                    message = errorMessage.message;
                } else if (errorMessage && typeof errorMessage === 'object') {
                    message = JSON.stringify(errorMessage);
                }
                
                console.log('Showing error message:', message);
                alert('Upload Error: ' + message);
            });
            
            this.on("removedfile", function(file) {
                // Remove file from uploaded files array
                uploadedFiles = uploadedFiles.filter(function(uploadedFile) {
                    return uploadedFile.original_name !== file.name;
                });
                
                // Update hidden field
                $('#uploaded_files').val(JSON.stringify(uploadedFiles));
                
                console.log('File removed:', file.name);
            });
            
            this.on("maxfilesexceeded", function(file) {
                alert("Maximum 5 files allowed");
                this.removeFile(file);
            });
        },
        
        // Custom preview template
        previewTemplate: `
            <div class="dz-preview dz-file-preview">
                <div class="dz-image">
                    <img data-dz-thumbnail />
                </div>
                <div class="dz-details">
                    <div class="dz-size"><span data-dz-size></span></div>
                    <div class="dz-filename"><span data-dz-name></span></div>
                </div>
                <div class="dz-progress">
                    <span class="dz-upload" data-dz-uploadprogress></span>
                </div>
                <div class="dz-error-message"><span data-dz-errormessage></span></div>
                <div class="dz-success-mark">
                    <i class="fa fa-check-circle" style="color: #56ab2f; font-size: 20px;"></i>
                </div>
                <div class="dz-error-mark">
                    <i class="fa fa-times-circle" style="color: #e74c3c; font-size: 20px;"></i>
                </div>
                <div class="dz-remove" data-dz-remove style="cursor: pointer; color: #e74c3c; font-size: 12px; text-align: center; padding: 5px;">
                    <i class="fa fa-trash"></i> Remove
                </div>
            </div>
        `
    });
    
    // Form validation and submission
    $('#payment-confirmation-form').on('submit', function(e) {
        var paymentProof = $('textarea[name="payment_proof"]').val().trim();
        
        // Check if either payment proof text or files are provided
        if (!paymentProof && uploadedFiles.length === 0) {
            e.preventDefault();
            alert('Please provide payment proof (either text reference or upload files)');
            $('textarea[name="payment_proof"]').focus();
            return false;
        }
        
        // Check if files are still uploading
        if (paymentDropzone.getQueuedFiles().length > 0 || paymentDropzone.getUploadingFiles().length > 0) {
            e.preventDefault();
            alert('Please wait for all files to finish uploading');
            return false;
        }
        
        if (!confirm('Are you sure you want to submit this payment confirmation? Please make sure all information is correct.')) {
            e.preventDefault();
            return false;
        }
        
        // Show loading state
        $('#submit-confirmation').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');
    });
    
    // Add smooth animations
    $('.form-control').on('focus', function() {
        $(this).parent().addClass('focused');
    }).on('blur', function() {
        $(this).parent().removeClass('focused');
    });
});
</script>
<?= $this->endSection() ?>
