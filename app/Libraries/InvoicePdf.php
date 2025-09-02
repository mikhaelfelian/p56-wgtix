<?php

namespace App\Libraries;

use TCPDF;

class InvoicePdf extends TCPDF
{
    private $order;
    private $orderDetails;
    private $paymentPlatforms;
    private $user;
    private $companyInfo;

    public function __construct($order, $orderDetails = [], $paymentPlatforms = [], $user = null)
    {
        // Initialize TCPDF with Folio format (8.5 x 11 inches)
        parent::__construct('P', 'mm', array(215.9, 279.4), true, 'UTF-8', false);
        
        $this->order = $order;
        $this->orderDetails = $orderDetails;
        $this->paymentPlatforms = $paymentPlatforms;
        $this->user = $user;
        
        // Company information - you can modify this or get from database
        $this->companyInfo = [
            'name' => 'WGTIX Event Management',
            'address' => 'Jl. Example Street No. 123',
            'city' => 'Jakarta, Indonesia 12345',
            'phone' => '+62 21 1234 5678',
            'email' => 'info@wgtix.com',
            'website' => 'www.wgtix.com'
        ];
        
        $this->setupPdf();
    }
    
    private function setupPdf()
    {
        // Set document information
        $this->SetCreator('WGTIX System');
        $this->SetAuthor('WGTIX Event Management');
        $this->SetTitle('Invoice #' . $this->order->invoice_no);
        $this->SetSubject('Invoice');
        
        // Set margins (F4/Folio format)
        $this->SetMargins(20, 20, 20);
        $this->SetHeaderMargin(10);
        $this->SetFooterMargin(15);
        
        // Set auto page breaks
        $this->SetAutoPageBreak(TRUE, 25);
        
        // Set font
        $this->SetFont('helvetica', '', 10);
    }
    
