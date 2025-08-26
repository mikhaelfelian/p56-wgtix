<?php

/**
 * EventGallery Controller
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Admin controller for managing event galleries
 */

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EventGaleriModel;
use App\Models\EventsModel;

class EventGallery extends BaseController
{
    protected $eventGaleriModel;
    protected $eventsModel;

    public function __construct()
    {
        parent::__construct();
        $this->eventGaleriModel = new EventGaleriModel();
        $this->eventsModel = new EventsModel();
    }

    /**
     * Display a list of all galleries
     */
    public function index()
    {
        $keyword = $this->request->getGet('keyword') ?? '';
        $currentPage = (int) ($this->request->getGet('page_galleries') ?? 1);
        $perPage = 10;



        // Build the query once
        $builder = $this->eventGaleriModel
            ->select('tbl_m_event_galeri.id, tbl_m_event_galeri.id_event, tbl_m_event_galeri.file, tbl_m_event_galeri.deskripsi, tbl_m_event_galeri.status, tbl_m_event.event as event_name')
            ->join('tbl_m_event', 'tbl_m_event.id = tbl_m_event_galeri.id_event', 'left')
            ->where('tbl_m_event_galeri.status !=', -1);

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('tbl_m_event.event', $keyword)
                ->orLike('tbl_m_event_galeri.deskripsi', $keyword)
                ->groupEnd();
        }

        $galleries = $builder->paginate($perPage, 'galleries');
        $pager = $this->eventGaleriModel->pager;

        $data = [
            'title'       => 'Kelola Galeri Event',
            'galleries'   => $galleries,
            'pager'       => $pager,
            'keyword'     => $keyword,
            'currentPage' => $currentPage,
            'perPage'     => $perPage,
        ];

