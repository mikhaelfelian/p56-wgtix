<?php

/**
 * Frontend Controller
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Controller for front-end participant registration and payment
 */

namespace App\Controllers;

use App\Models\PesertaModel;
use App\Models\KategoriModel;
use App\Models\PlatformModel;
use App\Models\KelompokPesertaModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class Frontend extends BaseController
{
    protected $pesertaModel;
    protected $kategoriModel;
    protected $platformModel;
    protected $kelompokModel;

    public function __construct()
    {
        parent::__construct();
        $this->pesertaModel = new PesertaModel();
        $this->kategoriModel = new KategoriModel();
        $this->platformModel = new PlatformModel();
        $this->kelompokModel = new KelompokPesertaModel();
    }

    /**
     * Display the registration form
     */
    public function index()
    {
        $kategoriOptions = $this->kategoriModel->getActiveCategories();
        $platformOptions = $this->platformModel->getByStatus(1);
        $kelompokOptions = $this->kelompokModel->getDropdownOptions();

        $data = [
            'title' => 'Pendaftaran Peserta',
            'Pengaturan' => $this->pengaturan,
            'kategoriOptions' => $kategoriOptions,
            'platformOptions' => $platformOptions,
            'kelompokOptions' => $kelompokOptions
        ];

        return view('admin-lte-3/frontend/registration', $data);
    }

    /**
     * Handle registration form submission
     */
    public function register()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $rules = [
            'nama_lengkap' => 'required|max_length[100]',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'tempat_lahir' => 'permit_empty|max_length[50]',
            'tanggal_lahir' => 'permit_empty|valid_date',
            'alamat' => 'permit_empty',
            'no_hp' => 'permit_empty|max_length[15]',
            'email' => 'permit_empty|valid_email|max_length[100]',
            'id_kelompok' => 'permit_empty|integer',
            'id_kategori' => 'permit_empty|integer',
            'id_platform' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Generate unique 8-digit registration number
        $kode_peserta = $this->generateUniqueKodePeserta();

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'kode_peserta' => $kode_peserta,
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir' => $this->request->getPost('tempat_lahir') ?: null,
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir') ?: null,
            'alamat' => $this->request->getPost('alamat') ?: null,
            'no_hp' => $this->request->getPost('no_hp') ?: null,
            'email' => $this->request->getPost('email') ?: null,
            'id_kelompok' => $this->request->getPost('id_kelompok') ?: null,
            'id_kategori' => $this->request->getPost('id_kategori') ?: null,
            'id_platform' => $this->request->getPost('id_platform') ?: null,
            'status' => '1',
            'id_user' => 1 // Default user ID for frontend registration
        ];

        if ($this->pesertaModel->insert($data)) {
            $insertedId = $this->pesertaModel->insertID();

            // Generate QR code
            $qrContent = $insertedId . '|' . date('Y-m-d H:i:s');
            $qrCode = new QrCode($qrContent);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            $qrBase64 = base64_encode($result->getString());

            // Update with QR code
            $this->pesertaModel->update($insertedId, ['qr_code' => $qrBase64]);

            // Check if platform is selected for payment
            $selectedPlatformId = $this->request->getPost('id_platform');
            if ($selectedPlatformId) {
                $platform = $this->platformModel->find($selectedPlatformId);
                if ($platform && $platform->status_gateway == 1) {
                    // Process payment gateway
                    $paymentResult = $this->processTripayPayment($insertedId, $data, $kode_peserta, $platform);
                    if ($paymentResult['success']) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Registration successful',
                            'data' => [
                                'kode_peserta' => $kode_peserta,
                                'payment_url' => $paymentResult['checkout_url'],
                                'qr_code' => $qrBase64,
                                'redirect_to_payment' => true
                            ]
                        ]);
                    } else {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Registration successful but payment failed: ' . $paymentResult['message']
                        ]);
                    }
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Registration successful',
                'data' => [
                    'kode_peserta' => $kode_peserta,
                    'qr_code' => $qrBase64,
                    'redirect_to_payment' => false
                ]
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to register participant'
        ]);
    }

    /**
     * Generate unique 8-digit registration number
     */
    private function generateUniqueKodePeserta()
    {
        do {
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

        // Check if Tripay configuration exists
        if (!$apiKey || !$privateKey || !$merchantCode) {
            return [
                'success' => false,
                'message' => 'Payment gateway configuration not found'
            ];
        }

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
                    'name'     => 'Biaya Pendaftaran Event',
                    'price'    => $amount,
                    'quantity' => 1
                ]
            ],
            'return_url'  => base_url('frontend/payment-success/' . $insertedId),
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
     * Payment success callback
     */
    public function paymentSuccess($pesertaId = null)
    {
        if (!$pesertaId) {
            return redirect()->to('/frontend')->with('error', 'Invalid payment request');
        }

        $peserta = $this->pesertaModel->find($pesertaId);
        if (!$peserta) {
            return redirect()->to('/frontend')->with('error', 'Participant not found');
        }

        $data = [
            'title' => 'Payment Success',
            'Pengaturan' => $this->pengaturan,
            'peserta' => $peserta
        ];

        return view('admin-lte-3/frontend/payment_success', $data);
    }

    /**
     * Payment failure callback
     */
    public function paymentFailed($pesertaId = null)
    {
        if (!$pesertaId) {
            return redirect()->to('/frontend')->with('error', 'Invalid payment request');
        }

        $peserta = $this->pesertaModel->find($pesertaId);
        if (!$peserta) {
            return redirect()->to('/frontend')->with('error', 'Participant not found');
        }

        $data = [
            'title' => 'Payment Failed',
            'Pengaturan' => $this->pengaturan,
            'peserta' => $peserta
        ];

        return view('admin-lte-3/frontend/payment_failed', $data);
    }
}
