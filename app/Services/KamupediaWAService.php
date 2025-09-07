<?php

namespace App\Services;

use App\Libraries\KamupediaWA;

/**
 * Kamupedia WhatsApp Service
 * 
 * A service class that provides easy access to KamupediaWA functionality
 * 
 * @author Your Name
 * @version 1.0.0
 */
class KamupediaWAService
{
    protected $kamupediaWA;

    public function __construct()
    {
        $this->kamupediaWA = new KamupediaWA();
    }

    /**
     * Send order status update notification
     * 
     * @param object $order Order object
     * @param string $status New order status
     * @param string $customerPhone Customer phone number
     * @param string $customerName Customer name
     * @param array $orderDetails Order details from tbl_trans_jual_det (optional)
     * @return array Response array
     */
    public function notifyOrderUpdate($order, $status, $customerPhone, $customerName = null, $orderDetails = null)
    {
        return $this->kamupediaWA->sendOrderNotification($order, $status, $customerPhone, $customerName, $orderDetails);
    }

    /**
     * Send payment status update notification
     * 
     * @param object $order Order object
     * @param string $paymentStatus New payment status
     * @param string $customerPhone Customer phone number
     * @param string $customerName Customer name
     * @param array $orderDetails Order details from tbl_trans_jual_det (optional)
     * @return array Response array
     */
    public function notifyPaymentUpdate($order, $paymentStatus, $customerPhone, $customerName = null, $orderDetails = null)
    {
        return $this->kamupediaWA->sendPaymentNotification($order, $paymentStatus, $customerPhone, $customerName, $orderDetails);
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
        return $this->kamupediaWA->sendCustomNotification($customerPhone, $title, $content, $customerName);
    }

    /**
     * Send bulk notifications
     * 
     * @param array $recipients Array of recipients
     * @return array Response array
     */
    public function sendBulkNotifications($recipients)
    {
        return $this->kamupediaWA->sendBulkMessages($recipients);
    }

    /**
     * Test API connection
     * 
     * @return array Test result
     */
    public function testConnection()
    {
        return $this->kamupediaWA->testConnection();
    }

    /**
     * Send welcome message to new customer
     * 
     * @param string $customerPhone Customer phone number
     * @param string $customerName Customer name
     * @return array Response array
     */
    public function sendWelcomeMessage($customerPhone, $customerName = null)
    {
        $title = "Selamat Datang!";
        $content = "Terima kasih telah bergabung dengan kami. Kami siap melayani kebutuhan Anda dengan sepenuh hati.";
        
        return $this->sendCustomNotification($customerPhone, $title, $content, $customerName);
    }

    /**
     * Send order confirmation
     * 
     * @param object $order Order object
     * @param string $customerPhone Customer phone number
     * @param string $customerName Customer name
     * @return array Response array
     */
    public function sendOrderConfirmation($order, $customerPhone, $customerName = null)
    {
        $invoiceNo = $order->invoice_no ?? $order->id ?? 'N/A';
        $totalAmount = number_format($order->total_amount ?? 0, 0, ',', '.');
        
        $title = "Konfirmasi Pesanan";
        $content = "Pesanan Anda dengan Invoice #$invoiceNo telah dikonfirmasi.\nTotal: Rp $totalAmount\n\nPesanan akan segera diproses.";
        
        return $this->sendCustomNotification($customerPhone, $title, $content, $customerName);
    }

    /**
     * Send shipping notification
     * 
     * @param object $order Order object
     * @param string $trackingNumber Tracking number
     * @param string $customerPhone Customer phone number
     * @param string $customerName Customer name
     * @return array Response array
     */
    public function sendShippingNotification($order, $trackingNumber, $customerPhone, $customerName = null)
    {
        $invoiceNo = $order->invoice_no ?? $order->id ?? 'N/A';
        
        $title = "Pesanan Dikirim";
        $content = "Pesanan Anda dengan Invoice #$invoiceNo telah dikirim.\nNomor Resi: $trackingNumber\n\nSilakan cek status pengiriman secara berkala.";
        
        return $this->sendCustomNotification($customerPhone, $title, $content, $customerName);
    }

    /**
     * Send delivery confirmation
     * 
     * @param object $order Order object
     * @param string $customerPhone Customer phone number
     * @param string $customerName Customer name
     * @return array Response array
     */
    public function sendDeliveryConfirmation($order, $customerPhone, $customerName = null)
    {
        $invoiceNo = $order->invoice_no ?? $order->id ?? 'N/A';
        
        $title = "Pesanan Telah Diterima";
        $content = "Pesanan Anda dengan Invoice #$invoiceNo telah diterima.\n\nTerima kasih telah berbelanja dengan kami. Kami tunggu pesanan berikutnya!";
        
        return $this->sendCustomNotification($customerPhone, $title, $content, $customerName);
    }
}
