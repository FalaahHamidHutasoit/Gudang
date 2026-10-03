<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $params = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('login')->with('error', 'Silakan login terlebih dahulu untuk mengakses sistem.');
        }

        // Cek batasan hak akses role jika ditentukan
        if ($params) {
            $userRole = session()->get('role');
            if ($userRole !== 'admin' && !in_array($userRole, $params)) {
                return redirect()->back()->with('error', 'Akses ditolak! Anda tidak memiliki izin untuk mengakses halaman tersebut.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $params = null)
    {
        // Do nothing
    }
}