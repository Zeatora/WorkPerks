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

class KelolaTunjanganController extends BaseController
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
    }

    public function index()
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $data = [
            'title' => 'Tambah Tunjangan',
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/form_crud/create_kelola_tunjangan', $data);
    }

    public function create()
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        if (!$this->validate([
            'nama' => 'required|min_length[3]|max_length[50]',
            'kategori' => 'required|in_list[kesehatan,transportasi,asuransi,makan,lainnya]',
            'deskripsi' => 'permit_empty|max_length[255]',
            'is_active' => 'required|in_list[1,0]',
        ])) {
            return redirect()->back()->withInput()->with('error', \Config\Services::validation()->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'kategori' => $this->request->getPost('kategori'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'is_active' => $this->request->getPost('is_active') == 1 ? 1 : 0,
        ];

        $this->BenefitModel->insert($data);

        return redirect()->to('PagesController/kelola_tunjangan')->with('status', 'success')->with('message', 'Tunjangan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $tunjangan = $this->BenefitModel->find($id);
        if (!$tunjangan) {
            return redirect()->back()->with('status', 'error')->with('message', 'Tunjangan tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Tunjangan',
            'tunjangan' => $tunjangan,
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/form_crud/edit_kelola_tunjangan', $data);
    }

    public function update($id)
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $tunjangan = $this->BenefitModel->find($id);
        if (!$tunjangan) {
            return redirect()->back()->with('status', 'error')->with('message', 'Tunjangan tidak ditemukan.');
        }

        if (!$this->validate([
            'nama' => 'required|min_length[3]|max_length[50]',
            'kategori' => 'required|in_list[kesehatan,transportasi,asuransi,makan,lainnya]',
            'deskripsi' => 'permit_empty|max_length[255]',
            'is_active' => 'required|in_list[1,0]',
        ])) {
            return redirect()->back()->withInput()->with('error', \Config\Services::validation()->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'kategori' => $this->request->getPost('kategori'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'is_active' => $this->request->getPost('is_active') == 1 ? 1 : 0,
        ];

        $this->BenefitModel->update($id, $data);

        return redirect()->to('PagesController/kelola_tunjangan')->with('status', 'success')->with('message', 'Tunjangan berhasil diperbarui.');
    }

    public function delete($id) {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $tunjangan = $this->BenefitModel->find($id);
        if (!$tunjangan) {
            return redirect()->back()->with('status', 'error')->with('message', 'Tunjangan tidak ditemukan.');
        }

        $this->BenefitModel->delete($id);

        return redirect()->to('PagesController/kelola_tunjangan')->with('status', 'success')->with('message', 'Tunjangan berhasil dihapus.');
    }
}