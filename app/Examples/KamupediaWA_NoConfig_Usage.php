<?php

/**
 * KamupediaWA Usage Examples - NO CONFIG REQUIRED
 * 
 * This file shows how to use the KamupediaWA library without any config files.
 * Everything is passed as parameters or uses environment variables.
 */

// ========================================
// 1. ENVIRONMENT VARIABLE SETUP
// ========================================

// Add to your .env file:
// WASENDER_TOKEN=your_actual_api_key_here

// ========================================
// 2. USING LIBRARY WITH ENVIRONMENT VARIABLE
// ========================================

// Uses environment variable for API key, defaults for other settings
$kamupediaWA = new \App\Libraries\KamupediaWA();
$result = $kamupediaWA->sendMessage('081234567890', 'Hello with ENV API key!');

// ========================================
// 3. USING LIBRARY WITH ALL PARAMETERS
// ========================================

// Provide all parameters explicitly
$kamupediaWA = new \App\Libraries\KamupediaWA(
    'your_api_key_here',  // API key
    '123456',             // Sender number
    'https://custom-api.com/send',  // API URL
    60                    // Timeout
);
$result = $kamupediaWA->sendMessage('081234567890', 'Hello with all parameters!');

// ========================================
// 4. USING LIBRARY WITH MIXED PARAMETERS
// ========================================

// API key from ENV, custom sender number, default URL and timeout
$kamupediaWA = new \App\Libraries\KamupediaWA(
    null,      // Use ENV for API key
    '999999',  // Custom sender number
    null,      // Use default URL
    null       // Use default timeout
);
$result = $kamupediaWA->sendMessage('081234567890', 'Hello with mixed parameters!');

// ========================================
// 5. USING HELPER WITH ENVIRONMENT VARIABLE
// ========================================

// Load helper
helper('kamupedia_wa');

// Uses environment variable for API key, defaults for other settings
$result = send_wa_message('081234567890', 'Hello from helper!');

// ========================================
// 6. USING HELPER WITH ALL PARAMETERS
// ========================================

// Provide all parameters explicitly
$result = send_wa_message(
    '081234567890',                    // Phone number
    'Hello from helper with params!',  // Message
    'John Doe',                        // Customer name
    'your_api_key_here',               // API key
    '123456',                          // Sender number
    'https://custom-api.com/send',     // API URL
    60                                 // Timeout
);

// ========================================
// 7. USING HELPER WITH MIXED PARAMETERS
// ========================================

// API key from ENV, custom sender number, default URL and timeout
$result = send_wa_message(
    '081234567890',                    // Phone number
    'Hello with mixed helper params!', // Message
    'John Doe',                        // Customer name
    null,                              // Use ENV for API key
    '999999',                          // Custom sender number
    null,                              // Use default URL
    null                               // Use default timeout
);

// ========================================
// 8. ORDER NOTIFICATIONS
// ========================================

$order = (object)['invoice_no' => 'INV-001', 'id' => 1, 'total_amount' => 150000];
$orderDetails = [
    (object)['item_data' => '{"participant_name":"John Doe"}', 'qty' => 1, 'harga' => 150000, 'subtotal' => 150000]
];

// With ENV API key
$result = send_order_notification($order, 'confirmed', '081234567890', 'John Doe', $orderDetails);

// With custom parameters
$result = send_order_notification(
    $order, 
    'confirmed', 
    '081234567890', 
    'John Doe', 
    $orderDetails,
    'your_api_key_here',  // API key
    '123456',             // Sender number
    'https://custom-api.com/send',  // API URL
    60                    // Timeout
);

// ========================================
// 9. PAYMENT NOTIFICATIONS
// ========================================

// With ENV API key
$result = send_payment_notification($order, 'paid', '081234567890', 'John Doe', $orderDetails);

// With custom parameters
$result = send_payment_notification(
    $order, 
    'paid', 
    '081234567890', 
    'John Doe', 
    $orderDetails,
    'your_api_key_here',  // API key
    '123456',             // Sender number
    'https://custom-api.com/send',  // API URL
    60                    // Timeout
);

// ========================================
// 10. CUSTOM NOTIFICATIONS
// ========================================

// With ENV API key
$result = send_custom_notification('081234567890', 'Welcome!', 'Thank you for joining us.', 'John Doe');

// With custom parameters
$result = send_custom_notification(
    '081234567890',                    // Phone number
    'Welcome!',                        // Title
    'Thank you for joining us.',       // Content
    'John Doe',                        // Customer name
    'your_api_key_here',               // API key
    '123456',                          // Sender number
    'https://custom-api.com/send',     // API URL
    60                                 // Timeout
);

// ========================================
// 11. ERROR HANDLING
// ========================================

try {
    // This will use ENV variable
    $kamupediaWA = new \App\Libraries\KamupediaWA();
    $result = $kamupediaWA->sendMessage('081234567890', 'Test message');
    
    if ($result['success']) {
        echo "Message sent successfully!";
    } else {
        echo "Failed to send message: " . $result['message'];
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
    // Handle missing API key or other errors
}

// ========================================
// 12. BULK MESSAGING
// ========================================

$kamupediaWA = new \App\Libraries\KamupediaWA(
    'your_api_key_here',  // API key
    '123456',             // Sender number
    null,                 // Use default URL
    90                    // Custom timeout
);

$recipients = [
    ['phone' => '081234567890', 'name' => 'John', 'message' => 'Hello John!'],
    ['phone' => '081234567891', 'name' => 'Jane', 'message' => 'Hello Jane!'],
];
$result = $kamupediaWA->sendBulkMessages($recipients);

// ========================================
// 13. TESTING CONNECTION
// ========================================

$kamupediaWA = new \App\Libraries\KamupediaWA();
$testResult = $kamupediaWA->testConnection();

if ($testResult['success']) {
    echo "API connection successful!";
} else {
    echo "API connection failed: " . $testResult['message'];
}

// ========================================
// 14. DEFAULT VALUES
// ========================================

// Default values used when not specified:
// - API Key: getenv('WASENDER_TOKEN')
// - Sender Number: '309448'
// - API URL: 'https://wasender.kamupedia.com/apiv2/send-message.php'
// - Timeout: 30 seconds

// ========================================
// 15. PARAMETER PRIORITY
// ========================================

// Priority order (highest to lowest):
// 1. Constructor/function parameters
// 2. Environment variable (WASENDER_TOKEN)
// 3. Default values (hardcoded)

// Example:
$kamupediaWA = new \App\Libraries\KamupediaWA(
    'explicit_api_key',   // This will be used (highest priority)
    '999999',            // This will be used
    null,                // Will use default URL
    null                 // Will use default timeout
);
