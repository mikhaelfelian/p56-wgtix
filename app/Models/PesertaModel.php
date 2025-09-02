<?php

/**
 * PesertaModel
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Model for managing peserta (participants) data
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
        'kode_peserta',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_hp',
        'email',
        'id_kelompok',
        'id_kategori',
        'status',
        'qr_code',
        'tripay_reference',
        'tripay_pay_url'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'nama_lengkap'    => 'required|max_length[100]',
        'kode_peserta'    => 'required|max_length[20]',
        'jenis_kelamin'   => 'required|in_list[L,P]',
        'tempat_lahir'    => 'permit_empty|max_length[50]',
        'tanggal_lahir'   => 'permit_empty|valid_date',
        'alamat'          => 'permit_empty',
        'no_hp'           => 'permit_empty|max_length[15]',
        'email'           => 'permit_empty|valid_email|max_length[100]',
        'id_kelompok'     => 'permit_empty|integer',
        'id_kategori'     => 'permit_empty|integer',
        'status'          => 'required|in_list[0,1]',
        'id_user'         => 'required|integer'
    ];

    protected $validationMessages = [
        'nama_lengkap' => [
            'required'   => 'Nama lengkap harus diisi',
            'max_length' => 'Nama lengkap maksimal 100 karakter'
        ],
        'kode_peserta' => [
            'required'   => 'Kode peserta harus diisi',
            'max_length' => 'Kode peserta maksimal 20 karakter'
        ],
        'jenis_kelamin' => [
            'required' => 'Jenis kelamin harus dipilih',
            'in_list'  => 'Jenis kelamin harus L (Laki-laki) atau P (Perempuan)'
        ],
        'tempat_lahir' => [
            'max_length' => 'Tempat lahir maksimal 50 karakter'
        ],
        'tanggal_lahir' => [
            'valid_date' => 'Format tanggal lahir tidak valid'
        ],
        'no_hp' => [
            'max_length' => 'Nomor HP maksimal 15 karakter'
        ],
        'email' => [
            'valid_email' => 'Format email tidak valid',
            'max_length'  => 'Email maksimal 100 karakter'
        ],
        'id_kelompok' => [
            'integer' => 'ID Kelompok harus berupa angka'
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
     * Get active participants
     * 
     * @return array
     */
    public function getActivePeserta()
    {
        return $this->where('status', '1')
                   ->orderBy('nama_lengkap', 'ASC')
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
        return $this->where('kode_peserta', $kode)->first();
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
                   ->orderBy('tbl_peserta.nama_lengkap', 'ASC')
                   ->findAll();
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
                   ->where('status', '1')
                   ->orderBy('nama_lengkap', 'ASC')
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

        $builder = $this->where('kode_peserta', $kode);
        
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
        $active = $this->where('status', '1')->countAllResults();
        $inactive = $this->where('status', '0')->countAllResults();
        $male = $this->where('jenis_kelamin', 'L')->where('status', '1')->countAllResults();
        $female = $this->where('jenis_kelamin', 'P')->where('status', '1')->countAllResults();

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
                   ->like('nama_lengkap', $keyword)
                   ->orLike('kode_peserta', $keyword)
                   ->orLike('no_hp', $keyword)
                   ->orLike('email', $keyword)
                   ->groupEnd()
                   ->orderBy('nama_lengkap', 'ASC')
                   ->findAll();
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
            $label = "({$p->kode_peserta}) {$p->nama_lengkap}";
            $options[$p->id] = $label;
        }
        
        return $options;
    }
} 