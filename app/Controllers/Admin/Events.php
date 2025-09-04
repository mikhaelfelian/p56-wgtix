<?php

/**
 * Events Controller
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Admin controller for managing events
 */

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EventsModel;
use App\Models\EventsHargaModel;
use App\Models\EventGaleriModel;
use App\Models\KategoriModel;

class Events extends BaseController
{
    protected $eventsModel;
    protected $eventsHargaModel;
    protected $eventGaleriModel;
    protected $kategoriModel;

    public function __construct()
    {
        parent::__construct();
        $this->eventsModel = new EventsModel();
        $this->eventsHargaModel = new EventsHargaModel();
        $this->eventGaleriModel = new EventGaleriModel();
        $this->kategoriModel = new KategoriModel();
    }

    /**
     * Display a list of events
     */
    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $keyword = $this->request->getGet('keyword') ?? '';
        $perPage = $this->pengaturan->pagination_limit; // Items per page
        
        // Get events with pagination and search
        $events = $this->eventsModel->getEventsWithFilters($perPage, $keyword, $page);
        $total = $this->eventsModel->getTotalEvents($keyword);
        
        // Create pager
        $pager = service('pager');
        $pager->setPath('admin/events');
        $pager->makeLinks($total, $perPage, $page, 'adminlte_pagination');
        
        $data = [
            'title' => 'Kelola Event',
            'events' => $events,
            'statistics' => $this->eventsModel->getEventStatistics(),
            'pager' => $pager,
            'currentPage' => $page,
            'perPage' => $perPage,
            'keyword' => $keyword,
            'total' => $total
        ];

