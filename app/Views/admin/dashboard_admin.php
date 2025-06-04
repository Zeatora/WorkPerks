<?= $this->extend('layout/templateOtherPages') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-4">Dashboard Admin - WorkPerks</h2>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Karyawan</h5>
                    <p class="card-text display-6"><?php echo $TotalKaryawan ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Gaji Bulan Ini</h5>
                    <p class="card-text display-6"><?php echo $TotalGaji ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tunjangan Aktif</h5>
                    <p class="card-text display-6"><?php echo $TunjanganAktif ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Klaim Pending</h5>
                    <p class="card-text display-6"><?php echo $KlaimPending ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tren Pengeluaran Gaji & Tunjangan</div>
                <div class="card-body">
                    <canvas id="salaryChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Distribusi Tunjangan</div>
                <div class="card-body">
                    <canvas id="benefitChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Claims Table -->
    <div class="row mt-4">
        <div class="col">
            <div class="card">
                <div class="card-header">Klaim Terbaru</div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Jenis Klaim</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ListPendingClaims as $claim): ?>
                                <tr>
                                    <td><?php echo $claim['nama_karyawan']; ?></td>
                                    <td><?php echo $claim['tipe_klaim']; ?></td>
                                    <td><?php echo number_format($claim['jumlah'], 0, ',', '.'); ?></td>
                                    <td><?php echo ucfirst($claim['status']); ?></td>
                                    <td>
                                        <a href="<?php echo base_url('claims/view/' . $claim['id']); ?>" class="btn btn-info btn-sm">Lihat</a>
                                        <?php if ($claim['status'] == 'pending'): ?>
                                            <a href="<?php echo base_url('claims/approve/' . $claim['id']); ?>" class="btn btn-success btn-sm">Setujui</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($ListPendingClaims)): ?>
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada klaim terbaru</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx1 = document.getElementById('salaryChart').getContext('2d');
    const salaryChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: <?= $chart_bulan ?>,
            datasets: [{
                label: 'Total Gaji',
                data: <?= $chart_gaji ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true
            }]
        }
    });

    const ctx2 = document.getElementById('benefitChart').getContext('2d');
    const benefitChart = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: <?= $chart_kategori ?>,
            datasets: [{
                label: 'Distribusi Tunjangan',
                data: <?= $chart_total ?>,
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545']
            }]
        }
    });
</script>

<?= $this->endSection() ?>
