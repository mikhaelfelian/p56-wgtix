<?php

if (!function_exists('send_wa_message')) {
    /**
     * Send WhatsApp message using Kamupedia API
     * 
     * @param string $phoneNumber Customer phone number
     * @param string $message Message to send
     * @param string $customerName Customer name (optional)
     * @return array Response array with status and message
     */
    function send_wa_message($phoneNumber, $message, $customerName = null, $apiKey = null, $senderNumber = null, $apiUrl = null, $timeout = null)
    {
        // Get API key from parameter or environment variable
        $apiKey = $apiKey ?? getenv('WASENDER_TOKEN');
        
        if (empty($apiKey)) {
            return [
                'success' => false,
                'message' => 'KamupediaWA API key not found. Please provide API key or set WASENDER_TOKEN environment variable.'
            ];
        }
        
        // Use provided parameters or defaults
        $senderNumber = $senderNumber ?? '309448';
        $apiUrl = $apiUrl ?? 'https://wasender.kamupedia.com/apiv2/send-message.php';
        $timeout = $timeout ?? 30;

        // Validate phone number
        if (empty($phoneNumber)) {
            return [
                'success' => false,
                'message' => 'Phone number is required'
            ];
        }

        // Format phone number (remove +, spaces, dashes)
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Add country code if not present (assume Indonesia +62)
        if (!str_starts_with($phoneNumber, '62')) {
            $phoneNumber = '62' . ltrim($phoneNumber, '0');
        }

        // Prepare data for Kamupedia API v2
        $data = [
            'api_key' => $apiKey,
            'sender'  => $senderNumber,
            'number'  => $phoneNumber,
            'message' => $message
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($curlError) {
            log_message('error', 'KamupediaWA cURL error: ' . $curlError);
            return [
                'success' => false,
                'message' => 'Failed to send WhatsApp message: ' . $curlError
            ];
        }

        if ($httpCode !== 200) {
            log_message('error', 'KamupediaWA HTTP error: ' . $httpCode);
            return [
                'success' => false,
                'message' => 'HTTP error: ' . $httpCode
            ];
        }

        // Parse response
        $responseData = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            log_message('error', 'KamupediaWA JSON decode error: ' . json_last_error_msg());
            return [
                'success' => false,
                'message' => 'Invalid response from WhatsApp service'
            ];
        }

        // Check if message was sent successfully
        if (isset($responseData['status']) && $responseData['status'] === 'success') {
            log_message('info', 'WhatsApp message sent successfully to ' . $phoneNumber);
            return [
                'success' => true,
                'message' => 'WhatsApp message sent successfully',
                'data' => $responseData
            ];
        } else {
            $errorMessage = $responseData['message'] ?? 'Unknown error';
            log_message('warning', 'WhatsApp message not sent: ' . $errorMessage);
            return [
                'success' => false,
                'message' => 'WhatsApp message failed: ' . $errorMessage,
                'data' => $responseData
            ];
        }
    }
}

if (!function_exists('send_order_notification')) {
    /**
     * Send order notification via WhatsApp
     * 
     * @param object $order Order object
     * @param string $status Order/payment status
     * @param string $customerPhone Customer phone number
     * @param string $customerName Customer name
     * @param array $orderDetails Order details from tbl_trans_jual_det (optional)
     * @return array Response array
     */
    function send_order_notification($order, $status, $customerPhone, $customerName = null, $orderDetails = null, $apiKey = null, $senderNumber = null, $apiUrl = null, $timeout = null)
    {
        $invoiceNo = $order->invoice_no ?? $order->id ?? 'N/A';
        $statusText = ucfirst($status);
        $totalAmount = isset($order->total_amount) ? number_format($order->total_amount, 0, ',', '.') : 'N/A';
        
        $message = "Halo " . ($customerName ?: 'Pelanggan') . ",\n";
        $message .= "Pesanan Anda dengan Invoice #$invoiceNo telah diupdate.\n";
        $message .= "Status: $statusText\n";
        $message .= "Total: Rp $totalAmount\n\n";
        
        // Add order details if provided
        if ($orderDetails && is_array($orderDetails) && count($orderDetails) > 0) {
            $message .= "Detail Pesanan:\n";
            $first = true;
            foreach ($orderDetails as $detail) {
                $item_dt   = json_decode($detail->item_data, true);
                $itemName  = $item_dt['participant_name'] ?? 'Item';
                $qty       = $detail->qty ?? $detail->quantity ?? 1;
                $price     = isset($detail->harga) ? format_angka($detail->harga) : '0';
                $subtotal  = isset($detail->subtotal) ? format_angka($detail->subtotal) : '0';
                if ($first) {
                    $message .= "• +$itemName x$qty @Rp $price = Rp $subtotal\n";
                    $first = false;
                } else {
                    $message .= "• $itemName x$qty @Rp $price = Rp $subtotal\n";
                }
            }
            $message .= "\n";
        }
        
        $message .= "Terima kasih telah bertransaksi bersama kami.";

        return send_wa_message($customerPhone, $message, $customerName, $apiKey, $senderNumber, $apiUrl, $timeout);
    }
}

