<?php
/**
 * Dashboard Controller
 * 
 * Created by Mikhael Felian Waskito
 * Created at 2024-01-09
 */

namespace App\Controllers;

use App\Models\PesertaModel;
use App\Models\RacepackModel;
use App\Models\StockRacepackModel;
use App\Models\KategoriModel;
use App\Models\KategoriRacepackModel;
use App\Models\KelompokPesertaModel;
use App\Models\PendaftaranModel;
use App\Models\JadwalModel;
use App\Models\UkuranModel;
use App\Models\PlatformModel;

class Dashboard extends BaseController
{
    protected $pesertaModel;
    protected $racepackModel;
    protected $stockRacepackModel;
    protected $kategoriModel;
    protected $kategoriRacepackModel;
    protected $kelompokPesertaModel;
    protected $pendaftaranModel;
    protected $jadwalModel;
    protected $ukuranModel;
    protected $platformModel;

    public function __construct()
    {
        $this->pesertaModel = new PesertaModel();
        $this->racepackModel = new RacepackModel();
        $this->stockRacepackModel = new StockRacepackModel();
        $this->kategoriModel = new KategoriModel();
        $this->kategoriRacepackModel = new KategoriRacepackModel();
        $this->kelompokPesertaModel = new KelompokPesertaModel();
        $this->pendaftaranModel = new PendaftaranModel();
        $this->jadwalModel = new JadwalModel();
        $this->ukuranModel = new UkuranModel();
        $this->platformModel = new PlatformModel();
    }
    
    public function index()
    {
        // Get statistics for dashboard
        $stats = [
            'total_peserta' => $this->pesertaModel->countAll(),
            'total_racepack' => $this->racepackModel->countAll(),
            'total_kategori' => $this->kategoriModel->countAll(),
            'total_kelompok' => $this->kelompokPesertaModel->countAll(),
            'total_pendaftaran' => $this->pendaftaranModel->countAll(),
            'total_jadwal' => $this->jadwalModel->countAll(),
            'total_ukuran' => $this->ukuranModel->countAll(),
            'total_platform' => $this->platformModel->countAll(),
            'total_kategori_racepack' => $this->kategoriRacepackModel->countAll(),
            'low_stock_items' => $this->stockRacepackModel->getLowStockItems(),
            'active_peserta' => $this->pesertaModel->where('status', 1)->countAllResults(),
            'active_racepack' => $this->racepackModel->where('status', 1)->countAllResults(),
        ];

        // Get recent data
        $recentData = [
            'recent_peserta' => $this->pesertaModel->orderBy('created_at', 'DESC')->limit(5)->find(),
            'recent_racepack' => $this->racepackModel->orderBy('created_at', 'DESC')->limit(5)->find(),
            'recent_pendaftaran' => $this->pendaftaranModel->orderBy('created_at', 'DESC')->limit(5)->find(),
        ];

        $data = [
            'title'        => 'Dashboard',
            'Pengaturan'   => $this->pengaturan,
            'user'         => $this->ionAuth->user()->row(),
            'isMenuActive' => isMenuActive('dashboard') ? 'active' : '',
            'stats'        => $stats,
            'recentData'   => $recentData
        ];

        return view($this->theme->getThemePath() . '/dashboard', $data);
    }
} 