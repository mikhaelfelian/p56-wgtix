<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    // Table & Primary Key
    protected $table            = 'tbl_cart';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;

    // Allowed Fields
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'session_id',
        'event_id',
        'price_id',
        'cart_data',
        'quantity',
        'total_price',
        'status'
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'event_id'    => 'required|integer',
        'price_id'    => 'required|integer',
        'quantity'    => 'required|integer|greater_than[0]',
        'total_price' => 'required|decimal'
    ];

    protected $validationMessages = [
        'event_id' => [
            'required' => 'Event ID is required',
            'integer'  => 'Event ID must be an integer'
        ],
        'price_id' => [
            'required' => 'Price ID is required',
            'integer'  => 'Price ID must be an integer'
        ],
        'quantity' => [
            'required'     => 'Quantity is required',
            'integer'      => 'Quantity must be an integer',
            'greater_than' => 'Quantity must be greater than 0'
        ]
    ];

    protected $skipValidation        = false;
    protected $cleanValidationRules  = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get cart items for a user or session
     */
    public function getCartItems($userId = null, $sessionId = null)
    {
        $builder = $this->select('*')
                        ->where('status', 'active');

        if ($userId) {
            $builder->where('user_id', $userId);
        } else if ($sessionId) {
            $builder->where('session_id', $sessionId);
        }

        // Debug logging
        log_message('info', 'CartModel getCartItems - userId: ' . $userId . ', sessionId: ' . $sessionId);

        $result = $builder->orderBy('created_at', 'DESC')->findAll();
        
        log_message('info', 'CartModel result count: ' . count($result));
        
        // Debug: Log first result if exists
        if (!empty($result)) {
            log_message('info', 'CartModel first item: ' . json_encode($result[0]));
        } else {
            log_message('info', 'CartModel: No items found for userId=' . $userId . ', sessionId=' . $sessionId);
        }
        
        return $result;
    }

    /**
     * Get cart count for a user or session
     */
    public function getCartCount($userId = null, $sessionId = null)
    {
        $builder = $this->where('status', 'active');

        if ($userId) {
            $builder->where('user_id', $userId);
        } else if ($sessionId) {
            $builder->where('session_id', $sessionId);
        }

        return $builder->countAllResults();
    }

    /**
     * Get cart total for a user or session
     */
    public function getCartTotal($userId = null, $sessionId = null)
    {
        $builder = $this->selectSum('total_price')->where('status', 'active');

        if ($userId) {
            $builder->where('user_id', $userId);
        } else if ($sessionId) {
            $builder->where('session_id', $sessionId);
        }

        $result = $builder->get()->getRow();
        return $result ? (float)$result->total_price : 0;
    }

    /**
     * Add item to cart or update quantity if exists
     */
    public function addToCart($data)
    {
        // Check if item already exists in cart
        $builder = $this->where('status', 'active')
                        ->where('event_id', $data['event_id'])
                        ->where('price_id', $data['price_id']);

        if (isset($data['user_id']) && $data['user_id']) {
            $builder->where('user_id', $data['user_id']);
        } else if (isset($data['session_id']) && $data['session_id']) {
            $builder->where('session_id', $data['session_id']);
        }

        $existingItem = $builder->first();

        if ($existingItem) {
            // Update quantity and total price
            $newQuantity = $existingItem->quantity + $data['quantity'];
            $newTotalPrice = $newQuantity * ($data['total_price'] / $data['quantity']);
            
            return $this->update($existingItem->id, [
                'quantity' => $newQuantity,
                'total_price' => $newTotalPrice,
                'cart_data' => json_encode(array_merge(json_decode($existingItem->cart_data, true), $data['cart_data']))
            ]);
        } else {
            // Insert new item
            $data['cart_data'] = json_encode($data['cart_data']);
            return $this->insert($data);
        }
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity($cartId, $quantity, $userId = null, $sessionId = null)
    {
        $builder = $this->where('id', $cartId)->where('status', 'active');

        if ($userId) {
            $builder->where('user_id', $userId);
        } else if ($sessionId) {
            $builder->where('session_id', $sessionId);
        }

        $cartItem = $builder->first();
        
        if ($cartItem) {
            $cartData = json_decode($cartItem->cart_data, true);
            $unitPrice = $cartData['harga'] ?? 0;
            $newTotalPrice = $quantity * $unitPrice;

            return $this->update($cartId, [
                'quantity' => $quantity,
                'total_price' => $newTotalPrice
            ]);
        }

        return false;
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($cartId, $userId = null, $sessionId = null)
    {
        $builder = $this->where('id', $cartId)->where('status', 'active');

        if ($userId) {
            $builder->where('user_id', $userId);
        } else if ($sessionId) {
            $builder->where('session_id', $sessionId);
        }

        return $builder->delete();
    }

    /**
     * Clear all cart items for a user or session
     */
    public function clearCart($userId = null, $sessionId = null)
    {
        $builder = $this->where('status', 'active');

        if ($userId) {
            $builder->where('user_id', $userId);
        } else if ($sessionId) {
            $builder->where('session_id', $sessionId);
        }

        return $builder->delete();
    }

    /**
     * Transfer cart items from session to user (when user logs in)
     */
    public function transferSessionCartToUser($sessionId, $userId)
    {
        return $this->where('session_id', $sessionId)
                    ->where('status', 'active')
                    ->set('user_id', $userId)
                    ->set('session_id', null)
                    ->update();
    }
}