if (!function_exists('send_payment_notification')) {
    /**
     * Send payment notification via WhatsApp
     * 
     * @param object $order Order object
     * @param string $paymentStatus Payment status
     * @param string $customerPhone Customer phone number
     * @param string $customerName Customer name
     * @param array $orderDetails Order details from tbl_trans_jual_det (optional)
     * @return array Response array
     */
    function send_payment_notification($order, $paymentStatus, $customerPhone, $customerName = null, $orderDetails = null, $apiKey = null, $senderNumber = null, $apiUrl = null, $timeout = null)
    {
        $invoiceNo = $order->invoice_no ?? $order->id ?? 'N/A';
        $statusText = ucfirst($paymentStatus);
        $totalAmount = isset($order->total_amount) ? number_format($order->total_amount, 0, ',', '.') : 'N/A';
        
        $message = "Halo " . ($customerName ?: 'Pelanggan') . ",\n";
        $message .= "Pembayaran untuk Invoice #$invoiceNo telah diupdate.\n";
        $message .= "Status Pembayaran: $statusText\n";
        $message .= "Total: Rp $totalAmount\n\n";
        
        // Add order details if provided
        if ($orderDetails && is_array($orderDetails) && count($orderDetails) > 0) {
            $message .= "Detail Pesanan:\n";
            $first = true;
            foreach ($orderDetails as $detail) {
                $item_dt   = json_decode($detail->item_data, true);
                $itemName  = $item_dt['participant_name'] ?? 'Item';
                $qty       = $detail->qty ?? $detail->quantity ?? 1;
                $price     = isset($detail->harga) ? format_angka($detail->harga) : '0';
                $subtotal  = isset($detail->subtotal) ? format_angka($detail->subtotal) : '0';
                if ($first) {
                    $message .= "• +$itemName x$qty @Rp $price = Rp $subtotal\n";
                    $first = false;
                } else {
                    $message .= "• $itemName x$qty @Rp $price = Rp $subtotal\n";
                }
            }
            $message .= "\n";
        }
        
        $message .= "Terima kasih telah bertransaksi bersama kami.";

        return send_wa_message($customerPhone, $message, $customerName, $apiKey, $senderNumber, $apiUrl, $timeout);
    }
}

if (!function_exists('send_custom_notification')) {
    /**
     * Send custom notification via WhatsApp
     * 
     * @param string $customerPhone Customer phone number
     * @param string $title Notification title
     * @param string $content Notification content
     * @param string $customerName Customer name
     * @return array Response array
     */
    function send_custom_notification($customerPhone, $title, $content, $customerName = null, $apiKey = null, $senderNumber = null, $apiUrl = null, $timeout = null)
    {
        $message = "Halo " . ($customerName ?: 'Pelanggan') . ",\n\n";
        $message .= "$title\n\n";
        $message .= "$content\n\n";
        $message .= "Terima kasih.";

        return send_wa_message($customerPhone, $message, $customerName, $apiKey, $senderNumber, $apiUrl, $timeout);
    }
}
