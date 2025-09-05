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
    private $shippingFee;
    private $serviceFee;

    public function __construct($order, $orderDetails = [], $paymentPlatforms = [], $user = null, $pengaturan = [])
    {
        // Initialize TCPDF with A4 format
        parent::__construct('P', 'mm', 'A4', true, 'UTF-8', false);
        
        $this->order            = $order;
        $this->orderDetails     = $orderDetails;
        $this->paymentPlatforms = $paymentPlatforms;
        $this->user             = $user;
        $this->pengaturan       = $pengaturan;
        
        // Company information
        $this->companyInfo = [
            'name'     => $this->pengaturan->judul,
            'tagline'  => 'Event Management System',
            'address'  => $this->pengaturan->alamat,
            'city'     => $this->pengaturan->kota,
            'phone'    => '',
            'email'    => getenv('app.email'),
            'website'  => getenv('app.domain'),
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
        
        // Set margins
        $this->SetMargins(15, 15, 15);
        $this->SetHeaderMargin(0);
        $this->SetFooterMargin(10);
        
        // Disable auto page breaks for precise control
        $this->SetAutoPageBreak(FALSE);
        
        // Set default font
        $this->SetFont('helvetica', '', 10);
    }
    
    // Disable default header and footer
    public function Header() {}
    public function Footer() {}
    
    public function generateInvoice()
    {
        $this->AddPage();
        
        // Draw the complete invoice
        $this->drawHeader();
        $this->drawInvoiceInfo();
        $this->drawBillingInfo();
        $this->drawItemsTable();
        $this->drawTotals();
        $this->drawPaymentMethod();
        $this->drawFooter();
        
        return $this->Output('S'); // Return as string
    }
    
    private function drawHeader()
    {
        // Company logo/name area (left side)
        $this->SetFont('helvetica', 'B', 24);
        $this->SetTextColor(76, 175, 80); // Green color like Tokopedia
        $this->SetXY(15, 15);
        $this->Cell(80, 12, $this->companyInfo['name'], 0, 1, 'L');
        
        // Invoice title and number (right side)
        $this->SetFont('helvetica', 'B', 20);
        $this->SetTextColor(0, 0, 0);
        $this->SetXY(120, 15);
        $this->Cell(75, 8, 'INVOICE', 0, 1, 'R');
        
        // Invoice number
        $this->SetFont('helvetica', '', 14);
        $this->SetTextColor(100, 100, 100);
        $this->SetXY(120, 25);
        $this->Cell(75, 6, $this->order->invoice_no, 0, 1, 'R');
        
        // Reset text color
        $this->SetTextColor(0, 0, 0);
    }
    
    private function drawInvoiceInfo()
    {
        $y = 45;
        
        // Left side - Bill from (Company info)
        $this->SetFont('helvetica', 'B', 11);
        $this->SetXY(15, $y);
        $this->Cell(80, 6, 'DITERBITKAN ATAS NAMA', 0, 1, 'L');
        
        $this->SetFont('helvetica', '', 10);
        $this->SetXY(15, $y + 8);
        $this->Cell(80, 5, 'Penjual: ' . $this->companyInfo['name'], 0, 1, 'L');
        
        // Right side - For (Customer info)
        $this->SetFont('helvetica', 'B', 11);
        $this->SetXY(120, $y);
        $this->Cell(75, 6, 'UNTUK', 0, 1, 'L');
        
        $this->SetFont('helvetica', '', 10);
        if ($this->user) {
            $this->SetXY(120, $y + 8);
            $this->Cell(75, 5, 'Pembeli: ' . $this->user->first_name . ' ' . $this->user->last_name, 0, 1, 'L');
            
            $this->SetXY(120, $y + 14);
            $this->Cell(75, 5, 'Tanggal Pembelian: ' . date('d F Y', strtotime($this->order->invoice_date)), 0, 1, 'L');
        } else {
            $this->SetXY(120, $y + 8);
            $this->Cell(75, 5, 'Pembeli: Guest Customer', 0, 1, 'L');
        }
        
        // Customer address (if available)
        if ($this->user && isset($this->user->address)) {
            $this->SetXY(120, $y + 20);
            $this->Cell(75, 5, 'Alamat Pengiriman: ' . $this->user->address, 0, 1, 'L');
        } else {
            // Default address like in the image
            $this->SetXY(120, $y + 20);
            $this->MultiCell(75, 4, 'Alamat Pengiriman: ' . $this->user->first_name . ' ' . $this->user->last_name . ' (+62)857412220427) Mangunharjo Dalam 1, Blok D11- Perumahan Mutiara Pancanaran Tembalang, Kota Semarang, 50272 Jawa Tengah', 0, 'L');
        }
    }
    
    private function drawBillingInfo()
    {
        // This method can be expanded if needed for additional billing info
    }
    
    private function drawItemsTable()
    {
        $startY = 110;

        // Table header background
        $this->SetFillColor(245, 245, 245);
        $this->SetFont('helvetica', 'B', 9);

        // Draw table header (border bottom only for header row)
        $this->SetXY(15, $startY);
        $this->Cell(110, 7, 'INFO PRODUK', 'TLR', 0, 'L', true);
        $this->Cell(15, 7, 'JUMLAH', 'TR', 0, 'C', true);
        $this->Cell(30, 7, 'HARGA SATUAN', 'TR', 0, 'C', true);
        $this->Cell(30, 7, 'TOTAL HARGA', 'TR', 1, 'C', true);

        // Draw header bottom border
        $this->SetDrawColor(0,0,0);
        $this->Line(15, $startY+7, 185, $startY+7);

        $this->SetFont('helvetica', '', 10);
        $this->SetFillColor(255, 255, 255);

        $yPos = $startY + 7;
        $grandTotal = 0;

        if (!empty($this->orderDetails)) {
            foreach ($this->orderDetails as $detail) {
                $grandTotal += $detail->total_price;

                // Product info cell (bold, green, multiline)
                $this->SetXY(15, $yPos);
                $this->SetFont('helvetica', 'B', 9);
                $this->SetTextColor(0, 153, 51);
                $eventTitle = $detail->event_title ?: 'Event Ticket';
                $cellHeight = 6;
                $this->MultiCell(110, $cellHeight, $eventTitle, 'L', 'L', false, 0);

                // Quantity
                $this->SetFont('helvetica', '', 10);
                $this->SetTextColor(0, 0, 0);
                $this->SetXY(125, $yPos);
                $this->Cell(15, $cellHeight, $detail->quantity ?: 1, 0, 0, 'C');

                // Unit price
                $this->SetXY(140, $yPos);
                $this->Cell(30, $cellHeight, 'Rp' . format_angka($detail->unit_price ?: 0, 0), 0, 0, 'R');

                // Total price
                $this->SetXY(170, $yPos);
                $this->Cell(30, $cellHeight, 'Rp' . format_angka($detail->total_price ?: 0, 0), 0, 1, 'R');

                // Second line: description/weight (smaller, gray)
                $yPos += $cellHeight;
                $this->SetXY(15, $yPos);
                $this->SetFont('helvetica', '', 8);
                $this->SetTextColor(100, 100, 100);

                // If there is a weight, show it, else show description
                $weight = isset($detail->weight) && $detail->weight ? 'Berat: ' . $detail->weight . ' kg' : '';
                $desc = $weight ?: ($detail->price_description ?: '');
                if ($desc) {
                    $this->MultiCell(110, 5, $desc, 'L', 'L', false, 0);
                } else {
                    $this->MultiCell(110, 5, '', 'L', 'L', false, 0);
                }
                // Empty cells for the rest of the row
                $this->SetXY(125, $yPos);
                $this->Cell(15, 5, '', 0, 0, 'C');
                $this->SetXY(140, $yPos);
                $this->Cell(30, 5, '', 0, 0, 'R');
                $this->SetXY(170, $yPos);
                $this->Cell(30, 5, '', 0, 1, 'R');

                // Draw bottom border for the row
                $this->SetDrawColor(0,0,0);
                $this->Line(15, $yPos+5, 185, $yPos+5);

                $this->SetTextColor(0, 0, 0);
                $yPos += 5;
            }
        }

        // Store totals for later use
        $this->subtotal = $grandTotal;
        $this->shippingFee = 17000; // Example shipping fee
        $this->serviceFee = 1000;   // Example service fee
        $this->totalTax = 0; // No tax
        $this->grandTotal = $grandTotal + $this->shippingFee + $this->serviceFee;
    }
    
    private function drawTotals()
    {
        $startY = 180;
        
        // Subtotal
        $this->SetFont('helvetica', '', 10);
        $this->SetXY(120, $startY);
        $this->Cell(40, 6, 'SUBTOTAL HARGA BARANG', 0, 0, 'L');
        $this->Cell(35, 6, 'Rp' . number_format($this->subtotal, 0, ',', '.'), 0, 1, 'R');
        
        // Shipping fee
        $this->SetXY(120, $startY + 8);
        $this->Cell(40, 6, 'Total Ongkos Kirim', 0, 0, 'L');
        $this->Cell(35, 6, 'Rp' . number_format($this->shippingFee, 0, ',', '.'), 0, 1, 'R');
        
        // Service fee
        $this->SetXY(120, $startY + 16);
        $this->Cell(40, 6, 'Biaya Jasa Aplikasi', 0, 0, 'L');
        $this->Cell(35, 6, 'Rp' . number_format($this->serviceFee, 0, ',', '.'), 0, 1, 'R');
        
        // Line separator
        $this->Line(120, $startY + 24, 195, $startY + 24);
        
        // Total
        $this->SetFont('helvetica', 'B', 11);
        $this->SetXY(120, $startY + 28);
        $this->Cell(40, 6, 'TOTAL BELANJA', 0, 0, 'L');
        $this->Cell(35, 6, 'Rp' . number_format($this->grandTotal, 0, ',', '.'), 0, 1, 'R');
        
        // Service fee note
        $this->SetFont('helvetica', '', 9);
        $this->SetXY(120, $startY + 36);
        $this->Cell(75, 6, 'Biaya Layanan', 0, 0, 'L');
        $this->Cell(35, 6, 'Rp' . number_format($this->serviceFee, 0, ',', '.'), 0, 1, 'R');
        
        // Line separator
        $this->Line(120, $startY + 44, 195, $startY + 44);
        
        // Final total
        $this->SetFont('helvetica', 'B', 12);
        $this->SetXY(120, $startY + 48);
        $this->Cell(40, 8, 'TOTAL TAGIHAN', 0, 0, 'L');
        $this->Cell(35, 8, 'Rp' . number_format($this->grandTotal, 0, ',', '.'), 0, 1, 'R');
    }
    
    private function drawPaymentMethod()
    {
        $startY = 245;
        
        $this->SetFont('helvetica', '', 10);
        $this->SetXY(15, $startY);
        $this->Cell(50, 6, 'Metode Pembayaran:', 0, 1, 'L');
        
        // Get payment method from platforms or order
        $paymentMethod = 'Transfer Bank';
        if (!empty($this->paymentPlatforms)) {
            $paymentMethod = $this->paymentPlatforms[0]->platform ?? 'Transfer Bank';
        }
        
        $this->SetFont('helvetica', 'B', 10);
        $this->SetXY(15, $startY + 6);
        $this->Cell(50, 6, $paymentMethod, 0, 1, 'L');
    }
    
    private function drawFooter()
    {
        $footerY = 270;
        
        // Footer note
        $this->SetFont('helvetica', '', 9);
        $this->SetTextColor(100, 100, 100);
        $this->SetXY(15, $footerY);
        $this->Cell(80, 4, 'Invoice ini sah dan diproses oleh komputer', 0, 1, 'L');
        
        $this->SetXY(15, $footerY + 4);
        $this->Cell(80, 4, 'Silakan hubungi ' . $this->companyInfo['name'] . ' Care apabila kamu membutuhkan bantuan.', 0, 1, 'L');
        
        // Timestamp
        $this->SetXY(120, $footerY + 4);
        $this->Cell(75, 4, 'Terakhir diupdate: ' . date('d F Y H:i') . ' WIB', 0, 1, 'R');
        
        // Reset color
        $this->SetTextColor(0, 0, 0);
    }
}