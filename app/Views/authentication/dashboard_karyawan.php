<?= $this->extend('layout/templateOtherPages') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <h2 class="mb-4">Dashboard Karyawan</h2>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <h6>Gaji Bulan ini</h6>
                    <h4>
                        <?php if (!empty($gajiTerbaru)): ?>
                            Rp<?= number_format($gajiTerbaru['total_gaji'], 0, ',', '.') ?>
                        <?php else: ?>
                            <span class="text-light">Belum ada data</span>
                        <?php endif; ?>
                    </h4>

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h6>Tunjangan Aktif</h6>
                    <h4><?= $jumlahTunjangan ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info shadow-sm">
                <div class="card-body">
                    <h6>Klaim Disetujui</h6>
                    <h4><?= $klaimDisetujui ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body">
                    <h6>Klaim Pending</h6>
                    <h4><?= $klaimPending ?></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Gaji -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light fw-bold">Tren Gaji 6 Bulan Terakhir</div>
        <div class="card-body">
            <canvas id="gajiChart"></canvas>
        </div>
    </div>

    <!-- Gaji Terbaru -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-light fw-bold">Gaji Bulan Ini</div>
        <div class="card-body">
            <?php if (!empty($gajiTerbaru)): ?>
                <p><strong>Periode:</strong> <?= esc(date('F Y', strtotime($gajiTerbaru['periode']))) ?></p>
                <p><strong>Gaji Pokok:</strong> Rp<?= number_format($gajiTerbaru['gaji_pokok'], 0, ',', '.') ?></p>
                <p><strong>Bonus:</strong> Rp<?= number_format($gajiTerbaru['bonus'], 0, ',', '.') ?></p>
                <p><strong>Insentif Kinerja:</strong> Rp<?= number_format($gajiTerbaru['insentif_kinerja'], 0, ',', '.') ?></p>
                <hr>
                <h5>Total: Rp<?= number_format($gajiTerbaru['total_gaji'], 0, ',', '.') ?></h5>
            <?php else: ?>
                <p class="text-muted">Belum ada data gaji bulan ini.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Tunjangan Aktif -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-success text-light fw-bold">Tunjangan Aktif</div>
        <div class="card-body">
            <?php if (!empty($tunjanganAktif)): ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($tunjanganAktif as $t): ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <?= esc($t['nama']) ?>
                            <span class="badge bg-success text-light p-2"><?= ucfirst($t['kategori']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">Belum ada tunjangan aktif.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Riwayat Klaim -->
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-light fw-bold">Riwayat Klaim Terakhir</div>
        <div class="card-body">
            <?php if (!empty($riwayatKlaim)): ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($riwayatKlaim as $k): ?>
                            <tr>
                                <td><?= esc($k['tipe_klaim']) ?></td>
                                <td>Rp<?= number_format($k['jumlah'], 0, ',', '.') ?></td>
                                <td>
                                    <span class="badge <?= match ($k['status']) {
                                                            'approved' => 'bg-success',
                                                            'pending' => 'bg-warning text-dark',
                                                            'rejected' => 'bg-danger',
                                                            default => 'bg-secondary'
                                                        } ?> text-light p-2">
                                        <?= ucfirst($k['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('d M Y', strtotime($k['submitted_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">Belum ada riwayat klaim.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('gajiChart').getContext('2d');
    const gajiChart = new Chart(ctx, {
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
            }]
        }
    });
</script>

<?= $this->endSection() ?>