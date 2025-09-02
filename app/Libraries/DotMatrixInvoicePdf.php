<?php

namespace App\Libraries;

use TCPDF;
use App\Models\PlatformModel;

class DotMatrixInvoicePdf extends TCPDF
{
    private $order;
    private $orderDetails;
    private $paymentPlatforms;
    private $user;
    private $companyInfo;
    private $subtotal;
    private $totalTax;
    private $grandTotal;

    public function __construct($order, $orderDetails = [], $paymentPlatforms = [], $user = null)
    {
        // Initialize TCPDF with NCR 8.5 x 11" format (216mm x 279mm)
        parent::__construct('P', 'mm', array(216, 279), true, 'UTF-8', false);
        
        $this->order = $order;
        $this->orderDetails = $orderDetails;
        $this->paymentPlatforms = $paymentPlatforms;
        $this->user = $user;
        
        // Company information - adjust according to your needs
        $this->companyInfo = [
            'name' => 'PT WGTIX Event Management',
            'address' => 'Jl. Event Management No. 123',
            'city' => 'Jakarta 12345',
            'phone' => '021-12345678',
            'email' => 'info@wgtix.com'
        ];
        
        $this->setupPdf();
    }
    
    private function setupPdf()
    {
        // Set document information
        $this->SetCreator('WGTIX System');
        $this->SetAuthor('WGTIX Event Management');
        $this->SetTitle('Invoice #' . $this->order->invoice_no);
        $this->SetSubject('Dot Matrix Invoice');
        
        // Set margins for dot matrix style (minimal margins)
        $this->SetMargins(10, 10, 10);
        $this->SetHeaderMargin(0);
        $this->SetFooterMargin(5);
        
        // Disable auto page breaks for precise control
        $this->SetAutoPageBreak(FALSE);
        
        // Set default font - using courier for dot matrix effect
        $this->SetFont('courier', '', 9);
    }
    
    // Disable default header and footer
    public function Header() {}
    public function Footer() {}
    
    public function generateInvoice()
    {
        $this->AddPage();
        
        // Draw the complete invoice
        $this->drawInvoiceHeader();
        $this->drawInvoiceInfo();
        $this->drawItemsTable();
        $this->drawTotals();
        $this->drawPaymentInfo();
        $this->drawFooterSignature();
        
        return $this->Output('S'); // Return as string
    }
    
    private function drawInvoiceHeader()
    {
        $this->SetY(15);
        
        // Left side - Company info
        $this->SetFont('courier', 'B', 12);
        $this->SetXY(10, 15);
        $this->Cell(80, 6, $this->companyInfo['name'], 0, 1, 'L');
        
        $this->SetFont('courier', '', 9);
        $this->SetXY(10, 22);
        $this->Cell(80, 4, $this->companyInfo['address'], 0, 1, 'L');
        $this->SetXY(10, 26);
        $this->Cell(80, 4, $this->companyInfo['city'], 0, 1, 'L');
        
        // Center - INVOICE title
        $this->SetFont('courier', 'B', 16);
        $this->SetXY(85, 20);
        $this->Cell(40, 8, 'INVOICE', 0, 1, 'C');
        
        // Right side - Bill to info
        $this->SetFont('courier', 'B', 10);
        $this->SetXY(130, 15);
        $this->Cell(80, 6, 'Tagihan Kepada', 0, 1, 'L');
        
        $this->SetFont('courier', '', 9);
        if ($this->user) {
            $this->SetXY(130, 22);
            $this->Cell(80, 4, $this->user->first_name . ' ' . $this->user->last_name, 0, 1, 'L');
            $this->SetXY(130, 26);
            $this->Cell(80, 4, $this->user->email, 0, 1, 'L');
            $this->SetXY(130, 30);
            $this->Cell(80, 4, 'Telp: ' . ($this->user->phone ?? '-'), 0, 1, 'L');
        } else {
            $this->SetXY(130, 22);
            $this->Cell(80, 4, 'Guest Customer', 0, 1, 'L');
        }
        
        // Contact info
        $this->SetXY(10, 32);
        $this->Cell(80, 4, 'No. Telepon: ' . $this->companyInfo['phone'], 0, 1, 'L');
        $this->SetXY(10, 36);
        $this->Cell(80, 4, 'Email: ' . $this->companyInfo['email'], 0, 1, 'L');
    }
    
