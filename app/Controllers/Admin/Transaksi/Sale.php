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
use CodeIgniter\HTTP\ResponseInterface;
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
    protected $ionAuth;

    public function __construct()
    {
        $this->transJualModel      = new TransJualModel();
        $this->transJualDetModel   = new TransJualDetModel();
        $this->transJualPlatModel  = new TransJualPlatModel();
        $this->pesertaModel        = new PesertaModel();
        $this->ionAuth             = new \IonAuth\Libraries\IonAuth();
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

        $sql = $this->transJualDetModel->where('id_penjualan', $invoiceId)->findAll();

        if ($newPaymentStatus === 'paid') {
            foreach ($sql as $s) {
                $ps = json_decode($s->item_data);

                $data = [
                    'id_penjualan'  => $invoiceId,
                    'id_kategori'   => 0,
                    'id_platform'   => 0,
                    'id_kelompok'   => 0,
                    'id_event'      => $s->event_id,
                    'kode'          => $this->pesertaModel->generateKode($s->event_id),
                    'nama'          => $ps->participant_name,
                ];

                if ($ps->participant_id) {
                    $data['id'] = $ps->participant_id;
                }

                $this->pesertaModel->save($data);
                $lastPesertaId = $this->pesertaModel->insertID() ?? $ps->participant_id;

                $peserta = [
                    "participant_name"     => $ps->participant_name,
                    "participant_id"       => $lastPesertaId,
                    "participant_number"   => $data['kode'],
                    "ticket_info"          => $ps->ticket_info,
                    "event_id"             => $ps->event_id,
                    "price_id"             => $ps->price_id,
                    "quantity"             => $ps->quantity ?? 1,
                    "unit_price"           => $ps->unit_price,
                    "total_price"          => $ps->total_price,
                    "event_title"          => $ps->event_title,
                    "price_description"    => $ps->price_description
                ];

                $this->transJualDetModel->update($s->id, ['item_data' => json_encode($peserta)]);
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
            $pdf->SetFont('helvetica', 'B', 8);
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
        $pdf->Cell(4, 0.5, 'SCAN TO VERIFY', 0, 1, 'C');

        // Display QR code if available, inside the white box at the exact box position (no shift)
        if (!empty($orderDetail->qrcode)) {
            $qrPath = FCPATH . 'file/sale/' . $order->id . '/qrcode/' . $orderDetail->qrcode;
            if (file_exists($qrPath)) {
                // Place QR image exactly inside the white box (no shift)
                // Make the QR code box bigger and keep it proportional (square)
                $biggerQrBoxW = 4.5;
                $biggerQrBoxH = 4.5;
                $biggerQrBoxX = $sideStripX + ($sideStripW - $biggerQrBoxW) / 2 - 1.35;
                $biggerQrBoxY = $ticketY + $headerH + 0.2 - 1.2;

                // Place QR image exactly at the bigger box position (with 0.2cm margin), no rectangle
                $pdf->Image($qrPath, $biggerQrBoxX + 1, $biggerQrBoxY + 0.7, $biggerQrBoxW - 0.4, $biggerQrBoxH - 0.4, 'PNG');
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
        $pdf->Cell($sideStripW - 1.50, 0.4,  ($itemData['participant_number'] != 0 ? $itemData['participant_number'] : ''), 0, 1, 'L');
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
