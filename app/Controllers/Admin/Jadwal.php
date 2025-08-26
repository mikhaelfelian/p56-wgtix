<?php

/**
 * Jadwal Controller
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-01-22
 * Github: github.com/mikhaelfelian
 * Description: Controller for managing jadwal (schedule) master data
 * This file represents the Controller for jadwal master data operations.
 */

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JadwalModel;

class Jadwal extends BaseController
{
    protected $jadwalModel;
    protected $perPage = 10;

    public function __construct()
    {
        parent::__construct();
        $this->jadwalModel = new JadwalModel();
    }

    /**
     * Display jadwal list
     */
    public function index()
    {
        $keyword = $this->request->getGet('keyword') ?? '';
        $currentPage = $this->request->getGet('page') ?? 1;

        $builder = $this->jadwalModel;

        if (!empty($keyword)) {
            $builder = $builder->groupStart()
                             ->like('nama_jadwal', $keyword)
                             ->orLike('kode', $keyword)
                             ->orLike('lokasi', $keyword)
                             ->orLike('keterangan', $keyword)
                             ->groupEnd();
        }

        $jadwal = $builder->paginate($this->perPage, 'jadwal');
        $pager = $this->jadwalModel->pager;

        // Count trash (soft deleted records - if implemented)
        $trashCount = 0; // Since we don't have soft deletes, set to 0

        $data = [
            'title'        => 'Data Jadwal',
            'jadwal'       => $jadwal,
            'pager'        => $pager,
            'keyword'      => $keyword,
            'currentPage'  => $currentPage,
            'perPage'      => $this->perPage,
            'trashCount'   => $trashCount
        ];

        return $this->view($this->theme->getThemePath() . '/admin/jadwal/index', $data);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Jadwal'
        ];

        return $this->view($this->theme->getThemePath() . '/admin/jadwal/create', $data);
    }

    /**
     * Store new jadwal
     */
    public function store()
    {
        $rules = [
            'nama_jadwal'     => 'required|max_length[100]',
            'kode'            => 'permit_empty|max_length[20]',
            'tanggal_mulai'   => 'required|valid_date',
            'tanggal_selesai' => 'required|valid_date',
            'waktu_mulai'     => 'required',
            'waktu_selesai'   => 'required',
            'lokasi'          => 'permit_empty|max_length[200]',
            'kapasitas'       => 'permit_empty|integer',
            'status'          => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id_user'         => $this->data['user']->id,
            'kode'            => $this->request->getPost('kode'),
            'nama_jadwal'     => $this->request->getPost('nama_jadwal'),
            'tanggal_mulai'   => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'waktu_mulai'     => $this->request->getPost('waktu_mulai'),
            'waktu_selesai'   => $this->request->getPost('waktu_selesai'),
            'lokasi'          => $this->request->getPost('lokasi'),
            'kapasitas'       => $this->request->getPost('kapasitas') ?: 0,
            'keterangan'      => $this->request->getPost('keterangan'),
            'status'          => $this->request->getPost('status')
        ];

        if ($this->jadwalModel->insert($data)) {
            return redirect()->to('/admin/jadwal')->with('success', 'Data jadwal berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data jadwal');
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $jadwal = $this->jadwalModel->find($id);
        
        if (!$jadwal) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Jadwal dengan ID $id tidak ditemukan");
        }

        $data = [
            'title'  => 'Edit Jadwal',
            'jadwal' => $jadwal
        ];

        return $this->view($this->theme->getThemePath() . '/admin/jadwal/edit', $data);
    }

    /**
     * Update jadwal
     */
    public function update($id)
    {
        $jadwal = $this->jadwalModel->find($id);
        
        if (!$jadwal) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Jadwal dengan ID $id tidak ditemukan");
        }

        $rules = [
            'nama_jadwal'     => 'required|max_length[100]',
            'kode'            => 'permit_empty|max_length[20]',
            'tanggal_mulai'   => 'required|valid_date',
            'tanggal_selesai' => 'required|valid_date',
            'waktu_mulai'     => 'required',
            'waktu_selesai'   => 'required',
            'lokasi'          => 'permit_empty|max_length[200]',
            'kapasitas'       => 'permit_empty|integer',
            'status'          => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'kode'            => $this->request->getPost('kode'),
            'nama_jadwal'     => $this->request->getPost('nama_jadwal'),
            'tanggal_mulai'   => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'waktu_mulai'     => $this->request->getPost('waktu_mulai'),
            'waktu_selesai'   => $this->request->getPost('waktu_selesai'),
            'lokasi'          => $this->request->getPost('lokasi'),
            'kapasitas'       => $this->request->getPost('kapasitas') ?: 0,
            'keterangan'      => $this->request->getPost('keterangan'),
            'status'          => $this->request->getPost('status')
        ];

        if ($this->jadwalModel->update($id, $data)) {
            return redirect()->to('/admin/jadwal')->with('success', 'Data jadwal berhasil diperbarui');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data jadwal');
        }
    }

    /**
     * Delete jadwal
     */
    public function delete($id)
    {
        $jadwal = $this->jadwalModel->find($id);
        
        if (!$jadwal) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Jadwal dengan ID $id tidak ditemukan");
        }

        if ($this->jadwalModel->delete($id)) {
            return redirect()->to('/admin/jadwal')->with('success', 'Data jadwal berhasil dihapus');
        } else {
            return redirect()->to('/admin/jadwal')->with('error', 'Gagal menghapus data jadwal');
        }
    }

    /**
     * Show jadwal detail
     */
    public function show($id)
    {
        $jadwal = $this->jadwalModel->find($id);
        
        if (!$jadwal) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Jadwal dengan ID $id tidak ditemukan");
        }

        $data = [
            'title'  => 'Detail Jadwal',
            'jadwal' => $jadwal
        ];

        return $this->view($this->theme->getThemePath() . '/admin/jadwal/show', $data);
    }
} 