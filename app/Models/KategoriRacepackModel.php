<?php

/**
 * KategoriRacepackModel
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: August 7, 2025
 * Github: github.com/mikhaelfelian
 * Description: Model for managing kategori racepack data
 */

namespace App\Models;

use CodeIgniter\Model;

class KategoriRacepackModel extends Model
{
    protected $table      = 'tbl_kategori_racepack';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'nama_kategori',
        'deskripsi',
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
        'nama_kategori' => 'required|max_length[100]',
        'deskripsi'     => 'permit_empty',
        'status'        => 'required|in_list[0,1]',
        'id_user'       => 'required|integer'
    ];

    protected $validationMessages = [
        'nama_kategori' => [
            'required'   => 'Nama kategori harus diisi',
            'max_length' => 'Nama kategori maksimal 100 karakter'
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
                   ->orderBy('nama_kategori', 'ASC')
                   ->findAll();
    }

    /**
     * Get category dropdown options
     * 
     * @return array
     */
    public function getDropdownOptions()
    {
        $categories = $this->getActiveCategories();
        $options = [];
        
        foreach ($categories as $category) {
            $options[$category->id] = $category->nama_kategori;
        }
        
        return $options;
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
                   ->like('nama_kategori', $keyword)
                   ->orLike('deskripsi', $keyword)
                   ->groupEnd()
                   ->orderBy('nama_kategori', 'ASC');
    }
} 