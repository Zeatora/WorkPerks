<?php

namespace App\Controllers;

use App\Models\usersModel as UserModel;
use App\Models\BenefitModel;
use App\Models\UserBenefitModel;
use App\Models\SalaryModel;
use App\Models\ClaimModel;
use App\Models\CompanySettingModel;
use App\Models\ReportModel;

class PagesController extends BaseController
{
    protected $UserModel;
    protected $BenefitModel;
    protected $UserBenefitModel;
    protected $SalaryModel;
    protected $ClaimModel;
    protected $CompanySettingModel;
    protected $ReportModel;


    public function __construct()
    {
        $this->UserModel = new UserModel();
        $this->BenefitModel = new BenefitModel();
        $this->UserBenefitModel = new UserBenefitModel();
        $this->SalaryModel = new SalaryModel();
        $this->ClaimModel = new ClaimModel();
        $this->CompanySettingModel = new CompanySettingModel();
        $this->ReportModel = new ReportModel();
    }

    public function index(): string
    {
        $data = [
            'title' => 'Home'
        ];


        return view('pages/home', $data);
    }

    public function loginPage()
    {
        $data = [
            'title' => 'Login'
        ];

        $session = session();
        $isLoggedIn = $session->get('DataUser.login');

        if ($isLoggedIn) {
            $data = [
                'title' => 'Home'
            ];
            return redirect()->to('/home')->with('status', 'warning')->with('message', 'You are already logged in')->with('data', $data);
        }

        return view('pages/login', $data);
    }

    public function signupPage(): string
    {
        $data = [
            'title' => 'Sign Up'
        ];
        return view('pages/register', $data);
    }

    public function contact(): string
    {
        $data = [
            'title' => 'Contact'
        ];
        return view('pages/contact', $data);
    }

    public function dashboard_admin(): string
    {
        $totalKaryawan = $this->UserModel->where('role', 'karyawan')->countAllResults();
        $klaimPending = $this->ClaimModel->where('status', 'pending')->countAllResults();
        $tunjanganAktif = $this->BenefitModel->where('is_active', 1)->countAllResults();

        $bulan = [];
        $gajiPerBulan = [];

        for ($i = 5; $i >= 0; $i--) {
            $periode = date('Y-m', strtotime("-$i months"));
            $bulan[] = date('M', strtotime($periode));

            $total = $this->SalaryModel
                ->selectSum('total_gaji')
                ->like('periode', $periode)
                ->first()['total_gaji'] ?? 0;

            $gajiPerBulan[] = (int) $total;
        }

        $tunjanganDistribusi = $this->UserBenefitModel
            ->select('benefits.kategori, COUNT(*) as total')
            ->join('benefits', 'benefits.id = user_benefit.benefit_id')
            ->where('user_benefit.status', 'aktif')
            ->groupBy('benefits.kategori')
            ->findAll();

        $ListPendingClaims =  $this->ClaimModel
            ->select('claims.*, users.nama_lengkap AS nama_karyawan')
            ->join('users', 'users.id = claims.user_id')
            ->where('claims.status', 'pending')
            ->findAll();



        $bulanIni = date('Y-m');
        $totalGaji = $this->SalaryModel
            ->selectSum('total_gaji')
            ->like('periode', $bulanIni)
            ->first()['total_gaji'] ?? 0;


        $data = [
            'title'           => 'Dashboard Admin',
            'TotalKaryawan'   => $totalKaryawan,
            'KlaimPending'    => $klaimPending,
            'TunjanganAktif'  => $tunjanganAktif,
            'TotalGaji'       => $totalGaji,
            'ListPendingClaims' => $ListPendingClaims,
            'chart_bulan'     => json_encode($bulan),
            'chart_gaji'      => json_encode($gajiPerBulan),
            'chart_kategori'  => json_encode(array_column($tunjanganDistribusi, 'kategori')),
            'chart_total'     => json_encode(array_column($tunjanganDistribusi, 'total')),
        ];


        return view('authentication/dashboard_admin', $data);
    }
}
