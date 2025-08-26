<?php

/**
 * Kategori Controller
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Controller for managing kategori (categories) master data
 * This file represents the Controller for kategori master data operations.
 */

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriModel;
use CodeIgniter\HTTP\ResponseInterface;

class Kategori extends BaseController
{
    protected $kategoriModel;

    public function __construct()
    {
        parent::__construct();
        $this->kategoriModel = new KategoriModel();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = $this->request->getGet('search');
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 10;

        $query = $this->kategoriModel;

        if ($search) {
            $query = $query->like('kategori', $search)
                          ->orLike('kode', $search)
                          ->orLike('keterangan', $search);
        }

        $kategoris = $query->paginate($perPage);
        $pager = $this->kategoriModel->pager;

        $data = [
            'title'        => 'Data Kategori',
            'kategori'     => $kategoris,
            'pager'        => $pager,
            'keyword'      => $search,
            'currentPage'  => $page,
            'perPage'      => $perPage,
            'trashCount'   => 0
        ];

        return $this->view($this->theme->getThemePath() . '/admin/kategori/index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Kategori'
        ];

        return $this->view($this->theme->getThemePath() . '/admin/kategori/create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $rules = [
            'kategori' => 'required|max_length[100]',
            'kode'     => 'permit_empty|max_length[20]',
            'keterangan' => 'permit_empty|max_length[255]',
            'status'   => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'kategori' => $this->request->getPost('kategori'),
            'kode' => $this->request->getPost('kode') ?: null,
            'keterangan' => $this->request->getPost('keterangan') ?: null,
            'status' => $this->request->getPost('status'),
            'id_user' => $this->data['user']->id
        ];

        // Check if kode already exists
        if ($data['kode'] && $this->kategoriModel->isCodeExists($data['kode'])) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Kode kategori sudah ada');
        }

        if ($this->kategoriModel->insert($data)) {
            return redirect()->to('/admin/kategori')
                           ->with('success', 'Kategori berhasil ditambahkan');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Gagal menambahkan kategori');
    }

    /**
     * Display the specified resource.
     */
    public function show($id = null)
    {
        $kategori = $this->kategoriModel->find($id);

        if (!$kategori) {
            return redirect()->to('/admin/kategori')
                           ->with('error', 'Kategori tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Kategori',
            'kategori' => $kategori
        ];

        return $this->view($this->theme->getThemePath() . '/admin/kategori/show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id = null)
    {
        $kategori = $this->kategoriModel->find($id);

        if (!$kategori) {
            return redirect()->to('/admin/kategori')
                           ->with('error', 'Kategori tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Kategori',
            'kategori' => $kategori
        ];

        return $this->view($this->theme->getThemePath() . '/admin/kategori/edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id = null)
    {
        $kategori = $this->kategoriModel->find($id);

        if (!$kategori) {
            return redirect()->to('/admin/kategori')
                           ->with('error', 'Kategori tidak ditemukan');
        }

        $rules = [
            'kategori' => 'required|max_length[100]',
            'kode'     => 'permit_empty|max_length[20]',
            'keterangan' => 'permit_empty|max_length[255]',
            'status'   => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'kategori' => $this->request->getPost('kategori'),
            'kode' => $this->request->getPost('kode') ?: null,
            'keterangan' => $this->request->getPost('keterangan') ?: null,
            'status' => $this->request->getPost('status')
        ];

        // Check if kode already exists (excluding current record)
        if ($data['kode'] && $this->kategoriModel->isCodeExists($data['kode'], $id)) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Kode kategori sudah ada');
        }

        if ($this->kategoriModel->update($id, $data)) {
            return redirect()->to('/admin/kategori')
                           ->with('success', 'Kategori berhasil diperbarui');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Gagal memperbarui kategori');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id = null)
    {
        $kategori = $this->kategoriModel->find($id);

        if (!$kategori) {
            return redirect()->to('/admin/kategori')
                           ->with('error', 'Kategori tidak ditemukan');
        }

        if ($this->kategoriModel->delete($id)) {
            return redirect()->to('/admin/kategori')
                           ->with('success', 'Kategori berhasil dihapus');
        }

        return redirect()->to('/admin/kategori')
                       ->with('error', 'Gagal menghapus kategori');
    }
} 