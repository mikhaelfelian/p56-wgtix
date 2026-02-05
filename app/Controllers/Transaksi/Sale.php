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
use App\Models\PesertaModel;
use App\Libraries\InvoicePdf;
use App\Libraries\TicketPdf;
use App\Libraries\DotMatrixInvoicePdf;

class Sale extends BaseController
{
    protected $transJualModel;
    protected $transJualDetModel;
    protected $transJualPlatModel;
    protected $platformModel;
    protected $pesertaModel;
    protected $ionAuth;

    public function __construct()
    {
        parent::__construct();
        $this->transJualModel = new TransJualModel();
        $this->transJualDetModel = new TransJualDetModel();
        $this->transJualPlatModel = new TransJualPlatModel();
        $this->platformModel = new PlatformModel();
        $this->pesertaModel = new PesertaModel();
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
            return redirect()->to('sale/orders');
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
        // Step 1: Authentication check
        if (!$this->ionAuth->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Not authorized'
            ]);
        }

        // Step 2: Authorization check (admin access)
        $user = $this->ionAuth->user()->row();
        $userGroups = $this->ionAuth->getUsersGroups($user->id)->getResult();
        $hasAdminAccess = false;
        foreach ($userGroups as $group) {
            if (in_array($group->name, ['admin', 'super_admin', 'manager'])) {
                $hasAdminAccess = true;
                break;
            }
        }
        if (!$hasAdminAccess) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Admin access required'
            ]);
        }

        // Step 3: Input validation
        $orderId   = $this->request->getPost('order_id');
        $newStatus = $this->request->getPost('status');
        $notes     = $this->request->getPost('notes');

        $validStatuses = ['pending', 'paid', 'failed', 'cancelled'];
        if (!in_array($newStatus, $validStatuses)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid status'
            ]);
        }

        // Step 4: Retrieve current order
        $currentOrder = $this->transJualModel->find($orderId);
        if (!$currentOrder) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order not found'
            ]);
        }

        // Step 5: Prepare update data
        $updateData = [
            'payment_status' => $newStatus,
            'updated_at'     => date('Y-m-d H:i:s')
        ];
        if (!empty($notes)) {
            $updateData['notes'] = $notes;
        }

        // Step 6: Update order
        $result = $this->transJualModel->update($orderId, $updateData);

        // Step 7: Post-update actions and response
        if ($result) {
            // If payment status changed to 'paid', generate QR codes and save participants
            if ($newStatus === 'paid' && $currentOrder->payment_status !== 'paid') {
                $this->generateQRCodesForOrder($orderId);
                $this->saveParticipantsFromOrder($orderId);
            }
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
            return redirect()->to('sale/orders');
        }

        // TODO: Implement Excel export functionality
        session()->setFlashdata('info', 'Export functionality will be implemented soon');
        return redirect()->to('sale/orders/' . $status);
    }

    /**
     * Generate and download invoice PDF (Frontend)
     */
    public function downloadInvoice($invoiceId)
    {
        // Check if user is logged in
        if (!$this->ionAuth->loggedIn()) {
            return redirect()->to('auth/login');
        }

        $user = $this->ionAuth->user()->row();

        // Get order details and ensure user can only access their own orders
        $order = $this->transJualModel->where([
            'id' => $invoiceId,
            'user_id' => $user->id // Security check
        ])->first();
        
        if (!$order) {
            session()->setFlashdata('error', 'Order not found or access denied');
            return redirect()->to('sale/orders');
        }

        // Get order items
        $orderDetails = $this->transJualDetModel->where('id_penjualan', $invoiceId)->findAll();

        // Get payment platforms
        $paymentPlatforms = $this->transJualPlatModel->where('id_penjualan', $invoiceId)->findAll();

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
            return redirect()->to('sale/order/' . $invoiceId);
        }
    }

    /**
     * Generate and download dot matrix style invoice PDF (Frontend)
     */
    public function downloadDotMatrixInvoice($invoiceId)
    {
        // Check if user is logged in
        if (!$this->ionAuth->loggedIn()) {
            return redirect()->to('auth/login');
        }

        $user = $this->ionAuth->user()->row();

        // Get order details and ensure user can only access their own orders
        $order = $this->transJualModel->where([
            'id' => $invoiceId,
            'user_id' => $user->id // Security check
        ])->first();
        
        if (!$order) {
            session()->setFlashdata('error', 'Order not found or access denied');
            return redirect()->to('sale/orders');
        }

        // Get order items
        $orderDetails = $this->transJualDetModel->where('id_penjualan', $invoiceId)->findAll();

        // Get payment platforms
        $paymentPlatforms = $this->transJualPlatModel->where('id_penjualan', $invoiceId)->findAll();

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
            return redirect()->to('sale/order/' . $invoiceId);
        }
    }

    /**
     * Generate and download ticket PDF for a specific order detail (Frontend)
     */
    public function downloadTicket($orderDetailId)
    {
        // Check if user is logged in
        if (!$this->ionAuth->loggedIn()) {
            return redirect()->to('auth/login');
        }

        $user = $this->ionAuth->user()->row();

        // Get order detail
        $orderDetail = $this->transJualDetModel->find($orderDetailId);
        
        if (!$orderDetail) {
            session()->setFlashdata('error', 'Ticket not found');
            return redirect()->to('sale/orders');
        }

        // Get main order and ensure user can only access their own tickets
        $order = $this->transJualModel->where([
            'id' => $orderDetail->id_penjualan,
            'user_id' => $user->id // Security check
        ])->first();
        
        if (!$order) {
            session()->setFlashdata('error', 'Order not found or access denied');
            return redirect()->to('sale/orders');
        }

        // Get event info
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
            return redirect()->to('sale/order/' . $orderDetail->id_penjualan);
        }
    }

    /**
     * Generate and download all tickets for an order (Frontend)
     * Creates individual tickets for each participant with their QR codes
     */
    public function downloadAllTickets($invoiceId)
    {
        // Check if user is logged in
        if (!$this->ionAuth->loggedIn()) {
            return redirect()->to('auth/login');
        }

        $user = $this->ionAuth->user()->row();

        // Get order and ensure user can only access their own orders
        $order = $this->transJualModel->where([
            'id' => $invoiceId,
            'user_id' => $user->id // Security check
        ])->first();
        
        if (!$order) {
            session()->setFlashdata('error', 'Order not found or access denied');
            return redirect()->to('sale/orders');
        }

        // Get all order details (each represents a participant/ticket)
        $orderDetails = $this->transJualDetModel->where('id_penjualan', $invoiceId)->findAll();
        
        if (empty($orderDetails)) {
            session()->setFlashdata('error', 'No tickets found for this order');
            return redirect()->to('sale/order/' . $invoiceId);
        }

        try {
            // Create a master PDF to combine all individual tickets
            $masterPdf = new \TCPDF('L', 'mm', array(210, 148.5), true, 'UTF-8', false);
            $masterPdf->SetCreator('WGTIX System');
            $masterPdf->SetAuthor('WGTIX Event Management');
            $masterPdf->SetTitle('Event Tickets - Invoice #' . $order->invoice_no);
            $masterPdf->SetMargins(0, 0, 0);
            $masterPdf->SetAutoPageBreak(FALSE);

            // Generate individual ticket for each order detail/participant
            foreach ($orderDetails as $index => $orderDetail) {
                // Event info
                $event = (object)[
                    'title' => $orderDetail->event_title ?: 'Event',
                    'date' => $order->invoice_date,
                    'location' => 'Event Venue'
                ];

                // Create individual ticket PDF
                $ticketPdf = new TicketPdf($orderDetail, $order, $user, $event);
                $ticketContent = $ticketPdf->generateTicket();
                
                // Add page to master PDF
                $masterPdf->AddPage();
                
                // Import the individual ticket content
                // We need to recreate the ticket content in the master PDF
                $this->drawTicketInMasterPdf($masterPdf, $orderDetail, $order, $user, $event, $index + 1, count($orderDetails));
            }
            
            // Set headers for PDF download
            $filename = 'All_Tickets_' . $order->invoice_no . '_' . date('Y-m-d') . '.pdf';
            
            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->setHeader('Cache-Control', 'private, max-age=0, must-revalidate')
                ->setHeader('Pragma', 'public')
                ->setBody($masterPdf->Output('S'));
                
        } catch (\Exception $e) {
            log_message('error', 'All tickets generation failed for invoice ' . $invoiceId . ': ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to generate tickets: ' . $e->getMessage());
            return redirect()->to('sale/order/' . $invoiceId);
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
            
            if (isset($orderDetail->sort_num)) {
                $pdf->SetXY(10, 101);
                $pdf->Cell(140, 6, 'Participant #: ' . $orderDetail->sort_num, 0, 1, 'L');
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
     * Save participants to tbl_peserta when payment is marked as paid (Frontend)
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
     * Generate QR codes for all order details when payment is marked as paid (Frontend)
     */
    private function generateQRCodesForOrder($invoiceId)
    {
        try {
            // Get all order details for this invoice
            $orderDetails = $this->transJualDetModel->where('id_penjualan', $invoiceId)->findAll();
            
            if (empty($orderDetails)) {
                log_message('info', "No order details found for invoice ID: {$invoiceId}");
                return;
            }

            // Create directory if it doesn't exist
            $qrDirectory = FCPATH . 'file/sale/' . $invoiceId . '/qrcode/';
            if (!is_dir($qrDirectory)) {
                if (!mkdir($qrDirectory, 0755, true)) {
                    log_message('error', "Failed to create QR directory: {$qrDirectory}");
                    return;
                }
                
                // Create index.php for security
                file_put_contents($qrDirectory . 'index.php', '<?php // Silence is golden');
                log_message('info', "QR directory created: {$qrDirectory}");
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
                $qrCode = new \Endroid\QrCode\QrCode($qrContent);
                $writer = new \Endroid\QrCode\Writer\PngWriter();
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
            
        } catch (\Exception $e) {
            log_message('error', "Exception in generateQRCodesForOrder: " . $e->getMessage());
        }
    }
}
