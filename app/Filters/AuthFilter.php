<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use IonAuth\Libraries\IonAuth;
use Config\Services;

class AuthFilter implements FilterInterface
{
    protected $ionAuth;
    protected $session;

    public function __construct()
    {
        $this->session = Services::session();
        $this->ionAuth = new IonAuth();
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!$this->ionAuth->loggedIn()) {
            // Not logged in at all
            if ($request->isAJAX()) {
                return Services::response()
                    ->setStatusCode(401)
                    ->setJSON(['error' => 'Unauthorized', 'message' => 'Silakan login terlebih dahulu!']);
            }
            $this->session->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('auth/login'));
        }

        // Determine which group to check based on filter alias
        // If 'authAdmin' is in $arguments, check for admin group (group 1)
        // If 'authUser' is in $arguments, check for user group (group 5)
        // If no arguments, default to user group (group 5)
        // Default to user group (5)
        $groupToCheck = [5];

        // If 'authAdmin' is in $arguments, allow group 1 and 2
        if (is_array($arguments) && in_array('authAdmin', $arguments)) {
            $groupToCheck = ['1', '2'];
        }

        // If user is not in the required group(s), deny access
        // Fix: allow admin (group 1 or 2) to access user pages as well
        $userGroups = $this->ionAuth->getUsersGroups()->getResultArray();
        $userGroupIds = array_column($userGroups, 'id');

        // If admin (group 1 or 2), always allow
        if (in_array(1, $userGroupIds) || in_array(2, $userGroupIds)) {
            // Admins can access all pages
            return;
        }

        // Otherwise, check if user is in required group(s)
        if (!$this->ionAuth->inGroup($groupToCheck)) {
            if ($request->isAJAX()) {
                return Services::response()
                    ->setStatusCode(403)
                    ->setJSON(['error' => 'Forbidden', 'message' => 'Akses ditolak! Anda tidak memiliki izin untuk mengakses halaman ini.']);
            }
            $this->session->setFlashdata('error', 'Akses ditolak! Anda tidak memiliki izin untuk mengakses halaman ini.');

            // Redirect to previous page if possible, otherwise to appropriate dashboard/login
            $referer = $request->getServer('HTTP_REFERER');
            if (!empty($referer) && strpos($referer, base_url()) === 0) {
                // Only redirect back if referer is from our site
                return redirect()->back()->with('toastr', [
                    'type' => 'error',
                    'message' => 'Akses ditolak! Anda tidak memiliki izin untuk mengakses halaman ini.'
                ]);
            }

            // If groupToCheck is ['1', '2'], it's admin
            if ($groupToCheck == ['1', '2']) {
                return redirect()->to(base_url('admin/dashboard'))->with('toastr', [
                    'type' => 'error',
                    'message' => 'Akses ditolak! Anda tidak memiliki izin admin untuk mengakses halaman ini.'
                ]);
            } else {
                // Default: user
                return redirect()->to(base_url('/'))->with('toastr', [
                    'type' => 'error',
                    'message' => 'Akses ditolak! Anda tidak memiliki izin untuk mengakses halaman ini.'
                ]);
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
} 