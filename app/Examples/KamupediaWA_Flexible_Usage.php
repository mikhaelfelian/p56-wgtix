<?php

/**
 * KamupediaWA Flexible Usage Examples
 * 
 * This file shows how to use the KamupediaWA library with flexible parameters
 * and environment variable configuration.
 */

// ========================================
// 1. ENVIRONMENT VARIABLE SETUP
// ========================================

// Add to your .env file:
// KAMUPEDIA_API_KEY=your_actual_api_key_here

// ========================================
// 2. USING LIBRARY WITH DEFAULT SETTINGS
// ========================================

// Uses environment variable for API key, config for other settings
$kamupediaWA = new \App\Libraries\KamupediaWA();
$result = $kamupediaWA->sendMessage('081234567890', 'Hello with default settings!');

// ========================================
// 3. USING LIBRARY WITH CUSTOM SETTINGS
// ========================================

// Override sender number, API URL, and timeout
$kamupediaWA = new \App\Libraries\KamupediaWA(
    '123456',  // Custom sender number
    'https://custom-api-url.com/send',  // Custom API URL
    60  // Custom timeout (60 seconds)
);
$result = $kamupediaWA->sendMessage('081234567890', 'Hello with custom settings!');

// ========================================
// 4. USING HELPER WITH DEFAULT SETTINGS
// ========================================

// Load helper
helper('kamupedia_wa');

// Uses environment variable for API key, config for other settings
$result = send_wa_message('081234567890', 'Hello from helper!');

// ========================================
// 5. USING SERVICE WITH FLEXIBLE PARAMETERS
// ========================================

// Service can be initialized with custom library settings
$kamupediaWA = new \App\Libraries\KamupediaWA('123456', null, 45);
$waService = new \App\Services\KamupediaWAService();
$waService->kamupediaWA = $kamupediaWA; // Inject custom library

$order = (object)['invoice_no' => 'INV-001', 'id' => 1];
$result = $waService->notifyOrderUpdate($order, 'confirmed', '081234567890', 'John Doe');

// ========================================
// 6. CONFIGURATION PRIORITY
// ========================================

// Priority order (highest to lowest):
// 1. Constructor parameters (when creating library instance)
// 2. Config file values (app/Config/KamupediaWA.php)
// 3. Default values (hardcoded in library)

// Example with mixed configuration:
$kamupediaWA = new \App\Libraries\KamupediaWA(
    '999999',  // This will override config sender number
    null,      // This will use config API URL
    120       // This will override config timeout
);

// ========================================
// 7. ENVIRONMENT VARIABLE EXAMPLES
// ========================================

// .env file examples:
/*
# Required - API Key from environment
KAMUPEDIA_API_KEY=6e4ae60c28d825e9f2750bcddb5abd98238e8db3

# Optional - Override config values
KAMUPEDIA_SENDER_NUMBER=123456
KAMUPEDIA_API_URL=https://custom-api.com/send
KAMUPEDIA_TIMEOUT=60
*/

// Using environment variables in config:
// app/Config/KamupediaWA.php
/*
public $apiKey = null; // Will be set from env in constructor
public $senderNumber = env('KAMUPEDIA_SENDER_NUMBER', '309448');
public $apiUrl = env('KAMUPEDIA_API_URL', 'https://wasender.kamupedia.com/apiv2/send-message.php');
public $timeout = env('KAMUPEDIA_TIMEOUT', 30);
*/

// ========================================
// 8. ERROR HANDLING
// ========================================

try {
    $kamupediaWA = new \App\Libraries\KamupediaWA();
    $result = $kamupediaWA->sendMessage('081234567890', 'Test message');
    
    if ($result['success']) {
        echo "Message sent successfully!";
    } else {
        echo "Failed to send message: " . $result['message'];
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
    // Handle missing API key or other configuration errors
}

// ========================================
// 9. BULK MESSAGING WITH CUSTOM SETTINGS
// ========================================

$kamupediaWA = new \App\Libraries\KamupediaWA('123456', null, 90);
$recipients = [
    ['phone' => '081234567890', 'name' => 'John', 'message' => 'Hello John!'],
    ['phone' => '081234567891', 'name' => 'Jane', 'message' => 'Hello Jane!'],
];
$result = $kamupediaWA->sendBulkMessages($recipients);

// ========================================
// 10. TESTING CONNECTION
// ========================================

$kamupediaWA = new \App\Libraries\KamupediaWA();
$testResult = $kamupediaWA->testConnection();

if ($testResult['success']) {
    echo "API connection successful!";
} else {
    echo "API connection failed: " . $testResult['message'];
}
