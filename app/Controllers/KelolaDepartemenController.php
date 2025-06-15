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

class KelolaDepartemenController extends BaseController
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

    public function index()
    {
        $check = $this->CheckAdmin();

        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $data = [
            'title' => 'Tambah Departemen'
        ];

        return view('admin/form_crud/create_kelola_departemen', $data);
    }

    public function store()
    {
        $check = $this->CheckAdmin();

        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $validation = \Config\Services::validation();

        $rules = [
            'nama_departemen' => [
                'label' => 'Nama Departemen',
                'rules' => 'required|min_length[3]|max_length[100]|is_unique[departemen.nama_departemen]|regex_match[/^[A-Za-z\s]+$/]',
                'errors' => [
                    'regex_match' => 'Nama Departemen hanya boleh berisi huruf dan spasi.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('status', 'error')->with('message', $validation->getErrors());
        }

        $data = [
            'nama_departemen' => $this->request->getPost('nama_departemen'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->DepartemenModel->insert($data);

        return redirect()->to('PagesController/kelola_departemen')->with('status', 'success')->with('message', 'Departemen berhasil ditambahkan.');
    }

    public function edit($id) {
        $check = $this->CheckAdmin();

        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $departemen = $this->DepartemenModel->find($id);

        if (!$departemen) {
            return redirect()->to('PagesController/kelola_departemen')->with('status', 'error')->with('message', 'Departemen tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Departemen',
            'departemen' => $departemen
        ];

        return view('admin/form_crud/edit_kelola_departemen', $data);
    }

    public function update($id) {
        $check = $this->CheckAdmin();

        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $departemen = $this->DepartemenModel->find($id);

        if (!$departemen) {
            return redirect()->to('PagesController/kelola_departemen')->with('status', 'error')->with('message', 'Departemen tidak ditemukan.');
        }

        $validation = \Config\Services::validation();

        $rules = [
            'nama_departemen' => [
                'label' => 'Nama Departemen',
                'rules' => 'required|min_length[3]|max_length[100]|regex_match[/^[A-Za-z\s]+$/]|is_unique[departemen.nama_departemen,id,' . $id . ']',
                'errors' => [
                    'regex_match' => 'Nama Departemen hanya boleh berisi huruf dan spasi.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('status', 'error')->with('message', $validation->getErrors());
        }

        $data = [
            'nama_departemen' => $this->request->getPost('nama_departemen'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->DepartemenModel->update($id, $data);

        return redirect()->to('PagesController/kelola_departemen')->with('status', 'success')->with('message', 'Departemen berhasil diperbarui.');
    }

    public function delete($id) {
        $check = $this->CheckAdmin();

        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }
        $departemen = $this->DepartemenModel->find($id);

        if (!$departemen) {
            return redirect()->to('PagesController/kelola_departemen')->with('status', 'error')->with('message', 'Departemen tidak ditemukan.');
        }

        $this->DepartemenModel->delete($id);

        return redirect()->to('PagesController/kelola_departemen')->with('status', 'success')->with('message', 'Departemen berhasil dihapus.');
    }
}
