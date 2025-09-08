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
            'kategoriOptions' => $kategoriOptions,
            'Pengaturan'      => $this->pengaturan,
            'user'            => $this->ionAuth->user()->row(),
        ];

        return $this->view($this->theme->getThemePath() . '/admin/racepack/create', $data);
    }

    /**
     * Store a newly created racepack or update existing one
     */
    public function store()
    {
        // Get racepack ID for edit mode
        $racepackId = $this->request->getPost('id');
        $isEdit = !empty($racepackId);

        // Validation rules - adjust for edit mode
        $rules = [
            'kode_racepack' => $isEdit ? 'required|max_length[50]|is_unique[tbl_racepack.kode_racepack,id,' . $racepackId . ']' : 'required|max_length[50]|is_unique[tbl_racepack.kode_racepack]',
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

        // Handle file upload
        $gambar = $this->handleFileUpload($racepackId);

        $data = [
            'kode_racepack' => $this->request->getPost('kode_racepack'),
            'nama_racepack' => $this->request->getPost('nama_racepack'),
            'id_kategori'   => $this->request->getPost('id_kategori'),
            'deskripsi'     => $this->request->getPost('deskripsi') ?: null,
            'harga'         => $this->request->getPost('harga'),
            'gambar'        => $gambar,
            'status'        => $this->request->getPost('status'),
            'id_user'       => $this->data['user']->id
        ];

        // Add ID for edit mode
        if ($isEdit) {
            $data['id'] = $racepackId;
        }

        // Save racepack using save() method
        if ($this->racepackModel->save($data)) {
            $message = $isEdit ? 'Racepack berhasil diperbarui' : 'Racepack berhasil ditambahkan';
            return redirect()->to('/admin/racepack')
                           ->with('success', $message);
        }

        $errorMessage = $isEdit ? 'Gagal memperbarui racepack' : 'Gagal menambahkan racepack';
        return redirect()->back()
                       ->withInput()
                       ->with('error', $errorMessage);
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
            'kategoriOptions' => $kategoriOptions,
            'Pengaturan'      => $this->pengaturan,
            'user'            => $this->ionAuth->user()->row(),
        ];

        return $this->view($this->theme->getThemePath() . '/admin/racepack/create', $data);
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

    /**
     * Handle file upload for racepack image
     */
    private function handleFileUpload($racepackId = null)
    {
        $file = $this->request->getFile('gambar');
        
        if (!$file || !$file->isValid()) {
            // If editing and no new file uploaded, keep existing image
            if ($racepackId) {
                $existing = $this->racepackModel->find($racepackId);
                return $existing ? $existing->gambar : null;
            }
            return null;
        }

        // Validate file
        if (!$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/racepack/', $newName);
            return $newName;
        }

        return null;
    }
} 