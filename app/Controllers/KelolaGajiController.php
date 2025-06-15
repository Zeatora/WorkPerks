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


class KelolaGajiController extends BaseController
{
    protected $UserModel;
    protected $BenefitModel;
    protected $UserBenefitModel;
    protected $SalaryModel;
    protected $ClaimModel;
    protected $CompanySettingModel;
    protected $ReportModel;
    protected $PesanCepatModel;

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

    public function cari_karyawan()
    {
        $term = $this->request->getGet('term');
        $model = new UserModel();
        $builder = $model->select('id, nama_lengkap, email');
        if ($term) {
            $builder->like('nama_lengkap', $term)->orLike('email', $term);
        }
        $users = $builder->findAll(10);
        $results = [];
        foreach ($users as $user) {
            $results[] = [
                'id' => $user['id'],
                'text' => $user['nama_lengkap'] . ' - ' . $user['email']
            ];
        }
        return $this->response->setJSON(['results' => $results]);
    }


    public function index()
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) return $check;

        $listKaryawan = $this->UserModel
            ->where('role', 'karyawan')
            ->select('id, nama_lengkap, email')
            ->findAll();

        return view('admin/form_crud/create_kelola_gaji', [
            'title' => 'Tambah Gaji',
            'listKaryawan' => $listKaryawan
        ]);
    }

    public function store()
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) return $check;

        $validation = \Config\Services::validation();
        $validation->setRules([
            'user_id' => 'required|is_not_unique[users.id]',
            'periode' => 'required',
            'gaji_pokok' => 'required|numeric|min_length[1]',
            'bonus' => 'permit_empty|numeric',
            'insentif_kinerja' => 'permit_empty|numeric'
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $totalGaji = $this->request->getPost('gaji_pokok') +
            ($this->request->getPost('bonus') ?: 0) +
            ($this->request->getPost('insentif_kinerja') ?: 0);

        $periode = $this->request->getPost('periode');
        if (strlen($periode) === 7) {
            $periode .= '-01'; 
        }

        $data = [
            'user_id' => $this->request->getPost('user_id'),
            'periode' => $periode,
            'gaji_pokok' => $this->request->getPost('gaji_pokok'),
            'total_gaji' => $totalGaji,
            'bonus' => $this->request->getPost('bonus') ?: 0,
            'insentif_kinerja' => $this->request->getPost('insentif_kinerja') ?: 0
        ];

        $this->SalaryModel->insert($data);

        return redirect()->to('PagesController/pengelolaan_gaji')->with('status', 'success')->with('message', 'Gaji berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) return $check;

        $salary = $this->SalaryModel->find($id);
        if (!$salary) {
            return redirect()->back()->with('status', 'error')->with('message', 'Data gaji tidak ditemukan.');
        }

        return view('admin/form_crud/edit_kelola_gaji', [
            'title' => 'Edit Gaji',
            'gaji' => $salary
        ]);
    }

    public function update($id)
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) return $check;

        $validation = \Config\Services::validation();
        $validation->setRules([
            'periode' => 'required',
            'gaji_pokok' => 'required|numeric|min_length[1]',
            'bonus' => 'permit_empty|numeric',
            'insentif_kinerja' => 'permit_empty|numeric'
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $totalGaji = $this->request->getPost('gaji_pokok') +
            ($this->request->getPost('bonus') ?: 0) +
            ($this->request->getPost('insentif_kinerja') ?: 0);

        $periode = $this->request->getPost('periode');
        if (strlen($periode) === 7) {
            $periode .= '-01'; 
        }

        $data = [
            'periode' => $periode,
            'gaji_pokok' => $this->request->getPost('gaji_pokok'),
            'total_gaji' => $totalGaji,
            'bonus' => $this->request->getPost('bonus') ?: 0,
            'insentif_kinerja' => $this->request->getPost('insentif_kinerja') ?: 0
        ];

        $this->SalaryModel->update($id, $data);

        return redirect()->to('PagesController/pengelolaan_gaji')->with('status', 'success')->with('message', 'Gaji berhasil diperbarui.');
    }

    public function delete($id)
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) return $check;

        $salary = $this->SalaryModel->find($id);
        if (!$salary) {
            return redirect()->back()->with('status', 'error')->with('message', 'Data gaji tidak ditemukan.');
        }

        $this->SalaryModel->delete($id);

        return redirect()->to('PagesController/pengelolaan_gaji')->with('status', 'success')->with('message', 'Gaji berhasil dihapus.');
    }
}
