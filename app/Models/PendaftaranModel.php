<?php

/**
 * PendaftaranModel
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Model for managing pendaftaran (registration) data
 */

namespace App\Models;

use CodeIgniter\Model;

class PendaftaranModel extends Model
{
    protected $table      = 'tbl_pendaftaran';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'id_user',
        'kode_pendaftaran',
        'id_peserta',
        'id_jadwal',
        'tanggal_pendaftaran',
        'status_pendaftaran',
        'catatan',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'kode_pendaftaran'    => 'required|max_length[20]',
        'id_peserta'          => 'required|integer',
        'id_jadwal'           => 'required|integer',
        'tanggal_pendaftaran' => 'required|valid_date',
        'status_pendaftaran'  => 'required|in_list[pending,approved,rejected,cancelled]',
        'catatan'             => 'permit_empty',
        'status'              => 'required|in_list[0,1]',
        'id_user'             => 'required|integer'
    ];

    protected $validationMessages = [
        'kode_pendaftaran' => [
            'required'   => 'Kode pendaftaran harus diisi',
            'max_length' => 'Kode pendaftaran maksimal 20 karakter'
        ],
        'id_peserta' => [
            'required' => 'ID Peserta harus diisi',
            'integer'  => 'ID Peserta harus berupa angka'
        ],
        'id_jadwal' => [
            'required' => 'ID Jadwal harus diisi',
            'integer'  => 'ID Jadwal harus berupa angka'
        ],
        'tanggal_pendaftaran' => [
            'required'   => 'Tanggal pendaftaran harus diisi',
            'valid_date' => 'Format tanggal pendaftaran tidak valid'
        ],
        'status_pendaftaran' => [
            'required' => 'Status pendaftaran harus dipilih',
            'in_list'  => 'Status pendaftaran tidak valid'
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
     * Get active registrations
     * 
     * @return array
     */
    public function getActivePendaftaran()
    {
        return $this->where('status', '1')
                   ->orderBy('tanggal_pendaftaran', 'DESC')
                   ->findAll();
    }

    /**
     * Get registration by code
     * 
     * @param string $kode
     * @return object|null
     */
    public function getPendaftaranByCode($kode)
    {
        return $this->where('kode_pendaftaran', $kode)->first();
    }

    /**
     * Get registrations with participant and schedule info
     * 
     * @return array
     */
    public function getPendaftaranWithDetails()
    {
        return $this->select('tbl_pendaftaran.*, tbl_peserta.nama_lengkap as nama_peserta, tbl_peserta.kode_peserta, tbl_m_jadwal.nama_jadwal, tbl_m_jadwal.tanggal_mulai, tbl_m_jadwal.tanggal_selesai')
                   ->join('tbl_peserta', 'tbl_peserta.id = tbl_pendaftaran.id_peserta', 'left')
                   ->join('tbl_m_jadwal', 'tbl_m_jadwal.id = tbl_pendaftaran.id_jadwal', 'left')
                   ->orderBy('tbl_pendaftaran.tanggal_pendaftaran', 'DESC')
                   ->findAll();
    }

    /**
     * Get registrations by status
     * 
     * @param string $status
     * @return array
     */
    public function getPendaftaranByStatus($status)
    {
        return $this->where('status_pendaftaran', $status)
                   ->where('status', '1')
                   ->orderBy('tanggal_pendaftaran', 'DESC')
                   ->findAll();
    }

    /**
     * Get registrations by participant
     * 
     * @param int $idPeserta
     * @return array
     */
    public function getPendaftaranByPeserta($idPeserta)
    {
        return $this->where('id_peserta', $idPeserta)
                   ->where('status', '1')
                   ->orderBy('tanggal_pendaftaran', 'DESC')
                   ->findAll();
    }

    /**
     * Get registrations by schedule
     * 
     * @param int $idJadwal
     * @return array
     */
    public function getPendaftaranByJadwal($idJadwal)
    {
        return $this->where('id_jadwal', $idJadwal)
                   ->where('status', '1')
                   ->orderBy('tanggal_pendaftaran', 'ASC')
                   ->findAll();
    }

    /**
     * Check if registration code exists (for validation)
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

        $builder = $this->where('kode_pendaftaran', $kode);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Check if participant already registered for schedule
     * 
     * @param int $idPeserta
     * @param int $idJadwal
     * @param int|null $excludeId
     * @return bool
     */
    public function isPesertaRegisteredForJadwal($idPeserta, $idJadwal, $excludeId = null)
    {
        $builder = $this->where('id_peserta', $idPeserta)
                       ->where('id_jadwal', $idJadwal)
                       ->where('status', '1');
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Get registration statistics
     * 
     * @return object
     */
    public function getPendaftaranStats()
    {
        $total = $this->countAll();
        $active = $this->where('status', '1')->countAllResults();
        $pending = $this->where('status_pendaftaran', 'pending')->where('status', '1')->countAllResults();
        $approved = $this->where('status_pendaftaran', 'approved')->where('status', '1')->countAllResults();
        $rejected = $this->where('status_pendaftaran', 'rejected')->where('status', '1')->countAllResults();
        $cancelled = $this->where('status_pendaftaran', 'cancelled')->where('status', '1')->countAllResults();

        return (object) [
            'total' => $total,
            'active' => $active,
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'cancelled' => $cancelled
        ];
    }

    /**
     * Search registrations
     * 
     * @param string $keyword
     * @return array
     */
    public function searchPendaftaran($keyword)
    {
        return $this->groupStart()
                   ->like('kode_pendaftaran', $keyword)
                   ->orLike('catatan', $keyword)
                   ->groupEnd()
                   ->orderBy('tanggal_pendaftaran', 'DESC')
                   ->findAll();
    }
} 