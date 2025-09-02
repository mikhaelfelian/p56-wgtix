<?php

namespace App\Libraries;

use TCPDF;

class TicketPdf extends TCPDF
{
    private $orderDetail;
    private $order;
    private $user;
    private $event;
    private $companyInfo;

    public function __construct($orderDetail, $order, $user = null, $event = null)
    {
        // Initialize TCPDF with custom ticket size (A4 landscape divided into 2 tickets)
        // Ticket size: 210mm x 148.5mm (A4 landscape / 2)
        parent::__construct('L', 'mm', array(210, 148.5), true, 'UTF-8', false);
        
        $this->orderDetail = $orderDetail;
        $this->order = $order;
        $this->user = $user;
        $this->event = $event;
        
        // Company information
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
        $this->SetTitle('Event Ticket - ' . ($this->event->title ?? 'Event'));
        $this->SetSubject('Event Ticket');
        
        // Set margins for ticket
        $this->SetMargins(10, 10, 10);
        $this->SetHeaderMargin(0);
        $this->SetFooterMargin(0);
        
        // Disable auto page breaks for custom ticket layout
        $this->SetAutoPageBreak(FALSE);
        
        // Set font
        $this->SetFont('helvetica', '', 10);
    }
    
    // Override header and footer to disable them for tickets
    public function Header() {}
    public function Footer() {}
    
    public function generateTicket()
    {
        $this->AddPage();
        
        // Main ticket design
        $this->drawTicketBackground();
        $this->drawTicketHeader();
        $this->drawEventInfo();
        $this->drawTicketDetails();
        $this->drawQRCode();
        $this->drawTicketFooter();
        
        return $this->Output('S'); // Return as string
    }
    
    private function drawTicketBackground()
    {
        // Ticket border with rounded corners effect
        $this->SetFillColor(255, 255, 255); // White background
        $this->SetDrawColor(0, 123, 255); // Blue border
        $this->SetLineWidth(1);
        $this->Rect(5, 5, 200, 138.5, 'DF');
        
        // Header background gradient effect (simulated with rectangles)
        $this->SetFillColor(0, 123, 255); // Blue header
        $this->Rect(5, 5, 200, 25, 'F');
        
        // Decorative side strip
        $this->SetFillColor(240, 240, 240); // Light gray
        $this->Rect(155, 30, 50, 113.5, 'F');
        
        // Decorative dots pattern on side strip
        $this->SetFillColor(220, 220, 220);
        for ($i = 35; $i < 140; $i += 8) {
            $this->Circle(180, $i, 1, 0, 360, 'F');
        }
    }
    
    private function drawTicketHeader()
    {
        // Company logo area (placeholder)
        $this->SetTextColor(255, 255, 255); // White text
        $this->SetFont('helvetica', 'B', 16);
        $this->SetXY(10, 8);
        $this->Cell(100, 8, $this->companyInfo['name'], 0, 1, 'L');
        
        $this->SetFont('helvetica', '', 8);
        $this->SetXY(10, 16);
        $this->Cell(100, 6, 'EVENT TICKET', 0, 1, 'L');
        
        // Ticket number
        $this->SetFont('helvetica', 'B', 10);
        $this->SetXY(120, 8);
        $this->Cell(80, 8, 'TICKET #' . str_pad($this->orderDetail->id, 6, '0', STR_PAD_LEFT), 0, 1, 'R');
        
        $this->SetFont('helvetica', '', 8);
        $this->SetXY(120, 16);
        $this->Cell(80, 6, 'Invoice: ' . $this->order->invoice_no, 0, 1, 'R');
    }
    
    private function drawEventInfo()
    {
        // Reset text color for main content
        $this->SetTextColor(0, 0, 0); // Black text
        
        // Event title
        $this->SetFont('helvetica', 'B', 18);
        $this->SetXY(10, 35);
        $eventTitle = $this->orderDetail->event_title ?: 'Event Title';
        $this->Cell(140, 12, $this->truncateText($eventTitle, 40), 0, 1, 'L');
        
        // Event details
        $this->SetFont('helvetica', '', 11);
        $this->SetXY(10, 50);
        
        // Parse item data for participant info
        $itemData = json_decode($this->orderDetail->item_data, true) ?: [];
        
        // Date and time (placeholder - you can get from event data)
        $this->Cell(140, 6, 'Date: ' . date('d F Y', strtotime($this->order->invoice_date)), 0, 1, 'L');
        $this->SetXY(10, 58);
        $this->Cell(140, 6, 'Time: 09:00 - 17:00 WIB', 0, 1, 'L');
        
        // Location (placeholder)
        $this->SetXY(10, 66);
        $this->Cell(140, 6, 'Location: Event Venue', 0, 1, 'L');
        
        // Price category
        $this->SetXY(10, 74);
        $this->Cell(140, 6, 'Category: ' . ($this->orderDetail->price_description ?: 'General'), 0, 1, 'L');
        
        // Participant info if available
        if (isset($itemData['participant_name'])) {
            $this->SetFont('helvetica', 'B', 12);
            $this->SetXY(10, 85);
            $this->Cell(140, 6, 'Participant:', 0, 1, 'L');
            $this->SetFont('helvetica', '', 11);
            $this->SetXY(10, 93);
            $this->Cell(140, 6, $itemData['participant_name'], 0, 1, 'L');
            
            if (isset($itemData['participant_number'])) {
                $this->SetXY(10, 101);
                $this->Cell(140, 6, 'Participant #: ' . $itemData['participant_number'], 0, 1, 'L');
            }
        }
    }
    
