<?php

/**
 * Absen Controller
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Admin controller for managing attendance using QR code scanning
 */

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PesertaModel;
use App\Models\EventsModel;
use App\Models\TransJualDetModel;

class Absen extends BaseController
{
    protected $pesertaModel;
    protected $eventsModel;
    protected $transJualDetModel;

    public function __construct()
    {
        parent::__construct();
        $this->pesertaModel         = new PesertaModel();
        $this->eventsModel          = new EventsModel();
        $this->transJualDetModel    = new TransJualDetModel();
    }

    /**
     * Display attendance page for specific event
     */
    public function index($eventId = null)
    {
        $event = $this->eventsModel->find($eventId);

        if (!$event) {
            return redirect()->to('/admin/events')
                ->with('error', 'Event tidak ditemukan');
        }

        // Get attendance statistics
        $totalParticipants = $this->pesertaModel->where('id_event', $eventId)
                                              ->where('status !=', -1)
                                              ->countAllResults();

        $attendedParticipants = $this->pesertaModel->where('id_event', $eventId)
                                                 ->where('status !=', -1)
                                                 ->where('status_hadir', '1')
                                                 ->countAllResults();

        $data = [
            'title' => 'Absensi Event - ' . $event->event,
            'event' => $event,
            'total_participants' => $totalParticipants,
            'attended_participants' => $attendedParticipants,
            'attendance_rate' => $totalParticipants > 0 ? round(($attendedParticipants / $totalParticipants) * 100, 1) : 0
        ];

        return $this->view($this->theme->getThemePath() . '/admin/absen/index', $data);
    }

    /**
     * Process QR code scan and update attendance
     */
    public function scan()
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid request method',
                    'error_code' => 'INVALID_METHOD'
                ]);
            }

            $qrCode = $this->request->getPost('qr_code');
            $eventId = $this->request->getPost('event_id');

            // Validate input
            if (!$qrCode || !$eventId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'QR Code dan Event ID diperlukan',
                    'error_code' => 'MISSING_PARAMETERS'
                ]);
            }

            // Validate event exists
            $event = $this->eventsModel->find($eventId);
            if (!$event) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Event tidak ditemukan',
                    'error_code' => 'EVENT_NOT_FOUND'
                ]);
            }

            // Find participant by QR code and event
            // QR content format: {detail_id}|{invoice_id}|{timestamp}
            $participant = null;
            
            // First, try to find by exact QR code match (in case it's stored as base64)
            $participant = $this->pesertaModel->where('qr_code', $qrCode)
                                            ->where('id_event', $eventId)
                                            ->where('status !=', -1)
                                            ->first();
            
            // If not found, try to find by order detail ID from QR content
            if (!$participant && strpos($qrCode, '|') !== false) {
                $qrParts = explode('|', $qrCode);
                if (count($qrParts) >= 2 && is_numeric($qrParts[0])) {
                    $detailId = (int)$qrParts[0];
                    $invoiceId = (int)$qrParts[1];
                    
                    // Find the order detail
                    $orderDetail = $this->transJualDetModel->find($detailId);
                    
                    if ($orderDetail && $orderDetail->id_penjualan == $invoiceId) {
                        $sql_det = $this->transJualDetModel->where('id', $detailId)->first();
                        $ps = json_decode($sql_det->item_data);
                        $participant = $this->pesertaModel->where('id', $ps->participant_id)
                                                        ->where('id_event', $eventId)
                                                        ->where('status !=', -1)
                                                        ->first();
                    }
                }
            }

            if (!$participant) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Peserta tidak ditemukan atau QR Code tidak valid untuk event ini',
                    'error_code' => 'PARTICIPANT_NOT_FOUND'
                ]);
            }

            // Check if already attended
            if ($participant->status_hadir == '1') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Peserta sudah melakukan absensi sebelumnya',
                    'error_code' => 'ALREADY_ATTENDED',
                    'participant' => [
                        'nama' => $participant->nama,
                        'email' => $participant->email,
                        'status_hadir' => $participant->status_hadir
                    ]
                ]);
            }

            // Update attendance status
            $updateData = [
                'status_hadir' => '1',
                'updated_at'   => date('Y-m-d H:i:s'),
                'qr_code'      => date('Y-m-d H:i:s')
            ];

            if ($this->pesertaModel->update($participant->id, $updateData)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Absensi berhasil dicatat',
                    'participant' => [
                        'nama' => $participant->nama,
                        'email' => $participant->email,
                        'status_hadir' => '1'
                    ]
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal mencatat absensi. Silakan coba lagi.',
                    'error_code' => 'UPDATE_FAILED'
                ]);
            }

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses QR Code',
                'error_code' => 'SYSTEM_ERROR'
            ]);
        }
    }

    /**
     * Get attendance statistics
     */
    public function getStats($eventId = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $totalParticipants = $this->pesertaModel->where('id_event', $eventId)
                                              ->where('status !=', -1)
                                              ->countAllResults();

        $attendedParticipants = $this->pesertaModel->where('id_event', $eventId)
                                                 ->where('status !=', -1)
                                                 ->where('status_hadir', '1')
                                                 ->countAllResults();

        $attendanceRate = $totalParticipants > 0 ? round(($attendedParticipants / $totalParticipants) * 100, 1) : 0;

        return $this->response->setJSON([
            'success' => true,
            'total_participants' => $totalParticipants,
            'attended_participants' => $attendedParticipants,
            'attendance_rate' => $attendanceRate
        ]);
    }

    /**
     * Reset attendance for event
     */
    public function reset($eventId = null)
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid request method',
                    'error_code' => 'INVALID_METHOD'
                ]);
            }

            $event = $this->eventsModel->find($eventId);
            if (!$event) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Event tidak ditemukan',
                    'error_code' => 'EVENT_NOT_FOUND'
                ]);
            }

            // Reset all attendance for this event
            $result = $this->pesertaModel->where('id_event', $eventId)
                                      ->set(['status_hadir' => '0', 'updated_at' => date('Y-m-d H:i:s')])
                                      ->update();

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Absensi berhasil direset',
                    'reset_count' => $this->pesertaModel->affectedRows()
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal mereset absensi',
                    'error_code' => 'RESET_FAILED'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Absen reset error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mereset absensi',
                'error_code' => 'SYSTEM_ERROR'
            ]);
        }
    }
}
