<?php

/**
 * UkuranModel
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-08-06
 * Github: github.com/mikhaelfelian
 * Description: Model for managing ukuran (size/racepack) master data
 * This file represents the Model for ukuran master data operations.
 */

namespace App\Models;

use CodeIgniter\Model;

class UkuranModel extends Model
{
    protected $table      = 'tbl_m_ukuran';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'id_user',
        'kode', 
        'ukuran',
        'deskripsi',
        'keterangan',
        'harga',
        'stok',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'ukuran'   => 'required|max_length[50]',
        'kode'     => 'permit_empty|max_length[20]',
        'deskripsi'=> 'permit_empty|max_length[100]',
        'harga'    => 'permit_empty|decimal',
        'stok'     => 'permit_empty|integer',
        'status'   => 'required|in_list[0,1]',
        'id_user'  => 'required|integer'
    ];

    protected $validationMessages = [
        'ukuran' => [
            'required'   => 'Nama ukuran harus diisi',
            'max_length' => 'Nama ukuran maksimal 50 karakter'
        ],
        'kode' => [
            'max_length' => 'Kode ukuran maksimal 20 karakter'
        ],
        'deskripsi' => [
            'max_length' => 'Deskripsi maksimal 100 karakter'
        ],
        'harga' => [
            'decimal' => 'Harga harus berupa angka desimal'
        ],
        'stok' => [
            'integer' => 'Stok harus berupa angka'
        ],
        'status' => [
            'required' => 'Status harus dipilih',
            'in_list'  => 'Status harus 0 atau 1'
        ],
        'id_user' => [
            'required' => 'ID User harus diisi',
            'integer'  => 'ID User harus berupa angka'
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
     * Get active ukuran
     * 
     * @return array
     */
    public function getActive()
    {
        return $this->where('status', '1')->findAll();
    }

    /**
     * Get ukuran by status
     * 
     * @param string $status
     * @return array
     */
    public function getByStatus($status = '1')
    {
        return $this->where('status', $status)->findAll();
    }

    /**
     * Search ukuran
     * 
     * @param string $keyword
     * @return array
     */
    public function search($keyword)
    {
        return $this->groupStart()
                    ->like('ukuran', $keyword)
                    ->orLike('kode', $keyword)
                    ->orLike('deskripsi', $keyword)
                    ->groupEnd()
                    ->findAll();
    }

    /**
     * Get ukuran with stock > 0
     * 
     * @return array
     */
    public function getAvailable()
    {
        return $this->where('status', '1')
                    ->where('stok >', 0)
                    ->findAll();
    }

    /**
     * Update stock
     * 
     * @param int $id
     * @param int $quantity
     * @return bool
     */
    public function updateStock($id, $quantity)
    {
        $ukuran = $this->find($id);
        if (!$ukuran) {
            return false;
        }

        $newStock = $ukuran->stok - $quantity;
        if ($newStock < 0) {
            return false;
        }

        return $this->update($id, ['stok' => $newStock]);
    }

    /**
     * Get ukuran dropdown options
     * 
     * @return array
     */
    public function getDropdownOptions()
    {
        $ukuran = $this->getActive();
        $options = [];
        
        foreach ($ukuran as $item) {
            $label = $item->ukuran;
            if (!empty($item->kode)) {
                $label = "{$item->kode} ({$item->deskripsi})";
            }
            if ($item->harga > 0) {
                $label .= " (+Rp " . number_format($item->harga, 0, ',', '.') . ")";
            }
            $options[$item->kode] = $label;
        }
        
        return $options;
    }
}