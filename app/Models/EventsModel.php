<?php

/**
 * EventsModel
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Model for managing events (jadwal) master data
 */

namespace App\Models;

use CodeIgniter\Model;

class EventsModel extends Model
{
    protected $table      = 'tbl_m_event';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'id_user',
        'id_kategori',
        'kode',
        'event',
        'foto',
        'tgl_masuk',
        'tgl_keluar',
        'wkt_masuk',
        'wkt_keluar',
        'lokasi',
        'jml',
        'latitude',
        'longitude',
        'keterangan',
        'status',
        'status_hps'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'id_user'         => 'required|integer',
        'id_kategori'     => 'required|integer',
        'event'           => 'required|max_length[100]',
        'foto'            => 'permit_empty|max_length[160]',
        'tgl_masuk'       => 'required|valid_date',
        'tgl_keluar'      => 'required|valid_date',
        'wkt_masuk'       => 'required',
        'wkt_keluar'      => 'required',
        'lokasi'          => 'permit_empty|max_length[200]',
        'jml'             => 'permit_empty|integer|greater_than_equal_to[0]',
        'latitude'        => 'permit_empty|decimal',
        'longitude'       => 'permit_empty|decimal',
        'keterangan'      => 'permit_empty',
        'status'          => 'required|in_list[0,1]',
        'status_hps'      => 'required|in_list[0,1]'
    ];

    protected $validationMessages = [
        'id_user' => [
            'required' => 'ID User harus diisi',
            'integer' => 'ID User harus berupa angka'
        ],
        'id_kategori' => [
            'required' => 'ID Kategori harus diisi',
            'integer' => 'ID Kategori harus berupa angka'
        ],
        'event' => [
            'required'   => 'Nama event harus diisi',
            'max_length' => 'Nama event maksimal 100 karakter'
        ],
        'foto' => [
            'max_length' => 'Foto maksimal 160 karakter'
        ],
        'tgl_masuk' => [
            'required'   => 'Tanggal mulai harus diisi',
            'valid_date' => 'Format tanggal mulai tidak valid'
        ],
        'tgl_keluar' => [
            'required'   => 'Tanggal selesai harus diisi',
            'valid_date' => 'Format tanggal selesai tidak valid'
        ],
        'wkt_masuk' => [
            'required' => 'Waktu mulai harus diisi'
        ],
        'wkt_keluar' => [
            'required' => 'Waktu selesai harus diisi'
        ],
        'lokasi' => [
            'max_length' => 'Lokasi maksimal 200 karakter'
        ],
        'jml' => [
            'integer'              => 'Kapasitas harus berupa angka',
            'greater_than_equal_to' => 'Kapasitas harus lebih dari atau sama dengan 0'
        ],
        'latitude' => [
            'decimal' => 'Latitude harus berupa angka desimal'
        ],
        'longitude' => [
            'decimal' => 'Longitude harus berupa angka desimal'
        ],
        'status' => [
            'required' => 'Status harus dipilih',
            'in_list'  => 'Status harus 0 (tidak aktif) atau 1 (aktif)'
        ],
        'status_hps' => [
            'required' => 'Status hapus harus dipilih',
            'in_list'  => 'Status hapus harus 0 (aktif) atau 1 (terhapus)'
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
     * Get active events
     * 
     * @return array
     */
    public function getActiveEvents()
    {
        return $this->where('status', '1')
                    ->where('tgl_masuk >=', date('Y-m-d'))
                    ->orderBy('tgl_masuk', 'ASC')
                    ->findAll();
    }

    /**
     * Get events by user
     * 
     * @param int $userId
     * @return array
     */
    public function getEventsByUser($userId)
    {
        return $this->where('id_user', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get upcoming events
     * 
     * @param int $limit
     * @return array
     */
    public function getUpcomingEvents($limit = 5)
    {
        return $this->where('status', 1)
                    ->where('tgl_masuk >=', date('Y-m-d'))
                    ->orderBy('tgl_masuk', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get events by date range
     * 
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getEventsByDateRange($startDate, $endDate)
    {
        return $this->where('status', 1)
                    ->where('tgl_masuk >=', $startDate)
                    ->where('tgl_keluar <=', $endDate)
                    ->orderBy('tgl_masuk', 'ASC')
                    ->findAll();
    }

    /**
     * Get events by location
     * 
     * @param string $location
     * @return array
     */
    public function getEventsByLocation($location)
    {
        return $this->where('status', 1)
                    ->like('lokasi', $location)
                    ->orderBy('tgl_masuk', 'ASC')
                    ->findAll();
    }

    /**
     * Get events with available capacity
     * 
     * @return array
     */
    public function getEventsWithAvailableCapacity()
    {
        return $this->select('tbl_m_event.*, 
                             COALESCE(COUNT(tbl_peserta.id), 0) as registered_participants,
                             (tbl_m_event.jml - COALESCE(COUNT(tbl_peserta.id), 0)) as available_capacity')
                    ->join('tbl_peserta', 'tbl_peserta.id_kategori = tbl_m_event.id', 'left')
                    ->where('tbl_m_event.status', 1)
                    ->where('tbl_m_event.jml >', 0)
                    ->groupBy('tbl_m_event.id')
                    ->having('available_capacity >', 0)
                    ->orderBy('tbl_m_event.tgl_masuk', 'ASC')
                    ->findAll();
    }

    /**
     * Get event statistics
     * 
     * @return array
     */
    public function getEventStatistics()
    {
        $totalEvents = $this->countAllResults();
        $activeEvents = $this->where('status', '1')->countAllResults();
        $upcomingEvents = $this->where('status', '1')
                               ->where('tgl_masuk >=', date('Y-m-d'))
                               ->countAllResults();
        $pastEvents = $this->where('status', 1)
                           ->where('tgl_masuk <', date('Y-m-d'))
                           ->countAllResults();

        return [
            'total_events'     => $totalEvents,
            'active_events'    => $activeEvents,
            'upcoming_events'  => $upcomingEvents,
            'past_events'      => $pastEvents
        ];
    }

    /**
     * Get events for frontend display
     * 
     * @return array
     */
    public function getFrontendEvents()
    {
        return $this->select('tbl_m_event.*, 
                             COALESCE(COUNT(tbl_peserta.id), 0) as registered_participants')
                    ->join('tbl_peserta', 'tbl_peserta.id_kategori = tbl_m_event.id', 'left')
                    ->where('tbl_m_event.status', 1)
                    ->where('tbl_m_event.tgl_masuk >=', date('Y-m-d'))
                    ->groupBy('tbl_m_event.id')
                    ->orderBy('tbl_m_event.tgl_masuk', 'ASC')
                    ->findAll();
    }

    /**
     * Check if event has available capacity
     * 
     * @param int $eventId
     * @return bool
     */
    public function hasAvailableCapacity($eventId)
    {
        $event = $this->find($eventId);
        if (!$event || $event->jml == 0) {
            return true; // No capacity limit
        }

        $registeredCount = $this->db->table('tbl_peserta')
                                   ->where('id_kategori', $eventId)
                                   ->countAllResults();

        return $registeredCount < $event->jml;
    }

    /**
     * Get event capacity info
     * 
     * @param int $eventId
     * @return array
     */
    public function getEventCapacityInfo($eventId)
    {
        $event = $this->find($eventId);
        if (!$event) {
            return null;
        }

        $registeredCount = $this->db->table('tbl_peserta')
                                   ->where('id_kategori', $eventId)
                                   ->countAllResults();

        return [
            'event_id'            => $eventId,
            'event_name'          => $event->event,
            'total_capacity'      => $event->jml,
            'registered_count'    => $registeredCount,
            'available_capacity'  => $event->jml > 0 ? $event->jml - $registeredCount : 'Unlimited',
            'is_full'            => $event->jml > 0 ? $registeredCount >= $event->jml : false
        ];
    }

    /**
     * Search events
     * 
     * @param string $keyword
     * @return array
     */
    public function searchEvents($keyword)
    {
        return $this->where('status', 1)
                    ->groupStart()
                        ->like('event', $keyword)
                        ->orLike('lokasi', $keyword)
                        ->orLike('keterangan', $keyword)
                    ->groupEnd()
                    ->orderBy('tgl_masuk', 'ASC')
                    ->findAll();
    }

    /**
     * Get events with pagination and search
     * 
     * @param int $page
     * @param int $perPage
     * @param string $keyword
     * @return array
     */
    public function getEventsWithPagination($page = 1, $perPage = 10, $keyword = '')
    {
        $offset = ($page - 1) * $perPage;
        
        $builder = $this->select('tbl_m_event.*, tbl_m_kategori.kategori as nama_kategori')
                        ->join('tbl_m_kategori', 'tbl_m_kategori.id = tbl_m_event.id_kategori', 'left')
                        ->where('tbl_m_event.status_hps', 0);
        
        if (!empty($keyword)) {
            $builder->groupStart()
                        ->like('tbl_m_event.event', $keyword)
                        ->orLike('tbl_m_event.lokasi', $keyword)
                        ->orLike('tbl_m_event.keterangan', $keyword)
                        ->orLike('tbl_m_kategori.kategori', $keyword)
                    ->groupEnd();
        }
        
        return $builder->orderBy('tbl_m_event.created_at', 'DESC')
                      ->limit($perPage, $offset)
                      ->findAll();
    }

    /**
     * Get total events count for pagination
     * 
     * @param string $keyword
     * @return int
     */
    public function getTotalEvents($keyword = '')
    {
        $builder = $this->select('tbl_m_event.id')
                        ->join('tbl_m_kategori', 'tbl_m_kategori.id = tbl_m_event.id_kategori', 'left')
                        ->where('tbl_m_event.status_hps', 0);
        
        if (!empty($keyword)) {
            $builder->groupStart()
                        ->like('tbl_m_event.event', $keyword)
                        ->orLike('tbl_m_event.lokasi', $keyword)
                        ->orLike('tbl_m_event.keterangan', $keyword)
                        ->orLike('tbl_m_kategori.kategori', $keyword)
                    ->groupEnd();
        }
        
        return $builder->countAllResults();
    }
}
