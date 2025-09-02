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
use CodeIgniter\HTTP\ResponseInterface;

class Sale extends BaseController
{
    protected $transJualModel;
    protected $transJualDetModel;
    protected $transJualPlatModel;
    protected $ionAuth;

    public function __construct()
    {
        $this->transJualModel = new TransJualModel();
        $this->transJualDetModel = new TransJualDetModel();
        $this->transJualPlatModel = new TransJualPlatModel();
        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
    }

    /**
     * Display transaction orders with filtering by status
     */
    public function orders($status = 'all')
    {

        // Build query conditions for the model
        if ($status !== 'all') {
            $validStatuses = ['pending', 'paid', 'failed', 'cancelled'];
            if (in_array($status, $validStatuses)) {
                $this->transJualModel->where('payment_status', $status);
            }
        }

        // Get orders with pagination
        $this->transJualModel->orderBy('invoice_date', 'DESC');
        $orders = $this->transJualModel->paginate(20);
        $pager = $this->transJualModel->pager;

        // Get statistics for status filter buttons using fresh model instances
        $statsModel = new TransJualModel();
        $stats = [
            'all' => $statsModel->countAll(),
            'pending' => $statsModel->where('payment_status', 'pending')->countAllResults(false),
            'paid' => $statsModel->where('payment_status', 'paid')->countAllResults(false),
            'failed' => $statsModel->where('payment_status', 'failed')->countAllResults(false),
            'cancelled' => $statsModel->where('payment_status', 'cancelled')->countAllResults(false),
        ];

        $data = [
            'title' => 'Transaction Management',
            'orders' => $orders,
            'pager' => $pager,
            'current_status' => $status,
            'stats' => $stats
        ];

        return $this->view('admin-lte-3/transaksi/sale/orders', $data);
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

        $newPaymentStatus = $this->request->getPost('payment_status');
        $newOrderStatus = $this->request->getPost('order_status');
        
        $updateData = [
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($newPaymentStatus) {
            $updateData['payment_status'] = $newPaymentStatus;
        }

        if ($newOrderStatus) {
            $updateData['status'] = $newOrderStatus;
        }

        if ($this->transJualModel->update($invoiceId, $updateData)) {
            session()->setFlashdata('success', 'Order status updated successfully');
        } else {
            session()->setFlashdata('error', 'Failed to update order status');
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
    public function export($format = 'csv')
    {
        // Get date range from request
        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?: date('Y-m-t');

        // Get sales data
        $builder = $this->transJualModel->builder();
        $builder->where('invoice_date >=', $startDate);
        $builder->where('invoice_date <=', $endDate . ' 23:59:59');
        $orders = $builder->orderBy('invoice_date', 'DESC')->get()->getResult();

        if ($format === 'csv') {
            // Generate CSV export
            $filename = 'sales_export_' . date('Y-m-d_H-i-s') . '.csv';
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            $output = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($output, [
                'Invoice No',
                'Invoice Date',
                'Customer',
                'Payment Method',
                'Payment Status',
                'Order Status',
                'Total Amount',
                'Created At'
            ]);
            
            // CSV data
            foreach ($orders as $order) {
                // Get customer name using IonAuth
                $customerName = 'Guest User';
                if ($order->user_id) {
                    $user = $this->ionAuth->user($order->user_id)->row();
                    if ($user) {
                        $customerName = $user->first_name . ' ' . $user->last_name . ' (' . $user->username . ')';
                    } else {
                        $customerName = 'User ID: ' . $order->user_id;
                    }
                }
                
                fputcsv($output, [
                    $order->invoice_no,
                    $order->invoice_date,
                    $customerName,
                    $order->payment_method,
                    $order->payment_status,
                    $order->status,
                    $order->total_amount,
                    $order->created_at
                ]);
            }
            
            fclose($output);
            exit;
        }

        // Default to redirect if format not supported
        session()->setFlashdata('error', 'Export format not supported');
        return redirect()->to('admin/transaksi/sale/reports');
    }
}
