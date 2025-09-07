<?php

/**
 * Peserta Controller
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Controller for managing peserta (participants) data
 */

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PesertaModel;
use App\Models\KelompokPesertaModel;
use App\Models\KategoriModel;
use App\Models\PlatformModel;
use CodeIgniter\HTTP\ResponseInterface;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class Peserta extends BaseController
{
    protected $pesertaModel;
    protected $kelompokModel;
    protected $kategoriModel;
    protected $platformModel;

    public function __construct()
    {
        parent::__construct();
        $this->pesertaModel   = new PesertaModel();
        $this->kelompokModel  = new KelompokPesertaModel();
        $this->kategoriModel  = new KategoriModel();
        $this->platformModel  = new PlatformModel();
    }

    // ========================= Peserta =========================

    /**
     * Display a listing of participants (Data Peserta)
     */
    public function daftar()
    {
        $search  = $this->request->getGet('search');
        $perPage = 10;

        // Fix pagination: get correct page number from query string
        $page = (int) ($this->request->getGet('page_peserta') ?? $this->request->getGet('page') ?? 1);

        // You can add more filters here if needed, e.g. $idKelompok, $status
        $peserta = $this->pesertaModel->getPesertaWithFilters($perPage, $page, $search);

        $pager = $this->pesertaModel->pager;

        // Get latest participant for QR code display in success message
        $latestPeserta = null;
        if (session('generated_code')) {
            $latestPeserta = $this->pesertaModel->orderBy('id', 'DESC')->first();
        }

        $data = [
            'title'         => 'Data Peserta',
            'peserta'       => $peserta,
            'pager'         => $pager,
            'keyword'       => $search,
            'currentPage'   => $page,
            'perPage'       => $perPage,
            'latestPeserta' => $latestPeserta
        ];

        return $this->view($this->theme->getThemePath() . '/admin/peserta/daftar', $data);
    }

    /**
     * Display the specified participant
     */
    public function show($id = null)
    {
        $peserta = $this->pesertaModel->getPesertaWithGroupQuery()->where('tbl_peserta.id', $id)->first();

        if (!$peserta) {
            return redirect()->to('/admin/peserta/daftar')
                ->with('error', 'Peserta tidak ditemukan');
        }

        $data = [
            'title'   => 'Detail Peserta',
            'peserta' => $peserta
        ];

        return $this->view($this->theme->getThemePath() . '/admin/peserta/show', $data);
    }

    /**
     * Show the form for creating a new participant
     */
    public function create()
    {
        $kelompokOptions = $this->kelompokModel->getDropdownOptions();
        $kategoriOptions = $this->kategoriModel->getActiveCategories();
        $platformOptions = $this->platformModel->getDropdownOptions();

        $data = [
            'title'           => 'Tambah Peserta',
            'kelompokOptions' => $kelompokOptions,
            'kategoriOptions' => $kategoriOptions,
            'platformOptions' => $platformOptions
        ];

        return $this->view($this->theme->getThemePath() . '/admin/peserta/create', $data);
    }

    /**
     * Store a newly created participant
     */
    public function store()
    {
        $rules = [
            'nama_lengkap'  => 'required|max_length[100]',
            // 'jenis_kelamin' => 'required|in_list[L,P]',
            // 'tempat_lahir'  => 'permit_empty|max_length[50]',
            // 'tanggal_lahir' => 'permit_empty|valid_date',
            // 'alamat'        => 'permit_empty',
            // 'no_hp'         => 'permit_empty|max_length[15]',
            // 'email'         => 'permit_empty|valid_email|max_length[100]',
            // 'id_kelompok'   => 'permit_empty|integer',
            // 'id_kategori'   => 'permit_empty|integer',
            // 'id_platform'   => 'permit_empty|integer',
            // 'status'        => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Generate unique 8-digit registration number
        $kode_peserta = $this->generateUniqueKodePeserta();

        $data = [
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'kode_peserta'  => $kode_peserta,
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir'  => $this->request->getPost('tempat_lahir') ?: null,
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir') ?: null,
            'alamat'        => $this->request->getPost('alamat') ?: null,
            'no_hp'         => $this->request->getPost('no_hp') ?: null,
            'email'         => $this->request->getPost('email') ?: null,
            'id_kelompok'   => $this->request->getPost('id_kelompok') ?: null,
            'id_kategori'   => $this->request->getPost('id_kategori') ?: null,
            'id_platform'   => $this->request->getPost('id_platform') ?: null,
            'status'        => '1',
            'id_user'       => $this->data['user']->id
        ];

        if ($this->pesertaModel->insert($data)) {
            // Get the inserted ID
            $insertedId = $this->pesertaModel->insertID();

            // Generate QR code content (ID and created_at)
            $qrContent = $insertedId . '|' . date('Y-m-d H:i:s');

            // Generate QR code as base64
            $qrCode = new QrCode($qrContent);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            // Convert to base64
            $qrBase64 = base64_encode($result->getString());

            // Update the record with QR code
            $this->pesertaModel->update($insertedId, ['qr_code' => $qrBase64]);

            // Check if platform is selected and is Tripay gateway
            $selectedPlatformId = $this->request->getPost('id_platform');
            if ($selectedPlatformId) {
                $platform = $this->platformModel->find($selectedPlatformId);
                if ($platform && $platform->status_gateway == 1) {
                    // Process Tripay payment
                    $tripayResult = $this->processTripayPayment($insertedId, $data, $kode_peserta, $platform);
                    if ($tripayResult['success']) {
                        return redirect()->to($tripayResult['checkout_url']);
                    } else {
                        return redirect()->back()->with('error', 'Gagal membuat transaksi Tripay: ' . $tripayResult['message']);
                    }
                }
            }

            return redirect()->to('/admin/peserta/daftar')
                ->with('success', 'Peserta berhasil ditambahkan dengan kode: ' . $kode_peserta)
                ->with('generated_code', $kode_peserta);
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal menambahkan peserta');
    }

    /**
     * Generate unique 8-digit registration number
     * 
     * @return string
     */
    private function generateUniqueKodePeserta()
    {
        do {
            // Generate 8-digit random number
            $kode = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
        } while ($this->pesertaModel->isCodeExists($kode));

        return $kode;
    }

    /**
     * Process Tripay payment
     * 
     * @param int $insertedId
     * @param array $data
     * @param string $kode_peserta
     * @param object $platform
     * @return array
     */
    private function processTripayPayment($insertedId, $data, $kode_peserta, $platform)
    {
        // --- SETTINGAN TRIPAY ---
        $apiKey       = getenv('TRIPAY_API_KEY');
        $privateKey   = getenv('TRIPAY_PRIVATE_KEY');
        $merchantCode = getenv('TRIPAY_MERCHANT_CODE');
        $tripayUrl    = getenv('TRIPAY_BASE_URL') . '/transaction/create';

        // Nominal biaya pendaftaran
        $amount = 50000; // contoh Rp50.000

        // Data transaksi
        $merchantRef = 'REG-' . $kode_peserta; // kode unik transaksi
        $signature = hash_hmac('sha256', $merchantCode . $merchantRef . $amount, $privateKey);

        $dataTripay = [
            'method'         => $platform->gateway_kode, // kode metode pembayaran Tripay
            'merchant_ref'   => $merchantRef,
            'amount'         => $amount,
            'customer_name'  => $data['nama_lengkap'],
            'customer_email' => $data['email'] ?? 'noemail@example.com',
            'customer_phone' => $data['no_hp'] ?? '0800000000',
            'order_items'    => [
                [
                    'sku'      => 'REG-001',
                    'name'     => 'Biaya Pendaftaran',
                    'price'    => $amount,
                    'quantity' => 1
                ]
            ],
            'return_url'  => base_url('admin/peserta/payment-success'),
            'expired_time'=> time() + (24 * 60 * 60),
            'signature'   => $signature
        ];

        // Kirim ke Tripay
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $tripayUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $apiKey],
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($dataTripay)
        ]);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return [
                'success' => false,
                'message' => 'CURL Error: ' . $err
            ];
        }

        $result = json_decode($response, true);

        // Simpan payment link & reference ke database peserta
        if (isset($result['success']) && $result['success'] === true) {
            $this->pesertaModel->update($insertedId, [
                'tripay_reference' => $result['data']['reference'],
                'tripay_pay_url'   => $result['data']['checkout_url']
            ]);

            return [
                'success' => true,
                'checkout_url' => $result['data']['checkout_url']
            ];
        } else {
            return [
                'success' => false,
                'message' => json_encode($result)
            ];
        }
    }

    /**
     * Show the form for editing the specified participant
     */
    public function edit($id = null)
    {
        $peserta = $this->pesertaModel->find($id);

        if (!$peserta) {
            return redirect()->to('/admin/peserta/daftar')
                ->with('error', 'Peserta tidak ditemukan');
        }

        $kelompokOptions = $this->kelompokModel->getDropdownOptions();
        $kategoriOptions = $this->kategoriModel->getActiveCategories();

        $data = [
            'title'           => 'Edit Peserta',
            'peserta'         => $peserta,
            'kelompokOptions' => $kelompokOptions,
            'kategoriOptions' => $kategoriOptions
        ];

        return $this->view($this->theme->getThemePath() . '/admin/peserta/edit', $data);
    }

    /**
     * Update the specified participant
     */
    public function update($id = null)
    {
        $peserta = $this->pesertaModel->find($id);

        if (!$peserta) {
            return redirect()->to('/admin/peserta/daftar')
                ->with('error', 'Peserta tidak ditemukan');
        }

        $rules = [
            'nama_lengkap'  => 'required|max_length[100]',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'tempat_lahir'  => 'permit_empty|max_length[50]',
            'tanggal_lahir' => 'permit_empty|valid_date',
            'alamat'        => 'permit_empty',
            'no_hp'         => 'permit_empty|max_length[15]',
            'email'         => 'permit_empty|valid_email|max_length[100]',
            'id_kelompok'   => 'permit_empty|integer',
            'id_kategori'   => 'permit_empty|integer',
            'status'        => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir'  => $this->request->getPost('tempat_lahir') ?: null,
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir') ?: null,
            'alamat'        => $this->request->getPost('alamat') ?: null,
            'no_hp'         => $this->request->getPost('no_hp') ?: null,
            'email'         => $this->request->getPost('email') ?: null,
            'id_kelompok'   => $this->request->getPost('id_kelompok') ?: null,
            'id_kategori'   => $this->request->getPost('id_kategori') ?: null,
            'status'        => $this->request->getPost('status')
        ];

        if ($this->pesertaModel->update($id, $data)) {
            return redirect()->to('/admin/peserta/daftar')
                ->with('success', 'Peserta berhasil diperbarui');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal memperbarui peserta');
    }

    /**
     * Remove the specified participant
     */
    public function delete($id = null)
    {
        $peserta = $this->pesertaModel->find($id);

        if (!$peserta) {
            return redirect()->to('/admin/peserta/daftar')
                ->with('error', 'Peserta tidak ditemukan');
        }

        if ($this->pesertaModel->delete($id)) {
            return redirect()->to('/admin/peserta/daftar')
                ->with('success', 'Peserta berhasil dihapus');
        }

        return redirect()->to('/admin/peserta/daftar')
            ->with('error', 'Gagal menghapus peserta');
    }

    /**
     * Display registrations (Pendaftaran)
     */
    public function pendaftaran()
    {
        $search  = $this->request->getGet('search');
        $status  = $this->request->getGet('status');
        $page    = $this->request->getGet('page') ?? 1;
        $perPage = 10;

        $query = $this->pesertaModel->db->table('tbl_pendaftaran')
            ->select('tbl_pendaftaran.*, tbl_peserta.nama_lengkap as nama_peserta, tbl_peserta.kode_peserta, tbl_m_jadwal.nama_jadwal, tbl_m_jadwal.tanggal_mulai, tbl_m_jadwal.tanggal_selesai')
            ->join('tbl_peserta', 'tbl_peserta.id = tbl_pendaftaran.id_peserta', 'left')
            ->join('tbl_m_jadwal', 'tbl_m_jadwal.id = tbl_pendaftaran.id_jadwal', 'left')
            ->where('tbl_pendaftaran.status', '1');

        if ($search) {
            $query = $query->groupStart()
                ->like('tbl_pendaftaran.kode_pendaftaran', $search)
                ->orLike('tbl_peserta.nama_lengkap', $search)
                ->orLike('tbl_m_jadwal.nama_jadwal', $search)
                ->groupEnd();
        }

        if ($status) {
            $query = $query->where('tbl_pendaftaran.status_pendaftaran', $status);
        }

        $pendaftaran = $query->orderBy('tbl_pendaftaran.tanggal_pendaftaran', 'DESC')
            ->get($perPage, ($page - 1) * $perPage)
            ->getResult();

        $data = [
            'title'       => 'Pendaftaran Peserta',
            'pendaftaran' => $pendaftaran,
            'keyword'     => $search,
            'status'      => $status,
            'currentPage' => $page,
            'perPage'     => $perPage
        ];

        return $this->view($this->theme->getThemePath() . '/admin/peserta/pendaftaran', $data);
    }

    /**
     * Display participant reports (Rekap Peserta)
     */
    public function rekap()
    {
        // Get statistics
        $pesertaStats   = $this->pesertaModel->getPesertaStats();
        $kelompokStats  = $this->kelompokModel->getKelompokStats();

        // Get recent registrations
        $recentPendaftaran = $this->pesertaModel->db->table('tbl_pendaftaran')
            ->select('tbl_pendaftaran.*, tbl_peserta.nama_lengkap as nama_peserta, tbl_m_jadwal.nama_jadwal')
            ->join('tbl_peserta', 'tbl_peserta.id = tbl_pendaftaran.id_peserta', 'left')
            ->join('tbl_m_jadwal', 'tbl_m_jadwal.id = tbl_pendaftaran.id_jadwal', 'left')
            ->where('tbl_pendaftaran.status', '1')
            ->orderBy('tbl_pendaftaran.tanggal_pendaftaran', 'DESC')
            ->limit(10)
            ->get()
            ->getResult();

        // Get participants by gender
        $pesertaByGender = $this->pesertaModel->db->table('tbl_peserta')
            ->select('jenis_kelamin, COUNT(*) as jumlah')
            ->where('status', '1')
            ->groupBy('jenis_kelamin')
            ->get()
            ->getResult();

        // Get registrations by status
        $pendaftaranByStatus = $this->pesertaModel->db->table('tbl_pendaftaran')
            ->select('status_pendaftaran, COUNT(*) as jumlah')
            ->where('status', '1')
            ->groupBy('status_pendaftaran')
            ->get()
            ->getResult();

        $data = [
            'title'                => 'Rekap Peserta',
            'pesertaStats'         => $pesertaStats,
            'kelompokStats'        => $kelompokStats,
            'recentPendaftaran'    => $recentPendaftaran,
            'pesertaByGender'      => $pesertaByGender,
            'pendaftaranByStatus'  => $pendaftaranByStatus
        ];

        return $this->view($this->theme->getThemePath() . '/admin/peserta/rekap', $data);
    }

    // ========================= Kelompok =========================

    /**
     * Display participant groups (Kelompok Peserta)
     */
    public function kelompok()
    {
        $search  = $this->request->getGet('search');
        $page    = $this->request->getGet('page') ?? 1;
        $perPage = 10;

        if ($search) {
            $kelompok = $this->kelompokModel->searchKelompokQuery($search)->paginate($perPage);
        } else {
            $kelompok = $this->kelompokModel->getKelompokWithMemberCountQuery()->paginate($perPage);
        }
        
        $pager = $this->kelompokModel->pager;

        $data = [
            'title'       => 'Kelompok Peserta',
            'kelompok'    => $kelompok,
            'pager'       => $pager,
            'keyword'     => $search,
            'currentPage' => $page,
            'perPage'     => $perPage
        ];

        return $this->view($this->theme->getThemePath() . '/admin/peserta/kelompok', $data);
    }

    /**
     * Show the form for creating a new kelompok
     */
    public function kelompokCreate()
    {
        $data = [
            'title' => 'Tambah Kelompok'
        ];

        return $this->view($this->theme->getThemePath() . '/admin/peserta/kelompok_create', $data);
    }

    /**
     * Store a newly created kelompok
     */
    public function kelompokStore()
    {
        $rules = [
            'nama_kelompok' => 'required|max_length[100]',
            'kode_kelompok' => 'required|max_length[20]',
            'deskripsi'     => 'permit_empty',
            'kapasitas'     => 'permit_empty|integer',
            'status'        => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_kelompok' => $this->request->getPost('nama_kelompok'),
            'kode_kelompok' => $this->request->getPost('kode_kelompok'),
            'deskripsi'     => $this->request->getPost('deskripsi') ?: null,
            'kapasitas'     => $this->request->getPost('kapasitas') ?: null,
            'status'        => $this->request->getPost('status'),
            'id_user'       => $this->data['user']->id
        ];

        // Check if kode already exists
        if ($this->kelompokModel->isCodeExists($data['kode_kelompok'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kode kelompok sudah ada');
        }

        if ($this->kelompokModel->insert($data)) {
            return redirect()->to('/admin/peserta/kelompok')
                ->with('success', 'Kelompok berhasil ditambahkan');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal menambahkan kelompok');
    }

    /**
     * Show the form for editing the specified kelompok
     */
    public function kelompokEdit($id = null)
    {
        $kelompok = $this->kelompokModel->find($id);

        if (!$kelompok) {
            return redirect()->to('/admin/peserta/kelompok')
                ->with('error', 'Kelompok tidak ditemukan');
        }

        $data = [
            'title'    => 'Edit Kelompok',
            'kelompok' => $kelompok
        ];

        return $this->view($this->theme->getThemePath() . '/admin/peserta/kelompok_edit', $data);
    }

    /**
     * Update the specified kelompok
     */
    public function kelompokUpdate($id = null)
    {
        $kelompok = $this->kelompokModel->find($id);

        if (!$kelompok) {
            return redirect()->to('/admin/peserta/kelompok')
                ->with('error', 'Kelompok tidak ditemukan');
        }

        $rules = [
            'nama_kelompok' => 'required|max_length[100]',
            'kode_kelompok' => 'required|max_length[20]',
            'deskripsi'     => 'permit_empty',
            'kapasitas'     => 'permit_empty|integer',
            'status'        => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_kelompok' => $this->request->getPost('nama_kelompok'),
            'kode_kelompok' => $this->request->getPost('kode_kelompok'),
            'deskripsi'     => $this->request->getPost('deskripsi') ?: null,
            'kapasitas'     => $this->request->getPost('kapasitas') ?: null,
            'status'        => $this->request->getPost('status')
        ];

        // Check if kode already exists (excluding current record)
        if ($this->kelompokModel->isCodeExists($data['kode_kelompok'], $id)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kode kelompok sudah ada');
        }

        if ($this->kelompokModel->update($id, $data)) {
            return redirect()->to('/admin/peserta/kelompok')
                ->with('success', 'Kelompok berhasil diperbarui');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal memperbarui kelompok');
    }

    /**
     * Remove the specified kelompok
     */
    public function kelompokDelete($id = null)
    {
        $kelompok = $this->kelompokModel->find($id);

        if (!$kelompok) {
            return redirect()->to('/admin/peserta/kelompok')
                ->with('error', 'Kelompok tidak ditemukan');
        }

        if ($this->kelompokModel->delete($id)) {
            return redirect()->to('/admin/peserta/kelompok')
                ->with('success', 'Kelompok berhasil dihapus');
        }

        return redirect()->to('/admin/peserta/kelompok')
            ->with('error', 'Gagal menghapus kelompok');
    }

    // ========================= Payment =========================

    /**
     * Handle payment success callback from Tripay
     */
    public function paymentSuccess()
    {
        $data = [
            'title'        => 'Payment Success',
            'Pengaturan'   => $this->pengaturan,
            'user'         => $this->ionAuth->user()->row(),
            'isMenuActive' => isMenuActive('dashboard') ? 'active' : ''
        ];

        return view($this->theme->getThemePath() . '/admin/peserta/payment_success', $data);
    }
}