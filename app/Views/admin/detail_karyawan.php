<?= $this->extend('layout/templateOtherPages') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <h2 class="mb-4">Detail Karyawan</h2>

    <!-- Info Utama -->
    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex flex-column flex-md-row align-items-center justify-content-between">
            <div class="d-flex align-items-center mb-3 mb-md-0">
                <img
                    id="preview"
                    src="<?= base_url(file_exists(FCPATH . $profilePath) ? $profilePath : 'uploads/foto/default_profile.jpg') ?>"
                    alt="Foto Profil"
                    class="rounded-circle me-4 border mr-3"
                    width="80"
                    height="80"
                    onerror="this.src='<?= base_url('uploads/profile/default_profile.jpg') ?>'">
                <div>
                    <h4 class="mb-0"><?= esc($karyawan['nama_lengkap']) ?>
                        <span class="badge <?= $karyawan['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?> text-light p-2">
                            <?= ucfirst($karyawan['status']) ?>
                        </span>
                    </h4>
                    <p class="mb-0 text-muted"><?= esc($karyawan['nama_departemen']) ?></p>

                </div>

            </div>
            <div class="ms-auto">
                <a href="<?= base_url('KelolaKaryawanController/edit/' . $karyawan['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#modalResetPassword">
                    Reset Password
                </button>
                <a href="<?= base_url('KelolaKaryawanController/delete/' . $karyawan['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus karyawan ini?')">Hapus</a>
            </div>
        </div>
    </div>

    <!-- Informasi Pribadi -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light fw-bold">Informasi Pribadi</div>
                <div class="card-body">
                    <p><strong>Email:</strong> <?= esc($karyawan['email']) ?></p>
                    <p><strong>Username:</strong> <?= esc($karyawan['username']) ?></p>
                    <p><strong>Departemen:</strong> <?= esc($karyawan['nama_departemen']) ?></p>
                    <p><strong>Status:</strong> <?= ucfirst($karyawan['status']) ?></p>
                </div>
            </div>
        </div>

        <!-- Gaji Terakhir -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light fw-bold">Gaji Bulan Ini</div>
                <div class="card-body">
                    <?php if ($gaji): ?>
                        <p><strong>Periode:</strong> <?= esc($gaji['periode']) ?></p>
                        <p><strong>Gaji Pokok:</strong> Rp<?= number_format($gaji['gaji_pokok'], 0, ',', '.') ?></p>
                        <p><strong>Bonus:</strong> Rp<?= number_format($gaji['bonus'], 0, ',', '.') ?></p>
                        <p><strong>Insentif:</strong> Rp<?= number_format($gaji['insentif_kinerja'], 0, ',', '.') ?></p>
                        <hr>
                        <h5>Total: Rp<?= number_format($gaji['total_gaji'], 0, ',', '.') ?></h5>
                    <?php else: ?>
                        <p class="text-muted">Belum ada data gaji.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>


    <!-- Tunjangan Aktif -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-success text-white">Tunjangan Aktif</div>
        <div class="card-body">
            <?php if (!empty($tunjanganAktif)): ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($tunjanganAktif as $t): ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <?= esc($t['nama']) ?>
                            <span class="badge bg-success text-light p-2"><?= esc($t['kategori']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">Tidak ada tunjangan aktif.</p>
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
    <!-- Riwayat Klaim -->
    <div class="card shadow-sm">
        <div class="card-header bg-warning">Riwayat Klaim Terakhir</div>
        <div class="card-body">
            <?php if (!empty($riwayatKlaim)): ?>
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($riwayatKlaim as $klaim): ?>
                            <tr>
                                <td><?= esc($klaim['tipe_klaim']) ?></td>
                                <td>Rp<?= number_format($klaim['jumlah'], 0, ',', '.') ?></td>
                                <td><span class="badge bg-<?= $klaim['status'] === 'approved' ? 'success' : ($klaim['status'] === 'pending' ? 'warning text-dark' : 'danger') ?> text-light p-2">
                                        <?= ucfirst($klaim['status']) ?>
                                    </span></td>
                                <td><?= date('d M Y', strtotime($klaim['submitted_at'])) ?></td>
                                <td>
                                    <a href="<?= base_url('ClaimsController/lihat_bukti/' . $klaim['id']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">Lihat Bukti</a>
                                    <?php if ($klaim['status'] === 'pending'): ?>
                                        <a href="<?php echo base_url('ClaimsController/reject_klaim/' . $klaim['id']); ?>" class="btn btn-danger btn-sm">Tolak</a>
                                        <a href="<?php echo base_url('ClaimsController/approve_klaim/' . $klaim['id']); ?>" class="btn btn-success btn-sm">Setujui</a>
                                    <?php endif; ?>
                                </td>
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

<!-- Modal Reset Password -->
<div class="modal fade" id="modalResetPassword" tabindex="-1" aria-labelledby="modalResetPasswordLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form action="<?= base_url('KelolaKaryawanController/reset_password_admin') ?>" method="post" class="modal-content">
      <?= csrf_field() ?>
      <input type="hidden" name="user_id" value="<?= $karyawan['id'] ?>">

      <div class="modal-header">
        <h5 class="modal-title" id="modalResetPasswordLabel">Reset Password Karyawan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label for="new_password" class="form-label">Password Baru</label>
          <input type="password" name="new_password" id="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="confirm_password" class="form-label">Konfirmasi Password</label>
          <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Reset Password</button>
      </div>
    </form>
  </div>
</div>


<?= $this->endSection() ?>