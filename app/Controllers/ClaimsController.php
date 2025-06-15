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

class ClaimsController extends BaseController
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

    public function index()
    {
        //
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

    public function approve_klaim($id)
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $claim = $this->ClaimModel->find($id);
        if (!$claim) {
            return redirect()->back()->with('error', 'Klaim tidak ditemukan.');
        }

        $start = $this->request->getPost('start_date');
        $end = $this->request->getPost('end_date');

        $this->ClaimModel->update(
            $id,
            ['status' => 'approved', 'approved_at' => date('Y-m-d H:i:s')]
        );

        $existing = $this->UserBenefitModel
            ->join('benefits', 'benefits.id = user_benefit.benefit_id')
            ->where('user_benefit.user_id', $claim['user_id'])
            ->where('benefits.kategori', $claim['tipe_klaim'])
            ->where('user_benefit.status', 'aktif')
            ->first();

        if (!$existing) {
            $benefit = $this->BenefitModel->where('kategori', $claim['tipe_klaim'])->first();

            if ($benefit) {
                $this->UserBenefitModel->insert([
                    'user_id' => $claim['user_id'],
                    'benefit_id' => $benefit['id'],
                    'jumlah' => $claim['jumlah'],
                    'tanggal_mulai' => $start,
                    'tanggal_berakhir' => $end ?: null,
                    'status' => 'aktif'
                ]);
            }
        }



        return redirect()->to(base_url('PagesController/dashboard_admin'))->with('success', 'Klaim berhasil disetujui.');
    }

    public function reject_klaim($id)
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $claim = $this->ClaimModel->find($id);
        if (!$claim) {
            return redirect()->back()->with('error', 'Klaim tidak ditemukan.');
        }

        $this->ClaimModel->update($id, ['status' => 'rejected']);

        return redirect()->to('PagesController/dashboard_admin')->with('success', 'Klaim berhasil ditolak.');
    }

    public function batalkan_claim($id)
    {
        $claim = $this->ClaimModel->find($id);
        if (!$claim) {
            return redirect()->back()->with('error', 'Klaim tidak ditemukan.');
        }

        $loggedInUserId = session()->get('DataUser.id');

        if ($claim['user_id'] != $loggedInUserId) {
            return redirect()->back()->with('error', 'Kamu tidak berhak membatalkan klaim ini.');
        }

        $this->ClaimModel->update($id, ['status' => 'inactive']);

        return redirect()->to(base_url('PagesController/form_pengajuan'))->with('success', 'Klaim berhasil dibatalkan.');
    }


    public function lihat_bukti($id)
    {
        $claim = $this->ClaimModel->find($id);
        if (!$claim || !$claim['bukti_url']) {
            return redirect()->back()->with('error', 'Bukti klaim tidak ditemukan.');
        }

        $user = session()->get('DataUser');
        $userId = $user['id'];
        $role = $user['role'];

        if ($claim['user_id'] != $userId && $role != 'admin' && $role != 'super_admin') {
            return redirect()->back()->with('error', 'Anda tidak diizinkan melihat bukti klaim ini.');
        }

        return redirect()->to(base_url($claim['bukti_url']));
    }
}
