<?php

/**
 * RacepackModel
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: August 7, 2025
 * Github: github.com/mikhaelfelian
 * Description: Model for managing racepack data
 */

namespace App\Models;

use CodeIgniter\Model;

class RacepackModel extends Model
{
    protected $table      = 'tbl_racepack';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'kode_racepack',
        'nama_racepack',
        'id_kategori',
        'deskripsi',
        'harga',
        'gambar',
        'status',
        'id_user'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'kode_racepack' => 'required|max_length[50]',
        'nama_racepack' => 'required|max_length[200]',
        'id_kategori'   => 'required|integer',
        'deskripsi'     => 'permit_empty',
        'harga'         => 'required|decimal',
        'gambar'        => 'permit_empty|max_length[255]',
        'status'        => 'required|in_list[0,1]',
        'id_user'       => 'required|integer'
    ];

    protected $validationMessages = [
        'kode_racepack' => [
            'required'   => 'Kode racepack harus diisi',
            'max_length' => 'Kode racepack maksimal 50 karakter'
        ],
        'nama_racepack' => [
            'required'   => 'Nama racepack harus diisi',
            'max_length' => 'Nama racepack maksimal 200 karakter'
        ],
        'id_kategori' => [
            'required' => 'Kategori harus dipilih',
            'integer'  => 'ID Kategori harus berupa angka'
        ],
        'harga' => [
            'required' => 'Harga harus diisi',
            'decimal'  => 'Harga harus berupa angka'
        ],
        'gambar' => [
            'max_length' => 'Path gambar maksimal 255 karakter'
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
     * Get racepack with category info
     * 
     * @return array
     */
    public function getRacepackWithCategory()
    {
        return $this->select('tbl_racepack.*, tbl_kategori_racepack.nama_kategori')
                   ->join('tbl_kategori_racepack', 'tbl_kategori_racepack.id = tbl_racepack.id_kategori', 'left')
                   ->orderBy('tbl_racepack.nama_racepack', 'ASC');
    }

    /**
     * Get active racepack
     * 
     * @return array
     */
    public function getActiveRacepack()
    {
        return $this->where('status', '1')
                   ->orderBy('nama_racepack', 'ASC')
                   ->findAll();
    }

    /**
     * Get racepack by code
     * 
     * @param string $kode
     * @return object|null
     */
    public function getRacepackByCode($kode)
    {
        return $this->where('kode_racepack', $kode)->first();
    }

    /**
     * Check if racepack code exists
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

        $builder = $this->where('kode_racepack', $kode);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Get racepack statistics
     * 
     * @return object
     */
    public function getRacepackStats()
    {
        $total = $this->countAll();
        $active = $this->where('status', '1')->countAllResults();
        $inactive = $this->where('status', '0')->countAllResults();

        return (object) [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive
        ];
    }

    /**
     * Search racepack
     * 
     * @param string $keyword
     * @return array
     */
    public function searchRacepack($keyword)
    {
        return $this->groupStart()
                   ->like('nama_racepack', $keyword)
                   ->orLike('kode_racepack', $keyword)
                   ->orLike('deskripsi', $keyword)
                   ->groupEnd()
                   ->orderBy('nama_racepack', 'ASC');
    }

    /**
     * Get racepack dropdown options
     * 
     * @return array
     */
    public function getDropdownOptions()
    {
        $racepack = $this->getActiveRacepack();
        $options = [];
        
        foreach ($racepack as $rp) {
            $label = "({$rp->kode_racepack}) {$rp->nama_racepack}";
            $options[$rp->id] = $label;
        }
        
        return $options;
    }
} 