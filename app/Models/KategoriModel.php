<?php

/**
 * MKategoriModel
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Model for managing kategori (categories) master data
 * This file represents the Model for kategori master data operations.
 */

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table      = 'tbl_m_kategori';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'id_user',
        'kode', 
        'kategori',
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
        'kategori' => 'required|max_length[100]',
        'kode'     => 'permit_empty|max_length[20]',
        'status'   => 'required|in_list[0,1]',
        'id_user'  => 'required|integer'
    ];

    protected $validationMessages = [
        'kategori' => [
            'required'   => 'Nama kategori harus diisi',
            'max_length' => 'Nama kategori maksimal 100 karakter'
        ],
        'kode' => [
            'max_length' => 'Kode kategori maksimal 20 karakter'
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
     * Get active categories
     * 
     * @return array
     */
    public function getActiveCategories()
    {
        return $this->where('status', '1')
                   ->orderBy('kategori', 'ASC')
                   ->findAll();
    }

    /**
     * Get category by code
     * 
     * @param string $kode
     * @return object|null
     */
    public function getCategoryByCode($kode)
    {
        return $this->where('kode', $kode)->first();
    }

    /**
     * Get categories with user info
     * 
     * @return array
     */
    public function getCategoriesWithUser()
    {
        return $this->select('tbl_m_kategori.*, users.username')
                   ->join('users', 'users.id = tbl_m_kategori.id_user', 'left')
                   ->orderBy('tbl_m_kategori.created_at', 'DESC')
                   ->findAll();
    }

    /**
     * Toggle category status
     * 
     * @param int $id
     * @return bool
     */
    public function toggleStatus($id)
    {
        $category = $this->find($id);
        if (!$category) {
            return false;
        }

        $newStatus = ($category->status == '1') ? '0' : '1';
        return $this->update($id, ['status' => $newStatus]);
    }

    /**
     * Check if category code exists (for validation)
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
     * Get category statistics
     * 
     * @return object
     */
    public function getCategoryStats()
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
     * Search categories
     * 
     * @param string $keyword
     * @return array
     */
    public function searchCategories($keyword)
    {
        return $this->groupStart()
                   ->like('kategori', $keyword)
                   ->orLike('kode', $keyword)
                   ->orLike('keterangan', $keyword)
                   ->groupEnd()
                   ->orderBy('kategori', 'ASC')
                   ->findAll();
    }
}