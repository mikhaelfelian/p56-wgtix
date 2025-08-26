<?php

/**
 * JadwalModel
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Model for managing jadwal (schedule) master data
 * This file represents the Model for jadwal master data operations.
 */

namespace App\Models;

use CodeIgniter\Model;

class JadwalModel extends Model
{
    protected $table      = 'tbl_m_jadwal';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'id_user',
        'kode', 
        'nama_jadwal',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'kapasitas',
        'keterangan',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'nama_jadwal'    => 'required|max_length[100]',
        'kode'           => 'permit_empty|max_length[20]',
        'tanggal_mulai'  => 'required|valid_date',
        'tanggal_selesai'=> 'required|valid_date',
        'waktu_mulai'    => 'required',
        'waktu_selesai'  => 'required',
        'lokasi'         => 'permit_empty|max_length[200]',
        'kapasitas'      => 'permit_empty|integer',
        'status'         => 'required|in_list[0,1]',
        'id_user'        => 'required|integer'
    ];

    protected $validationMessages = [
        'nama_jadwal' => [
            'required'   => 'Nama jadwal harus diisi',
            'max_length' => 'Nama jadwal maksimal 100 karakter'
        ],
        'kode' => [
            'max_length' => 'Kode jadwal maksimal 20 karakter'
        ],
        'tanggal_mulai' => [
            'required'   => 'Tanggal mulai harus diisi',
            'valid_date' => 'Format tanggal mulai tidak valid'
        ],
        'tanggal_selesai' => [
            'required'   => 'Tanggal selesai harus diisi',
            'valid_date' => 'Format tanggal selesai tidak valid'
        ],
        'waktu_mulai' => [
            'required' => 'Waktu mulai harus diisi'
        ],
        'waktu_selesai' => [
            'required' => 'Waktu selesai harus diisi'
        ],
        'lokasi' => [
            'max_length' => 'Lokasi maksimal 200 karakter'
        ],
        'kapasitas' => [
            'integer' => 'Kapasitas harus berupa angka'
        ],
        'status' => [
            'required' => 'Status harus dipilih',
            'in_list'  => 'Status harus 0 (tidak aktif) atau 1 (aktif)'
        ],
        'id_user' => [
            'required' => 'ID User harus diisi',
            'integer'  => 'ID User harus berupa angka'
        ]
    ];

    // Custom methods
    
    /**
     * Get active schedules
     * 
     * @return array
     */
    public function getActiveSchedules()
    {
        return $this->where('status', '1')
                   ->orderBy('tanggal_mulai', 'ASC')
                   ->orderBy('waktu_mulai', 'ASC')
                   ->findAll();
    }

    /**
     * Get schedule by code
     * 
     * @param string $kode
     * @return object|null
     */
    public function getScheduleByCode($kode)
    {
        return $this->where('kode', $kode)->first();
    }

    /**
     * Get schedules with user info
     * 
     * @return array
     */
    public function getSchedulesWithUser()
    {
        return $this->select('tbl_m_jadwal.*, users.username')
                   ->join('users', 'users.id = tbl_m_jadwal.id_user', 'left')
                   ->orderBy('tbl_m_jadwal.tanggal_mulai', 'DESC')
                   ->orderBy('tbl_m_jadwal.waktu_mulai', 'ASC')
                   ->findAll();
    }

    /**
     * Get upcoming schedules
     * 
     * @param int $limit
     * @return array
     */
    public function getUpcomingSchedules($limit = 10)
    {
        $today = date('Y-m-d');
        return $this->where('status', '1')
                   ->where('tanggal_mulai >=', $today)
                   ->orderBy('tanggal_mulai', 'ASC')
                   ->orderBy('waktu_mulai', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get schedules by date range
     * 
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getSchedulesByDateRange($startDate, $endDate)
    {
        return $this->where('status', '1')
                   ->where('tanggal_mulai >=', $startDate)
                   ->where('tanggal_selesai <=', $endDate)
                   ->orderBy('tanggal_mulai', 'ASC')
                   ->orderBy('waktu_mulai', 'ASC')
                   ->findAll();
    }

    /**
     * Toggle schedule status
     * 
     * @param int $id
     * @return bool
     */
    public function toggleStatus($id)
    {
        $schedule = $this->find($id);
        if (!$schedule) {
            return false;
        }

        $newStatus = ($schedule->status == '1') ? '0' : '1';
        return $this->update($id, ['status' => $newStatus]);
    }

    /**
     * Check if schedule code exists (for validation)
     * 
     * @param string $kode
     * @param int|null $excludeId
     * @return bool
     */
    public function isCodeExists($kode, $excludeId = null)
    {
        if (empty($kode)) {
            return false;
        }

        $builder = $this->where('kode', $kode);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Get schedule statistics
     * 
     * @return object
     */
    public function getScheduleStats()
    {
        $total = $this->countAll();
        $active = $this->where('status', '1')->countAllResults();
        $inactive = $this->where('status', '0')->countAllResults();
        $upcoming = $this->where('status', '1')
                        ->where('tanggal_mulai >=', date('Y-m-d'))
                        ->countAllResults();

        return (object) [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'upcoming' => $upcoming
        ];
    }

    /**
     * Search schedules
     * 
     * @param string $keyword
     * @return array
     */
    public function searchSchedules($keyword)
    {
        return $this->groupStart()
                   ->like('nama_jadwal', $keyword)
                   ->orLike('kode', $keyword)
                   ->orLike('lokasi', $keyword)
                   ->orLike('keterangan', $keyword)
                   ->groupEnd()
                   ->orderBy('tanggal_mulai', 'ASC')
                   ->orderBy('waktu_mulai', 'ASC')
                   ->findAll();
    }

    /**
     * Get schedule dropdown options
     * 
     * @return array
     */
    public function getDropdownOptions()
    {
        $schedules = $this->getActiveSchedules();
        $options = [];
        
        foreach ($schedules as $schedule) {
            $label = $schedule->nama_jadwal;
            if (!empty($schedule->kode)) {
                $label = "({$schedule->kode}) {$schedule->nama_jadwal}";
            }
            $label .= " - " . date('d/m/Y', strtotime($schedule->tanggal_mulai));
            $options[$schedule->id] = $label;
        }
        
        return $options;
    }
} 