    private function drawTicketDetails()
    {
        // Customer info
        if ($this->user) {
            $this->SetFont('helvetica', 'B', 10);
            $this->SetXY(10, 115);
            $this->Cell(140, 6, 'Ticket Holder:', 0, 1, 'L');
            $this->SetFont('helvetica', '', 9);
            $this->SetXY(10, 123);
            $this->Cell(140, 5, $this->user->first_name . ' ' . $this->user->last_name, 0, 1, 'L');
            $this->SetXY(10, 130);
            $this->Cell(140, 5, $this->user->email, 0, 1, 'L');
        }
        
        // Price info
        $this->SetFont('helvetica', 'B', 12);
        $this->SetXY(10, 138);
        $this->Cell(140, 5, 'Price: Rp ' . number_format($this->orderDetail->total_price, 0, ',', '.'), 0, 1, 'L');
    }
    
    private function drawQRCode()
    {
        // QR Code area
        $this->SetFont('helvetica', 'B', 10);
        $this->SetXY(160, 35);
        $this->Cell(40, 6, 'SCAN TO VERIFY', 0, 1, 'C');
        
        // Check if QR code exists
        if (!empty($this->orderDetail->qrcode)) {
            $qrPath = FCPATH . 'file/sale/' . $this->order->id . '/qrcode/' . $this->orderDetail->qrcode;
            
            if (file_exists($qrPath)) {
                // Display QR code image
                $this->Image($qrPath, 165, 45, 30, 30, 'PNG');
            } else {
                // QR code not found, show placeholder
                $this->drawQRPlaceholder();
            }
        } else {
            // No QR code generated yet
            $this->drawQRPlaceholder();
        }
        
        // Ticket ID below QR code
        $this->SetFont('helvetica', '', 8);
        $this->SetXY(160, 80);
        $this->Cell(40, 4, 'ID: ' . $this->orderDetail->id, 0, 1, 'C');
    }
    
    private function drawQRPlaceholder()
    {
        // Draw QR code placeholder
        $this->SetFillColor(240, 240, 240);
        $this->Rect(165, 45, 30, 30, 'F');
        
        $this->SetFont('helvetica', '', 8);
        $this->SetXY(165, 58);
        $this->Cell(30, 4, 'QR CODE', 0, 1, 'C');
        $this->SetXY(165, 63);
        $this->Cell(30, 4, 'PENDING', 0, 1, 'C');
    }
    
    private function drawTicketFooter()
    {
        // Terms and conditions
        $this->SetFont('helvetica', '', 7);
        $this->SetTextColor(100, 100, 100);
        $this->SetXY(160, 90);
        $this->MultiCell(40, 3, "Terms:\n• Non-transferable\n• Valid for one entry\n• No refund\n• Present with ID", 0, 'L');
        
        // Footer info
        $this->SetXY(160, 115);
        $this->Cell(40, 3, $this->companyInfo['website'], 0, 1, 'C');
        $this->SetXY(160, 120);
        $this->Cell(40, 3, $this->companyInfo['phone'], 0, 1, 'C');
        
        // Payment status indicator
        $this->SetFont('helvetica', 'B', 8);
        if ($this->order->payment_status === 'paid') {
            $this->SetTextColor(0, 150, 0); // Green
            $this->SetXY(160, 130);
            $this->Cell(40, 4, 'PAID', 0, 1, 'C');
        } else {
            $this->SetTextColor(200, 0, 0); // Red
            $this->SetXY(160, 130);
            $this->Cell(40, 4, 'UNPAID', 0, 1, 'C');
        }
        
        // Security watermark
        $this->SetTextColor(230, 230, 230);
        $this->SetFont('helvetica', '', 20);
        $this->SetXY(20, 80);
        $this->Cell(100, 20, 'WGTIX', 0, 1, 'C');
    }
    
    private function truncateText($text, $maxLength)
    {
        if (strlen($text) > $maxLength) {
            return substr($text, 0, $maxLength - 3) . '...';
        }
        return $text;
    }
}
