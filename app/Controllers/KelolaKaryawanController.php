<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\usersModel as UserModel;
use App\Models\BenefitModel;
use App\Models\UserBenefitModel;
use App\Models\SalaryModel;
use App\Models\ClaimModel;
use App\Models\CompanySettingModel;
use App\Models\ReportModel;
use App\Models\PesanCepatModel;
use App\Models\DepartemenModel;


class KelolaKaryawanController extends BaseController
{

    protected $UserModel;
    protected $BenefitModel;
    protected $UserBenefitModel;
    protected $SalaryModel;
    protected $ClaimModel;
    protected $CompanySettingModel;
    protected $ReportModel;
    protected $PesanCepatModel;
    protected $DepartemenModel;

    public function __construct()
    {
        $this->UserModel = new UserModel();
        $this->BenefitModel = new BenefitModel();
        $this->UserBenefitModel = new UserBenefitModel();
        $this->SalaryModel = new SalaryModel();
        $this->ClaimModel = new ClaimModel();
        $this->CompanySettingModel = new CompanySettingModel();
        $this->ReportModel = new ReportModel();
        $this->PesanCepatModel = new PesanCepatModel();
        $this->DepartemenModel = new DepartemenModel();
    }

    public function CheckAdmin()
    {
        if (!session()->get('DataUser.login')) {
            return redirect()->to('/login')->with('status', 'warning')->with('message', 'Silakan login terlebih dahulu.');
        }
        if (session()->get('DataUser.role') !== 'admin') {
            return redirect()->to('/home')->with('status', 'error')->with('message', 'Akses ditolak, hanya admin yang dapat mengakses halaman ini.');
        }

        return null;
    }

    public function search_departemen()
    {
        $term = $this->request->getGet('term');
        $departemenModel = $this->DepartemenModel;

        if (!$term) {
            return $this->response->setJSON([]);
        }

        $departemen = $departemenModel
            ->like('nama_departemen', $term)
            ->select('id, nama_departemen')
            ->findAll(10);

        return $this->response->setJSON($departemen);
    }


    public function CheckUsername()
    {
        $username = $this->request->getPost('username');
        $user = $this->request->getPost('id');

        $user = $this->UserModel->where('username', $username)
            ->where('id !=', $user)
            ->first();

        if ($user) {
            return $this->response->setJSON(['exists' => true]);
        } elseif (!$user) {
            return $this->response->setJSON(['exists' => false]);
        }

        if (strlen($username) < 3) {
            return $this->response->setJSON(['error' => true, 'message' => 'Username terlalu pendek.']);
        }
    }

    public function CheckUsernameCreate()
    {
        $username = $this->request->getPost('username');

        $user = $this->UserModel->where('username', $username)->first();

        if ($user) {
            return $this->response->setJSON(['exists' => true]);
        } elseif (!$user) {
            return $this->response->setJSON(['exists' => false]);
        }

        if (strlen($username) < 3) {
            return $this->response->setJSON(['error' => true, 'message' => 'Username terlalu pendek.']);
        }
    }

    public function CheckEmailCreate()
    {
        $email = $this->request->getPost('email');

        $user = $this->UserModel->where('email', $email)
            ->first();

        if ($user) {
            return $this->response->setJSON(['EmailExists' => true]);
        } elseif (!$user) {
            return $this->response->setJSON(['EmailExists' => false]);
        }
    }

