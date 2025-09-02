<?php

/**
 * @description Sale controller for payment confirmation and order management
 * @author CodeIgniter Development Team
 * @since 2025-01-15
 * @version 1.0.0
 */

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransJualModel;
use App\Models\TransJualDetModel;
use App\Models\TransJualPlatModel;
use App\Models\PlatformModel;

class Sale extends BaseController
{
    protected $transJualModel;
    protected $transJualDetModel;
    protected $transJualPlatModel;
    protected $platformModel;
    protected $ionAuth;

    public function __construct()
    {
        $this->transJualModel = new TransJualModel();
        $this->transJualDetModel = new TransJualDetModel();
        $this->transJualPlatModel = new TransJualPlatModel();
        $this->platformModel = new PlatformModel();
        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
    }

    /**
     * Payment confirmation page
     */
    public function confirm($orderId)
    {
        // Check if user is logged in
        if (!$this->ionAuth->loggedIn()) {
            return redirect()->to('auth/login');
        }

        $user = $this->ionAuth->user()->row();

        // Get order details
        $order = $this->transJualModel->where([
            'id' => $orderId,
            'user_id' => $user->id // Ensure user can only access their own orders
        ])->first();

        if (!$order) {
            session()->setFlashdata('error', 'Order not found or access denied');
            return redirect()->to('my/orders');
        }

        // Get order items
        $order_details = $this->transJualDetModel->where('id_penjualan', $orderId)->findAll();

        // Get payment platforms
        $payment_platforms = $this->transJualPlatModel->where('id_penjualan', $orderId)->findAll();

        // Get platform details for payment instructions
        $platformDetails = [];
        foreach ($payment_platforms as $payment) {
            $platform = $this->platformModel->find($payment->id_platform);
            if ($platform) {
                $platformDetails[] = $platform;
            }
        }

        $data = [
            'title' => 'Payment Confirmation - Invoice #' . $order->invoice_no,
            'order' => $order,
            'order_details' => $order_details,
            'payment_platforms' => $payment_platforms,
            'platform_details' => $platformDetails
        ];

        return $this->view('da-theme/sale/confirm', $data);
    }

    /**
     * Process payment confirmation
     */
    public function processConfirmation()
    {
        // Check if user is logged in
        if (!$this->ionAuth->loggedIn()) {
            return redirect()->to('auth/login');
        }

        $orderId = $this->request->getPost('order_id');
        $paymentProof = $this->request->getPost('payment_proof');
        $notes = $this->request->getPost('notes');
        $uploadedFiles = $this->request->getPost('uploaded_files');

        if (!$orderId) {
            session()->setFlashdata('error', 'Invalid order ID');
            return redirect()->to('my/orders');
        }

        $user = $this->ionAuth->user()->row();

        // Verify order belongs to user
        $order = $this->transJualModel->where([
            'id' => $orderId,
            'user_id' => $user->id
        ])->first();

        if (!$order) {
            session()->setFlashdata('error', 'Order not found or access denied');
            return redirect()->to('my/orders');
        }

        // Update order with payment confirmation details
        $updateData = [
            'payment_status' => 'pending', // Set to pending for admin review
            'notes' => $notes,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->transJualModel->update($orderId, $updateData)) {
            session()->setFlashdata('success', 'Payment confirmation submitted successfully. Please wait for admin approval.');
        } else {
            session()->setFlashdata('error', 'Failed to submit payment confirmation');
        }

        return redirect()->to('my/orders');
    }