    private function drawInvoiceInfo()
    {
        // Draw separator line
        $this->Line(10, 45, 206, 45);
        
        $this->SetFont('courier', '', 9);
        
        // Invoice details - left side
        $this->SetXY(10, 50);
        $this->Cell(25, 5, 'NO.', 0, 0, 'L');
        $this->Cell(40, 5, 'INV-' . $this->order->invoice_no, 0, 1, 'L');
        
        $this->SetXY(10, 55);
        $this->Cell(25, 5, 'Tanggal', 0, 0, 'L');
        $this->Cell(40, 5, date('d/m/Y', strtotime($this->order->invoice_date)), 0, 1, 'L');
        
        $this->SetXY(10, 60);
        $this->Cell(25, 5, 'Tgl. Jatuh Tempo', 0, 0, 'L');
        $this->Cell(40, 5, date('d/m/Y', strtotime($this->order->invoice_date . ' +7 days')), 0, 1, 'L');
        
        // Draw another separator line
        $this->Line(10, 70, 206, 70);
    }
    
    private function drawItemsTable()
    {
        $this->SetFont('courier', 'B', 9);
        
        // Table header - adjust column widths to fit all columns properly
        $this->SetXY(10, 75);
        $this->Cell(60, 6, 'Produk', 1, 0, 'C');
        $this->Cell(50, 6, 'Deskripsi', 1, 0, 'C');
        $this->Cell(20, 6, 'Kuantitas', 1, 0, 'C');
        $this->Cell(30, 6, 'Harga', 1, 0, 'C');
        $this->Cell(36, 6, 'Jumlah', 1, 1, 'C'); // Make sure this line ends properly
        
        $this->SetFont('courier', '', 8);
        $yPos = 81;
        $grandTotal = 0;
        
        if (!empty($this->orderDetails)) {
            foreach ($this->orderDetails as $detail) {
                $grandTotal += $detail->total_price;
                
                $this->SetXY(10, $yPos);
                
                // Product name - Event title
                $eventName = $this->truncateText($detail->event_title ?: 'Event', 25);
                $this->Cell(60, 6, $eventName, 1, 0, 'L');
                
                // Description - Price description
                $description = $this->truncateText($detail->price_description ?: 'Tiket', 20);
                $this->Cell(50, 6, $description, 1, 0, 'L');
                
                // Quantity - from database
                $this->Cell(20, 6, $detail->quantity ?: 1, 1, 0, 'C');
                
                // Unit price - from database
                $this->Cell(30, 6, number_format($detail->unit_price ?: 0, 0, ',', '.'), 1, 0, 'R');
                
                // Total - from database (make sure this is displayed)
                $this->Cell(36, 6, number_format($detail->total_price ?: 0, 0, ',', '.'), 1, 1, 'R');
                
                $yPos += 6;
            }
        }
        
        // Fill remaining space if needed
        while ($yPos < 150) {
            $this->SetXY(10, $yPos);
            $this->Cell(60, 6, '', 1, 0, 'L');
            $this->Cell(50, 6, '', 1, 0, 'L');
            $this->Cell(20, 6, '', 1, 0, 'C');
            $this->Cell(30, 6, '', 1, 0, 'R');
            $this->Cell(36, 6, '', 1, 1, 'R');
            $yPos += 6;
        }
        
        // Store totals for later use
        $this->subtotal = $grandTotal;
        $this->totalTax = 0; // No tax
        $this->grandTotal = $grandTotal;
    }
    
    private function drawTotals()
    {
        $yPos = 155;
        
        // Payment terms box
        $this->SetXY(10, $yPos);
        $this->Cell(100, 25, '', 1, 0, 'L'); // Empty box for terms
        
        $this->SetFont('courier', 'B', 9);
        $this->SetXY(12, $yPos + 2);
        $this->Cell(96, 5, 'TERBILANG', 0, 1, 'L');
        
        $this->SetFont('courier', '', 8);
        $this->SetXY(12, $yPos + 8);
        $terbilang = $this->numberToWords($this->grandTotal) . ' RUPIAH';
        $this->MultiCell(96, 4, strtoupper($terbilang), 0, 'L');
        
        // Totals on the right - simplified without tax
        $this->SetFont('courier', '', 9);
        
        // Subtotal
        $this->SetXY(140, $yPos + 3);
        $this->Cell(40, 6, 'Subtotal', 0, 0, 'R');
        $this->Cell(30, 6, 'Rp ' . number_format($this->subtotal, 0, ',', '.'), 0, 1, 'R');
        
        // Total (same as subtotal since no tax)
        $this->SetFont('courier', 'B', 10);
        $this->SetXY(140, $yPos + 12);
        $this->Cell(40, 8, 'Total', 0, 0, 'R');
        $this->Cell(30, 8, 'Rp ' . number_format($this->grandTotal, 0, ',', '.'), 0, 1, 'R');
    }
    
