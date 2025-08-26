<?php

/**
 * EventPricing Controller
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Admin controller for managing event pricing
 */

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EventsModel;
use App\Models\EventsHargaModel;

class EventPricing extends BaseController
{
    protected $eventsModel;
    protected $eventsHargaModel;

    public function __construct()
    {
        parent::__construct();
        $this->eventsModel = new EventsModel();
        $this->eventsHargaModel = new EventsHargaModel();
    }

    /**
     * Display a list of event pricing
     */
    public function index()
    {
        $pricing = $this->eventsHargaModel->getAllActivePricingWithEvents();

        $data = [
            'title' => 'Kelola Harga Event',
            'pricing' => $pricing,
            'statistics' => $this->eventsHargaModel->getPricingStatistics()
        ];

        return $this->view($this->theme->getThemePath() . '/admin/event_pricing/index', $data);
    }

    /**
     * Show the form for creating a new pricing
     */
    public function create()
    {
        $events = $this->eventsModel->getActiveEvents();

        $data = [
            'title' => 'Tambah Harga Event Baru',
            'events' => $events
        ];

        return $this->view($this->theme->getThemePath() . '/admin/event_pricing/create', $data);
    }

    /**
     * Store a newly created pricing
     */
    public function store()
    {
        $id_event   = $this->request->getPost('id_event');
        $harga      = $this->request->getPost('harga');
        $status     = $this->request->getPost('status');
        $keterangan = $this->request->getPost('keterangan');

        $rules = [
            'id_event'   => 'required|integer',
            'harga'      => 'required',
            'status'     => 'required|in_list[0,1]',
            'keterangan' => 'permit_empty|string'
        ];

        // if (!$this->validate($rules)) {
        //     return redirect()->back()
        //         ->withInput()
        //         ->with('errors', $this->validator->getErrors());
        // }

        // // Check if event already has active pricing
        // $existingPricing = $this->eventsHargaModel->getActivePricing($id_event);
        // if ($existingPricing && $status == '1') {
        //     return redirect()->back()
        //         ->withInput()
        //         ->with('error', 'Event ini sudah memiliki harga aktif. Nonaktifkan harga lama terlebih dahulu.');
        // }

        $data = [
            'id_event'   => $id_event,
            'harga'      => format_angka_db($harga),
            'status'     => $status,
            'keterangan' => $keterangan
        ];

        $this->eventsHargaModel->save($data);
        pre($data);

        // if ($this->eventsHargaModel->save($data)) {
        //     return redirect()->to('/admin/events/pricing/' . $id_event)
        //         ->with('success', 'Harga event berhasil ditambahkan');
        // }

        // return redirect()->back()
        //     ->withInput()
        //     ->with('error', 'Gagal menambahkan harga event');
    }

    /**
     * Show the form for editing the specified pricing
     */
    public function edit($id = null)
    {
        $pricing = $this->eventsHargaModel->find($id);

        if (!$pricing) {
            return redirect()->to('/admin/event-pricing')
                ->with('error', 'Harga event tidak ditemukan');
        }

        $events = $this->eventsModel->getActiveEvents();

        $data = [
            'title' => 'Edit Harga Event',
            'pricing' => $pricing,
            'events' => $events
        ];

        return $this->view($this->theme->getThemePath() . '/admin/event_pricing/edit', $data);
    }

