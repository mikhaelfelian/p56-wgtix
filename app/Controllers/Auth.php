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
            return redirect()->to('/dashboard');
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
        return view($this->theme->getThemePath() . '/login/login', $data);
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

        return redirect()->to('/dashboard')->with('toastr', [
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
            'Pengaturan'    => $this->pengaturan
        ];

        // Menggunakan theme da-theme untuk user login
        return view('da-theme/auth/login', $data);
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
        return view('da-theme/auth/register', $data);
    }

    /**
     * Proses registrasi user (frontend) menggunakan da-theme
     */
    public function register_store()
    {
        $validasi = \Config\Services::validation();

        $username = $this->request->getVar('user');
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('pass');
        $password_confirm = $this->request->getVar('pass_confirm');
        $recaptchaResponse = $this->request->getVar('recaptcha_response');

        // reCAPTCHA check
        $recaptcha = $this->recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])
                                    ->setScoreThreshold(0.5)
                                    ->verify($recaptchaResponse, $_SERVER['REMOTE_ADDR']);

        if (!$recaptcha->isSuccess()) {
            return redirect()->back()->withInput()->with('toastr', [
                'type' => 'error',
                'message' => 'reCAPTCHA verification failed. Please try again.'
            ]);
        }

        $rules = [
            'user' => [
                'rules' => 'required|min_length[3]|is_unique[users.username]',
                'errors' => [
                    'required' => 'Username wajib diisi',
                    'min_length' => 'Username minimal 3 karakter',
                    'is_unique' => 'Username sudah digunakan'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => 'Email wajib diisi',
                    'valid_email' => 'Format email tidak valid',
                    'is_unique' => 'Email sudah digunakan'
                ]
            ],
            'pass' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password wajib diisi',
                    'min_length' => 'Password minimal 6 karakter'
                ]
            ],
            'pass_confirm' => [
                'rules' => 'required|matches[pass]',
                'errors' => [
                    'required' => 'Konfirmasi password wajib diisi',
                    'matches' => 'Konfirmasi password tidak cocok'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            $errors = $validasi->getErrors();
            $error_message = implode('<br>', $errors);
            return redirect()->back()->withInput()->with('toastr', [
                'type' => 'error',
                'message' => $error_message
            ]);
        }

        $additional_data = [
            'username' => $username,
            'email' => $email,
            'tipe' => '2',
        ];

        $register = $this->ionAuth->register($username, $password, $email, $additional_data);

        if (!$register) {
            return redirect()->back()->withInput()->with('toastr', [
                'type' => 'error',
                'message' => 'Registrasi gagal. Silakan coba lagi.'
            ]);
        }

        // Redirect ke halaman login setelah registrasi berhasil
        return redirect()->to('/auth/login_user')->with('toastr', [
            'type' => 'success',
            'message' => 'Registrasi berhasil! Silakan login.'
        ]);
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

        return view($this->theme->getThemePath() . '/login/forgot_password', $this->data);
    }
}