<?php

namespace App\Models;

use CodeIgniter\Model;

class EventsGaleriModel extends Model
{
    protected $table            = 'tbl_m_event_galeri';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'id_event',
        'file',
        'deskripsi',
        'is_cover',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules    = [
        'id_event'  => 'required|integer',
        'file'      => 'required|string|max_length[200]',
        'is_cover'  => 'permit_empty|in_list[0,1]',
        'status'    => 'permit_empty|in_list[0,1]',
        'deskripsi' => 'permit_empty|string'
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Get gallery images for a specific event
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
     * Get active gallery images for frontend display
     * @param int $eventId
     * @return array
     */
    public function getActiveGalleryByEvent($eventId)
    {
        return $this->where('id_event', $eventId)
                    ->where('status', 1)
                    ->orderBy('is_cover', 'DESC')
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Get cover image for event
     * @param int $eventId
     * @return array|null
     */
    public function getCoverImage($eventId)
    {
        return $this->where('id_event', $eventId)
                    ->where('is_cover', 1)
                    ->where('status', 1)
                    ->first();
    }
}
