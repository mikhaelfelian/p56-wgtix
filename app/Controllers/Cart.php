<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\PlatformModel;
use App\Models\TransJualModel;
use App\Models\TransJualDetModel;
use App\Models\TransJualPlatModel;

class Cart extends BaseController
{
    protected $cartModel;
    protected $platformModel;
    protected $transJualModel;
    protected $transJualDetModel;
    protected $transJualPlatModel;
    protected $ionAuth;

    public function __construct()
    {
        parent::__construct();
        $this->cartModel = new CartModel();
        $this->platformModel = new PlatformModel();
        $this->transJualModel = new TransJualModel();
        $this->transJualDetModel = new TransJualDetModel();
        $this->transJualPlatModel = new TransJualPlatModel();
        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
    }

    /**
     * Add item to cart (AJAX)
     */
    public function add()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            $eventId = $this->request->getPost('event_id');
            $priceId = $this->request->getPost('price_id');
            $quantity = $this->request->getPost('quantity') ?? 1;
            
            // Get additional data from the form
            $eventTitle = $this->request->getPost('event_title') ?? 'Event';
            $eventImage = $this->request->getPost('event_image') ?? '';
            $priceDescription = $this->request->getPost('price_description') ?? 'Ticket';
            $price = $this->request->getPost('price') ?? 0;
            $eventDate = $this->request->getPost('event_date') ?? '';
            $eventLocation = $this->request->getPost('event_location') ?? 'TBA';
            
            // Get participant data
            $participantData = $this->request->getPost('participant_data') ?? [];

            // Debug log
            log_message('info', 'Cart add request: event_id=' . $eventId . ', price_id=' . $priceId . ', quantity=' . $quantity . ', participants=' . count($participantData));

