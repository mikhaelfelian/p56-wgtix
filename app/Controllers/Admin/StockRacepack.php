<?php

/**
 * StockRacepack Controller
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: August 7, 2025
 * Github: github.com/mikhaelfelian
 * Description: Controller for managing stock racepack data
 */

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StockRacepackModel;
use App\Models\RacepackModel;
use App\Models\UkuranModel;
use CodeIgniter\HTTP\ResponseInterface;

class StockRacepack extends BaseController
{
    protected $stockModel;
    protected $racepackModel;
    protected $ukuranModel;

    public function __construct()
    {
        parent::__construct();
        $this->stockModel = new StockRacepackModel();
        $this->racepackModel = new RacepackModel();
        $this->ukuranModel = new UkuranModel();
    }

    /**
     * Display a listing of stock
     */
    public function index()
    {
        $search = $this->request->getGet('search');
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 10;

        $query = $this->stockModel;

        if ($search) {
            $query = $query->searchStock($search);
        } else {
            $query = $query->getStockWithDetails();
        }

        $stock = $query->paginate($perPage);
        $pager = $this->stockModel->pager;

        $data = [
            'title'       => 'Stock Racepack',
            'stock'       => $stock,
            'pager'       => $pager,
            'keyword'     => $search,
            'currentPage' => $page,
            'perPage'     => $perPage
        ];

        return $this->view($this->theme->getThemePath() . '/admin/stock_racepack/index', $data);
    }

    /**
     * Show the form for creating a new stock
     */
    public function create()
    {
        $racepackOptions = $this->racepackModel->getDropdownOptions();
        $ukuranOptions = $this->ukuranModel->getDropdownOptions();

        $data = [
            'title'           => 'Tambah Stock Racepack',
            'racepackOptions' => $racepackOptions,
            'ukuranOptions'   => $ukuranOptions
        ];

        return $this->view($this->theme->getThemePath() . '/admin/stock_racepack/create', $data);
    }

    /**
     * Store a newly created stock
     */
    public function store()
    {
        $rules = [
            'id_racepack'   => 'required|integer',
            'id_ukuran'     => 'required|integer',
            'stok_masuk'    => 'required|integer',
            'stok_keluar'   => 'required|integer',
            'minimal_stok'  => 'required|integer',
            'status'        => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $stokMasuk = (int)$this->request->getPost('stok_masuk');
        $stokKeluar = (int)$this->request->getPost('stok_keluar');
        $stokTersedia = $stokMasuk - $stokKeluar;

        $data = [
            'id_racepack'   => $this->request->getPost('id_racepack'),
            'id_ukuran'     => $this->request->getPost('id_ukuran'),
            'stok_masuk'    => $stokMasuk,
            'stok_keluar'   => $stokKeluar,
            'stok_tersedia' => $stokTersedia,
            'minimal_stok'  => $this->request->getPost('minimal_stok'),
            'status'        => $this->request->getPost('status'),
            'id_user'       => $this->data['user']->id
        ];

        if ($this->stockModel->insert($data)) {
            return redirect()->to('/admin/stock-racepack')
                           ->with('success', 'Stock racepack berhasil ditambahkan');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Gagal menambahkan stock racepack');
    }

    /**
     * Show the form for editing the specified stock
     */
    public function edit($id = null)
    {
        $stock = $this->stockModel->find($id);

        if (!$stock) {
            return redirect()->to('/admin/stock-racepack')
                           ->with('error', 'Stock racepack tidak ditemukan');
        }

        $racepackOptions = $this->racepackModel->getDropdownOptions();
        $ukuranOptions = $this->ukuranModel->getDropdownOptions();

        $data = [
            'title'           => 'Edit Stock Racepack',
            'stock'           => $stock,
            'racepackOptions' => $racepackOptions,
            'ukuranOptions'   => $ukuranOptions
        ];

        return $this->view($this->theme->getThemePath() . '/admin/stock_racepack/edit', $data);
    }

    /**
     * Update the specified stock
     */
    public function update($id = null)
    {
        $stock = $this->stockModel->find($id);

        if (!$stock) {
            return redirect()->to('/admin/stock-racepack')
                           ->with('error', 'Stock racepack tidak ditemukan');
        }

        $rules = [
            'id_racepack'   => 'required|integer',
            'id_ukuran'     => 'required|integer',
            'stok_masuk'    => 'required|integer',
            'stok_keluar'   => 'required|integer',
            'minimal_stok'  => 'required|integer',
            'status'        => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $stokMasuk = (int)$this->request->getPost('stok_masuk');
        $stokKeluar = (int)$this->request->getPost('stok_keluar');
        $stokTersedia = $stokMasuk - $stokKeluar;

        $data = [
            'id_racepack'   => $this->request->getPost('id_racepack'),
            'id_ukuran'     => $this->request->getPost('id_ukuran'),
            'stok_masuk'    => $stokMasuk,
            'stok_keluar'   => $stokKeluar,
            'stok_tersedia' => $stokTersedia,
            'minimal_stok'  => $this->request->getPost('minimal_stok'),
            'status'        => $this->request->getPost('status')
        ];

        if ($this->stockModel->update($id, $data)) {
            return redirect()->to('/admin/stock-racepack')
                           ->with('success', 'Stock racepack berhasil diperbarui');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Gagal memperbarui stock racepack');
    }

    /**
     * Remove the specified stock
     */
    public function delete($id = null)
    {
        $stock = $this->stockModel->find($id);

        if (!$stock) {
            return redirect()->to('/admin/stock-racepack')
                           ->with('error', 'Stock racepack tidak ditemukan');
        }

        if ($this->stockModel->delete($id)) {
            return redirect()->to('/admin/stock-racepack')
                           ->with('success', 'Stock racepack berhasil dihapus');
        }

        return redirect()->to('/admin/stock-racepack')
                       ->with('error', 'Gagal menghapus stock racepack');
    }

    /**
     * Show low stock report
     */
    public function lowStock()
    {
        $lowStockItems = $this->stockModel->getLowStockItems();

        $data = [
            'title'        => 'Low Stock Report',
            'lowStockItems' => $lowStockItems
        ];

        return $this->view($this->theme->getThemePath() . '/admin/stock_racepack/low_stock', $data);
    }
} 