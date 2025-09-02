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
}
