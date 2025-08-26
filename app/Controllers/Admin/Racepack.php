<?php

/**
 * Racepack Controller
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: August 7, 2025
 * Github: github.com/mikhaelfelian
 * Description: Controller for managing racepack data
 */

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RacepackModel;
use App\Models\KategoriRacepackModel;
use CodeIgniter\HTTP\ResponseInterface;

class Racepack extends BaseController
{
    protected $racepackModel;
    protected $kategoriModel;

    public function __construct()
    {
        parent::__construct();
        $this->racepackModel = new RacepackModel();
        $this->kategoriModel = new KategoriRacepackModel();
    }

    /**
     * Display a listing of racepack
     */
    public function index()
    {
        $search = $this->request->getGet('search');
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 10;

        $query = $this->racepackModel;

        if ($search) {
            $query = $query->searchRacepack($search);
        } else {
            $query = $query->getRacepackWithCategory();
        }

        $racepack = $query->paginate($perPage);
        $pager = $this->racepackModel->pager;

        $data = [
            'title'       => 'Data Racepack',
            'racepack'    => $racepack,
            'pager'       => $pager,
            'keyword'     => $search,
            'currentPage' => $page,
            'perPage'     => $perPage
        ];

        return $this->view($this->theme->getThemePath() . '/admin/racepack/index', $data);
    }

    /**
     * Show the form for creating a new racepack
     */
    public function create()
    {
        $kategoriOptions = $this->kategoriModel->getDropdownOptions();

        $data = [
            'title'           => 'Tambah Racepack',
            'kategoriOptions' => $kategoriOptions
        ];

        return $this->view($this->theme->getThemePath() . '/admin/racepack/create', $data);
    }

    /**
     * Store a newly created racepack
     */
    public function store()
    {
        $rules = [
            'kode_racepack' => 'required|max_length[50]',
            'nama_racepack' => 'required|max_length[200]',
            'id_kategori'   => 'required|integer',
            'deskripsi'     => 'permit_empty',
            'harga'         => 'required|decimal',
            'gambar'        => 'permit_empty|max_length[255]',
            'status'        => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Check if kode already exists
        $kode = $this->request->getPost('kode_racepack');
        if ($this->racepackModel->isCodeExists($kode)) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Kode racepack sudah ada');
        }

        $data = [
            'kode_racepack' => $kode,
            'nama_racepack' => $this->request->getPost('nama_racepack'),
            'id_kategori'   => $this->request->getPost('id_kategori'),
            'deskripsi'     => $this->request->getPost('deskripsi') ?: null,
            'harga'         => $this->request->getPost('harga'),
            'gambar'        => $this->request->getPost('gambar') ?: null,
            'status'        => $this->request->getPost('status'),
            'id_user'       => $this->data['user']->id
        ];

        if ($this->racepackModel->insert($data)) {
            return redirect()->to('/admin/racepack')
                           ->with('success', 'Racepack berhasil ditambahkan');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Gagal menambahkan racepack');
    }

    /**
     * Display the specified racepack
     */
    public function show($id = null)
    {
        $racepack = $this->racepackModel->getRacepackWithCategory()->where('tbl_racepack.id', $id)->first();

        if (!$racepack) {
            return redirect()->to('/admin/racepack')
                           ->with('error', 'Racepack tidak ditemukan');
        }

        $data = [
            'title'   => 'Detail Racepack',
            'racepack' => $racepack
        ];

        return $this->view($this->theme->getThemePath() . '/admin/racepack/show', $data);
    }

    /**
     * Show the form for editing the specified racepack
     */
    public function edit($id = null)
    {
        $racepack = $this->racepackModel->find($id);

        if (!$racepack) {
            return redirect()->to('/admin/racepack')
                           ->with('error', 'Racepack tidak ditemukan');
        }

        $kategoriOptions = $this->kategoriModel->getDropdownOptions();

        $data = [
            'title'           => 'Edit Racepack',
            'racepack'        => $racepack,
            'kategoriOptions' => $kategoriOptions
        ];

        return $this->view($this->theme->getThemePath() . '/admin/racepack/edit', $data);
    }

    /**
     * Update the specified racepack
     */
    public function update($id = null)
    {
        $racepack = $this->racepackModel->find($id);

        if (!$racepack) {
            return redirect()->to('/admin/racepack')
                           ->with('error', 'Racepack tidak ditemukan');
        }

        $rules = [
            'kode_racepack' => 'required|max_length[50]',
            'nama_racepack' => 'required|max_length[200]',
            'id_kategori'   => 'required|integer',
            'deskripsi'     => 'permit_empty',
            'harga'         => 'required|decimal',
            'gambar'        => 'permit_empty|max_length[255]',
            'status'        => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Check if kode already exists (excluding current record)
        $kode = $this->request->getPost('kode_racepack');
        if ($this->racepackModel->isCodeExists($kode, $id)) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Kode racepack sudah ada');
        }

        $data = [
            'kode_racepack' => $kode,
            'nama_racepack' => $this->request->getPost('nama_racepack'),
            'id_kategori'   => $this->request->getPost('id_kategori'),
            'deskripsi'     => $this->request->getPost('deskripsi') ?: null,
            'harga'         => $this->request->getPost('harga'),
            'gambar'        => $this->request->getPost('gambar') ?: null,
            'status'        => $this->request->getPost('status')
        ];

        if ($this->racepackModel->update($id, $data)) {
            return redirect()->to('/admin/racepack')
                           ->with('success', 'Racepack berhasil diperbarui');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Gagal memperbarui racepack');
    }

    /**
     * Remove the specified racepack
     */
    public function delete($id = null)
    {
        $racepack = $this->racepackModel->find($id);

        if (!$racepack) {
            return redirect()->to('/admin/racepack')
                           ->with('error', 'Racepack tidak ditemukan');
        }

        if ($this->racepackModel->delete($id)) {
            return redirect()->to('/admin/racepack')
                           ->with('success', 'Racepack berhasil dihapus');
        }

        return redirect()->to('/admin/racepack')
                       ->with('error', 'Gagal menghapus racepack');
    }
} 