    /**
     * Update the specified pricing
     */
    public function update($id = null)
    {
        $pricing = $this->eventsHargaModel->find($id);

        if (!$pricing) {
            return redirect()->to('/admin/event-pricing')
                ->with('error', 'Harga event tidak ditemukan');
        }

        $rules = [
            'id_event' => 'required|integer',
            'harga'    => 'required|decimal',
            'status'   => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Check if another pricing is active for the same event
        if ($this->request->getPost('status') == '1') {
            $existingPricing = $this->eventsHargaModel->where('id_event', $this->request->getPost('id_event'))
                                                     ->where('id !=', $id)
                                                     ->where('status', '1')
                                                     ->first();
            if ($existingPricing) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Event ini sudah memiliki harga aktif lainnya. Nonaktifkan harga lain terlebih dahulu.');
            }
        }

        $data = [
            'id_event' => $this->request->getPost('id_event'),
            'harga'    => $this->request->getPost('harga'),
            'status'   => $this->request->getPost('status')
        ];

        if ($this->eventsHargaModel->update($id, $data)) {
            return redirect()->to('/admin/event-pricing')
                ->with('success', 'Harga event berhasil diperbarui');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal memperbarui harga event');
    }

    /**
     * Remove the specified pricing
     */
    public function delete($id = null)
    {
        $pricing = $this->eventsHargaModel->find($id);

        if (!$pricing) {
            return redirect()->to('/admin/event-pricing')
                ->with('error', 'Harga event tidak ditemukan');
        }

        if ($this->eventsHargaModel->delete($id)) {
            return redirect()->to('/admin/event-pricing')
                ->with('success', 'Harga event berhasil dihapus');
        }

        return redirect()->back()
            ->with('error', 'Gagal menghapus harga event');
    }

    /**
     * Get pricing by event ID
     */
    public function getByEvent($eventId = null)
    {
        $event = $this->eventsModel->find($eventId);

        if (!$event) {
            return redirect()->to('/admin/event-pricing')
                ->with('error', 'Event tidak ditemukan');
        }

        $pricing = $this->eventsHargaModel->getEventPricing($eventId);

        $data = [
            'title' => 'Harga Event: ' . $event->event,
            'event' => $event,
            'pricing' => $pricing
        ];

        return $this->view($this->theme->getThemePath() . '/admin/event_pricing/by_event', $data);
    }

    /**
     * Toggle pricing status
     */
    public function toggleStatus($id = null)
    {
        $pricing = $this->eventsHargaModel->find($id);

        if (!$pricing) {
            return redirect()->to('/admin/event-pricing')
                ->with('error', 'Harga event tidak ditemukan');
        }

        $newStatus = $pricing->status == '1' ? '0' : '1';
        
        // If activating, check if another pricing is already active for the same event
        if ($newStatus == '1') {
            $existingPricing = $this->eventsHargaModel->where('id_event', $pricing->id_event)
                                                     ->where('id !=', $id)
                                                     ->where('status', '1')
                                                     ->first();
            if ($existingPricing) {
                return redirect()->back()
                    ->with('error', 'Event ini sudah memiliki harga aktif. Nonaktifkan harga lain terlebih dahulu.');
            }
        }
        
        if ($this->eventsHargaModel->update($id, ['status' => $newStatus])) {
            $statusText = $newStatus == '1' ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->to('/admin/event-pricing')
                ->with('success', "Harga event berhasil {$statusText}");
        }

        return redirect()->back()
            ->with('error', 'Gagal mengubah status harga event');
    }

    /**
     * Get pricing for AJAX requests
     */
    public function getPricingAjax()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request']);
        }

        $eventId = $this->request->getGet('event_id');
        
        if ($eventId) {
            $pricing = $this->eventsHargaModel->getActivePricing($eventId);
        } else {
            $pricing = $this->eventsHargaModel->getAllActivePricingWithEvents();
        }
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $pricing
        ]);
    }

    /**
     * Search pricing
     */
    public function search()
    {
        $keyword = $this->request->getGet('q');
        
        if (empty($keyword)) {
            return redirect()->to('/admin/event-pricing');
        }

        $pricing = $this->eventsHargaModel->searchPricing($keyword);

        $data = [
            'title' => 'Hasil Pencarian Harga Event',
            'pricing' => $pricing,
            'keyword' => $keyword,
            'statistics' => $this->eventsHargaModel->getPricingStatistics()
        ];

        return $this->view($this->theme->getThemePath() . '/admin/event_pricing/search', $data);
    }
}
