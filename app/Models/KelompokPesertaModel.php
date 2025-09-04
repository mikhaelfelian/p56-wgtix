<?php

/**
 * KelompokPesertaModel
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Model for managing kelompok peserta (participant groups) data
 */

namespace App\Models;

use CodeIgniter\Model;

class KelompokPesertaModel extends Model
{
    protected $table      = 'tbl_kelompok_peserta';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'id_user',
        'kode_kelompok',
        'nama_kelompok',
        'deskripsi',
        'kapasitas',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'nama_kelompok' => 'required|max_length[100]',
        'kode_kelompok' => 'required|max_length[20]',
        'deskripsi'     => 'permit_empty',
        'kapasitas'     => 'permit_empty|integer',
        'status'        => 'required|in_list[0,1]',
        'id_user'       => 'required|integer'
    ];

    protected $validationMessages = [
        'nama_kelompok' => [
            'required'   => 'Nama kelompok harus diisi',
            'max_length' => 'Nama kelompok maksimal 100 karakter'
        ],
        'kode_kelompok' => [
            'required'   => 'Kode kelompok harus diisi',
            'max_length' => 'Kode kelompok maksimal 20 karakter'
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
     * Get active groups
     * 
     * @return array
     */
    public function getActiveKelompok()
    {
        return $this->where('status', '1')
                   ->orderBy('nama_kelompok', 'ASC')
                   ->findAll();
    }

    /**
     * Get group by code
     * 
     * @param string $kode
     * @return object|null
     */
    public function getKelompokByCode($kode)
    {
        return $this->where('kode_kelompok', $kode)->first();
    }

    /**
     * Get groups with member count
     * 
     * @return array
     */
    public function getKelompokWithMemberCount()
    {
        return $this->select('tbl_kelompok_peserta.*, COUNT(tbl_peserta.id) as jumlah_anggota')
                   ->join('tbl_peserta', 'tbl_peserta.id_kelompok = tbl_kelompok_peserta.id', 'left')
                   ->groupBy('tbl_kelompok_peserta.id')
                   ->orderBy('tbl_kelompok_peserta.nama_kelompok', 'ASC')
                   ->findAll();
    }

    /**
     * Get groups with member count (for pagination)
     * 
     * @return $this
     */
    public function getKelompokWithMemberCountQuery()
    {
        return $this->select('tbl_kelompok_peserta.*, COUNT(tbl_peserta.id) as jumlah_anggota')
                   ->join('tbl_peserta', 'tbl_peserta.id_kelompok = tbl_kelompok_peserta.id', 'left')
                   ->groupBy('tbl_kelompok_peserta.id')
                   ->orderBy('tbl_kelompok_peserta.nama_kelompok', 'ASC');
    }

    /**
     * Check if group code exists (for validation)
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

        $builder = $this->where('kode_kelompok', $kode);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Get group statistics
     * 
     * @return object
     */
    public function getKelompokStats()
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
     * Search groups
     * 
     * @param string $keyword
     * @return array
     */
    public function searchKelompok($keyword)
    {
        return $this->groupStart()
                   ->like('nama_kelompok', $keyword)
                   ->orLike('kode_kelompok', $keyword)
                   ->orLike('deskripsi', $keyword)
                   ->groupEnd()
                   ->orderBy('nama_kelompok', 'ASC')
                   ->findAll();
    }

    /**
     * Search groups (for pagination)
     * 
     * @param string $keyword
     * @return $this
     */
    public function searchKelompokQuery($keyword)
    {
        return $this->select('tbl_kelompok_peserta.*, COUNT(tbl_peserta.id) as jumlah_anggota')
                   ->join('tbl_peserta', 'tbl_peserta.id_kelompok = tbl_kelompok_peserta.id', 'left')
                   ->groupStart()
                   ->like('tbl_kelompok_peserta.nama_kelompok', $keyword)
                   ->orLike('tbl_kelompok_peserta.kode_kelompok', $keyword)
                   ->orLike('tbl_kelompok_peserta.deskripsi', $keyword)
                   ->groupEnd()
                   ->groupBy('tbl_kelompok_peserta.id')
                   ->orderBy('tbl_kelompok_peserta.nama_kelompok', 'ASC');
    }

    /**
     * Get group dropdown options
     * 
     * @return array
     */
    public function getDropdownOptions()
    {
        $kelompok = $this->getActiveKelompok();
        $options = [];
        
        foreach ($kelompok as $k) {
            $label = "({$k->kode_kelompok}) {$k->nama_kelompok}";
            $options[$k->id] = $label;
        }
        
        return $options;
    }
} 