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

    public function generateKode($id_event = null)
    {
        // Ambil kode terbesar (urutkan sebagai string, bukan numerik)
        $builder = $this->orderBy('CAST(kode AS UNSIGNED)', 'DESC');
        if ($id_event !== null) {
            $builder = $builder->where('id_event', $id_event);
        }
        $lastPeserta = $builder->first();
        if ($lastPeserta && is_numeric($lastPeserta->kode)) {
            $lastNumber = (int)$lastPeserta->kode;
        } else {
            $lastNumber = 0;
        }
        $newNumber = $lastNumber + 1;
        return str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get peserta with group and kategori info, with optional search and pagination.
     * 
     * @param int $perPage
     * @param int $page
     * @param string|null $keyword
     * @param int|null $idKelompok
     * @param int|null $status
     * @return array
     */
    public function getPesertaWithFilters($perPage = 10, $page = 1, $keyword = null, $idKelompok = null, $status = null)
    {
        $builder = $this->select('tbl_peserta.*, tbl_kelompok_peserta.nama_kelompok, tbl_m_kategori.kategori as nama_kategori')
            ->join('tbl_kelompok_peserta', 'tbl_kelompok_peserta.id = tbl_peserta.id_kelompok', 'left')
            ->join('tbl_m_kategori', 'tbl_m_kategori.id = tbl_peserta.id_kategori', 'left')
            ->orderBy('tbl_peserta.nama', 'ASC');

        if ($keyword) {
            $builder->groupStart()
                ->like('tbl_peserta.nama', $keyword)
                ->orLike('tbl_peserta.kode', $keyword)
                ->orLike('tbl_peserta.no_hp', $keyword)
                ->orLike('tbl_peserta.email', $keyword)
                ->groupEnd();
        }

        if ($idKelompok) {
            $builder->where('tbl_peserta.id_kelompok', $idKelompok);
        }

        if ($status !== null && $status !== '') {
            $builder->where('tbl_peserta.status', $status);
        }

        return $builder->paginate($perPage, 'peserta', $page);
    }

    /**
     * Get peserta with group and kategori info using query builder.
     * 
     * @return \CodeIgniter\Database\BaseBuilder
     */
    public function getPesertaWithGroupQuery()
    {
        return $this->select('tbl_peserta.*, tbl_kelompok_peserta.nama_kelompok, tbl_m_kategori.kategori as nama_kategori')
            ->join('tbl_kelompok_peserta', 'tbl_kelompok_peserta.id = tbl_peserta.id_kelompok', 'left')
            ->join('tbl_m_kategori', 'tbl_m_kategori.id = tbl_peserta.id_kategori', 'left');
    }
}