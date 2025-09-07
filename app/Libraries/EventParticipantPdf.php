<?php

namespace App\Libraries;

use TCPDF;

class EventParticipantPdf extends TCPDF
{
    private $event;
    private $participants;
    private $kategori;
    private $pengaturan;

    public function __construct($event, $participants = [], $kategori = null, $pengaturan = [])
    {
        // Initialize TCPDF with A4 format
        parent::__construct('P', 'mm', 'A4', true, 'UTF-8', false);
        
        $this->event = $event;
        $this->participants = $participants;
        $this->kategori = $kategori;
        $this->pengaturan = $pengaturan;
        
        $this->setupPdf();
    }
    
    private function setupPdf()
    {
        // Set document information
        $this->SetCreator('WGTIX System');
        $this->SetAuthor('WGTIX Event Management');
        $this->SetTitle('Daftar Peserta - ' . $this->event->event);
        $this->SetSubject('Event Participants List');
        
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
    
    public function generateParticipantList()
    {
        $this->AddPage();
        
        // Draw the complete participant list
        $this->drawHeader();
        $this->drawEventInfo();
        $this->drawParticipantTable();
        $this->drawFooter();
        
        return $this->Output('S'); // Return as string
    }
    
    private function drawHeader()
    {
        // Company logo/name area
        $this->SetFont('helvetica', 'B', 20);
        $this->SetTextColor(76, 175, 80); // Green color
        $this->SetXY(15, 15);
        $this->Cell(100, 10, $this->pengaturan->judul ?? 'WGTIX', 0, 1, 'L');
        
        // Title
        $this->SetFont('helvetica', 'B', 16);
        $this->SetTextColor(0, 0, 0);
        $this->SetXY(15, 30);
        $this->Cell(100, 8, 'DAFTAR PESERTA EVENT', 0, 1, 'L');
        
        // Event name
        $this->SetFont('helvetica', 'B', 14);
        $this->SetXY(15, 40);
        $this->Cell(100, 6, $this->event->event, 0, 1, 'L');
        
        // Date and time
        $this->SetFont('helvetica', '', 10);
        $this->SetXY(15, 48);
        $this->Cell(100, 5, 'Tanggal: ' . date('d F Y', strtotime($this->event->tgl_masuk)) . ' - ' . date('d F Y', strtotime($this->event->tgl_keluar)), 0, 1, 'L');
        
        $this->SetXY(15, 54);
        $this->Cell(100, 5, 'Waktu: ' . $this->event->wkt_masuk . ' - ' . $this->event->wkt_keluar, 0, 1, 'L');
        
        // Location
        if ($this->event->lokasi) {
            $this->SetXY(15, 60);
            $this->Cell(100, 5, 'Lokasi: ' . $this->event->lokasi, 0, 1, 'L');
        }
        
        // Line separator
        $this->Line(15, 70, 195, 70);
    }
    
    private function drawEventInfo()
    {
        $y = 75;
        
        // Event details
        $this->SetFont('helvetica', '', 9);
        
        // Left column
        $this->SetXY(15, $y);
        $this->Cell(30, 5, 'Kode Event:', 0, 0, 'L');
        $this->Cell(40, 5, $this->event->kode ?: '-', 0, 1, 'L');
        
        $this->SetXY(15, $y + 6);
        $this->Cell(30, 5, 'Kategori:', 0, 0, 'L');
        $this->Cell(40, 5, $this->kategori ? $this->kategori->kategori : 'Tidak ada kategori', 0, 1, 'L');
        
        // Right column
        $this->SetXY(120, $y);
        $this->Cell(30, 5, 'Total Peserta:', 0, 0, 'L');
        $this->Cell(40, 5, count($this->participants) . ' orang', 0, 1, 'L');
        
        $this->SetXY(120, $y + 6);
        $this->Cell(30, 5, 'Kapasitas:', 0, 0, 'L');
        $this->Cell(40, 5, $this->event->jml > 0 ? $this->event->jml . ' peserta' : 'Tidak terbatas', 0, 1, 'L');
        
        // Line separator
        $this->Line(15, $y + 15, 195, $y + 15);
    }
    
    private function drawParticipantTable()
    {
        $startY = 95;
        
        // Table header
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(240, 240, 240);
        
        // Header row
        $this->SetXY(15, $startY);
        $this->Cell(15, 8, 'No', 1, 0, 'C', true);
        $this->Cell(60, 8, 'Nama Peserta', 1, 0, 'C', true);
        $this->Cell(50, 8, 'Email', 1, 0, 'C', true);
        $this->Cell(30, 8, 'Telepon', 1, 0, 'C', true);
        $this->Cell(30, 8, 'Tanda Tangan', 1, 1, 'C', true);
        
        // Table content
        $this->SetFont('helvetica', '', 8);
        $this->SetFillColor(255, 255, 255);
        
        $yPos = $startY + 8;
        $no = 1;
        
        foreach ($this->participants as $participant) {
            // Check if we need a new page
            if ($yPos > 250) {
                $this->AddPage();
                $yPos = 20;
                
                // Redraw header on new page
                $this->SetFont('helvetica', 'B', 9);
                $this->SetFillColor(240, 240, 240);
                $this->SetXY(15, $yPos);
                $this->Cell(15, 8, 'No', 1, 0, 'C', true);
                $this->Cell(60, 8, 'Nama Peserta', 1, 0, 'C', true);
                $this->Cell(50, 8, 'Email', 1, 0, 'C', true);
                $this->Cell(30, 8, 'Telepon', 1, 0, 'C', true);
                $this->Cell(30, 8, 'Tanda Tangan', 1, 1, 'C', true);
                
                $this->SetFont('helvetica', '', 8);
                $this->SetFillColor(255, 255, 255);
                $yPos += 8;
            }
            
            // Participant row
            $this->SetXY(15, $yPos);
            $this->Cell(15, 8, $no++, 1, 0, 'C');
            $this->Cell(60, 8, $this->truncateText($participant->nama, 25), 1, 0, 'L');
            $this->Cell(50, 8, $this->truncateText($participant->email, 20), 1, 0, 'L');
            $this->Cell(30, 8, $participant->no_hp ?: '-', 1, 0, 'C');
            $this->Cell(30, 8, '', 1, 1, 'C'); // Empty signature column
            
            $yPos += 8;
        }
        
        // Add empty rows for additional signatures if needed
        $remainingRows = 20 - (count($this->participants) % 20);
        if ($remainingRows < 20) {
            for ($i = 0; $i < $remainingRows; $i++) {
                if ($yPos > 250) {
                    $this->AddPage();
                    $yPos = 20;
                }
                
                $this->SetXY(15, $yPos);
                $this->Cell(15, 8, '', 1, 0, 'C');
                $this->Cell(60, 8, '', 1, 0, 'L');
                $this->Cell(50, 8, '', 1, 0, 'L');
                $this->Cell(30, 8, '', 1, 0, 'C');
                $this->Cell(30, 8, '', 1, 1, 'C');
                
                $yPos += 8;
            }
        }
    }
    
    private function drawFooter()
    {
        $footerY = 270;
        
        // Footer note
        $this->SetFont('helvetica', '', 8);
        $this->SetTextColor(100, 100, 100);
        $this->SetXY(15, $footerY);
        $this->Cell(80, 4, 'Dokumen ini sah dan diproses oleh komputer', 0, 1, 'L');
        
        // Timestamp
        $this->SetXY(120, $footerY);
        $this->Cell(75, 4, 'Dicetak pada: ' . date('d F Y H:i') . ' WIB', 0, 1, 'R');
        
        // Reset color
        $this->SetTextColor(0, 0, 0);
    }
    
    private function truncateText($text, $maxLength)
    {
        if (strlen($text) > $maxLength) {
            return substr($text, 0, $maxLength - 3) . '...';
        }
        return $text;
    }
}