    /**
     * Upload payment proof files via AJAX
     */
    public function uploadPaymentProof($orderId)
    {
        // Check if user is logged in
        if (!$this->ionAuth->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $user = $this->ionAuth->user()->row();

        // Verify order belongs to user
        $order = $this->transJualModel->where([
            'id' => $orderId,
            'user_id' => $user->id
        ])->first();

        if (!$order) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order not found or access denied'
            ]);
        }

        // Check if file was uploaded
        $file = $this->request->getFile('file');
        
        if (!$file) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No file uploaded'
            ]);
        }

        // Check for upload errors
        if ($file->getError() !== UPLOAD_ERR_OK) {
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => 'File size exceeds server limit',
                UPLOAD_ERR_FORM_SIZE => 'File size exceeds form limit',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
            ];
            
            $errorCode = $file->getError();
            $message = isset($errorMessages[$errorCode]) ? $errorMessages[$errorCode] : 'Unknown upload error';
            
            return $this->response->setJSON([
                'success' => false,
                'message' => $message . ' (Error code: ' . $errorCode . ')'
            ]);
        }

        // Validate file using extension instead of MIME type to avoid temp file issues
        $extension = strtolower($file->getClientExtension());
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($extension, $allowedExtensions)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid file type. Only JPG, PNG, and PDF files are allowed.'
            ]);
        }

        if ($file->getSize() > $maxSize) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'File size too large. Maximum size is 5MB.'
            ]);
        }

        try {
            // Create upload directory structure
            $baseUploadPath = FCPATH . 'file/sale/' . $orderId . '/';
            
            // Debug: Log the path being created
            log_message('info', 'Creating upload directory: ' . $baseUploadPath);
            
            // Create directory if it doesn't exist
            if (!is_dir($baseUploadPath)) {
                if (!mkdir($baseUploadPath, 0755, true)) {
                    log_message('error', 'Failed to create directory: ' . $baseUploadPath);
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to create upload directory: ' . $baseUploadPath
                    ]);
                }
                log_message('info', 'Directory created successfully: ' . $baseUploadPath);
            }

            // Generate unique filename
            $extension = strtolower($file->getClientExtension());
            $newName = uniqid('payment_proof_') . '_' . time() . '.' . $extension;

            // Debug: Log essential info only
            log_message('info', 'Moving file: ' . $newName . ' (Size: ' . $file->getSize() . ' bytes)');

            // Check if temp file exists before moving
            if (!file_exists($file->getTempName())) {
                log_message('error', 'Temporary file does not exist: ' . $file->getTempName());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Temporary file not found. Please try again.'
                ]);
            }

            // Move file to upload directory
            if ($file->move($baseUploadPath, $newName)) {
                log_message('info', 'File moved successfully: ' . $newName);
                // Update database - find existing payment platform record and update foto field
                $paymentPlatform = $this->transJualPlatModel->where('id_penjualan', $orderId)->first();
                
                if ($paymentPlatform) {
                    // Get existing foto data or initialize empty array
                    $existingFoto = !empty($paymentPlatform->foto) ? json_decode($paymentPlatform->foto, true) : [];
                    if (!is_array($existingFoto)) {
                        $existingFoto = [];
                    }

                    // Determine MIME type based on extension to avoid temp file issues
                    $mimeTypes = [
                        'jpg' => 'image/jpeg',
                        'jpeg' => 'image/jpeg',
                        'png' => 'image/png',
                        'pdf' => 'application/pdf'
                    ];
                    $mimeType = isset($mimeTypes[$extension]) ? $mimeTypes[$extension] : 'application/octet-stream';

                    // Add new file to foto array
                    $existingFoto[] = [
                        'filename' => $newName,
                        'original_name' => $file->getClientName(),
                        'size' => $file->getSize(),
                        'type' => $mimeType,
                        'extension' => $extension,
                        'uploaded_at' => date('Y-m-d H:i:s')
                    ];

                    // Update the database record
                    $updateResult = $this->transJualPlatModel->update($paymentPlatform->id, [
                        'foto' => json_encode($existingFoto),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                    // Log result
                    log_message('info', 'DB update: ' . ($updateResult ? 'SUCCESS' : 'FAILED') . ' for platform ID: ' . $paymentPlatform->id);

                    if ($updateResult) {
                        $response = [
                            'success' => true,
                            'message' => 'File uploaded successfully',
                            'filename' => $newName,
                            'original_name' => $file->getClientName(),
                            'path' => 'file/sale/' . $orderId . '/' . $newName
                        ];
                        return $this->response->setJSON($response);
                    } else {
                        // Delete uploaded file if database update fails
                        unlink($baseUploadPath . $newName);
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Failed to update database record'
                        ]);
                    }
                } else {
                    // Delete uploaded file if no payment platform record found
                    unlink($baseUploadPath . $newName);
                    log_message('error', 'Payment platform record not found for order ID: ' . $orderId);
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Payment platform record not found'
                    ]);
                }
            } else {
                log_message('error', 'Failed to move file from: ' . $file->getTempName() . ' to: ' . $baseUploadPath . $newName);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to save file to: ' . $baseUploadPath . $newName
                ]);
            }

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Upload error: ' . $e->getMessage()
            ]);
        }
    }
}
