<?php

/**
 * PesertaModel
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Model for managing peserta (participants) data
 * 
 * Adjusted to match table structure:
 * - Table: tbl_peserta
 * - Fields: id, id_user, id_kategori, id_platform, id_kelompok, created_at, updated_at, kode, nama, tmp_lahir, tgl_lahir, jns_klm, alamat, no_hp, email, qr_code, status
 */

namespace App\Models;

use CodeIgniter\Model;

class PesertaModel extends Model
{
    protected $table      = 'tbl_peserta';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'id_user',
        'id_kategori',
        'id_platform',
        'id_kelompok',
        'id_event',
        'id_penjualan',
        'created_at',
        'updated_at',
        'kode',
        'nama',
        'tmp_lahir',
        'tgl_lahir',
        'jns_klm',
        'alamat',
        'no_hp',
        'email',
        'qr_code',
        'status',
        'status_hadir'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [];

    protected $validationMessages = [];

    // Custom methods

    public function generateKode()
    {
        // Ambil kode terakhir, urutkan numerik dari kanan (3 digit terakhir)
        $lastPeserta = $this->orderBy('CAST(SUBSTRING(kode, -3) AS UNSIGNED)', 'DESC')->first();
        if ($lastPeserta && preg_match('/(\d{3})$/', $lastPeserta->kode, $matches)) {
            $lastNumber = (int)$matches[1];
        } else {
            $lastNumber = 0;
        }
        $newNumber = $lastNumber + 1;
        // Jika ada prefix (misal "PES"), ambil prefix dari kode terakhir, jika tidak, default kosong
        $prefix = '';
        if ($lastPeserta && preg_match('/^([A-Za-z]*)\d{3}$/', $lastPeserta->kode, $matchesPrefix)) {
            $prefix = $matchesPrefix[1];
        }
        return str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get active participants
     * 
     * @return array
     */
    public function getActivePeserta()
    {
        return $this->where('status', '1')
                    ->orderBy('nama', 'ASC')
                    ->findAll();
    }

    /**
     * Get participant by code
     * 
     * @param string $kode
     * @return object|null
     */
    public function getPesertaByCode($kode)
    {
        return $this->where('kode', $kode)->first();
    }

    /**
     * Get participants with group and kategori info
     * 
     * @return array
     */
    public function getPesertaWithGroup()
    {
        return $this->select('tbl_peserta.*, tbl_kelompok_peserta.nama_kelompok, tbl_m_kategori.kategori as nama_kategori')
                    ->join('tbl_kelompok_peserta', 'tbl_kelompok_peserta.id = tbl_peserta.id_kelompok', 'left')
                    ->join('tbl_m_kategori', 'tbl_m_kategori.id = tbl_peserta.id_kategori', 'left')
                    ->orderBy('tbl_peserta.nama', 'ASC')
                    ->findAll();
    }

    /**
     * Get participants with group and kategori info (for pagination)
     * 
     * @return $this
     */
    public function getPesertaWithGroupQuery()
    {
        return $this->select('tbl_peserta.*, tbl_kelompok_peserta.nama_kelompok, tbl_m_kategori.kategori as nama_kategori')
                    ->join('tbl_kelompok_peserta', 'tbl_kelompok_peserta.id = tbl_peserta.id_kelompok', 'left')
                    ->join('tbl_m_kategori', 'tbl_m_kategori.id = tbl_peserta.id_kategori', 'left')
                    ->orderBy('tbl_peserta.nama', 'ASC');
    }

    /**
     * Get participants by group
     * 
     * @param int $idKelompok
     * @return array
     */
    public function getPesertaByKelompok($idKelompok)
    {
        return $this->where('id_kelompok', $idKelompok)
                    ->where('status', 1)
                    ->orderBy('nama', 'ASC')
                    ->findAll();
    }

    /**
     * Check if participant code exists (for validation)
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
     * Get participant statistics
     * 
     * @return object
     */
    public function getPesertaStats()
    {
        $total = $this->countAll();
        $active = $this->where('status', 1)->countAllResults();
        $inactive = $this->where('status', 0)->countAllResults();
        $male = $this->where('jns_klm', 'L')->where('status', 1)->countAllResults();
        $female = $this->where('jns_klm', 'P')->where('status', 1)->countAllResults();

        return (object) [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'male' => $male,
            'female' => $female
        ];
    }

    /**
     * Search participants
     * 
     * @param string $keyword
     * @return array
     */
    public function searchPeserta($keyword)
    {
        return $this->groupStart()
                    ->like('nama', $keyword)
                    ->orLike('kode', $keyword)
                    ->orLike('no_hp', $keyword)
                    ->orLike('email', $keyword)
                    ->groupEnd()
                    ->orderBy('nama', 'ASC')
                    ->findAll();
    }

    /**
     * Search participants (for pagination)
     * 
     * @param string $keyword
     * @return $this
     */
    public function searchPesertaQuery($keyword)
    {
        return $this->select('tbl_peserta.*, tbl_kelompok_peserta.nama_kelompok, tbl_m_kategori.kategori as nama_kategori')
                    ->join('tbl_kelompok_peserta', 'tbl_kelompok_peserta.id = tbl_peserta.id_kelompok', 'left')
                    ->join('tbl_m_kategori', 'tbl_m_kategori.id = tbl_peserta.id_kategori', 'left')
                    ->groupStart()
                    ->like('tbl_peserta.nama', $keyword)
                    ->orLike('tbl_peserta.kode', $keyword)
                    ->orLike('tbl_peserta.no_hp', $keyword)
                    ->orLike('tbl_peserta.email', $keyword)
                    ->groupEnd()
                    ->orderBy('tbl_peserta.nama', 'ASC');
    }

    /**
     * Get participant dropdown options
     * 
     * @return array
     */
    public function getDropdownOptions()
    {
        $peserta = $this->getActivePeserta();
        $options = [];
        
        foreach ($peserta as $p) {
            $label = "({$p->kode}) {$p->nama}";
            $options[$p->id] = $label;
        }
        
        return $options;
    }
}