    private function drawPaymentInfo()
    {
        $yPos = 190;
        
        // Get available payment platforms
        $platformModel = new PlatformModel();
        $platforms = $platformModel->where('status', 1)->findAll();
        
        // Payment message box
        $this->SetXY(20, $yPos);
        $this->Cell(80, 30, '', 1, 0, 'L'); // Payment instruction box
        
        $this->SetFont('courier', 'B', 9);
        $this->SetXY(25, $yPos + 3);
        $this->Cell(70, 5, 'Pesan', 0, 1, 'L');
        
        $this->SetFont('courier', '', 7);
        $this->SetXY(25, $yPos + 8);
        $this->Cell(70, 3, 'Silahkan transfer ke rekening:', 0, 1, 'L');
        
        // Display available payment methods
        $lineY = $yPos + 12;
        if (!empty($platforms)) {
            foreach ($platforms as $platform) {
                if ($lineY > $yPos + 26) break; // Limit space
                
                $this->SetXY(25, $lineY);
                $paymentInfo = $platform->nomor_rekening . ' ' . $platform->nama;
                if (!empty($platform->nama_rekening)) {
                    $paymentInfo .= ' a/n ' . $platform->nama_rekening;
                }
                $this->Cell(70, 3, $this->truncateText($paymentInfo, 35), 0, 1, 'L');
                $lineY += 3;
            }
        } else {
            // Fallback if no platforms found
            $this->SetXY(25, $lineY);
            $this->Cell(70, 3, 'Hubungi admin untuk info pembayaran', 0, 1, 'L');
        }
        
        // Signature area
        $this->SetXY(130, $yPos);
        $this->Cell(60, 5, 'Dengan Hormat,', 0, 1, 'L');
        
        // Signature space
        $this->SetXY(130, $yPos + 25);
        $this->Cell(60, 5, '( ................... )', 0, 1, 'L');
        $this->SetXY(130, $yPos + 30);
        $this->Cell(60, 5, 'Finance Dept', 0, 1, 'L');
        
        // Payment status
        $this->SetFont('courier', 'B', 12);
        if ($this->order->payment_status === 'paid') {
            $this->SetTextColor(0, 150, 0); // Green
            $this->SetXY(10, $yPos + 40);
            $this->Cell(196, 8, 'STATUS: LUNAS', 1, 1, 'C');
        } else {
            $this->SetTextColor(200, 0, 0); // Red
            $this->SetXY(10, $yPos + 40);
            $this->Cell(196, 8, 'STATUS: BELUM LUNAS', 1, 1, 'C');
        }
        $this->SetTextColor(0, 0, 0); // Reset to black
    }
    
    private function drawFooterSignature()
    {
        // Footer with timestamp
        $this->SetFont('courier', '', 7);
        $this->SetTextColor(100, 100, 100);
        $this->SetXY(10, 270);
        $this->Cell(196, 4, 'Generated by WGTIX System - ' . date('d/m/Y H:i:s'), 0, 1, 'C');
        
        // Payment method info if available
        if ($this->order->payment_method) {
            $this->SetXY(10, 265);
            $this->Cell(196, 4, 'Payment Method: ' . $this->order->payment_method, 0, 1, 'C');
        }
    }
    
    private function truncateText($text, $maxLength)
    {
        if (strlen($text) > $maxLength) {
            return substr($text, 0, $maxLength - 3) . '...';
        }
        return $text;
    }
    
    private function numberToWords($number)
    {
        $ones = array(
            0 => '', 1 => 'satu', 2 => 'dua', 3 => 'tiga', 4 => 'empat', 5 => 'lima',
            6 => 'enam', 7 => 'tujuh', 8 => 'delapan', 9 => 'sembilan', 10 => 'sepuluh',
            11 => 'sebelas', 12 => 'dua belas', 13 => 'tiga belas', 14 => 'empat belas',
            15 => 'lima belas', 16 => 'enam belas', 17 => 'tujuh belas', 18 => 'delapan belas',
            19 => 'sembilan belas'
        );
        
        $tens = array(
            0 => '', 2 => 'dua puluh', 3 => 'tiga puluh', 4 => 'empat puluh',
            5 => 'lima puluh', 6 => 'enam puluh', 7 => 'tujuh puluh',
            8 => 'delapan puluh', 9 => 'sembilan puluh'
        );
        
        if ($number < 20) {
            return $ones[$number];
        } elseif ($number < 100) {
            return $tens[intval($number / 10)] . ' ' . $ones[$number % 10];
        } elseif ($number < 1000) {
            return $ones[intval($number / 100)] . ' ratus ' . $this->numberToWords($number % 100);
        } elseif ($number < 1000000) {
            return $this->numberToWords(intval($number / 1000)) . ' ribu ' . $this->numberToWords($number % 1000);
        } elseif ($number < 1000000000) {
            return $this->numberToWords(intval($number / 1000000)) . ' juta ' . $this->numberToWords($number % 1000000);
        }
        
        return 'angka terlalu besar';
    }
}
