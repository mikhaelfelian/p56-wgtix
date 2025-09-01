<?php

/**
 * Sale Transaction Controller
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-09-01
 * Github: github.com/mikhaelfelian
 * Description: Controller for managing sales transactions and order management
 * This file represents the Sale Transaction Controller.
 */

namespace App\Controllers\Transaksi;

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
        parent::__construct();
        $this->transJualModel = new TransJualModel();
        $this->transJualDetModel = new TransJualDetModel();
        $this->transJualPlatModel = new TransJualPlatModel();
        $this->platformModel = new PlatformModel();
        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
    }

    /**
     * Display orders based on payment status (Frontend)
     */
    public function orders($status = 'all')
    {
        // Check if user is logged in
        if (!$this->ionAuth->loggedIn()) {
            return redirect()->to('auth/login');
        }

        // Get current user info
        $user = $this->ionAuth->user()->row();
        $userGroups = $this->ionAuth->getUsersGroups($user->id)->getResult();
        
        // Check if user has admin access
        $hasAdminAccess = false;
        foreach ($userGroups as $group) {
            if (in_array($group->name, ['admin', 'super_admin', 'manager'])) {
                $hasAdminAccess = true;
                break;
            }
        }

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
            'has_admin_access' => $hasAdminAccess,
            'user' => $user
        ];

        return $this->view('da-theme/transaksi/sale/orders', $data);
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
        $userGroups = $this->ionAuth->getUsersGroups($user->id)->getResult();
        
        // Check if user has admin access
        $hasAdminAccess = false;
        foreach ($userGroups as $group) {
            if (in_array($group->name, ['admin', 'super_admin', 'manager'])) {
                $hasAdminAccess = true;
                break;
            }
        }

        // Get order header - users can only see their own orders
        $order = $this->transJualModel->where([
            'user_id' => $user->id,
            'id' => $invoiceId
        ])->first();

        if (!$order) {
            session()->setFlashdata('error', 'Order not found or access denied');
            return redirect()->to('my/orders');
        }

        // Get order details
        $orderDetails = $this->transJualDetModel->where('id_penjualan', $invoiceId)->findAll();

        // Get payment platforms
        $paymentPlatforms = $this->transJualPlatModel->where('id_penjualan', $invoiceId)->findAll();

        $data = [
            'title' => 'Order Details - ' . $order->invoice_no,
            'order' => $order,
            'order_details' => $orderDetails,
            'payment_platforms' => $paymentPlatforms,
            'has_admin_access' => $hasAdminAccess
        ];

        return $this->view('da-theme/transaksi/sale/detail', $data);
    }

    /**
     * Update order payment status (Admin only)
     */
    public function updateStatus()
    {
        if (!$this->ionAuth->loggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authorized']);
        }

        $user = $this->ionAuth->user()->row();
        $userGroups = $this->ionAuth->getUsersGroups($user->id)->getResult();
        
        // Check if user has admin access
        $hasAdminAccess = false;
        foreach ($userGroups as $group) {
            if (in_array($group->name, ['admin', 'super_admin', 'manager'])) {
                $hasAdminAccess = true;
                break;
            }
        }

        if (!$hasAdminAccess) {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $orderId = $this->request->getPost('order_id');
        $newStatus = $this->request->getPost('status');
        $notes = $this->request->getPost('notes');

        $validStatuses = ['pending', 'paid', 'failed', 'cancelled'];
        if (!in_array($newStatus, $validStatuses)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status']);
        }

        $updateData = [
            'payment_status' => $newStatus,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($notes) {
            $updateData['notes'] = $notes;
        }

        $result = $this->transJualModel->update($orderId, $updateData);

        if ($result) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Order status updated successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Failed to update order status'
            ]);
        }
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
     * Export orders to Excel (Admin only)
     */
    public function export($status = 'all')
    {
        if (!$this->ionAuth->loggedIn()) {
            return redirect()->to('auth/login');
        }

        $user = $this->ionAuth->user()->row();
        $userGroups = $this->ionAuth->getUsersGroups($user->id)->getResult();
        
        // Check if user has admin access
        $hasAdminAccess = false;
        foreach ($userGroups as $group) {
            if (in_array($group->name, ['admin', 'super_admin', 'manager'])) {
                $hasAdminAccess = true;
                break;
            }
        }

        if (!$hasAdminAccess) {
            session()->setFlashdata('error', 'Admin access required');
            return redirect()->to('my/orders');
        }

        // TODO: Implement Excel export functionality
        session()->setFlashdata('info', 'Export functionality will be implemented soon');
        return redirect()->to('my/orders/' . $status);
    }
}
