<?php

if (!function_exists('generateQRCode')) {
    /**
     * Generate QR Code for participant
     * 
     * @param string $data Data to encode in QR code
     * @param int $size QR code size (default: 200)
     * @return string Base64 encoded QR code image
     */
    function generateQRCode($data, $size = 200)
    {
        // Simple QR code generation using Google Charts API
        $url = 'https://chart.googleapis.com/chart?chs=' . $size . 'x' . $size . '&cht=qr&chl=' . urlencode($data);
        
        // Get the image content
        $imageContent = file_get_contents($url);
        
        if ($imageContent === false) {
            return false;
        }
        
        // Convert to base64
        return base64_encode($imageContent);
    }
}

if (!function_exists('generateParticipantQRCode')) {
    /**
     * Generate QR Code for participant with event and participant info
     * 
     * @param int $participantId Participant ID
     * @param int $eventId Event ID
     * @param string $participantName Participant name
     * @return string Base64 encoded QR code image
     */
    function generateParticipantQRCode($participantId, $eventId, $participantName)
    {
        // Create unique QR data
        $qrData = json_encode([
            'participant_id' => $participantId,
            'event_id' => $eventId,
            'name' => $participantName,
            'timestamp' => time()
        ]);
        
        return generateQRCode($qrData);
    }
}

if (!function_exists('generateTicketQRCode')) {
    /**
     * Generate QR Code for ticket
     * 
     * @param string $ticketCode Ticket code
     * @param int $size QR code size (default: 150)
     * @return string Base64 encoded QR code image
     */
    function generateTicketQRCode($ticketCode, $size = 150)
    {
        return generateQRCode($ticketCode, $size);
    }
}