    public function create()
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }
        $departemenList = $this->DepartemenModel->findAll(); // Sesuaikan enum di tabel kamu

        return view('admin/form_crud/create_kelola_karyawan', [
            'title' => 'Tambah Karyawan Baru',
            'departemenList' => $departemenList
        ]);
    }

    public function store()
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }
        $url_profile = "uploads/profile/default_profile.jpg";


        $data = [
            'username' => $this->request->getPost('username'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email' => $this->request->getPost('email'),
            'departemen_id' => $this->request->getPost('departemen_id') ?? 0,
            'role' => $this->request->getPost('role'),
            'status' => $this->request->getPost('status'),
            'url_profile' => $url_profile,
            'status' => 'active',
        ];

        $password = $this->request->getPost('password');

        if ($password) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $data['password'] = password_hash('default_password', PASSWORD_DEFAULT); // Ganti dengan password default jika tidak diisi
        }

        if (!$this->validate([
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'departemen_id' => 'required',
            'role' => 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        if ($this->UserModel->insert($data)) {
            return redirect()->to('PagesController/kelola_karyawan')->with('success', 'Karyawan baru berhasil ditambahkan.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan karyawan baru.');
        }
    }


    public function edit($id)
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $karyawan = $this->UserModel
            ->select('users.*, departemen.nama_departemen')
            ->join('departemen', 'departemen.id = users.departemen_id', 'left')
            ->where('users.id', $id)
            ->first();

        $ProfilePath = 'uploads/profile/' . $karyawan['username'] . '_profile.jpg';

        if (!$karyawan) {
            return redirect()->to('/karyawan')->with('error', 'Karyawan tidak ditemukan.');
        }

        $departemenList = $this->DepartemenModel->findAll();

        return view('admin/form_crud/edit_kelola_karyawan', [
            'title' => 'Edit Data Akun Karyawan',
            'karyawan' => $karyawan,
            'departemenList' => $departemenList,
            'profilePath' => $ProfilePath,
            'id' => $id
        ]);
    }

    public function update($id)
    {
        if (session()->get('DataUser.login') !== true) {
            return redirect()->to('/login')->with('status', 'warning')->with('message', 'Silakan login terlebih dahulu.');
        }

        $sessionUser = session()->get('DataUser');
        $isAdmin = $sessionUser['role'] && $sessionUser['role'] === 'admin';
        $isSuper = $sessionUser['role'] && $sessionUser['role'] === 'super_admin';
        $isKaryawan = $sessionUser['role'] && $sessionUser['role'] === 'karyawan';
        $DiriSendiri = $sessionUser['id'] && $sessionUser['id'] == $id;

        if (!$isAdmin && !$isSuper && !$DiriSendiri) {
            return redirect()->to('PagesController/profile/' . session()->get('DataUser.id'))->with('status', 'error')->with('message', 'Akses ditolak');
        }

        $karyawan = $this->UserModel->find($id);

        if (!$karyawan) {
            if ($isAdmin || $isSuper) {
                return redirect()->to('PagesController/kelola_karyawan')->with('error', 'Karyawan tidak ditemukan.');
            } elseif ($isKaryawan) {
                return redirect()->to('PagesController/profile/' . $id)->with('error', 'Karyawan tidak ditemukan.');
            }
        }

        $ProfilePicture = $this->request->getFile('url_profile');
        if ($ProfilePicture && $ProfilePicture->isValid() && !$ProfilePicture->hasMoved()) {
            $profilePath = 'uploads/profile/' . $karyawan['username'] . '_profile.jpg';
            if (file_exists(FCPATH . $profilePath)) {
                unlink(FCPATH . $profilePath);
            }

            $newProfilePath = 'uploads/profile/' . $karyawan['username'] . '_profile.jpg';
            $ProfilePicture->move(FCPATH . 'uploads/profile', $karyawan['username'] . '_profile.jpg');
        }


        $data = [
            'username' => $this->request->getPost('username'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email' => $this->request->getPost('email'),
            'departemen_id' => $this->request->getPost('departemen_id') ?? $karyawan['departemen_id'],
            'role' => $this->request->getPost('role') ?? $karyawan['role'],
            'status' => $this->request->getPost('status') ?? $karyawan['status'],
            'url_profile' => isset($newProfilePath) ? $newProfilePath : $karyawan['url_profile'],
        ];

        $rules = [
            'username'   => 'required|min_length[3]|max_length[50]',
            'email'      => 'required|valid_email',
            'departemen_id' => 'required'
        ];

        if ($isAdmin || $isSuper) {
            $rules['role'] = 'required';
            $rules['status'] = 'required';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        if ($ProfilePicture && $ProfilePicture->isValid() && !$ProfilePicture->hasMoved()) {
            if ($ProfilePicture->getSize() > 2048000) { // 2MB
                return redirect()->back()->withInput()->with('error', 'Ukuran file foto profil terlalu besar. Maksimal 2MB.');
            }

            $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];
            if (!in_array($ProfilePicture->getMimeType(), $allowedTypes)) {
                return redirect()->back()->withInput()->with('error', 'Tipe file tidak didukung. Hanya JPG, JPEG, dan PNG yang diperbolehkan.');
            }
        }

        if ($this->UserModel->update($id, $data)) {
            if ($sessionUser['role'] === 'admin' || $sessionUser['role'] === 'super_admin') {
                return redirect()->to('PagesController/kelola_karyawan')->with('success', 'Data karyawan berhasil diperbarui.');
            } elseif ($sessionUser['role'] === 'karyawan') {
                return redirect()->to('PagesController/profile/' . $id)->with('success', 'Data akun Anda berhasil diperbarui.');
            }
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data karyawan.');
        }
    }

    public function delete($id)
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }
        $karyawan = $this->UserModel->find($id);
        if (!$karyawan) {
            return redirect()->to('PagesController/kelola_karyawan')->with('error', 'Karyawan tidak ditemukan.');
        }
        $profilePath = 'uploads/profile/' . $karyawan['username'] . '_profile.jpg';
        if (file_exists(FCPATH . $profilePath)) {
            unlink(FCPATH . $profilePath);
        }

        if ($this->UserModel->delete($id)) {
            return redirect()->to('PagesController/kelola_karyawan')->with('success', 'Karyawan berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus karyawan.');
        }
    }

    public function edit_karyawan($id)
    {
        if (session()->get('DataUser.id') != $id) {
            return redirect()->to('PagesController/profile/' . session()->get('DataUser.id'))->with('status', 'error')->with('message', 'Akses ditolak');
        }

        $karyawan = $this->UserModel
            ->select('users.*, departemen.nama_departemen')
            ->join('departemen', 'departemen.id = users.departemen_id', 'left')
            ->where('users.id', $id)
            ->first();

        if (!$karyawan) {
            return redirect()->to('/karyawan')->with('error', 'Karyawan tidak ditemukan.');
        }

        $ProfilePath = 'uploads/profile/' . $karyawan['username'] . '_profile.jpg';

        return view('admin/form_crud/edit_kelola_karyawan', [
            'title' => 'Edit Data Akun Karyawan',
            'karyawan' => $karyawan,
            'profilePath' => $ProfilePath,
            'id' => $id
        ]);
    }

    public function reset_password()
    {
        $session = session();
        $userId = $session->get('DataUser.id');
        $newPassword = $this->request->getPost('new_password');

        if (!$newPassword) {
            return redirect()->back()->with('error', 'Password tidak boleh kosong.');
        }

        $this->UserModel->update($userId, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);

        return redirect()->back()->with('success', 'Password berhasil di-reset.');
    }

    public function reset_password_admin()
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }
        $userId = $this->request->getPost('user_id');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if (!$userId || !$newPassword || !$confirmPassword) {
            return redirect()->back()->with('error', 'Data tidak lengkap.');
        }

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak cocok.');
        }

        $this->UserModel->update($userId, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);

        return redirect()->back()->with('success', 'Password karyawan berhasil direset.');
    }
}
