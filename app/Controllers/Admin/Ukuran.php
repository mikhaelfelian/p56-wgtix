<?php

/**
 * Ukuran Controller
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-08-06
 * Github: github.com/mikhaelfelian
 * Description: Controller for managing ukuran (size/racepack) master data
 * This file represents the Controller for ukuran master data operations.
 */

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UkuranModel;

class Ukuran extends BaseController
{
    protected $ukuranModel;
    protected $perPage = 10;

    public function __construct()
    {
        parent::__construct();
        $this->ukuranModel = new UkuranModel();
    }

    /**
     * Display ukuran list
     */
    public function index()
    {
        $keyword = $this->request->getGet('keyword') ?? '';
        $currentPage = $this->request->getGet('page') ?? 1;

        $builder = $this->ukuranModel;

        if (!empty($keyword)) {
            $builder = $builder->groupStart()
                             ->like('ukuran', $keyword)
                             ->orLike('kode', $keyword)
                             ->orLike('deskripsi', $keyword)
                             ->groupEnd();
        }

        $ukuran = $builder->paginate($this->perPage, 'ukuran');
        $pager = $this->ukuranModel->pager;

        // Count trash (soft deleted records - if implemented)
        $trashCount = 0; // Since we don't have soft deletes, set to 0

        $data = [
            'title'        => 'Data Ukuran',
            'ukuran'       => $ukuran,
            'pager'        => $pager,
            'keyword'      => $keyword,
            'currentPage'  => $currentPage,
            'perPage'      => $this->perPage,
            'trashCount'   => $trashCount
        ];

        return $this->view($this->theme->getThemePath() . '/admin/ukuran/index', $data);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Ukuran'
        ];

        return $this->view($this->theme->getThemePath() . '/admin/ukuran/create', $data);
    }

    /**
     * Store new ukuran
     */
    public function store()
    {
        $rules = [
            'ukuran'    => 'required|max_length[50]',
            'kode'      => 'permit_empty|max_length[20]',
            'deskripsi' => 'permit_empty|max_length[100]',
            'harga'     => 'permit_empty|decimal',
            'stok'      => 'permit_empty|integer',
            'status'    => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id_user'    => $this->data['user']->id,
            'kode'       => $this->request->getPost('kode'),
            'ukuran'     => $this->request->getPost('ukuran'),
            'deskripsi'  => $this->request->getPost('deskripsi'),
            'keterangan' => $this->request->getPost('keterangan'),
            'harga'      => $this->request->getPost('harga') ?: 0,
            'stok'       => $this->request->getPost('stok') ?: 0,
            'status'     => $this->request->getPost('status')
        ];

        if ($this->ukuranModel->insert($data)) {
            return redirect()->to('/admin/ukuran')->with('success', 'Data ukuran berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data ukuran');
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $ukuran = $this->ukuranModel->find($id);
        
        if (!$ukuran) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Ukuran dengan ID $id tidak ditemukan");
        }

        $data = [
            'title'  => 'Edit Ukuran',
            'ukuran' => $ukuran
        ];

        return $this->view($this->theme->getThemePath() . '/admin/ukuran/edit', $data);
    }

    /**
     * Update ukuran
     */
    public function update($id)
    {
        $ukuran = $this->ukuranModel->find($id);
        
        if (!$ukuran) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Ukuran dengan ID $id tidak ditemukan");
        }

        $rules = [
            'ukuran'    => 'required|max_length[50]',
            'kode'      => 'permit_empty|max_length[20]',
            'deskripsi' => 'permit_empty|max_length[100]',
            'harga'     => 'permit_empty|decimal',
            'stok'      => 'permit_empty|integer',
            'status'    => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'kode'       => $this->request->getPost('kode'),
            'ukuran'     => $this->request->getPost('ukuran'),
            'deskripsi'  => $this->request->getPost('deskripsi'),
            'keterangan' => $this->request->getPost('keterangan'),
            'harga'      => $this->request->getPost('harga') ?: 0,
            'stok'       => $this->request->getPost('stok') ?: 0,
            'status'     => $this->request->getPost('status')
        ];

        if ($this->ukuranModel->update($id, $data)) {
            return redirect()->to('/admin/ukuran')->with('success', 'Data ukuran berhasil diperbarui');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data ukuran');
        }
    }

    /**
     * Delete ukuran
     */
    public function delete($id)
    {
        $ukuran = $this->ukuranModel->find($id);
        
        if (!$ukuran) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Ukuran dengan ID $id tidak ditemukan");
        }

        if ($this->ukuranModel->delete($id)) {
            return redirect()->to('/admin/ukuran')->with('success', 'Data ukuran berhasil dihapus');
        } else {
            return redirect()->to('/admin/ukuran')->with('error', 'Gagal menghapus data ukuran');
        }
    }

    /**
     * Show ukuran detail
     */
    public function show($id)
    {
        $ukuran = $this->ukuranModel->find($id);
        
        if (!$ukuran) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Ukuran dengan ID $id tidak ditemukan");
        }

        $data = [
            'title'  => 'Detail Ukuran',
            'ukuran' => $ukuran
        ];

        return $this->view($this->theme->getThemePath() . '/admin/ukuran/show', $data);
    }
}