            // Validation
            if (!$eventId || !$priceId || !$price) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Event ID, Price ID, and Price are required',
                    'debug' => ['event_id' => $eventId, 'price_id' => $priceId, 'price' => $price]
                ]);
            }

            // Validate participant data if provided
            if (!empty($participantData)) {
                if (count($participantData) != $quantity) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Jumlah data peserta tidak sesuai dengan quantity',
                        'debug' => ['participant_count' => count($participantData), 'quantity' => $quantity]
                    ]);
                }
                
                // Validate each participant has required fields
                foreach ($participantData as $index => $participant) {
                    if (empty($participant['name']) || empty($participant['gender']) || empty($participant['phone']) || empty($participant['address']) || empty($participant['payment_method'])) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Data peserta ' . ($index + 1) . ' tidak lengkap',
                            'debug' => ['participant' => $participant]
                        ]);
                    }
                    
                    // Validate gender
                    if (!in_array($participant['gender'], ['L', 'P'])) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Jenis kelamin peserta ' . ($index + 1) . ' tidak valid',
                            'debug' => ['gender' => $participant['gender']]
                        ]);
                    }
                    
                    // Validate phone format
                    if (!preg_match('/^08[0-9]{8,13}$/', $participant['phone'])) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Format nomor HP peserta ' . ($index + 1) . ' tidak valid',
                            'debug' => ['phone' => $participant['phone']]
                        ]);
                    }
                    
                    // Validate payment method exists in platform table
                    $platform = $this->platformModel->where(['id' => $participant['payment_method'], 'status' => 1])->first();
                    if (!$platform) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Metode pembayaran peserta ' . ($index + 1) . ' tidak valid',
                            'debug' => ['payment_method' => $participant['payment_method']]
                        ]);
                    }
                }
            }

            // Prepare cart data
            $userId = $this->ionAuth->loggedIn() ? $this->ionAuth->user()->row()->id : null;
            $sessionId = $userId ? null : session_id();

            $cartData = [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'event_id' => $eventId,
                'price_id' => $priceId,
                'quantity' => (int)$quantity,
                'total_price' => (float)$price * (int)$quantity,
                'cart_data' => [
                    'event_title' => $eventTitle,
                    'event_image' => $eventImage,
                    'price_description' => $priceDescription,
                    'harga' => (float)$price,
                    'event_date' => $eventDate,
                    'event_location' => $eventLocation,
                    'participants' => $participantData // Add participant data to cart_data
                ]
            ];

            if ($this->cartModel->addToCart($cartData)) {
                // Get updated cart count
                $cartCount = $this->cartModel->getCartCount($userId, $sessionId);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Item added to cart successfully',
                    'cart_count' => $cartCount,
                    'csrf_token' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to add item to cart',
                    'debug' => 'Cart model addToCart returned false'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Cart add error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
                'debug' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Get cart items (AJAX)
     */
    public function getItems()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $userId = $this->ionAuth->loggedIn() ? $this->ionAuth->user()->row()->id : null;
        $sessionId = $userId ? null : session_id();

        // If user is logged in, try to transfer session cart to user
        if ($userId) {
            $this->transferSessionCartToUser($sessionId, $userId);
        }

        $cartItems = $this->cartModel->getCartItems($userId, $sessionId);
        $cartTotal = $this->cartModel->getCartTotal($userId, $sessionId);
        $cartCount = $this->cartModel->getCartCount($userId, $sessionId);

        // Debug logging
        log_message('info', 'Cart getItems - userId: ' . $userId . ', sessionId: ' . $sessionId . ', items count: ' . count($cartItems));

        return $this->response->setJSON([
            'success' => true,
            'items' => $cartItems,
            'total' => $cartTotal,
            'count' => $cartCount,
            'formatted_total' => 'Rp ' . number_format($cartTotal, 0, ',', '.'),
            'debug' => [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'items_count' => count($cartItems)
            ]
        ]);
    }

    /**
     * Update cart item quantity (AJAX)
     */
    public function updateQuantity()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $cartId = $this->request->getPost('cart_id');
        $quantity = $this->request->getPost('quantity');

        if (!$cartId || !$quantity || $quantity < 1) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid cart ID or quantity'
            ]);
        }

        $userId = $this->ionAuth->loggedIn() ? $this->ionAuth->user()->row()->id : null;
        $sessionId = $userId ? null : session_id();

        if ($this->cartModel->updateQuantity($cartId, $quantity, $userId, $sessionId)) {
            $cartTotal = $this->cartModel->getCartTotal($userId, $sessionId);
            $cartCount = $this->cartModel->getCartCount($userId, $sessionId);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Quantity updated successfully',
                'cart_total' => $cartTotal,
                'cart_count' => $cartCount,
                'formatted_total' => 'Rp ' . number_format($cartTotal, 0, ',', '.'),
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update quantity'
            ]);
        }
    }

    /**
     * Remove item from cart (AJAX)
     */
    public function remove()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $cartId = $this->request->getPost('cart_id');

        if (!$cartId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cart ID is required'
            ]);
        }

        $userId = $this->ionAuth->loggedIn() ? $this->ionAuth->user()->row()->id : null;
        $sessionId = $userId ? null : session_id();

        if ($this->cartModel->removeFromCart($cartId, $userId, $sessionId)) {
            $cartTotal = $this->cartModel->getCartTotal($userId, $sessionId);
            $cartCount = $this->cartModel->getCartCount($userId, $sessionId);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Item removed from cart',
                'cart_total' => $cartTotal,
                'cart_count' => $cartCount,
                'formatted_total' => 'Rp ' . number_format($cartTotal, 0, ',', '.'),
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to remove item from cart'
            ]);
        }
    }

    /**
     * Clear all cart items (AJAX)
     */
    public function clear()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $userId = $this->ionAuth->loggedIn() ? $this->ionAuth->user()->row()->id : null;
        $sessionId = $userId ? null : session_id();

        if ($this->cartModel->clearCart($userId, $sessionId)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Cart cleared successfully',
                'cart_count' => 0,
                'cart_total' => 0,
                'formatted_total' => 'Rp 0',
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to clear cart'
            ]);
        }
    }

    /**
     * Get cart count (AJAX) - for navbar counter
     */
    public function getCount()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $userId = $this->ionAuth->loggedIn() ? $this->ionAuth->user()->row()->id : null;
        $sessionId = $userId ? null : session_id();

        // If user is logged in, try to transfer session cart to user
        if ($userId) {
            $this->transferSessionCartToUser($sessionId, $userId);
        }

        $cartCount = $this->cartModel->getCartCount($userId, $sessionId);

        return $this->response->setJSON([
            'success' => true,
            'count' => $cartCount
        ]);
    }

    /**
     * Display cart page
     */
    public function index()
    {
        $userId = $this->ionAuth->loggedIn() ? $this->ionAuth->user()->row()->id : null;
        $sessionId = $userId ? null : session_id();

        // If user is logged in, try to transfer session cart to user
        if ($userId) {
            $this->transferSessionCartToUser($sessionId, $userId);
        }

        $cartItems = $this->cartModel->getCartItems($userId, $sessionId);
        $cartTotal = $this->cartModel->getCartTotal($userId, $sessionId);
        $cartCount = $this->cartModel->getCartCount($userId, $sessionId);

        // Get active payment platforms
        $platforms = $this->platformModel->getByStatus(1);

        $data = [
            'title'           => 'Shopping Cart',
            'cart_items'      => $cartItems,
            'cart_total'      => $cartTotal,
            'cart_count'      => $cartCount,
            'formatted_total' => format_angka($cartTotal),
            'platforms'       => $platforms,
        ];

        return $this->view('da-theme/cart/index', $data);
    }
    
    /**
     * Get platforms (AJAX)
     */
    public function getPlatforms()
    {
        $platforms = $this->platformModel->getByStatus(1);
        
        return $this->response->setJSON([
            'success' => true,
            'platforms' => $platforms
        ]);
    }
    
    /**
     * Store order - Process checkout
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
                    // Ensure platform_id exists
                    if (!isset($payment['platform_id'])) {
                        continue; // Skip this payment
                    }
                    
                    $platData = [
                        'id_penjualan'  => $lastId,
                        'id_platform'   => $payment['platform_id'],
                        'no_nota'       => $data['no_nota'],
                        'platform'      => $payment['platform']       ?? '',
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
            
            return redirect()->to(base_url('sale/orders'))->with('error', $e->getMessage());
        }
    }

    /**
     * Handle Tripay Payment Gateway
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
        if (!$platform || $platform->status_gateway != 1) {
            return $this->response->setStatusCode(400)->setBody('Invalid payment gateway');
        }

        // Prepare Tripay API request
        $tripayApiKey = getenv('TRIPAY_API_KEY');
        $tripayMerchantCode = getenv('TRIPAY_MERCHANT_CODE');
        $tripayPrivateKey = getenv('TRIPAY_PRIVATE_KEY');
        $tripayApiUrl = 'https://tripay.co.id/api/transaction/create';

        // Prepare payload
        $payload = [
            'method'         => $platform->kode_gateway, // e.g. 'BRIVA', 'BNIVA', etc.
            'merchant_ref'   => $order->invoice_no,
            'amount'         => $order->total_amount,
            'customer_name'  => $order->user_id ? $this->ionAuth->user($order->user_id)->row()->first_name : 'Guest',
            'customer_email' => $order->user_id ? $this->ionAuth->user($order->user_id)->row()->email : '',
            'order_items'    => [],
            'callback_url'   => base_url('sale/tripay/callback'),
            'return_url'     => base_url('sale/orders'),
            'expired_time'   => strtotime('+1 day'),
            'signature'      => hash_hmac('sha256', $tripayMerchantCode.$order->invoice_no.$order->total_amount, $tripayPrivateKey)
        ];

        // Get order items
        $orderItems = $this->transJualDetModel->where('id_penjualan', $orderId)->findAll();
        foreach ($orderItems as $item) {
            $payload['order_items'][] = [
                'sku'      => $item->event_id,
                'name'     => $item->event_title,
                'price'    => $item->unit_price,
                'quantity' => $item->quantity,
                'product_url' => base_url('event/detail/' . $item->event_id)
            ];
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

            if (isset($result['success']) && $result['success'] && isset($result['data']['checkout_url'])) {
                // Optionally, save Tripay reference to order
                $this->transJualModel->update($orderId, [
                    'tripay_ref' => $result['data']['reference'] ?? null,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                // Redirect to Tripay checkout
                return redirect()->to($result['data']['checkout_url']);
            } else {
                $errorMsg = isset($result['message']) ? $result['message'] : 'Failed to create Tripay transaction';
                return $this->response->setStatusCode(500)->setBody('Tripay Error: ' . $errorMsg);
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setBody('Tripay Exception: ' . $e->getMessage());
        }
    }

    /**
     * Handle Tripay Payment Gateway Callback
     */
    public function pg_tripay_callback()
    {
        // Tripay will POST JSON to this endpoint
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // Log callback for debugging
        log_message('info', 'Tripay Callback: ' . $json);

        if (!$data || !isset($data['merchant_ref']) || !isset($data['status'])) {
            return $this->response->setStatusCode(400)->setBody('Invalid callback data');
        }

        // Find order by merchant_ref (invoice_no)
        $order = $this->transJualModel->where('invoice_no', $data['merchant_ref'])->first();
        if (!$order) {
            return $this->response->setStatusCode(404)->setBody('Order not found');
        }

        // Update payment status based on Tripay status
        $status = $data['status'];
        $update = [];
        if ($status == 'PAID') {
            $update['payment_status'] = 'paid';
            $update['status'] = 'completed';
        } elseif ($status == 'EXPIRED') {
            $update['payment_status'] = 'expired';
            $update['status'] = 'expired';
        } elseif ($status == 'FAILED') {
            $update['payment_status'] = 'failed';
            $update['status'] = 'failed';
        } else {
            $update['payment_status'] = strtolower($status);
        }
        $update['updated_at'] = date('Y-m-d H:i:s');

        $this->transJualModel->update($order->id, $update);

        // Optionally, log the update
        log_message('info', 'Tripay Callback Update: Order ' . $order->id . ' status updated to ' . $status);

        // Respond to Tripay
        return $this->response->setJSON(['success' => true]);
    }

    /**
     * Transfer session cart items to user account
     */
    private function transferSessionCartToUser($sessionId, $userId)
    {
        try {
            // Only process session-based cart items (where user_id IS NULL)
            $sessionCartItems = $this->cartModel->where('session_id', $sessionId)
                                              ->where('user_id IS NULL')
                                              ->where('status', 'active')
                                              ->findAll();
            
            if (empty($sessionCartItems)) {
                return true; // No session cart items to transfer
            }
            
            // Process session items
            $this->processSessionItems($sessionCartItems, $userId);
            
            return true;

        } catch (\Exception $e) {
            log_message('error', 'Failed to transfer session cart to user: ' . $e->getMessage());
            return false;
        }
    }
    
    private function processSessionItems($sessionCartItems, $userId)
    {
        foreach ($sessionCartItems as $sessionItem) {
            $existingUserItem = $this->cartModel->where('user_id', $userId)
                                               ->where('event_id', $sessionItem->event_id)
                                               ->where('price_id', $sessionItem->price_id)
                                               ->where('status', 'active')
                                               ->first();

            if ($existingUserItem) {
                // Merge quantities and update existing item
                $newQuantity = $existingUserItem->quantity + $sessionItem->quantity;
                $cartData = json_decode($existingUserItem->cart_data, true);
                $unitPrice = $cartData['harga'] ?? 0;
                $newTotalPrice = $newQuantity * $unitPrice;

                $this->cartModel->update($existingUserItem->id, [
                    'quantity' => $newQuantity,
                    'total_price' => $newTotalPrice,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                // Delete the session item
                $this->cartModel->delete($sessionItem->id);
            } else {
                // Transfer session item to user
                $this->cartModel->update($sessionItem->id, [
                    'user_id' => $userId,
                    'session_id' => null,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        
        log_message('info', 'Session cart transferred to user: ' . $userId . ', items processed: ' . count($sessionCartItems));
    }

    /**
     * Debug method to test cart queries directly
     */
    public function debug()
    {
        $userId = $this->ionAuth->loggedIn() ? $this->ionAuth->user()->row()->id : null;
        $sessionId = $userId ? null : session_id();

        // Test direct database query
        $db = \Config\Database::connect();
        $query = $db->table('tbl_cart')
                   ->where('status', 'active')
                   ->where('user_id', $userId)
                   ->get();
        
        $results = $query->getResult();
        
        // Test query without soft deletes
        $queryNoSoftDelete = $db->table('tbl_cart')
                               ->where('status', 'active')
                               ->where('user_id', $userId)
                               ->where('deleted_at IS NULL')
                               ->get();
        
        $resultsNoSoftDelete = $queryNoSoftDelete->getResult();
        
        // Check if cart item has deleted_at value
        $checkDeleted = $db->table('tbl_cart')
                          ->where('user_id', $userId)
                          ->get();
        
        $allUserItems = $checkDeleted->getResult();
        
        return $this->response->setJSON([
            'success' => true,
            'debug' => [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'direct_query_count' => count($results),
                'direct_query_results' => $results,
                'no_soft_delete_count' => count($resultsNoSoftDelete),
                'no_soft_delete_results' => $resultsNoSoftDelete,
                'all_user_items' => $allUserItems,
                'model_query_count' => count($this->cartModel->getCartItems($userId, $sessionId))
            ]
        ]);
    }
}
