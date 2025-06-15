<?= $this->extend('layout/templateOtherPages') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <h2 class="mb-4">Laporan & Analitik</h2>

    <!-- Filter Waktu -->
    <form method="get" class="row mb-4">
        <div class="col-md-3">
            <label for="bulan" class="form-label">Periode</label>
            <input type="month" name="periode" class="form-control" value="<?= esc($periodeDipilih) ?>">
        </div>
        <div class="col-md-4">
            <label for="departemen" class="form-label">Filter Departemen</label>
            <select name="departemen" id="departemen" class="form-select">
                <option value="">-- Semua Departemen --</option>
                <?php foreach ($departemenList as $dep): ?>
                    <option value="<?= $dep['id'] ?>" <?= $selectedDepartemen == $dep['id'] ? 'selected' : '' ?>>
                        <?= esc($dep['nama_departemen']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 align-self-end">
            <button class="btn btn-secondary">Terapkan</button>
        </div>
    </form>


    <!-- Statistik Ringkas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <h6>Total Gaji</h6>
                    <h4>Rp<?= number_format($totalGaji, 0, ',', '.') ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h6>Total Klaim</h6>
                    <h4>Rp<?= number_format($totalKlaim, 0, ',', '.') ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info shadow-sm">
                <div class="card-body">
                    <h6>Tunjangan Aktif</h6>
                    <h4><?= $jumlahTunjangan ?> Tipe</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3 d-flex justify-content-end gap-2">
        <a href="<?= base_url('LaporanController/exportPdf/pdf?periode=' . $periodeDipilih . ($selectedDepartemen ? '&departemen=' . $selectedDepartemen : '')) ?>" class="btn btn-danger btn-sm" target="_blank">
            <i class="bi bi-file-earmark-pdf"></i> Export PDF
        </a>
        <a href="<?= base_url('LaporanController/exportExcel/excel?periode=' . $periodeDipilih . ($selectedDepartemen ? '&departemen=' . $selectedDepartemen : '')) ?>" class="btn btn-success btn-sm ml-2" target="_blank">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </a>
    </div>
    <!-- Grafik -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">Tren Pengeluaran Gaji</div>
                <div class="card-body">
                    <canvas id="gajiChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header">Distribusi Tunjangan</div>
                <div class="card-body">
                    <canvas id="tunjanganChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Klaim Detail -->

    <div class="card shadow-sm">
        <div class="card-header">Detail Klaim Bulan Ini</div>
        <div class="card-body">
            <?php if (!empty($detailKlaim)): ?>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jenis Klaim</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detailKlaim as $klaim): ?>
                            <tr>
                                <td><?= esc($klaim['nama_lengkap']) ?></td>
                                <td><?= esc($klaim['tipe_klaim']) ?></td>
                                <td>Rp<?= number_format($klaim['jumlah'], 0, ',', '.') ?></td>
                                <td><?= ucfirst($klaim['status']) ?></td>
                                <td><?= date('d M Y', strtotime($klaim['submitted_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">Tidak ada data klaim untuk periode ini.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const gajiChart = new Chart(document.getElementById('gajiChart'), {
        type: 'line',
        data: {
            labels: <?= $chart_bulan ?>,
            datasets: [{
                    label: 'Total Gaji',
                    data: <?= $chart_gaji ?>,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0,123,255,0.1)',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Total Tunjangan',
                    data: <?= $chart_tunjangan ?>,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220,53,69,0.1)',
                    fill: true,
                    tension: 0.3
                }
            ]
        }
    });

    const tunjanganChart = new Chart(document.getElementById('tunjanganChart'), {
        type: 'pie',
        data: {
            labels: <?= $chart_kategori ?>,
            datasets: [{
                data: <?= $chart_total ?>,
                backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545']
            }]
        }
    });
</script>

<?= $this->endSection() ?>