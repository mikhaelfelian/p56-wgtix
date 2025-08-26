<?php

/**
 * EventGaleriModel
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Model for managing event gallery data
 */

namespace App\Models;

use CodeIgniter\Model;

class EventGaleriModel extends Model
{
    protected $table      = 'tbl_m_event_galeri';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'id_event',
        'file',
        'deskripsi',
        'is_cover',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'id_event' => 'required|integer',
        'file'     => 'required|max_length[200]',
        'deskripsi' => 'permit_empty',
        'is_cover'  => 'required|in_list[0,1]',
        'status'    => 'required|in_list[0,1]'
    ];

    protected $validationMessages = [
        'id_event' => [
            'required' => 'ID Event harus diisi',
            'integer' => 'ID Event harus berupa angka'
        ],
        'file' => [
            'required'   => 'File harus diisi',
            'max_length' => 'Nama file maksimal 200 karakter'
        ],
        'is_cover' => [
            'required' => 'Status cover harus dipilih',
            'in_list'  => 'Status cover harus 0 (bukan cover) atau 1 (cover utama)'
        ],
        'status' => [
            'required' => 'Status harus dipilih',
            'in_list'  => 'Status harus 0 (non-aktif) atau 1 (aktif)'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get gallery by event ID
     * 
     * @param int $eventId
     * @return array
     */
    public function getGalleryByEvent($eventId)
    {
        return $this->where('id_event', $eventId)
                    ->where('status', 1)
                    ->orderBy('is_cover', 'DESC')
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Get cover image for an event
     * 
     * @param int $eventId
     * @return object|null
     */
    public function getCoverImage($eventId)
    {
        return $this->where('id_event', $eventId)
                    ->where('is_cover', 1)
                    ->where('status', 1)
                    ->first();
    }

    /**
     * Get gallery with event details
     * 
     * @param int $eventId
     * @return array
     */
    public function getGalleryWithEvent($eventId)
    {
        return $this->select('tbl_m_event_galeri.*, 
                             tbl_m_event.event as event_name')
                    ->join('tbl_m_event', 'tbl_m_event.id = tbl_m_event_galeri.id_event', 'left')
                    ->where('tbl_m_event_galeri.id_event', $eventId)
                    ->where('tbl_m_event_galeri.status', 1)
                    ->orderBy('tbl_m_event_galeri.is_cover', 'DESC')
                    ->orderBy('tbl_m_event_galeri.created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Get all active gallery with event details
     * 
     * @return array
     */
    public function getAllActiveGalleryWithEvents()
    {
        return $this->select('tbl_m_event_galeri.*, 
                             tbl_m_event.event as event_name,
                             tbl_m_event.tgl_masuk,
                             tbl_m_event.lokasi')
                    ->join('tbl_m_event', 'tbl_m_event.id = tbl_m_event_galeri.id_event', 'left')
                    ->where('tbl_m_event_galeri.status', 1)
                    ->where('tbl_m_event.status', 1)
                    ->orderBy('tbl_m_event.tgl_masuk', 'DESC')
                    ->orderBy('tbl_m_event_galeri.is_cover', 'DESC')
                    ->findAll();
    }

    /**
     * Set cover image for an event
     * 
     * @param int $eventId
     * @param int $galleryId
     * @return bool
     */
    public function setCoverImage($eventId, $galleryId)
    {
        // Remove existing cover
        $this->where('id_event', $eventId)
             ->where('is_cover', 1)
             ->set(['is_cover' => 0])
             ->update();

        // Set new cover
        return $this->update($galleryId, ['is_cover' => 1]);
    }

    /**
     * Get gallery statistics
     * 
     * @return array
     */
    public function getGalleryStatistics()
    {
        $totalGallery = $this->countAllResults();
        $activeGallery = $this->where('status', 1)->countAllResults();
        $coverImages = $this->where('is_cover', 1)->where('status', 1)->countAllResults();
        $inactiveGallery = $this->where('status', 0)->countAllResults();

        return [
            'total_gallery'    => $totalGallery,
            'active_gallery'   => $activeGallery,
            'cover_images'     => $coverImages,
            'inactive_gallery' => $inactiveGallery
        ];
    }

    /**
     * Get gallery for frontend display
     * 
     * @param int $eventId
     * @return array
     */
    public function getFrontendGallery($eventId)
    {
        return $this->where('id_event', $eventId)
                    ->where('status', 1)
                    ->orderBy('is_cover', 'DESC')
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Get gallery by multiple events
     * 
     * @param array $eventIds
     * @return array
     */
    public function getGalleryByEvents($eventIds)
    {
        if (empty($eventIds)) {
            return [];
        }

        return $this->select('tbl_m_event_galeri.*, 
                             tbl_m_event.event as event_name,
                             tbl_m_event.tgl_masuk,
                             tbl_m_event.lokasi')
                    ->join('tbl_m_event', 'tbl_m_event.id = tbl_m_event_galeri.id_event', 'left')
                    ->whereIn('tbl_m_event_galeri.id_event', $eventIds)
                    ->where('tbl_m_event_galeri.status', 1)
                    ->where('tbl_m_event.status', 1)
                    ->orderBy('tbl_m_event.tgl_masuk', 'DESC')
                    ->orderBy('tbl_m_event_galeri.is_cover', 'DESC')
                    ->findAll();
    }

    /**
     * Search gallery by event name or description
     * 
     * @param string $keyword
     * @return array
     */
    public function searchGallery($keyword)
    {
        return $this->select('tbl_m_event_galeri.*, 
                             tbl_m_event.event as event_name,
                             tbl_m_event.tgl_masuk,
                             tbl_m_event.lokasi')
                    ->join('tbl_m_event', 'tbl_m_event.id = tbl_m_event_galeri.id_event', 'left')
                    ->where('tbl_m_event_galeri.status', 1)
                    ->where('tbl_m_event.status', 1)
                    ->groupStart()
                        ->like('tbl_m_event.event', $keyword)
                        ->orLike('tbl_m_event_galeri.deskripsi', $keyword)
                    ->groupEnd()
                    ->orderBy('tbl_m_event.tgl_masuk', 'DESC')
                    ->findAll();
    }

    /**
     * Get recent gallery items
     * 
     * @param int $limit
     * @return array
     */
    public function getRecentGallery($limit = 10)
    {
        return $this->select('tbl_m_event_galeri.*, 
                             tbl_m_event.event as event_name,
                             tbl_m_event.tgl_masuk,
                             tbl_m_event.lokasi')
                    ->join('tbl_m_event', 'tbl_m_event.id = tbl_m_event_galeri.id_event', 'left')
                    ->where('tbl_m_event_galeri.status', 1)
                    ->where('tbl_m_event.status', 1)
                    ->orderBy('tbl_m_event_galeri.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get gallery count by event
     * 
     * @param int $eventId
     * @return int
     */
    public function getGalleryCountByEvent($eventId)
    {
        return $this->where('id_event', $eventId)
                    ->where('status', 1)
                    ->countAllResults();
    }

    /**
     * Deactivate all gallery for an event
     * 
     * @param int $eventId
     * @return bool
     */
    public function deactivateEventGallery($eventId)
    {
        return $this->where('id_event', $eventId)
                    ->set(['status' => 0])
                    ->update();
    }

    /**
     * Get gallery by event ID (alias for getGalleryByEvent)
     * 
     * @param int $eventId
     * @return array
     */
    public function getByEvent($eventId)
    {
        return $this->getGalleryByEvent($eventId);
    }

    /**
     * Get galleries with pagination and search
     * 
     * @param int $page
     * @param int $perPage
     * @param string $keyword
     * @return array
     */
    public function getGalleriesWithPagination($page = 1, $perPage = 10, $keyword = '')
    {
        $offset = ($page - 1) * $perPage;
        
        $builder = $this->select('tbl_m_event_galeri.*, tbl_m_event.event as event_name')
                        ->join('tbl_m_event', 'tbl_m_event.id = tbl_m_event_galeri.id_event', 'left')
                        ->where('tbl_m_event_galeri.status_hps', 0);
        
        if (!empty($keyword)) {
            $builder->groupStart()
                        ->like('tbl_m_event.event', $keyword)
                        ->orLike('tbl_m_event_galeri.deskripsi', $keyword)
                    ->groupEnd();
        }
        
        return $builder->orderBy('tbl_m_event_galeri.created_at', 'DESC')
                      ->limit($perPage, $offset)
                      ->findAll();
    }

    /**
     * Get total galleries count for pagination
     * 
     * @param string $keyword
     * @return int
     */
    public function getTotalGalleries($keyword = '')
    {
        $builder = $this->select('tbl_m_event_galeri.id')
                        ->join('tbl_m_event', 'tbl_m_event.id = tbl_m_event_galeri.id_event', 'left')
                        ->where('tbl_m_event_galeri.status_hps', 0);
        
        if (!empty($keyword)) {
            $builder->groupStart()
                        ->like('tbl_m_event.event', $keyword)
                        ->orLike('tbl_m_event_galeri.deskripsi', $keyword)
                    ->groupEnd();
        }
        
        return $builder->countAllResults();
    }
}
