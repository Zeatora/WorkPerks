<?php

namespace App\Controllers;

use App\Models\usersModel as UserModel;
use App\Models\BenefitModel;
use App\Models\UserBenefitModel;
use App\Models\SalaryModel;
use App\Models\ClaimModel;
use App\Models\CompanySettingModel;
use App\Models\ReportModel;
use App\Models\PesanCepatModel;

class PagesController extends BaseController
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
            return redirect()->to('/home')->with('status', 'warning')->with('message', 'Kamu sudah logged in')->with('data', $data);
        }

        return view('pages/login', $data);
    }

    public function signupPage()
    {
        $data = [
            'title' => 'Sign Up'
        ];

        $session = session();
        $isLoggedIn = $session->get('DataUser.login');

        if ($isLoggedIn) {
            $data = [
                'title' => 'Home'
            ];
            return redirect()->to('/home')->with('status', 'warning')->with('message', 'Kamu sudah logged in!')->with('data', $data);
        }
        return view('pages/register', $data);
    }

    public function contact(): string
    {
        $pengaturan = $this->CompanySettingModel->findAll();

        $setting = [];
        foreach ($pengaturan as $row) {
            $setting[$row['setting_key']] = $row['setting_value'];
        }

        $data = [
            'title' => 'Kontak Kami',
            'setting' => $setting
        ];
        return view('pages/contact', $data);
    }

    public function dashboard_admin()
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

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


        return view('admin/dashboard_admin', $data);
    }

    public function kelola_karyawan()
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $data = [
            'title' => 'Kelola Karyawan',
            'daftarKaryawan' => $this->UserModel->where('role', 'karyawan')->findAll()
        ];

        return view('admin/kelola_karyawan', $data);
    }

    public function kelola_tunjangan()
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $data = [
            'title' => 'Kelola Tunjangan',
            'daftarTunjangan' => $this->BenefitModel->findAll()
        ];

        return view('admin/kelola_tunjangan', $data);
    }

    public function pengelolaan_gaji()
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $periode = $this->request->getGet('periode') ?? date('Y-m');

        $gaji = $this->SalaryModel
            ->select('salaries.*, users.nama_lengkap')
            ->join('users', 'users.id = salaries.user_id')
            ->like('salaries.periode', $periode)
            ->orderBy('periode', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Pengelolaan Gaji',
            'daftarGaji' => $gaji,
            'periodeDipilih' => $periode,
        ];

        return view('admin/pengelolaan_gaji', $data);
    }

    public function pengaturan_perusahaan()
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $pengaturan = $this->CompanySettingModel->findAll();

        $data = [
            'title' => 'Pengaturan Perusahaan',
            'pengaturan' => $pengaturan
        ];

        return view('admin/pengaturan_perusahaan', $data);
    }

    public function post_pesan_cepat()
    {
        if ($this->request->getMethod() === 'POST') {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'pesan' => 'required|min_length[5]|max_length[500]',
            ]);

            if (!$this->validate($validation->getRules())) {
                return redirect()->back()->withInput()->with('error', $validation->getErrors());
            }

            $data = [
                'user_id' => session()->get('DataUser.id'),
                'nama' => $this->request->getPost('nama'),
                'email' => $this->request->getPost('email'),
                'pesan' => $this->request->getPost('pesan'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($this->PesanCepatModel->insert($data)) {
                return redirect()->to('/home')->with('success', 'Pesan berhasil dikirim.');
            } else {
                return redirect()->back()->with('error', 'Gagal mengirim pesan, silakan coba lagi.')->withInput();
            }
        }
    }

    public function lihat_inbox_pesan()
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $pesanCepat = $this->PesanCepatModel
            ->findAll();

        $data = [
            'title' => 'Inbox Pesan Cepat',
            'pesanCepat' => $pesanCepat
        ];

        return view('admin/inbox_pesan_cepat', $data);
    }
}
