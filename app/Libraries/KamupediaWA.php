<?php

namespace App\Libraries;

use CodeIgniter\Config\BaseService;

/**
 * Kamupedia WhatsApp Library
 * 
 * A library for sending WhatsApp messages using Kamupedia API
 * 
 * @author Your Name
 * @version 1.0.0
 */
class KamupediaWA
{
    protected $config;
    protected $apiKey;
    protected $senderNumber;
    protected $apiUrl;
    protected $timeout;

    public function __construct($apiKey = null, $senderNumber = null, $apiUrl = null, $timeout = null)
    {
        // Get API key from parameter or environment variable, or throw if not set
        $this->apiKey       = $apiKey      ?? getenv('WASENDER_TOKEN');
        $this->senderNumber = $senderNumber ?? getenv('WASENDER_ID');
        $this->apiUrl       = $apiUrl      ?? 'https://wasender.kamupedia.com/apiv2/send-message.php';
        $this->timeout      = $timeout     ?? 30;
        
        if (empty($this->apiKey)) {
            throw new \Exception('KamupediaWA API key not found. Please provide API key or set WASENDER_TOKEN environment variable.');
        }
    }

    /**
     * Send WhatsApp message
     * 
     * @param string $phoneNumber Customer phone number
     * @param string $message Message to send
     * @param string $customerName Customer name (optional)
     * @return array Response array with status and message
     */
    public function sendMessage($phoneNumber, $message, $customerName = null)
    {
        // Validate phone number
        if (empty($phoneNumber)) {
            return [
                'success' => false,
                'message' => 'Phone number is required'
            ];
        }

        // Format phone number
        $phoneNumber = $this->formatPhoneNumber($phoneNumber);

        // Prepare data for Kamupedia API v2
        $data = [
            'api_key' => $this->apiKey,
            'sender'  => $this->senderNumber,
            'number'  => $phoneNumber,
            'message' => $message
        ];

        $response = $this->makeRequest($data);

        if (!$response['success']) {
            return $response;
        }

        // Parse response
        $responseData = json_decode($response['data'], true);
        
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

    /**
     * Send order notification
     * 
     * @param object $order Order object
     * @param string $status Order/payment status
     * @param string $customerPhone Customer phone number
     * @param string $customerName Customer name
     * @param array $orderDetails Order details from tbl_trans_jual_det (optional)
     * @return array Response array
     */
    public function sendOrderNotification($order, $status, $customerPhone, $customerName = null, $orderDetails = null)
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
            $message .= "Tiket *".$orderDetails[0]->event_title."*\n";
            $message .= "Detail Pesanan:\n";
            $first = true;
            foreach ($orderDetails as $detail) {
                $item_dt   = json_decode($detail->item_data, true);
                $itemName  = $item_dt['participant_name'] ?? 'Item';
                $qty       = $detail->qty ?? $detail->quantity ?? 1;
                $price     = isset($detail->unit_price) ? format_angka($detail->unit_price) : '0';
                $subtotal  = isset($detail->total_price) ? format_angka($detail->total_price) : '0';
                if ($first) {
                    $message .= "+ $itemName x$qty @Rp $price = Rp $subtotal\n";
                    $first = false;
                } else {
                    $message .= "+ $itemName x$qty @Rp $price = Rp $subtotal\n";
                }
            }
            $message .= "\n";
        }
        
        $message .= "Terima kasih telah bertransaksi bersama kami.";

        return $this->sendMessage($customerPhone, $message, $customerName);
    }

    /**
     * Send payment notification
     * 
     * @param object $order Order object
     * @param string $paymentStatus Payment status
     * @param string $customerPhone Customer phone number
     * @param string $customerName Customer name
     * @param array $orderDetails Order details from tbl_trans_jual_det (optional)
     * @return array Response array
     */
    public function sendPaymentNotification($order, $paymentStatus, $customerPhone, $customerName = null, $orderDetails = null)
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

        return $this->sendMessage($customerPhone, $message, $customerName);
    }

    /**
     * Send custom notification
     * 
     * @param string $customerPhone Customer phone number
     * @param string $title Notification title
     * @param string $content Notification content
     * @param string $customerName Customer name
     * @return array Response array
     */
    public function sendCustomNotification($customerPhone, $title, $content, $customerName = null)
    {
        $message = "Halo " . ($customerName ?: 'Pelanggan') . ",\n\n";
        $message .= "$title\n\n";
        $message .= "$content\n\n";
        $message .= "Terima kasih.";

        return $this->sendMessage($customerPhone, $message, $customerName);
    }

    /**
     * Send bulk messages
     * 
     * @param array $recipients Array of recipients with phone, name, and message
     * @return array Response array with results
     */
    public function sendBulkMessages($recipients)
    {
        $results = [];
        $successCount = 0;
        $failCount = 0;

        foreach ($recipients as $recipient) {
            $phone = $recipient['phone'] ?? '';
            $message = $recipient['message'] ?? '';
            $name = $recipient['name'] ?? null;

            $result = $this->sendMessage($phone, $message, $name);
            $results[] = [
                'phone' => $phone,
                'name' => $name,
                'result' => $result
            ];

            if ($result['success']) {
                $successCount++;
            } else {
                $failCount++;
            }

            // Add delay between messages to avoid rate limiting
            sleep(1);
        }

        return [
            'success' => $failCount === 0,
            'message' => "Bulk send completed. Success: $successCount, Failed: $failCount",
            'results' => $results,
            'summary' => [
                'total' => count($recipients),
                'success' => $successCount,
                'failed' => $failCount
            ]
        ];
    }

    /**
     * Format phone number
     * 
     * @param string $phoneNumber Raw phone number
     * @return string Formatted phone number
     */
    protected function formatPhoneNumber($phoneNumber)
    {
        // Remove all non-numeric characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Add country code if not present (assume Indonesia +62)
        if (!str_starts_with($phoneNumber, '62')) {
            $phoneNumber = '62' . ltrim($phoneNumber, '0');
        }

        return $phoneNumber;
    }

    /**
     * Make HTTP request to Kamupedia API
     * 
     * @param array $data Request data
     * @return array Response array
     */
    protected function makeRequest($data)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => $this->timeout,
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

        return [
            'success' => true,
            'data' => $response
        ];
    }

    /**
     * Test API connection
     * 
     * @return array Test result
     */
    public function testConnection()
    {
        try {
            $testData = [
                'api_key' => $this->apiKey,
                'sender'  => $this->senderNumber,
                'number'  => '6281234567890', // Test number
                'message' => 'Test message from KamupediaWA Library'
            ];

            $response = $this->makeRequest($testData);
            
            if ($response['success']) {
                $responseData = json_decode($response['data'], true);
                return [
                    'success' => true,
                    'message' => 'API connection successful',
                    'data' => $responseData
                ];
            } else {
                return $response;
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'API connection failed: ' . $e->getMessage()
            ];
        }
    }
}
