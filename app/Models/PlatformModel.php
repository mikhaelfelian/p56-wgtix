<?php

/**
 * MPlatformModel
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-08-06
 * Github: github.com/mikhaelfelian
 * Description: Model for managing platform master data including payment gateways and platforms
 * This file represents the Model for platform master data operations.
 */

namespace App\Models;

use CodeIgniter\Model;

class PlatformModel extends Model
{
    protected $table      = 'tbl_m_platform';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'id_user',
        'id_kategori',
        'nama',
        'jenis',
        'kategori',
        'nama_rekening',
        'nomor_rekening',
        'deskripsi',
        'gateway_kode',
        'gateway_instruksi',
        'logo',
        'hasil',
        'status',
        'status_gateway',
        'status_system'
    ];

    // Dates - Note: This table doesn't have timestamp fields based on the schema provided
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        // 'id_user'           => 'required|integer',
        // 'id_kategori'       => 'required|integer',
        // 'nama'              => 'required|max_length[100]',
        // 'jenis'             => 'required|max_length[100]',
        // 'kategori'          => 'required|max_length[100]',
        // 'nama_rekening'     => 'required|max_length[111]',
        // 'nomor_rekening'    => 'required|max_length[111]',
        // 'deskripsi'         => 'required',
        // 'gateway_kode'      => 'permit_empty|max_length[111]',
        // 'gateway_instruksi' => 'permit_empty|in_list[0,1]',
        // 'logo'              => 'required|max_length[111]',
        // 'hasil'             => 'required|max_length[255]',
        // 'status'            => 'required|integer',
        // 'status_gateway'    => 'required|in_list[0,1]'
    ];

    protected $validationMessages = [
        'id_user' => [
            'required' => 'ID User harus diisi',
            'integer'  => 'ID User harus berupa angka'
        ],
        'id_kategori' => [
            'required' => 'ID Kategori harus diisi',
            'integer'  => 'ID Kategori harus berupa angka'
        ],
        'nama' => [
            'required'   => 'Nama platform harus diisi',
            'max_length' => 'Nama platform maksimal 100 karakter'
        ],
        'jenis' => [
            'required'   => 'Jenis platform harus diisi',
            'max_length' => 'Jenis platform maksimal 100 karakter'
        ],
        'kategori' => [
            'required'   => 'Kategori harus diisi',
            'max_length' => 'Kategori maksimal 100 karakter'
        ],
        'nama_rekening' => [
            'required'   => 'Nama rekening harus diisi',
            'max_length' => 'Nama rekening maksimal 111 karakter'
        ],
        'nomor_rekening' => [
            'required'   => 'Nomor rekening harus diisi',
            'max_length' => 'Nomor rekening maksimal 111 karakter'
        ],
        'deskripsi' => [
            'required' => 'Deskripsi harus diisi'
        ],
        'gateway_kode' => [
            'max_length' => 'Gateway kode maksimal 111 karakter'
        ],
        'gateway_instruksi' => [
            'in_list' => 'Gateway instruksi harus 0 atau 1'
        ],
        'logo' => [
            'required'   => 'Logo harus diisi',
            'max_length' => 'Logo maksimal 111 karakter'
        ],
        'hasil' => [
            'required'   => 'Hasil harus diisi',
            'max_length' => 'Hasil maksimal 255 karakter'
        ],
        'status' => [
            'required' => 'Status harus diisi',
            'integer'  => 'Status harus berupa angka'
        ],
        'status_gateway' => [
            'required' => 'Status gateway harus diisi',
            'in_list'  => 'Status gateway harus 0 atau 1'
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
     * Get platform by status
     * 
     * @param int $status
     * @return array
     */
    public function getByStatus($status = 1)
    {
        return $this->where('status', $status)->findAll();
    }

    /**
     * Get platform by gateway status
     * 
     * @param string $status_gateway
     * @return array
     */
    public function getByGatewayStatus($status_gateway = '1')
    {
        return $this->where('status', $status_gateway)->findAll();
    }

    /**
     * Get platform by kategori
     * 
     * @param int $id_kategori
     * @return array
     */
    public function getByKategori($id_kategori)
    {
        return $this->where('id_kategori', $id_kategori)->findAll();
    }

    /**
     * Get platform by user
     * 
     * @param int $id_user
     * @return array
     */
    public function getByUser($id_user)
    {
        return $this->where('id_user', $id_user)->findAll();
    }

    /**
     * Get platform with kategori join
     * 
     * @return array
     */
    public function getPlatformWithKategori()
    {
        return $this->select('tbl_m_platform.*, tbl_m_kategori.kategori as kategori_name')
                    ->join('tbl_m_kategori', 'tbl_m_kategori.id = tbl_m_platform.id_kategori', 'left')
                    ->findAll();
    }

    /**
     * Get platform dropdown options
     * 
     * @return array
     */
    public function getDropdownOptions()
    {
        $platforms = $this->getByGatewayStatus('1'); // Get active gateway platforms only
        $options = [];
        
        foreach ($platforms as $platform) {
            $label = $platform->nama;
            if (!empty($platform->jenis)) {
                $label .= " ({$platform->jenis})";
            }
            $options[$platform->id] = $label;
        }
        
        return $options;
    }
}