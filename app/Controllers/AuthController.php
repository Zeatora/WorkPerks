<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\usersModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new usersModel();
    }

    public function registerUser()
    {
        if ($this->request->getMethod() === 'POST') {

            $validation = \Config\Services::validation();
            $validation->setRules([
                'username' => 'required|min_length[3]|max_length[20]|is_unique[users.username]',
                'password' => 'required|min_length[6]',
                'nama_lengkap' => 'required|min_length[3]|max_length[50]',
                'email' => 'required|valid_email|is_unique[users.email]',
            ]);
            if (!$this->validate($validation->getRules())) {
                return redirect()->back()->withInput()->with('error', $validation->getErrors());
            }
            
            $data = [
                'username' => $this->request->getPost('username'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                'email' => $this->request->getPost('email'),
                'role' => $this->request->getPost('role'),
                'departemen' => $this->request->getPost('departemen'),
            ];

            $data['role'] = 'karyawan'; 
            $data['departemen'] = 'Informatika'; 

            if ($this->userModel->insert($data)) {
                return redirect()->to('/login')->with('success', 'Registrasi berhasil, silakan login.');
            } else {
                return redirect()->back()->with('error', 'Registrasi gagal, silakan coba lagi.')->withInput();
            }
        } else if ($this->request->getMethod() === 'GET') {
            return view('pages/register', [
                'title' => 'Sign Up'
            ]);
        }
    }

    public function loginUser()
    {
        if ($this->request->getMethod() === 'POST') {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'email' => 'required',
                'password' => 'required',
            ]);
            if (!$this->validate($validation->getRules())) {
                return redirect()->back()->withInput()->with('error', $validation->getErrors());
            }

            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $user = $this->userModel->where('email', $email)->first();

            if ($user && password_verify($password, $user['password'])) {
                $sessionData = [
                    'DataUser' => [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'nama_lengkap' => $user['nama_lengkap'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'departemen' => $user['departemen'],
                        'login' => true,
                    ]
                ];
                session()->set($sessionData);
                return redirect()->to('/home')->with('success', 'Login berhasil.');
            } else {
                return redirect()->back()->with('error', 'Email atau password salah.')->withInput();
            }
        } else if ($this->request->getMethod() === 'GET') {
            return view('pages/login', [
                'title' => 'Login'
            ]);
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah berhasil keluar.');
    }
}
