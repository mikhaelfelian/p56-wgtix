<?php

namespace App\Models;

use CodeIgniter\Model;

class VPesertaModel extends Model
{
    protected $table            = 'v_peserta';
    protected $primaryKey       = 'id';

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'id',
        'id_user',
        'nama',
        'alamat',
        'phone'
    ];

    protected $useTimestamps    = false;

    // =============================
    // BASIC QUERIES
    // =============================

    public function getAll()
    {
        return $this->orderBy('nama', 'ASC')->findAll();
    }

    public function getById($id)
    {
        return $this->where('id', $id)->first();
    }

    public function getByUser($id_user)
    {
        return $this->where('id_user', $id_user)->findAll();
    }

    public function search($keyword)
    {
        return $this->groupStart()
                        ->like('nama', $keyword)
                        ->orLike('alamat', $keyword)
                        ->orLike('phone', $keyword)
                    ->groupEnd()
                    ->orderBy('nama','ASC')
                    ->findAll();
    }

    // =============================
    // DATATABLE SERVER SIDE READY
    // =============================

    public function getDatatable($limit, $offset, $search = null, $order = 'nama', $dir = 'ASC')
    {
        $builder = $this->builder();

        if ($search) {
            $builder->groupStart()
                        ->like('nama', $search)
                        ->orLike('alamat', $search)
                        ->orLike('phone', $search)
                     ->groupEnd();
        }

        return $builder
                ->orderBy($order, $dir)
                ->limit($limit, $offset)
                ->get()
                ->getResultArray();
    }

    public function countFiltered($search = null)
    {
        $builder = $this->builder();

        if ($search) {
            $builder->groupStart()
                        ->like('nama', $search)
                        ->orLike('alamat', $search)
                        ->orLike('phone', $search)
                     ->groupEnd();
        }

        return $builder->countAllResults();
    }

    public function countAll()
    {
        return $this->countAllResults();
    }
}
