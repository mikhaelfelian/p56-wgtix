<?php
/**
 * Created by:
 * Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * GitHub: https://github.com/mikhaelfelian
 * 2025-01-12
 * 
 * Auth Controller
 */

namespace App\Controllers;

use ReCaptcha\ReCaptcha;

class Auth extends BaseController
{
    protected $recaptcha;
    
    public function __construct()
    {
        $recaptchaModel = new \App\Models\ReCaptchaModel();
        $this->recaptcha = new ReCaptcha($recaptchaModel->getSecretKey());
    }

    public function index()
    {
        $data = [
            'title'         => 'Dashboard',
            'Pengaturan'    => $this->pengaturan
        ];

        if ($this->ionAuth->loggedIn()) {
            return redirect()->to('admin/dashboard');
        }
        return $this->login();
    }

    public function login()
    {
        if ($this->ionAuth->loggedIn()) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title'         => 'Login',
            'Pengaturan'    => $this->pengaturan
        ];

        // Tetap gunakan theme lama untuk admin login
        return $this->view($this->theme->getThemePath() . '/login/login', $data);
    }

    public function cek_login()
    {
        $validasi = \Config\Services::validation();
        
        $username = $this->request->getVar('user');
        $password = $this->request->getVar('pass');
        $remember = $this->request->getVar('ingat');
        
        $recaptchaResponse = $this->request->getVar('recaptcha_response');
        
        # Verify reCAPTCHA
        $recaptcha = $this->recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])
                                    ->setScoreThreshold(0.5)
                                    ->verify($recaptchaResponse, $_SERVER['REMOTE_ADDR']);

        if (!$recaptcha->isSuccess()) {
            return redirect()->back()->with('toastr', [
                'type' => 'error', 
                'message' => 'reCAPTCHA verification failed. Please try again.'
            ]);
        }

        $rules = [
            'user' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Username is required',
                    'min_length' => 'Username must be at least 3 characters'
                ]
            ],
            'pass' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password is required'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            $errors = $validasi->getErrors();
            $error_message = implode('<br>', $errors);
            return redirect()->back()->with('toastr', [
                'type' => 'error',
                'message' => $error_message
            ]);
        }

        $rememberMe = ($remember == '1' ? true : false);
        $login = $this->ionAuth->login($username, $password, $rememberMe);

        if (!$login) {
            return redirect()->back()->with('toastr', [
                'type' => 'error',
                'message' => 'Invalid username or password'
            ]);
        }

        return redirect()->to('/admin/dashboard')->with('toastr', [
            'type' => 'success',
            'message' => 'Login successful!'
        ]);
    }

    /**
     * User login page (frontend) using da-theme
     */
    public function login_user()
    {
        if ($this->ionAuth->loggedIn()) {
            return redirect()->to('/');
        }

        $data = [
            'title'         => 'Login Pengguna',
            'Pengaturan'    => $this->pengaturan,
        ];

        // Menggunakan theme da-theme untuk user login
        return $this->view('da-theme/auth/login', $data);
    }

    /**
     * Proses login user (frontend) menggunakan da-theme
     */
    public function cek_login_user()
    {
        $validasi = \Config\Services::validation();
        
        $username = $this->request->getVar('user');
        $password = $this->request->getVar('pass');
        $remember = $this->request->getVar('ingat');
        $recaptchaResponse = $this->request->getVar('recaptcha_response');
        
        // reCAPTCHA check
        $recaptcha = $this->recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])
                                    ->setScoreThreshold(0.5)
                                    ->verify($recaptchaResponse, $_SERVER['REMOTE_ADDR']);

        if (!$recaptcha->isSuccess()) {
            return redirect()->back()->with('toastr', [
                'type' => 'error', 
                'message' => 'reCAPTCHA verification failed. Please try again.'
            ]);
        }

        $rules = [
            'user' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Username wajib diisi',
                    'min_length' => 'Username minimal 3 karakter'
                ]
            ],
            'pass' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password wajib diisi'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            $errors = $validasi->getErrors();
            $error_message = implode('<br>', $errors);
            return redirect()->back()->with('toastr', [
                'type' => 'error',
                'message' => $error_message
            ]);
        }

        $rememberMe = ($remember == '1' ? true : false);
        $login = $this->ionAuth->login($username, $password, $rememberMe);

        if (!$login) {
            return redirect()->back()->with('toastr', [
                'type' => 'error',
                'message' => 'Username atau password salah'
            ]);
        }

        // Ambil user dari database untuk cek tipe
        $db = \Config\Database::connect();
        $user = $db->table('tbl_ion_users')
            ->where('username', $username)
            ->get()
            ->getRow();

        if (!$user || $user->tipe != '2') {
            // Jika bukan tipe user, logout dan redirect
            $this->ionAuth->logout();
            return redirect()->to('/auth/login')->with('toastr', [
                'type' => 'error',
                'message' => 'Akun Anda tidak memiliki akses ke halaman ini'
            ]);
        }

        // Redirect ke halaman utama user setelah login
        return redirect()->to('/')->with('toastr', [
            'type' => 'success',
            'message' => 'Login berhasil!'
        ]);
    }

    /**
     * User registration page (frontend) using da-theme
     */
    public function register()
    {
        if ($this->ionAuth->loggedIn()) {
            return redirect()->to('/');
        }

        $data = [
            'title'         => 'Daftar Pengguna',
            'Pengaturan'    => $this->pengaturan
        ];

        // Menggunakan theme da-theme untuk user registration
        return $this->view('da-theme/auth/register', $data);
    }

    /**
     * Proses registrasi user (frontend) menggunakan da-theme
     */
    public function register_store()
    {
        // Get form data
        $first_name        = $this->request->getVar('first_name');
        $last_name         = $this->request->getVar('last_name');
        $username          = $this->request->getVar('username');
        $email             = $this->request->getVar('email');
        $password          = $this->request->getVar('password');
        $password_confirm  = $this->request->getVar('password_confirm');
        $company           = $this->request->getVar('company');
        $phone             = $this->request->getVar('phone');
        $profile           = $this->request->getVar('profile');
        $tipe              = $this->request->getVar('tipe');
        $terms             = $this->request->getVar('terms');
        $recaptchaResponse = $this->request->getVar('g-recaptcha-response');

        // Check terms agreement
        if (!$terms) {
            return redirect()->back()->withInput()->with('toastr', [
                'type' => 'error',
                'message' => 'Anda harus menyetujui Syarat & Ketentuan'
            ]);
        }

        // reCAPTCHA check
        $recaptcha = $this->recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])
                                    ->setScoreThreshold(0.5)
                                    ->verify($recaptchaResponse, $_SERVER['REMOTE_ADDR']);

        if (!$recaptcha->isSuccess()) {
            return redirect()->back()->withInput()->with('toastr', [
                'type' => 'error',
                'message' => 'Verifikasi keamanan gagal. Silakan coba lagi.'
            ]);
        }

        // Validation rules
        $rules = [
            'first_name' => [
                'rules' => 'required|min_length[2]',
                'errors' => [
                    'required' => 'Nama depan wajib diisi',
                    'min_length' => 'Nama depan minimal 2 karakter'
                ]
            ],
            'last_name' => [
                'rules' => 'required|min_length[2]',
                'errors' => [
                    'required' => 'Nama belakang wajib diisi',
                    'min_length' => 'Nama belakang minimal 2 karakter'
                ]
            ],
            'username' => [
                'rules' => 'required|min_length[3]|is_unique[tbl_ion_users.username]',
                'errors' => [
                    'required' => 'Username wajib diisi',
                    'min_length' => 'Username minimal 3 karakter',
                    'is_unique' => 'Username sudah digunakan'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[tbl_ion_users.email]',
                'errors' => [
                    'required' => 'Email wajib diisi',
                    'valid_email' => 'Format email tidak valid',
                    'is_unique' => 'Email sudah digunakan'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Password wajib diisi',
                    'min_length' => 'Password minimal 8 karakter'
                ]
            ],
            'password_confirm' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi password wajib diisi',
                    'matches' => 'Konfirmasi password tidak cocok'
                ]
            ],
            'phone' => [
                'rules' => 'required|regex_match[/^08[0-9]{8,11}$/]',
                'errors' => [
                    'required' => 'Nomor telepon wajib diisi',
                    'regex_match' => 'Format nomor telepon tidak valid (contoh: 085741220427)'
                ]
            ]
        ];

        // Run validation
        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $error_message = implode('<br>', $errors);
            return redirect()->back()->withInput()->with('toastr', [
                'type' => 'error',
                'message' => $error_message
            ]);
        }

        // Prepare additional data (excluding username and email as they're passed separately)
        $additional_data = [
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'company'    => $company,
            'phone'      => $phone,
            'profile'    => $profile,
            'tipe'       => $tipe,
        ];

        // Check if IonAuth is properly loaded
        if (!isset($this->ionAuth) || !$this->ionAuth) {
            return redirect()->back()->withInput()->with('toastr', [
                'type' => 'error',
                'message' => 'Sistem autentikasi tidak tersedia. Silakan coba lagi.'
            ]);
        }
        
        // Check IonAuth configuration
        try {
            $ionAuthConfig = config('IonAuth');
        } catch (\Exception $e) {
            // Do nothing, just continue
        }
        
        // Test database connection
        try {
            $db = \Config\Database::connect();
            $testQuery = $db->query('SELECT 1 as test');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('toastr', [
                'type' => 'error',
                'message' => 'Koneksi database gagal. Silakan coba lagi.'
            ]);
        }
        
        try {
            // Try IonAuth first
            $register = $this->ionAuth->register($username, $password, $email, $additional_data);
            
            if (!$register) {
                // If IonAuth fails, try manual registration
                $register = $this->manualRegister($username, $password, $email, $additional_data);
                
                if (!$register) {
                    $error_message = 'Registrasi gagal. Silakan coba lagi.';
                    return redirect()->back()->withInput()->with('toastr', [
                        'type' => 'error',
                        'message' => $error_message
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Try manual registration as fallback
            try {
                $register = $this->manualRegister($username, $password, $email, $additional_data);
                
                if (!$register) {
                    return redirect()->back()->withInput()->with('toastr', [
                        'type' => 'error',
                        'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
                    ]);
                }
            } catch (\Exception $e2) {
                return redirect()->back()->withInput()->with('toastr', [
                    'type' => 'error',
                    'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
                ]);
            }
        }

        // Get the user ID from the registration result
        $userId = is_array($register) ? $register['id'] : $register;
        
        // User is automatically added to the default group (supervisor) by IonAuth

        // Redirect ke halaman login setelah registrasi berhasil
        return redirect()->to(base_url('/auth/login'))->with('toastr', [
            'type' => 'success',
            'message' => 'Registrasi berhasil! Silakan login.'
        ]);
    }
    
    /**
     * Manual registration method as fallback when IonAuth fails
     */
    private function manualRegister($username, $password, $email, $additionalData)
    {
        try {
            $db = \Config\Database::connect();
            
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Get IP address
            $ipAddress = \Config\Services::request()->getIPAddress();
            
            // Prepare user data
            $userData = [
                'username' => $username,
                'password' => $hashedPassword,
                'email' => $email,
                'ip_address' => $ipAddress,
                'created_on' => time(),
                'active' => 1,
                'first_name' => $additionalData['first_name'] ?? '',
                'last_name' => $additionalData['last_name'] ?? '',
                'company' => $additionalData['company'] ?? '',
                'phone' => $additionalData['phone'] ?? '',
                'profile' => $additionalData['profile'] ?? '',
                'tipe' => $additionalData['tipe'] ?? '2',
            ];
            
            // Insert user
            $db->table('tbl_ion_users')->insert($userData);
            $userId = $db->insertID();
            
            if (!$userId) {
                log_message('error', 'Failed to get insert ID for manual registration');
                return false;
            }
            
            // Add user to supervisor group (group ID 4)
            $groupData = [
                'user_id' => $userId,
                'group_id' => 5, // supervisor group
            ];
            
            $db->table('tbl_ion_users_groups')->insert($groupData);
            
            log_message('info', 'Manual registration successful. User ID: ' . $userId);
            return $userId;
            
        } catch (\Exception $e) {
            log_message('error', 'Manual registration failed: ' . $e->getMessage());
            return false;
        }
    }

    public function logout()
    {
        $this->ionAuth->logout();
        session()->setFlashdata('toastr', ['type' => 'success', 'message' => 'Anda berhasil keluar dari aplikasi.']);
        return redirect()->to('/auth/login');
    }

    public function forgot_password()
    {
        $this->data['title'] = 'Lupa Kata Sandi';

        if ($this->request->getMethod() === 'post') {
            $this->validation->setRules([
                'identity' => 'required|valid_email',
            ]);

            if ($this->validation->withRequest($this->request)->run()) {
                $identity = $this->request->getVar('identity');
                
                if ($this->ionAuth->forgottenPassword($identity)) {
                    session()->setFlashdata('toastr', ['type' => 'success', 'message' => $this->ionAuth->messages()]);
                    return redirect()->back();
                } else {
                    session()->setFlashdata('toastr', ['type' => 'error', 'message' => $this->ionAuth->errors()]);
                    return redirect()->back();
                }
            }
        }

        return $this->view($this->theme->getThemePath() . '/login/forgot_password', $this->data);
    }
}