<?php

/**
 * @description Transaction management controller for admin panel - handles order management, status updates, and reporting
 * @author CodeIgniter Development Team
 * @since 2025-01-15
 * @version 1.0.0
 */

namespace App\Controllers\Admin\Transaksi;

use App\Controllers\BaseController;
use App\Models\TransJualModel;
use App\Models\TransJualDetModel;
use App\Models\TransJualPlatModel;
use App\Models\PesertaModel;
use App\Models\EventsHargaModel;
use App\Models\EventsModel;
use App\Models\PlatformModel;
use App\Models\VKategoriModel;
use App\Models\UkuranModel;
use App\Models\VPesertaTransModel;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use App\Libraries\InvoicePdf;
use App\Libraries\TicketPdf;
use App\Libraries\DotMatrixInvoicePdf;

class Sale extends BaseController
{
    protected $transJualModel;
    protected $transJualDetModel;
    protected $transJualPlatModel;
    protected $pesertaModel;
    protected $eventsHargaModel;
    protected $eventsModel;
    protected $platformModel;
    protected $kategoriModel;
    protected $ukuranModel;
    protected $vPesertaTransModel;
    protected $ionAuth;

    public function __construct()
    {
        $this->transJualModel      = new TransJualModel();
        $this->transJualDetModel   = new TransJualDetModel();
        $this->transJualPlatModel  = new TransJualPlatModel();
        $this->pesertaModel        = new PesertaModel();
        $this->eventsHargaModel    = new EventsHargaModel();
        $this->eventsModel         = new EventsModel();
        $this->platformModel        = new PlatformModel();
        $this->kategoriModel        = new VKategoriModel();
        $this->ukuranModel          = new UkuranModel();
        $this->vPesertaTransModel   = new VPesertaTransModel();
        $this->ionAuth             = new \IonAuth\Libraries\IonAuth();
    }

    /**
     * Display transaction orders with filtering by status
     */
    public function orders($status = 'all')
    {
        // Get search parameter
        $search = $this->request->getGet('search');
        $search = !empty($search) ? trim($search) : null;

        // Create a fresh model instance for querying
        $ordersModel = new TransJualModel();

        // Build query conditions for the model
        if ($status !== 'all') {
            $validStatuses = ['pending', 'paid', 'failed', 'cancelled'];
            if (in_array($status, $validStatuses)) {
                $ordersModel->where('tbl_trans_jual.payment_status', $status);
            }
        }

        // Add search filter if provided
        $hasSearchJoin = false;
        if (!empty($search)) {
            // Join with order details table to search in JSON item_data
            $ordersModel->join('tbl_trans_jual_det', 'tbl_trans_jual_det.id_penjualan = tbl_trans_jual.id', 'left')
                ->groupStart()
                    ->like("JSON_UNQUOTE(JSON_EXTRACT(tbl_trans_jual_det.item_data, '$.participant_name'))", $search)
                    ->orLike("JSON_UNQUOTE(JSON_EXTRACT(tbl_trans_jual_det.item_data, '$.participant_phone'))", $search)
                ->groupEnd()
                ->groupBy('tbl_trans_jual.id');
            $hasSearchJoin = true;
        }

        // Get orders with pagination
        $ordersModel->orderBy('tbl_trans_jual.invoice_date', 'DESC');
        
        // Configure pager path before pagination
        $pager = service('pager');
        $pagerPath = 'admin/transaksi/sale/orders';
        if ($status !== 'all') {
            $pagerPath .= '/' . $status;
        }
        $pager->setPath($pagerPath, 'orders');
        
        // When using groupBy with joins, we need to handle pagination manually
        // because countAllResults() counts before grouping
        if ($hasSearchJoin) {
            // Get total count of distinct orders
            $countModel = new TransJualModel();
            if ($status !== 'all') {
                $validStatuses = ['pending', 'paid', 'failed', 'cancelled'];
                if (in_array($status, $validStatuses)) {
                    $countModel->where('tbl_trans_jual.payment_status', $status);
                }
            }
            $countModel->join('tbl_trans_jual_det', 'tbl_trans_jual_det.id_penjualan = tbl_trans_jual.id', 'left')
                ->groupStart()
                    ->like("JSON_UNQUOTE(JSON_EXTRACT(tbl_trans_jual_det.item_data, '$.participant_name'))", $search)
                    ->orLike("JSON_UNQUOTE(JSON_EXTRACT(tbl_trans_jual_det.item_data, '$.participant_phone'))", $search)
                ->groupEnd()
                ->groupBy('tbl_trans_jual.id');
            
            // Count distinct orders - when using groupBy, count the grouped results
            // Get all matching order IDs, then count unique ones
            $orderIds = $countModel->select('tbl_trans_jual.id')
                ->distinct()
                ->get()
                ->getResult();
            $totalCount = count($orderIds);
            
            // Get current page
            $page = $this->request->getGet('page_orders') ?? $this->request->getGet('page') ?? 1;
            $perPage = 10;
            $offset = ($page - 1) * $perPage;
            
            // Get orders with limit and offset - explicitly select columns from tbl_trans_jual
            // This ensures we get complete order objects with all properties when using groupBy
            $orders = $ordersModel->select('tbl_trans_jual.*')
                ->get($perPage, $offset)
                ->getResult();
            
            // Manually store pagination data
            $pager->store('orders', (int)$page, $perPage, $totalCount, 0);
        } else {
            // Normal pagination without search/join
            // Path is already set above, so paginate() will use it
            $orders = $ordersModel->paginate(10, 'orders');
            $pager = $ordersModel->pager;
            // Path is already configured, no need to set it again
        }

        // Collect participant info per order from tbl_trans_jual_det.item_data (JSON fields participant_name, participant_phone)
        $participantsByOrder = [];
        $userPhonesByOrder   = [];
        if (!empty($orders)) {
            foreach ($orders as $order) {
                $participants = [];
                $details = $this->transJualDetModel
                    ->where('id_penjualan', $order->id)
                    ->findAll();

                foreach ($details as $detail) {
                    // Get kategori from price_id via eventsHargaModel (same as export method)
                    $kategori = '';
                    if (!empty($detail->price_id)) {
                        $priceInfo = $this->eventsHargaModel
                            ->where('id', $detail->price_id)
                            ->where('id_event', $detail->event_id)
                            ->where('deleted_at', null)
                            ->first();
                        if ($priceInfo && isset($priceInfo->keterangan)) {
                            $kategori = $priceInfo->keterangan;
                        }
                    }

                    if (!empty($detail->item_data)) {
                        $itemData = json_decode($detail->item_data, true);
                        
                        // Handle JSON decode errors
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            log_message('error', 'JSON decode error for order detail ' . $detail->id . ': ' . json_last_error_msg());
                            continue;
                        }
                        
                        if (empty($itemData)) {
                            $itemData = [];
                        }
                        
                        $participantName = $itemData['participant_name'] ?? '';
                        $participantPhone = $itemData['participant_phone'] ?? '';
                        
                        // Only process if we have a participant name
                        if (!empty($participantName)) {
                            // Don't filter participants in PHP - SQL query already filtered the orders
                            // Just show all participants from orders that matched the search
                            $participants[] = [
                                'name'  => $participantName,
                                'phone' => !empty($participantPhone) ? $participantPhone : null,
                                'category' => $kategori,
                            ];
                        }
                    }
                }

                $participantsByOrder[$order->id] = $participants;

                // Fallback phone from tbl_ion_users (via IonAuth) for this order
                if ($order->user_id && !isset($userPhonesByOrder[$order->id])) {
                    $user = $this->ionAuth->user($order->user_id)->row();
                    $userPhonesByOrder[$order->id] = $user->phone ?? null;
                }
            }
        }

        // Get statistics for status filter buttons using fresh model instances for each count
        $stats = [
            'all' => (new TransJualModel())->countAll(),
            'pending' => (new TransJualModel())->where('payment_status', 'pending')->countAllResults(false),
            'paid' => (new TransJualModel())->where('payment_status', 'paid')->countAllResults(false),
            'failed' => (new TransJualModel())->where('payment_status', 'failed')->countAllResults(false),
            'cancelled' => (new TransJualModel())->where('payment_status', 'cancelled')->countAllResults(false),
        ];