    public function Header()
    {
        // Company logo placeholder - you can add actual logo here
        $this->SetFont('helvetica', 'B', 20);
        $this->Cell(0, 15, $this->companyInfo['name'], 0, 1, 'L');
        
        $this->SetFont('helvetica', '', 9);
        $this->Cell(0, 5, $this->companyInfo['address'], 0, 1, 'L');
        $this->Cell(0, 5, $this->companyInfo['city'], 0, 1, 'L');
        $this->Cell(0, 5, 'Phone: ' . $this->companyInfo['phone'] . ' | Email: ' . $this->companyInfo['email'], 0, 1, 'L');
        
        // Line separator
        $this->Ln(5);
        $this->Line(20, $this->GetY(), 195.9, $this->GetY());
        $this->Ln(10);
    }
    
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        
        // Line separator
        $this->Line(20, $this->GetY(), 195.9, $this->GetY());
        $this->Ln(2);
        
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 5, 'Thank you for your business!', 0, 1, 'C');
        $this->Cell(0, 5, 'This is a computer generated invoice - ' . date('d/m/Y H:i:s'), 0, 0, 'C');
    }
    
    public function generateInvoice()
    {
        $this->AddPage();
        
        // Invoice header
        $this->drawInvoiceHeader();
        
        // Customer information
        $this->drawCustomerInfo();
        
        // Order items table
        $this->drawOrderItems();
        
        // Payment information
        $this->drawPaymentInfo();
        
        // Payment status
        $this->drawPaymentStatus();
        
        return $this->Output('S'); // Return as string
    }
    
    private function drawInvoiceHeader()
    {
        // Invoice title and number
        $this->SetFont('helvetica', 'B', 16);
        $this->Cell(0, 10, 'INVOICE', 0, 1, 'C');
        
        $this->Ln(5);
        
        // Invoice details in two columns
        $this->SetFont('helvetica', '', 10);
        
        // Left column
        $this->Cell(90, 6, 'Invoice No: ' . $this->order->invoice_no, 0, 0, 'L');
        // Right column
        $this->Cell(0, 6, 'Invoice Date: ' . date('d/m/Y H:i', strtotime($this->order->invoice_date)), 0, 1, 'R');
        
        $this->Cell(90, 6, 'Payment Method: ' . ($this->order->payment_method ?: 'Not specified'), 0, 0, 'L');
        $this->Cell(0, 6, 'Due Date: ' . date('d/m/Y', strtotime($this->order->invoice_date . ' +7 days')), 0, 1, 'R');
        
        $this->Ln(10);
    }
    
    private function drawCustomerInfo()
    {
        $this->SetFont('helvetica', 'B', 11);
        $this->Cell(0, 8, 'BILL TO:', 0, 1, 'L');
        
        $this->SetFont('helvetica', '', 10);
        
        if ($this->user) {
            $this->Cell(0, 6, $this->user->first_name . ' ' . $this->user->last_name, 0, 1, 'L');
            $this->Cell(0, 6, 'Username: ' . $this->user->username, 0, 1, 'L');
            $this->Cell(0, 6, 'Email: ' . $this->user->email, 0, 1, 'L');
            $this->Cell(0, 6, 'User ID: ' . $this->order->user_id, 0, 1, 'L');
        } else {
            $this->Cell(0, 6, 'Guest Customer', 0, 1, 'L');
            $this->Cell(0, 6, 'User ID: ' . ($this->order->user_id ?: 'N/A'), 0, 1, 'L');
        }
        
        $this->Ln(10);
    }
    
    private function drawOrderItems()
    {
        $this->SetFont('helvetica', 'B', 10);
        $this->Cell(0, 8, 'ORDER DETAILS:', 0, 1, 'L');
        
        // Table header
        $this->SetFillColor(240, 240, 240);
        $this->SetFont('helvetica', 'B', 9);
        
        $this->Cell(60, 8, 'Event', 1, 0, 'C', true);
        $this->Cell(50, 8, 'Price Description', 1, 0, 'C', true);
        $this->Cell(20, 8, 'Qty', 1, 0, 'C', true);
        $this->Cell(30, 8, 'Unit Price', 1, 0, 'C', true);
        $this->Cell(30, 8, 'Total Price', 1, 1, 'C', true);
        
        // Table content
        $this->SetFont('helvetica', '', 8);
        $grandTotal = 0;
        
        if (!empty($this->orderDetails)) {
            foreach ($this->orderDetails as $detail) {
                $grandTotal += $detail->total_price;
                
                // Event name (with word wrap)
                $eventName = $detail->event_title ?: 'N/A';
                $priceDesc = $detail->price_description ?: 'N/A';
                
                $this->Cell(60, 8, $this->truncateText($eventName, 30), 1, 0, 'L');
                $this->Cell(50, 8, $this->truncateText($priceDesc, 25), 1, 0, 'L');
                $this->Cell(20, 8, $detail->quantity ?: 1, 1, 0, 'C');
                $this->Cell(30, 8, 'Rp ' . number_format($detail->unit_price ?: 0, 0, ',', '.'), 1, 0, 'R');
                $this->Cell(30, 8, 'Rp ' . number_format($detail->total_price ?: 0, 0, ',', '.'), 1, 1, 'R');
            }
        } else {
            $this->Cell(190, 8, 'No order items found', 1, 1, 'C');
        }
        
        // Total row
        $this->SetFont('helvetica', 'B', 10);
        $this->SetFillColor(230, 230, 230);
        $this->Cell(160, 10, 'GRAND TOTAL:', 1, 0, 'R', true);
        $this->Cell(30, 10, 'Rp ' . number_format($grandTotal, 0, ',', '.'), 1, 1, 'R', true);
        
        $this->Ln(10);
    }
    
    private function drawPaymentInfo()
    {
        if (!empty($this->paymentPlatforms)) {
            $this->SetFont('helvetica', 'B', 10);
            $this->Cell(0, 8, 'PAYMENT DETAILS:', 0, 1, 'L');
            
            // Payment table header
            $this->SetFillColor(240, 240, 240);
            $this->SetFont('helvetica', 'B', 9);
            
            $this->Cell(30, 8, 'Date', 1, 0, 'C', true);
            $this->Cell(40, 8, 'Platform', 1, 0, 'C', true);
            $this->Cell(40, 8, 'Transaction ID', 1, 0, 'C', true);
            $this->Cell(30, 8, 'Amount', 1, 0, 'C', true);
            $this->Cell(50, 8, 'Notes', 1, 1, 'C', true);
            
            // Payment content
            $this->SetFont('helvetica', '', 8);
            $totalPayments = 0;
            
            foreach ($this->paymentPlatforms as $payment) {
                $totalPayments += $payment->nominal;
                
                $this->Cell(30, 8, date('d/m/Y', strtotime($payment->created_at)), 1, 0, 'C');
                $this->Cell(40, 8, $this->truncateText($payment->platform, 20), 1, 0, 'L');
                $this->Cell(40, 8, $this->truncateText($payment->no_nota ?: 'N/A', 20), 1, 0, 'L');
                $this->Cell(30, 8, 'Rp ' . number_format($payment->nominal, 0, ',', '.'), 1, 0, 'R');
                $this->Cell(50, 8, $this->truncateText($payment->keterangan ?: '-', 25), 1, 1, 'L');
            }
            
            // Total payments row
            $this->SetFont('helvetica', 'B', 9);
            $this->SetFillColor(230, 230, 230);
            $this->Cell(140, 8, 'TOTAL PAYMENTS:', 1, 0, 'R', true);
            $this->Cell(50, 8, 'Rp ' . number_format($totalPayments, 0, ',', '.'), 1, 1, 'R', true);
            
            $this->Ln(10);
        }
    }
    
    private function drawPaymentStatus()
    {
        $this->SetFont('helvetica', 'B', 14);
        
        // Payment status with colored background
        if ($this->order->payment_status === 'paid') {
            $this->SetFillColor(76, 175, 80); // Green
            $this->SetTextColor(255, 255, 255); // White text
            $statusText = 'PAID';
        } else {
            $this->SetFillColor(244, 67, 54); // Red
            $this->SetTextColor(255, 255, 255); // White text
            $statusText = 'NOT PAID';
        }
        
        // Center the status
        $this->Cell(0, 15, 'PAYMENT STATUS: ' . $statusText, 1, 1, 'C', true);
        
        // Reset colors
        $this->SetTextColor(0, 0, 0);
        $this->SetFillColor(255, 255, 255);
        
        $this->Ln(10);
        
        // Additional notes if any
        if (!empty($this->order->notes)) {
            $this->SetFont('helvetica', 'B', 10);
            $this->Cell(0, 8, 'NOTES:', 0, 1, 'L');
            $this->SetFont('helvetica', '', 9);
            $this->MultiCell(0, 6, $this->order->notes, 1, 'L');
        }
    }
    
    private function truncateText($text, $maxLength)
    {
        if (strlen($text) > $maxLength) {
            return substr($text, 0, $maxLength - 3) . '...';
        }
        return $text;
    }
}
