<?php

/**
 * KamupediaWA Usage Examples
 * 
 * This file shows how to use the KamupediaWA helper, library, and service
 * in your CodeIgniter 4 application.
 */

// ========================================
// 1. USING HELPER FUNCTIONS
// ========================================

// Load the helper (usually done in BaseController or specific controllers)
helper('kamupedia_wa');

// Send a simple message
$result = send_wa_message('081234567890', 'Hello from KamupediaWA Helper!', 'John Doe');

// Send order notification
$order = (object)['invoice_no' => 'INV-001', 'id' => 1];
$result = send_order_notification($order, 'confirmed', '081234567890', 'John Doe');

// Send payment notification
$result = send_payment_notification($order, 'paid', '081234567890', 'John Doe');

// Send custom notification
$result = send_custom_notification('081234567890', 'Special Offer', 'Get 50% off on your next purchase!', 'John Doe');

// ========================================
// 2. USING LIBRARY DIRECTLY
// ========================================

// Load the library
$kamupediaWA = new \App\Libraries\KamupediaWA();

// Send a message
$result = $kamupediaWA->sendMessage('081234567890', 'Hello from KamupediaWA Library!', 'John Doe');

// Send order notification
$result = $kamupediaWA->sendOrderNotification($order, 'shipped', '081234567890', 'John Doe');

// Send payment notification
$result = $kamupediaWA->sendPaymentNotification($order, 'pending', '081234567890', 'John Doe');

// Send custom notification
$result = $kamupediaWA->sendCustomNotification('081234567890', 'Welcome!', 'Thank you for joining us.', 'John Doe');

// Send bulk messages
$recipients = [
    ['phone' => '081234567890', 'name' => 'John Doe', 'message' => 'Hello John!'],
    ['phone' => '081234567891', 'name' => 'Jane Doe', 'message' => 'Hello Jane!'],
];
$result = $kamupediaWA->sendBulkMessages($recipients);

// Test API connection
$result = $kamupediaWA->testConnection();

// ========================================
// 3. USING SERVICE CLASS
// ========================================

// Load the service
$waService = new \App\Services\KamupediaWAService();

// Send order update notification
$result = $waService->notifyOrderUpdate($order, 'confirmed', '081234567890', 'John Doe');

// Send payment update notification
$result = $waService->notifyPaymentUpdate($order, 'paid', '081234567890', 'John Doe');

// Send welcome message
$result = $waService->sendWelcomeMessage('081234567890', 'John Doe');

// Send order confirmation
$result = $waService->sendOrderConfirmation($order, '081234567890', 'John Doe');

// Send shipping notification
$result = $waService->sendShippingNotification($order, 'TRK123456789', '081234567890', 'John Doe');

// Send delivery confirmation
$result = $waService->sendDeliveryConfirmation($order, '081234567890', 'John Doe');

// Send custom notification
$result = $waService->sendCustomNotification('081234567890', 'Special Offer', 'Get 50% off!', 'John Doe');

// Send bulk notifications
$recipients = [
    ['phone' => '081234567890', 'name' => 'John Doe', 'message' => 'Hello John!'],
    ['phone' => '081234567891', 'name' => 'Jane Doe', 'message' => 'Hello Jane!'],
];
$result = $waService->sendBulkNotifications($recipients);

// Test connection
$result = $waService->testConnection();

// ========================================
// 4. IN CONTROLLER USAGE
// ========================================

class ExampleController extends BaseController
{
    public function sendNotification()
    {
        // Using helper
        helper('kamupedia_wa');
        $result = send_wa_message('081234567890', 'Hello from controller!');
        
        // Using library
        $kamupediaWA = new \App\Libraries\KamupediaWA();
        $result = $kamupediaWA->sendMessage('081234567890', 'Hello from library!');
        
        // Using service
        $waService = new \App\Services\KamupediaWAService();
        $result = $waService->sendCustomNotification('081234567890', 'Title', 'Content');
        
        return $this->response->setJSON($result);
    }
}

// ========================================
// 5. CONFIGURATION
// ========================================

// Edit app/Config/KamupediaWA.php to configure:
// - API Key
// - Sender Number
// - API URL
// - Timeout
// - Rate limiting
// - Logging

// ========================================
// 6. ERROR HANDLING
// ========================================

$result = send_wa_message('081234567890', 'Test message');

if ($result['success']) {
    echo "Message sent successfully!";
    // Handle success
} else {
    echo "Failed to send message: " . $result['message'];
    // Handle error
    log_message('error', 'WhatsApp send failed: ' . $result['message']);
}

// ========================================
// 7. BULK MESSAGING WITH ERROR HANDLING
// ========================================

$recipients = [
    ['phone' => '081234567890', 'name' => 'John', 'message' => 'Hello John!'],
    ['phone' => '081234567891', 'name' => 'Jane', 'message' => 'Hello Jane!'],
];

$waService = new \App\Services\KamupediaWAService();
$result = $waService->sendBulkNotifications($recipients);

if ($result['success']) {
    echo "All messages sent successfully!";
} else {
    echo "Some messages failed. Check results for details.";
    foreach ($result['results'] as $item) {
        if (!$item['result']['success']) {
            echo "Failed to send to {$item['phone']}: {$item['result']['message']}\n";
        }
    }
}
