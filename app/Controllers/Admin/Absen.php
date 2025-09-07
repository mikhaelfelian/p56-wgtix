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

class Absen extends BaseController
{
    protected $pesertaModel;
    protected $eventsModel;

    public function __construct()
    {
        parent::__construct();
        $this->pesertaModel = new PesertaModel();
        $this->eventsModel = new EventsModel();
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
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $qrCode = $this->request->getPost('qr_code');
        $eventId = $this->request->getPost('event_id');

        if (!$qrCode || !$eventId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'QR Code dan Event ID diperlukan'
            ]);
        }

        // Find participant by QR code and event
        $participant = $this->pesertaModel->where('qr_code', $qrCode)
                                        ->where('id_event', $eventId)
                                        ->where('status !=', -1)
                                        ->first();

        if (!$participant) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Peserta tidak ditemukan atau QR Code tidak valid'
            ]);
        }

        // Check if already attended
        if ($participant->status_hadir == '1') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Peserta sudah melakukan absensi sebelumnya',
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
            'updated_at' => date('Y-m-d H:i:s')
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
                'message' => 'Gagal mencatat absensi'
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
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $event = $this->eventsModel->find($eventId);
        if (!$event) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Event tidak ditemukan'
            ]);
        }

        // Reset all attendance for this event
        $this->pesertaModel->where('id_event', $eventId)
                          ->set(['status_hadir' => '0', 'updated_at' => date('Y-m-d H:i:s')])
                          ->update();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Absensi berhasil direset'
        ]);
    }
}
