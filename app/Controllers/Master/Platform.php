<?php

/**
 * Platform Controller
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-08-06
 * Github: github.com/mikhaelfelian
 * Description: Controller for managing platform master data including payment gateways and platforms
 * This file represents the Controller for platform master data operations.
 */

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\PlatformModel;
use App\Models\KategoriModel;

class Platform extends BaseController
{
    protected $platformModel;
    protected $kategoriModel;
    protected $perPage = 10;

    public function __construct()
    {
        parent::__construct();
        $this->platformModel = new PlatformModel();
        $this->kategoriModel = new KategoriModel();
    }

    /**
     * Display platform list
     */
    public function index()
    {
        $keyword = $this->request->getGet('keyword') ?? '';
        $currentPage = $this->request->getGet('page') ?? 1;

        $builder = $this->platformModel->select('tbl_m_platform.*, tbl_m_kategori.kategori as kategori_name')
                                      ->join('tbl_m_kategori', 'tbl_m_kategori.id = tbl_m_platform.id_kategori', 'left');

        if (!empty($keyword)) {
            $builder->groupStart()
                   ->like('tbl_m_platform.nama', $keyword)
                   ->orLike('tbl_m_platform.jenis', $keyword)
                   ->orLike('tbl_m_platform.nama_rekening', $keyword)
                   ->orLike('tbl_m_platform.nomor_rekening', $keyword)
                   ->orLike('tbl_m_kategori.kategori', $keyword)
                   ->groupEnd();
        }

        $platform = $builder->paginate($this->perPage, 'platform');
        $pager = $this->platformModel->pager;

        // Count trash (soft deleted records - if implemented)
        $trashCount = 0; // Since we don't have soft deletes, set to 0

        $data = [
            'title'        => 'Data Platform',
            'platform'     => $platform,
            'pager'        => $pager,
            'keyword'      => $keyword,
            'currentPage'  => $currentPage,
            'perPage'      => $this->perPage,
            'trashCount'   => $trashCount
        ];

        return $this->view($this->theme->getThemePath() . '/master/platform/index', $data);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $data = [
            'title'     => 'Tambah Platform',
            'kategori'  => $this->kategoriModel->where('status', 1)->findAll()
        ];

        return $this->view($this->theme->getThemePath() . '/master/platform/create', $data);
    }

    /**
     * Store new platform
     */
    public function store()
    {
        $rules = [
            // 'id_kategori'       => 'required|integer',
            'nama'              => 'required|max_length[100]',
            // 'jenis'             => 'required|max_length[100]',
            // 'kategori'          => 'required|max_length[100]',
            // 'nama_rekening'     => 'required|max_length[111]',
            // 'nomor_rekening'    => 'required|max_length[111]',
            // 'deskripsi'         => 'required',
            // 'gateway_kode'      => 'permit_empty|max_length[111]',
            // 'gateway_instruksi' => 'permit_empty|in_list[0,1]',
            // 'logo'              => 'required|max_length[111]',
            // 'hasil'             => 'required|max_length[255]',
            // 'status'            => 'required|integer',
            // 'status_gateway'    => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $id_user           = $this->data['user']->id;
        $id_kategori       = $this->request->getPost('id_kategori');
        $nama              = $this->request->getPost('nama');
        $jenis             = $this->request->getPost('jenis');
        $kategori          = $this->request->getPost('kategori');
        $nama_rekening     = $this->request->getPost('nama_rekening');
        $nomor_rekening    = $this->request->getPost('nomor_rekening');
        $deskripsi         = $this->request->getPost('deskripsi');
        $gateway_kode      = $this->request->getPost('gateway_kode') ?: '-';
        $gateway_instruksi = $this->request->getPost('gateway_instruksi') ?: '0';
        $logo              = $this->request->getPost('logo');
        $hasil             = $this->request->getPost('hasil');
        $status            = $this->request->getPost('status');
        $status_gateway    = $this->request->getPost('status_gateway');

        $data = [
            'id_user'           => $id_user,
            'id_kategori'       => $id_kategori,
            'nama'              => $nama,
            'jenis'             => $jenis,
            'kategori'          => $kategori,
            'nama_rekening'     => $nama_rekening,
            'nomor_rekening'    => $nomor_rekening,
            'deskripsi'         => $deskripsi,
            'gateway_kode'      => $gateway_kode,
            'gateway_instruksi' => $gateway_instruksi,
            'logo'              => $logo,
            'hasil'             => $hasil,
            'status'            => $status,
            'status_gateway'    => $status_gateway
        ];

        $result = $this->platformModel->save($data);
        return redirect()->to(base_url('admin/master/platform'));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $platform = $this->platformModel->find($id);
        
        if (!$platform) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Platform dengan ID $id tidak ditemukan");
        }

        $data = [
            'title'     => 'Edit Platform',
            'platform'  => $platform,
            'kategori'  => $this->kategoriModel->where('status', 1)->findAll()
        ];

        return $this->view($this->theme->getThemePath() . '/master/platform/edit', $data);
    }

