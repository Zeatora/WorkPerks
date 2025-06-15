<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\{SalaryModel, ClaimModel, BenefitModel, UserBenefitModel, DepartemenModel, UserModel};
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanController extends BaseController
{
    protected $SalaryModel;
    protected $ClaimModel;
    protected $BenefitModel;
    protected $UserBenefitModel;
    protected $DepartemenModel;

    public function __construct()
    {
        $this->SalaryModel = new SalaryModel();
        $this->ClaimModel = new ClaimModel();
        $this->BenefitModel = new BenefitModel();
        $this->UserBenefitModel = new UserBenefitModel();
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

    public function exportPdf()
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }

        $periode = $this->request->getGet('periode') ?? date('Y-m');
        $selectedDepartemen = $this->request->getGet('departemen');

        // Query klaim
        $klaimQuery = $this->ClaimModel
            ->select('claims.*, users.nama_lengkap, departemen.nama_departemen')
            ->join('users', 'users.id = claims.user_id')
            ->join('departemen', 'departemen.id = users.departemen_id', 'left')
            ->like('claims.submitted_at', $periode)
            ->where('claims.status', 'approved');

        if (!empty($selectedDepartemen)) {
            $klaimQuery->where('users.departemen_id', $selectedDepartemen);
        }

        $detailKlaim = $klaimQuery->findAll();

        // Tambahan statistik
        $totalKlaim = array_sum(array_column($detailKlaim, 'jumlah'));

        $totalGaji = $this->SalaryModel
            ->join('users', 'users.id = salaries.user_id')
            ->like('salaries.periode', $periode);

        if (!empty($selectedDepartemen)) {
            $totalGaji->where('users.departemen_id', $selectedDepartemen);
        }

        $totalGaji = $totalGaji->selectSum('salaries.total_gaji')->first()['total_gaji'] ?? 0;

        $totalTunjangan = $this->UserBenefitModel
            ->join('users', 'users.id = user_benefit.user_id')
            ->like('tanggal_mulai', $periode)
            ->where('user_benefit.status', 'aktif');

        if (!empty($selectedDepartemen)) {
            $totalTunjangan->where('users.departemen_id', $selectedDepartemen);
        }

        $totalTunjangan = $totalTunjangan->selectSum('user_benefit.jumlah')->first()['jumlah'] ?? 0;

        $html = view('admin/form_exports/laporan_pdf', [
            'periode' => $periode,
            'detailKlaim' => $detailKlaim,
            'totalKlaim' => $totalKlaim,
            'totalGaji' => $totalGaji,
            'totalTunjangan' => $totalTunjangan
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("laporan_klaim_{$periode}.pdf", ['Attachment' => false]);
    }


    public function exportExcel()
    {
        $check = $this->CheckAdmin();
        if ($check instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $check;
        }
        $periode = $this->request->getGet('periode') ?? date('Y-m');
        $selectedDepartemen = $this->request->getGet('departemen');

        // Ambil data klaim
        $klaimQuery = $this->ClaimModel
            ->select('claims.*, users.nama_lengkap, departemen.nama_departemen')
            ->where('claims.status', 'approved')
            ->join('users', 'users.id = claims.user_id')
            ->join('departemen', 'departemen.id = users.departemen_id', 'left')
            ->like('claims.submitted_at', $periode);

        if (!empty($selectedDepartemen)) {
            $klaimQuery->where('users.departemen_id', $selectedDepartemen);
        }

        $detailKlaim = $klaimQuery->findAll();

        // Tambahan data ringkasan
        $totalKlaim = array_sum(array_column($detailKlaim, 'jumlah'));

        $totalGaji = $this->SalaryModel
            ->join('users', 'users.id = salaries.user_id')
            ->like('salaries.periode', $periode);
        if (!empty($selectedDepartemen)) {
            $totalGaji->where('users.departemen_id', $selectedDepartemen);
        }
        $totalGaji = $totalGaji->selectSum('salaries.total_gaji')->first()['total_gaji'] ?? 0;

        $totalTunjangan = $this->UserBenefitModel
            ->join('users', 'users.id = user_benefit.user_id')
            ->like('tanggal_mulai', $periode)
            ->where('user_benefit.status', 'aktif');
        if (!empty($selectedDepartemen)) {
            $totalTunjangan->where('users.departemen_id', $selectedDepartemen);
        }
        $totalTunjangan = $totalTunjangan->selectSum('user_benefit.jumlah')->first()['jumlah'] ?? 0;

        // Mulai Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header laporan
        $sheet->setCellValue('A1', 'LAPORAN KLAIM KARYAWAN');
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A2', 'Periode: ' . $periode);
        $sheet->mergeCells('A2:F2');

        // Ringkasan
        $sheet->setCellValue('A4', 'Total Gaji');
        $sheet->setCellValue('B4', $totalGaji);
        $sheet->setCellValue('A5', 'Total Klaim');
        $sheet->setCellValue('B5', $totalKlaim);
        $sheet->setCellValue('A6', 'Total Tunjangan');
        $sheet->setCellValue('B6', $totalTunjangan);

        // Spacer
        $startRow = 8;
        $sheet->fromArray(
            ['Nama', 'Departemen', 'Jenis Klaim', 'Jumlah', 'Status', 'Tanggal'],
            NULL,
            "A{$startRow}"
        );

        $row = $startRow + 1;
        foreach ($detailKlaim as $klaim) {
            $sheet->setCellValue("A{$row}", $klaim['nama_lengkap']);
            $sheet->setCellValue("B{$row}", $klaim['nama_departemen']);
            $sheet->setCellValue("C{$row}", $klaim['tipe_klaim']);
            $sheet->setCellValue("D{$row}", $klaim['jumlah']);
            $sheet->setCellValue("E{$row}", ucfirst($klaim['status']));
            $sheet->setCellValue("F{$row}", $klaim['submitted_at']);
            $row++;
        }

        // Style otomatis lebar kolom
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Simpan output
        $filename = "laporan_klaim_{$periode}.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }


    public function cetak($id)
    {
        $gaji = $this->SalaryModel
            ->join('users', 'users.id = salaries.user_id')
            ->select('salaries.*, users.nama_lengkap, users.username, users.email')
            ->where('salaries.id', $id)
            ->first();


        if (!$gaji) {
            return redirect()->back()->with('error', 'Data gaji tidak ditemukan.');
        }

        $data = [
            'gaji' => $gaji,
            'title' => 'Slip Gaji - ' . $gaji['nama_lengkap']
        ];

        return view('authentication/form_laporan/slip_gaji', $data);
    }
}
