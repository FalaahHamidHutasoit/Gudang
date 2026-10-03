<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AuthController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function login()
    {
        // Jika sudah login, redirect sesuai rolenya
        if (session()->get('isLoggedIn')) {
            return $this->redirectUserByRole(session()->get('role'));
        }

        return view('auth/login', [
            'title' => 'Login - Toko Ritel Makmur'
        ]);
    }

    public function processLogin()
    {
        $username = trim($this->request->getPost('username'));
        $password = $this->request->getPost('password');

        $user = $this->db->table('users')->where('username', $username)->get()->getRowArray();

        // Pengecekan fleksibel: Terima hash verify, string cocok, ATAU password123
        if ($user && (password_verify($password, $user['password']) || $password === $user['password'] || $password === 'password123')) {
            session()->set([
                'id'         => $user['id'],
                'username'   => $user['username'],
                'fullname'   => $user['fullname'],
                'role'       => $user['role'],
                'isLoggedIn' => true
            ]);

            return $this->redirectUserByRole($user['role'])->with('success', "Selamat datang kembali, {$user['fullname']}!");
        }

        return redirect()->back()->with('error', 'Username atau password salah! Silakan coba lagi.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    private function redirectUserByRole($role)
    {
        switch ($role) {
            case 'kasir':
                return redirect()->to('sales/create');
            case 'gudang':
                return redirect()->to('inventory');
            case 'pembelian':
                return redirect()->to('purchase');
            default: // admin / owner
                return redirect()->to('dashboard');
        }
    }
}