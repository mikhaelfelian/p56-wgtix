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
use App\Models\CartModel;
use App\Models\PesertaModel;
use App\Models\KategoriModel;
use App\Models\KelompokPesertaModel;
use App\Libraries\InvoicePdf;
use App\Libraries\TicketPdf;
use App\Libraries\DotMatrixInvoicePdf;

class Sale extends BaseController{
    protected $transJualModel;
    protected $transJualDetModel;
    protected $transJualPlatModel;
    protected $platformModel;
    protected $cartModel;
    protected $pesertaModel;
    protected $kategoriModel;
    protected $kelompokModel;
    protected $ionAuth;
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->transJualModel = new TransJualModel();
        $this->transJualDetModel = new TransJualDetModel();
        $this->transJualPlatModel = new TransJualPlatModel();
        $this->platformModel = new PlatformModel();
        $this->cartModel = new CartModel();
        $this->pesertaModel = new PesertaModel();
        $this->kategoriModel = new KategoriModel();
        $this->kelompokModel = new KelompokPesertaModel();
        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
        $this->db = \Config\Database::connect();
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
            return redirect()->to('sale/orders')->with('toastr', [
                'type' => 'error', 
                'message' => 'Order not found or access denied'
            ]);
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
            return redirect()->to('sale/orders')->with('toastr', [
                'type' => 'error', 
                'message' => 'Invalid order ID'
            ]);
        }

        $user = $this->ionAuth->user()->row();

        // Verify order belongs to user
        $order = $this->transJualModel->where([
            'id' => $orderId,
            'user_id' => $user->id
        ])->first();

        if (!$order) {
            return redirect()->to('sale/orders')->with('toastr', [
                'type' => 'error', 
                'message' => 'Order not found or access denied'
            ]);
        }

        // Update order with payment confirmation details
        $updateData = [
            'payment_status' => 'pending', // Set to pending for admin review
            'notes' => $notes,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->transJualModel->update($orderId, $updateData)) {
            return redirect()->to('sale/orders')->with('toastr', [
                'type' => 'success', 
                'message' => 'Payment confirmation submitted successfully. Please wait for admin approval.'
            ]);
        } else {
            return redirect()->to('sale/orders')->with('toastr', [
                'type' => 'error', 
                'message' => 'Failed to submit payment confirmation'
            ]);
        }
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

    /**
     * Store order - Process checkout (moved from Cart controller)
     */
    public function store()
    {
        // Get order data from form
        $orderDataJson = $this->request->getPost('order_data');
        
        if (empty($orderDataJson)) {
            die('No order data received');
        }
        
        // Decode JSON data
        $data = json_decode($orderDataJson, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            die('Invalid order data format');
        }
        
        // Validate required fields
        if (empty($data['no_nota']) || empty($data['cart_data']) || empty($data['subtotal'])) {
            die('Missing required order information');
        }
        
        try {
            // Start database transaction
            $this->db->transStart();
            
            // 1. Save to tbl_trans_jual (header)
            $userId    = $this->ionAuth->loggedIn() ? $this->ionAuth->user()->row()->id : null;
            $sessionId = $userId ? null : session_id();
            
            $headerData = [
                'invoice_no'      => $data['no_nota'],
                'user_id'         => $userId,
                'session_id'      => $sessionId,
                'invoice_date'    => date('Y-m-d H:i:s'),
                'total_amount'    => $data['subtotal'],
                'payment_status'  => 'pending',
                'payment_method'  => $data['cart_payments'][0]['platform_id'], // Since we can have multiple payment platforms
                'notes'           => 'Order from cart checkout',
                'status'          => 'active',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s')
            ];
            
            $headerId = $this->transJualModel->insert($headerData);
            $lastId = $this->db->insertID();
            
            // 2. Save to tbl_trans_jual_det (detail items)
            foreach ($data['participant'] as $item) {
                $detailData = [
                    'id_penjualan'      => $lastId,
                    'event_id'          => $item['event_id']         ?? 0,
                    'price_id'          => $item['price_id']         ?? 0,
                    'event_title'       => $item['event_title']      ?? '',
                    'price_description' => $item['price_description']?? '',
                    'quantity'          => $item['quantity']         ?? 1,
                    'unit_price'        => $item['unit_price']       ?? 0,
                    'total_price'       => $item['total_price']      ?? 0,
                    'item_data'         => json_encode($item),
                    'created_at'        => date('Y-m-d H:i:s'),
                    'updated_at'        => date('Y-m-d H:i:s')
                ];
                
                $this->transJualDetModel->insert($detailData);
            }
            
            // 3. Save to tbl_trans_jual_plat (payment platforms)
            if (!empty($data['cart_payments'])) {
                foreach ($data['cart_payments'] as $payment) {
                    // After saving to tbl_trans_jual_plat, check payment platform requirements
                    // Ensure platform_id exists
                    if (!isset($payment['platform_id'])) {
                        continue; // Skip this payment
                    }
                    
                    $platform = $this->platformModel->find($payment['platform_id']);
                    $platData = [
                        'id_penjualan'  => $lastId,
                        'id_platform'   => $payment['platform_id'],
                        'no_nota'       => $data['no_nota'],
                        'platform'      => strtolower($platform->jenis)       ?? '',
                        'nominal'       => $payment['amount']         ?? 0,
                        'keterangan'    => $payment['note']           ?? '',
                        'created_at'    => date('Y-m-d H:i:s'),
                        'updated_at'    => date('Y-m-d H:i:s'),
                    ];
                    
                    $this->transJualPlatModel->insert($platData);
                }
            }

            // Complete transaction
            $this->db->transComplete();
            
            if ($this->db->transStatus() === false) {
                throw new \Exception('Database transaction failed');
            }
            
            // Clear cart after successful order
            $this->cartModel->clearCart($userId, $sessionId);

            // After success order, use KamupediaWA to send notification
            // Load KamupediaWA library
            try {
                $kamupediaWA = new \App\Libraries\KamupediaWA();

                // Get customer information
                $customerPhone = $this->ionAuth->user()->phone ?? null;
                $customerName = $this->ionAuth->user()->first_name ?? $this->ionAuth->user()->last_name ?? null;

                // Get the order just created
                $order = $this->transJualModel->find($lastId);

                // Get order details from tbl_trans_jual_det
                $orderDetails = [];
                if (isset($order->id)) {
                    $orderDetailsModel = new \App\Models\TransJualDetModel();
                    $orderDetails = $orderDetailsModel->where('id_trans', $order->id)->findAll();
                }

                // Send order notification (since payment is still pending)
                $result = $kamupediaWA->sendOrderNotification($order, 'active', $customerPhone, $customerName, $orderDetails);

                // Log result
                if (isset($result['success']) && $result['success']) {
                    log_message('info', 'WhatsApp notification sent successfully');
                } else {
                    log_message('error', 'WhatsApp notification failed: ' . ($result['message'] ?? 'Unknown error'));
                }
            } catch (\Throwable $waEx) {
                log_message('error', 'WhatsApp notification exception: ' . $waEx->getMessage());
            }

            // Send email berhasil order via SMTP
            try {
                $email = \Config\Services::email();

                // Get SMTP config from environment
                $smtpHost = getenv('smtp_host');
                $smtpPort = getenv('smtp_port');
                $smtpUser = getenv('smtp_user');
                $smtpPass = getenv('smtp_pass');

                $emailConfig = [
                    'protocol'  => 'smtp',
                    'SMTPHost'  => $smtpHost,
                    'SMTPPort'  => $smtpPort,
                    'SMTPUser'  => $smtpUser,
                    'SMTPPass'  => $smtpPass,
                    'mailType'  => 'html',
                    'charset'   => 'utf-8',
                    'SMTPTimeout' => 10,
                    'SMTPCrypto' => 'tls',
                ];
                $email->initialize($emailConfig);

                // Get user email
                $userEmail = null;
                if ($this->ionAuth->loggedIn()) {
                    $userEmail = $this->ionAuth->user()->row()->email ?? null;
                }
                if ($userEmail) {
                    $email->setTo($userEmail);
                    $email->setFrom($smtpUser, $this->pengaturan->judul);
                    $email->setSubject('Order Berhasil - ' . $data['no_nota']);

                    // Compose email message
                    $message = '<h3>Terima kasih, pesanan Anda berhasil dibuat!</h3>';
                    $message .= '<p>Nomor Invoice: <b>' . htmlspecialchars($data['no_nota']) . '</b></p>';
                    $message .= '<p>Total: <b>Rp ' . number_format($data['subtotal'], 0, ',', '.') . '</b></p>';
                    $message .= '<p>Status Pembayaran: <b>Pending</b></p>';
                    $message .= '<p>Silakan lakukan pembayaran sesuai instruksi di halaman order Anda.</p>';
                    $message .= '<br><small>Email ini dikirim otomatis oleh sistem "' . $this->pengaturan->judul . '".</small>';

                    $email->setMessage($message);

                    if ($email->send()) {
                        log_message('info', 'Order success email sent to ' . $userEmail);
                    } else {
                        log_message('error', 'Failed to send order success email: ' . $email->printDebugger(['headers']));
                    }
                } else {
                    log_message('error', 'User email not found, cannot send order success email.');
                }
            } catch (\Throwable $mailEx) {
                log_message('error', 'Order success email exception: ' . $mailEx->getMessage());
            }

            // Check if payment confirmation or gateway is needed
            if (!empty($data['cart_payments'])) {
                foreach ($data['cart_payments'] as $payment) {
                    $platformId = $payment['platform_id'];
                    
                    // If platform is not ID 1, check platform requirements
                    if ($platformId != '1') {
                        $platform = $this->platformModel->find($platformId);
                        
                        if ($platform) {
                            // If status_system == 0 and status_gateway == 1, throw into tripay payment gateway
                            if ($platform->status_system == 0 && $platform->status_gateway == 1 && $platform->jenis == 'tripay') {
                                // Redirect to tripay payment gateway page
                                return redirect()->to(base_url('sale/tripay/' . $lastId));
                            }

                            // If status_system == 0 and status_gateway == 0, manual confirmation
                            if ($platform->status_system == 0 && $platform->status_gateway == 0) {
                                // Redirect to payment confirmation page
                                return redirect()->to(base_url('sale/confirm/' . $lastId));
                            }
                        }
                    }
                }
            }

            // Default redirect to orders page
            return redirect()->to(base_url('sale/orders'));            
        } catch (\Exception $e) {
            $this->db->transRollback();
            
            $errorData = [
                'error' => $e->getMessage(),
                'original_data' => $data,
                'transaction_status' => 'FAILED'
            ];
            
            return redirect()->to(base_url('sale/orders'))->with('toastr', [
                'type' => 'error', 
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * View order details
     */
    public function detail($invoiceId)
    {
        if (!$this->ionAuth->loggedIn()) {
            return redirect()->to('auth/login');
        }

        $user = $this->ionAuth->user()->row();
        $userGroups = $this->ionAuth->getUsersGroups($user->id)->getRow();

        // Get order header - users can only see their own orders
        $order = $this->transJualModel->where([
            'user_id' => $user->id,
            'id' => $invoiceId
        ])->first();

        if (!$order) {
            return redirect()->to('sale/orders')->with('toastr', [
                'type' => 'error', 
                'message' => 'Order not found or access denied'
            ]);
        }

        // Get order details
        $orderDetails = $this->transJualDetModel->where('id_penjualan', $invoiceId)->findAll();

        // Get payment platforms
        $paymentPlatforms = $this->transJualPlatModel->where('id_penjualan', $invoiceId)->findAll();

        // Get payment records (for backward compatibility or additional info)
        $paymentRecords = $this->platformModel->where('id', $order->payment_method)->first();

        $data = [
            'title'             => 'Order Details - ' . $order->invoice_no,
            'order'             => $order,
            'order_details'     => $orderDetails,
            'payment_platforms' => $paymentPlatforms,
            'payment_records'   => $paymentRecords,
        ];

        return $this->view('da-theme/transaksi/sale/detail', $data);
    }

    /**
     * Display user orders (moved from Transaksi namespace)
     */
    public function orders($status = 'all')
    {
        // Check if user is logged in
        if (!$this->ionAuth->loggedIn()) {
            return redirect()->to('auth/login');
        }

        // Get current user info
        $user = $this->ionAuth->user()->row();
        $userGroups = $this->ionAuth->getUsersGroups($user->id)->getRow();

        // Build query conditions for the model
        $this->transJualModel->where('user_id', $user->id);

        // Filter by payment status if specified
        if ($status !== 'all') {
            $validStatuses = ['pending', 'paid', 'failed', 'cancelled'];
            if (in_array($status, $validStatuses)) {
                $this->transJualModel->where('payment_status', $status);
            }
        }

        // Order by latest first and get with pagination
        $this->transJualModel->orderBy('invoice_date', 'DESC');
        
        // Get orders with pagination using model
        $orders = $this->transJualModel->paginate(20);
        $pager = $this->transJualModel->pager;

        // Get statistics for different statuses for current user
        $stats = [
            'all' => $this->getOrderCount($user->id),
            'pending' => $this->getOrderCount($user->id, 'pending'),
            'paid' => $this->getOrderCount($user->id, 'paid'),
            'failed' => $this->getOrderCount($user->id, 'failed'),
            'cancelled' => $this->getOrderCount($user->id, 'cancelled'),
        ];

        $data = [
            'title' => 'My Orders',
            'orders' => $orders,
            'pager' => $pager,
            'current_status' => $status,
            'stats' => $stats,
            'user' => $user
        ];

        return $this->view('da-theme/transaksi/sale/orders', $data);
    }

    /**
     * Print dot matrix invoice
     */
    public function print_dm($invoiceId)
    {
        $user = $this->ionAuth->user()->row();

        // Get order details and ensure user can only access their own orders
        $order = $this->transJualModel->where([
            'id' => $invoiceId,
            'user_id' => $user->id // Security check
        ])->first();
        
        if (!$order) {
            return redirect()->to('sale/orders')->with('error', 'Order not found or access denied');
        }

        // Get order items
        $orderDetails = $this->transJualDetModel->where('id_penjualan', $invoiceId)->findAll();

        // Get payment platforms
        $paymentPlatforms = $this->transJualPlatModel->where('id_penjualan', $invoiceId)->findAll();

        try {
            // Create dot matrix PDF instance
            $pdf = new DotMatrixInvoicePdf($order, $orderDetails, $paymentPlatforms, $user, $this->pengaturan);
            
            // Generate PDF content
            $pdfContent = $pdf->generateInvoice();
            
            // Set headers for PDF download
            $filename = 'DotMatrix_Invoice_' . $order->invoice_no . '_' . date('Y-m-d') . '.pdf';
            
            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->setHeader('Cache-Control', 'private, max-age=0, must-revalidate')
                ->setHeader('Pragma', 'public')
                ->setBody($pdfContent);
                
        } catch (\Exception $e) {
            log_message('error', 'Dot matrix PDF generation failed for invoice ' . $invoiceId . ': ' . $e->getMessage());
            return redirect()->to('sale/orders')->with('toastr', [
                'type' => 'error', 
                'message' => 'Failed to generate dot matrix invoice: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Print PDF ticket - single ticket or all tickets
     */
    public function print_pdf_ticket($invoiceId = null)
    {
        $data = [
            'Pengaturan'    => $this->pengaturan,
            'items'         => $this->transJualDetModel->where('id_penjualan', $invoiceId)->findAll(),
        ];

        return view('/events/ticket/ticket', $data);
    }

    // public function print_pdf_ticket($invoiceId = null)
    // {
    //     // Check if user is logged in
    //     if (!$this->ionAuth->loggedIn()) {
    //         return redirect()->to('auth/login');
    //     }

    //     $user = $this->ionAuth->user()->row();

    //     // If no invoiceId provided, show all tickets for user
    //     if ($invoiceId === null) {
    //         return $this->printAllUserTickets();
    //     }

    //     // Get order and ensure user can only access their own orders
    //     $order = $this->transJualModel->where([
    //         'id' => $invoiceId,
    //         'user_id' => $user->id // Security check
    //     ])->first();
        
    //     if (!$order) {
    //         session()->setFlashdata('error', 'Order not found or access denied');
    //         return redirect()->to('sale/orders');
    //     }

    //     // Get all order details (each represents a participant/ticket)
    //     $orderDetails = $this->transJualDetModel->where('id_penjualan', $invoiceId)->findAll();
        
    //     if (empty($orderDetails)) {
    //         session()->setFlashdata('error', 'No tickets found for this order');
    //         return redirect()->to('sale/orders');
    //     }

    //     try {
    //         // Create a master PDF to combine all individual tickets
    //         $masterPdf = new \TCPDF('L', 'mm', array(210, 148.5), true, 'UTF-8', false);
    //         $masterPdf->SetCreator('WGTIX System');
    //         $masterPdf->SetAuthor('WGTIX Event Management');
    //         $masterPdf->SetTitle('Event Tickets - Invoice #' . $order->invoice_no);
    //         $masterPdf->SetMargins(0, 0, 0);
    //         $masterPdf->SetAutoPageBreak(FALSE);

    //         // Generate individual ticket for each order detail/participant
    //         foreach ($orderDetails as $index => $orderDetail) {
    //             // Event info
    //             $event = (object)[
    //                 'title' => $orderDetail->event_title ?: 'Event',
    //                 'date' => $order->invoice_date,
    //                 'location' => 'Event Venue'
    //             ];

    //             // Add page to master PDF
    //             $masterPdf->AddPage();
                
    //             // Draw ticket content
    //             $this->drawTicketInMasterPdf($masterPdf, $orderDetail, $order, $user, $event, $index + 1, count($orderDetails));
    //         }
            
    //         // Set headers for PDF download
    //         $filename = 'Tickets_' . $order->invoice_no . '_' . date('Y-m-d') . '.pdf';
            
    //         return $this->response
    //             ->setHeader('Content-Type', 'application/pdf')
    //             ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
    //             ->setHeader('Cache-Control', 'private, max-age=0, must-revalidate')
    //             ->setHeader('Pragma', 'public')
    //             ->setBody($masterPdf->Output('S'));
                
    //     } catch (\Exception $e) {
    //         log_message('error', 'Tickets generation failed for invoice ' . $invoiceId . ': ' . $e->getMessage());
    //         session()->setFlashdata('error', 'Failed to generate tickets: ' . $e->getMessage());
    //         return redirect()->to('sale/orders');
    //     }
    // }

    /**
     * Print all tickets for the current user
     */
    private function printAllUserTickets()
    {
        $user = $this->ionAuth->user()->row();

        // Get all paid orders for the user
        $orders = $this->transJualModel->where([
            'user_id' => $user->id,
            'payment_status' => 'paid'
        ])->orderBy('invoice_date', 'DESC')->findAll();

        if (empty($orders)) {
            return redirect()->to('sale/orders')->with('toastr', [
                'type' => 'error', 
                'message' => 'No paid orders found'
            ]);
        }

        try {
            // Create a master PDF for all tickets
            $masterPdf = new \TCPDF('L', 'mm', array(210, 148.5), true, 'UTF-8', false);
            $masterPdf->SetCreator('WGTIX System');
            $masterPdf->SetAuthor('WGTIX Event Management');
            $masterPdf->SetTitle('All Event Tickets - ' . $user->first_name . ' ' . $user->last_name);
            $masterPdf->SetMargins(0, 0, 0);
            $masterPdf->SetAutoPageBreak(FALSE);

            $totalTickets = 0;
            $ticketNumber = 0;

            // First pass: count total tickets
            foreach ($orders as $order) {
                $orderDetails = $this->transJualDetModel->where('id_penjualan', $order->id)->findAll();
                $totalTickets += count($orderDetails);
            }

            // Second pass: generate tickets
            foreach ($orders as $order) {
                $orderDetails = $this->transJualDetModel->where('id_penjualan', $order->id)->findAll();
                
                foreach ($orderDetails as $orderDetail) {
                    $ticketNumber++;
                    
                    // Event info
                    $event = (object)[
                        'title' => $orderDetail->event_title ?: 'Event',
                        'date' => $order->invoice_date,
                        'location' => 'Event Venue'
                    ];

                    // Add page to master PDF
                    $masterPdf->AddPage();
                    
                    // Draw ticket content
                    $this->drawTicketInMasterPdf($masterPdf, $orderDetail, $order, $user, $event, $ticketNumber, $totalTickets);
                }
            }
            
            // Set headers for PDF download
            $filename = 'All_Tickets_' . $user->first_name . '_' . $user->last_name . '_' . date('Y-m-d') . '.pdf';
            
            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->setHeader('Cache-Control', 'private, max-age=0, must-revalidate')
                ->setHeader('Pragma', 'public')
                ->setBody($masterPdf->Output('S'));
                
        } catch (\Exception $e) {
            log_message('error', 'All user tickets generation failed: ' . $e->getMessage());
            return redirect()->to('sale/orders')->with('toastr', [
                'type' => 'error', 
                'message' => 'Failed to generate all tickets: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Draw individual ticket content in the master PDF
     */
    private function drawTicketInMasterPdf($pdf, $orderDetail, $order, $user, $event, $ticketNumber, $totalTickets)
    {
        // Ticket background and border
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetDrawColor(0, 123, 255);
        $pdf->SetLineWidth(1);
        $pdf->Rect(5, 5, 200, 138.5, 'DF');
        
        // Header background
        $pdf->SetFillColor(0, 123, 255);
        $pdf->Rect(5, 5, 200, 25, 'F');
        
        // Decorative side strip
        $pdf->SetFillColor(240, 240, 240);
        $pdf->Rect(155, 30, 50, 113.5, 'F');
        
        // Header content
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetXY(10, 8);
        $pdf->Cell(100, 8, 'WGTIX Event Management', 0, 1, 'L');
        
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetXY(10, 16);
        $pdf->Cell(100, 6, 'EVENT TICKET', 0, 1, 'L');
        
        // Ticket number
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetXY(120, 8);
        $pdf->Cell(80, 8, 'TICKET #' . str_pad($orderDetail->id, 6, '0', STR_PAD_LEFT), 0, 1, 'R');
        
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetXY(120, 16);
        $pdf->Cell(80, 6, 'Invoice: ' . $order->invoice_no, 0, 1, 'R');
        
        // Event info
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->SetXY(10, 35);
        $pdf->Cell(140, 12, $event->title, 0, 1, 'L');
        
        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetXY(10, 50);
        $pdf->Cell(140, 6, 'Date: ' . date('d F Y', strtotime($order->invoice_date)), 0, 1, 'L');
        $pdf->SetXY(10, 58);
        $pdf->Cell(140, 6, 'Time: 09:00 - 17:00 WIB', 0, 1, 'L');
        $pdf->SetXY(10, 66);
        $pdf->Cell(140, 6, 'Location: ' . $event->location, 0, 1, 'L');
        $pdf->SetXY(10, 74);
        $pdf->Cell(140, 6, 'Category: ' . ($orderDetail->price_description ?: 'General'), 0, 1, 'L');
        
        // Participant info
        $itemData = json_decode($orderDetail->item_data, true) ?: [];
        if (isset($itemData['participant_name'])) {
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->SetXY(10, 85);
            $pdf->Cell(140, 6, 'Participant:', 0, 1, 'L');
            $pdf->SetFont('helvetica', '', 11);
            $pdf->SetXY(10, 93);
            $pdf->Cell(140, 6, $itemData['participant_name'], 0, 1, 'L');
            
            if (isset($itemData['participant_number'])) {
                $pdf->SetXY(10, 101);
                $pdf->Cell(140, 6, 'Participant #: ' . $itemData['participant_number'], 0, 1, 'L');
            }
        }
        
        // Customer info
        if ($user) {
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetXY(10, 115);
            $pdf->Cell(140, 6, 'Ticket Holder:', 0, 1, 'L');
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetXY(10, 123);
            $pdf->Cell(140, 5, $user->first_name . ' ' . $user->last_name, 0, 1, 'L');
            $pdf->SetXY(10, 130);
            $pdf->Cell(140, 5, $user->email, 0, 1, 'L');
        }
        
        // Price info
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetXY(10, 138);
        $pdf->Cell(140, 5, 'Price: Rp ' . number_format($orderDetail->total_price, 0, ',', '.'), 0, 1, 'L');
        
        // QR Code section
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetXY(160, 35);
        $pdf->Cell(40, 6, 'SCAN TO VERIFY', 0, 1, 'C');
        
        // Display QR code if available
        if (!empty($orderDetail->qrcode)) {
            $qrPath = FCPATH . 'file/sale/' . $order->id . '/qrcode/' . $orderDetail->qrcode;
            
            if (file_exists($qrPath)) {
                $pdf->Image($qrPath, 165, 45, 30, 30, 'PNG');
            } else {
                // QR placeholder
                $pdf->SetFillColor(240, 240, 240);
                $pdf->Rect(165, 45, 30, 30, 'F');
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetXY(165, 58);
                $pdf->Cell(30, 4, 'QR CODE', 0, 1, 'C');
                $pdf->SetXY(165, 63);
                $pdf->Cell(30, 4, 'PENDING', 0, 1, 'C');
            }
        } else {
            // No QR code
            $pdf->SetFillColor(240, 240, 240);
            $pdf->Rect(165, 45, 30, 30, 'F');
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetXY(165, 58);
            $pdf->Cell(30, 4, 'QR CODE', 0, 1, 'C');
            $pdf->SetXY(165, 63);
            $pdf->Cell(30, 4, 'NOT READY', 0, 1, 'C');
        }
        
        // Ticket ID and count
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetXY(160, 80);
        $pdf->Cell(40, 4, 'ID: ' . $orderDetail->id, 0, 1, 'C');
        $pdf->SetXY(160, 85);
        $pdf->Cell(40, 4, 'Ticket ' . $ticketNumber . ' of ' . $totalTickets, 0, 1, 'C');
        
        // Terms and conditions
        $pdf->SetFont('helvetica', '', 7);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->SetXY(160, 95);
        $pdf->MultiCell(40, 3, "Terms:\n• Non-transferable\n• Valid for one entry\n• No refund\n• Present with ID", 0, 'L');
        
        // Payment status
        $pdf->SetFont('helvetica', 'B', 8);
        if ($order->payment_status === 'paid') {
            $pdf->SetTextColor(0, 150, 0);
            $pdf->SetXY(160, 130);
            $pdf->Cell(40, 4, 'PAID', 0, 1, 'C');
        } else {
            $pdf->SetTextColor(200, 0, 0);
            $pdf->SetXY(160, 130);
            $pdf->Cell(40, 4, 'UNPAID', 0, 1, 'C');
        }
        
        // Reset colors
        $pdf->SetTextColor(0, 0, 0);
    }

    /**
     * Get order count by status
     */
    private function getOrderCount($userId = null, $status = null)
    {
        $builder = $this->transJualModel->builder();
        
        if ($userId) {
            $builder->where('user_id', $userId);
        }
        
        if ($status) {
            $builder->where('payment_status', $status);
        }
        
        return $builder->countAllResults();
    }

    /**
     * Handle Tripay Payment Gateway (moved from Cart controller)
     * @param int $orderId
     */
    public function pg_tripay($orderId = null)
    {
        // Validate orderId
        if (empty($orderId) || !is_numeric($orderId)) {
            return $this->response->setStatusCode(400)->setBody('Invalid order ID');
        }

        // Fetch order data
        $order = $this->transJualModel->find($orderId);
        if (!$order) {
            return $this->response->setStatusCode(404)->setBody('Order not found');
        }

        // Fetch payment platform info
        $platform = $this->platformModel->find($order->payment_method);
        if (!$platform || !isset($platform->status_gateway) || $platform->status_gateway != 1) {
            return $this->response->setStatusCode(400)->setBody('Invalid payment gateway');
        }

        // Prepare Tripay API request
        $tripayApiKey = getenv('TRIPAY_API_KEY');
        $tripayMerchantCode = getenv('TRIPAY_MERCHANT_CODE');
        $tripayPrivateKey = getenv('TRIPAY_PRIVATE_KEY');
        $tripayApiUrl = getenv('TRIPAY_BASE_URL') . '/transaction/create'; // Use Tripay demo API

        if (!$tripayApiKey || !$tripayMerchantCode || !$tripayPrivateKey) {
            return $this->response->setStatusCode(500)->setBody('Tripay configuration missing');
        }

        // Prepare payload
        $customerName = 'Guest';
        $customerEmail = '';
        if (!empty($order->user_id)) {
            $user = $this->ionAuth->user($order->user_id)->row();
            if ($user) {
                $customerName = $user->first_name ?? 'Guest';
                $customerEmail = $user->email ?? '';
            }
        }

        // Tripay expects the signature to be generated as:
        // hash_hmac('sha256', merchant_code + merchant_ref + amount, private_key)
        // All as string, amount as integer or float but no formatting
        $merchantRef = (string)$order->invoice_no;
        $amount = (float)$order->total_amount;
        // Remove any formatting from amount (e.g. 10000.00 => 10000)
        if (intval($amount) == $amount) {
            $amountForSignature = (string)intval($amount);
        } else {
            $amountForSignature = (string)$amount;
        }
        $signatureString = $tripayMerchantCode . $merchantRef . $amountForSignature;
        $signature = hash_hmac('sha256', $signatureString, $tripayPrivateKey);

        $payload = [
            'method'         => $platform->gateway_kode, // e.g. 'BRIVA', 'BNIVA', etc.
            'merchant_ref'   => $merchantRef,
            'amount'         => $amount,
            'customer_name'  => $customerName,
            'customer_email' => $customerEmail,
            'order_items'    => [],
            'callback_url'   => base_url('sale/tripay/callback'),
            'return_url'     => base_url('sale/orders'),
            'expired_time'   => time() + 86400, // 1 day from now
            'signature'      => $signature
        ];

        // Get order items
        $orderItems = $this->transJualDetModel->where('id_penjualan', $orderId)->findAll();
        if ($orderItems && is_array($orderItems)) {
            foreach ($orderItems as $item) {

                $event = json_decode($item->item_data, true);
                
                // Support both object and array, formatted for clarity
                if (is_object($item)) {
                    $eventId    = $item->event_id;
                    $eventTitle = $item->event_title . ' - ' . ($event['participant_name'] ?? '');
                    $unitPrice  = $item->unit_price;
                    $quantity   = $item->quantity;
                } else {
                    $eventId    = isset($item['event_id']) ? $item['event_id'] : '';
                    $eventTitle = isset($item['event_title']) ? $item['event_title'] . ' - ' . ($event['participant_name'] ?? '') : '';
                    $unitPrice  = isset($item['unit_price']) ? $item['unit_price'] : 0;
                    $quantity   = isset($item['quantity']) ? $item['quantity'] : 1;
                }

                $payload['order_items'][] = [
                    'sku'        => $eventId,
                    'name'       => $eventTitle,
                    'price'      => (float)$unitPrice,
                    'quantity'   => (int)$quantity,
                    'product_url'=> base_url('events/' . $eventId)
                ];
            }
        }

        // Send request to Tripay
        $client = \Config\Services::curlrequest();
        try {
            $response = $client->post($tripayApiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $tripayApiKey,
                    'Accept'        => 'application/json'
                ],
                'form_params' => $payload,
                'http_errors' => false
            ]);
            $result = json_decode($response->getBody(), true);

            // Debugging: log the payload and Tripay response if signature error
            if (isset($result['message']) && stripos($result['message'], 'signature') !== false) {
                log_message('error', 'Tripay Signature Error. Payload: ' . json_encode($payload) . ' Response: ' . $response->getBody());
            }

            if (
                isset($result['success']) && $result['success'] &&
                isset($result['data']['checkout_url'])
            ) {
                // Optionally, save Tripay reference to order
                $this->transJualModel->update($orderId, [
                    'tripay_ref' => isset($result['data']['reference']) ? $result['data']['reference'] : null,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                
                // Redirect to Tripay checkout
                return redirect()->to($result['data']['checkout_url']);
            } else {
                $errorMsg = isset($result['message']) ? $result['message'] : 'Failed to create Tripay transaction';
                return $this->response->setStatusCode(500)->setBody('Tripay Error: ' . $errorMsg);
            }
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setBody('Tripay Exception: ' . $e->getMessage());
        }
    }

    /**
     * Handle Tripay Payment Gateway Callback (moved from Cart controller)
     */
    public function pg_tripay_callback()
    {
        // Set JSON response header
        $this->response->setContentType('application/json');
        
        // Only accept POST requests
        if (!$this->request->is('post')) {
            return $this->response->setStatusCode(405)->setJSON(['success' => false, 'message' => 'Method Not Allowed']);
        }

        // Read raw POST body (JSON)
        $json = file_get_contents('php://input');
        
        // Check if we have any data
        if (empty($json)) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Empty request body']);
        }
        
        $data = json_decode($json, true);
        
        // Check JSON decode error
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Invalid JSON']);
        }

        // Validate required fields
        if (
            !$data ||
            !isset($data['merchant_ref']) ||
            !isset($data['status']) ||
            !isset($data['payment_method']) ||
            !isset($data['payment_method_code']) ||
            !isset($data['total_amount'])
        ) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Invalid callback data']);
        }

        // Find order by merchant_ref (invoice_no)
        $order = $this->transJualModel->where('invoice_no', $data['merchant_ref'])->first();
        if (!$order) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Order not found']);
        }

        // Update payment status based on Tripay status
        $status = $data['status'];
        $update = [];

        // Normalize status to uppercase for comparison
        $statusUpper = strtoupper(trim($status));

        // Default values
        $update['payment_status'] = strtolower($statusUpper);
        $update['status'] = $order->status; // fallback to current status

        // Map Tripay status to our enum: 'pending','paid','failed','cancelled'
        if ($statusUpper === 'PAID') {
            $update['payment_status'] = 'paid';
            $update['status'] = 'paid';
        } elseif ($statusUpper === 'UNPAID' || $statusUpper === 'PENDING') {
            $update['payment_status'] = 'pending';
            $update['status'] = 'pending';
        } elseif ($statusUpper === 'EXPIRED' || $statusUpper === 'FAILED') {
            $update['payment_status'] = 'failed';
            $update['status'] = 'cancelled';
        } elseif ($statusUpper === 'CANCELLED') {
            $update['payment_status'] = 'cancelled';
            $update['status'] = 'cancelled';
        }

        // Fallback: if payment_status is still empty, set to 'pending'
        if (empty($update['payment_status'])) {
            $update['payment_status'] = 'pending';
            $update['status'] = 'active';
        }

        $update['updated_at'] = date('Y-m-d H:i:s');

        // Use payment_reference only if not empty
        if (!empty($data['reference'])) {
            $update['payment_reference'] = $data['reference'];
        }

        try {
            $this->transJualModel->update($order->id, $update);

            // Optionally, record payment to trans_jual_plat if paid and not already recorded
            if (strtoupper($status) === 'PAID') {
                // Get platform ID for proper foreign key relationship
                $platform = $this->platformModel->where('gateway_kode', $data['payment_method_code'])->first();

                // Prepare payment data
                $platData = [
                    'id_penjualan' => $order->id,
                    'id_platform'  => $platform ? $platform->id : null,
                    'platform'     => 'tripay',
                    'nominal'      => $data['total_amount'],
                    'keterangan'   => 'Payment via Tripay - ' . ($data['payment_method'] ?? '').' - '.$data['reference'] ?? null,
                    'updated_at'   => date('Y-m-d H:i:s'),
                ];

                // Check if payment already exists for this order and platform
                $platExists = $this->transJualPlatModel
                    ->where('id_penjualan', $order->id)
                    ->first();

                if ($platExists) {
                    // Update the existing payment record
                    $this->transJualPlatModel
                        ->where('id', $platExists->id)
                        ->set($platData)
                        ->update();
                } else {
                    // Insert new payment record
                    $platData['created_at'] = date('Y-m-d H:i:s');
                    $this->transJualPlatModel->insert($platData);
                }
            }

            // Respond to Tripay
            return $this->response->setJSON(['success' => true]);
            
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Database error']);
        }
    }

    /**
     * Test endpoint to verify callback route is working
     */
    public function test_callback()
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Callback route is working',
            'method' => $this->request->getMethod(),
            'time' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Show participant registration form
     */
    public function registerParticipant($orderId = null)
    {
        if (!$orderId) {
            return redirect()->to('sale/orders')->with('toastr', [
                'type' => 'error', 
                'message' => 'Order ID tidak valid'
            ]);
        }

        // Get order details
        $order = $this->transJualModel->find($orderId);
        if (!$order) {
            return redirect()->to('sale/orders')->with('toastr', [
                'type' => 'error', 
                'message' => 'Order tidak ditemukan'
            ]);
        }

        // Check if user owns this order
        $currentUserId = $this->ionAuth->user()->row()->id;
        if ($currentUserId != $order->user_id) {
            return redirect()->to('sale/orders')->with('toastr', [
                'type' => 'error', 
                'message' => 'Akses ditolak. User ID tidak cocok.'
            ]);
        }

        // Get order details with event info
        $orderDetails = $this->transJualDetModel->where('id_penjualan', $orderId)->findAll();
        if (empty($orderDetails)) {
            return redirect()->to('sale/orders')->with('toastr', [
                'type' => 'error', 
                'message' => 'Detail order tidak ditemukan'
            ]);
        }

        // Get event ID from first order detail
        $eventId = $orderDetails[0]->event_id ?? null;
        if (!$eventId) {
            return redirect()->to('sale/orders')->with('toastr', [
                'type' => 'error', 
                'message' => 'Event tidak ditemukan'
            ]);
        }

        // Get categories and groups for dropdowns
        $kategoriOptions = $this->kategoriModel->where('status', '1')->findAll();
        $kelompokOptions = $this->kelompokModel->where('status', '1')->findAll();

        // Get participants for this event
        $participants = $this->pesertaModel->where('id_event', $eventId)->orderBy('created_at', 'DESC')->findAll();

        $data = [
            'title'           => 'Registrasi Peserta',
            'Pengaturan'      => $this->pengaturan,
            'order'           => $order,
            'orderDetails'    => $orderDetails,
            'eventId'         => $eventId,
            'kategoriOptions' => $kategoriOptions,
            'kelompokOptions' => $kelompokOptions,
            'participants'    => $participants,
            // Add this data to every view (follow @file_context_0)
            // 'footer_text'     => 'Copyright &copy; ' . date('Y') . ' Your Organization. All rights reserved.',
        ];

        return $this->view('da-theme/transaksi/sale/register_participant', $data);
    }

    /**
     * Store participant registration
     */
    public function storeParticipant()
    {
        $orderId = $this->request->getPost('order_id');
        $eventId = $this->request->getPost('event_id');
        $participantId = $this->request->getPost('participant_id');

        if (!$orderId || !$eventId) {
            return redirect()->back()->with('toastr', [
                'type' => 'error', 
                'message' => 'Data tidak valid'
            ]);
        }

        // Get order details
        $order = $this->transJualModel->find($orderId);
        if (!$order) {
            return redirect()->back()->with('toastr', [
                'type' => 'error', 
                'message' => 'Order tidak ditemukan'
            ]);
        }

        // Check if user owns this order
        if ($this->ionAuth->getUserId() != $order->user_id) {
            return redirect()->back()->with('toastr', [
                'type' => 'error', 
                'message' => 'Akses ditolak'
            ]);
        }

        // Validation rules
        $rules = [
            'nama'        => 'required|max_length[100]',
            'email'       => 'required|valid_email|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('toastr', [
                'type'      => 'error', 
                'message'   => implode('<br>', $this->validator->getErrors())
            ])->withInput();
        }

        try {
            // Prepare participant data
            $participantData = [
                'nama'        => trim($this->request->getPost('nama')),
                'email'       => strtolower(trim($this->request->getPost('email'))),
                'no_hp'       => trim($this->request->getPost('no_hp')),
                'jns_klm'     => $this->request->getPost('jns_klm'),
                'id_kategori' => $this->request->getPost('id_kategori') !== '' ? (int)$this->request->getPost('id_kategori') : null,
                'id_kelompok' => $this->request->getPost('id_kelompok') !== '' ? (int)$this->request->getPost('id_kelompok') : null,
                'tmp_lahir'   => $this->request->getPost('tmp_lahir') !== '' ? trim($this->request->getPost('tmp_lahir')) : null,
                'tgl_lahir'   => $this->request->getPost('tgl_lahir') !== '' ? $this->request->getPost('tgl_lahir') : null,
                'alamat'      => $this->request->getPost('alamat') !== '' ? trim($this->request->getPost('alamat')) : null,
            ];

            if ($participantId) {
                // Update existing participant
                $existingParticipant = $this->pesertaModel->find($participantId);
                if (!$existingParticipant) {
                    return redirect()->back()->with('toastr', [
                        'type' => 'error', 
                        'message' => 'Peserta tidak ditemukan'
                    ]);
                }

                // Check if participant belongs to this event and order
                if ($existingParticipant->id_event != $eventId || $existingParticipant->id_penjualan != $orderId) {
                    return redirect()->back()->with('toastr', [
                        'type' => 'error', 
                        'message' => 'Akses ditolak'
                    ]);
                }

                // Update participant
                if ($this->pesertaModel->update($participantId, $participantData)) {
                    return redirect()->to('sale/register-participant/' . $orderId)
                        ->with('toastr', [
                            'type' => 'success', 
                            'message' => 'Data peserta berhasil diperbarui'
                        ]);
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->with('toastr', [
                            'type' => 'error', 
                            'message' => 'Gagal memperbarui data peserta'
                        ]);
                }
            } else {
                // Create new participant
                $kode = $this->pesertaModel->generateKode($eventId);

                // Add additional fields for new participant
                $participantData['id_user']        = $this->ionAuth->getUserId();
                $participantData['id_event']       = $eventId;
                $participantData['id_penjualan']   = $orderId;
                $participantData['kode']           = $kode;
                $participantData['status']         = 1;
                $participantData['status_hadir']   = 0;

                // Generate QR code
                $qrData = json_encode([
                    'participant_id' => null, // Will be set after insert
                    'event_id'       => $eventId,
                    'order_id'       => $orderId,
                    'kode'           => $kode
                ]);
                
                $participantData['qr_code'] = $qrData;

                // Insert participant
                if ($this->pesertaModel->insert($participantData)) {
                    $newParticipantId = $this->pesertaModel->insertID();
                    
                    // Update QR code with actual participant ID
                    $updatedQrData = json_encode([
                        'participant_id'    => $newParticipantId,
                        'event_id'          => $eventId,
                        'order_id'          => $orderId,
                        'kode'              => $kode
                    ]);
                    
                    $this->pesertaModel->update($newParticipantId, ['qr_code' => $updatedQrData]);

                    return redirect()->to('sale/register-participant/' . $orderId)
                        ->with('toastr', [
                            'type' => 'success', 
                            'message' => 'Peserta berhasil didaftarkan dengan kode: ' . $kode
                        ]);
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->with('toastr', [
                            'type' => 'error', 
                            'message' => 'Gagal menyimpan data peserta'
                        ]);
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('toastr', [
                    'type' => 'error', 
                    'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
                ]);
        }
    }
}