        // Get events and platforms for manual order form
        // Use soft-delete filtering from the model (deleted_at is already handled)
        $events    = $this->eventsModel->orderBy('event', 'ASC')->findAll();
        $platforms = $this->platformModel->where('status', 1)->orderBy('nama', 'ASC')->findAll();
        $ukuranOptions = $this->ukuranModel->getDropdownOptions();

        $data = [
            'title' => 'Transaction Management',
            'orders' => $orders,
            'pager' => $pager,
            'current_status' => $status,
            'search' => $search,
            'stats' => $stats,
            'participantsByOrder' => $participantsByOrder,
            'userPhonesByOrder'   => $userPhonesByOrder,
            'events' => $events,
            'platforms' => $platforms,
            'ukuranOptions' => $ukuranOptions,
        ];

        return $this->view('admin-lte-3/transaksi/sale/orders', $data);
    }

    /**
     * Create manual order
     */
    public function createManualOrder()
    {
        if (!$this->ionAuth->loggedIn()) {
            return redirect()->to('auth/login');
        }

        $currentUser = $this->ionAuth->user()->row();
        $userId = $currentUser->id;

        // Validate required fields
        $validation = \Config\Services::validation();
        $validation->setRules([
            'event_id' => 'required|integer',
            'price_id' => 'required|integer',
            'participant_name' => 'required|max_length[255]',
            'participant_birthdate' => 'required|valid_date',
            'participant_phone' => 'permit_empty|max_length[20]',
            'participant_gender' => 'permit_empty|in_list[M,F]',
            'participant_address' => 'permit_empty|max_length[500]',
            'participant_uk' => 'permit_empty|max_length[10]',
            'participant_emg' => 'permit_empty|max_length[20]',
            'platform_id' => 'required|integer',
            'total_amount' => 'required|decimal',
        ]);

        if (!$validation->run($this->request->getPost())) {
            session()->setFlashdata('error', 'Validation failed: ' . implode(', ', $validation->getErrors()));
            return redirect()->to('admin/transaksi/sale/orders');
        }

        try {
            $this->db = \Config\Database::connect();
            $this->db->transStart();

            // Get event and price details
            $event = $this->eventsModel->find($this->request->getPost('event_id'));
            $price = $this->eventsHargaModel->find($this->request->getPost('price_id'));

            if (!$event || !$price) {
                throw new \Exception('Event or price not found');
            }

            // Generate invoice number
            $invoiceNo = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

            // Create order header
            $headerData = [
                'invoice_no' => $invoiceNo,
                'user_id' => $userId, // Set to current logged-in admin user
                'session_id' => null,
                'invoice_date' => date('Y-m-d H:i:s'),
                'total_amount' => $this->request->getPost('total_amount'),
                'payment_status' => $this->request->getPost('payment_status') ?? 'pending',
                'payment_method' => $this->request->getPost('platform_id'),
                'notes' => $this->request->getPost('notes') ?? 'Manual order created by admin',
                'status' => 'active',
            ];

            $headerId = $this->transJualModel->insert($headerData);
            if (!$headerId) {
                throw new \Exception('Failed to create order header');
            }

            // Create KTP upload directory if needed
            $ktpUploadPath = FCPATH . 'file/sale/ktp/' . $headerId . '/';
            if (!is_dir($ktpUploadPath)) {
                if (!mkdir($ktpUploadPath, 0755, true)) {
                    log_message('error', 'Failed to create KTP upload directory: ' . $ktpUploadPath);
                    throw new \Exception('Failed to create KTP upload directory');
                }
            }

            // Handle KTP file upload
            $ktpFilePath = null;
            $ktpFileJson = $this->request->getPost('ktp_file');
            if (!empty($ktpFileJson)) {
                $ktpFileInfo = json_decode($ktpFileJson, true);
                if (is_array($ktpFileInfo) && !empty($ktpFileInfo['filename'])) {
                    $sessionId = session_id();
                    $tempPath = FCPATH . 'file/sale/temp/' . $sessionId . '/';
                    $tempFile = $tempPath . $ktpFileInfo['filename'];
                    
                    if (file_exists($tempFile)) {
                        // Validate file type
                        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
                        $extension = strtolower(pathinfo($ktpFileInfo['filename'], PATHINFO_EXTENSION));
                        
                        if (in_array($extension, $allowedExtensions)) {
                            // Validate file size (5MB)
                            $maxSize = 5 * 1024 * 1024; // 5MB
                            $fileSize = filesize($tempFile);
                            
                            if ($fileSize <= $maxSize) {
                                // Generate unique filename
                                $newName = 'ktp_' . uniqid() . '_' . time() . '.' . $extension;
                                $ktpFile = $ktpUploadPath . $newName;
                                
                                // Move file from temp to KTP directory
                                if (rename($tempFile, $ktpFile)) {
                                    $ktpFilePath = 'file/sale/ktp/' . $headerId . '/' . $newName;
                                    log_message('info', 'KTP file moved successfully: ' . $ktpFilePath);
                                } else {
                                    log_message('error', 'Failed to move KTP file from temp to KTP directory');
                                }
                            } else {
                                log_message('error', 'KTP file too large: ' . $fileSize . ' bytes');
                            }
                        } else {
                            log_message('error', 'Invalid KTP file type: ' . $extension);
                        }
                    } else {
                        log_message('error', 'KTP temp file not found: ' . $tempFile);
                    }
                }
            }

            // Prepare participant data
            $participant = [
                'participant_id' => 0,
                'participant_name' => $this->request->getPost('participant_name'),
                'participant_gender' => $this->request->getPost('participant_gender'),
                'participant_birth_date' => $this->request->getPost('participant_birthdate'),
                'participant_phone' => $this->request->getPost('participant_phone'),
                'participant_address' => $this->request->getPost('participant_address'),
                'participant_uk' => $this->request->getPost('participant_uk'),
                'participant_emg' => $this->request->getPost('participant_emg'),
                'participant_ktp_file' => $ktpFilePath,
                'id_user' => $userId,
                'id_event' => $event->id,
                'id_kategori' => $event->id_kategori ?? 0,
                'id_platform' => $this->request->getPost('platform_id'),
            ];

            // Get kode from tbl_m_event_harga and add to unit_price and total_price
            $kode = 0;
            if ($price && isset($price->kode)) {
                $kode = (int)$price->kode;
            }
            
            // Add kode to unit_price and total_price
            $unitPrice = (float)$price->harga;
            $totalPrice = (float)$this->request->getPost('total_amount');
            $unitPriceWithKode = $unitPrice + $kode;
            $totalPriceWithKode = $totalPrice + $kode;
            
            // Calculate next sort_num from v_peserta_trans
            $maxSortNum = $this->vPesertaTransModel->selectMax('sort_num')->first();
            $nextSortNum = ($maxSortNum && isset($maxSortNum->sort_num)) ? ((int)$maxSortNum->sort_num + 1) : 1;
            
            // Create order detail
            $detailData = [
                'id_penjualan' => $headerId,
                'event_id' => $event->id,
                'price_id' => $price->id,
                'event_title' => $event->event,
                'price_description' => $price->keterangan,
                'sort_num' => $nextSortNum,
                'quantity' => 1,
                'unit_price' => $unitPriceWithKode,
                'total_price' => $totalPriceWithKode,
                'item_data' => json_encode($participant),
            ];

            $this->transJualDetModel->insert($detailData);

            // Create payment platform record
            $platform = $this->platformModel->find($this->request->getPost('platform_id'));
            $paymentPlatformId = null;
            if ($platform) {
                $platData = [
                    'id_penjualan' => $headerId,
                    'id_platform' => $platform->id,
                    'no_nota' => $invoiceNo,
                    'platform' => strtolower($platform->jenis ?? 'manual'),
                    'nominal' => $this->request->getPost('total_amount'),
                    'keterangan' => 'Manual order',
                ];
                $paymentPlatformId = $this->transJualPlatModel->insert($platData);
            }

            // Handle uploaded files - move from temp to order directory
            $uploadedFilesJson = $this->request->getPost('uploaded_files');
            if (!empty($uploadedFilesJson)) {
                $uploadedFiles = json_decode($uploadedFilesJson, true);
                if (is_array($uploadedFiles) && !empty($uploadedFiles)) {
                    $sessionId = session_id();
                    $tempPath = FCPATH . 'file/sale/temp/' . $sessionId . '/';
                    $orderPath = FCPATH . 'file/sale/' . $headerId . '/';
                    
                    // Create order directory if it doesn't exist
                    if (!is_dir($orderPath)) {
                        mkdir($orderPath, 0755, true);
                    }
                    
                    $movedFiles = [];
                    foreach ($uploadedFiles as $fileInfo) {
                        $tempFile = $tempPath . $fileInfo['filename'];
                        $orderFile = $orderPath . $fileInfo['filename'];
                        
                        // Move file from temp to order directory
                        if (file_exists($tempFile)) {
                            if (rename($tempFile, $orderFile)) {
                                $movedFiles[] = [
                                    'filename' => $fileInfo['filename'],
                                    'original_name' => $fileInfo['original_name'] ?? $fileInfo['filename'],
                                    'size' => $fileInfo['size'] ?? 0,
                                    'type' => $fileInfo['type'] ?? 'application/octet-stream',
                                    'extension' => pathinfo($fileInfo['filename'], PATHINFO_EXTENSION),
                                    'uploaded_at' => date('Y-m-d H:i:s')
                                ];
                                log_message('info', 'Moved file from temp to order: ' . $fileInfo['filename']);
                            } else {
                                log_message('error', 'Failed to move file: ' . $fileInfo['filename']);
                            }
                        }
                    }
                    
                    // Update payment platform record with file information
                    if (!empty($movedFiles) && $paymentPlatformId) {
                        $paymentPlatform = $this->transJualPlatModel->find($paymentPlatformId);
                        if ($paymentPlatform) {
                            $existingFoto = !empty($paymentPlatform->foto) ? json_decode($paymentPlatform->foto, true) : [];
                            if (!is_array($existingFoto)) {
                                $existingFoto = [];
                            }
                            
                            // Merge existing files with new files
                            $allFiles = array_merge($existingFoto, $movedFiles);
                            
                            $this->transJualPlatModel->update($paymentPlatformId, [
                                'foto' => json_encode($allFiles),
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                            
                            log_message('info', 'Updated payment platform with ' . count($movedFiles) . ' files');
                        }
                    }
                    
                    // Clean up temp directory (optional - can be done via cron job)
                    // For now, we'll leave temp files for potential recovery
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            session()->setFlashdata('success', 'Manual order created successfully. Invoice: ' . $invoiceNo);
            return redirect()->to('admin/transaksi/sale/orders');

        } catch (\Exception $e) {
            if ($this->db->transStatus() !== false) {
                $this->db->transRollback();
            }
            session()->setFlashdata('error', 'Failed to create manual order: ' . $e->getMessage());
            return redirect()->to('admin/transaksi/sale/orders');
        }
    }

    /**
     * Upload temporary files for manual order (before order creation)
     */
    public function uploadTemp()
    {
        // Check if user is logged in
        if (!$this->ionAuth->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
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

        // Validate file using extension
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
            // Create temporary upload directory using session ID
            $sessionId = session_id();
            $baseUploadPath = FCPATH . 'file/sale/temp/' . $sessionId . '/';
            
            log_message('info', 'Creating temporary upload directory: ' . $baseUploadPath);
            
            // Create directory if it doesn't exist
            if (!is_dir($baseUploadPath)) {
                if (!mkdir($baseUploadPath, 0755, true)) {
                    log_message('error', 'Failed to create directory: ' . $baseUploadPath);
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to create upload directory'
                    ]);
                }
                log_message('info', 'Directory created successfully: ' . $baseUploadPath);
            }

            // Generate unique filename
            $extension = strtolower($file->getClientExtension());
            $newName = uniqid('temp_') . '_' . time() . '.' . $extension;

            log_message('info', 'Moving file: ' . $newName . ' (Size: ' . $file->getSize() . ' bytes)');

            // Check if temp file exists before moving
            if (!file_exists($file->getTempName())) {
                log_message('error', 'Temporary file does not exist: ' . $file->getTempName());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Temporary file not found. Please try again.'
                ]);
            }

            // Move file to temporary upload directory
            if ($file->move($baseUploadPath, $newName)) {
                log_message('info', 'File moved successfully: ' . $newName);
                
                // Determine MIME type based on extension
                $mimeTypes = [
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                    'pdf' => 'application/pdf'
                ];
                $mimeType = isset($mimeTypes[$extension]) ? $mimeTypes[$extension] : 'application/octet-stream';

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'File uploaded successfully',
                    'filename' => $newName,
                    'original_name' => $file->getClientName(),
                    'size' => $file->getSize(),
                    'type' => $mimeType,
                    'extension' => $extension,
                    'temp_path' => 'file/sale/temp/' . $sessionId . '/' . $newName
                ]);
            } else {
                log_message('error', 'Failed to move file: ' . $file->getErrorString());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to save file: ' . $file->getErrorString()
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Upload exception: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get event prices via AJAX
     */
    public function getEventPrices($eventId)
    {
        $prices = $this->eventsHargaModel
            ->where('id_event', $eventId)
            ->where('status', '1')
            ->where('deleted_at', null)
            ->orderBy('keterangan', 'ASC')
            ->findAll();

        $priceList = [];
        foreach ($prices as $price) {
            $priceList[] = [
                'id' => $price->id,
                'keterangan' => $price->keterangan,
                'harga' => $price->harga,
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'prices' => $priceList
        ]);
    }

    /**
     * Display transaction detail
     */
    public function detail($invoiceId)
    {

        // Get order details
        $order = $this->transJualModel->find($invoiceId);
        
        if (!$order) {
            session()->setFlashdata('error', 'Order not found');
            return redirect()->to('admin/transaksi/sale/orders');
        }

        // Get order items
        $order_details = $this->transJualDetModel->where('id_penjualan', $invoiceId)->findAll();

        // Get payment platforms
        $payment_platforms = $this->transJualPlatModel->where('id_penjualan', $invoiceId)->findAll();

        // Get user info using IonAuth
        $user = null;
        if ($order->user_id) {
            $user = $this->ionAuth->user($order->user_id)->row();
        }

        $data = [
            'title' => 'Transaction Detail - Invoice #' . $order->invoice_no,
            'order' => $order,
            'order_details' => $order_details,
            'payment_platforms' => $payment_platforms,
            'user' => $user
        ];

        return $this->view('admin-lte-3/transaksi/sale/detail', $data);
    }

    /**
     * Update order status
     */
    public function updateStatus($invoiceId)
    {
        $order = $this->transJualModel->find($invoiceId);

        if (!$order) {
            session()->setFlashdata('error', 'Order not found');
            return redirect()->to('admin/transaksi/sale/orders');
        }

        // Get user info once for this order (may be used for phone/email fallback)
        $user = null;
        if ($order->user_id) {
            $user = $this->ionAuth->user($order->user_id)->row();
        }

        $newPaymentStatus = $this->request->getPost('payment_status');
        $newOrderStatus = $this->request->getPost('order_status');
        $note = $this->request->getPost('note');

        $updateData = [
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($newPaymentStatus) {
            $updateData['payment_status'] = $newPaymentStatus;
        }

        if ($newOrderStatus) {
            $updateData['status'] = $newOrderStatus;
        }

        if ($note !== null) {
            $updateData['note'] = $note;
        }

        $sql = $this->transJualDetModel->where('id_penjualan', $invoiceId)->findAll();

        if ($newPaymentStatus === 'paid') {
            // Calculate next sort_num from v_peserta_trans (only paid orders)
            $maxSortNum = $this->vPesertaTransModel
                ->where('paid_date IS NOT', null)
                ->orderBy('paid_date', 'ASC')
                ->selectMax('sort_num')
                ->first();
            $nextSortNum = ($maxSortNum && isset($maxSortNum->sort_num)) ? ((int)$maxSortNum->sort_num + 1) : 1;
            
            foreach ($sql as $s) {
                // Safely decode item_data (handle both object and array structures)
                $itemData = json_decode($s->item_data ?? '', true) ?: [];

                $participantName   = $itemData['participant_name']   ?? '';
                $participantPhone  = $itemData['participant_phone']  ?? null;
                $participantEmail  = $itemData['participant_email']  ?? null;
                $participantEvent  = $itemData['id_event']           ?? $s->event_id;
                $participantKat    = $itemData['id_kategori']        ?? 0;
                $participantPlat   = $itemData['id_platform']        ?? 0;
                $participantIdOrig = $itemData['participant_id']     ?? 0;

                $data = [
                    'id_penjualan'  => $invoiceId,
                    'id_kategori'   => $participantKat,
                    'id_platform'   => $participantPlat,
                    'id_kelompok'   => 0,
                    'id_event'      => $participantEvent,
                    'id_user'       => $order->user_id,
                    'kode'          => $this->pesertaModel->generateKode($s->event_id),
                    'nama'          => $participantName,
                    'no_hp'         => $participantPhone ?? $user->phone,
                    'email'         => $participantEmail ?? $user->email,
                ];

                if (!empty($participantIdOrig) && $participantIdOrig != 0) {
                    $data['id'] = $participantIdOrig;
                }

                $this->pesertaModel->save($data);
                $lastPesertaId = (!empty($participantIdOrig) && $participantIdOrig != 0 ? $participantIdOrig : $this->pesertaModel->insertID());

                $peserta = [
                    "participant_id"      => $lastPesertaId,
                    "participant_name"    => strtolower(ucwords($participantName)),
                    "participant_number"  => $data['kode'],
                    "participant_phone"   => $participantPhone ?? $user->phone,
                    "participant_email"   => $participantEmail ?? $user->email,
                    "id_user"             => $order->user_id,
                    "id_event"            => $participantEvent,
                    "id_kategori"         => $participantKat,
                    "id_platform"         => $participantPlat,
                ];

                // Update sort_num for each order detail
                $currentSortNum = $nextSortNum;
                $this->transJualDetModel->update($s->id, [
                    'item_data' => json_encode($peserta),
                    'sort_num' => $currentSortNum
                ]);
                $nextSortNum++; // Increment for next detail in this order
            }
        } elseif ($newPaymentStatus === 'pending') {
            // Delete peserta with this invoiceId
            $this->pesertaModel->where('id_penjualan', $invoiceId)->delete();
        }

        $success = false;
        if ($this->transJualModel->update($invoiceId, $updateData)) {
            // Generate QR codes and save participants if payment status changed to 'paid'
            if ($newPaymentStatus === 'paid' && $order->payment_status !== 'paid') {
                $this->generateQRCodesForOrder($invoiceId);
                // $this->saveParticipantsFromOrder($invoiceId);
            }

            session()->setFlashdata('success', 'Order status updated successfully');
            $success = true;
        } else {
            session()->setFlashdata('error', 'Failed to update order status');
        }

        // After success update all, send WhatsApp notification using KamupediaWA library
        if ($success) {
            // Use fully qualified class name to avoid class name conflict
            $kamupediaWA = new \App\Libraries\KamupediaWA();

            // Get customer information
            $customerPhone = null;
            $customerName = null;
            if ($order->user_id) {
                $user = $this->ionAuth->user($order->user_id)->row();
                $customerPhone = $user->phone ?? null;
                $customerName = $user->first_name ?? $user->last_name ?? null;
            }

            // Get order details from tbl_trans_jual_det
            $orderDetails = [];
            if (isset($order->id)) {
                $orderDetailsModel = new \App\Models\TransJualDetModel();
                // Use correct field for foreign key
                $orderDetails = $orderDetailsModel->where('id_penjualan', $order->id)->findAll();
            }

            // Determine status and send appropriate notification
            $waResult = null;
            try {
                if ($newPaymentStatus) {
                    $waResult = $kamupediaWA->sendOrderNotification($order, $newPaymentStatus, $customerPhone, $customerName, $orderDetails);
                } else {
                    $waResult = $kamupediaWA->sendOrderNotification($order, $newOrderStatus, $customerPhone, $customerName, $orderDetails);
                }

                // Log result
                if (isset($waResult['success']) && $waResult['success']) {
                    log_message('info', 'WhatsApp notification sent successfully');
                } else {
                    $waErrorMsg = isset($waResult['message']) ? $waResult['message'] : 'Unknown error';
                    log_message('warning', 'WhatsApp message not sent: ' . $waErrorMsg);
                    log_message('error', 'WhatsApp notification failed: WhatsApp message failed: ' . $waErrorMsg);
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

                // Ensure $smtpPort is an integer, fallback to 587 if not set or not numeric
                $smtpPortInt = 465;
                if (is_numeric($smtpPort)) {
                    $smtpPortInt = (int)$smtpPort;
                }

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
                    $userEmail = $this->ionAuth->user($order->user_id)->row()->email ?? null;
                }
                if ($userEmail) {
                    $email->setTo($userEmail);
                    $email->setFrom($smtpUser, $this->pengaturan->judul);

                    // Use order object for email subject and body, fallback if fields missing
                    $invoiceNo = isset($order->invoice_no) ? $order->invoice_no : $invoiceId;
                    $subtotal = isset($order->subtotal) ? $order->subtotal : (isset($order->total_amount) ? $order->total_amount : 0);

                    $email->setSubject('Order Berhasil - ' . $invoiceNo);

                    // Compose email message
                    $message = '<h3>Terima kasih, pesanan Anda berhasil dibuat!</h3>';
                    $message .= '<p>Nomor Invoice: <b>' . htmlspecialchars($invoiceNo) . '</b></p>';
                    $message .= '<p>Total: <b>Rp ' . number_format($subtotal, 0, ',', '.') . '</b></p>';
                    $message .= '<p>Status Pembayaran: <b>' . htmlspecialchars(ucfirst($newPaymentStatus)) . '</b></p>';
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
        }

        return redirect()->to('admin/transaksi/sale/detail/' . $invoiceId);
    }

    /**
     * Generate sales reports
     */
    public function reports()
    {
        // Get date range from request
        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?: date('Y-m-t');

        // Get sales data
        $builder = $this->transJualModel->builder();
        $builder->where('invoice_date >=', $startDate);
        $builder->where('invoice_date <=', $endDate . ' 23:59:59');
        $orders = $builder->orderBy('invoice_date', 'DESC')->get()->getResult();

        // Calculate statistics
        $totalOrders = count($orders);
        $totalRevenue = array_sum(array_column($orders, 'total_amount'));
        $paidOrders = array_filter($orders, function($order) {
            return $order->payment_status === 'paid';
        });
        $paidRevenue = array_sum(array_column($paidOrders, 'total_amount'));

        $data = [
            'title' => 'Sales Reports',
            'orders' => $orders,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'stats' => [
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
                'paid_orders' => count($paidOrders),
                'paid_revenue' => $paidRevenue,
                'pending_orders' => $totalOrders - count($paidOrders),
                'pending_revenue' => $totalRevenue - $paidRevenue
            ]
        ];

        return $this->view('admin-lte-3/transaksi/sale/reports', $data);
    }

    /**
     * Export transaction data
     */
    public function export($format = 'xlsx')
    {
        // Get filters from request
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $status = $this->request->getGet('status') ?? 'all';
        $search = $this->request->getGet('search');
        $search = !empty($search) ? trim($search) : null;

        // Normalize kategori so it reliably matches the Excel headers (e.g. 1K == 1 K)
        $normalizeKategori = static function ($value): string {
            if ($value === null) {
                return '';
            }
            $s = strtoupper(trim((string) $value));
            // Collapse whitespace
            $s = preg_replace('/\s+/', ' ', $s) ?? $s;
            // Make "1K" / "10K" / "3 K" consistent: "<digits> K"
            $s = preg_replace('/(\d+)\s*K\b/', '$1 K', $s) ?? $s;
            return $s;
        };
        
        // Helper function to get Excel column letter from number (1 = A, 27 = AA, etc.)
        $getColumnLetter = static function ($colNum): string {
            $letter = '';
            while ($colNum > 0) {
                $colNum--;
                $letter = chr(65 + ($colNum % 26)) . $letter;
                $colNum = intval($colNum / 26);
            }
            return $letter;
        };

        // Get transaction details with joins
        $builder = $this->transJualDetModel->builder();
        $builder->select('tbl_trans_jual_det.*, 
                         tbl_trans_jual.invoice_date,
                         tbl_trans_jual.payment_status,
                         tbl_ion_users.phone as user_phone,
                         tbl_m_event_harga.keterangan as kategori,
                         tbl_m_event_harga.harga as harga_satuan')
                ->join('tbl_trans_jual', 'tbl_trans_jual.id = tbl_trans_jual_det.id_penjualan', 'left')
                ->join('tbl_ion_users', 'tbl_ion_users.id = tbl_trans_jual.user_id', 'left')
                // Ensure we join the correct pricing row for this event + price_id, and ignore soft-deleted prices
                ->join(
                    'tbl_m_event_harga',
                    'tbl_m_event_harga.id = tbl_trans_jual_det.price_id'
                        . ' AND tbl_m_event_harga.id_event = tbl_trans_jual_det.event_id'
                        . ' AND tbl_m_event_harga.deleted_at IS NULL',
                    'left'
                );
        
        // Apply status filter if provided and not 'all'
        if ($status !== 'all') {
            $validStatuses = ['pending', 'paid', 'failed', 'cancelled'];
            if (in_array($status, $validStatuses)) {
                $builder->where('tbl_trans_jual.payment_status', $status);
            }
        }
        
        // Apply search filter if provided
        if (!empty($search)) {
            $builder->groupStart()
                ->like("JSON_UNQUOTE(JSON_EXTRACT(tbl_trans_jual_det.item_data, '$.participant_name'))", $search)
                ->orLike("JSON_UNQUOTE(JSON_EXTRACT(tbl_trans_jual_det.item_data, '$.participant_phone'))", $search)
                ->groupEnd();
        }
        
        // Apply date filter if dates are explicitly provided
        if (!empty($startDate) && !empty($endDate)) {
            $builder->where('tbl_trans_jual.invoice_date >=', $startDate)
                    ->where('tbl_trans_jual.invoice_date <=', $endDate . ' 23:59:59');
        }
        
        $builder->orderBy('tbl_trans_jual.invoice_date', 'ASC');
        $transactionDetails = $builder->get()->getResult();
        
        // Process participants from item_data JSON
        $participants = [];
        foreach ($transactionDetails as $detail) {
            if (!empty($detail->item_data)) {
                $itemData = json_decode($detail->item_data, true);
                if ($itemData && isset($itemData['participant_name'])) {
                    $participants[] = [
                        'nama' => $itemData['participant_name'] ?? '',
                        // Prefer phone from tbl_ion_users (via tbl_trans_jual.user_id), fallback to item_data
                        'phone' => $detail->user_phone ?? ($itemData['participant_phone'] ?? ''),
                        'tgl_pendaftaran' => $detail->invoice_date ?? '',
                        'payment_status' => $detail->payment_status ?? 'pending',
                        'kategori' => $detail->kategori ?? '',
                    ];
                }
            }
        }

        if ($format === 'xlsx' || $format === 'excel') {
            // Get unique kategori columns from tbl_m_event_harga
            $kategoriResults = $this->eventsHargaModel
                ->select('keterangan')
                ->distinct(true)
                ->where('deleted_at', null)
                ->where('status', '1')
                ->orderBy('keterangan', 'ASC')
                ->findAll();
            
            // Extract kategori values and normalize
            $kategoriColumns = [];
            foreach ($kategoriResults as $row) {
                if (!empty($row->keterangan)) {
                    $kategoriColumns[] = $row->keterangan;
                }
            }
            
            // If no kategori found, use empty array (will create empty export)
            if (empty($kategoriColumns)) {
                $kategoriColumns = [];
            }
            
            $kategoriColumnsNormalized = array_map($normalizeKategori, $kategoriColumns);
            
            // Calculate dynamic column ranges
            // Columns: A=NO(1), B=NAMA(2), C=TGL(3), D=ADA(4), E=TIDAK(5), F+=KATEGORI(6+), Last=JML PESERTA
            $kategoriStartColNum = 6; // Column F
            $numKategoriCols = count($kategoriColumns);
            $jmlPesertaColNum = $kategoriStartColNum + $numKategoriCols; // F + N = JML PESERTA column
            $kategoriStartCol = $getColumnLetter($kategoriStartColNum);
            $jmlPesertaCol = $getColumnLetter($jmlPesertaColNum);
            $lastCol = $jmlPesertaCol;
            
            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Title row
            $sheet->setCellValue('A1', 'DAFTAR UPDATE PESERTA RUN PSMTI 2026');
            $sheet->mergeCells('A1:' . $lastCol . '1');
            $sheet->getStyle('A1')->applyFromArray([
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            
            // Header row 1 (main headers)
            // Merge A3:A4 for NO. column, vertically center and horizontally center the content
            $sheet->setCellValue('A3', 'NO.');
            $sheet->mergeCells('A3:A4');
            $sheet->getStyle('A3:A4')->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ]);
            
            // Merge B3:B4 for NAMA column, as per instruction, with vertical and horizontal center
            $sheet->setCellValue('B3', 'NAMA');
            $sheet->mergeCells('B3:B4');
            $sheet->getStyle('B3:B4')->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ]);
            
            // Merge C3:C4 for TGL PENDAFTARAN column, as per instruction, with vertical and horizontal center
            $sheet->setCellValue('C3', 'TGL PENDAFTARAN');
            $sheet->mergeCells('C3:C4');
            $sheet->getStyle('C3:C4')->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ]);
            
            $sheet->mergeCells('D3:E3');
            $sheet->setCellValue('D3', 'BUKTI TRANSFER');
            if ($numKategoriCols > 0) {
                $kategoriEndColNum = $kategoriStartColNum + $numKategoriCols - 1;
                $kategoriEndCol = $getColumnLetter($kategoriEndColNum);
                $sheet->mergeCells($kategoriStartCol . '3:' . $kategoriEndCol . '3');
            } else {
                $sheet->mergeCells($kategoriStartCol . '3:' . $kategoriStartCol . '3');
            }
            $sheet->setCellValue($kategoriStartCol . '3', 'KATEGORI');

            // Merge jmlPesertaCol (e.g., J3:J4 if J is the lastCol) as per instruction, center and middle
            $sheet->setCellValue($jmlPesertaCol . '3', 'JML PESERTA');
            $sheet->mergeCells($jmlPesertaCol . '3:' . $jmlPesertaCol . '4');
            $sheet->getStyle($jmlPesertaCol . '3:' . $jmlPesertaCol . '4')->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ]);
            
            // Header row 2 (sub-headers)
            $sheet->setCellValue('D4', 'ADA');
            $sheet->setCellValue('E4', 'TIDAK');
            $colNum = $kategoriStartColNum;
            foreach ($kategoriColumns as $kategori) {
                $col = $getColumnLetter($colNum);
                $sheet->setCellValue($col . '4', $kategori);
                $colNum++;
            }
            
            // Style header rows
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9E1F2'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ];
            $sheet->getStyle('A3:' . $lastCol . '4')->applyFromArray($headerStyle);
            
            // Populate data rows
            $row = 5;
            $no = 1;
            $summaryTotals = array_fill_keys($kategoriColumns, 0);
            $totalJmlPeserta = 0;
            
            foreach ($participants as $participant) {
                // Format date as DD/MM/YYYY
                $tglPendaftaran = '';
                if (!empty($participant['tgl_pendaftaran'])) {
                    $tglPendaftaran = tgl_indo2($participant['tgl_pendaftaran']);
                }
                
                // Determine BUKTI TRANSFER status
                $buktiAda = ($participant['payment_status'] === 'paid') ? 'X' : '';
                $buktiTidak = ($participant['payment_status'] !== 'paid') ? 'X' : '';
                
                // Get kategori
                $kategori = $participant['kategori'] ?? '';
                $kategoriNormalized = $normalizeKategori($kategori);
                
                // Set cell values
                $sheet->setCellValue('A' . $row, $no++);
                $sheet->setCellValue('B' . $row, ucwords($participant['nama']));
                $sheet->setCellValue('C' . $row, $tglPendaftaran);
                $sheet->setCellValue('D' . $row, $buktiAda);
                $sheet->setCellValue('E' . $row, $buktiTidak);
                
                // Set kategori columns (show 1 in matching column, empty in others)
                $colNum = $kategoriStartColNum;
                foreach ($kategoriColumns as $idx => $kat) {
                    $katNormalized = $kategoriColumnsNormalized[$idx] ?? $normalizeKategori($kat);
                    $col = $getColumnLetter($colNum);
                    if ($kategoriNormalized !== '' && $kategoriNormalized === $katNormalized) {
                        $sheet->setCellValue($col . $row, 1);
                        $summaryTotals[$kat]++;
                    } else {
                        $sheet->setCellValue($col . $row, '');
                    }
                    $colNum++;
                }
                
                // JML PESERTA (always 1 per participant)
                $sheet->setCellValue($jmlPesertaCol . $row, 1);
                $totalJmlPeserta++;
                
                // Style data row
                $dataRowStyle = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ];
                $sheet->getStyle('A' . $row . ':' . $lastCol . $row)->applyFromArray($dataRowStyle);
                
                // NAMA column left-aligned
                $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                
                // JML PESERTA column yellow background
                $sheet->getStyle($jmlPesertaCol . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFE699'],
                    ],
                ]);
                
                $row++;
            }
            
            // Summary row
            $summaryRow = $row;
            $sheet->mergeCells('A' . $summaryRow . ':B' . $summaryRow);
            $sheet->setCellValue('A' . $summaryRow, 'JUMLAH PESERTA');
            
            $colNum = $kategoriStartColNum;
            foreach ($kategoriColumns as $kat) {
                $col = $getColumnLetter($colNum);
                $sheet->setCellValue($col . $summaryRow, $summaryTotals[$kat] ?? 0);
                $colNum++;
            }
            
            $sheet->setCellValue($jmlPesertaCol . $summaryRow, $totalJmlPeserta);
            
            // Style summary row
            $summaryStyle = [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9E1F2'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ];
            $sheet->getStyle('A' . $summaryRow . ':' . $lastCol . $summaryRow)->applyFromArray($summaryStyle);
            
            // Auto-size columns
            foreach (range('A', $lastCol) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Set filename
            $filename = 'sales_export_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            // Set headers for Excel download
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            // Write file to output
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        }

        // Default to redirect if format not supported
        session()->setFlashdata('error', 'Export format not supported');
        return redirect()->to('admin/transaksi/sale/reports');
    }

    /**
     * Generate and download invoice PDF
     */
    public function downloadInvoice($invoiceId)
    {
        // Get order details
        $order = $this->transJualModel->find($invoiceId);
        
        if (!$order) {
            session()->setFlashdata('error', 'Order not found');
            return redirect()->to('admin/transaksi/sale/orders');
        }

        // Get order items
        $orderDetails = $this->transJualDetModel->where('id_penjualan', $invoiceId)->findAll();

        // Get payment platforms
        $paymentPlatforms = $this->transJualPlatModel->where('id_penjualan', $invoiceId)->findAll();

        // Get user info using IonAuth
        $user = null;
        if ($order->user_id) {
            $user = $this->ionAuth->user($order->user_id)->row();
        }

        try {
            // Create PDF instance
            $pdf = new InvoicePdf($order, $orderDetails, $paymentPlatforms, $user);
            
            // Generate PDF content
            $pdfContent = $pdf->generateInvoice();
            
            // Set headers for PDF download
            $filename = 'Invoice_' . $order->invoice_no . '_' . date('Y-m-d') . '.pdf';
            
            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->setHeader('Cache-Control', 'private, max-age=0, must-revalidate')
                ->setHeader('Pragma', 'public')
                ->setBody($pdfContent);
                
        } catch (\Exception $e) {
            log_message('error', 'PDF generation failed for invoice ' . $invoiceId . ': ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to generate PDF invoice: ' . $e->getMessage());
            return redirect()->to('admin/transaksi/sale/detail/' . $invoiceId);
        }
    }

    /**
     * Generate and download dot matrix style invoice PDF
     */
    public function downloadDotMatrixInvoice($invoiceId)
    {
        // Get order details
        $order = $this->transJualModel->find($invoiceId);
        
        if (!$order) {
            session()->setFlashdata('error', 'Order not found');
            return redirect()->to('admin/transaksi/sale/orders');
        }

        // Get order items
        $orderDetails = $this->transJualDetModel->where('id_penjualan', $invoiceId)->findAll();

        // Get payment platforms
        $paymentPlatforms = $this->transJualPlatModel->where('id_penjualan', $invoiceId)->findAll();

        // Get user info using IonAuth
        $user = null;
        if ($order->user_id) {
            $user = $this->ionAuth->user($order->user_id)->row();
        }

        try {
            // Create dot matrix PDF instance
            $pdf = new DotMatrixInvoicePdf($order, $orderDetails, $paymentPlatforms, $user);
            
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
            session()->setFlashdata('error', 'Failed to generate dot matrix invoice: ' . $e->getMessage());
            return redirect()->to('admin/transaksi/sale/detail/' . $invoiceId);
        }
    }

    /**
     * Generate and download ticket PDF for a specific order detail
     */
    public function downloadTicket($orderDetailId)
    {
        // Get order detail
        $orderDetail = $this->transJualDetModel->find($orderDetailId);
        
        if (!$orderDetail) {
            session()->setFlashdata('error', 'Ticket not found');
            return redirect()->to('admin/transaksi/sale/orders');
        }

        // Get main order
        $order = $this->transJualModel->find($orderDetail->id_penjualan);
        
        if (!$order) {
            session()->setFlashdata('error', 'Order not found');
            return redirect()->to('admin/transaksi/sale/orders');
        }

        // Get user info using IonAuth
        $user = null;
        if ($order->user_id) {
            $user = $this->ionAuth->user($order->user_id)->row();
        }

        // Get event info (placeholder - you can expand this based on your event model)
        $event = (object)[
            'title' => $orderDetail->event_title ?: 'Event',
            'date' => $order->invoice_date,
            'location' => 'Event Venue'
        ];

        try {
            // Create ticket PDF instance
            $ticket = new TicketPdf($orderDetail, $order, $user, $event);
            
            // Generate ticket content
            $ticketContent = $ticket->generateTicket();
            
            // Set headers for PDF download
            $filename = 'Ticket_' . str_pad($orderDetail->id, 6, '0', STR_PAD_LEFT) . '_' . date('Y-m-d') . '.pdf';
            
            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->setHeader('Cache-Control', 'private, max-age=0, must-revalidate')
                ->setHeader('Pragma', 'public')
                ->setBody($ticketContent);
                
        } catch (\Exception $e) {
            log_message('error', 'Ticket generation failed for order detail ' . $orderDetailId . ': ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to generate ticket: ' . $e->getMessage());
            return redirect()->to('admin/transaksi/sale/detail/' . $orderDetail->id_penjualan);
        }
    }

    /**
     * Generate and download all tickets for an order (Admin)
     * Creates individual tickets for each participant with their QR codes
     */
    public function downloadAllTickets($invoiceId)
    {
        
        // Get order
        $order = $this->transJualModel->find($invoiceId);
        
        if (!$order) {
            session()->setFlashdata('error', 'Order not found');
            return redirect()->to('admin/transaksi/sale/orders');
        }

        // Get all order details (each represents a participant/ticket)
        $orderDetails = $this->transJualDetModel->where('id_penjualan', $invoiceId)->findAll();
        
        if (empty($orderDetails)) {
            session()->setFlashdata('error', 'No tickets found for this order');
            return redirect()->to('admin/transaksi/sale/detail/' . $invoiceId);
        }

        // Get user info
        $user = null;
        if ($order->user_id) {
            $user = $this->ionAuth->user($order->user_id)->row();
        }

        try {
            // Create a master PDF to combine all individual tickets
            // Use centimeters for page size (TCPDF supports 'cm' as unit)
            $pdfWidthCm = 21.0; // 210mm = 21cm (A4 width)
            $pdfHeightCm = 8.5; // Example: 11cm height (adjust as needed)
            $masterPdf = new \TCPDF('L', 'cm', array($pdfWidthCm, $pdfHeightCm), true, 'UTF-8', false);
            $masterPdf->SetCreator('WGTIX System');
            $masterPdf->SetAuthor('WGTIX Event Management');
            $masterPdf->SetTitle('Event Tickets - Invoice #' . $order->invoice_no);
            $masterPdf->SetMargins(0, 0, 0);
            $masterPdf->SetAutoPageBreak(FALSE);

            // Generate individual ticket for each order detail/participant
            foreach ($orderDetails as $index => $orderDetail) {
                $ps = json_decode($orderDetail->item_data);

                // Event info
                $event = (object)[
                    'title' => $orderDetail->event_title ?: 'Event',
                    'date' => $order->invoice_date,
                    'location' => 'Event Venue'
                ];

                // Add page to master PDF
                $masterPdf->AddPage();
                
                // Draw the individual ticket content
                $this->drawTicketInMasterPdf($masterPdf, $orderDetail, $order, $user, $event, $index + 1, count($orderDetails));
            }
            
            // Output PDF as a download (attachment)
            $filename = 'All_Tickets_' . $order->invoice_no . '_' . date('Y-m-d') . '.pdf';
            $masterPdf->Output($filename, 'I');
            exit;
                
        } catch (\Exception $e) {
            log_message('error', 'All tickets generation failed for invoice ' . $invoiceId . ': ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to generate tickets: ' . $e->getMessage());
            return redirect()->to('admin/transaksi/sale/detail/' . $invoiceId);
        }
    }

    /**
     * Draw individual ticket content in the master PDF (Admin version)
     */
    private function drawTicketInMasterPdf($pdf, $orderDetail, $order, $user, $event, $ticketNumber, $totalTickets)
    {
        // Use ticket background image (domain path, not C:\...)
        $bgPath = FCPATH . 'file/app/bg_tiket.png';
        // Set ticket size (A5 landscape: 21 x 14.85 cm), but ticket area is 20x7.5cm
        $ticketX = 0.5; // 0.5cm margin
        $ticketY = 0.5;
        $ticketW = 20.0;
        $ticketH = 7.5; // 7.5cm height

        // If using a background image, get its real size and fit to ticket area
        if (file_exists($bgPath)) {
            list($imgWidthPx, $imgHeightPx) = getimagesize($bgPath);
            // TCPDF uses cm, so we use the ticketW and ticketH as the image size
            $pdf->Image($bgPath, $ticketX, $ticketY, $ticketW, $ticketH, 'PNG');
        } else {
            // Fallback: white background if image not found
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Rect($ticketX, $ticketY, $ticketW, $ticketH, 'F');
        }

        // Ticket border
        // $pdf->SetDrawColor(0, 123, 255);
        // $pdf->SetLineWidth(0.1); // 1mm = 0.1cm
        // $pdf->Rect($ticketX, $ticketY, $ticketW, $ticketH, 'D');

        // Header background (semi-transparent over bg)
        $headerH = 1.8; // 1.8cm header height for compact ticket

        // Decorative side strip (semi-transparent over bg)
        $sideStripW = 4.0;
        $sideStripX = $ticketX + $ticketW - $sideStripW;
        $sideStripY = $ticketY + $headerH;
        $sideStripH = $ticketH - $headerH;

        // Participant info
        $itemData = json_decode($orderDetail->item_data, true) ?: [];
        $currentY = $ticketY + $headerH + 3.0;
        if (isset($itemData['participant_name'])) {
            $currentY += 0.5;

            if (isset($itemData['participant_number'])) {
                $pdf->SetXY($ticketX + 0.5, $currentY);
                $currentY += 0.5;
            }
        }

        // Customer info
        if ($user) {
            $pdf->SetFont('helvetica', 'B', 7);
            $pdf->SetXY($ticketX + 0.5, $currentY);
            // $pdf->Cell($ticketW - $sideStripW - 1, 0.4, 'Ticket Holder:', 0, 1, 'L');
            $currentY += 0.4;
            $pdf->SetFont('helvetica', '', 7);
            $pdf->SetXY($ticketX + 0.5, $currentY);
            // $pdf->Cell($ticketW - $sideStripW - 1, 0.4, $user->first_name . ' ' . $user->last_name, 0, 1, 'L');
            $currentY += 0.4;
            $pdf->SetXY($ticketX + 0.5, $currentY);
            // $pdf->Cell($ticketW - $sideStripW - 1, 0.4, $user->email, 0, 1, 'L');
            $currentY += 0.4;
        }

        // Price info
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetXY($ticketX + 0.5, $ticketY + $ticketH - 1.2);
        // $pdf->Cell($ticketW - $sideStripW - 1, 0.4, 'Price: Rp ' . number_format($orderDetail->total_price, 0, ',', '.'), 0, 1, 'L');

        // QR Code section - white box for barcode/QR
        // Move QR block up 1.2cm and left 1.5cm, and center all text (SCAN TO VERIFY, QR, etc) in the block
        $qrBoxW = 2.2;
        $qrBoxH = 2.2;

        // Move left 1.5cm and up 1.2cm from original position
        $qrBoxX = $sideStripX + ($sideStripW - $qrBoxW) / 2 - 1.5;
        $qrBoxY = $ticketY + $headerH + 0.5 - 1.2;

        // Draw white box for QR/barcode
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Rect($qrBoxX, $qrBoxY, $qrBoxW, $qrBoxH, 'F');

        // Center "SCAN TO VERIFY" at the top of the QR block
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetXY($qrBoxX + 0.1, $qrBoxY - 0.2); // 0.2cm above the QR box
        $pdf->Cell(4.2, 0.5, 'SCAN TO VERIFY', 0, 1, 'C');

        // Display QR code if available, inside the white box at the exact box position (no shift)
        if (!empty($orderDetail->qrcode)) {
            $qrPath = FCPATH . 'file/sale/' . $order->id . '/qrcode/' . $orderDetail->qrcode;
            if (file_exists($qrPath)) {
                // Place QR image exactly inside the white box (no shift)
                // Make the QR code box bigger and keep it proportional (square)
                $biggerQrBoxW = 4;
                $biggerQrBoxH = 4;
                $biggerQrBoxX = $sideStripX + ($sideStripW - $biggerQrBoxW) / 2 - 1.35;
                $biggerQrBoxY = $ticketY + $headerH + 0.2 - 1.2;

                // Place QR image exactly at the bigger box position (with 0.2cm margin), no rectangle
                $pdf->Image($qrPath, $biggerQrBoxX + 1, $biggerQrBoxY + 0.5, $biggerQrBoxW - 0.4, $biggerQrBoxH - 0.4, 'PNG');
            } else {
                // QR placeholder in white box, centered
                $pdf->SetFont('helvetica', '', 7);
                $pdf->SetTextColor(150, 150, 150);
                $pdf->SetXY($qrBoxX, $qrBoxY + 0.2);
                $pdf->Cell($qrBoxW, 0.4, 'QR CODE', 0, 1, 'C');
                $pdf->SetXY($qrBoxX, $qrBoxY + 0.7);
                $pdf->Cell($qrBoxW, 0.4, 'PENDING', 0, 1, 'C');
                $pdf->SetTextColor(0, 0, 0);
            }
        } else {
            // No QR code, show placeholder in white box, centered
            $pdf->SetFont('helvetica', '', 7);
            $pdf->SetTextColor(150, 150, 150);
            $pdf->SetXY($qrBoxX, $qrBoxY + 0.7);
            $pdf->Cell($qrBoxW, 0.4, 'QR CODE', 0, 1, 'C');
            $pdf->SetXY($qrBoxX, $qrBoxY + 1.2);
            $pdf->Cell($qrBoxW, 0.4, 'NOT READY', 0, 1, 'C');
            $pdf->SetTextColor(0, 0, 0);
        }

        // Ticket ID and count
        $pdf->SetFont('helvetica', '', 18);
        $pdf->SetXY(2.5, $qrBoxY - 0.24);
        $pdf->Cell($sideStripW - 1.50, 0.4,  ($orderDetail->sort_num != 0 ? $orderDetail->sort_num : ''), 0, 1, 'L');
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->SetXY(8.45, $qrBoxY - 0.2);
        $pdf->Cell($sideStripW + 2.10, 0.4, strtoupper($itemData['participant_name']), 0, 1, 'L');

        // Terms and conditions (shortened for compact ticket)
        $pdf->SetFont('helvetica', '', 6);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->SetXY($sideStripX - 0.60, $ticketY + $ticketH - 1.90);
        $pdf->MultiCell($sideStripW, 0.3, "Terms:\n• Non-transferable\n• One entry - No refund \n\nTanggal Pembelian : ".tgl_indo8($order->invoice_date), 0, 'L');

        // Reset colors
        $pdf->SetTextColor(0, 0, 0);
    }

    /**
     * Save participants to tbl_peserta when payment is marked as paid
     */
    private function saveParticipantsFromOrder($invoiceId)
    {
        // Get order details
        $orderDetails = $this->transJualDetModel->where('id_penjualan', $invoiceId)->findAll();
        
        if (empty($orderDetails)) {
            log_message('info', "No order details found for invoice ID: {$invoiceId}");
            return;
        }

        // Get order information for user_id
        $order = $this->transJualModel->find($invoiceId);
        if (!$order) {
            log_message('error', "Order not found for invoice ID: {$invoiceId}");
            return;
        }

        $savedCount = 0;
        $errorCount = 0;

        foreach ($orderDetails as $detail) {
            try {
                // Debug: Log the raw item_data
                log_message('info', "Processing order detail ID {$detail->id}, raw item_data: " . ($detail->item_data ?: 'NULL'));
                
                // Parse participant data from item_data JSON
                $itemData = json_decode($detail->item_data, true) ?: [];
                
                // Debug: Log parsed item_data
                log_message('info', "Parsed item_data: " . json_encode($itemData));
                
                // Extract participant information
                $participantName = $itemData['participant_name'] ?? null;
                $participantEmail = $itemData['participant_email'] ?? null;
                $participantPhone = $itemData['participant_phone'] ?? null;
                $participantGender = $itemData['participant_gender'] ?? null;
                $participantBirthDate = $itemData['participant_birth_date'] ?? null;
                $participantBirthPlace = $itemData['participant_birth_place'] ?? null;
                $participantAddress = $itemData['participant_address'] ?? null;

                // Skip if no participant name
                if (empty($participantName)) {
                    log_message('info', "No participant name found for order detail ID: {$detail->id}");
                    continue;
                }
                
                // Debug: Log what participant fields we found
                log_message('info', "Found participant data - Name: {$participantName}, Email: {$participantEmail}, Phone: {$participantPhone}, Gender: {$participantGender}");

                // Generate unique participant code
                $kode_peserta = 'P' . str_pad($detail->id, 6, '0', STR_PAD_LEFT);
                
                // Check if participant already exists for this order detail
                $existingPeserta = $this->pesertaModel->where('kode_peserta', $kode_peserta)->first();
                if ($existingPeserta) {
                    log_message('info', "Participant already exists with code: {$kode_peserta}");
                    continue;
                }

                // Prepare participant data (only fields that exist in database)
                $participantData = [
                    'id_user' => (int)$order->user_id,
                    'kode_peserta' => $kode_peserta,
                    'nama_lengkap' => $participantName,
                    'tempat_lahir' => $participantBirthPlace,
                    'tanggal_lahir' => $participantBirthDate,
                    'jenis_kelamin' => $participantGender === 'male' ? 'L' : ($participantGender === 'female' ? 'P' : 'L'), // Default to 'L' if not specified
                    'alamat' => $participantAddress,
                    'no_hp' => $participantPhone,
                    'email' => $participantEmail,
                    'id_kelompok' => null,
                    'id_kategori' => null,
                    'status' => 1, // Active
                    'qr_code' => $detail->qrcode // Link to the QR code file
                    // Remove tripay fields and manual timestamps - let CodeIgniter handle them
                ];

                // Debug logging
                log_message('info', "Attempting to save participant data: " . json_encode($participantData));

                // Try to save participant with detailed error handling
                $insertResult = $this->pesertaModel->insert($participantData);
                if ($insertResult) {
                    $savedCount++;
                    log_message('info', "Participant saved successfully: {$participantName} with code {$kode_peserta}");
                } else {
                    $errorCount++;
                    $errors = $this->pesertaModel->errors();
                    log_message('error', "Failed to save participant {$participantName}: " . json_encode($errors));
                }

            } catch (\Exception $e) {
                $errorCount++;
                log_message('error', "Exception while saving participant for order detail ID {$detail->id}: " . $e->getMessage());
            }
        }

        log_message('info', "Participants processing completed for invoice {$invoiceId}. Saved: {$savedCount}, Errors: {$errorCount}");
    }

    /**
     * Generate QR codes for all order details when payment is marked as paid
     */
    private function generateQRCodesForOrder($invoiceId)
    {
        try {
            // Get all order details for this invoice
            $orderDetails = $this->transJualDetModel->where('id_penjualan', $invoiceId)->findAll();
            
            if (empty($orderDetails)) {
                log_message('warning', "No order details found for invoice ID: {$invoiceId}");
                return false;
            }

            // Create directory structure for QR codes
            $qrDirectory = FCPATH . 'file/sale/' . $invoiceId . '/qrcode/';
            if (!is_dir($qrDirectory)) {
                if (!mkdir($qrDirectory, 0755, true)) {
                    log_message('error', "Failed to create QR directory: {$qrDirectory}");
                    return false;
                }
            }

            foreach ($orderDetails as $detail) {
                // Remove existing QR code file if it exists
                if (!empty($detail->qrcode)) {
                    $oldFilePath = $qrDirectory . $detail->qrcode;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                        log_message('info', "Removed existing QR code: {$detail->qrcode}");
                    }
                }
                
                // Generate QR content - using detail ID and timestamp
                $qrContent = $detail->id . '|' . $invoiceId . '|' . date('Y-m-d H:i:s');
                
                // Generate unique filename
                $fileName = 'qr_' . $detail->id . '_' . time() . '.png';
                $filePath = $qrDirectory . $fileName;
                
                // Create QR code
                $qrCode = new QrCode($qrContent);
                $writer = new PngWriter();
                $result = $writer->write($qrCode);
                
                // Save QR code to file (overwrite if exists)
                if (file_put_contents($filePath, $result->getString())) {
                    // Update database with QR filename
                    $this->transJualDetModel->update($detail->id, [
                        'qrcode' => $fileName,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    
                    log_message('info', "QR code generated for order detail ID: {$detail->id}, file: {$fileName}");
                } else {
                    log_message('error', "Failed to save QR code file: {$filePath}");
                }
            }
            
            return true;
            
        } catch (\Exception $e) {
            log_message('error', "QR code generation failed for invoice {$invoiceId}: " . $e->getMessage());
            return false;
        }
    }
}
