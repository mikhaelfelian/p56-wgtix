<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\PlatformModel;

class Cart extends BaseController
{
    protected $cartModel;
    protected $platformModel;
    protected $ionAuth;

    public function __construct()
    {
        parent::__construct();
        $this->cartModel = new CartModel();
        $this->platformModel = new PlatformModel();
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
        // if (!$this->request->isPost()) {
        //     return redirect()->back()->with('error', 'Invalid request method');
        // }

        // try {
            // Get order data from form
            $orderDataJson = $this->request->getPost('order_data');

            pre(json_decode($orderDataJson, true));
            
        //     if (empty($orderDataJson)) {
        //         return redirect()->back()->with('error', 'No order data received');
        //     }
            
        //     // Decode JSON data
        //     $data = json_decode($orderDataJson, true);
            
        //     if (json_last_error() !== JSON_ERROR_NONE) {
        //         return redirect()->back()->with('error', 'Invalid order data format');
        //     }
            
        //     // Show debug preview
        //     echo '<pre>';
        //     print_r($data);
        //     echo '</pre>';
        //     exit;

        //     // Validate required fields
        //     if (empty($data['no_nota']) || empty($data['cart_data']) || empty($data['subtotal'])) {
        //         return redirect()->back()->with('error', 'Missing required order information');
        //     }
            
        //     // Process the order
        //     $result = $this->processOrder($data);
            
        //     if ($result['success']) {
        //         // Clear cart after successful order
        //         $userId = $this->ionAuth->loggedIn() ? $this->ionAuth->user()->row()->id : null;
        //         $sessionId = $userId ? null : session_id();
        //         $this->cartModel->clearCart($userId, $sessionId);
                
        //         return redirect()->to('cart')->with('success', 'Order processed successfully! Invoice: ' . $data['no_nota']);
        //     } else {
        //         return redirect()->back()->with('error', $result['message']);
        //     }
            
        // } catch (\Exception $e) {
        //     log_message('error', 'Order processing error: ' . $e->getMessage());
        //     return redirect()->back()->with('error', 'An error occurred while processing your order');
        // }
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