    /**
     * Update platform
     */
    public function update($id)
    {
        $platform = $this->platformModel->find($id);
        
        if (!$platform) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Platform dengan ID $id tidak ditemukan");
        }

        $rules = [
            'id_kategori'       => 'required|integer',
            'nama'              => 'required|max_length[100]',
            'jenis'             => 'required|max_length[100]',
            'kategori'          => 'required|max_length[100]',
            'nama_rekening'     => 'required|max_length[111]',
            'nomor_rekening'    => 'required|max_length[111]',
            'deskripsi'         => 'required',
            'gateway_kode'      => 'permit_empty|max_length[111]',
            'gateway_instruksi' => 'permit_empty|in_list[0,1]',
            'logo'              => 'required|max_length[111]',
            'hasil'             => 'required|max_length[255]',
            'status'            => 'required|integer',
            'status_gateway'    => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id_kategori'       => $this->request->getPost('id_kategori'),
            'nama'              => $this->request->getPost('nama'),
            'jenis'             => $this->request->getPost('jenis'),
            'kategori'          => $this->request->getPost('kategori'),
            'nama_rekening'     => $this->request->getPost('nama_rekening'),
            'nomor_rekening'    => $this->request->getPost('nomor_rekening'),
            'deskripsi'         => $this->request->getPost('deskripsi'),
            'gateway_kode'      => $this->request->getPost('gateway_kode') ?: '-',
            'gateway_instruksi' => $this->request->getPost('gateway_instruksi') ?: '0',
            'logo'              => $this->request->getPost('logo'),
            'hasil'             => $this->request->getPost('hasil'),
            'status'            => $this->request->getPost('status'),
            'status_gateway'    => $this->request->getPost('status_gateway')
        ];

        if ($this->platformModel->update($id, $data)) {
            return redirect()->to('/master/platform')->with('success', 'Data platform berhasil diperbarui');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data platform');
        }
    }

    /**
     * Delete platform
     */
    public function delete($id)
    {
        $platform = $this->platformModel->find($id);
        
        if (!$platform) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Platform dengan ID $id tidak ditemukan");
        }

        if ($this->platformModel->delete($id)) {
            return redirect()->to('/master/platform')->with('success', 'Data platform berhasil dihapus');
        } else {
            return redirect()->to('/master/platform')->with('error', 'Gagal menghapus data platform');
        }
    }

    /**
     * Show platform detail
     */
    public function show($id)
    {
        $platform = $this->platformModel->select('tbl_m_platform.*, tbl_m_kategori.kategori as kategori_name')
                                        ->join('tbl_m_kategori', 'tbl_m_kategori.id = tbl_m_platform.id_kategori', 'left')
                                        ->find($id);
        
        if (!$platform) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Platform dengan ID $id tidak ditemukan");
        }

        $data = [
            'title'    => 'Detail Platform',
            'platform' => $platform
        ];

        return $this->view($this->theme->getThemePath() . '/master/platform/show', $data);
    }
}