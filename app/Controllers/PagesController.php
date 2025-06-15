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
use App\Models\DepartemenModel;


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

    public function checkLogin()
    {
        $session = session();
        $isLoggedIn = $session->get('DataUser.login');

        if (!$isLoggedIn) {
            return redirect()->to('/home')->with('status', 'warning')->with('message', 'Kamu belum login, silakan login terlebih dahulu.');
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
        $totalDepartemen = $this->DepartemenModel->countAllResults();

        $bulan = [];
        $gajiPerBulan = [];
        $tunjanganPerBulan = [];

        for ($i = 5; $i >= 0; $i--) {
            $periodeLoop = date('Y-m', strtotime("-$i months"));
            $bulan[] = date('M', strtotime($periodeLoop));

            $totalGaji = $this->SalaryModel
                ->selectSum('total_gaji')
                ->like('periode', $periodeLoop)
                ->first()['total_gaji'] ?? 0;

            $totalTunjangan = $this->UserBenefitModel
                ->selectSum('jumlah')
                ->like('tanggal_mulai', $periodeLoop)
                ->where('status', 'aktif')
                ->first()['jumlah'] ?? 0;

            $gajiPerBulan[] = (int)$totalGaji;
            $tunjanganPerBulan[] = (int)$totalTunjangan;
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
            ->paginate(5);



        $bulanIni = date('Y-m');
        $totalGaji = $this->SalaryModel
            ->selectSum('total_gaji')
            ->like('periode', $bulanIni)
            ->first()['total_gaji'] ?? 0;

        $totalTunjangan = $this->UserBenefitModel
            ->selectSum('jumlah')
            ->like('tanggal_mulai', $bulanIni)
            ->where('status', 'aktif')
            ->first()['jumlah'] ?? 0;


        $data = [
            'title'           => 'Dashboard Admin',
            'TotalKaryawan'   => $totalKaryawan,
            'KlaimPending'    => $klaimPending,
            'TunjanganAktif'  => $tunjanganAktif,
            'TotalGaji'       => $totalGaji,
            'TotalTunjangan' => $totalTunjangan,
            'TotalDepartemen' => $totalDepartemen,
            'ListPendingClaims' => $ListPendingClaims,
            'chart_bulan'     => json_encode($bulan),
            'chart_gaji'      => json_encode($gajiPerBulan),
            'chart_kategori'  => json_encode(array_column($tunjanganDistribusi, 'kategori')),
            'chart_total'     => json_encode(array_column($tunjanganDistribusi, 'total')),
            'chart_tunjangan' => json_encode($tunjanganPerBulan),
            'pager' => $this->ClaimModel->pager,
        ];


        return view('admin/dashboard_admin', $data);
    }

    public function kelola_karyawan()
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        // Ambil parameter dari GET
        $search = $this->request->getGet('search');
        $departemenFilter = $this->request->getGet('departemen');
        $sort = $this->request->getGet('sort') ?? 'nama_lengkap';
        $order = strtolower($this->request->getGet('order') ?? 'asc');

        // Validasi kolom sort
        $validSortColumns = ['username', 'nama_lengkap', 'email', 'nama_departemen'];

        if (!in_array($sort, $validSortColumns)) {
            $sort = 'nama_lengkap';
        }
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        // Build query
        $query = $this->UserModel
            ->select('users.*, departemen.nama_departemen')
            ->join('departemen', 'departemen.id = users.departemen_id', 'left');

        if (!empty($search)) {
            $query->groupStart()
                ->like('users.nama_lengkap', $search)
                ->orLike('users.email', $search)
                ->groupEnd();
        }

        if (!empty($departemenFilter)) {
            $query->where('users.departemen_id', $departemenFilter);
        }

        $sortField = $sort === 'nama_departemen' ? 'departemen.nama_departemen' : "users.$sort";
        $query->orderBy($sortField, $order);


        $daftarKaryawan = $query->paginate(5, 'default');

        // Ambil list semua departemen untuk filter
        $departemenList = $this->DepartemenModel->findAll();

        return view('admin/kelola_karyawan', [
            'title' => 'Kelola Karyawan',
            'daftarKaryawan' => $daftarKaryawan,
            'pager' => $this->UserModel->pager,
            'search' => $search,
            'departemenList' => $departemenList,
            'departemenFilter' => $departemenFilter,
            'sort' => $sort,
            'order' => $order
        ]);
    }

    public function kelola_departemen()
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $search = $this->request->getGet('search');

        $builder = $this->DepartemenModel
            ->select('departemen.*, COUNT(users.id) as total_karyawan')
            ->join('users', 'users.departemen_id = departemen.id', 'left')
            ->groupBy('departemen.id');

        if (!empty($search)) {
            $builder->like('nama_departemen', $search);
        }

        $departemenList = $builder->paginate(10, 'default');

        $data = [
            'title' => 'Kelola Departemen',
            'departemenList' => $departemenList,
            'pager' => $this->DepartemenModel->pager,
            'search' => $search
        ];

        return view('admin/kelola_departemen', $data);
    }



    public function kelola_tunjangan()
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $search = $this->request->getGet('search');
        $kategoriFilter = $this->request->getGet('kategori') ?? '';
        $query = $this->BenefitModel;

        if (!empty($search)) {
            $query->like('nama', $search);
        }
        if (!empty($kategoriFilter)) {
            $query->where('kategori', $kategoriFilter);
        }

        $kategoriList = ['Kesehatan', 'Transportasi', 'Asuransi', 'Makan', 'Lainnya'];

        $daftarTunjangan = $this->BenefitModel
            ->paginate(10);

        $data = [
            'title' => 'Kelola Tunjangan',
            'daftarTunjangan' => $daftarTunjangan,
            'pager' => $this->BenefitModel->pager,
            'search' => $search,
            'kategoriList' => $kategoriList,
            'kategoriFilter' => $kategoriFilter
        ];

        return view('admin/kelola_tunjangan', $data);
    }

    public function pengelolaan_gaji()
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) return $check;

        $search = $this->request->getGet('search');
        $min_total = $this->request->getGet('min_total');
        $max_total = $this->request->getGet('max_total');
        $periode = $this->request->getGet('periode');
        if (!$periode) {
            $periode = date('Y-m');
        }

        $builder = $this->SalaryModel
            ->select('salaries.*, users.nama_lengkap')
            ->join('users', 'users.id = salaries.user_id')
            ->orderBy('salaries.periode', 'DESC');

        // Filter periode (bulan-tahun)
        if ($periode) {
            $builder->like('salaries.periode', $periode);
        }

        // Filter nama
        if ($search) {
            $builder->like('users.nama_lengkap', $search);
        }

        // Filter total_gaji range
        if ($min_total) {
            $builder->where('salaries.total_gaji >=', (int)$min_total);
        }
        if ($max_total) {
            $builder->where('salaries.total_gaji <=', (int)$max_total);
        }

        $data = [
            'title' => 'Pengelolaan Gaji',
            'daftarGaji' => $builder->paginate(10, 'default'),
            'periodeDipilih' => $periode,
            'pager' => $this->SalaryModel->pager,
            'search' => $search,
            'min_total' => $min_total,
            'max_total' => $max_total
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

            $check = $this->checkLogin();
            if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
                return $check;
            }
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
            ->paginate(10);

        $data = [
            'title' => 'Inbox Pesan Cepat',
            'pesanCepat' => $pesanCepat,
            'pager' => $this->PesanCepatModel->pager,
        ];

        return view('admin/inbox_pesan_cepat', $data);
    }

    public function profile()
    {
        $session = session();
        $userId = $session->get('DataUser.id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        $karyawan = $this->UserModel
            ->select('users.*, departemen.nama_departemen')
            ->join('departemen', 'departemen.id = users.departemen_id', 'left')
            ->where('users.id', $userId)
            ->first();



        $ProfilePath = 'uploads/profile/' . $karyawan['username'] . '_profile.jpg';

        // Gaji terakhir
        $gajiTerbaru = $this->SalaryModel
            ->where('user_id', $userId)
            ->orderBy('periode', 'DESC')
            ->first();

        // Tunjangan aktif
        $tunjanganAktif = $this->UserBenefitModel
            ->select('benefits.nama, benefits.kategori')
            ->join('benefits', 'benefits.id = user_benefit.benefit_id')
            ->where('user_benefit.user_id', $userId)
            ->where('user_benefit.status', 'aktif')
            ->findAll();

        // Klaim terakhir (limit 5)
        $riwayatKlaim = $this->ClaimModel
            ->where('user_id', $userId)
            ->orderBy('submitted_at', 'DESC')
            ->findAll(5);

        // Riwayat gaji
        $riwayatGaji = $this->SalaryModel
            ->where('user_id', $userId)
            ->orderBy('periode', 'DESC')
            ->paginate(5);

        return view('authentication/profile', [
            'title' => 'Profil Saya',
            'gajiTerbaru' => $gajiTerbaru,
            'tunjanganAktif' => $tunjanganAktif,
            'riwayatKlaim' => $riwayatKlaim,
            'profilePath' => $ProfilePath,
            'riwayatGaji' => $riwayatGaji,
            'karyawan' => $karyawan, // pastikan ini dikirim
            'pager' => $this->SalaryModel->pager
        ]);
    }


    public function dashboard_karyawan()
    {
        $check = $this->checkLogin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }
        $userId = session()->get('DataUser.id');

        $gajiTerbaru = $this->SalaryModel
            ->where('user_id', $userId)
            ->orderBy('periode', 'DESC')
            ->first();


        $jumlahTunjangan = $this->UserBenefitModel
            ->where('user_id', $userId)
            ->where('status', 'aktif')
            ->countAllResults();

        $klaimDisetujui = $this->ClaimModel
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->countAllResults();

        $klaimPending = $this->ClaimModel
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->countAllResults();

        // Tunjangan aktif
        $tunjanganAktif = $this->UserBenefitModel
            ->select('benefits.nama, benefits.kategori')
            ->join('benefits', 'benefits.id = user_benefit.benefit_id')
            ->where('user_benefit.user_id', $userId)
            ->where('user_benefit.status', 'aktif')
            ->findAll();

        // Riwayat klaim terakhir
        $riwayatKlaim = $this->ClaimModel
            ->where('user_id', $userId)
            ->orderBy('submitted_at', 'DESC')
            ->findAll(5);

        $bulan = [];
        $gajiPerBulan = [];
        for ($i = 5; $i >= 0; $i--) {
            $periode = date('Y-m', strtotime("-$i months"));
            $bulan[] = date('M', strtotime($periode));
            $total = $this->SalaryModel
                ->selectSum('total_gaji')
                ->where('user_id', $userId)
                ->like('periode', $periode)
                ->first()['total_gaji'] ?? 0;
            $gajiPerBulan[] = (int)$total;
        }

        return view('authentication/dashboard_karyawan', [
            'title' => 'Dashboard Karyawan',
            'jumlahTunjangan' => $jumlahTunjangan,
            'gajiTerbaru' => $gajiTerbaru,
            'klaimDisetujui' => $klaimDisetujui,
            'klaimPending' => $klaimPending,
            'tunjanganAktif' => $tunjanganAktif,
            'riwayatKlaim' => $riwayatKlaim,
            'chart_bulan' => json_encode($bulan),
            'chart_gaji' => json_encode($gajiPerBulan),
        ]);
    }

    public function laporan_analitik()
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $periode = $this->request->getGet('periode') ?? date('Y-m');
        $selectedDepartemen = $this->request->getGet('departemen'); // sekarang ID

        $gajiQuery = $this->SalaryModel
            ->selectSum('salaries.total_gaji')
            ->join('users', 'users.id = salaries.user_id')
            ->like('salaries.periode', $periode);

        if (!empty($selectedDepartemen)) {
            $gajiQuery->where('users.departemen_id', $selectedDepartemen);
        }

        $totalGaji = $gajiQuery->first()['total_gaji'] ?? 0;


        $totalKlaim = $this->ClaimModel
            ->selectSum('claims.jumlah')
            ->join('users', 'users.id = claims.user_id')
            ->where('claims.status', 'approved')
            ->like('claims.submitted_at', $periode);

        if (!empty($selectedDepartemen)) {
            $totalKlaim->where('users.departemen_id', $selectedDepartemen);
        }
        $totalKlaim = $totalKlaim->first()['jumlah'] ?? 0;

        // Jumlah Tunjangan Aktif (semua)
        $jumlahTunjangan = $this->BenefitModel->where('is_active', 1)->countAllResults();

        // Grafik Gaji & Tunjangan 6 bulan terakhir
        $bulan = [];
        $gajiPerBulan = [];
        $tunjanganPerBulan = [];

        for ($i = 5; $i >= 0; $i--) {
            $periodeLoop = date('Y-m', strtotime("-$i months"));
            $bulan[] = date('M', strtotime($periodeLoop));

            $gajiQuery = $this->SalaryModel
                ->selectSum('salaries.total_gaji')
                ->join('users', 'users.id = salaries.user_id')
                ->like('salaries.periode', $periodeLoop);
            if (!empty($selectedDepartemen)) {
                $gajiQuery->where('users.departemen_id', $selectedDepartemen);
            }
            $gaji = $gajiQuery->first()['total_gaji'] ?? 0;

            $tunjanganQuery = $this->UserBenefitModel
                ->selectSum('user_benefit.jumlah')
                ->join('users', 'users.id = user_benefit.user_id')
                ->like('tanggal_mulai', $periodeLoop)
                ->where('user_benefit.status', 'aktif');

            if (!empty($selectedDepartemen)) {
                $tunjanganQuery->where('users.departemen_id', $selectedDepartemen);
            }

            $tunjangan = $tunjanganQuery->first()['jumlah'] ?? 0;

            $gajiPerBulan[] = (int)$gaji;
            $tunjanganPerBulan[] = (int)$tunjangan;
        }


        // Total Tunjangan Aktif Periode Terpilih
        $tunjanganTotalQuery = $this->UserBenefitModel
            ->selectSum('user_benefit.jumlah')
            ->join('users', 'users.id = user_benefit.user_id')
            ->like('tanggal_mulai', $periode)
            ->where('user_benefit.status', 'aktif');

        if (!empty($selectedDepartemen)) {
            $tunjanganTotalQuery->where('users.departemen_id', $selectedDepartemen);
        }

        $totalTunjangan = $tunjanganTotalQuery->first()['jumlah'] ?? 0;

        // Distribusi tunjangan
        $distribusiQuery = $this->UserBenefitModel
            ->select('benefits.kategori, COUNT(*) as total')
            ->join('benefits', 'benefits.id = user_benefit.benefit_id')
            ->join('users', 'users.id = user_benefit.user_id')
            ->where('user_benefit.status', 'aktif')
            ->groupBy('benefits.kategori');

        if (!empty($selectedDepartemen)) {
            $distribusiQuery->where('users.departemen_id', $selectedDepartemen);
        }

        $distribusi = $distribusiQuery->findAll();


        $chart_kategori = array_column($distribusi, 'kategori');
        $chart_total = array_column($distribusi, 'total');

        // Ambil daftar departemen dari tabel
        $departemenList = $this->DepartemenModel->findAll();

        // Ambil klaim (join user & departemen)
        $klaimQuery = $this->ClaimModel
            ->select('claims.*, users.nama_lengkap, departemen.nama_departemen')
            ->where('claims.status', 'approved')
            ->join('users', 'users.id = claims.user_id')
            ->join('departemen', 'departemen.id = users.departemen_id', 'left')
            ->like('submitted_at', $periode);

        if (!empty($selectedDepartemen)) {
            $klaimQuery->where('users.departemen_id', $selectedDepartemen);
        }

        $detailKlaim = $klaimQuery->orderBy('submitted_at', 'DESC')->findAll();

        return view('admin/Laporan_Analitik', [
            'title' => 'Laporan & Analitik',
            'periodeDipilih' => $periode,
            'totalGaji' => $totalGaji,
            'totalKlaim' => $totalKlaim,
            'jumlahTunjangan' => $jumlahTunjangan,
            'TotalTunjangan' => $totalTunjangan,
            'chart_bulan' => json_encode($bulan),
            'chart_gaji' => json_encode($gajiPerBulan),
            'chart_kategori' => json_encode($chart_kategori),
            'chart_total' => json_encode($chart_total),
            'chart_tunjangan' => json_encode($tunjanganPerBulan),
            'detailKlaim' => $detailKlaim,
            'departemenList' => $departemenList,
            'selectedDepartemen' => $selectedDepartemen
        ]);
    }


    public function detail_karyawan($id)
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

        $gaji = $this->SalaryModel
            ->where('user_id', $id)
            ->like('periode', date('Y-m'))
            ->first();

        $tunjanganAktif = $this->UserBenefitModel
            ->select('benefits.nama, benefits.kategori')
            ->join('benefits', 'benefits.id = user_benefit.benefit_id')
            ->where('user_benefit.user_id', $id)
            ->where('user_benefit.status', 'aktif')
            ->findAll();

        $riwayatKlaim = $this->ClaimModel
            ->where('user_id', $id)
            ->orderBy('submitted_at', 'DESC')
            ->findAll(5);

        $riwayatGaji = $this->SalaryModel
            ->where('user_id', $id)
            ->orderBy('periode', 'DESC')
            ->paginate(5);

        return view('admin/detail_karyawan', [
            'title' => 'Detail Karyawan',
            'karyawan' => $karyawan,
            'gaji' => $gaji,
            'tunjanganAktif' => $tunjanganAktif,
            'riwayatKlaim' => $riwayatKlaim,
            'profilePath' => $ProfilePath,
            'riwayatGaji' => $riwayatGaji,
            'pager' => $this->SalaryModel->pager
        ]);
    }

    public function riwayat_pembayaran()
    {
        $check = $this->checkLogin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $userId = session()->get('DataUser.id');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        $riwayatGaji = $this->SalaryModel
            ->where('user_id', $userId)
            ->like('periode', $tahun)
            ->orderBy('periode', 'DESC')
            ->findAll();


        $tahunList = $this->SalaryModel
            ->select("DISTINCT(SUBSTRING(periode, 1, 4)) as tahun")
            ->where('user_id', $userId)
            ->orderBy('tahun', 'DESC')
            ->findAll();

        $tahunList = array_column($tahunList, 'tahun');

        return view('authentication/riwayat_pembayaran', [
            'title' => 'Riwayat Pembayaran',
            'riwayatGaji' => $riwayatGaji,
            'tahunList' => $tahunList,
            'tahunDipilih' => $tahun
        ]);
    }

    public function form_pengajuan()
    {
        $check = $this->checkLogin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $userId = session()->get('DataUser.id');

        $benefits = $this->BenefitModel
            ->where('is_active', 1)
            ->findAll();

        $riwayatKlaim = $this->ClaimModel
            ->where('user_id', $userId)
            ->orderBy('submitted_at', 'DESC')
            ->findAll();

        return view('authentication/pengajuan_klaim', [
            'title' => 'Pengajuan Klaim',
            'riwayatKlaim' => $riwayatKlaim,
            'benefits' => $benefits
        ]);
    }

    public function ajukan()
    {
        $check = $this->checkLogin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'tipe_klaim' => 'required',
            'jumlah' => 'required|numeric|min_length[3]',
            'bukti' => 'uploaded[bukti]|max_size[bukti,2048]|ext_in[bukti,pdf,jpg,jpeg,png]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('error', $validation->getErrors());
        }



        $file = $this->request->getFile('bukti');
        $mime = mime_content_type($file->getTempName());
        $allowedMimes = ['application/pdf', 'image/jpeg', 'image/png'];
        if (!in_array($mime, $allowedMimes)) {
            return redirect()->back()->with('error', 'Tipe file tidak diizinkan.');
        }

        $fileName = $file->getRandomName();
        $file->move('uploads/klaim', $fileName);

        $data = [
            'user_id' => session()->get('DataUser.id'),
            'tipe_klaim' => $this->request->getPost('tipe_klaim'),
            'jumlah' => $this->request->getPost('jumlah'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'bukti' => $fileName,
            'status' => 'pending',
            'bukti_url' => 'uploads/klaim/' . $fileName,
            'submitted_at' => date('Y-m-d H:i:s')
        ];

        $this->ClaimModel->insert($data);
        return redirect()->to(base_url('PagesController/form_pengajuan'))->with('success', 'Klaim berhasil diajukan.');
    }

    public function update_aturan_perusahaan()
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $postData = $this->request->getPost('pengaturan');

        if ($postData && is_array($postData)) {
            $settingModel = $this->CompanySettingModel;

            foreach ($postData as $key => $value) {
                $settingModel
                    ->where('setting_key', $key)
                    ->set('setting_value', $value)
                    ->update();
            }

            return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Data tidak valid atau kosong.');
    }
}