        return $this->view($this->theme->getThemePath() . '/admin/events/index_galeri', $data);
    }

    /**
     * Manage gallery for a specific event
     */
    public function manage($eventId = null)
    {
        $event = $this->eventsModel->find($eventId);
        if (!$event) {
            return redirect()->to('/admin/events')
                ->with('error', 'Event tidak ditemukan');
        }

        $galleries = $this->eventGaleriModel->getByEvent($eventId);

        $data = [
            'title' => 'Kelola Galeri Event: ' . $event->event,
            'event' => $event,
            'galleries' => $galleries
        ];

        return $this->view($this->theme->getThemePath() . '/admin/events/manage_galeri', $data);
    }

    /**
     * Upload gallery images via AJAX
     */
    public function upload()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Invalid request'
            ]);
        }

        $eventId = $this->request->getPost('event_id');
        if (!$eventId) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Event ID required'
            ]);
        }

        // Check if event exists
        $event = $this->eventsModel->find($eventId);
        if (!$event) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Event not found'
            ]);
        }

        // Check upload directory permissions
        $uploadPath = FCPATH . '/file/events/' . $eventId . '/gallery/';
        if (!is_dir($uploadPath)) {
            if (!mkdir($uploadPath, 0755, true)) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Gagal membuat direktori upload. Pastikan folder public/file/events/ dapat ditulis.'
                ]);
            }
        }

        if (!is_writable($uploadPath)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Direktori upload tidak dapat ditulis. Periksa permission folder.'
            ]);
        }

        $uploadedFiles = [];
        $errors = [];

        // Handle multiple file uploads - check if files exist first
        $files = $this->request->getFileMultiple('gallery');

        // Defensive: If getFileMultiple returns null, try getFile (single upload fallback)
        if (empty($files) || !is_array($files)) {
            $singleFile = $this->request->getFile('gallery');
            if ($singleFile && $singleFile->isValid()) {
                $files = [$singleFile];
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Tidak ada file yang diupload atau format file tidak valid'
                ]);
            }
        }

        foreach ($files as $file) {
            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Validate file size (2MB max)
                if ($file->getSize() > 2048 * 1024) {
                    $errors[] = $file->getName() . ' - File terlalu besar (maksimal 2MB)';
                    continue;
                }

                // Validate file type
                $allowedMimes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($file->getClientMimeType(), $allowedMimes)) {
                    $errors[] = $file->getName() . ' - Tipe file tidak valid (hanya JPG, PNG, GIF)';
                    continue;
                }

                // Generate unique filename
                $fileName = $file->getRandomName();

                // Create directory if not exists (redundant, but safe)
                if (!is_dir($uploadPath)) {
                    if (!mkdir($uploadPath, 0755, true)) {
                        $errors[] = $file->getName() . ' - Gagal membuat direktori upload';
                        continue;
                    }
                }

                // Move uploaded file
                try {
                    if ($file->move($uploadPath, $fileName)) {
                        // Save to database using save() (insert or update)
                        $galleryData = [
                            'id_event' => $eventId,
                            'file' => $fileName,
                            'deskripsi' => '',
                            'is_cover' => 0,
                            'status' => 1
                        ];

                        if ($this->eventGaleriModel->save($galleryData)) {
                            $uploadedFiles[] = [
                                'name' => $file->getName(),
                                'file' => $fileName,
                                'id' => $this->eventGaleriModel->getInsertID()
                            ];
                        } else {
                            $errors[] = $file->getName() . ' - Gagal menyimpan ke database';
                            // Remove uploaded file if database insert fails
                            if (file_exists($uploadPath . $fileName)) {
                                unlink($uploadPath . $fileName);
                            }
                        }
                    } else {
                        $errors[] = $file->getName() . ' - Gagal memindahkan file ke direktori upload';
                    }
                } catch (\Exception $e) {
                    $errors[] = $file->getName() . ' - Error: ' . $e->getMessage();
                }
            } else {
                $errors[] = ($file && method_exists($file, 'getName') ? $file->getName() : 'File') . ' - File tidak valid atau sudah dipindahkan';
            }
        }

        // Prepare response
        $response = [
            'success' => count($uploadedFiles) > 0,
            'uploaded' => $uploadedFiles,
            'errors' => $errors,
            'message' => ''
        ];

        if (count($uploadedFiles) > 0 && count($errors) === 0) {
            $response['message'] = 'Semua file berhasil diupload';
        } elseif (count($uploadedFiles) > 0 && count($errors) > 0) {
            $response['message'] = 'Beberapa file berhasil diupload, beberapa gagal';
        } elseif (count($uploadedFiles) === 0 && count($errors) > 0) {
            $response['message'] = 'Semua file gagal diupload';
            $response['success'] = false;
        }

        return $this->response->setJSON($response);
    }
    

    /**
     * Set image as cover
     */
    public function setCover($id = null)
    {
        $gallery = $this->eventGaleriModel->find($id);
        if (!$gallery) {
            return redirect()->back()
                ->with('error', 'Galeri tidak ditemukan');
        }

        // Unset previous cover for this event
        $this->eventGaleriModel->where('id_event', $gallery->id_event)
                               ->where('is_cover', 1)
                               ->set(['is_cover' => 0])
                               ->update();

        // Set new cover
        if ($this->eventGaleriModel->update($id, ['is_cover' => 1])) {
            return redirect()->back()
                ->with('success', 'Cover image berhasil diubah');
        }

        return redirect()->back()
            ->with('error', 'Gagal mengubah cover image');
    }

    /**
     * Update gallery description
     */
    public function updateDescription()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request']);
        }

        $id = $this->request->getPost('id');
        $description = $this->request->getPost('description');

        if (!$id) {
            return $this->response->setJSON(['error' => 'ID required']);
        }

        if ($this->eventGaleriModel->update($id, ['deskripsi' => $description])) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['error' => 'Failed to update description']);
    }

    /**
     * Delete gallery image
     */
    public function delete($id = null)
    {
        $gallery = $this->eventGaleriModel->find($id);
        if (!$gallery) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Galeri tidak ditemukan'
            ]);
        }

        $eventId = $gallery->id_event;
        $fileName = $gallery->file;

        // Delete from database first
        if ($this->eventGaleriModel->delete($id)) {
            // Delete physical file
            $filePath = FCPATH . '/file/events/' . $eventId . '/gallery/' . $fileName;
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Galeri berhasil dihapus'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'error' => 'Gagal menghapus galeri'
        ]);
    }

    /**
     * Toggle gallery status
     */
    public function toggleStatus($id = null)
    {
        $gallery = $this->eventGaleriModel->find($id);
        if (!$gallery) {
            return redirect()->back()
                ->with('error', 'Galeri tidak ditemukan');
        }

        $newStatus = $gallery->status == 1 ? 0 : 1;
        
        if ($this->eventGaleriModel->update($id, ['status' => $newStatus])) {
            $statusText = $newStatus == 1 ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->back()
                ->with('success', "Status galeri berhasil $statusText");
        }

        return redirect()->back()
            ->with('error', 'Gagal mengubah status galeri');
    }
}
