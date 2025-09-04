<?php

/**
 * EventsHargaModel
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Model for managing event pricing data
 */

namespace App\Models;

use CodeIgniter\Model;

class EventsHargaModel extends Model
{
    protected $table      = 'tbl_m_event_harga';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'id_event',
        'keterangan',
        'harga',
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
        'harga'    => 'required|decimal',
        'status'   => 'required|in_list[0,1]'
    ];

    protected $validationMessages = [];

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
     * Get active pricing for an event
     * 
     * @param int $eventId
     * @return object|null
     */
    public function getActivePricing($eventId)
    {
        return $this->where('id_event', $eventId)
                    ->where('status', '1')
                    ->first();
    }

    /**
     * Get all pricing for an event from published event
     * 
     * @param int $eventId
     * @return array
     */
    public function getEventPricing($eventId)
    {
        return $this->where('id_event', $eventId)
                    ->where('status', '1')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get all pricing for an event from admin event
     * 
     * @param int $eventId
     * @return array
     */
    public function getEventPricings($eventId)
    {
        return $this->where('id_event', $eventId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get pricing with event details
     * 
     * @param int $eventId
     * @return object|null
     */
    public function getPricingWithEvent($eventId)
    {
        return $this->select('tbl_m_event_harga.*, 
                             tbl_m_event.event,
                             tbl_m_event.tgl_masuk,
                             tbl_m_event.lokasi')
                    ->join('tbl_m_event', 'tbl_m_event.id = tbl_m_event_harga.id_event', 'left')
                    ->where('tbl_m_event_harga.id_event', $eventId)
                    ->where('tbl_m_event_harga.status', '1')
                    ->first();
    }

    /**
     * Get all active pricing with event details
     * 
     * @return array
     */
    public function getAllActivePricingWithEvents()
    {
        return $this->select('tbl_m_event_harga.*, 
                             tbl_m_event.event as event_name,
                             tbl_m_event.tgl_masuk,
                             tbl_m_event.tgl_keluar,
                             tbl_m_event.lokasi,
                             tbl_m_event.jml,
                             tbl_m_kategori.kategori')
                    ->join('tbl_m_event', 'tbl_m_event.id = tbl_m_event_harga.id_event', 'left')
                    ->join('tbl_m_kategori', 'tbl_m_kategori.id = tbl_m_event.id_kategori', 'left')
                    ->where('tbl_m_event_harga.status', '1')
                    ->where('tbl_m_event.status', 1)
                    ->orderBy('tbl_m_event.tgl_masuk', 'ASC')
                    ->findAll();
    }

    /**
     * Get pricing by price range
     * 
     * @param float $minPrice
     * @param float $maxPrice
     * @return array
     */
    public function getPricingByRange($minPrice, $maxPrice)
    {
        return $this->select('tbl_m_event_harga.*, 
                             tbl_m_event.event as event_name,
                             tbl_m_event.tgl_masuk,
                             tbl_m_event.lokasi')
                    ->join('tbl_m_event', 'tbl_m_event.id = tbl_m_event_harga.id_event', 'left')
                    ->where('tbl_m_event_harga.status', '1')
                    ->where('tbl_m_event.status', 1)
                    ->where('tbl_m_event_harga.harga >=', $minPrice)
                    ->where('tbl_m_event_harga.harga <=', $maxPrice)
                    ->orderBy('tbl_m_event_harga.harga', 'ASC')
                    ->findAll();
    }

    /**
     * Get pricing statistics
     * 
     * @return array
     */
    public function getPricingStatistics()
    {
        $totalPricing = $this->countAllResults();
        $activePricing = $this->where('status', '1')->countAllResults();
        $inactivePricing = $this->where('status', '0')->countAllResults();

        // Get total events count
        $totalEvents = $this->db->table('tbl_m_event')
                                ->where('status', 1)
                                ->countAllResults();

        // Get price range statistics
        $priceStats = $this->select('MIN(harga) as min_price, MAX(harga) as max_price, AVG(harga) as avg_price')
                           ->where('status', '1')
                           ->first();

        return [
            'total_pricing'    => $totalPricing,
            'active_pricing'   => $activePricing,
            'inactive_pricing' => $inactivePricing,
            'total_events'     => $totalEvents,
            'average_price'    => $priceStats ? round($priceStats->avg_price, 2) : 0,
            'price_range'      => [
                'min_price' => $priceStats ? $priceStats->min_price : 0,
                'max_price' => $priceStats ? $priceStats->max_price : 0,
                'avg_price' => $priceStats ? round($priceStats->avg_price, 2) : 0
            ]
        ];
    }

    /**
     * Check if event has active pricing
     * 
     * @param int $eventId
     * @return bool
     */
    public function hasActivePricing($eventId)
    {
        return $this->where('id_event', $eventId)
                    ->where('status', '1')
                    ->countAllResults() > 0;
    }

    /**
     * Get all pricing for an event (alias for getEventPricing)
     * 
     * @param int $eventId
     * @return array
     */
    public function getPricesByEvent($eventId)
    {
        return $this->getEventPricing($eventId);
    }

    /**
     * Get pricing with search functionality
     * 
     * @param string $keyword
     * @return array
     */
    public function getPricingWithSearch($keyword = '')
    {
        $builder = $this->select('tbl_m_event_harga.*, 
                                 tbl_m_event.event as event_name,
                                 tbl_m_event.tgl_masuk,
                                 tbl_m_event.tgl_keluar,
                                 tbl_m_event.lokasi,
                                 tbl_m_event.jml,
                                 tbl_m_kategori.kategori')
                        ->join('tbl_m_event', 'tbl_m_event.id = tbl_m_event_harga.id_event', 'left')
                        ->join('tbl_m_kategori', 'tbl_m_kategori.id = tbl_m_event.id_kategori', 'left')
                        ->where('tbl_m_event_harga.status', '1')
                        ->where('tbl_m_event.status', '1');
        
        if (!empty($keyword)) {
            $builder->groupStart()
                        ->like('tbl_m_event.event', $keyword)
                        ->orLike('tbl_m_kategori.kategori', $keyword)
                    ->groupEnd();
        }
        
        return $builder->orderBy('tbl_m_event.tgl_masuk', 'ASC')
                      ->findAll();
    }

    /**
     * Get pricing history for an event
     * 
     * @param int $eventId
     * @return array
     */
    public function getPricingHistory($eventId)
    {
        return $this->where('id_event', $eventId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Deactivate all pricing for an event
     * 
     * @param int $eventId
     * @return bool
     */
    public function deactivateEventPricing($eventId)
    {
        return $this->where('id_event', $eventId)
                    ->set(['status' => '0'])
                    ->update();
    }

    /**
     * Get pricing for frontend display
     * 
     * @param int $eventId
     * @return object|null
     */
    public function getFrontendPricing($eventId)
    {
        return $this->select('tbl_m_event_harga.harga, 
                             tbl_m_event_harga.status,
                             tbl_m_event.event as event_name,
                             tbl_m_event.tgl_masuk,
                             tbl_m_event.lokasi,
                             tbl_m_event.jml')
                    ->join('tbl_m_event', 'tbl_m_event.id = tbl_m_event_harga.id_event', 'left')
                    ->where('tbl_m_event_harga.id_event', $eventId)
                    ->where('tbl_m_event_harga.status', '1')
                    ->where('tbl_m_event.status', 1)
                    ->first();
    }

    /**
     * Get pricing by multiple events
     * 
     * @param array $eventIds
     * @return array
     */
    public function getPricingByEvents($eventIds)
    {
        if (empty($eventIds)) {
            return [];
        }

        return $this->select('tbl_m_event_harga.*, 
                             tbl_m_event.event as event_name,
                             tbl_m_event.tgl_masuk,
                             tbl_m_event.lokasi')
                    ->join('tbl_m_event', 'tbl_m_event.id = tbl_m_event_harga.id_event', 'left')
                    ->whereIn('tbl_m_event_harga.id_event', $eventIds)
                    ->where('tbl_m_event_harga.status', '1')
                    ->where('tbl_m_event.status', 1)
                    ->orderBy('tbl_m_event.tgl_masuk', 'ASC')
                    ->findAll();
    }

    /**
     * Search pricing by event name or location
     * 
     * @param string $keyword
     * @return array
     */
    public function searchPricing($keyword)
    {
        return $this->select('tbl_m_event_harga.*, 
                             tbl_m_event.event as event_name,
                             tbl_m_event.tgl_masuk,
                             tbl_m_event.lokasi')
                    ->join('tbl_m_event', 'tbl_m_event.id = tbl_m_event_harga.id_event', 'left')
                    ->where('tbl_m_event_harga.status', '1')
                    ->where('tbl_m_event.status', 1)
                    ->groupStart()
                        ->like('tbl_m_event.event', $keyword)
                        ->orLike('tbl_m_event.lokasi', $keyword)
                    ->groupEnd()
                    ->orderBy('tbl_m_event.tgl_masuk', 'ASC')
                    ->findAll();
    }

    /**
     * Get pricing with capacity info
     * 
     * @param int $eventId
     * @return object|null
     */
    public function getPricingWithCapacity($eventId)
    {
        return $this->select('tbl_m_event_harga.*, 
                             tbl_m_event.event as event_name,
                             tbl_m_event.tgl_masuk,
                             tbl_m_event.lokasi,
                             tbl_m_event.jml,
                             COALESCE(COUNT(tbl_peserta.id), 0) as registered_participants,
                             (tbl_m_event.jml - COALESCE(COUNT(tbl_peserta.id), 0)) as available_capacity')
                    ->join('tbl_m_event', 'tbl_m_event.id = tbl_m_event_harga.id_event', 'left')
                    ->join('tbl_peserta', 'tbl_peserta.id_kategori = tbl_m_event.id', 'left')
                    ->where('tbl_m_event_harga.id_event', $eventId)
                    ->where('tbl_m_event_harga.status', '1')
                    ->where('tbl_m_event.status', 1)
                    ->groupBy('tbl_m_event.id')
                    ->first();
    }
}
