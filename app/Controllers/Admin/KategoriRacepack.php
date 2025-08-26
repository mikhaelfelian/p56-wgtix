<?php

/**
 * KategoriRacepack Controller
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: August 7, 2025
 * Github: github.com/mikhaelfelian
 * Description: Controller for managing kategori racepack data
 */

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriRacepackModel;
use CodeIgniter\HTTP\ResponseInterface;

class KategoriRacepack extends BaseController
{
    protected $kategoriModel;

    public function __construct()
    {
        parent::__construct();
        $this->kategoriModel = new KategoriRacepackModel();
    }

    /**
     * Display a listing of categories
     */
    public function index()
    {
        $search = $this->request->getGet('search');
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 10;

        $query = $this->kategoriModel;

        if ($search) {
            $query = $query->searchCategories($search);
        } else {
            $query = $query->orderBy('nama_kategori', 'ASC');
        }

        $kategori = $query->paginate($perPage);
        $pager = $this->kategoriModel->pager;

        $data = [
            'title'       => 'Kategori Racepack',
            'kategori'    => $kategori,
            'pager'       => $pager,
            'keyword'     => $search,
            'currentPage' => $page,
            'perPage'     => $perPage
        ];

        return $this->view($this->theme->getThemePath() . '/admin/kategori_racepack/index', $data);
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Kategori Racepack'
        ];

        return $this->view($this->theme->getThemePath() . '/admin/kategori_racepack/create', $data);
    }

    /**
     * Store a newly created category
     */
    public function store()
    {
        $rules = [
            'nama_kategori' => 'required|max_length[100]',
            'deskripsi'     => 'permit_empty',
            'status'        => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'deskripsi'     => $this->request->getPost('deskripsi') ?: null,
            'status'        => $this->request->getPost('status'),
            'id_user'       => $this->data['user']->id
        ];

        if ($this->kategoriModel->insert($data)) {
            return redirect()->to('/admin/kategori-racepack')
                           ->with('success', 'Kategori racepack berhasil ditambahkan');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Gagal menambahkan kategori racepack');
    }

    /**
     * Show the form for editing the specified category
     */
    public function edit($id = null)
    {
        $kategori = $this->kategoriModel->find($id);

        if (!$kategori) {
            return redirect()->to('/admin/kategori-racepack')
                           ->with('error', 'Kategori racepack tidak ditemukan');
        }

        $data = [
            'title'    => 'Edit Kategori Racepack',
            'kategori' => $kategori
        ];

        return $this->view($this->theme->getThemePath() . '/admin/kategori_racepack/edit', $data);
    }

    /**
     * Update the specified category
     */
    public function update($id = null)
    {
        $kategori = $this->kategoriModel->find($id);

        if (!$kategori) {
            return redirect()->to('/admin/kategori-racepack')
                           ->with('error', 'Kategori racepack tidak ditemukan');
        }

        $rules = [
            'nama_kategori' => 'required|max_length[100]',
            'deskripsi'     => 'permit_empty',
            'status'        => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'deskripsi'     => $this->request->getPost('deskripsi') ?: null,
            'status'        => $this->request->getPost('status')
        ];

        if ($this->kategoriModel->update($id, $data)) {
            return redirect()->to('/admin/kategori-racepack')
                           ->with('success', 'Kategori racepack berhasil diperbarui');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Gagal memperbarui kategori racepack');
    }

    /**
     * Remove the specified category
     */
    public function delete($id = null)
    {
        $kategori = $this->kategoriModel->find($id);

        if (!$kategori) {
            return redirect()->to('/admin/kategori-racepack')
                           ->with('error', 'Kategori racepack tidak ditemukan');
        }

        if ($this->kategoriModel->delete($id)) {
            return redirect()->to('/admin/kategori-racepack')
                           ->with('success', 'Kategori racepack berhasil dihapus');
        }

        return redirect()->to('/admin/kategori-racepack')
                       ->with('error', 'Gagal menghapus kategori racepack');
    }
} 