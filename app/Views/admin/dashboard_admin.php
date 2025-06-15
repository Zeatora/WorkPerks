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
            <div class="card text-white bg-dark mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Departemen</h5>
                    <p class="card-text display-6"><?php echo $TotalDepartemen ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Gaji Bulan Ini</h5>
                    <?php echo 'Rp' . number_format($TotalGaji, 0, ',', '.') ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tunjangan Bulan Ini</h5>
                    <?php echo 'Rp' . number_format($TotalTunjangan, 0, ',', '.') ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tunjangan Aktif</h5>
                    <p class="card-text display-6"><?php echo $TunjanganAktif ?> Tipe</p>
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
                                        <a href="<?= base_url('ClaimsController/lihat_bukti/' . $claim['id']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">Lihat Bukti</a>
                                        <?php if ($claim['status'] == 'pending'): ?>
                                            <a href="<?php echo base_url('ClaimsController/reject_klaim/' . $claim['id']); ?>" class="btn btn-danger btn-sm">Tolak</a>
                                            <a href="#"
                                                class="btn btn-success btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#approveModal<?= $claim['id'] ?>">
                                                Setujui
                                            </a>

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
                    <?= $pager->links('default', 'bootstrap') ?>
                    <?php foreach ($ListPendingClaims as $claim): ?>
                        <div class="modal fade" id="approveModal<?= $claim['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="<?= base_url('ClaimsController/approve_klaim/' . $claim['id']) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Setujui Klaim - <?= esc($claim['nama_karyawan']) ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Jenis:</strong> <?= esc($claim['tipe_klaim']) ?></p>
                                            <p><strong>Jumlah:</strong> Rp<?= number_format($claim['jumlah'], 0, ',', '.') ?></p>

                                            <div class="mb-3">
                                                <label for="start_date" class="form-label">Tanggal Mulai Tunjangan</label>
                                                <input type="date" name="start_date" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="end_date" class="form-label">Tanggal Berakhir (opsional)</label>
                                                <input type="date" name="end_date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Setujui & Aktifkan</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
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
            datasets: [

            {
                label: 'Total Gaji',
                data: <?= $chart_gaji ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true
            }
            , {
                label: 'Total Tunjangan',
                data: <?= $chart_tunjangan ?>,
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 2,
                fill: true
            }
        ]
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
                backgroundColor: ['#28a745', '#007bff', '#ffc107', '#dc3545']
            }]
        }
    });
</script>

<?= $this->endSection() ?>