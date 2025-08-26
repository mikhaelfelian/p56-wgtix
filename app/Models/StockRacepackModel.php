<?php

/**
 * StockRacepackModel
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: August 7, 2025
 * Github: github.com/mikhaelfelian
 * Description: Model for managing stock racepack data
 */

namespace App\Models;

use CodeIgniter\Model;

class StockRacepackModel extends Model
{
    protected $table      = 'tbl_stock_racepack';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'id_racepack',
        'id_ukuran',
        'stok_masuk',
        'stok_keluar',
        'stok_tersedia',
        'minimal_stok',
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
        'id_racepack'   => 'required|integer',
        'id_ukuran'     => 'required|integer',
        'stok_masuk'    => 'required|integer',
        'stok_keluar'   => 'required|integer',
        'stok_tersedia' => 'required|integer',
        'minimal_stok'  => 'required|integer',
        'status'        => 'required|in_list[0,1]',
        'id_user'       => 'required|integer'
    ];

    protected $validationMessages = [
        'id_racepack' => [
            'required' => 'Racepack harus dipilih',
            'integer'  => 'ID Racepack harus berupa angka'
        ],
        'id_ukuran' => [
            'required' => 'Ukuran harus dipilih',
            'integer'  => 'ID Ukuran harus berupa angka'
        ],
        'stok_masuk' => [
            'required' => 'Stok masuk harus diisi',
            'integer'  => 'Stok masuk harus berupa angka'
        ],
        'stok_keluar' => [
            'required' => 'Stok keluar harus diisi',
            'integer'  => 'Stok keluar harus berupa angka'
        ],
        'stok_tersedia' => [
            'required' => 'Stok tersedia harus diisi',
            'integer'  => 'Stok tersedia harus berupa angka'
        ],
        'minimal_stok' => [
            'required' => 'Minimal stok harus diisi',
            'integer'  => 'Minimal stok harus berupa angka'
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
     * Get stock with racepack and ukuran info
     * 
     * @return array
     */
    public function getStockWithDetails()
    {
        return $this->select('tbl_stock_racepack.*, tbl_racepack.nama_racepack, tbl_racepack.kode_racepack, tbl_m_ukuran.ukuran')
                   ->join('tbl_racepack', 'tbl_racepack.id = tbl_stock_racepack.id_racepack', 'left')
                   ->join('tbl_m_ukuran', 'tbl_m_ukuran.id = tbl_stock_racepack.id_ukuran', 'left')
                   ->orderBy('tbl_racepack.nama_racepack', 'ASC')
                   ->orderBy('tbl_m_ukuran.ukuran', 'ASC');
    }

    /**
     * Get stock by racepack and ukuran
     * 
     * @param int $idRacepack
     * @param int $idUkuran
     * @return object|null
     */
    public function getStockByRacepackAndUkuran($idRacepack, $idUkuran)
    {
        return $this->where('id_racepack', $idRacepack)
                   ->where('id_ukuran', $idUkuran)
                   ->where('status', '1')
                   ->first();
    }

    /**
     * Update stock
     * 
     * @param int $idRacepack
     * @param int $idUkuran
     * @param int $stokMasuk
     * @param int $stokKeluar
     * @return bool
     */
    public function updateStock($idRacepack, $idUkuran, $stokMasuk, $stokKeluar)
    {
        $stokTersedia = $stokMasuk - $stokKeluar;
        
        $data = [
            'stok_masuk'    => $stokMasuk,
            'stok_keluar'   => $stokKeluar,
            'stok_tersedia' => $stokTersedia
        ];

        return $this->where('id_racepack', $idRacepack)
                   ->where('id_ukuran', $idUkuran)
                   ->set($data)
                   ->update();
    }

    /**
     * Get low stock items
     * 
     * @return array
     */
    public function getLowStockItems()
    {
        return $this->select('tbl_stock_racepack.*, tbl_racepack.nama_racepack, tbl_racepack.kode_racepack, tbl_m_ukuran.ukuran')
                   ->join('tbl_racepack', 'tbl_racepack.id = tbl_stock_racepack.id_racepack', 'left')
                   ->join('tbl_m_ukuran', 'tbl_m_ukuran.id = tbl_stock_racepack.id_ukuran', 'left')
                   ->where('tbl_stock_racepack.stok_tersedia <= tbl_stock_racepack.minimal_stok')
                   ->where('tbl_stock_racepack.status', '1')
                   ->findAll();
    }

    /**
     * Get stock statistics
     * 
     * @return object
     */
    public function getStockStats()
    {
        $total = $this->countAll();
        $active = $this->where('status', '1')->countAllResults();
        $inactive = $this->where('status', '0')->countAllResults();
        $lowStock = $this->where('stok_tersedia <= minimal_stok')->where('status', '1')->countAllResults();
        $outOfStock = $this->where('stok_tersedia <= 0')->where('status', '1')->countAllResults();

        return (object) [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'lowStock' => $lowStock,
            'outOfStock' => $outOfStock
        ];
    }

    /**
     * Search stock
     * 
     * @param string $keyword
     * @return array
     */
    public function searchStock($keyword)
    {
        return $this->select('tbl_stock_racepack.*, tbl_racepack.nama_racepack, tbl_racepack.kode_racepack, tbl_m_ukuran.ukuran')
                   ->join('tbl_racepack', 'tbl_racepack.id = tbl_stock_racepack.id_racepack', 'left')
                   ->join('tbl_m_ukuran', 'tbl_m_ukuran.id = tbl_stock_racepack.id_ukuran', 'left')
                   ->groupStart()
                   ->like('tbl_racepack.nama_racepack', $keyword)
                   ->orLike('tbl_racepack.kode_racepack', $keyword)
                   ->orLike('tbl_m_ukuran.ukuran', $keyword)
                   ->groupEnd()
                   ->orderBy('tbl_racepack.nama_racepack', 'ASC');
    }
} 