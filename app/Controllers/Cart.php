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

            // Debug log
            log_message('info', 'Cart add request: event_id=' . $eventId . ', price_id=' . $priceId . ', quantity=' . $quantity);

            // Validation
            if (!$eventId || !$priceId || !$price) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Event ID, Price ID, and Price are required',
                    'debug' => ['event_id' => $eventId, 'price_id' => $priceId, 'price' => $price]
                ]);
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
                    'event_location' => $eventLocation
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

        $cartItems = $this->cartModel->getCartItems($userId, $sessionId);
        $cartTotal = $this->cartModel->getCartTotal($userId, $sessionId);
        $cartCount = $this->cartModel->getCartCount($userId, $sessionId);

        return $this->response->setJSON([
            'success' => true,
            'items' => $cartItems,
            'total' => $cartTotal,
            'count' => $cartCount,
            'formatted_total' => 'Rp ' . number_format($cartTotal, 0, ',', '.')
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

        $cartCount = $this->cartModel->getCartCount($userId, $sessionId);

        return $this->response->setJSON([
            'success' => true,
            'count' => $cartCount
        ]);
    }

    /**
     * Test method to debug cart functionality
     */
    public function test()
    {
        $userId = $this->ionAuth->loggedIn() ? $this->ionAuth->user()->row()->id : null;
        $sessionId = $userId ? null : session_id();
        
        return $this->response->setJSON([
            'success' => true,
            'user_id' => $userId,
            'session_id' => $sessionId,
            'logged_in' => $this->ionAuth->loggedIn(),
            'csrf_token' => csrf_token(),
            'csrf_hash' => csrf_hash()
        ]);
    }

    /**
     * Display cart page
     */
    public function index()
    {
        $userId = $this->ionAuth->loggedIn() ? $this->ionAuth->user()->row()->id : null;
        $sessionId = $userId ? null : session_id();

        $cartItems = $this->cartModel->getCartItems($userId, $sessionId);
        $cartTotal = $this->cartModel->getCartTotal($userId, $sessionId);
        $cartCount = $this->cartModel->getCartCount($userId, $sessionId);

        // Get active payment platforms
        $platforms = $this->platformModel->getByStatus(1);

        $data = [
            'title' => 'Shopping Cart',
            'cart_items' => $cartItems,
            'cart_total' => $cartTotal,
            'cart_count' => $cartCount,
            'formatted_total' => 'Rp ' . number_format($cartTotal, 0, ',', '.'),
            'platforms' => $platforms
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
                'payment_method'  => 'multiple', // Since we can have multiple payment platforms
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
                    $platData = [
                        'id_penjualan'  => $lastId,
                        'id_platform'   => $payment['platform_id']    ?? 0,
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

            // Redirect to orders page on success
            return redirect()->to(base_url('my/orders'));
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            
            $errorData = [
                'error' => $e->getMessage(),
                'original_data' => $data,
                'transaction_status' => 'FAILED'
            ];
            
            pre($errorData);
        }
    }
    
    /**
     * Process order data
     */
    private function processOrder($data)
    {
        try {
            // Here you can implement your order processing logic
            // For now, let's just log the data and return success
            
            log_message('info', 'Processing order: ' . json_encode($data));
            
            // You can add database operations here:
            // 1. Save to tbl_trans_jual (invoice)
            // 2. Save to tbl_trans_jual_det (invoice details)
            // 3. Save participants if needed
            // 4. Process payments
            
            return [
                'success' => true,
                'message' => 'Order processed successfully',
                'invoice_no' => $data['no_nota']
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to process order: ' . $e->getMessage()
            ];
        }
    }
}