        return $this->view($this->theme->getThemePath() . '/admin/events/index', $data);
    }

    /**
     * Show the form for creating a new event
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Event Baru',
            'kategoriOptions' => $this->kategoriModel->getActiveCategories()
        ];

        return $this->view($this->theme->getThemePath() . '/admin/events/create', $data);
    }

    /**
     * Store a newly created event
     */
    public function store()
    {
        $rules = [
            'id_kategori'     => 'required|integer',
            'event'           => 'required|max_length[100]',
            'foto'            => 'permit_empty|uploaded[foto]|max_size[foto,2048]|is_image[foto]',
            'tgl_masuk'       => 'required|valid_date',
            'tgl_keluar'      => 'required|valid_date',
            'wkt_masuk'       => 'required',
            'wkt_keluar'      => 'required',
            'lokasi'          => 'permit_empty|max_length[200]',
            'jml'             => 'permit_empty|integer|greater_than_equal_to[0]',
            'keterangan'      => 'permit_empty',
            'status'          => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/events/create')
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $id_user      = $this->data['user']->id;
        $id_kategori  = $this->request->getPost('id_kategori');
        $kode         = $this->request->getPost('kode') ?: null;
        $event        = $this->request->getPost('event');
        $tgl_masuk    = $this->request->getPost('tgl_masuk');
        $tgl_keluar   = $this->request->getPost('tgl_keluar');
        $wkt_masuk    = $this->request->getPost('wkt_masuk');
        $wkt_keluar   = $this->request->getPost('wkt_keluar');
        $lokasi       = $this->request->getPost('lokasi') ?: null;
        $jml          = $this->request->getPost('jml') ?: null;
        $keterangan   = $this->request->getPost('keterangan') ?: null;
        
        // Sanitize keterangan field (HTML content from TinyMCE)
        if ($keterangan) {
            $keterangan = $this->sanitizeHtml($keterangan);
        }
        
        $status       = $this->request->getPost('status');
        $id           = $this->request->getPost('id'); // Add id if exists
        $latitude     = $this->request->getPost('lat') ?: null;
        $longitude    = $this->request->getPost('long') ?: null;

        // Handle primary photo upload
        $foto = null;
        if ($this->request->getFile('foto') && $this->request->getFile('foto')->isValid()) {
            $fotoFile = $this->request->getFile('foto');
            $foto = $fotoFile->getRandomName();
        }else{
            $foto = $this->request->getPost('foto_lama');
        }
        $keterangan = (string) ($this->request->getPost('keterangan') ?? '');
        $keterangan = preg_replace('/<\?xml[^>]*\?>|<!--\s*\?xml[\s\S]*?\?-->/i', '', $keterangan);

        $data = [
            'id_user'         => $id_user,
            'id_kategori'     => $id_kategori,
            'kode'            => $kode,
            'event'           => $event,
            'foto'            => $foto,
            'tgl_masuk'       => $tgl_masuk,
            'tgl_keluar'      => $tgl_keluar,
            'wkt_masuk'       => $wkt_masuk,
            'wkt_keluar'      => $wkt_keluar,
            'lokasi'          => $lokasi,
            'jml'             => $jml,
            'keterangan'      => $keterangan,
            'latitude'        => $latitude,
            'longitude'       => $longitude,
            'status'          => $status,
        ];

        if ($id) {
            $data['id'] = $id;
        }

        try {
            if ($this->eventsModel->save($data)) {
                // Get the event ID (insertID for new, or $id for update)
                $eventId = $id ?: $this->eventsModel->insertID();

                // Upload primary photo if exists
                if ($foto && $this->request->getFile('foto') && $this->request->getFile('foto')->isValid()) {
                    $fotoFile = $this->request->getFile('foto');
                    $uploadPath = FCPATH . 'file/events/' . $eventId . '/';

                    // Create directory if not exists
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }

                    // Move uploaded file
                    $fotoFile->move($uploadPath, $foto);
                }

                return redirect()->to('/admin/events/edit/' . $eventId)
                    ->with('success', 'Event berhasil disimpan');
            } else {
                // Get validation errors if any
                $errors = $this->eventsModel->errors();
                if (!empty($errors)) {
                    return redirect()->to('/admin/events/create')
                        ->withInput()
                        ->with('errors', $errors);
                }

                return redirect()->to('/admin/events/create')
                    ->withInput()
                    ->with('error', 'Gagal menambahkan event');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/events/create')
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified event
     */
    public function show($id = null)
    {
        $event = $this->eventsModel->find($id);

        if (!$event) {
            return redirect()->to('/admin/events')
                ->with('error', 'Event tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Event',
            'event' => $event,
            'pricing' => $this->eventsHargaModel->getEventPricings($id),
            'gallery' => $this->eventGaleriModel->getGalleryByEvent($id),
            'capacity_info' => $this->eventsModel->getEventCapacityInfo($id)
        ];

        return $this->view($this->theme->getThemePath() . '/admin/events/show', $data);
    }

    /**
     * Show the form for editing the specified event
     */
    public function edit($id = null)
    {
        $event = $this->eventsModel->find($id);

        if (!$event) {
            return redirect()->to('/admin/events')
                ->with('error', 'Event tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Event',
            'event' => $event,
            'kategoriOptions' => $this->kategoriModel->getActiveCategories()
        ];

        return $this->view($this->theme->getThemePath() . '/admin/events/create', $data);
    }

    /**
     * Remove the specified event
     */
    public function delete($id = null)
    {
        $event = $this->eventsModel->find($id);

        if (!$event) {
            return redirect()->to('/admin/events')
                ->with('error', 'Event tidak ditemukan');
        }

        // Check if event has participants
        $capacityInfo = $this->eventsModel->getEventCapacityInfo($id);
        if ($capacityInfo && $capacityInfo['registered_count'] > 0) {
            return redirect()->to('/admin/events')
                ->with('error', 'Event tidak dapat dihapus karena sudah ada peserta terdaftar');
        }

        if ($this->eventsModel->delete($id)) {
            // Also delete related pricing and gallery
            $this->eventsHargaModel->deactivateEventPricing($id);
            $this->eventGaleriModel->deactivateEventGallery($id);

            return redirect()->to('/admin/events')
                ->with('success', 'Event berhasil dihapus');
        }

        return redirect()->to('/admin/events')
            ->with('error', 'Gagal menghapus event');
    }

    /**
     * Toggle event status
     */
    public function toggleStatus($id = null)
    {
        $event = $this->eventsModel->find($id);

        if (!$event) {
            return redirect()->to('/admin/events')
                ->with('error', 'Event tidak ditemukan');
        }

        $newStatus = $event->status == 1 ? 0 : 1;
        
        if ($this->eventsModel->update($id, ['status' => $newStatus])) {
            $statusText = $newStatus == 1 ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->to('/admin/events')
                ->with('success', "Event berhasil {$statusText}");
        }

        return redirect()->to('/admin/events')
            ->with('error', 'Gagal mengubah status event');
    }

    /**
     * Get events for AJAX requests
     */
    public function getEventsAjax()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request']);
        }

        $events = $this->eventsModel->getActiveEvents();
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Search events
     */
    public function search()
    {
        $keyword = $this->request->getGet('q');
        
        if (empty($keyword)) {
            return redirect()->to('/admin/events');
        }

        $events = $this->eventsModel->searchEvents($keyword);

        $data = [
            'title' => 'Hasil Pencarian Event',
            'events' => $events,
            'keyword' => $keyword,
            'statistics' => $this->eventsModel->getEventStatistics()
        ];

        return $this->view($this->theme->getThemePath() . '/admin/events/search', $data);
    }

    /**
     * Export events to Excel/PDF
     */
    public function export($format = 'excel')
    {
        $events = $this->eventsModel->getFrontendEvents();

        if ($format === 'excel') {
            return $this->exportToExcel($events);
        } elseif ($format === 'pdf') {
            return $this->exportToPDF($events);
        }

        return redirect()->to('/admin/events')
            ->with('error', 'Format export tidak valid');
    }

    /**
     * Export to Excel
     */
    private function exportToExcel($events)
    {
        // Implementation for Excel export
        // You can use PhpSpreadsheet library
        return redirect()->to('/admin/events')
            ->with('info', 'Fitur export Excel akan segera tersedia');
    }

    /**
     * Export to PDF
     */
    private function exportToPDF($events)
    {
        // Implementation for PDF export
        // You can use TCPDF or Dompdf library
        return redirect()->to('/admin/events')
            ->with('info', 'Fitur export PDF akan segera tersedia');
    }
    
    /**
     * Upload gallery images via AJAX
     */
    public function uploadGallery()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request']);
        }
        
        $eventId = $this->request->getPost('event_id');
        if (!$eventId) {
            return $this->response->setJSON(['error' => 'Event ID required']);
        }
        
        // Check if event exists
        $event = $this->eventsModel->find($eventId);
        if (!$event) {
            return $this->response->setJSON(['error' => 'Event not found']);
        }
        
        $uploadedFiles = [];
        $errors = [];
        
        // Handle multiple file uploads
        $files = $this->request->getFileMultiple('gallery');
        
        foreach ($files as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                // Validate file
                if (!$file->isValid() || $file->getSize() > 2048 * 1024) { // 2MB max
                    $errors[] = $file->getName() . ' - File too large or invalid';
                    continue;
                }
                
                if (!in_array($file->getClientMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
                    $errors[] = $file->getName() . ' - Invalid file type';
                    continue;
                }
                
                $fileName = $file->getRandomName();
                $uploadPath = FCPATH . 'public/file/events/' . $eventId . '/gallery/';
                
                // Create directory if not exists
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Move uploaded file
                if ($file->move($uploadPath, $fileName)) {
                    // Save to database
                    $galleryData = [
                        'id_event' => $eventId,
                        'file' => $fileName,
                        'deskripsi' => '',
                        'is_cover' => 0,
                        'status' => 1
                    ];
                    
                    if ($this->eventGaleriModel->insert($galleryData)) {
                        $uploadedFiles[] = [
                            'name' => $file->getName(),
                            'file' => $fileName,
                            'id' => $this->eventGaleriModel->insertID()
                        ];
                    } else {
                        $errors[] = $file->getName() . ' - Failed to save to database';
                    }
                } else {
                    $errors[] = $file->getName() . ' - Failed to upload';
                }
            }
        }
        
        return $this->response->setJSON([
            'success' => true,
            'uploaded' => $uploadedFiles,
            'errors' => $errors
        ]);
    }

    /**
     * Show event pricing management
     */
    public function pricing($eventId = null)
    {
        $event = $this->eventsModel->find($eventId);
        if (!$event) {
            return redirect()->to('/admin/events')
                ->with('error', 'Event tidak ditemukan');
        }

        $prices = $this->eventsHargaModel->getEventPricings($eventId);

        $data = [
            'title' => 'Kelola Harga Event: ' . $event->event,
            'event' => $event,
            'prices' => $prices
        ];

        return $this->view($this->theme->getThemePath() . '/admin/events/pricing', $data);
    }

    /**
     * Store new event price
     */
    public function storePrice()
    {
        $id_event   = $this->request->getPost('id_event');
        $harga      = $this->request->getPost('harga_numeric'); // Use numeric value from AutoNumeric
        $status     = $this->request->getPost('status');
        $keterangan = $this->request->getPost('keterangan');
        $id         = $this->request->getPost('id'); // For update mode

        $rules = [
            'id_event'   => 'required|integer',
            'harga'      => 'required',
            'status'     => 'required|in_list[0,1]',
            'keterangan' => 'permit_empty|string'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/events/pricing/' . $id_event)
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id_event'   => $id_event,
            'harga'      => $harga, // Already clean numeric value
            'status'     => $status,
            'keterangan' => $keterangan
        ];

        // If ID exists, it's update mode; if empty, it's insert mode
        if (!empty($id)) {
            $data['id'] = $id;
        }

        // Use save() method - CI4 automatically handles insert vs update
        if ($this->eventsHargaModel->save($data)) {
            $message = !empty($id) ? 'Harga berhasil diupdate' : 'Harga berhasil ditambahkan';
            return redirect()->to('/admin/events/pricing/' . $id_event)
                ->with('success', $message);
        }

        return redirect()->to('/admin/events/pricing/' . $id_event)
            ->withInput()
            ->with('error', 'Gagal menyimpan harga');
    }


    /**
     * Delete event price
     */
    public function deletePrice($priceId = null)
    {
        $price = $this->eventsHargaModel->find($priceId);
        if (!$price) {
            return redirect()->to('/admin/events')
                ->with('error', 'Harga tidak ditemukan');
        }

        $eventId = $price->id_event;

        if ($this->eventsHargaModel->delete($priceId)) {
            return redirect()->to('/admin/events/pricing/' . $eventId)
                ->with('success', 'Harga berhasil dihapus');
        }

        return redirect()->to('/admin/events/pricing/' . $eventId)
            ->with('error', 'Gagal menghapus harga');
    }

    /**
     * Toggle price status
     */
    public function togglePriceStatus($priceId = null)
    {
        $price = $this->eventsHargaModel->find($priceId);
        if (!$price) {
            // Redirect to event list if price not found
            return redirect()->to('/admin/events')
                ->with('error', 'Harga tidak ditemukan');
        }

        $newStatus = $price->status == 1 ? '0' : '1';

        if ($this->eventsHargaModel->update($priceId, ['status' => $newStatus])) {
            $statusText = $newStatus == 1 ? 'diaktifkan' : 'dinonaktifkan';
            // Redirect to the pricing page of the event
            return redirect()->to('/admin/events/pricing/' . $price->id_event)
                ->with('success', "Status harga berhasil $statusText");
        }

        return redirect()->to('/admin/events/pricing/' . $price->id_event)
            ->with('error', 'Gagal mengubah status harga');
    }

    /**
     * Sanitize HTML content from TinyMCE
     * Allows safe HTML tags while removing potentially dangerous content
     */
    private function sanitizeHtml($html)
    {
        if (empty($html)) {
            return $html;
        }

        // Define allowed HTML tags and attributes
        $allowedTags = [
            'p' => ['class', 'style'],
            'br' => [],
            'strong' => ['class', 'style'],
            'b' => ['class', 'style'],
            'em' => ['class', 'style'],
            'i' => ['class', 'style'],
            'u' => ['class', 'style'],
            'h1' => ['class', 'style'],
            'h2' => ['class', 'style'],
            'h3' => ['class', 'style'],
            'h4' => ['class', 'style'],
            'h5' => ['class', 'style'],
            'h6' => ['class', 'style'],
            'ul' => ['class', 'style'],
            'ol' => ['class', 'style'],
            'li' => ['class', 'style'],
            'blockquote' => ['class', 'style'],
            'a' => ['href', 'target', 'class', 'style', 'title'],
            'img' => ['src', 'alt', 'class', 'style', 'width', 'height', 'title'],
            'table' => ['class', 'style', 'width', 'border', 'cellpadding', 'cellspacing'],
            'thead' => ['class', 'style'],
            'tbody' => ['class', 'style'],
            'tr' => ['class', 'style'],
            'td' => ['class', 'style', 'colspan', 'rowspan', 'width', 'height'],
            'th' => ['class', 'style', 'colspan', 'rowspan', 'width', 'height'],
            'div' => ['class', 'style'],
            'span' => ['class', 'style'],
            'hr' => ['class', 'style'],
            'code' => ['class', 'style'],
            'pre' => ['class', 'style']
        ];

        // Use HTML Purifier or create a simple sanitizer
        $dom = new \DOMDocument();
        
        // Suppress warnings for malformed HTML
        libxml_use_internal_errors(true);
        
        // Load HTML with UTF-8 encoding
        $dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
        // Clear any libxml errors
        libxml_clear_errors();
        
        // Get all elements
        $elements = $dom->getElementsByTagName('*');
        
        // Remove disallowed elements and attributes
        for ($i = $elements->length - 1; $i >= 0; $i--) {
            $element = $elements->item($i);
            $tagName = strtolower($element->tagName);
            
            // Remove disallowed tags
            if (!array_key_exists($tagName, $allowedTags)) {
                $element->parentNode->removeChild($element);
                continue;
            }
            
            // Remove disallowed attributes
            $allowedAttrs = $allowedTags[$tagName];
            $attributes = $element->attributes;
            
            for ($j = $attributes->length - 1; $j >= 0; $j--) {
                $attr = $attributes->item($j);
                $attrName = strtolower($attr->name);
                
                if (!in_array($attrName, $allowedAttrs)) {
                    $element->removeAttribute($attr->name);
                } else {
                    // Additional security for specific attributes
                    if ($attrName === 'href' && $tagName === 'a') {
                        $href = $attr->value;
                        // Only allow http, https, mailto, and tel protocols
                        if (!preg_match('/^(https?|mailto|tel):/i', $href)) {
                            $element->removeAttribute('href');
                        }
                    }
                    
                    if ($attrName === 'src' && $tagName === 'img') {
                        $src = $attr->value;
                        // Only allow http and https protocols for images
                        if (!preg_match('/^https?:\/\//i', $src)) {
                            $element->removeAttribute('src');
                        }
                    }
                }
            }
        }
        
        // Get cleaned HTML
        $cleanHtml = $dom->saveHTML();
        
        // Remove XML declaration and extra whitespace
        $cleanHtml = preg_replace('/<\?xml[^>]*\?>/', '', $cleanHtml);
        $cleanHtml = trim($cleanHtml);
        
        return $cleanHtml;
    }
}
