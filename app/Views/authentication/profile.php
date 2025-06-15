<?= $this->extend('layout/templateOtherPages') ?>
<?= $this->section('content') ?>

<?php
$session = session();
$user = $session->get('DataUser');
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <!-- Header -->
            <!-- Profile Header -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-body d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <div class="d-flex align-items-center mb-3 mb-md-0">
                        <img
                            id="preview"
                            src="<?= base_url(file_exists(FCPATH . $profilePath) ? $profilePath : 'uploads/foto/default_profile.jpg') ?>"
                            alt="Foto Profil"
                            class="rounded-circle border shadow-sm me-3 mr-3"
                            width="100"
                            height="100"
                            onerror="this.src='<?= base_url('uploads/profile/default_profile.jpg') ?>'">

                        <div>
                            <h3 class="mb-0"><?= esc($user['nama_lengkap']) ?></h3>
                            <div class="text-muted small">
                                <?= ucfirst($user['role']) ?> <?= isset($user['jabatan']) ? '| ' . esc($user['jabatan']) : '' ?>
                            </div>
                            <span class="badge <?= ($user['status'] === 'active') ? 'bg-success' : 'bg-secondary' ?> mt-1 text-light p-2">
                                <?= ucfirst($user['status']) ?>
                            </span>
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="<?= base_url('KelolaKaryawanController/edit_karyawan/' . $user['id']) ?>" class="btn btn-outline-primary me-2">
                            <i class="bi bi-pencil-square"></i> Edit Profil
                        </a>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalResetPassword">
                            <i class="bi bi-key"></i> Reset Password
                        </button>

                    </div>
                </div>
            </div>


            <!-- Info Grid -->
            <div class="row">
                <!-- Personal Info -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light fw-bold">Informasi Pribadi</div>
                        <div class="card-body">
                            <p><strong>Nama:</strong> <?= esc($user['nama_lengkap']) ?></p>
                            <p><strong>Email:</strong> <?= esc($user['email']) ?></p>
                            <p><strong>Username:</strong> <?= esc($user['username']) ?></p>
                            <p><strong>Departemen:</strong> <?= esc($karyawan['nama_departemen'] ?? '-') ?></p>
                            <p><strong>Jabatan:</strong> <?= esc($user['jabatan'] ?? '-') ?></p>
                        </div>
                    </div>
                </div>

                <!-- Akun Info -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light fw-bold">Status Akun</div>
                        <div class="card-body">
                            <p><strong>Role:</strong> <?= ucfirst($user['role']) ?></p>
                            <p><strong>Status:</strong>
                                <span class="badge <?= $user['status'] === 'active' ? 'bg-success' : 'bg-danger' ?> text-light p-2">
                                    <?= ucfirst($user['status']) ?>
                                </span>
                            </p>
                            <p><strong>Login Terakhir:</strong> <?= esc($user['login_terakhir'] ?? '-') ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gaji Terbaru -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white fw-bold">Gaji Bulan Ini</div>
                <div class="card-body">
                    <?php if (!empty($gajiTerbaru)): ?>
                        <p><strong>Periode:</strong> <?= esc(date('F Y', strtotime($gajiTerbaru['periode']))) ?></p>
                        <p><strong>Gaji Pokok:</strong> Rp<?= number_format($gajiTerbaru['gaji_pokok'], 0, ',', '.') ?></p>
                        <p><strong>Bonus:</strong> Rp<?= number_format($gajiTerbaru['bonus'], 0, ',', '.') ?></p>
                        <p><strong>Insentif Kinerja:</strong> Rp<?= number_format($gajiTerbaru['insentif_kinerja'], 0, ',', '.') ?></p>
                        <hr>
                        <h5>Total: Rp<?= number_format($gajiTerbaru['total_gaji'], 0, ',', '.') ?></h5>
                    <?php else: ?>
                        <p class="text-muted">Data gaji belum tersedia.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-light">Riwayat Gaji</div>
                <div class="card-body">
                    <?php if (!empty($riwayatGaji)): ?>
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Periode</th>
                                    <th>Gaji Pokok</th>
                                    <th>Bonus</th>
                                    <th>Insentif</th>
                                    <th>Total Gaji</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($riwayatGaji as $gaji): ?>
                                    <tr>
                                        <td><?= esc($gaji['periode']) ?></td>
                                        <td>Rp<?= number_format($gaji['gaji_pokok'], 0, ',', '.') ?></td>
                                        <td>Rp<?= number_format($gaji['bonus'], 0, ',', '.') ?></td>
                                        <td>Rp<?= number_format($gaji['insentif_kinerja'], 0, ',', '.') ?></td>
                                        <td>Rp<?= number_format($gaji['total_gaji'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?= $pager->links('default', 'bootstrap') ?>
                    <?php else: ?>
                        <p class="text-muted">Belum ada riwayat gaji.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tunjangan Aktif -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-success text-white fw-bold">Tunjangan Aktif</div>
                <div class="card-body">
                    <?php if (!empty($tunjanganAktif)): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($tunjanganAktif as $t): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
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
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-warning text-light fw-bold">Riwayat Klaim Terakhir</div>
                <div class="card-body">
                    <?php if (!empty($riwayatKlaim)): ?>
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Jenis</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($riwayatKlaim as $klaim): ?>
                                    <tr>
                                        <td><?= esc($klaim['tipe_klaim']) ?></td>
                                        <td>Rp<?= number_format($klaim['jumlah'], 0, ',', '.') ?></td>
                                        <td>
                                            <span class="badge <?= match ($klaim['status']) {
                                                                    'approved' => 'bg-success',
                                                                    'pending' => 'bg-warning text-dark',
                                                                    'rejected' => 'bg-danger',
                                                                    default => 'bg-secondary'
                                                                } ?> text-light p-2">
                                                <?= ucfirst($klaim['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('d M Y', strtotime($klaim['submitted_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-muted">Belum ada klaim yang diajukan.</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalResetPassword" tabindex="-1" aria-labelledby="modalResetPasswordLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('KelolaKaryawanController/reset_password') ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalResetPasswordLabel"><i class="bi bi-shield-lock"></i> Reset Password</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin me-reset password akun Anda?</p>
                    <div class="form-group">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <input type="text" class="form-control" name="new_password" id="new_password" placeholder="Masukkan password baru" required>
                        <small class="text-muted">Password ini akan langsung menggantikan password lama.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Reset Password</button>
                </div>
            </div>
        </form>
    </div>
</div>


<?= $this->endSection() ?>